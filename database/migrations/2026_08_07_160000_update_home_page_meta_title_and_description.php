<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewords the Home page's meta title and description, shown wherever the
 * site root gets shared or indexed (search results, WhatsApp/social link
 * previews). The old comma-separated title read as a tagline rather than a
 * title; the description was written for search engines rather than for a
 * person deciding whether to open the link.
 *
 * Guarded on the old values, matching the pattern of the other one-way
 * content-tweak migrations in this batch.
 */
return new class extends Migration
{
    private const OLD_TITLE = 'Fastora, Communications & Digital Strategy';
    private const OLD_DESCRIPTION = 'Fastora helps businesses communicate with clarity, credibility, and confidence.';
    private const NEW_TITLE = 'Fastora | Communications & Digital Strategy';
    private const NEW_DESCRIPTION = 'Helping businesses become easier to understand, remember, and choose.';

    public function up(): void
    {
        DB::table('pages')
            ->where('slug', 'home')
            ->where('meta_title', self::OLD_TITLE)
            ->where('meta_description', self::OLD_DESCRIPTION)
            ->update([
                'meta_title' => self::NEW_TITLE,
                'meta_description' => self::NEW_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'home')
            ->where('meta_title', self::NEW_TITLE)
            ->where('meta_description', self::NEW_DESCRIPTION)
            ->update([
                'meta_title' => self::OLD_TITLE,
                'meta_description' => self::OLD_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }
};
