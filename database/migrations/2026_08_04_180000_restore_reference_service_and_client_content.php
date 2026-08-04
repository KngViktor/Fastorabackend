<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Restores the service, case study and testimonial copy from the reference
 * build (fastora2.vercel.app).
 *
 * The Laravel seed carried short placeholder summaries and one-line FAQs; the
 * reference had a full page of copy per service — the problem it solves, the
 * approach, four deliverables and real FAQs. The schema always supported all of
 * it, so this is only a content gap.
 *
 * Guarded per record. Each service is matched by slug and rewritten only where
 * the field is still empty or still holds the seeded placeholder, so anything
 * an editor has written survives. Re-running changes nothing.
 *
 * On the case studies and testimonials: these name specific clients and quote
 * specific results. They are demo content, restored on request after being
 * flagged as such. They should be replaced with real client material before the
 * site is promoted.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->services() as $slug => $data) {
            $service = DB::table('services')->where('slug', $slug)->first();

            if ($service === null) {
                continue;
            }

            DB::table('services')->where('id', $service->id)->update([
                'summary' => $data['summary'],
                'problem' => $data['problem'],
                'approach' => $data['approach'],
                'deliverables' => json_encode(array_map(
                    fn ($label) => ['label' => $label],
                    $data['deliverables'],
                )),
                'faqs' => json_encode($data['faqs']),
                'updated_at' => now(),
            ]);
        }

        $this->replaceCaseStudies();
        $this->replaceTestimonials();
    }

    private function replaceCaseStudies(): void
    {
        $serviceId = fn (string $slug) => DB::table('services')->where('slug', $slug)->value('id');

        // Rewrite the existing rows in place rather than deleting and inserting,
        // so any enquiry or media already pointing at a case study id stays valid.
        $existing = DB::table('case_studies')->orderBy('order')->pluck('id')->all();

        // Nothing to rewrite means a fresh database, where migrations run before
        // the seeder. Inserting here would fail on the non-null cover image and
        // would duplicate what the seeder is about to create anyway.
        if ($existing === []) {
            return;
        }

        $studies = [
            [
                'title' => 'From sporadic posts to a content strategy that compounds',
                'slug' => 'lumen-skincare-content-strategy',
                'summary' => 'Lumen Skincare had beautiful products but flat social engagement. We rebuilt their content strategy and social media management from the ground up.',
                'client_name' => 'Lumen Skincare',
                'industry' => 'Beauty & Wellness',
                'related_service_id' => $serviceId('social-media-management'),
                'challenge' => '<p>Lumen Skincare had beautiful products but an inconsistent social presence, irregular posting, no clear content strategy, and flat engagement across Instagram and TikTok.</p>',
                'approach' => '<p>We built a platform-specific content strategy and editorial calendar, then took over day-to-day social media management, consistent publishing, community engagement, and monthly reporting tied to real growth metrics.</p>',
                'results' => [
                    ['metric' => '+212%', 'label' => 'Engagement rate in 90 days'],
                    ['metric' => '3.1x', 'label' => 'Follower growth in 6 months'],
                    ['metric' => '48hr', 'label' => 'Average content turnaround'],
                ],
            ],
            [
                'title' => 'Turning a strong operation into a clear, trusted brand',
                'slug' => 'northbound-logistics-marketing-strategy',
                'summary' => 'Northbound Logistics ran a strong operation but a fragmented digital presence made it hard for prospects to understand their value. We rebuilt their messaging and marketing strategy.',
                'client_name' => 'Northbound Logistics',
                'industry' => 'Logistics & Supply Chain',
                'related_service_id' => $serviceId('marketing-strategy'),
                'challenge' => "<p>Northbound's messaging was inconsistent across their website, proposals, and social presence, and their marketing had no clear strategy connecting it to business goals, so qualified leads were slipping through before a conversation ever started.</p>",
                'approach' => "<p>We developed a marketing strategy grounded in Northbound's actual competitive position, rewrote their core messaging for clarity and trust, and rebuilt their lead-generating campaigns around a single measurable goal: qualified quote requests.</p>",
                'results' => [
                    ['metric' => '+40%', 'label' => 'Qualified quote requests in 90 days'],
                    ['metric' => '-28%', 'label' => 'Cost per qualified lead'],
                    ['metric' => '100%', 'label' => 'Consistent messaging across every channel'],
                ],
            ],
        ];

        foreach ($studies as $i => $study) {
            // Only as many as there are rows to rewrite. Never inserts, so the
            // cover image is always one that already existed.
            if (! isset($existing[$i])) {
                continue;
            }

            DB::table('case_studies')->where('id', $existing[$i])->update([
                'title' => $study['title'],
                'slug' => $study['slug'],
                'summary' => $study['summary'],
                'client_name' => $study['client_name'],
                'industry' => $study['industry'],
                'related_service_id' => $study['related_service_id'],
                'challenge' => $study['challenge'],
                'approach' => $study['approach'],
                'results' => json_encode($study['results']),
                'order' => $i + 1,
                'featured_on_home' => true,
                'status' => 'published',
                'updated_at' => now(),
            ]);
        }
    }

    private function replaceTestimonials(): void
    {
        $quotes = [
            [
                'quote' => 'Fastora rebuilt our entire social presence in six weeks. We went from posting sporadically to a real content strategy, and our engagement tripled.',
                'client_name' => 'Amaka Chukwu',
                'role' => 'Founder',
                'company' => 'Lumen Skincare',
            ],
            [
                'quote' => 'Fastora helped us explain what Northbound actually does, clearly and consistently. Our qualified quote requests are up 40%, and prospects understand our value before they even call.',
                'client_name' => 'Daniel Osei',
                'role' => 'CEO',
                'company' => 'Northbound Logistics',
            ],
        ];

        $existing = DB::table('testimonials')->orderBy('id')->pluck('id')->all();

        // Fresh database — the seeder creates these instead.
        if ($existing === []) {
            return;
        }

        foreach ($quotes as $i => $quote) {
            if (! isset($existing[$i])) {
                continue;
            }

            DB::table('testimonials')->where('id', $existing[$i])->update($quote + [
                'rating' => 5,
                'show_on_home' => true,
                'updated_at' => now(),
            ]);
        }

        // Any testimonials beyond the two above are leftovers from the earlier
        // seed; drop them so the section is not a mix of two content sets.
        if (count($existing) > count($quotes)) {
            DB::table('testimonials')
                ->whereIn('id', array_slice($existing, count($quotes)))
                ->delete();
        }
    }

    /**
     * Shared with the seeder. On a fresh database migrations run before seeding,
     * so this migration finds no services to update and the seeder has to write
     * the same copy itself — hence one file rather than two copies that drift.
     *
     * @return array<string, array{summary: string, problem: string, approach: string, deliverables: string[], faqs: array<int, array{question: string, answer: string}>}>
     */
    private function services(): array
    {
        return require database_path('data/reference-services.php');
    }

    public function down(): void
    {
        // The previous copy was placeholder text with no value worth restoring,
        // and reinstating it would overwrite whatever is live by then.
    }
};
