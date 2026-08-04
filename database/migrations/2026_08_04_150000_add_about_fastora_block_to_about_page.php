<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the "Good work deserves to be noticed" block to the top of the About page.
 *
 * The seeder change alone would not reach the live site: app:deploy skips
 * seeding once content exists. Same reasoning as the admin-email migration —
 * app:deploy always runs migrations, so this is the vehicle that works on a
 * database already carrying edited content.
 *
 * Two differences from the home-page copy of this block. It carries no link,
 * because "More about Fastora" pointing at /about from /about is a dead end.
 * And it takes whichever photograph the About hero is *not* using, so the page
 * does not open with the same image twice.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            $layout = [];
        }

        // Idempotent: never add a second copy, and never overwrite one an editor
        // has already positioned or reworded.
        foreach ($layout as $block) {
            if (($block['type'] ?? null) === 'aboutFastora') {
                return;
            }
        }

        $image = $this->pickPhotograph($page->hero_media_id ?? null);

        array_unshift($layout, [
            'type' => 'aboutFastora',
            'data' => [
                'heading' => 'Good work deserves to be noticed, understood, and remembered.',
                'richText' => '<p>Many businesses are genuinely good at what they do. Capable teams, quality products, years of experience. Yet they are overlooked because they struggle to communicate their value.</p><p>Fastora exists to close that gap. We help businesses communicate more effectively so they become easier to understand, easier to trust, and harder to ignore.</p>',
                'image' => $image,
                'linkLabel' => null,
                'linkUrl' => null,
                'stats' => [
                    ['value' => '10', 'label' => 'Services under one team'],
                    ['value' => 'Africa', 'label' => 'Rooted here, working globally'],
                ],
            ],
        ]);

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    /**
     * Picks a genuine photograph for the block.
     *
     * Naively taking the first image row gives you the brand mark, because the
     * logo is seeded before the photography — which is how the logo ended up
     * standing in for real imagery across the site once already. So the logo,
     * the favicon and anything named like a brand asset are excluded, and only
     * then does it fall back to the hero's own image rather than leaving an
     * empty slot.
     */
    private function pickPhotograph(?int $heroMediaId): ?int
    {
        $brandIds = array_filter((array) DB::table('site_settings')
            ->select('logo_light_media_id', 'logo_dark_media_id', 'favicon_media_id')
            ->first());

        $excluded = array_values(array_unique(array_map('intval', $brandIds)));

        $photo = DB::table('media')
            ->where('mime_type', 'like', 'image/%')
            ->when($excluded !== [], fn ($q) => $q->whereNotIn('id', $excluded))
            ->when($heroMediaId, fn ($q) => $q->where('id', '!=', $heroMediaId))
            ->where(function ($q) {
                // 'hero' is excluded alongside the brand assets because the hero
                // image is pre-composited — navy gradient, ghosted wordmark and a
                // diagonal crop baked into one landscape file. It is built for the
                // hero band and looks wrong dropped into a light content block.
                foreach (['logo', 'brand', 'mark', 'icon', 'favicon', 'hero'] as $word) {
                    $q->where('filename', 'not like', "%{$word}%");
                }
            })
            ->orderBy('id')
            ->value('id');

        return $photo ? (int) $photo : $heroMediaId;
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $filtered = array_values(array_filter(
            $layout,
            fn ($block) => ($block['type'] ?? null) !== 'aboutFastora',
        ));

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($filtered),
            'updated_at' => now(),
        ]);
    }
};
