<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Fastora');
            $table->string('tagline')->nullable();
            $table->foreignId('logo_light_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('logo_dark_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('favicon_media_id')->nullable()->constrained('media')->nullOnDelete();

            // Backend-editable color palette (see the Next.js Site Settings → Colors tab).
            $table->string('accent_color')->default('#2B7FD6');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('text_color')->default('#111827');
            $table->string('surface_color')->default('#F7F9FC');
            $table->string('border_color')->default('#E3E8EF');
            $table->string('muted_text_color')->default('#5B6472');
            $table->string('primary_color')->default('#0B2545');
            $table->string('dark_panel_text_color')->default('#FFFFFF');

            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            // [{ "platform": "instagram", "url": "..." }, ...]
            $table->json('social_links')->nullable();

            $table->text('footer_text')->nullable();
            $table->string('newsletter_heading')->nullable();
            $table->string('newsletter_subheading')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
