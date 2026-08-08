<?php

/**
 * The About page as written in the client's content document
 * ("FASTORA WEBSITE CONTENT COPY"), shared by the seeder and the migration
 * that rebuilds it on an existing database.
 *
 * This replaces an earlier reference build wholesale: Vision/Mission, the
 * six core values, "The problem we exist to solve", "Our approach", and the
 * About-page FAQ are all gone, because the document's About page doesn't
 * cover them — it has its own structure (Story, principles, team,
 * experience, process, audience, name origin), and superseding rather than
 * appending is what was asked for.
 *
 * The image ids are filled in by the caller, for the same reason as before:
 * they're environment-specific. This file returns a function rather than a
 * plain array so the caller can pass them in — four of the plain-text
 * sections below now carry a photo each, alternating sides down the page
 * rather than reading as an unbroken stack of paragraphs.
 *
 * @param array{origin: int, process: int, audience: int, name: int, kator: int, emmanuel: int, ndidiamaka: int} $images
 */
return function (array $images): array {
    return [
    'hero_rich_text' => '<h1>Good businesses deserve to be understood.</h1>'
        . '<p>Fastora is a communications and digital strategy company that helps businesses '
        . 'present themselves in ways that reflect the quality of what they do. We work with '
        . 'businesses, founders, and organisations to strengthen their communication, shape '
        . 'their brand, and build a stronger digital presence through thoughtful strategy and '
        . 'execution.</p>',

    'layout' => [
        [
            'type' => 'content',
            'data' => [
                'richText' => '<h2>How Fastora began</h2>'
                    . '<p>Every business has a story.</p>'
                    . '<p>Ours began with an observation. Time and again, we saw businesses with '
                    . "great products and services struggle to communicate their value in a way "
                    . 'people understood.</p>'
                    . '<p>Many business owners came to us after trying different approaches that '
                    . 'never quite solved the problem. They had already invested time and money, '
                    . 'only to realise they still needed to start over.</p>'
                    . '<p>Fastora was created to help businesses get it right from the beginning.</p>'
                    . '<p>Today, we partner with businesses, founders, and organisations that want '
                    . 'to communicate with greater confidence, build stronger brands, and create '
                    . 'meaningful connections with the people they serve.</p>',
                'image' => $images['origin'],
                'imagePosition' => 'right',
            ],
        ],

        [
            'type' => 'whyFastora',
            'data' => [
                'eyebrow' => 'What guides our work',
                'heading' => 'The principles behind every recommendation',
                // No `title` per point, deliberately — see the matching Home
                // page whyFastora instance for the same treatment.
                'points' => [
                    [
                        'stat' => 'Be Understood',
                        'title' => '',
                        'description' => "A business shouldn't miss opportunities because people struggle to understand what it does. We help close that gap.",
                    ],
                    [
                        'stat' => 'Think First',
                        'title' => '',
                        'description' => "Design, content, campaigns, and marketing all matter, but they work best when they're guided by thoughtful decisions rather than guesswork.",
                    ],
                    [
                        'stat' => 'One Clear Message',
                        'title' => '',
                        'description' => 'Your website, social media, presentations, proposals, and conversations all shape how people see your business. We help make sure they point in the same direction.',
                    ],
                ],
            ],
        ],

        [
            'type' => 'team',
            'data' => [
                'eyebrow' => 'Meet the team',
                'heading' => 'The people behind Fastora',
                'description' => 'Behind every project is a team that believes thoughtful work creates lasting results.',
                'members' => [
                    [
                        'name' => 'Kator Tarkaa',
                        'role' => 'Founder & Digital Communications Strategist',
                        'bio' => "Kator leads Fastora's strategy, helping businesses communicate more effectively through brand positioning, communications, content, and digital strategy. His work focuses on helping businesses present themselves with confidence and build stronger connections with the people they serve.",
                        'photo' => $images['kator'],
                    ],
                    [
                        'name' => 'Emmanuel Akaluese',
                        'role' => 'Operations Associate',
                        'bio' => 'Emmanuel helps keep projects moving from idea to delivery. He supports internal operations, coordinates workflows, and ensures client projects stay organised, efficient, and on schedule.',
                        'photo' => $images['emmanuel'],
                    ],
                    [
                        'name' => 'Ndidiamaka Eya',
                        'role' => 'Digital Communications Associate',
                        'bio' => 'Ndidiamaka supports the planning, coordination, and delivery of digital communications across client accounts. She helps ensure content is published consistently and that day-to-day communication reflects the quality and direction of each brand.',
                        'photo' => $images['ndidiamaka'],
                    ],
                ],
            ],
        ],

        [
            'type' => 'whyFastora',
            'data' => [
                'eyebrow' => 'Our experience',
                'heading' => 'A growing body of work',
                'description' => "Over the years, we've had the opportunity to work with businesses of "
                    . 'different sizes, industries, and ambitions. Every project has added to our '
                    . 'understanding of what helps businesses communicate more effectively.',
                'points' => [
                    ['stat' => '18+', 'title' => 'Years', 'description' => 'Combined experience.'],
                    ['stat' => '20+', 'title' => 'Clients', 'description' => 'Each with a different story to tell.'],
                    ['stat' => '640K+', 'title' => 'Growth', 'description' => 'Built through our work.'],
                    ['stat' => '10+', 'title' => 'Industries', 'description' => 'From healthcare to hospitality.'],
                    ['stat' => '4', 'title' => 'Continents', 'description' => 'Clients in Africa, Europe, North America, and Australia.'],
                ],
            ],
        ],

        [
            'type' => 'content',
            'data' => [
                'richText' => '<h2>A thoughtful process from start to finish</h2>'
                    . '<p>Every project begins with a conversation. Before recommending a direction, '
                    . "we take time to understand your business, the people you're trying to reach, "
                    . "and what you're hoping to achieve.</p>"
                    . '<p>From there, we develop a clear plan, bring it to life with care, and '
                    . "continue refining it as your business grows. It's a simple approach that helps "
                    . 'us get the work right from the start.</p>',
                'image' => $images['process'],
                'imagePosition' => 'left',
            ],
        ],

        [
            'type' => 'content',
            'data' => [
                'richText' => '<h2>Who we work with</h2>'
                    . '<p>We work with people building businesses they believe in.</p>'
                    . '<p>Some are taking their first steps. Others have been around for years and '
                    . 'are ready for a new chapter.</p>'
                    . '<p>Our role is to help them communicate in a way that gives people a reason '
                    . 'to notice, understand, and trust what they do.</p>',
                'image' => $images['audience'],
                'imagePosition' => 'right',
            ],
        ],

        [
            'type' => 'content',
            'data' => [
                'richText' => '<h2>A name inspired by the way we work</h2>'
                    . '<p>Fastora is a name we created, inspired by the idea of speed and getting '
                    . 'things right from the start.</p>'
                    . "<p>Over the years, we've met businesses that spent more time and money fixing "
                    . 'work that should have been done properly the first time.</p>'
                    . '<p>We wanted to build a company that helped people avoid that.</p>',
                'image' => $images['name'],
                'imagePosition' => 'left',
            ],
        ],

        [
            'type' => 'cta',
            'data' => [
                'richText' => "<h2>Let's build something people understand.</h2>"
                    . '<p>Every business deserves to be recognised for the quality of its work.</p>'
                    . "<p>Let's talk about where your business is today and where you want it to go.</p>",
                'links' => [
                    ['label' => 'Book a Conversation', 'url' => '/consultation', 'appearance' => 'default'],
                ],
            ],
        ],
    ],
    ];
};
