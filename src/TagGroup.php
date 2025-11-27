<?php

namespace Biigle\Modules\AnnotationTags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagGroup extends Model
{
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
