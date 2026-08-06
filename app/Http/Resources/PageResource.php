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
            'pageHeaderEyebrow' => $this->page_header_eyebrow,
            'pageHeaderHeading' => $this->page_header_heading,
            'pageHeaderDescription' => $this->page_header_description,
            'faqs' => $this->faqs ?? [],
            'hero' => [
                'type' => $this->hero_type,
                'eyebrow' => $this->hero_eyebrow,
                'richText' => $this->hero_rich_text,
                'links' => $this->hero_links ?? [],
                'media' => $this->heroMedia ? new MediaResource($this->heroMedia) : null,
            ],
            'layout' => $this->resolveLayoutMedia($this->layout ?? []),
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

    /**
     * Blocks are stored as [{ "type": "...", "data": {...} }, ...] holding raw
     * media ids. Resolve them to full MediaResource arrays in one pass so the
     * frontend never has to make a second lookup per block.
     *
     * Keyed by block type: each entry lists the data keys holding a single id,
     * and the repeaters holding a list of rows that each carry an id.
     */
    protected const MEDIA_FIELDS = [
        'mediaBlock' => ['single' => ['media'], 'repeaters' => []],
        'aboutFastora' => ['single' => ['image'], 'repeaters' => []],
        'content' => ['single' => ['image'], 'repeaters' => []],
        'trustedBy' => ['single' => [], 'repeaters' => ['logos' => 'media']],
        'team' => ['single' => [], 'repeaters' => ['members' => 'photo']],
    ];

    protected function resolveLayoutMedia(array $blocks): array
    {
        $ids = [];

        foreach ($blocks as $block) {
            $spec = self::MEDIA_FIELDS[$block['type'] ?? ''] ?? null;
            if (! $spec) {
                continue;
            }

            foreach ($spec['single'] as $key) {
                if (! empty($block['data'][$key])) {
                    $ids[] = $block['data'][$key];
                }
            }

            foreach ($spec['repeaters'] as $repeater => $key) {
                foreach ($block['data'][$repeater] ?? [] as $row) {
                    if (! empty($row[$key])) {
                        $ids[] = $row[$key];
                    }
                }
            }
        }

        $mediaById = $ids
            ? Media::query()->whereIn('id', array_unique($ids))->get()->keyBy('id')
            : collect();

        $resolve = fn ($id) => ($media = $mediaById->get($id))
            ? (new MediaResource($media))->resolve()
            : null;

        return collect($blocks)->map(function ($block) use ($resolve) {
            $spec = self::MEDIA_FIELDS[$block['type'] ?? ''] ?? null;
            if (! $spec) {
                return $block;
            }

            foreach ($spec['single'] as $key) {
                if (array_key_exists($key, $block['data'] ?? [])) {
                    $block['data'][$key] = $resolve($block['data'][$key]);
                }
            }

            foreach ($spec['repeaters'] as $repeater => $key) {
                if (! isset($block['data'][$repeater])) {
                    continue;
                }

                $block['data'][$repeater] = collect($block['data'][$repeater])
                    ->map(function ($row) use ($key, $resolve) {
                        $row[$key] = $resolve($row[$key] ?? null);

                        return $row;
                    })
                    // Drop only rows with nothing left to render. This used to
                    // require the media, back when a client without a logo could
                    // not be displayed; the Trusted By block now falls back to the
                    // client's name, so filtering on media alone silently hid every
                    // confirmed client that had no logo file yet.
                    ->filter(function ($row) use ($key) {
                        if ($row[$key] !== null) {
                            return true;
                        }

                        return collect($row)
                            ->except($key)
                            ->contains(fn ($value) => is_string($value) && trim($value) !== '');
                    })
                    ->values()
                    ->all();
            }

            return $block;
        })->values()->all();
    }
}
