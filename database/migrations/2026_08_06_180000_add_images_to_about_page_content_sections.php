<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds a photo to each of the About page's four plain-text `content`
 * sections, alternating which side it sits on. Follow-up to
 * 2026_08_06_140000: that migration only ever runs once per database, so a
 * database where it already ran before this one existed needs a separate
 * pass to pick up the images.
 *
 * Matched by each block's distinctive `<h2>` heading rather than position,
 * and only touched when the block has no `image` key yet — an editor who
 * has since added their own image, or removed the heading, is left alone.
 */
return new class extends Migration
{
    private const SECTIONS = [
        'How Fastora began' => ['alt' => 'A communications professional at work in a studio', 'position' => 'right'],
        'A thoughtful process from start to finish' => ['alt' => 'Mapping a communications strategy across markets', 'position' => 'left'],
        'Who we work with' => ['alt' => 'Planning content across digital channels', 'position' => 'right'],
        'A name inspired by the way we work' => ['alt' => 'Reviewing performance figures on a tablet', 'position' => 'left'],
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

        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) !== 'content') {
                continue;
            }

            $richText = $block['data']['richText'] ?? '';
            $heading = $this->matchHeading($richText);

            if ($heading === null || array_key_exists('image', $block['data'] ?? [])) {
                continue;
            }

            $mediaId = DB::table('media')->where('alt', self::SECTIONS[$heading]['alt'])->value('id');

            if ($mediaId === null) {
                continue;
            }

            $layout[$index]['data']['image'] = $mediaId;
            $layout[$index]['data']['imagePosition'] = self::SECTIONS[$heading]['position'];
            $changed = true;
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
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $changed = false;

        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) !== 'content') {
                continue;
            }

            $heading = $this->matchHeading($block['data']['richText'] ?? '');

            if ($heading === null) {
                continue;
            }

            unset($layout[$index]['data']['image'], $layout[$index]['data']['imagePosition']);
            $changed = true;
        }

        if (! $changed) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    private function matchHeading(string $richText): ?string
    {
        foreach (array_keys(self::SECTIONS) as $heading) {
            if (str_starts_with($richText, "<h2>{$heading}</h2>")) {
                return $heading;
            }
        }

        return null;
    }
};
