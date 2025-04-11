<?php

namespace Biigle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    public function annotation(): BelongsTo
    {
        return $this->belongsTo(Annotation::class);
    }
}
