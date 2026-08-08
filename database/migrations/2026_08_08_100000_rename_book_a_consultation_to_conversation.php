<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Renames every "Book a Consultation" link label to "Book a Conversation",
 * and retitles the consultation page itself to match. The URL (/consultation)
 * is untouched — only the wording changes, everywhere it appears: page hero
 * links, links inside layout blocks (e.g. a closing CTA), and the footer nav.
 *
 * Matched purely by the old label text, on every page and the footer nav
 * singleton, rather than replayed from any single reference file — this
 * label is duplicated across reference-home-page.php, reference-about-page.php
 * and DatabaseSeeder.php's footer nav, and an editor may have added their own
 * copy of the same link elsewhere. A link an editor has already reworded is
 * left alone, since it no longer matches the old label.
 */
return new class extends Migration
{
    private const OLD_LABEL = 'Book a Consultation';
    private const NEW_LABEL = 'Book a Conversation';

    public function up(): void
    {
        $this->renameLabel(self::OLD_LABEL, self::NEW_LABEL);

        DB::table('pages')
            ->where('slug', 'consultation')
            ->where('title', self::OLD_LABEL)
            ->update(['title' => self::NEW_LABEL, 'updated_at' => now()]);

        DB::table('pages')
            ->where('slug', 'consultation')
            ->where('meta_title', self::OLD_LABEL)
            ->update(['meta_title' => self::NEW_LABEL, 'updated_at' => now()]);
    }

    public function down(): void
    {
        $this->renameLabel(self::NEW_LABEL, self::OLD_LABEL);

        DB::table('pages')
            ->where('slug', 'consultation')
            ->where('title', self::NEW_LABEL)
            ->update(['title' => self::OLD_LABEL, 'updated_at' => now()]);

        DB::table('pages')
            ->where('slug', 'consultation')
            ->where('meta_title', self::NEW_LABEL)
            ->update(['meta_title' => self::OLD_LABEL, 'updated_at' => now()]);
    }

    private function renameLabel(string $from, string $to): void
    {
        foreach (DB::table('pages')->get(['id', 'hero_links', 'layout']) as $page) {
            $heroLinks = json_decode($page->hero_links ?? '[]', true);
            $layout = json_decode($page->layout ?? '[]', true);
            $changed = false;

            if (is_array($heroLinks)) {
                foreach ($heroLinks as $i => $link) {
                    if (($link['label'] ?? null) === $from) {
                        $heroLinks[$i]['label'] = $to;
                        $changed = true;
                    }
                }
            }

            if (is_array($layout)) {
                foreach ($layout as $bi => $block) {
                    foreach ($block['data']['links'] ?? [] as $li => $link) {
                        if (($link['label'] ?? null) === $from) {
                            $layout[$bi]['data']['links'][$li]['label'] = $to;
                            $changed = true;
                        }
                    }
                }
            }

            if (! $changed) {
                continue;
            }

            DB::table('pages')->where('id', $page->id)->update([
                'hero_links' => json_encode($heroLinks ?? []),
                'layout' => json_encode($layout ?? []),
                'updated_at' => now(),
            ]);
        }

        $footer = DB::table('nav_footers')->first();

        if ($footer === null) {
            return;
        }

        $navItems = json_decode($footer->nav_items ?? '[]', true);

        if (! is_array($navItems)) {
            return;
        }

        $changed = false;

        foreach ($navItems as $i => $item) {
            if (($item['label'] ?? null) === $from) {
                $navItems[$i]['label'] = $to;
                $changed = true;
            }
        }

        if (! $changed) {
            return;
        }

        DB::table('nav_footers')->where('id', $footer->id)->update([
            'nav_items' => json_encode($navItems),
            'updated_at' => now(),
        ]);
    }
};
