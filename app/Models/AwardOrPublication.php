<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwardOrPublication extends Model
{
    protected $fillable = [
        'project_id',
        'entry_type',
        'title',
        'issuer_or_publisher',
        'url',
        'published_on',
        'excerpt',
        'display_order',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
