<?php

namespace Biigle\Modules\AnnotationTags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Biigle\ImageAnnotation;
use Biigle\Modules\AnnotationTags\TagValue;

class Tag extends Model
{
    public function annotations(): BelongsToMany
    {
        return $this->belongsToMany(ImageAnnotation::class, 'annotations_tags')
            ->using(TagValue::class)
            ->withPivot('value')
            ->withTimestamps();
    }

    public function setColorAttribute($value)
    {
        if (is_string($value) && $value[0] === '#') {
            $value = substr($value, 1);
        }

        $this->attributes['color'] = $value;
    }
}
