<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets consultation requests share the enquiries table instead of needing their
 * own.
 *
 * A consultation request is an enquiry with a couple of extra fields, so it
 * belongs in the same inbox: one place to watch, one notification path, and the
 * existing new/contacted/closed workflow applies unchanged.
 *
 * preferred_times is free text on purpose. Asking for two or three times someone
 * can make, in their own words, is far more likely to be answered accurately than
 * a slot picker fed by availability nobody remembers to keep current — and it
 * cannot double-book against a calendar this app has no sight of.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Distinguishes the two forms in one inbox. Defaults to general so
            // every existing row stays correct.
            $table->string('kind')->default('general')->after('status')->index();
            $table->text('preferred_times')->nullable()->after('brief');
            $table->string('timezone')->nullable()->after('preferred_times');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['kind']);
            $table->dropColumn(['kind', 'preferred_times', 'timezone']);
        });
    }
};
