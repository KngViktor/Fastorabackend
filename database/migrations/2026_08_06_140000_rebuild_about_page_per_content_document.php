<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rebuilds the About page to match the client's content document, replacing
 * rather than extending the previous structure: Vision/Mission, the six core
 * values, "The problem we exist to solve", "Our approach", and the About-page
 * FAQ are all removed, because the document's About page has its own
 * structure and doesn't cover any of them.
 *
 * Guarded on the `visionMission` block still being present — a page an
 * editor has already reworked past that point is left alone.
 *
 * `hero_media_id` is a separate column and untouched: the document doesn't
 * supply a new hero image, so whatever is there stays.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout) || ! $this->hasType($layout, 'visionMission')) {
            return;
        }

        $reference = (require database_path('data/reference-about-page.php'))($this->resolveImageIds());

        DB::table('pages')->where('id', $page->id)->update([
            'hero_rich_text' => $reference['hero_rich_text'],
            'layout' => json_encode($reference['layout']),
            'updated_at' => now(),
        ]);
    }

    /**
     * Looked up by alt text rather than a hardcoded id, since these rows'
     * ids depend on import order and aren't stable across installs.
     */
    private function resolveImageIds(): array
    {
        $idFor = fn (string $alt) => DB::table('media')->where('alt', $alt)->value('id');

        return [
            'origin' => $idFor('A communications professional at work in a studio'),
            'process' => $idFor('Mapping a communications strategy across markets'),
            'audience' => $idFor('Planning content across digital channels'),
            'name' => $idFor('Reviewing performance figures on a tablet'),
        ];
    }

    /**
     * Restores the pre-rewrite structure. One known gap: the old
     * `aboutFastora` block's image can't be recovered here — it's a media id
     * this migration never captured, and by the time `down()` runs the
     * layout no longer contains it. It comes back with no image set; an
     * editor would need to re-pick one. Everything else is exact.
     */
    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'hero_rich_text' => '<h1>We help good businesses become impossible to overlook</h1>'
                . '<p>Fastora is a communications and digital strategy company. We help businesses communicate '
                . 'with purpose, strengthen their brands, and earn the attention they deserve.</p>',
            'layout' => json_encode([
                [
                    'type' => 'whyFastora',
                    'data' => [
                        'eyebrow' => 'At a glance',
                        'heading' => 'A snapshot of how we work',
                        'points' => [
                            ['stat' => '10', 'title' => 'Integrated services', 'description' => 'From strategic communications to digital marketing, delivered by one accountable team.'],
                            ['stat' => '6', 'title' => 'Core values', 'description' => 'Principles that guide every recommendation and every project we take on.'],
                            ['stat' => '100%', 'title' => 'In-house thinking', 'description' => 'No subcontracted black boxes, every strategy is developed and executed by our own team.'],
                        ],
                    ],
                ],
                [
                    'type' => 'content',
                    'data' => [
                        'richText' => '<h2>Our story</h2>'
                            . '<p>Fastora was founded on a simple observation: many businesses are genuinely good at what they do, '
                            . 'with capable teams and valuable services, yet they are overlooked because they struggle to '
                            . 'communicate their value.</p>'
                            . "<p>Inconsistent messaging. Websites that don't reflect the quality of the work behind them. Content "
                            . 'with no clear direction. Fastora exists to close that gap, helping businesses become easier to '
                            . 'understand, easier to trust, and harder to ignore.</p>',
                    ],
                ],
                [
                    'type' => 'content',
                    'data' => [
                        'richText' => '<h2>The problem we exist to solve</h2>'
                            . "<p>Every day, good businesses miss opportunities, not because they lack quality, but because people don't "
                            . 'fully understand who they are, what they do, or why they matter.</p>'
                            . '<p>We believe communication is one of the most valuable assets a business can invest in. When businesses '
                            . 'communicate well, trust grows faster and better opportunities follow.</p>',
                    ],
                ],
                [
                    'type' => 'visionMission',
                    'data' => [
                        'visionHeading' => 'Our vision',
                        'visionBody' => "<p>To become one of the world's most respected communications and digital strategy companies, helping "
                            . 'businesses, founders, and organisations communicate with confidence and build brands with lasting impact.</p>'
                            . "<p>We're proudly African, committed to raising the standard of business communication across Africa while "
                            . 'serving clients and partners around the world.</p>',
                        'missionHeading' => 'Our mission',
                        'missionBody' => '<p>To help businesses communicate with purpose, strengthen their brands, and build meaningful connections '
                            . 'through thoughtful strategy, compelling storytelling, and practical digital solutions.</p>'
                            . '<p>We listen before advising, think before creating, and execute with the same care from the first '
                            . 'conversation to long after a project ships.</p>',
                    ],
                ],
                [
                    'type' => 'whyFastora',
                    'data' => [
                        'eyebrow' => 'Our values',
                        'heading' => 'What guides our work',
                        'points' => [
                            ['stat' => 'Think first', 'title' => 'Think Before We Create', 'description' => "Every project begins with understanding, the client's business, goals, audience, and challenges, before we recommend anything."],
                            ['stat' => 'On purpose', 'title' => 'Communicate with Purpose', 'description' => 'Every message has a clear objective, whether to inform, persuade, reassure, or inspire action.'],
                            ['stat' => 'Consistency', 'title' => 'Build Trust Through Consistency', 'description' => 'Consistency creates recognition. Recognition builds confidence. Confidence strengthens trust.'],
                            ['stat' => 'Simplicity', 'title' => 'Keep Things Simple', 'description' => "Complex ideas don't need complicated explanations, our goal is always clarity over complexity."],
                            ['stat' => 'Excellence', 'title' => 'Deliver with Excellence', 'description' => 'From strategy and writing to design and execution, we hold every detail to a high standard.'],
                            ['stat' => 'Partnership', 'title' => 'Grow Through Partnership', 'description' => "We invest in our clients' ambitions and remain committed to their long-term growth. When they succeed, we succeed."],
                        ],
                    ],
                ],
                [
                    'type' => 'audienceGrid',
                    'data' => [
                        'eyebrow' => 'Who we serve',
                        'heading' => 'Organisations we work with',
                        'description' => "Regardless of industry, we're drawn to organisations that are serious about growth and committed to improving how they communicate.",
                        'items' => [
                            ['label' => 'Small and medium-sized businesses'],
                            ['label' => 'Startups'],
                            ['label' => 'Corporate organisations'],
                            ['label' => 'Professional service firms'],
                            ['label' => 'Founders and executives'],
                            ['label' => 'Non-profit organisations'],
                            ['label' => 'Educational institutions'],
                            ['label' => 'Government and development organisations'],
                            ['label' => 'Personal brands with established businesses'],
                        ],
                    ],
                ],
                [
                    'type' => 'content',
                    'data' => [
                        'richText' => '<h2>Our approach</h2>'
                            . '<p>Many agencies focus on producing more content. We focus on helping clients communicate more '
                            . 'effectively, starting with understanding the business, not a template.</p>'
                            . '<p>Every project we take on is guided by the same promise: to help good businesses become easier to '
                            . 'understand, easier to trust, and harder to ignore.</p>',
                    ],
                ],
                [
                    'type' => 'faq',
                    'data' => [
                        'heading' => 'Questions about working with us',
                        'items' => [
                            ['question' => 'What makes Fastora different from a traditional marketing agency?', 'answer' => 'We position ourselves as a communications and digital strategy partner, not a content factory. We start with strategy and positioning, then move into execution, so the work we produce is always tied to a clear business objective.'],
                            ['question' => 'Which industries does Fastora work with?', 'answer' => 'We work with SMEs, startups, corporate organisations, professional service firms, founders and executives, non-profits, educational institutions, and government or development organisations, any business serious about communicating better.'],
                            ['question' => 'Do you only work with businesses in Africa?', 'answer' => "No. We're proudly African and based in Africa, but we work with clients and partners globally."],
                            ['question' => 'How do we get started?', 'answer' => "Book a consultation through our contact page. We'll ask a few questions about your business and communication goals, then follow up with a proposal tailored to what you actually need."],
                        ],
                    ],
                ],
                [
                    'type' => 'cta',
                    'data' => [
                        'richText' => '<h2>Ready to communicate with more confidence?</h2>',
                        'links' => [
                            ['label' => 'Book a Consultation', 'url' => '/contact', 'appearance' => 'default'],
                        ],
                    ],
                ],
            ]),
            'updated_at' => now(),
        ]);
    }

    private function hasType(array $layout, string $type): bool
    {
        foreach ($layout as $block) {
            if (($block['type'] ?? null) === $type) {
                return true;
            }
        }

        return false;
    }
};
