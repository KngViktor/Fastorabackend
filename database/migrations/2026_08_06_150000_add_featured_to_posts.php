<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The content document asks to be able to pin one article at the top of the
 * Insights page. Nothing is pre-selected — this just adds the capability;
 * an editor picks which post (if any) by toggling it on.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('featured')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('featured');
        });
    }
};
