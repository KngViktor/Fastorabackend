<?php

namespace App\Http\Resources;

use App\Models\Media;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\CaseStudy
 */
class CaseStudyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $galleryIds = collect($this->gallery ?? [])->pluck('media_id')->filter()->all();
        $galleryMedia = $galleryIds
            ? Media::query()->whereIn('id', $galleryIds)->get()->keyBy('id')
            : collect();

        // Resolved here rather than in the frontend so an unpublished or deleted
        // service simply drops out of the list instead of rendering a dead link.
        $relatedSlugs = collect($this->related_service_slugs ?? []);
        $relatedServices = $relatedSlugs->isNotEmpty()
            ? Service::query()
                ->published()
                ->whereIn('slug', $relatedSlugs->all())
                ->get()
                ->sortBy(fn ($service) => $relatedSlugs->search($service->slug))
                ->map(fn ($service) => [
                    'id' => $service->id,
                    'title' => $service->title,
                    'slug' => $service->slug,
                ])
                ->values()
            : collect();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'heroIntro' => $this->hero_intro,
            'clientName' => $this->client_name,
            'industry' => $this->industry,
            'location' => $this->location,
            'engagement' => $this->engagement,
            'serviceLabels' => $this->service_labels ?? [],
            'coverImage' => $this->coverImage ? new MediaResource($this->coverImage) : null,
            'gallery' => collect($this->gallery ?? [])
                ->map(fn ($item) => [
                    'image' => $galleryMedia->get($item['media_id'] ?? null),
                    'caption' => $item['caption'] ?? null,
                ])
                ->filter(fn ($item) => $item['image'] !== null)
                ->map(fn ($item) => [
                    'image' => new MediaResource($item['image']),
                    'caption' => $item['caption'],
                ])
                ->values(),
            'order' => $this->order,
            'featuredOnHome' => (bool) $this->featured_on_home,
            'relatedService' => $this->relatedService
                ? [
                    'id' => $this->relatedService->id,
                    'title' => $this->relatedService->title,
                    'slug' => $this->relatedService->slug,
                ]
                : null,
            'relatedServices' => $relatedServices,
            'theBusiness' => $this->the_business,
            'whatWeNoticed' => $this->what_we_noticed,
            'ourThinking' => $this->our_thinking,
            'whatWeDid' => $this->what_we_did,
            'resultsHeading' => $this->results_heading,
            'resultsIntro' => $this->results_intro,
            'results' => $this->results ?? [],
            'resultsNote' => $this->results_note,
            'resultsPlacement' => $this->results_placement,
            'testimonial' => $this->testimonial_quote
                ? [
                    'quote' => $this->testimonial_quote,
                    'author' => $this->testimonial_author,
                    'role' => $this->testimonial_role,
                ]
                : null,
            'standoutHeading' => $this->standout_heading,
            'standoutCopy' => $this->standout_copy,
            'takeawayHeading' => $this->takeaway_heading,
            'takeawayCopy' => $this->takeaway_copy,
            'ctaHeading' => $this->cta_heading,
            'ctaCopy' => $this->cta_copy,
            'ctaLabel' => $this->cta_label,
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
