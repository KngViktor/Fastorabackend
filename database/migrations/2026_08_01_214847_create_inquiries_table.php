<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['new', 'contacted', 'closed'])->default('new');
            $table->string('name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->foreignId('service_needed_id')->nullable()->constrained('services')->nullOnDelete();
            $table->enum('budget_range', ['under-1k', '1k-5k', '5k-15k', '15k-plus', 'not-sure'])->nullable();
            $table->enum('timeline', ['asap', '1-month', '1-3-months', 'exploring'])->nullable();
            $table->text('brief');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
