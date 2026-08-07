<?php

/**
 * The four client case studies, shared by the seeder and the migration that
 * installs them on an existing database. Copy supplied directly by the client
 * in the "FASTORA WEBSITE CONTENT COPY" documents, not drafted here.
 *
 * 'cover' and 'gallery' name files in database/seeders/images. The importer
 * resolves them to media ids; the gallery captions are the ones written under
 * each image in the source documents.
 *
 * On service_labels vs related_service_slugs: the first is the facts block and
 * is plain text, because engagements included work such as "Business
 * Development" and "Communication Advisory" that are not service pages of their
 * own. The second is the linked "Related services" block, so it only ever holds
 * slugs that resolve. Energia's document lists Communication Advisory there,
 * which now sits under Communications Strategy — linking both would point at one
 * page twice, so it appears once.
 */
return [

    // ---------------------------------------------------------------- Biografrica

    [
        'slug' => 'biografrica-media-publishing',
        'title' => 'Helping a new media platform become a destination for African stories.',
        'summary' => "When Biografrica launched, it wasn't competing for information. It was competing for attention.",
        'hero_intro' => "<p>When Biografrica launched, it wasn't competing for information. It was competing for attention.</p>"
            . '<p>Our role was to help build a publishing system that gave people a reason to stop, read, share, and come back.</p>',
        'client_name' => 'Biografrica',
        'industry' => 'Media & Publishing',
        'location' => 'Africa',
        'engagement' => 'August 2022 – Present',
        'service_labels' => [
            'Communications Strategy',
            'Content & Storytelling',
            'Digital Marketing',
            'Business Development',
        ],
        'cover' => ['case-biografrica-digital-presence.jpg', "Biografrica's profile across Instagram, Facebook and Threads"],
        'gallery' => [
            ['case-biografrica-digital-presence.jpg', "Biografrica's digital presence"],
            ['case-biografrica-content-system.jpg', 'Biografrica content system'],
            ['case-biografrica-clay-story.jpg', 'A viral story about African clay products helped spark wider conversations that contributed to the launch of the Biografrica Marketplace.'],
        ],
        'the_business' => '<p>Biografrica is a media platform dedicated to telling African stories across business, politics, history, culture, and innovation.</p>'
            . "<p>Like many new media brands, it entered a crowded digital space where established publishers already had loyal audiences. The challenge wasn't finding stories worth telling. It was making sure those stories reached the people they were written for.</p>",
        'what_we_noticed' => "<p>Publishing more wasn't the answer.</p>"
            . '<p>The stories were already there. What was missing was a consistent way of presenting them so people would stop scrolling and pay attention.</p>'
            . "<p>There was another opportunity too. While the audience was beginning to grow, there wasn't yet a clear bridge between that attention and the platform's commercial ambitions.</p>"
            . '<p>Growth needed to support the business, not just the numbers.</p>',
        'our_thinking' => "<p>People rarely share information because it's new.</p>"
            . '<p>They share it because it makes them feel something.</p>'
            . '<p>That became the principle behind the strategy.</p>'
            . '<p>Instead of treating social media as a place to repost articles, we treated it as its own publishing platform. Every story needed a stronger angle, clearer structure, and a format that felt natural wherever people encountered it.</p>'
            . '<p>At the same time, we wanted the audience to become more than readers. We wanted the platform to build enough trust that it could eventually create products, partnerships, and new opportunities around that audience.</p>',
        'what_we_did' => '<p>Our work included:</p>'
            . '<ul>'
            . "<li>Developing Biografrica's social media strategy.</li>"
            . '<li>Creating a story-led editorial system across platforms.</li>'
            . '<li>Repurposing long-form journalism into carousels, reels, quote cards, Threads, and short-form videos.</li>'
            . '<li>Building recurring content series, including the African Spotlight Series.</li>'
            . '<li>Managing publishing workflows and editorial planning across multiple contributors.</li>'
            . '<li>Supporting business development with partnership materials and audience insights.</li>'
            . "<li>Helping shape the launch of Biografrica Marketplace, the platform's commercial extension.</li>"
            . '</ul>',
        'results_heading' => 'What changed',
        'results_intro' => '<p>The work produced consistent audience growth across platforms and helped position Biografrica as a recognised African media brand.</p>',
        'results' => [
            ['metric' => '60,000+', 'label' => 'Combined followers built across Instagram, Facebook, and Threads.'],
            ['metric' => '718,000+', 'label' => 'Average monthly reach.'],
            ['metric' => '1.9 million', 'label' => 'Instagram accounts reached in 90 days.'],
            ['metric' => '270,768', 'label' => 'Interactions in 90 days.'],
            ['metric' => '83.7%', 'label' => "Instagram reach coming from people who weren't already following the account."],
        ],
        'results_note' => '<p>The numbers told one story.</p>'
            . '<p>The audience behaviour told an even better one: new people continued discovering the platform month after month instead of engagement being limited to existing followers.</p>',
        'testimonial_quote' => 'Fastora was instrumental in growing our social media presence from scratch to over 10K followers in a year. Their expertise in social media management and growth strategies is unmatched, and we would highly recommend their services.',
        'testimonial_author' => 'Anthony E.',
        'testimonial_role' => 'Founder, Biografrica',
        'standout_heading' => 'One moment that stood out',
        'standout_copy' => '<p>One of the clearest examples of the strategy in action came from a story about clay products.</p>'
            . '<p>Instead of presenting it as a straightforward news update, we shaped it into a story people wanted to talk about.</p>'
            . "<p>The post reached far beyond Biografrica's existing audience, sparked widespread conversation, and demonstrated something bigger than a viral moment. It showed there was genuine demand for African-made products when they were presented in the right way.</p>"
            . '<p>That insight contributed to conversations that eventually led to the launch of Biografrica Marketplace, creating a space where attention could be connected to commerce.</p>',
        'takeaway_heading' => 'One takeaway',
        'takeaway_copy' => '<p>Building an audience is only part of the work.</p>'
            . '<p>The bigger opportunity is knowing what to do with that audience once it arrives.</p>'
            . '<p>For Biografrica, that meant building a publishing system people trusted, then using that trust to support new products, partnerships, and commercial opportunities.</p>',
        'related_service_slugs' => ['communications-strategy', 'content-and-storytelling', 'digital-marketing'],
        'cta_heading' => "Let's talk about your business.",
        'cta_copy' => '<p>Every business has stories worth telling.</p>'
            . '<p>The question is whether people are finding them.</p>',
        'order' => 1,
        'featured_on_home' => true,
        'meta_description' => 'How Fastora helped Biografrica build a story-led publishing system that grew a 60,000-strong audience and opened the door to Biografrica Marketplace.',
    ],

    // -------------------------------------------------------------------- Energia

    [
        'slug' => 'energia-corporate-communications',
        'title' => 'Helping a growing business communicate like one.',
        'summary' => 'Energia Limited had years of operational progress behind it, but very little of that story was reaching the people who mattered.',
        'hero_intro' => '<p>Energia Limited had years of operational progress behind it, but very little of that story was reaching the people who mattered.</p>'
            . '<p>Our role was to build the systems that helped the business communicate with greater consistency, both internally and externally.</p>',
        'client_name' => 'Energia Limited',
        'industry' => 'Energy',
        'location' => 'Nigeria',
        'engagement' => 'October 2024 – April 2026',
        'service_labels' => [
            'Communications Strategy',
            'Content & Storytelling',
            'Digital Marketing',
            'Communication Advisory',
        ],
        'cover' => ['case-energia-communications.jpg', 'Energia communication materials'],
        'gallery' => [
            ['case-energia-communications.jpg', 'Examples of communication materials developed during the engagement, from digital content to internal and corporate communications.'],
        ],
        'the_business' => '<p>Energia Limited is a Nigerian indigenous energy company with a long track record of operational growth.</p>'
            . "<p>Over the years, the business had expanded its production and continued to make significant progress. Yet much of that progress wasn't visible outside the organisation.</p>"
            . "<p>The business had a strong story. It simply wasn't being told consistently.</p>",
        'what_we_noticed' => "<p>The challenge wasn't a lack of content.</p>"
            . '<p>It was a lack of structure.</p>'
            . "<p>Communication happened when it was needed rather than as part of a coordinated system. Social media channels were inconsistent, leadership wasn't very visible online, and different parts of the business often communicated independently.</p>"
            . '<p>For a growing organisation, that creates missed opportunities with customers, partners, employees, and other stakeholders.</p>',
        'our_thinking' => '<p>Good communication should reflect the quality of the business behind it.</p>'
            . '<p>Instead of starting with a content calendar, we focused on building the systems that would make communication more consistent over time.</p>'
            . '<p>That meant improving not only what people saw on social media, but also how communication moved across the organisation. The goal was to create a stronger foundation that leadership and different teams could build on.</p>',
        'what_we_did' => '<p>Our work included:</p>'
            . '<ul>'
            . "<li>Reviving and managing the company's digital channels.</li>"
            . '<li>Developing content calendars and publishing workflows.</li>'
            . '<li>Creating communication processes that improved consistency.</li>'
            . '<li>Supporting executive communication and leadership visibility.</li>'
            . '<li>Producing newsletters, presentations, corporate announcements, and internal communication materials.</li>'
            . '<li>Working closely with Corporate Affairs and Human Resources to align communication across departments.</li>'
            . '</ul>',
        'results_heading' => 'What changed',
        'results_intro' => '<p>The business gained a stronger and more consistent communication system.</p>',
        'results' => [
            ['metric' => '4,000+', 'label' => 'LinkedIn community.'],
            ['metric' => '3x', 'label' => 'Growth in LinkedIn engagement within three months.'],
            ['metric' => '35%', 'label' => 'Faster communication turnaround.'],
            ['metric' => 'Consistent digital presence', 'label' => 'Previously inactive channels became active, coordinated communication platforms.'],
        ],
        'takeaway_heading' => 'One takeaway',
        'takeaway_copy' => "<p>Sometimes the biggest communication challenge isn't creating more content.</p>"
            . "<p>It's building a system that helps good work become visible.</p>"
            . '<p>For Energia, that meant creating a stronger foundation for communication across the organisation, giving leadership a more consistent voice and making it easier for the business to share its progress with the people who mattered.</p>',
        'related_service_slugs' => ['communications-strategy', 'content-and-storytelling'],
        'cta_heading' => "Let's talk about your business.",
        'cta_copy' => '<p>Growth deserves to be seen.</p>'
            . "<p>We'll help you build the systems that make that possible.</p>",
        'order' => 2,
        'featured_on_home' => true,
        'meta_description' => 'How Fastora built the communication systems that made an indigenous Nigerian energy company\'s operational progress visible to customers, partners and staff.',
    ],

    // ------------------------------------------------------------- Unity Key Group

    [
        'slug' => 'unity-key-group-real-estate',
        'title' => 'Building trust before asking people to buy.',
        'summary' => "Unity Key was already reaching new audiences on Instagram. The opportunity wasn't getting more people to see the business.",
        'hero_intro' => "<p>Unity Key was already reaching new audiences on Instagram. The opportunity wasn't getting more people to see the business. It was giving them enough confidence to take the next step.</p>",
        'client_name' => 'Unity Key (Real Estate) Group',
        'industry' => 'Real Estate',
        'location' => 'Greater Toronto Area, Canada',
        'engagement' => 'September – December 2025',
        'service_labels' => [
            'Communications Strategy',
            'Content & Storytelling',
            'Digital Marketing',
        ],
        'cover' => ['case-unity-key-content.png', 'Unity Key Group content on Instagram'],
        'gallery' => [
            ['case-unity-key-content.png', "Examples of the educational and story-led content developed during the engagement, supporting both the homebuyers' seminar and Unity Key's long-term content strategy."],
            ['case-unity-key-community.png', 'The engagement extended beyond social media, supporting community initiatives and helping Unity Key strengthen its relationship with current and future homebuyers.'],
        ],
        'the_business' => '<p>Unity Key Group is a four-partner real estate team serving buyers and sellers across the Greater Toronto Area.</p>'
            . "<p>By the time we joined the team, they were preparing for their final homebuyers' seminar of the year while continuing to build their presence on social media. The seminar was important, but so was everything that came after it. The business needed a content system that could continue building trust long after the event ended.</p>",
        'what_we_noticed' => "<p>The account wasn't struggling to reach people.</p>"
            . "<p>Nearly two-thirds of its reach came from people who weren't already following the page, showing that Instagram was consistently introducing the business to new audiences.</p>"
            . "<p>At the same time, most of the content centred on promoting the upcoming seminar. There wasn't enough content helping first-time buyers understand the market, learn something useful, or get to know the people behind the business before they were invited to attend.</p>"
            . "<p>We also found opportunities across the profile itself, from the account handle and biography to Story Highlights and the link in bio. Individually they seemed small. Together they shaped a visitor's first impression.</p>",
        'our_thinking' => '<p>People rarely build trust through promotions alone.</p>'
            . '<p>We believed the seminar should be part of a bigger story, not the whole story.</p>'
            . '<p>Instead of focusing only on getting people to register, we developed a content direction that educated, informed, and entertained first-time buyers. Reels and carousels became opportunities to answer common questions, explain the buying process, and build confidence over time.</p>'
            . "<p>When the seminar ended, that approach didn't end with it. It became the foundation for the months that followed, giving Unity Key a more consistent way to communicate with current and future clients.</p>",
        'results_heading' => 'What the audit revealed',
        'results' => [
            ['metric' => '400', 'label' => 'Followers at the start.'],
            ['metric' => '64.8%', 'label' => 'Reach coming from people who were not following the account.'],
            ['metric' => '561', 'label' => 'Profile visits in 90 days.'],
            ['metric' => '13', 'label' => 'Link taps from those visits.'],
        ],
        'results_note' => '<p>Those numbers helped us understand where people were dropping off and where the biggest opportunities existed.</p>',
        'results_placement' => 'after_thinking',
        'what_we_did' => '<p>Our work included:</p>'
            . '<ul>'
            . '<li>Carrying out a detailed audit of the account and its performance.</li>'
            . '<li>Developing a communications and content strategy for the business.</li>'
            . '<li>Creating educational content series for Reels and carousels that helped first-time buyers understand the buying journey.</li>'
            . '<li>Shifting the content from event-led promotion to ongoing storytelling that continued beyond the seminar.</li>'
            . "<li>Supporting content and community engagement for the October homebuyers' seminar.</li>"
            . '<li>Developing a five-pillar content system that the team could sustain after the event.</li>'
            . '<li>Refreshing Story Highlights, branded templates, and the profile experience.</li>'
            . '<li>Recommending a shorter, more memorable account handle.</li>'
            . '<li>Replacing the existing link with a branded, trackable link hub.</li>'
            . '<li>Planning and launching the Moving Forward, Giving Back community campaign in partnership with Goodwill Toronto.</li>'
            . '</ul>',
        'standout_heading' => 'What changed',
        'standout_copy' => '<p>The engagement gave Unity Key a stronger foundation for how it communicated online.</p>'
            . '<p>The seminar received dedicated content and engagement support during its promotion, while the months that followed focused on building a more consistent presence through educational and story-led content.</p>'
            . '<p>The engagement also delivered:</p>'
            . '<ul>'
            . '<li>A new five-pillar content system.</li>'
            . '<li>Refreshed Story Highlights and profile structure.</li>'
            . '<li>A branded, trackable link system.</li>'
            . '<li>The Moving Forward, Giving Back community campaign in partnership with Goodwill Toronto.</li>'
            . '<li>Better measurement tools to guide future marketing decisions.</li>'
            . '</ul>'
            . "<p>Some long-term targets were established during the engagement, but we chose not to publish results we couldn't fully verify. We'd rather be transparent than overstate the outcome.</p>",
        'takeaway_heading' => 'One takeaway',
        'takeaway_copy' => "<p>Good communication doesn't begin with a promotion.</p>"
            . '<p>It begins long before that.</p>'
            . '<p>By helping people learn something useful, answer their questions, and understand the people behind the business, every future campaign starts from a stronger position.</p>'
            . "<p>That's the difference between asking for attention and earning it.</p>",
        'related_service_slugs' => ['communications-strategy', 'content-and-storytelling', 'digital-marketing'],
        'cta_heading' => "Let's talk about your business.",
        'cta_copy' => "<p>Sometimes the biggest opportunity isn't creating more content.</p>"
            . "<p>It's helping every piece of content work together.</p>",
        'order' => 3,
        'featured_on_home' => false,
        'meta_description' => 'How Fastora shifted a Toronto real estate team from event-led promotion to a content system that builds trust with first-time buyers.',
    ],

    // ------------------------------------------------------------ Naturals by Jelique

    [
        'slug' => 'naturals-by-jelique-founder-story',
        'title' => "Turning a founder's story into a growth strategy.",
        'summary' => "Naturals by Jelique already had a remarkable story. The challenge wasn't creating one. It was making sure the people discovering the brand actually heard it.",
        'hero_intro' => "<p>Naturals by Jelique already had a remarkable story. The challenge wasn't creating one. It was making sure the people discovering the brand actually heard it.</p>",
        'client_name' => 'Naturals by Jelique',
        'industry' => 'Beauty & Wellness',
        'location' => 'Jacksonville, Florida, USA',
        'engagement' => 'Ongoing (Started June 2026)',
        'service_labels' => [
            'Brand Positioning',
            'Content & Storytelling',
            'Digital Marketing',
        ],
        'cover' => ['case-naturals-before-after.png', 'The Naturals by Jelique Instagram profile before and after the engagement'],
        'gallery' => [
            ['case-naturals-before-after.png', 'Before & after: the brand profile at the start of the engagement and after the founder-led direction was introduced.'],
            ['case-naturals-founder-content.png', 'Examples of the storytelling approach introduced across the brand\'s Instagram content.'],
        ],
        'the_business' => '<p>Naturals by Jelique is a natural skincare and haircare brand based in Jacksonville, Florida. The business was founded by Angelique James after years of living with severe psoriasis and creating her own solution using her background in biochemistry and the natural ingredients she grew up with in Jamaica.</p>'
            . "<p>That story had always been part of the business. It simply wasn't showing up where most people first encountered the brand.</p>",
        'what_we_noticed' => "<p>The website told the founder's story well.</p>"
            . "<p>Instagram didn't.</p>"
            . "<p>Most of the content focused on products, while the person behind the brand appeared only occasionally. Yet the account's own performance already pointed to something different. Posts featuring Angelique consistently outperformed product graphics, suggesting that people were connecting with the person before they connected with the products.</p>"
            . "<p>The answer wasn't hidden. It was already in the data.</p>",
        'our_thinking' => "<p>We didn't think the brand needed a new identity.</p>"
            . '<p>It already had something many businesses spend years trying to create: a genuine reason for existing.</p>'
            . "<p>Instead of introducing a completely different direction, we built the content strategy around the founder's story and used it as the thread connecting education, products, customer experiences, and everyday content.</p>"
            . "<p>The goal wasn't simply to increase activity. It was to help people understand why the business existed before asking them to buy from it.</p>",
        'what_we_did' => '<p>Our work included:</p>'
            . '<ul>'
            . "<li>Reviewing the brand's digital presence and existing content.</li>"
            . '<li>Refreshing the Instagram profile, bio, and Story Highlights.</li>'
            . "<li>Building a content strategy centred around the founder's story.</li>"
            . '<li>Developing a six-pillar content system to guide future publishing.</li>'
            . '<li>Managing content planning, publishing, and community engagement.</li>'
            . '<li>Creating a balance between educational, founder-led, product, and lifestyle content.</li>'
            . '</ul>',
        'results_heading' => 'What changed',
        'results_intro' => '<p>The new direction quickly showed signs that it was resonating.</p>',
        'results' => [
            ['metric' => '2,406', 'label' => 'Views on the "Meet Angelique" founder story Reel.'],
            ['metric' => '408', 'label' => 'Accounts reached by that single Reel.'],
            ['metric' => '19', 'label' => 'Feed posts published in July, up from 11 the previous month.'],
            ['metric' => '1,280', 'label' => 'Accounts reached in July as the strategy gained momentum.'],
        ],
        'takeaway_heading' => 'One takeaway',
        'takeaway_copy' => "<p>Sometimes the strongest part of a business isn't missing.</p>"
            . "<p>It's simply not being seen.</p>"
            . "<p>Naturals by Jelique didn't need a new story. It already had one built on personal experience, scientific knowledge, and years of persistence. Once that story became a bigger part of the brand's everyday communication, the business started connecting with people in a different way.</p>",
        'related_service_slugs' => ['brand-positioning', 'content-and-storytelling', 'digital-marketing'],
        'cta_heading' => "Let's talk about your business.",
        'cta_copy' => '<p>Every business has a story.</p>'
            . "<p>Sometimes the next step isn't creating a new one. It's helping people see the one that's already there.</p>",
        'order' => 4,
        'featured_on_home' => false,
        'meta_description' => "How Fastora rebuilt a Florida skincare brand's Instagram around its founder's story, and what happened when the person came before the product.",
    ],

];
