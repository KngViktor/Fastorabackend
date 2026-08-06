<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the drafted Privacy Policy and Terms & Conditions with the
 * client's own copy, renames "Terms & Conditions" (terms-conditions) to
 * "Terms of Use" (terms-of-use) to match it, and adds the Cookie Policy as
 * its own page rather than a section of the Privacy Policy.
 *
 * Unconditional on the page already existing (unlike the migration that
 * first created these pages) — this one is specifically about overwriting
 * the drafted content with the real thing, not about seeding a page that
 * might not exist yet.
 */
return new class extends Migration
{
    public function up(): void
    {
        $legal = require database_path('data/reference-legal-pages.php');
        $email = 'hello@fastora.africa';
        $phone = '+234 703 814 7969';

        $this->upsert('privacy-policy', $legal['privacy_policy'], $email, $phone);

        // The old page is renamed rather than replaced by a new one, so any
        // link or bookmark to /terms-conditions still resolves to the same
        // row under its new slug.
        DB::table('pages')->where('slug', 'terms-conditions')->update(['slug' => 'terms-of-use']);
        $this->upsert('terms-of-use', $legal['terms_of_use'], $email, $phone);

        $this->upsert('cookie-policy', $legal['cookie_policy'], $email, $phone);
    }

    private function upsert(string $slug, array $page, string $email, string $phone): void
    {
        $richText = sprintf($page['body'], $email, $phone);

        $attributes = [
            'title' => $page['title'],
            'hero_type' => 'lowImpact',
            'hero_rich_text' => $page['hero_rich_text'],
            'layout' => json_encode([
                ['type' => 'content', 'data' => ['richText' => $richText]],
            ]),
            'meta_title' => $page['meta_title'],
            'meta_description' => $page['meta_description'],
            'updated_at' => now(),
        ];

        if (DB::table('pages')->where('slug', $slug)->exists()) {
            DB::table('pages')->where('slug', $slug)->update($attributes);

            return;
        }

        DB::table('pages')->insert($attributes + [
            'slug' => $slug,
            'hero_links' => json_encode([]),
            'faqs' => json_encode([]),
            'status' => 'published',
            'published_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'terms-of-use')->update(['slug' => 'terms-conditions']);
        DB::table('pages')->where('slug', 'cookie-policy')->delete();
    }
};
