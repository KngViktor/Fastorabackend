<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A local record of every signup, independent of whichever third-party
 * provider is configured (or not) at the time. If a provider is unreachable,
 * unconfigured, or swapped out later, the raw list of who signed up is never
 * lost — it's always visible in the admin even without the integration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            // Where the signup happened — the footer today, possibly other
            // forms later — useful context an admin can't get from email alone.
            $table->string('source')->default('footer');
            $table->boolean('synced_to_provider')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
