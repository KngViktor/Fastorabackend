<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Page
 */
class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'hero' => [
                'type' => $this->hero_type,
                'richText' => $this->hero_rich_text,
                'links' => $this->hero_links ?? [],
                'media' => $this->heroMedia ? new MediaResource($this->heroMedia) : null,
            ],
            'layout' => $this->resolveLayoutMedia($this->layout ?? []),
            'status' => $this->status,
            'publishedAt' => $this->published_at?->toIso8601String(),
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'image' => $this->metaImage ? new MediaResource($this->metaImage) : null,
            ],
        ];
    }

    /**
     * Blocks are stored as [{ "type": "...", "data": {...} }, ...]. The only
     * block referencing media directly is "mediaBlock" (a raw media id under
     * data.media) — resolve it to a full MediaResource so the frontend never
     * has to make a second lookup.
     */
    protected function resolveLayoutMedia(array $blocks): array
    {
        $mediaIds = collect($blocks)
            ->filter(fn ($block) => ($block['type'] ?? null) === 'mediaBlock')
            ->pluck('data.media')
            ->filter()
            ->all();

        $mediaById = $mediaIds
            ? Media::query()->whereIn('id', $mediaIds)->get()->keyBy('id')
            : collect();

        return collect($blocks)->map(function ($block) use ($mediaById) {
            if (($block['type'] ?? null) === 'mediaBlock' && isset($block['data']['media'])) {
                $media = $mediaById->get($block['data']['media']);
                $block['data']['media'] = $media ? (new MediaResource($media))->resolve() : null;
            }

            return $block;
        })->values()->all();
    }
}
