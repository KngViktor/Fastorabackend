<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Every marketing email needs a working, one-click way to opt out — without
 * it, the Privacy Policy's "unsubscribe anytime" line would be a promise
 * this app couldn't keep. A random token rather than the row id, so the
 * unsubscribe link in an email can't be walked to remove someone else's
 * subscription by guessing a nearby number.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('unsubscribe_token', 40)->nullable()->unique()->after('email');
        });

        DB::table('newsletter_subscribers')->whereNull('unsubscribe_token')->get()->each(function ($row) {
            DB::table('newsletter_subscribers')->where('id', $row->id)->update([
                'unsubscribe_token' => Str::random(40),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn('unsubscribe_token');
        });
    }
};
