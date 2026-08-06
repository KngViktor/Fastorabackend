<?php

namespace App\Models;

use App\Models\Concerns\SingletonModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends Model
{
    use SingletonModel;

    protected $fillable = [
        'site_name',
        'tagline',
        'logo_light_media_id',
        'logo_dark_media_id',
        'favicon_media_id',
        'accent_color',
        'gold_color',
        'background_color',
        'text_color',
        'surface_color',
        'border_color',
        'muted_text_color',
        'primary_color',
        'dark_panel_text_color',
        'contact_email',
        'contact_phone',
        'address',
        'social_links',
        'footer_text',
        'newsletter_heading',
        'newsletter_subheading',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'notification_email',
        'newsletter_provider',
        'newsletter_api_key',
        'newsletter_list_id',
    ];

    protected $casts = [
        'social_links' => 'array',
        'mail_password' => 'encrypted',
        'newsletter_api_key' => 'encrypted',
    ];

    public function logoLight(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_light_media_id');
    }

    public function logoDark(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_dark_media_id');
    }

    public function favicon(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'favicon_media_id');
    }
}
