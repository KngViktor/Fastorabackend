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
            'overviewHeading' => $this->overview_heading,
            'overviewCopy' => $this->overview_copy,
            'approach' => $this->approach,
            'outcomes' => $this->outcomes ?? [],
            'deliverables' => $this->deliverables ?? [],
            'goodFitIf' => $this->good_fit_if ?? [],
            // The former standalone services now grouped under this one, shown on
            // the card. Distinct from deliverables, which is the longer page list.
            'includes' => $this->includes ?? [],
            // Slugs rather than full records: the frontend only needs to build two
            // links, and resolving them here would mean loading services inside a
            // service response.
            'relatedServiceSlugs' => $this->related_service_slugs ?? [],
            'ctaHeading' => $this->cta_heading,
            'ctaCopy' => $this->cta_copy,
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
