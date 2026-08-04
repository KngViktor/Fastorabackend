<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the seeded social links with the official accounts.
 *
 * The seeded pair pointed at instagram.com/fastora and linkedin.com/company/fastora,
 * one of which was simply the wrong handle. Four more accounts are now confirmed.
 *
 * Replaces the list wholesale rather than merging, because a handle that has
 * changed needs to be corrected, not kept alongside its replacement. Guarded so
 * it only runs while the list still matches what was seeded, leaving anything an
 * editor has curated untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $settings = DB::table('site_settings')->first();

        if ($settings === null) {
            return;
        }

        $current = json_decode($settings->social_links ?? '[]', true);

        if (! is_array($current)) {
            $current = [];
        }

        // The seeded state: the placeholder handles, with or without the WhatsApp
        // entry added just before this. Anything else is an editor's own list.
        $seededUrls = [
            'https://instagram.com/fastora',
            'https://linkedin.com/company/fastora',
            'https://wa.me/2347038147969',
        ];

        foreach ($current as $link) {
            if (! in_array($link['url'] ?? '', $seededUrls, true)) {
                return;
            }
        }

        DB::table('site_settings')->where('id', $settings->id)->update([
            'social_links' => json_encode(require database_path('data/social-links.php')),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Restoring a wrong handle would be worse than leaving the correct one.
    }
};
