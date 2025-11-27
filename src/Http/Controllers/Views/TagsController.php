<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Views;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\AnnotationTags\Tag;
use Biigle\Label;
use Biigle\Modules\AnnotationTags\TagGroup;

class TagsController extends Controller
{
    public function index(){
        $tags = Tag::all();

        $labels = Label::all();

        $groups = TagGroup::with('tags:id,tag_group_id')->get()
        ->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'tags' => $group->tags->pluck('id'),
            ];
        });
        
        return view('annotation-tags::index', [
            'tags' => $tags, 
            'labels' => $labels,
            'groups' => $groups,
        ]);
    }
}
