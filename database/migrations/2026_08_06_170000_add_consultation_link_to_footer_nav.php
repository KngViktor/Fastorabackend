<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds "Book a Consultation" to the footer nav, alongside the header's
 * dedicated button. Idempotent: skipped if a link to /consultation is
 * already there, whatever it's labelled, so this can't create a duplicate
 * on a page an editor has already updated.
 */
return new class extends Migration
{
    public function up(): void
    {
        $nav = DB::table('nav_footers')->first();

        if ($nav === null) {
            return;
        }

        $items = json_decode($nav->nav_items ?? '[]', true);

        if (! is_array($items)) {
            return;
        }

        foreach ($items as $item) {
            if (($item['url'] ?? null) === '/consultation') {
                return;
            }
        }

        $items[] = ['label' => 'Book a Consultation', 'url' => '/consultation'];

        DB::table('nav_footers')->where('id', $nav->id)->update([
            'nav_items' => json_encode($items),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $nav = DB::table('nav_footers')->first();

        if ($nav === null) {
            return;
        }

        $items = json_decode($nav->nav_items ?? '[]', true);

        if (! is_array($items)) {
            return;
        }

        $filtered = array_values(array_filter(
            $items,
            fn (array $item) => ! ($item['label'] === 'Book a Consultation' && $item['url'] === '/consultation'),
        ));

        DB::table('nav_footers')->where('id', $nav->id)->update([
            'nav_items' => json_encode($filtered),
            'updated_at' => now(),
        ]);
    }
};
