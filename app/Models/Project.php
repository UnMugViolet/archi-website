<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->withPivot('display_order')
            ->withTimestamps();
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('display_order');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ProjectSection::class)
            ->orderBy('display_order');
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ProjectMetric::class)
            ->orderBy('display_order');
    }

    public function awardOrPublication(): HasMany
    {
        return $this->hasMany(AwardOrPublication::class)
            ->orderBy('display_order');
    }
}
