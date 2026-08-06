<?php

/**
 * The consultation page, shared by the seeder and the migration that creates it
 * on an existing database — same reason as the other data files: migrations run
 * before seeding, so neither path can rely on the other.
 *
 * Every string here is editable in the admin afterwards, including the button
 * label and the reassurance line, so this is a starting point rather than fixed
 * copy.
 */
return [
    'title' => 'Book a Consultation',
    'slug' => 'consultation',
    'hero_type' => 'lowImpact',
    'hero_eyebrow' => '45-minute strategic session',
    'hero_rich_text' => '<h1>Talk it through with us first.</h1>'
        . '<p>Every project starts with a conversation. We\'ll spend time understanding your '
        . "business, the people you're trying to reach, and the challenges you're facing. By the "
        . "end, you'll have a better idea of what comes next, whether that's working with us or "
        . 'taking the next step on your own.</p>',

    'layout' => [
        [
            'type' => 'ourProcess',
            'data' => [
                'eyebrow' => 'What to expect',
                'heading' => 'How the conversation works',
                'intro' => "There's no presentation to sit through and no pressure to make a "
                    . "decision. We'll simply spend time understanding your business and talking "
                    . "through what's in front of you.",
                'steps' => [
                    ['title' => 'You tell us about your business', 'description' => "We'll talk about what your business does, where you are today, and what you're hoping to achieve."],
                    ['title' => 'We ask the right questions', 'description' => "We'll explore the things that matter most so we understand the business before discussing possible solutions."],
                    ['title' => 'You leave with a clear next step', 'description' => "By the end of the conversation, you'll have a better understanding of where to focus and what comes next."],
                ],
            ],
        ],

        [
            'type' => 'consultationForm',
            'data' => [
                'eyebrow' => 'Request a conversation',
                'heading' => 'Pick a few times that work for you',
                'description' => "Share two or three dates and times that suit you, and we'll "
                    . 'confirm one by email. Conversations take place online, so you can join from '
                    . "wherever you're based.",
                'idealFor' => [
                    ['label' => 'Businesses preparing for their next stage of growth.'],
                    ['label' => "Founders refining how they're seen."],
                    ['label' => 'Organisations reviewing how they communicate.'],
                ],
                'submitLabel' => 'Request a Conversation',
                'reassurance' => "We'll get back to you within one business day to confirm your conversation.",
            ],
        ],

        [
            'type' => 'faq',
            'data' => [
                'heading' => 'Before you book',
                'items' => [
                    [
                        'question' => 'Do I need to know which service I need?',
                        'answer' => "Not at all. Many conversations begin with a challenge rather than a clear solution. We'll help you decide what makes the most sense after we've learned more about your business.",
                    ],
                    [
                        'question' => 'Is the first conversation free?',
                        'answer' => "Yes. It's an opportunity for us to understand your business, answer your questions, and see whether we're the right fit to work together.",
                    ],
                    [
                        'question' => 'What should I prepare?',
                        'answer' => 'Nothing formal. It helps to have a general idea of your business, your goals, and the challenges you\'re facing. The rest is simply a conversation.',
                    ],
                    [
                        'question' => 'Who will I be speaking to?',
                        'answer' => "You'll be speaking with Kator Tarkaa, Founder & Digital Communications Strategist at Fastora. He'll lead the conversation, answer your questions, and help you think through the best next step for your business.",
                    ],
                    [
                        'question' => 'What if none of my suggested times work?',
                        'answer' => "We'll get in touch and find another time that works for both of us.",
                    ],
                ],
            ],
        ],
    ],

    'meta_title' => 'Book a Consultation',
    'meta_description' => 'A free 45-minute strategic session on your business, your audience, and the communication problem in front of you.',
];
