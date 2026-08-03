<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The brand specification defines three brand colours: navy, sky blue, and
 * gold. Only the first two had fields. Gold is the accent reserved for
 * emphasis and premium touches, so it needs to be editable alongside the rest
 * rather than hardcoded in the frontend stylesheet.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('gold_color')->default('#C6A15B')->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('gold_color');
        });
    }
};
