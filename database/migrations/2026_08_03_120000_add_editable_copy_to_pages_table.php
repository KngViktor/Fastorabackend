<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Copy that was previously hardcoded in the Next.js pages, moved into the CMS.
 *
 *  - hero_eyebrow: the small label above the home hero headline, which lived
 *    in the HighImpact hero component.
 *  - faqs: the question/answer list rendered at the bottom of the services and
 *    contact pages, which lived in arrays in those route files.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('hero_eyebrow')->nullable()->after('hero_type');
            $table->json('faqs')->nullable()->after('page_header_description');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['hero_eyebrow', 'faqs']);
        });
    }
};
