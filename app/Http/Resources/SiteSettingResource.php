<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\SiteSetting
 */
class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'siteName' => $this->site_name,
            'tagline' => $this->tagline,
            'logoLight' => $this->logoLight ? new MediaResource($this->logoLight) : null,
            'logoDark' => $this->logoDark ? new MediaResource($this->logoDark) : null,
            'favicon' => $this->favicon ? new MediaResource($this->favicon) : null,
            'colors' => [
                'accent' => $this->accent_color,
                'gold' => $this->gold_color,
                'background' => $this->background_color,
                'text' => $this->text_color,
                'surface' => $this->surface_color,
                'border' => $this->border_color,
                'mutedText' => $this->muted_text_color,
                'primary' => $this->primary_color,
                'darkPanelText' => $this->dark_panel_text_color,
            ],
            'contactEmail' => $this->contact_email,
            'contactPhone' => $this->contact_phone,
            'address' => $this->address,
            'socialLinks' => $this->social_links ?? [],
            'footerText' => $this->footer_text,
            'newsletterHeading' => $this->newsletter_heading,
            'newsletterSubheading' => $this->newsletter_subheading,
        ];
    }
}
