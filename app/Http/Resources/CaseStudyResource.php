<?php

namespace App\Http\Resources;

use App\Models\Media;
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

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'clientName' => $this->client_name,
            'industry' => $this->industry,
            'coverImage' => $this->coverImage ? new MediaResource($this->coverImage) : null,
            'gallery' => collect($this->gallery ?? [])
                ->map(fn ($item) => $galleryMedia->get($item['media_id'] ?? null))
                ->filter()
                ->map(fn ($media) => new MediaResource($media))
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
            'challenge' => $this->challenge,
            'approach' => $this->approach,
            'results' => $this->results ?? [],
            'status' => $this->status,
            'publishedAt' => $this->published_at?->toIso8601String(),
            'updatedAt' => $this->updated_at?->toIso8601String(),
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'image' => $this->metaImage ? new MediaResource($this->metaImage) : null,
            ],
        ];
    }
}
