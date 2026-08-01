<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'icon_media_id',
        'featured_image_media_id',
        'order',
        'featured_on_home',
        'problem',
        'approach',
        'deliverables',
        'faqs',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_image_media_id',
    ];

    protected $casts = [
        'featured_on_home' => 'boolean',
        'deliverables' => 'array',
        'faqs' => 'array',
        'published_at' => 'datetime',
    ];

    public function icon(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'icon_media_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_media_id');
    }

    public function metaImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'meta_image_media_id');
    }

    public function caseStudies(): HasMany
    {
        return $this->hasMany(CaseStudy::class, 'related_service_id');
    }

    public function testimonials(): BelongsToMany
    {
        return $this->belongsToMany(Testimonial::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
