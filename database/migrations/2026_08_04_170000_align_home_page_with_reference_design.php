<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Brings the home page in line with the reference build the client asked to
 * return to (fastora2.vercel.app).
 *
 * Three structural gaps, none of them fixable in the frontend because they are
 * content rather than layout:
 *
 *  - No FAQ section existed at all, though the block and its FAQPage JSON-LD
 *    were already built.
 *  - "How we work" sat after the testimonials; in the reference it follows the
 *    stats and precedes the case studies.
 *  - The stats block made numeric performance claims (89%, 150+, 2.4x) that no
 *    engagement backs up. The reference used qualitative values instead, so
 *    following it here also removes three invented metrics from a live site.
 *
 * The trustedBy and aboutFastora blocks stay where they are: they were added
 * after the reference was built, on request, so this is a merge rather than a
 * straight revert.
 *
 * Guarded throughout. Blocks are matched by type and only rewritten when they
 * still hold the seeded copy, so an editor's wording is never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout) || $layout === []) {
            return;
        }

        foreach ($layout as $i => $block) {
            $type = $block['type'] ?? null;

            if ($type === 'whyFastora' && ($block['data']['heading'] ?? null) === 'Results our clients can point to') {
                $layout[$i]['data'] = [
                    'eyebrow' => null,
                    'heading' => 'A strategic partner, not just another vendor',
                    'points' => [
                        [
                            // "10+" rather than the reference's literal "0+", which
                            // rendered as a broken-looking zero: the count-up reads
                            // the leading digits, and the source value really was 0.
                            'stat' => '10+',
                            'title' => 'Integrated services',
                            'description' => 'From strategy to execution, communications and digital work live under one accountable team, not scattered across vendors.',
                        ],
                        [
                            'stat' => 'Strategy-first',
                            'title' => 'We think before we create',
                            'description' => 'Every recommendation starts with understanding your business, not a template. Strategy guides everything we produce.',
                        ],
                        [
                            'stat' => 'Africa',
                            'title' => 'Proudly African, globally minded',
                            'description' => "We're committed to raising the standard of business communication across Africa while serving clients and partners around the world.",
                        ],
                    ],
                ];
            }

            if ($type === 'ourProcess' && ($block['data']['heading'] ?? null) === 'A process built for clarity') {
                $layout[$i]['data'] = [
                    'eyebrow' => null,
                    'heading' => 'How we work with you',
                    'steps' => [
                        ['title' => 'Listen & understand', 'description' => 'We start every engagement by understanding your business, audience, and communication challenge, before recommending anything.'],
                        ['title' => 'Strategise', 'description' => 'We translate that understanding into a clear, practical strategy connected to your actual business objectives.'],
                        ['title' => 'Create & execute', 'description' => 'We bring the strategy to life, content, campaigns, messaging, and digital execution, with the same care at every step.'],
                        ['title' => 'Review & grow', 'description' => "We measure what matters, share what we're learning, and refine the approach as your business and audience evolve."],
                    ],
                ];
            }
        }

        $layout = $this->moveProcessBeforeSelectedWork($layout);
        $layout = $this->appendFaq($layout);

        $update = [
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ];

        // Hero copy, matching the reference word for word. Only rewritten while
        // it still holds the seeded wording, so edited copy survives.
        if (str_contains((string) $page->hero_rich_text, 'not just spend it')) {
            $update['hero_rich_text'] = '<h1>Communication that earns attention.</h1>'
                . '<p>Fastora is a communications and digital strategy company that helps businesses '
                . 'communicate with purpose, strengthen their brand, and earn the trust they deserve.</p>';
        }

        $heroLinks = json_decode($page->hero_links ?? '[]', true);

        if (is_array($heroLinks)) {
            $changed = false;

            foreach ($heroLinks as $i => $link) {
                if (($link['label'] ?? null) === 'View our work') {
                    $heroLinks[$i]['label'] = 'View case studies';
                    $changed = true;
                }
            }

            if ($changed) {
                $update['hero_links'] = json_encode($heroLinks);
            }
        }

        DB::table('pages')->where('id', $page->id)->update($update);
    }

    /**
     * @param  array<int, array<string, mixed>>  $layout
     * @return array<int, array<string, mixed>>
     */
    private function moveProcessBeforeSelectedWork(array $layout): array
    {
        $from = $this->indexOfType($layout, 'ourProcess');
        $to = $this->indexOfType($layout, 'selectedWork');

        // Nothing to do if either block is absent, or the order is already right.
        if ($from === null || $to === null || $from < $to) {
            return $layout;
        }

        $block = $layout[$from];
        unset($layout[$from]);
        $layout = array_values($layout);

        $to = $this->indexOfType($layout, 'selectedWork');
        array_splice($layout, $to, 0, [$block]);

        return $layout;
    }

    /**
     * @param  array<int, array<string, mixed>>  $layout
     * @return array<int, array<string, mixed>>
     */
    private function appendFaq(array $layout): array
    {
        if ($this->indexOfType($layout, 'faq') !== null) {
            return $layout;
        }

        $faq = [
            'type' => 'faq',
            'data' => [
                'heading' => 'Questions, answered directly',
                'items' => [
                    [
                        'question' => 'What does Fastora do?',
                        'answer' => 'Fastora is a communications and digital strategy company. We help businesses communicate more effectively through strategic communications, brand consulting, content strategy, reputation management, founder branding, social media management, copywriting, digital marketing, marketing strategy, and communication advisory, all working toward one goal: helping you become easier to understand, easier to trust, and harder to ignore.',
                    ],
                    [
                        'question' => 'How is Fastora different from a typical marketing agency?',
                        'answer' => 'We start with strategy, not content production. Before we write a caption or launch a campaign, we take time to understand your business, audience, and communication challenge, then build a plan execution can actually follow.',
                    ],
                    [
                        'question' => 'How quickly can we start working together?',
                        'answer' => 'Most engagements begin with a consultation to understand your goals, followed by a proposal within a few days. From there, timelines depend on scope, but we move as quickly as good strategy allows.',
                    ],
                    [
                        'question' => 'Does Fastora work with businesses outside Africa?',
                        'answer' => "Yes. We're proudly African and committed to raising the standard of business communication across Africa, while serving clients and partners around the world.",
                    ],
                ],
            ],
        ];

        // Directly before the closing call to action, which should stay last.
        $ctaIndex = $this->indexOfType($layout, 'cta');

        if ($ctaIndex === null) {
            $layout[] = $faq;

            return $layout;
        }

        array_splice($layout, $ctaIndex, 0, [$faq]);

        return $layout;
    }

    /**
     * @param  array<int, array<string, mixed>>  $layout
     */
    private function indexOfType(array $layout, string $type): ?int
    {
        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) === $type) {
                return $i;
            }
        }

        return null;
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

        // Only the FAQ is removed. The reordering and the copy changes have no
        // meaningful "before" to restore, and reinstating invented metrics on a
        // rollback would be the wrong default.
        $layout = array_values(array_filter(
            $layout,
            fn ($block) => ($block['type'] ?? null) !== 'faq',
        ));

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }
};
