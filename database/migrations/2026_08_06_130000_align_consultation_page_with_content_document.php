<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aligns the consultation page with the client's content document. The
 * original creation migration (2026_08_04_240000) only ever inserts once, so
 * it can't apply this update to the page it already created — hence a
 * second migration here.
 *
 * Guarded on the hero's old supporting paragraph, which changes; the H1
 * itself ("Talk it through with us first.") reads identically in both the old
 * copy and the document, so it can't be used as the guard.
 */
return new class extends Migration
{
    private const OLD_HERO_MARKER = 'A focused 45 minutes';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'consultation')->first();

        if ($page === null || ! str_contains($page->hero_rich_text ?? '', self::OLD_HERO_MARKER)) {
            return;
        }

        $reference = require database_path('data/consultation-page.php');
        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $rebuilt = array_map(function (array $block) use ($reference) {
            foreach ($reference['layout'] as $referenceBlock) {
                if ($referenceBlock['type'] === ($block['type'] ?? null)) {
                    return $referenceBlock;
                }
            }

            return $block;
        }, $layout);

        DB::table('pages')->where('id', $page->id)->update([
            'hero_rich_text' => $reference['hero_rich_text'],
            'layout' => json_encode($rebuilt),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'consultation')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $old = [
            'ourProcess' => [
                'eyebrow' => 'What happens',
                'heading' => 'How the session runs',
                'steps' => [
                    ['title' => 'You tell us the situation', 'description' => 'Where the business is, who you are trying to reach, and what is not landing.'],
                    ['title' => 'We ask the awkward questions', 'description' => 'The ones that surface what is actually causing the problem rather than the symptom.'],
                    ['title' => 'We name the next step', 'description' => 'A clear recommendation you can act on, in your own time, with or without us.'],
                ],
            ],
            'consultationForm' => [
                'eyebrow' => 'Request a session',
                'heading' => 'Pick a few times that suit you',
                'description' => 'Send two or three options and we will confirm one by email. Sessions run over video, or by phone if you prefer.',
                'idealFor' => [
                    ['label' => 'Businesses preparing to grow'],
                    ['label' => 'Founders refining positioning'],
                    ['label' => 'Organisations reviewing communication'],
                ],
                'submitLabel' => 'Request a session',
                'reassurance' => "Within one business day we'll confirm one of your preferred times by email.",
            ],
            'faq' => [
                'heading' => 'Before you book',
                'items' => [
                    ['question' => 'Is the session really free?', 'answer' => 'Yes. The first consultation is a conversation about your business and communication goals, with no cost and no obligation to proceed.'],
                    ['question' => 'What should I prepare?', 'answer' => 'Nothing formal. If you have a website, deck, or recent campaign you are unsure about, send it ahead and we will look at it before we speak.'],
                    ['question' => 'Who will I be speaking to?', 'answer' => 'Someone senior enough to give you a straight answer. These sessions are not run by a sales team.'],
                    ['question' => 'What if none of my suggested times work for you?', 'answer' => 'We will reply with the nearest alternatives. Sending two or three options usually means we can confirm on the first reply.'],
                ],
            ],
        ];

        $rebuilt = array_map(function (array $block) use ($old) {
            $type = $block['type'] ?? null;

            return isset($old[$type]) ? ['type' => $type, 'data' => $old[$type]] : $block;
        }, $layout);

        DB::table('pages')->where('id', $page->id)->update([
            'hero_rich_text' => '<h1>Talk it through with us first.</h1>'
                . '<p>A focused 45 minutes on your business, your audience, and the communication '
                . 'problem in front of you. No pitch deck, no obligation — you leave with a clear '
                . 'view of what to do next, whether or not you work with us.</p>',
            'layout' => json_encode($rebuilt),
            'updated_at' => now(),
        ]);
    }
};
