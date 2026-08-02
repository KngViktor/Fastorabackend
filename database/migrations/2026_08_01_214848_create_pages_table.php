<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // Hero (mirrors Payload's hero group).
            $table->enum('hero_type', ['none', 'highImpact', 'mediumImpact', 'lowImpact'])->default('lowImpact');
            $table->longText('hero_rich_text')->nullable();
            // [{ "label": "...", "url": "...", "appearance": "default|outline" }, ...]
            $table->json('hero_links')->nullable();
            $table->foreignId('hero_media_id')->nullable()->constrained('media')->nullOnDelete();

            // Optional CMS-editable header banner for utility routes with their
            // own hand-coded layout (services, case-studies, contact) — those
            // routes don't use the block-based `layout` below, just this one
            // eyebrow/heading/description trio for their top banner.
            $table->string('page_header_eyebrow')->nullable();
            $table->string('page_header_heading')->nullable();
            $table->text('page_header_description')->nullable();

            // Flexible block layout — the equivalent of Payload's `blocks` field.
            // Each entry: { "type": "callToAction", "data": { ... } }, edited via
            // a Filament Builder field. See app/Filament/Blocks for the schema
            // of each block type.
            $table->json('layout')->nullable();

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->foreignId('meta_image_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
