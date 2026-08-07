<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gives case studies the sections the client's case study documents specify.
 *
 * The table was built for a short "challenge / approach / metrics" summary. The
 * documents are a full page: a facts block (client, industry, location,
 * engagement dates, services delivered), then six narrative sections, a stats
 * block, an optional pull quote, an optional "one moment that stood out", a
 * closing takeaway, related services and a call to action.
 *
 * challenge and approach are renamed rather than left beside the new columns.
 * They already held exactly these two ideas under vaguer names — "the challenge"
 * is what we noticed, "our approach" is our thinking — so renaming keeps the
 * existing copy and avoids two columns that mean the same thing.
 *
 * related_service_slugs holds slugs rather than ids, matching services, so the
 * seeder and the content migration can express the links without resolving ids.
 * service_labels is separate and is plain text on purpose: the facts block lists
 * everything delivered on an engagement, including work like "Business
 * Development" that has never been a service page and so has nothing to link to.
 *
 * results_placement exists because the stats do not always sit in the same
 * place. Three documents report them at the end as "What changed"; Unity Key
 * reports them up front as "What the audit revealed", before the work is
 * described. One nullable column is cheaper than a block editor for a difference
 * that is only ever going to be one of two positions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->renameColumn('challenge', 'what_we_noticed');
            $table->renameColumn('approach', 'our_thinking');
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('location')->nullable()->after('industry');
            $table->string('engagement')->nullable()->after('location');
            $table->json('service_labels')->nullable()->after('engagement');
            $table->longText('hero_intro')->nullable()->after('summary');

            $table->longText('the_business')->nullable()->after('related_service_id');
            $table->longText('what_we_did')->nullable()->after('our_thinking');

            $table->string('results_heading')->nullable()->after('what_we_did');
            $table->longText('results_intro')->nullable()->after('results_heading');
            $table->longText('results_note')->nullable()->after('results_intro');
            // 'after_thinking' puts the stats before "What we did"; anything else
            // (the default) puts them after it.
            $table->string('results_placement')->nullable()->after('results_note');

            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('testimonial_role')->nullable();

            $table->string('standout_heading')->nullable();
            $table->longText('standout_copy')->nullable();

            $table->string('takeaway_heading')->nullable();
            $table->longText('takeaway_copy')->nullable();

            $table->json('related_service_slugs')->nullable();
            $table->string('cta_heading')->nullable();
            $table->text('cta_copy')->nullable();
            $table->string('cta_label')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'engagement',
                'service_labels',
                'hero_intro',
                'the_business',
                'what_we_did',
                'results_heading',
                'results_intro',
                'results_note',
                'results_placement',
                'testimonial_quote',
                'testimonial_author',
                'testimonial_role',
                'standout_heading',
                'standout_copy',
                'takeaway_heading',
                'takeaway_copy',
                'related_service_slugs',
                'cta_heading',
                'cta_copy',
                'cta_label',
            ]);
        });

        Schema::table('case_studies', function (Blueprint $table) {
            $table->renameColumn('what_we_noticed', 'challenge');
            $table->renameColumn('our_thinking', 'approach');
        });
    }
};
