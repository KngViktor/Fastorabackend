<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Two edits to the About page, requested after review:
 *
 * - "The principles behind every recommendation" whyFastora block: drops
 *   the middle title line on each card (same treatment as the Home page's
 *   equivalent instance) and renames the three stat headers to "Be
 *   Understood" / "Think First" / "One Clear Message".
 * - The team block's bio for Eya Ndidiamaka, matched by name rather than
 *   the old bio text (which this migration is about to change, so matching
 *   on it would make the migration non-idempotent), now opens with her
 *   surname instead of her first name.
 */
return new class extends Migration
{
    private const NEW_STATS = [
        'Understood' => 'Be Understood',
        'Think first' => 'Think First',
        'One story' => 'One Clear Message',
    ];

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $changed = false;

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) === 'whyFastora' && ($block['data']['heading'] ?? null) === 'The principles behind every recommendation') {
                foreach ($block['data']['points'] ?? [] as $p => $point) {
                    $newStat = self::NEW_STATS[$point['stat'] ?? ''] ?? null;

                    if ($newStat !== null && $point['stat'] !== $newStat) {
                        $layout[$i]['data']['points'][$p]['stat'] = $newStat;
                        $changed = true;
                    }

                    if (($point['title'] ?? '') !== '') {
                        $layout[$i]['data']['points'][$p]['title'] = '';
                        $changed = true;
                    }
                }
            }

            if (($block['type'] ?? null) === 'team') {
                foreach ($block['data']['members'] ?? [] as $m => $member) {
                    if (($member['name'] ?? null) === 'Eya Ndidiamaka' && str_starts_with($member['bio'] ?? '', 'Eya supports')) {
                        $layout[$i]['data']['members'][$m]['bio'] = 'Ndidiamaka' . substr($member['bio'], strlen('Eya'));
                        $changed = true;
                    }
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
        // Not restorable to the exact prior wording — matches the pattern of
        // the other one-way content-tweak migrations in this batch.
    }
};
