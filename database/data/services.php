<?php

/**
 * The four services, copied from the client's content document.
 *
 * The ten former services are not gone — each is listed under the parent it now
 * belongs to, in `includes`. That is the document's structure: four things a
 * business can buy, with the old names as what each one covers.
 *
 * `summary` is the short line on cards and the services index. `problem` is the
 * longer supporting copy under the page heading, which in every case frames the
 * problem the service solves. `deliverables` is the page's "This service may
 * include" list, which is longer and more granular than `includes`.
 *
 * Shared by the seeder and the migration that applies this to an existing
 * database, so a fresh install and a migrated one cannot drift.
 *
 * @return array<int, array<string, mixed>>
 */
return [
    [
        'slug' => 'communications-strategy',
        'title' => 'Communications Strategy',
        'order' => 1,
        'featured_on_home' => true,
        'summary' => 'The way your business communicates shapes how people see it. We help you get that right.',
        'problem' => "<p>People don't always ignore a business because it's the wrong choice.</p>"
            . '<p>Sometimes they simply don\'t understand what it does, why it matters, or why they should trust it.</p>'
            . '<p>Communications Strategy helps you close that gap.</p>',
        'overview_heading' => 'A shared direction for your business.',
        'overview_copy' => '<p>Communications Strategy gives your business a common direction before decisions are made about content, campaigns, or marketing.</p>'
            . '<p>It helps everyone communicate from the same foundation, so your website, presentations, social media, proposals, and conversations reinforce the same business instead of telling different stories.</p>',
        'outcomes' => [
            ['label' => 'Explain your business more confidently.'],
            ['label' => 'Build trust through consistent communication.'],
            ['label' => 'Give every message a clear purpose.'],
            ['label' => 'Help customers understand why they should choose you.'],
            ['label' => 'Strengthen communication across every touchpoint.'],
        ],
        'deliverables' => [
            ['label' => 'Communications strategy'],
            ['label' => 'Messaging framework'],
            ['label' => 'Key message development'],
            ['label' => 'Communication planning'],
            ['label' => 'Stakeholder communication'],
            ['label' => 'Reputation guidance'],
            ['label' => 'Communication advisory'],
            ['label' => 'Internal communication recommendations'],
        ],
        'approach' => '<p>Good recommendations come from understanding the business behind the brief.</p>'
            . "<p>Before any decisions are made, we take time to understand how your business operates, the people you're trying to reach, and what you're working towards. That understanding shapes every recommendation that follows.</p>",
        'good_fit_if' => [
            ['label' => 'Your business has outgrown its current messaging.'],
            ['label' => 'Different teams communicate differently.'],
            ['label' => "Customers don't immediately understand what you do."],
            ['label' => "You're preparing for growth, investment, or expansion."],
            ['label' => 'You want communication to support the business more intentionally.'],
        ],
        'includes' => [
            ['label' => 'Strategic Communications'],
            ['label' => 'Communication Advisory'],
            ['label' => 'Reputation Management'],
        ],
        'related_service_slugs' => ['brand-positioning', 'content-and-storytelling'],
        'cta_heading' => "Let's talk about your business.",
        'cta_copy' => '<p>Every business communicates differently.</p>'
            . "<p>Let's understand yours and decide what comes next.</p>",
        'faqs' => [
            ['question' => 'What is Communications Strategy?', 'answer' => 'Communications Strategy helps shape how your business is understood. It defines what you want people to know about your business, the messages that matter most, and the best ways to express those messages across different channels and audiences.'],
            ['question' => 'How is it different from marketing?', 'answer' => 'Marketing helps people discover your business. Communications Strategy helps them understand it. The two work best together. A strong communications strategy gives your marketing a clearer direction and makes every campaign more consistent.'],
            ['question' => 'Do we need this if we already have a marketing team?', 'answer' => 'In many cases, yes. A marketing team focuses on execution. Communications Strategy provides the direction that guides that work. It helps everyone communicate from the same foundation, making campaigns, content, and customer interactions feel more consistent.'],
            ['question' => 'How long does the process take?', 'answer' => "Every business is different, so timelines depend on the scope of the engagement. After our first conversation, we'll recommend an approach, outline the process, and agree on a timeline before any work begins."],
            ['question' => 'Will we receive a written strategy?', 'answer' => "Yes. You'll receive a documented strategy outlining the recommendations, messaging direction, and agreed priorities for the engagement. Depending on the project, it may also include communication principles, audience insights, key messages, and practical recommendations to guide future work."],
        ],
        'meta_title' => 'Communications Strategy',
        'meta_description' => 'Communications Strategy gives your business a common direction before decisions are made about content, campaigns, or marketing.',
    ],

    [
        'slug' => 'brand-positioning',
        'title' => 'Brand Positioning',
        'order' => 2,
        'featured_on_home' => true,
        'summary' => 'People remember brands that stand for something. We help you define what that is.',
        'problem' => '<p>People begin forming opinions about your business long before they become customers.</p>'
            . '<p>Brand Positioning helps you define what your business stands for, what makes it different, and why people should remember it.</p>',
        'overview_heading' => 'A brand people can recognise and remember.',
        'overview_copy' => '<p>A strong brand goes beyond a logo or colour palette.</p>'
            . '<p>It gives people a clear understanding of who you are, what you stand for, and what they can expect every time they interact with your business.</p>'
            . '<p>Brand Positioning creates that foundation, helping your business present itself with consistency and confidence across every touchpoint.</p>',
        'outcomes' => [
            ['label' => 'Define what makes your business different.'],
            ['label' => 'Build a brand people recognise and remember.'],
            ['label' => 'Create greater consistency across your brand.'],
            ['label' => 'Strengthen trust through a clearer identity.'],
            ['label' => 'Help your business stand out for the right reasons.'],
        ],
        'deliverables' => [
            ['label' => 'Brand positioning strategy'],
            ['label' => 'Brand consulting'],
            ['label' => 'Founder branding'],
            ['label' => 'Brand messaging'],
            ['label' => 'Brand voice development'],
            ['label' => 'Brand architecture'],
            ['label' => 'Positioning workshops'],
            ['label' => 'Brand guidelines and recommendations'],
        ],
        'approach' => "<p>A brand isn't created by choosing colours or writing a tagline.</p>"
            . '<p>It grows from a clear understanding of the business behind it, the people it serves, and the impression it wants to leave.</p>'
            . '<p>That understanding guides every recommendation, helping your brand reflect the quality of the work behind it.</p>',
        'good_fit_if' => [
            ['label' => "Your business has changed but your brand hasn't."],
            ['label' => 'People struggle to understand what makes you different.'],
            ['label' => 'Your brand feels inconsistent across different channels.'],
            ['label' => "You're launching something new or entering a new market."],
            ['label' => 'You want your brand to better reflect the quality of your work.'],
        ],
        'includes' => [
            ['label' => 'Brand Consulting'],
            ['label' => 'Founder Branding'],
        ],
        'related_service_slugs' => ['communications-strategy', 'content-and-storytelling'],
        'cta_heading' => "Let's build a brand people remember.",
        'cta_copy' => '<p>A strong brand gives people confidence before the first conversation even begins.</p>'
            . "<p>Let's build one that reflects the quality of your business.</p>",
        'faqs' => [
            ['question' => 'What is Brand Positioning?', 'answer' => 'Brand Positioning defines how you want your business to be understood and remembered. It brings together your purpose, strengths, values, and messaging to create a distinct place in the minds of the people you want to reach.'],
            ['question' => 'How is Brand Positioning different from branding?', 'answer' => 'Branding is how your business looks, sounds, and presents itself. Brand Positioning comes first. It defines the thinking that gives your branding direction and meaning.'],
            ['question' => 'Can you work with our existing brand?', 'answer' => "Yes. Sometimes a business needs a complete repositioning. Other times, it simply needs to strengthen what's already there. We'll recommend the approach that best suits your business."],
            ['question' => 'Do you help founders build their personal brands?', 'answer' => 'Yes. Founder Branding is part of this service. We help founders build a professional presence that strengthens the businesses they lead.'],
            ['question' => 'Will we receive brand guidelines?', 'answer' => "Where appropriate, yes. Depending on the scope of work, you'll receive documented recommendations that may include positioning, messaging, brand voice, and guidelines to help your team apply the brand consistently."],
        ],
        'meta_title' => 'Brand Positioning',
        'meta_description' => 'Brand Positioning defines what your business stands for, what makes it different, and why people should remember it.',
    ],

    [
        'slug' => 'content-and-storytelling',
        'title' => 'Content & Storytelling',
        'order' => 3,
        'featured_on_home' => true,
        'summary' => 'Content is often the first conversation people have with your business. We help make it count.',
        'problem' => '<p>People often meet your business through your content before they ever meet you.</p>'
            . '<p>Every article, caption, website page, or campaign becomes part of the story they remember.</p>'
            . "<p>Content &amp; Storytelling helps make sure it's a story worth telling.</p>",
        'overview_heading' => 'Every piece of content says something about your business.',
        'overview_copy' => '<p>Content should do more than fill a calendar or keep your pages active.</p>'
            . '<p>It should help people understand your business, build confidence in what you do, and leave them with something worth remembering.</p>'
            . '<p>Every article, web page, social media post, and campaign should reinforce the same story.</p>',
        'outcomes' => [
            ['label' => 'Create content with a clear purpose.'],
            ['label' => 'Tell a more consistent brand story.'],
            ['label' => 'Build trust through valuable content.'],
            ['label' => 'Give your audience a reason to return.'],
            ['label' => 'Turn everyday content into a business asset.'],
        ],
        'deliverables' => [
            ['label' => 'Content strategy'],
            ['label' => 'Copywriting'],
            ['label' => 'Content writing'],
            ['label' => 'Website copy'],
            ['label' => 'Social media content'],
            ['label' => 'Campaign messaging'],
            ['label' => 'Long-form articles'],
            ['label' => 'Editorial planning'],
        ],
        'approach' => '<p>Good content starts long before the first sentence is written.</p>'
            . '<p>We take time to understand what your audience needs to hear, what your business needs to say, and how those two meet. The result is content that feels purposeful, consistent, and true to your brand.</p>',
        'good_fit_if' => [
            ['label' => "Your content doesn't feel connected."],
            ['label' => "You're creating content but it's not making much of a difference."],
            ['label' => 'Your business has plenty to say but struggles to say it well.'],
            ['label' => 'You want your content to support long-term business goals.'],
            ['label' => 'You want a clearer plan for what to publish.'],
        ],
        'includes' => [
            ['label' => 'Content Strategy'],
            ['label' => 'Copywriting'],
            ['label' => 'Content Writing'],
        ],
        'related_service_slugs' => ['brand-positioning', 'digital-marketing'],
        'cta_heading' => "Let's tell a better story.",
        'cta_copy' => '<p>Every piece of content shapes how people see your business.</p>'
            . "<p>Let's make every one of them count.</p>",
        'faqs' => [
            ['question' => 'What is Content & Storytelling?', 'answer' => 'Content & Storytelling is about creating content that helps people understand, trust, and remember your business. It gives every piece of content a purpose beyond simply being published.'],
            ['question' => 'How is this different from content creation?', 'answer' => 'Content creation focuses on producing content. Content & Storytelling begins by deciding what should be said, why it matters, and how it supports the bigger story your business is trying to tell.'],
            ['question' => 'Do you create the content as well?', 'answer' => 'Yes. Depending on the project, we can develop the strategy, write the content, and support its execution across the appropriate channels.'],
            ['question' => 'What types of content do you create?', 'answer' => 'We work across a range of formats, including website copy, articles, social media content, campaign messaging, newsletters, thought leadership, and other editorial content.'],
            ['question' => 'Can this work alongside our existing marketing efforts?', 'answer' => 'Absolutely. Thoughtful content strengthens marketing by giving every campaign, channel, and customer interaction a stronger foundation.'],
        ],
        'meta_title' => 'Content & Storytelling',
        'meta_description' => 'Content that helps people understand, trust, and remember your business, with a purpose beyond simply being published.',
    ],

    [
        'slug' => 'digital-marketing',
        'title' => 'Digital Marketing',
        'order' => 4,
        'featured_on_home' => true,
        'summary' => 'Once your message is right, we help it reach the people who need to hear it.',
        'problem' => '<p>The right message only makes a difference if the right people see it.</p>'
            . '<p>Digital Marketing helps your business reach the audiences that matter, using channels and campaigns that support your goals.</p>',
        'overview_heading' => 'Helping your message reach further.',
        'overview_copy' => "<p>Digital Marketing connects your business with the people it's trying to reach.</p>"
            . "<p>It's about choosing the right channels, sharing the right message, and creating opportunities for people to discover, engage with, and remember your business.</p>",
        'outcomes' => [
            ['label' => 'Help more people discover your business.'],
            ['label' => 'Put your message in front of the right audience.'],
            ['label' => 'Stay visible through consistent digital activity.'],
            ['label' => 'Build stronger relationships with your audience.'],
            ['label' => 'Support your business with marketing that has a clear direction.'],
        ],
        'deliverables' => [
            ['label' => 'Digital marketing strategy'],
            ['label' => 'Social media management'],
            ['label' => 'Campaign planning'],
            ['label' => 'Content distribution'],
            ['label' => 'Paid advertising support'],
            ['label' => 'Community management'],
            ['label' => 'Performance reporting'],
            ['label' => 'Marketing strategy'],
        ],
        'approach' => '<p>Digital Marketing works best when it follows a clear direction.</p>'
            . '<p>Before choosing channels or planning campaigns, we take time to understand your business, your audience, and what success looks like. That helps us focus on marketing that\'s relevant, purposeful, and aligned with your goals.</p>',
        'good_fit_if' => [
            ['label' => 'You want more people to discover your business.'],
            ['label' => 'Your marketing lacks a clear direction.'],
            ['label' => "You're investing in digital marketing without seeing consistent results."],
            ['label' => 'You want your marketing to support broader business goals.'],
            ['label' => "You're ready to build a stronger online presence."],
        ],
        'includes' => [
            ['label' => 'Social Media Management'],
            ['label' => 'Digital Marketing'],
            ['label' => 'Marketing Strategy'],
        ],
        'related_service_slugs' => ['content-and-storytelling', 'communications-strategy'],
        'cta_heading' => "Let's help more people find your business.",
        'cta_copy' => '<p>The right message deserves the right audience.</p>'
            . "<p>Let's build a digital marketing approach that supports where your business is headed.</p>",
        'faqs' => [
            ['question' => 'What is Digital Marketing?', 'answer' => "Digital Marketing helps your business reach the people it's trying to serve through the right digital channels. It combines strategy, content, campaigns, and ongoing activity to help more people discover, engage with, and choose your business."],
            ['question' => 'Do you manage social media accounts?', 'answer' => 'Yes. Social Media Management is one of the services within Digital Marketing. Depending on your needs, we can help plan, publish, manage, and optimise your social media presence.'],
            ['question' => 'Do you run paid advertising campaigns?', 'answer' => "Yes, where they support your business objectives. We'll recommend paid advertising only when it's the right fit for your goals and overall strategy."],
            ['question' => 'How do you measure success?', 'answer' => 'Success looks different for every business. At the start of each engagement, we agree on what success should look like and use those goals to guide our reporting and recommendations.'],
            ['question' => 'Do I need Digital Marketing if I already have a website?', 'answer' => 'Yes. A website gives people a place to learn about your business. Digital Marketing helps the right people find it and encourages them to take the next step.'],
        ],
        'meta_title' => 'Digital Marketing',
        'meta_description' => 'Digital Marketing helps your business reach the audiences that matter, using channels and campaigns that support your goals.',
    ],
];
