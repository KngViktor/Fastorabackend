<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Service
 */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'icon' => $this->icon ? new MediaResource($this->icon) : null,
            'featuredImage' => $this->featuredImage ? new MediaResource($this->featuredImage) : null,
            'order' => $this->order,
            'featuredOnHome' => (bool) $this->featured_on_home,
            'problem' => $this->problem,
            'approach' => $this->approach,
            'deliverables' => $this->deliverables ?? [],
            'faqs' => $this->faqs ?? [],
            'status' => $this->status,
            'publishedAt' => $this->published_at?->toIso8601String(),
            'updatedAt' => $this->updated_at?->toIso8601String(),
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'canonicalUrl' => $this->meta_canonical_url,
                'noindex' => (bool) $this->meta_noindex,
                'image' => $this->metaImage ? new MediaResource($this->metaImage) : null,
            ],
        ];
    }
}
