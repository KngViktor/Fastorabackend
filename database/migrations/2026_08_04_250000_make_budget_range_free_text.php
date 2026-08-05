<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Turns budget_range from an enum into free text.
 *
 * It was enum('under-1k','1k-5k','5k-15k','15k-plus','not-sure'), so anything an
 * enquirer typed was rejected by MySQL with "Data truncated for column
 * budget_range" and the whole submission 500'd. Fixed bands also pushed people
 * into the nearest wrong one, and "not sure" carried no information at all.
 *
 * Existing values are machine codes that were only ever readable because a select
 * decoded them for display. With the field now shown as plain text they would
 * read as "under-1k", so they are rewritten into the words they stood for.
 */
return new class extends Migration
{
    private const LABELS = [
        'under-1k' => 'Under $1,000',
        '1k-5k' => '$1,000 – $5,000',
        '5k-15k' => '$5,000 – $15,000',
        '15k-plus' => '$15,000+',
        'not-sure' => 'Not sure yet',
    ];

    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('budget_range')->nullable()->change();
        });

        foreach (self::LABELS as $code => $label) {
            DB::table('inquiries')->where('budget_range', $code)->update(['budget_range' => $label]);
        }
    }

    public function down(): void
    {
        // Map the labels back, then anything typed by hand has no enum member to
        // go to, so it is cleared rather than blocking the rollback.
        foreach (self::LABELS as $code => $label) {
            DB::table('inquiries')->where('budget_range', $label)->update(['budget_range' => $code]);
        }

        DB::table('inquiries')
            ->whereNotNull('budget_range')
            ->whereNotIn('budget_range', array_keys(self::LABELS))
            ->update(['budget_range' => null]);

        Schema::table('inquiries', function (Blueprint $table) {
            $table->enum('budget_range', array_keys(self::LABELS))->nullable()->change();
        });
    }
};
