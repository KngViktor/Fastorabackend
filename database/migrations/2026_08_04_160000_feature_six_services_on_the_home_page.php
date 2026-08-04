<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fills the home page services grid to six cards.
 *
 * The grid is two columns and the block asks for a limit of six, but only three
 * services were flagged, so it rendered as a short, lopsided block instead of
 * the full 3x2 grid the design expects.
 *
 * These six are the ones from the reference design, in its order. Everything
 * here stays editable: the flag is a checkbox on each service in the admin, so
 * an editor can swap which six appear without another migration.
 */
return new class extends Migration
{
    private const FEATURED = [
        'strategic-communications',
        'brand-consulting',
        'content-strategy',
        'social-media-management',
        'digital-marketing',
        'communication-advisory',
    ];

    public function up(): void
    {
        // Only act while the flags still look untouched. If an editor has already
        // chosen a different set, that choice is theirs to keep.
        $flagged = DB::table('services')->where('featured_on_home', true)->count();

        if ($flagged >= count(self::FEATURED)) {
            return;
        }

        foreach (self::FEATURED as $position => $slug) {
            DB::table('services')
                ->where('slug', $slug)
                ->update([
                    'featured_on_home' => true,
                    'order' => $position + 1,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // No reliable "before" state to restore — the previous set was a subset of
        // these — so leave the flags alone rather than guess and unfeature
        // something an editor wanted.
    }
};
