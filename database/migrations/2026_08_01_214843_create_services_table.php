<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->foreignId('icon_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('featured_image_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->integer('order')->default(0);
            $table->boolean('featured_on_home')->default(true);
            // Rich text stored as HTML (Filament's RichEditor output).
            $table->longText('problem')->nullable();
            $table->longText('approach')->nullable();
            // [{ "label": "..." }, ...]
            $table->json('deliverables')->nullable();
            // [{ "question": "...", "answer": "..." }, ...]
            $table->json('faqs')->nullable();
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
        Schema::dropIfExists('services');
    }
};
