<?php

namespace Biigle\Modules\AnnotationTags;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TagValue extends Pivot
{
    protected $table = 'annotations_tags';
    protected $fillable = ['annotation_id', 'tag_id', 'value'];
    public $timestamps = true;
}
