<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Api;

use Illuminate\Http\Request;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\AnnotationTags\Tag;
use Biigle\Modules\AnnotationTags\Http\Requests\UpdateTag;
use Biigle\Modules\AnnotationTags\Http\Requests\TagImport;

use DB;
use Throwable;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::with('group')->get();

        return response()->json($tags);
    }

    public function store(Request $request)
    {        
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->value = $request->input('value');
        $tag->color = $request->input('color');
        $tag->save();
        $tag->labels()->sync($request->input('label_ids', []));

        return response()->json($tag, 201);
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
        $tag->value = $request->input('value', null);
        $tag->color = $request->input('color', $tag->color);
        $tag->labels()->sync($request->input('label_ids', []));

        $tag->save();

        return response()->json($tag);
    }

    public function annotation($annotation_id)
    {
        $tags = Tag::whereHas('annotations', function ($query) use ($annotation_id) {
            $query->where('image_annotation_id', $annotation_id);
        })
        ->get();
        return response()->json($tags);
    }

    public function updateRelation(Request $request, $annotation_id)
    {
        DB::table('tag_annotations')
            ->where('image_annotation_id', $annotation_id)
            ->delete();

        foreach ($request->tags as $tagId) {
            DB::table('tag_annotations')->insert([
                'image_annotation_id' => $annotation_id,
                'tag_id' => $tagId,
            ]);
        }

        return response()->json(['message' => 'Relation updated successfully']);
    }

    public function importTags(TagImport $request) {
        $file = $request->file('file');

        $path = $file->getRealPath();

        
        if (($handle = fopen($path, 'r')) !== false) {
            $header = null;
            while(($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }

                $data = array_combine($header, $row);
                $data = array_map(fn($v) => $v === '' ? null : $v, $data);

                Tag::updateOrCreate([
                    'name' => $data['name'],
                    'value' => $data['value'] ?? null,
                    'color' => $this->randomRgbCode(),
                ]);
            }
            fclose($handle);
        }

        return response()->json(['message' => 'Import successfull']);
    }

    private function randomRgbCode(): string {
        return sprintf('%02x%02x%02x', rand(0, 255), rand(0, 255), rand(0, 255));
    }
}
