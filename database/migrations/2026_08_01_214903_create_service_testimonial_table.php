<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mirrors Payload's Testimonials.relatedService (hasMany services).
        Schema::create('service_testimonial', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('testimonial_id')->constrained()->cascadeOnDelete();
            $table->primary(['service_id', 'testimonial_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_testimonial');
    }
};
