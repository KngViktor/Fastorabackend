<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the Accessibility page from the client's copy, alongside the Privacy
 * Policy, Terms of Use and Cookie Policy.
 *
 * Same shape as those three: a lowImpact hero, one content block, and the
 * contact details filled in from the values the other legal pages use, so the
 * email and phone number are written in one place.
 *
 * Guarded on the page not already existing, unlike the migration that replaced
 * the drafted legal copy — this one is seeding a page that has never been there,
 * so it must not overwrite an Accessibility page an editor has since written.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('pages')->where('slug', 'accessibility')->exists()) {
            return;
        }

        $legal = require database_path('data/reference-legal-pages.php');
        $page = $legal['accessibility'];

        DB::table('pages')->insert([
            'slug' => 'accessibility',
            'title' => $page['title'],
            'hero_type' => 'lowImpact',
            'hero_rich_text' => $page['hero_rich_text'],
            'hero_links' => json_encode([]),
            'faqs' => json_encode([]),
            'layout' => json_encode([
                [
                    'type' => 'content',
                    'data' => [
                        'richText' => sprintf($page['body'], 'hello@fastora.africa', '+234 703 814 7969'),
                    ],
                ],
            ]),
            'meta_title' => $page['meta_title'],
            'meta_description' => $page['meta_description'],
            'status' => 'published',
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'accessibility')->delete();
    }
};
