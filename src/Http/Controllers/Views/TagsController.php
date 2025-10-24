<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Views;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\AnnotationTags\Tag;

class TagsController extends Controller
{
    public function index(){
        $tags = Tag::all();
        return view('annotation-tags::index', [
            'tags' => $tags
        ]);
    }
}
