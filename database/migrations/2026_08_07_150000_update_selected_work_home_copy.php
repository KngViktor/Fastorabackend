<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewords the Home page's "Selected work" heading and adds the subtext
 * introducing it as a client roster rather than a results claim.
 *
 * Guarded on the old heading text. A database that already carries the new
 * copy (a fresh install, or one where 2026_08_05_140000 already picked it up
 * from reference-home-page.php) is left alone.
 */
return new class extends Migration
{
    private const OLD_HEADING = 'Results, not just deliverables';
    private const NEW_HEADING = 'Every project tells a bigger story.';
    private const NEW_DESCRIPTION = "These are some of the businesses we've had the privilege of working with.";

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

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) === 'selectedWork' && ($block['data']['heading'] ?? null) === self::OLD_HEADING) {
                $layout[$i]['data']['heading'] = self::NEW_HEADING;
                $layout[$i]['data']['description'] = self::NEW_DESCRIPTION;
                $changed = true;
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
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $changed = false;

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) === 'selectedWork' && ($block['data']['heading'] ?? null) === self::NEW_HEADING) {
                $layout[$i]['data']['heading'] = self::OLD_HEADING;
                unset($layout[$i]['data']['description']);
                $changed = true;
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
};
