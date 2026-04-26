<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
        'path',
        'disk',
        'alt_text',
        'caption',
        'display_order',
        'is_thumbnail',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
