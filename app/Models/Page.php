<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'hero_type',
        'hero_eyebrow',
        'hero_rich_text',
        'hero_links',
        'hero_media_id',
        'page_header_eyebrow',
        'page_header_heading',
        'page_header_description',
        'faqs',
        'layout',
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
        'hero_links' => 'array',
        'faqs' => 'array',
        'layout' => 'array',
        'published_at' => 'datetime',
    ];

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_media_id');
    }

    public function metaImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'meta_image_media_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
