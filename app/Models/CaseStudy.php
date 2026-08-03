<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'client_name',
        'industry',
        'cover_image_media_id',
        'gallery',
        'order',
        'featured_on_home',
        'related_service_id',
        'challenge',
        'approach',
        'results',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_canonical_url',
        'meta_noindex',
        'meta_image_media_id',
    ];

    protected $casts = [
        'meta_noindex' => 'boolean',
        'gallery' => 'array',
        'featured_on_home' => 'boolean',
        'results' => 'array',
        'published_at' => 'datetime',
    ];

    public function coverImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_image_media_id');
    }

    public function metaImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'meta_image_media_id');
    }

    public function relatedService(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'related_service_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
