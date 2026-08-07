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
        'hero_intro',
        'client_name',
        'industry',
        'location',
        'engagement',
        'service_labels',
        'cover_image_media_id',
        'gallery',
        'order',
        'featured_on_home',
        'related_service_id',
        'the_business',
        'what_we_noticed',
        'our_thinking',
        'what_we_did',
        'results_heading',
        'results_intro',
        'results',
        'results_note',
        'results_placement',
        'testimonial_quote',
        'testimonial_author',
        'testimonial_role',
        'standout_heading',
        'standout_copy',
        'takeaway_heading',
        'takeaway_copy',
        'related_service_slugs',
        'cta_heading',
        'cta_copy',
        'cta_label',
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
        'service_labels' => 'array',
        'results' => 'array',
        'related_service_slugs' => 'array',
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
