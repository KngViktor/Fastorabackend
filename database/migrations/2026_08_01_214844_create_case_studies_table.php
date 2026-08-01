<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->string('client_name');
            $table->string('industry')->nullable();
            $table->foreignId('cover_image_media_id')->constrained('media')->restrictOnDelete();
            // [{ "media_id": 1 }, ...] — ordered gallery of media ids.
            $table->json('gallery')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('featured_on_home')->default(false);
            $table->foreignId('related_service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->longText('challenge')->nullable();
            $table->longText('approach')->nullable();
            // [{ "metric": "+212%", "label": "engagement rate in 90 days" }, ...]
            $table->json('results')->nullable();
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
        Schema::dropIfExists('case_studies');
    }
};
