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
    public function show()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function store(Request $request)
    {        
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->color = $request->input('color');
        $tag->save();
        return response()->json($tag, 201);
    }

    public function annotation($annotation_id)
    {
        $tags = Tag::whereHas('annotations', function ($query) use ($annotation_id) {
            $query->where('image_annotation_id', $annotation_id);
        })
        ->get()
        ->map(function ($tag) use ($annotation_id) {
            $annotation = $tag->annotations()->where('image_annotation_id', $annotation_id)->first();
            $tag->pivot = $annotation ? $annotation->pivot->value : null;
            return $tag;
        });
        return response()->json($tags);
    }

    public function attach(Request $request, $annotation_id)
    {
        $tag = Tag::find($request->input('tag_id'));
        
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 404);
        }

        $tag->annotations()->attach($annotation_id, ['value' => null]);

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

    public function updateTag(Request $request, $annotation_id)
    {
        $tag = Tag::findOrFail($request->input('tag_id'));

        $tag->annotations()->syncWithoutDetaching([
            $annotation_id => ['value' => $request->input('value')],
        ]);

        return response()->json(['message' => 'Value updated successfully']);
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

    public function importTags(TagImport $request) {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $file = $request->file(key: 'file');
        $content = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $tags = array_map(function ($line) {
            $line = trim($line);
            return filter_var($line, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        }, $content);

        $tags = array_unique($tags);
        
        if (empty($tags)) {
            return response()->json(['message' => 'Nothin to import']);
        }

        try {
            DB::transaction(function() use ($tags) {
                $existing = Tag::whereIn('name', $tags)->pluck('name')->toArray();

                $newTags = array_diff($tags, $existing);

                if (empty($newTags)) {
                    return;
                }

                $now = now();

                $insertData = array_map(fn($name) => [
                    'name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'color' => $this->randomRgbCode()
                ], $newTags);
                
                Tag::insert($insertData);
            });
        } catch (Throwable $e) {
            return response()->json(['error' => 'Error during tag import'], 500);
        }

        return response()->json(['message' => 'Import success']);
    }

    private function randomRgbCode(): string {
        return sprintf('%02x%02x%02x', rand(0, 255), rand(0, 255), rand(0, 255));
    }
}
