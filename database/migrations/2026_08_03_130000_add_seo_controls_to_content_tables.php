<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-item SEO controls that the meta title/description/image fields did not
 * cover:
 *
 *  - meta_canonical_url: points search engines at the authoritative URL when
 *    the same content is reachable from more than one address.
 *  - meta_noindex: keeps a page out of search results while leaving it
 *    publicly reachable, for thank-you pages, campaign landers and the like.
 */
return new class extends Migration
{
    private const TABLES = ['pages', 'services', 'case_studies', 'posts'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('meta_canonical_url')->nullable()->after('meta_description');
                $t->boolean('meta_noindex')->default(false)->after('meta_canonical_url');
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['meta_canonical_url', 'meta_noindex']);
            });
        }
    }
};
