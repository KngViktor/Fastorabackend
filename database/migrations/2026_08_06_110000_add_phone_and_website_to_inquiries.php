<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The content document's contact form adds two optional fields the current
 * one doesn't collect: a phone number and the enquirer's own website.
 *
 * Named `website_url`, not `website` — the contact form already uses
 * `website` as its honeypot field name (a real user never sees or fills it),
 * so a genuine visible "Website" field needs a different name to avoid
 * colliding with it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('website_url')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['phone', 'website_url']);
        });
    }
};
