<?php

namespace Biigle\Modules\AnnotationTags\Http\Controllers\Views;

use Illuminate\Http\Request;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\Modules\AnnotationTags\Tag;

class TagsController extends Controller
{

    /**
     * Shows the tags page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $tags = Tag::all();
        return view('annotation-tags::index', [
            'tags' => $tags
        ]);
    }
}
