<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aligns the Services page header with the client's content document.
 *
 * Guarded on the exact old heading, so an edited header isn't overwritten.
 */
return new class extends Migration
{
    private const OLD_HEADING = 'Services built around how you communicate';

    private const OLD_DESCRIPTION = 'Integrated services, each designed to help your business communicate with more clarity, credibility, and confidence.';

    private const NEW_HEADING = 'Services built around how people experience your business.';

    private const NEW_DESCRIPTION = 'Every interaction shapes how people think about your business. Our services help you communicate more intentionally, strengthen your brand, and support long-term growth.';

    public function up(): void
    {
        DB::table('pages')
            ->where('slug', 'services')
            ->where('page_header_heading', self::OLD_HEADING)
            ->where('page_header_description', self::OLD_DESCRIPTION)
            ->update([
                'page_header_heading' => self::NEW_HEADING,
                'page_header_description' => self::NEW_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'services')
            ->where('page_header_heading', self::NEW_HEADING)
            ->where('page_header_description', self::NEW_DESCRIPTION)
            ->update([
                'page_header_heading' => self::OLD_HEADING,
                'page_header_description' => self::OLD_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }
};
