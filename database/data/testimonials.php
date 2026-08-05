<?php

/**
 * Real client testimonials, replacing the demo ones.
 *
 * Sources: the Client Reviews page of the founder's portfolio, and a LinkedIn
 * recommendation. Shared by the seeder and the migration that applies them.
 *
 * On the wording: these were written about the founder by name, and the client
 * asked for that name to read as Fastora. The substitution is the only edit —
 * every other word is the reviewer's. It is defensible because the work
 * described is the work Fastora does, but it does mean the quotes no longer
 * match their sources verbatim.
 *
 * Deliberately NOT included, and why:
 *
 *  - Stephen Salami's LinkedIn recommendation. He describes himself as a
 *    "longtime friend" who worked on the same team, so it is a colleague's
 *    reference rather than a client's. Presenting it among client reviews would
 *    misrepresent the relationship.
 *  - Two Upwork reviews with no attributable reviewer ("Marketing team needed for
 *    new app launch", "Client Outreach, through Social Media"). An unattributed
 *    quote carries no weight and cannot be verified by a reader.
 *  - The "Virtual Assistance" Upwork review. It opens with "Contract ended early"
 *    and misspells the name; it would work against the section it sits in.
 *  - The Upwork "Social Media and Brand Manager" review is the same text as
 *    Rasheem A.'s, so it is here once rather than twice.
 *
 * Avatars stay null. There are photographs on the portfolio page but not in this
 * repository, and standing the logo in as a face was wrong the first time it was
 * tried. Uploading each person's photo in the admin is the fix, if they consent.
 *
 * @return array<int, array<string, mixed>>
 */
return [
    [
        'client_name' => 'Anthony E.',
        'role' => 'Founder',
        'company' => 'Biografrica',
        'quote' => 'Fastora was instrumental in growing our social media presence from scratch to over 10K followers in a year. Their expertise in social media management and growth strategies is unmatched, and we would highly recommend their services.',
        'rating' => 5,
        'show_on_home' => true,
        'service_slug' => 'digital-marketing',
    ],
    [
        'client_name' => 'Sarah E.',
        'role' => 'CEO',
        'company' => 'Society for the Performing Arts in Nigeria',
        'quote' => "Working with Fastora has been transformative for our performing arts school. Their work goes beyond standard social media management, it's a comprehensive and highly effective growth marketing strategy. Their services significantly boosted our enrolment rates, and we feel immensely grateful for their contributions.",
        'rating' => 5,
        'show_on_home' => true,
        'service_slug' => 'digital-marketing',
    ],
    [
        'client_name' => 'Rasheem A.',
        'role' => 'Creative Director',
        'company' => 'Survive & Conquer',
        'quote' => "Fastora did a great job managing our social media. They were consistent, creative, and delivered high-quality content that aligned with our brand's message. They communicated clearly, met deadlines, and were easy to work with throughout the project. I'd definitely recommend them to others looking for a reliable and skilled social media manager.",
        'rating' => 5,
        'show_on_home' => true,
        'service_slug' => 'digital-marketing',
    ],
    [
        'client_name' => 'Michael A.',
        'role' => 'Founder',
        'company' => 'ADM Creative Media',
        // A collaborator rather than a paying client — he managed the founder
        // directly on shared projects. Kept because it speaks to communications
        // work specifically, which the other three do not, but it is the one here
        // whose relationship is professional rather than client-supplier.
        'quote' => "I've worked with Fastora on several projects and they have consistently proven to be a reliable communications expert, especially in grassroots strategy. Their strategic thinking and problem-solving skills have helped us navigate key challenges with clarity and impact.",
        'rating' => 5,
        'show_on_home' => false,
        'service_slug' => 'communications-strategy',
    ],
];
