<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Contact page has never had its own meta title or description — the
 * seeder created it without either, so search results and shares fall back
 * to the bare site name and no description at all, on the one page whose
 * job is to convert. Backfills both, guarded on each still being empty so
 * an editor's own copy is never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'contact')->first();

        if ($page === null) {
            return;
        }

        $update = [];

        if (blank($page->meta_title)) {
            $update['meta_title'] = 'Contact';
        }

        if (blank($page->meta_description)) {
            $update['meta_description'] = "Tell us about your business and where you'd like to go. "
                . "We respond to every enquiry within one business day.";
        }

        if ($update === []) {
            return;
        }

        $update['updated_at'] = now();

        DB::table('pages')->where('id', $page->id)->update($update);
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'contact')->update([
            'meta_title' => null,
            'meta_description' => null,
            'updated_at' => now(),
        ]);
    }
};
