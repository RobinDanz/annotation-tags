<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Api;

use Biigle\Modules\AnnotationTags\TagGroup;
use Biigle\Modules\AnnotationTags\Tag;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\AnnotationTags\Http\Requests\StoreTagGroup;
use Biigle\Modules\AnnotationTags\Http\Requests\UpdateTagGroup;

use DB;
use Throwable;

class TagGroupController extends Controller
{
    public function index()
    {
        $groups = TagGroup::all();
        return response()->json($groups);
    }

    public function store(StoreTagGroup $request)
    {
        $group = new TagGroup();

        $group->name = $request->input('name');

        $group->save();


        if (!empty($request->input('tags'))) {
            Tag::whereIn('id', $request->input('tags'))
                ->update(['tag_group_id' => $group->id]);
        }

        return response()->json($group->load('tags'));
    }

    public function update(UpdateTagGroup $request)
    {
        $group = $request->tagGroup;

        $group->name = $request->input('name', $group->name);

        $tags = $request->input('tags', $group->tags);

        Tag::where('tag_group_id', $group->id)->update(['tag_group_id' => null]);

        Tag::whereIn('id', $tags)->update(['tag_group_id' => $group->id]);

        $group->save();

        return response()->json($group->load('tags'));
    }

    public function destroy($id)
    {
        $group = TagGroup::find($id);
        
        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        $group->delete();
        return response()->json(['message' => 'group deleted successfully']);
    }
}