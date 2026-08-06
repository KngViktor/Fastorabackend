<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Two edits to the Home page, requested after review:
 *
 * - Drops the "How we work with you" ourProcess block entirely (the 01-04
 *   step section). Matched by heading, and only this block type/heading, so
 *   Consultation's own ourProcess block ("How the conversation works") is
 *   untouched — it's a different instance of the same block type.
 * - Removes the middle title line ("We listen first" / "We connect the
 *   dots" / "We measure what matters") from the "We think before we create."
 *   whyFastora block's three cards, leaving just the stat word and the
 *   description. Matched by heading, so the page's other whyFastora
 *   instance ("Our impact at a glance") is untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $changed = false;

        $layout = array_values(array_filter($layout, function ($block) use (&$changed) {
            if (($block['type'] ?? null) === 'ourProcess' && ($block['data']['heading'] ?? null) === 'How we work with you') {
                $changed = true;

                return false;
            }

            return true;
        }));

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) !== 'whyFastora' || ($block['data']['heading'] ?? null) !== 'We think before we create.') {
                continue;
            }

            foreach ($block['data']['points'] ?? [] as $p => $point) {
                if (($point['title'] ?? '') !== '') {
                    $layout[$i]['data']['points'][$p]['title'] = '';
                    $changed = true;
                }
            }
        }

        if (! $changed) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Not restorable to the exact prior order/content — see
        // 2026_08_04_170000's own down() for the same caveat on this page.
    }
};
