<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('display_order')
            ->withTimestamps()
            ->orderByPivot('display_order');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('display_order');
    }
}
