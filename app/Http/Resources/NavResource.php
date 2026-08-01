<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\NavHeader|\App\Models\NavFooter
 */
class NavResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'navItems' => collect($this->nav_items ?? [])
                ->map(fn ($item) => [
                    'label' => $item['label'] ?? '',
                    'url' => $item['url'] ?? '',
                ])
                ->values(),
        ];
    }
}
