<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Creates the Privacy Policy and Terms & Conditions pages the site was
 * missing — needed now that it collects real data (contact/consultation
 * forms, newsletter sign-ups) and runs analytics cookies.
 *
 * Idempotent like the other page-creating migrations: never a second copy,
 * and never overwrites a page an editor has already started customising.
 */
return new class extends Migration
{
    public function up(): void
    {
        $legal = require database_path('data/reference-legal-pages.php');

        foreach (['privacy-policy' => 'privacy_policy', 'terms-conditions' => 'terms_conditions'] as $slug => $key) {
            if (DB::table('pages')->where('slug', $slug)->exists()) {
                continue;
            }

            $page = $legal[$key];

            DB::table('pages')->insert([
                'title' => $page['title'],
                'slug' => $slug,
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
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('pages')->whereIn('slug', ['privacy-policy', 'terms-conditions'])->delete();
    }
};
