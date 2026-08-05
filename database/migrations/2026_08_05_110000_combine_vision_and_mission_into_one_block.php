<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Merges the About page's two stacked "Our vision" / "Our mission" content
 * blocks into a single visionMission block, so the frontend can render them
 * side by side in one card instead of as two full-width paragraphs.
 *
 * Guarded on finding both source blocks by their heading, so an editor who has
 * already reworded or removed either one is left alone rather than having
 * this reconstruct something from a state that no longer matches.
 *
 * On a fresh database this is a no-op: reference-about-page.php already
 * defines the merged block directly, so the seeder never produces the old
 * two-block shape for this to find.
 */
return new class extends Migration
{
    private const VISION_HEADING = 'Our vision';

    private const MISSION_HEADING = 'Our mission';

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

        $visionIndex = $this->findContentBlockIndex($layout, self::VISION_HEADING);
        $missionIndex = $this->findContentBlockIndex($layout, self::MISSION_HEADING);

        if ($visionIndex === null || $missionIndex === null) {
            return;
        }

        $combined = [
            'type' => 'visionMission',
            'data' => [
                'visionHeading' => self::VISION_HEADING,
                'visionBody' => $this->stripLeadingHeading($layout[$visionIndex]['data']['richText'], self::VISION_HEADING),
                'missionHeading' => self::MISSION_HEADING,
                'missionBody' => $this->stripLeadingHeading($layout[$missionIndex]['data']['richText'], self::MISSION_HEADING),
            ],
        ];

        $rebuilt = [];

        foreach ($layout as $index => $block) {
            if ($index === $visionIndex) {
                $rebuilt[] = $combined;

                continue;
            }

            if ($index === $missionIndex) {
                continue;
            }

            $rebuilt[] = $block;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($rebuilt),
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

        $index = null;

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) === 'visionMission') {
                $index = $i;

                break;
            }
        }

        if ($index === null) {
            return;
        }

        $data = $layout[$index]['data'] ?? [];

        $split = [
            [
                'type' => 'content',
                'data' => [
                    'richText' => '<h2>' . ($data['visionHeading'] ?? self::VISION_HEADING) . '</h2>' . ($data['visionBody'] ?? ''),
                ],
            ],
            [
                'type' => 'content',
                'data' => [
                    'richText' => '<h2>' . ($data['missionHeading'] ?? self::MISSION_HEADING) . '</h2>' . ($data['missionBody'] ?? ''),
                ],
            ],
        ];

        array_splice($layout, $index, 1, $split);

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    private function findContentBlockIndex(array $layout, string $heading): ?int
    {
        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) !== 'content') {
                continue;
            }

            $richText = $block['data']['richText'] ?? '';

            if (str_starts_with($richText, "<h2>{$heading}</h2>")) {
                return $index;
            }
        }

        return null;
    }

    private function stripLeadingHeading(string $richText, string $heading): string
    {
        return substr($richText, strlen("<h2>{$heading}</h2>"));
    }
};
