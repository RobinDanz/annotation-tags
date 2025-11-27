<?php

namespace Biigle\Modules\AnnotationTags;

use Biigle\Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Biigle\ImageAnnotation;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'value',
        'color'
    ];

    protected $appends = ['label_ids'];

    public function annotations(): BelongsToMany
    {
        return $this->belongsToMany(ImageAnnotation::class, 'tag_annotations')->withTimestamps();
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'tag_allowed_labels');
    }

    public function getLabelIdsAttribute()
    {
        return $this->labels()->pluck('labels.id');
    }

    public function group()
    {
        return $this->belongsTo(TagGroup::class, 'tag_group_id');
    }

    public function setColorAttribute($value)
    {
        if (is_string($value) && $value[0] === '#') {
            $value = substr($value, 1);
        }

        $this->attributes['color'] = $value;
    }
}
