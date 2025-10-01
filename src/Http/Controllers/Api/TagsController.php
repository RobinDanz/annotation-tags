<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Api;

use Illuminate\Http\Request;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\AnnotationTags\Tag;
use Biigle\Modules\AnnotationTags\Http\Requests\UpdateTag;
use Biigle\Modules\AnnotationTags\Http\Requests\TagReport;
use Biigle\Modules\AnnotationTags\Jobs\TestJob;

use Biigle\Http\Requests\StoreProjectReport;
use Biigle\Report;

class TagsController extends Controller
{

    /**
     * Shows the quotes page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function test2(StoreProjectReport $request, $id)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln($request->input('type_id'));
        $report = new Report;
        $report->source()->associate($request->project);
        $report->type_id = $request->input('type_id');
        $report->user()->associate($request->user());
        $report->options = $request->getOptions();
        // $report->save();

        // $queue = config('reports.generate_report_queue');
        //GenerateReportJob::dispatch($report)->onQueue($queue);

        if ($this->isAutomatedRequest()) {
            return $report;
        }
    }

    public function test(TagReport $request)
    {   
        

        $file = $request->file(key: 'file');
        $content = file_get_contents($file->getRealPath());

        $js = json_decode($content, true);

        if(json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $tags = Tag::with(['annotations' => function ($query) {
            $query->select('image_annotation_id');
        }])->get()->toArray();
        
        TestJob::dispatch($js, $tags)->onQueue(config('annotation_tags.annotation_tags_queue'));

        return response()->json([]);
    }

    public function store(Request $request)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->color = $request->input('color');
        $tag->save();
        $output->writeln('Tag created: ' . $tag->name);
        return response()->json($tag, 201);
    }

    public function annotation($annotation_id)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $tags = Tag::whereHas('annotations', function ($query) use ($annotation_id) {
            $query->where('image_annotation_id', $annotation_id);
        })->get();
        $output->writeln($tags->count());
        return response()->json($tags);
    }

    public function attach(Request $request, $annotation_id)
    {
        $tag = Tag::find($request->input('tag_id'));
        
        
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        $tag->annotations()->attach($annotation_id, ['value' => 0]);
        return response()->json(['message' => 'Tag attached successfully']);
    }

    public function detach(Request $request, $annotation_id)
    {
        $tag = Tag::find($request->input('tag_id'));
        
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }


        $tag->annotations()->detach($annotation_id);
        return response()->json(['message' => 'Tag detached successfully']);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        $tag->delete();
        return response()->json(['message' => 'Tag deleted successfully']);
    }

    public function update(UpdateTag $request)
    {
        $tag = $request->tag;
        $tag->name = $request->input('name', $tag->name);
        $tag->color = $request->input('color', $tag->color);

        $tag->save();

        return response()->json($tag);
    }
}
