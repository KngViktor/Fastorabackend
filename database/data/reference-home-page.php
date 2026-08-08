<?php

/**
 * The Home page as written in the client's content document
 * ("FASTORA WEBSITE CONTENT COPY"), shared by the seeder and the migration
 * that applies it to an existing database.
 *
 * Image ids, the confirmed-clients list, and the "How we work with you" steps
 * are filled in by the caller rather than defined here — the first two for
 * the same reason as reference-about-page.php (environment-specific ids, and
 * a list already maintained in its own file), the third because the content
 * document does not cover it and there is no reason to disturb copy it never
 * mentioned.
 *
 * The two "Why Fastora"-typed blocks are deliberate: the document's "Our
 * impact at a glance" (five numeric stats) and its later "Why Fastora?" (three
 * listen/connect/measure cards) are visually the same stat-card layout, so
 * both reuse the `whyFastora` block type rather than needing one each.
 */
return [
    'hero_eyebrow' => 'Communications & Digital Strategy',
    'hero_rich_text' => "<h1>People can't choose what they don't understand.</h1>"
        . "<p>Being good at what you do isn't always enough. Fastora helps businesses present "
        . 'themselves in ways that match the quality of what they do.</p>',
    'hero_links' => [
        ['label' => 'Book a Conversation', 'url' => '/consultation', 'appearance' => 'default'],
        ['label' => 'Explore Services', 'url' => '/services', 'appearance' => 'outline'],
    ],

    'about_fastora' => [
        'heading' => 'Good work deserves to be noticed, understood, and remembered.',
        'richText' => '<p>People make decisions based on what they understand.</p>'
            . '<p>When a business struggles to communicate its value, opportunities slip away.</p>'
            . "<p>Fastora helps businesses present themselves with confidence, so they're recognised "
            . 'for the quality they\'ve always had.</p>',
        'linkLabel' => 'More about Fastora',
        'linkUrl' => '/about',
        'stats' => [
            ['value' => '10+', 'label' => 'Specialised services working together.'],
            ['value' => 'Africa', 'label' => 'Built in Africa. Working wherever good businesses need us.'],
        ],
    ],

    'impact_at_a_glance' => [
        'eyebrow' => null,
        'heading' => 'Our impact at a glance',
        'description' => 'Behind every project is a team with experience across communications, '
            . 'branding, marketing, and digital strategy, working with businesses in different '
            . 'industries and markets.',
        'points' => [
            ['stat' => '18+', 'title' => 'Years', 'description' => 'Combined experience.'],
            ['stat' => '20+', 'title' => 'Clients', 'description' => 'Each with a different story to tell.'],
            ['stat' => '640K+', 'title' => 'Audience', 'description' => 'Added through our work.'],
            ['stat' => '10+', 'title' => 'Industries', 'description' => 'From healthcare to hospitality.'],
            ['stat' => '4', 'title' => 'Continents', 'description' => 'Clients across Africa, Europe, North America, and Australia.'],
        ],
    ],

    'services_overview' => [
        'eyebrow' => 'What we do',
        'heading' => 'Services built around how people experience your business.',
        'description' => 'Everything we do helps answer one question: "Why should someone choose '
            . "you?\" Different businesses need different answers. That's why our services work together.",
        'limit' => 6,
    ],

    'why_fastora' => [
        'eyebrow' => null,
        'heading' => 'We think before we create.',
        'description' => "It's a simple idea, but it shapes everything we do. Before we write, "
            . 'design, publish, or launch, we take time to understand the business behind the brief. '
            . 'Better decisions at the beginning usually lead to better outcomes at the end.',
        // No `title` per point, deliberately: just the stat word and the
        // description underneath it, one line lighter than the other
        // whyFastora instances on this page.
        'points' => [
            ['stat' => 'Listen', 'title' => '', 'description' => 'Good recommendations start with understanding the business, not making assumptions.'],
            ['stat' => 'Connect', 'title' => '', 'description' => 'Brand, content, communications, and marketing should reinforce one another.'],
            ['stat' => 'Measure', 'title' => '', 'description' => "We care less about what's delivered and more about what it helps you achieve."],
        ],
    ],

    'selected_work' => [
        'eyebrow' => 'Selected work',
        'heading' => 'Every project tells a bigger story.',
        'description' => "These are some of the businesses we've had the privilege of working with.",
        'limit' => 3,
    ],

    'testimonials_block' => [
        'eyebrow' => 'Client voices',
        'heading' => 'What clients say',
        'limit' => 3,
    ],

    'latest_insights' => [
        'eyebrow' => 'Insights',
        'heading' => 'From the Fastora Journal',
        'description' => 'Articles, observations, and practical insights on communication, '
            . 'branding, and digital strategy, shaped by our work and the businesses we serve.',
        'limit' => 3,
    ],

    'faq' => [
        'heading' => 'Questions, answered directly',
        'items' => [
            [
                'question' => 'What does Fastora do?',
                'answer' => 'Fastora is a communications and digital strategy company. We help '
                    . 'businesses communicate more effectively through strategy, branding, content, '
                    . 'digital marketing, and advisory services. We help businesses present themselves '
                    . 'in ways that reflect the quality of the work behind them.',
            ],
            [
                'question' => 'What makes Fastora different?',
                'answer' => 'Every business has its own story, ambitions, and challenges. We believe '
                    . "the best work begins with understanding all three. That's why we take time to "
                    . "learn about the business before recommending a direction. The result is work "
                    . "that's thoughtful, relevant, and built around what the business is trying to achieve.",
            ],
            [
                'question' => 'How quickly can we start working together?',
                'answer' => 'Every project begins with an initial conversation. Once we understand '
                    . "your goals and the scope of work, we'll recommend the next steps, agree on "
                    . 'timelines, and confirm availability. Smaller projects can often begin sooner, '
                    . 'while larger engagements may require additional planning.',
            ],
            [
                'question' => 'Does Fastora work with businesses outside Africa?',
                'answer' => 'Yes. Fastora was built in Africa, but we work with businesses, founders, '
                    . 'and organisations wherever thoughtful communication and digital strategy are '
                    . 'needed. Our experience is rooted in African markets, while our approach is '
                    . 'designed to support businesses across different industries and regions.',
            ],
            [
                'question' => 'Do I need all of your services?',
                'answer' => 'Not at all. Some clients come to us for a single project, while others '
                    . "choose ongoing support across several areas. We'll recommend only the services "
                    . 'that make sense for your goals and explain how they fit together if more than '
                    . 'one is needed.',
            ],
            [
                'question' => 'How do I know which service is right for my business?',
                'answer' => "You don't have to. We'll start with a conversation, understand where "
                    . "your business is today, and recommend only what's needed.",
            ],
            [
                'question' => 'Can we combine multiple services?',
                'answer' => 'Yes. Many of our best results come from combining strategy, '
                    . 'communications, content, and marketing into one coordinated approach.',
            ],
            [
                'question' => 'Do you only work on long-term retainers?',
                'answer' => 'No. Some clients need a single project. Others need an ongoing '
                    . "communications partner. We'll recommend the approach that makes the most "
                    . 'sense for your goals.',
            ],
            [
                'question' => 'How long does a project usually take?',
                'answer' => "That depends on the scope of work. After our first conversation, we'll "
                    . 'outline what the project involves, how long it will take, and what happens at '
                    . 'each stage.',
            ],
        ],
    ],

    'cta' => [
        'richText' => '<h2>Ready to become harder to ignore?</h2>'
            . '<p>Tell us where your business is today and where you want it to go.</p>'
            . "<p>We'll help people understand why you're worth paying attention to.</p>",
        'links' => [
            ['label' => 'Book a Conversation', 'url' => '/consultation', 'appearance' => 'default'],
        ],
    ],
];
