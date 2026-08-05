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
        . '<p>A focused 45 minutes on your business, your audience, and the communication '
        . 'problem in front of you. No pitch deck, no obligation — you leave with a clear '
        . 'view of what to do next, whether or not you work with us.</p>',

    'layout' => [
        [
            'type' => 'ourProcess',
            'data' => [
                'eyebrow' => 'What happens',
                'heading' => 'How the session runs',
                'steps' => [
                    ['title' => 'You tell us the situation', 'description' => 'Where the business is, who you are trying to reach, and what is not landing.'],
                    ['title' => 'We ask the awkward questions', 'description' => 'The ones that surface what is actually causing the problem rather than the symptom.'],
                    ['title' => 'We name the next step', 'description' => 'A clear recommendation you can act on, in your own time, with or without us.'],
                ],
            ],
        ],

        [
            'type' => 'consultationForm',
            'data' => [
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
        ],

        [
            'type' => 'faq',
            'data' => [
                'heading' => 'Before you book',
                'items' => [
                    [
                        'question' => 'Is the session really free?',
                        'answer' => 'Yes. The first consultation is a conversation about your business and communication goals, with no cost and no obligation to proceed.',
                    ],
                    [
                        'question' => 'What should I prepare?',
                        'answer' => 'Nothing formal. If you have a website, deck, or recent campaign you are unsure about, send it ahead and we will look at it before we speak.',
                    ],
                    [
                        'question' => 'Who will I be speaking to?',
                        'answer' => 'Someone senior enough to give you a straight answer. These sessions are not run by a sales team.',
                    ],
                    [
                        'question' => 'What if none of my suggested times work for you?',
                        'answer' => 'We will reply with the nearest alternatives. Sending two or three options usually means we can confirm on the first reply.',
                    ],
                ],
            ],
        ],
    ],

    'meta_title' => 'Book a Consultation',
    'meta_description' => 'A free 45-minute strategic session on your business, your audience, and the communication problem in front of you.',
];
