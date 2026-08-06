<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewrites the Home page's hero and most of its layout to match the client's
 * content document ("FASTORA WEBSITE CONTENT COPY"), and adds a new
 * "Our impact at a glance" section the document introduced that the page
 * didn't have.
 *
 * Guarded on the old `whyFastora` block still reading "A strategic partner,
 * not just another vendor" — the seeded, unedited copy. If an editor has
 * already changed it, this leaves the page alone rather than overwriting
 * their edit with content that is, from the database's point of view,
 * arbitrary.
 *
 * `trustedBy`, `ourProcess` and `testimonialsBlock` are untouched: the
 * document either doesn't cover them or (for testimonials) explicitly asks
 * for real client content to be supplied later rather than guessed at now.
 * Media ids on `aboutFastora` and `trustedBy` logos are preserved exactly.
 */
return new class extends Migration
{
    private const OLD_WHY_FASTORA_HEADING = 'A strategic partner, not just another vendor';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $whyFastoraIndex = $this->findByHeading($layout, 'whyFastora', self::OLD_WHY_FASTORA_HEADING);
        $aboutIndex = $this->findByType($layout, 'aboutFastora');

        if ($whyFastoraIndex === null || $aboutIndex === null) {
            return;
        }

        $reference = require database_path('data/reference-home-page.php');

        $rebuilt = [];

        foreach ($layout as $index => $block) {
            $type = $block['type'] ?? null;

            if ($index === $aboutIndex) {
                $rebuilt[] = [
                    'type' => 'aboutFastora',
                    'data' => [...$reference['about_fastora'], 'image' => $block['data']['image'] ?? null],
                ];
                $rebuilt[] = ['type' => 'whyFastora', 'data' => $reference['impact_at_a_glance']];

                continue;
            }

            if ($index === $whyFastoraIndex) {
                $rebuilt[] = ['type' => 'whyFastora', 'data' => $reference['why_fastora']];

                continue;
            }

            $replacement = match ($type) {
                'servicesOverview' => $reference['services_overview'],
                'selectedWork' => $reference['selected_work'],
                'latestInsights' => $reference['latest_insights'],
                'faq' => $reference['faq'],
                'cta' => $reference['cta'],
                default => null,
            };

            $rebuilt[] = $replacement !== null ? ['type' => $type, 'data' => $replacement] : $block;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'hero_eyebrow' => $reference['hero_eyebrow'],
            'hero_rich_text' => $reference['hero_rich_text'],
            'hero_links' => json_encode($reference['hero_links']),
            'layout' => json_encode($rebuilt),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $impactIndex = $this->findByHeading($layout, 'whyFastora', 'Our impact at a glance');
        $whyFastoraIndex = $this->findByHeading($layout, 'whyFastora', 'We think before we create.');
        $aboutIndex = $this->findByType($layout, 'aboutFastora');

        if ($impactIndex === null || $whyFastoraIndex === null || $aboutIndex === null) {
            return;
        }

        $rebuilt = [];

        foreach ($layout as $index => $block) {
            if ($index === $impactIndex) {
                continue; // Dropped: this block didn't exist before.
            }

            $type = $block['type'] ?? null;

            if ($index === $aboutIndex) {
                $rebuilt[] = [
                    'type' => 'aboutFastora',
                    'data' => [
                        'heading' => 'Good work deserves to be noticed, understood, and remembered.',
                        'richText' => '<p>Many businesses are genuinely good at what they do. Capable teams, quality products, years of experience. Yet they are overlooked because they struggle to communicate their value.</p><p>Fastora exists to close that gap. We help businesses communicate more effectively so they become easier to understand, easier to trust, and harder to ignore.</p>',
                        'image' => $block['data']['image'] ?? null,
                        'linkLabel' => 'More about Fastora',
                        'linkUrl' => '/about',
                        'stats' => [
                            ['value' => '10', 'label' => 'Services under one team'],
                            ['value' => 'Africa', 'label' => 'Rooted here, working globally'],
                        ],
                    ],
                ];

                continue;
            }

            if ($index === $whyFastoraIndex) {
                $rebuilt[] = [
                    'type' => 'whyFastora',
                    'data' => [
                        'eyebrow' => null,
                        'heading' => self::OLD_WHY_FASTORA_HEADING,
                        'points' => [
                            ['stat' => '10+', 'title' => 'Integrated services', 'description' => 'From strategy to execution, communications and digital work live under one accountable team, not scattered across vendors.'],
                            ['stat' => 'Strategy-first', 'title' => 'We think before we create', 'description' => 'Every recommendation starts with understanding your business, not a template. Strategy guides everything we produce.'],
                            ['stat' => 'Africa', 'title' => 'Proudly African, globally minded', 'description' => "We're committed to raising the standard of business communication across Africa while serving clients and partners around the world."],
                        ],
                    ],
                ];

                continue;
            }

            $replacement = match ($type) {
                'servicesOverview' => ['eyebrow' => 'What we do', 'heading' => 'Services built around how you communicate', 'limit' => 6],
                'selectedWork' => ['eyebrow' => 'Selected work', 'heading' => 'Results, not just deliverables', 'limit' => 3],
                'latestInsights' => ['eyebrow' => 'Insights', 'heading' => 'Recent thinking', 'limit' => 3],
                'faq' => [
                    'heading' => 'Questions, answered directly',
                    'items' => [
                        ['question' => 'What does Fastora do?', 'answer' => 'Fastora is a communications and digital strategy company. We help businesses communicate more effectively through strategic communications, brand consulting, content strategy, reputation management, founder branding, social media management, copywriting, digital marketing, marketing strategy, and communication advisory, all working toward one goal: helping you become easier to understand, easier to trust, and harder to ignore.'],
                        ['question' => 'How is Fastora different from a typical marketing agency?', 'answer' => 'We start with strategy, not content production. Before we write a caption or launch a campaign, we take time to understand your business, audience, and communication challenge, then build a plan execution can actually follow.'],
                        ['question' => 'How quickly can we start working together?', 'answer' => 'Most engagements begin with a consultation to understand your goals, followed by a proposal within a few days. From there, timelines depend on scope, but we move as quickly as good strategy allows.'],
                        ['question' => 'Does Fastora work with businesses outside Africa?', 'answer' => "Yes. We're proudly African and committed to raising the standard of business communication across Africa, while serving clients and partners around the world."],
                    ],
                ],
                'cta' => [
                    'richText' => '<h2>Ready to start your project?</h2><p>Tell us where you want to go, we\'ll come back with how to get there.</p>',
                    'links' => [['label' => 'Book a Consultation', 'url' => '/consultation', 'appearance' => 'default']],
                ],
                default => null,
            };

            $rebuilt[] = $replacement !== null ? ['type' => $type, 'data' => $replacement] : $block;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'hero_eyebrow' => 'Communications & Digital Strategy',
            'hero_rich_text' => '<h1>Communication that earns attention.</h1><p>Fastora is a communications and digital strategy company that helps businesses communicate with purpose, strengthen their brand, and earn the trust they deserve.</p>',
            'hero_links' => json_encode([
                ['label' => 'Book a Consultation', 'url' => '/consultation', 'appearance' => 'default'],
                ['label' => 'View case studies', 'url' => '/case-studies', 'appearance' => 'outline'],
            ]),
            'layout' => json_encode($rebuilt),
            'updated_at' => now(),
        ]);
    }

    private function findByType(array $layout, string $type): ?int
    {
        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) === $type) {
                return $index;
            }
        }

        return null;
    }

    private function findByHeading(array $layout, string $type, string $heading): ?int
    {
        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) === $type && ($block['data']['heading'] ?? null) === $heading) {
                return $index;
            }
        }

        return null;
    }
};
