<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the sections the content document gives every service page.
 *
 * The existing columns cover part of it — summary for the card line, problem for
 * the hero's supporting copy, approach for "Our approach", deliverables for
 * "What's included", faqs for the FAQs — but the document also specifies an
 * overview, an outcomes list, a good-fit list, the sub-services shown on the
 * card, related services, and a closing call to action per service.
 *
 * `includes` is separate from `deliverables` on purpose. They read similarly but
 * do different jobs: `includes` is the short list of former standalone services
 * now grouped under this one, shown on the card, while `deliverables` is the
 * longer "this service may include" list on the page itself.
 *
 * related_service_slugs holds slugs rather than ids, so the seeder and the
 * content migration can express the links without resolving ids first, and a
 * service can reference one created later in the same run.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('overview_heading')->nullable()->after('summary');
            $table->text('overview_copy')->nullable()->after('overview_heading');
            $table->json('outcomes')->nullable()->after('approach');
            $table->json('good_fit_if')->nullable()->after('outcomes');
            $table->json('includes')->nullable()->after('good_fit_if');
            $table->json('related_service_slugs')->nullable()->after('includes');
            $table->string('cta_heading')->nullable()->after('related_service_slugs');
            $table->text('cta_copy')->nullable()->after('cta_heading');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'overview_heading',
                'overview_copy',
                'outcomes',
                'good_fit_if',
                'includes',
                'related_service_slugs',
                'cta_heading',
                'cta_copy',
            ]);
        });
    }
};
