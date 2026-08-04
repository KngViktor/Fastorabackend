<?php

/**
 * Service copy from the reference build, shared by the seeder and the migration
 * that backfills it.
 *
 * Kept in one file because the two run at different times: on a fresh database
 * migrations execute before the seeder, so the migration finds no services to
 * update and the seeder must supply this copy itself. Duplicating it would mean
 * a new install and an existing one drifting apart.
 *
 * @return array<string, array{summary: string, problem: string, approach: string, deliverables: string[], faqs: array<int, array{question: string, answer: string}>}>
 */
return [
            'strategic-communications' => [
                'summary' => 'Clear, coordinated communication that helps your business explain what it does, why it matters, and why people should choose it.',
                'problem' => '<p>Many businesses communicate in fragments, one message on the website, another in a pitch, another on social media, leaving audiences confused about what the business actually stands for.</p>',
                'approach' => '<p>We start by understanding your business, audience, and objectives, then build a communications strategy that gives every message a clear purpose and keeps your story consistent across every platform and touchpoint.</p>',
                'deliverables' => [
                    'Communications strategy & messaging framework',
                    'Key message development',
                    'Stakeholder communication planning',
                    'Ongoing communications support',
                ],
                'faqs' => [
                    ['question' => 'What is strategic communications, exactly?', 'answer' => 'It is the discipline of deciding what your business needs to say, to whom, and why, before any content, campaign, or website copy gets written. It is the foundation every other service builds on.'],
                    ['question' => 'Do we need this if we already have a marketing team?', 'answer' => 'Often, yes. Strategic communications sits above day-to-day marketing execution, it is the thinking that keeps every campaign, post, and conversation pointed at the same goal.'],
                ],
            ],
            'brand-consulting' => [
                'summary' => 'Brand positioning and identity thinking that helps your business stand for something clear, credible, and memorable.',
                'problem' => '<p>Inconsistent visuals, mixed messaging, and an unclear position in the market make it harder for people to understand, and trust, what a business stands for.</p>',
                'approach' => '<p>We work with you to define your positioning, personality, and value proposition, then translate that thinking into practical guidance your team can apply consistently across every touchpoint.</p>',
                'deliverables' => [
                    'Brand positioning & personality definition',
                    'Messaging and voice guidelines',
                    'Brand audit & recommendations',
                    'Practical brand usage guidance',
                ],
                'faqs' => [
                    ['question' => 'Do you design logos and visual identities?', 'answer' => 'Our focus is on brand thinking, positioning, personality, voice, and messaging. Where visual identity work is needed, we scope it as part of the engagement or work alongside your design team.'],
                    ['question' => 'How long does a brand consulting engagement take?', 'answer' => 'Most positioning engagements run four to eight weeks, depending on how much stakeholder research and internal alignment is needed.'],
                ],
            ],
            'content-strategy' => [
                'summary' => 'A content plan tied to real business goals, so what you publish actually builds trust and moves people to act.',
                'problem' => '<p>Content gets produced without a clear plan, so it fills a calendar without building the trust, authority, or interest a business actually needs.</p>',
                'approach' => '<p>We build a content strategy around a small number of clear pillars connected to your business objectives and audience questions, then set a realistic publishing rhythm your team can sustain.</p>',
                'deliverables' => [
                    'Content pillar & editorial framework',
                    'Content calendar',
                    'Topic and format recommendations',
                    'Performance review framework',
                ],
                'faqs' => [
                    ['question' => 'Do you also write and produce the content?', 'answer' => 'Yes, content strategy is often paired with our Copywriting service, so the plan and the execution stay connected.'],
                ],
            ],
            'reputation-management' => [
                'summary' => 'Proactive and responsive reputation support that protects trust and keeps your story accurate and credible.',
                'problem' => '<p>A single unclear message, unanswered review, or poorly handled moment can undo years of credibility, and most businesses only think about reputation once something has already gone wrong.</p>',
                'approach' => '<p>We help you build a proactive reputation strategy, consistent messaging, monitoring, and response protocols, so your business is prepared before an issue arises, not scrambling after.</p>',
                'deliverables' => [
                    'Reputation audit',
                    'Monitoring & response protocols',
                    'Crisis communication planning',
                    'Ongoing reputation support',
                ],
                'faqs' => [
                    ['question' => 'Is this only for businesses already in a crisis?', 'answer' => 'No. Most of our reputation work is proactive, building the messaging, monitoring, and protocols that prevent a small issue from becoming a large one.'],
                ],
            ],
            'founder-branding' => [
                'summary' => 'Help for founders and executives who need to communicate their vision clearly and consistently as the face of their business.',
                'problem' => "<p>Founders are often their business's most valuable communicator, yet many struggle to show up consistently across platforms, interviews, and public moments.</p>",
                'approach' => '<p>We help founders clarify their voice and message, then build a practical plan for showing up, on LinkedIn, in interviews, at events, in a way that strengthens both their personal credibility and the business behind them.</p>',
                'deliverables' => [
                    'Personal brand positioning',
                    'Content & talking-point development',
                    'Thought leadership planning',
                    'Media & public appearance preparation',
                ],
                'faqs' => [
                    ['question' => 'Is founder branding the same as personal social media management?', 'answer' => 'It includes it, but starts earlier, with positioning and message clarity, so that whatever you post or say is working toward a consistent, credible personal brand.'],
                ],
            ],
            'social-media-management' => [
                'summary' => 'Strategic, consistent social media management that builds real engagement instead of just filling a calendar.',
                'problem' => "<p>Inconsistent posting and a lack of platform-specific strategy leave many businesses with social accounts that don't build engagement or trust.</p>",
                'approach' => '<p>We build a platform-specific content and community strategy, then manage day-to-day publishing and engagement on a consistent, sustainable cadence.</p>',
                'deliverables' => [
                    'Platform strategy & content calendar',
                    'Content creation & scheduling',
                    'Community management',
                    'Monthly performance reporting',
                ],
                'faqs' => [
                    ['question' => 'Which platforms do you manage?', 'answer' => 'Instagram, LinkedIn, TikTok, X, Facebook, and YouTube, we recommend a focused mix based on where your audience actually spends time, not every platform at once.'],
                ],
            ],
            'copywriting' => [
                'summary' => 'Clear, purposeful writing for websites, campaigns, and content that sounds like your business and moves people to act.',
                'problem' => '<p>Generic or unclear copy makes even strong businesses sound like every other option, and readers move on before they understand the value being offered.</p>',
                'approach' => '<p>We write with intent, every page, email, and caption is built around a clear objective and a voice that sounds distinctly like your business, not a template.</p>',
                'deliverables' => [
                    'Website & landing page copy',
                    'Campaign & email copy',
                    'Social and marketing copy',
                    'Voice & messaging consistency review',
                ],
                'faqs' => [
                    ['question' => 'Can you write in our existing brand voice?', 'answer' => 'Yes, we start by studying how your business already communicates, then write in a voice that is recognisably yours, refined rather than replaced.'],
                ],
            ],
            'digital-marketing' => [
                'summary' => 'Digital campaigns built around one measurable goal at a time, not vanity metrics.',
                'problem' => '<p>Digital spend gets wasted chasing impressions and reach instead of outcomes that matter to the business, enquiries, sign-ups, and sales.</p>',
                'approach' => '<p>We plan and manage campaigns against a single primary objective, with consistent iteration based on real performance data rather than guesswork.</p>',
                'deliverables' => [
                    'Campaign strategy & targeting',
                    'Paid & organic execution',
                    'Landing page and funnel review',
                    'Performance reporting & iteration',
                ],
                'faqs' => [
                    ['question' => 'Do you handle paid ads or only organic?', 'answer' => 'Both, the right mix depends on your goals and audience, which we validate early rather than assuming upfront.'],
                ],
            ],
            'marketing-strategy' => [
                'summary' => 'A clear, practical marketing plan that connects your business objectives to the channels and messages that will actually reach your audience.',
                'problem' => '<p>Without a clear strategy, marketing activity becomes reactive, a mix of tactics with no shared direction or way to measure what is actually working.</p>',
                'approach' => '<p>We build a marketing strategy grounded in your business goals, audience, and competitive position, then translate it into a practical plan your team can execute and measure.</p>',
                'deliverables' => [
                    'Market & audience analysis',
                    'Marketing strategy & roadmap',
                    'Channel & budget recommendations',
                    'Quarterly strategy reviews',
                ],
                'faqs' => [
                    ['question' => 'How is this different from digital marketing?', 'answer' => 'Marketing strategy is the plan; digital marketing is one part of the execution. We offer both together or independently, depending on what you already have in place.'],
                ],
            ],
            'communication-advisory' => [
                'summary' => 'Ongoing, trusted advisory support for leaders who need a communications partner they can call on for guidance, not just execution.',
                'problem' => '<p>Important communication decisions, a public statement, a sensitive announcement, a new positioning, often need an outside, experienced perspective, but many businesses have no one to turn to.</p>',
                'approach' => '<p>We act as an ongoing advisory partner, available to review messaging, guide sensitive communications, and provide an outside perspective when it matters most.</p>',
                'deliverables' => [
                    'Retainer-based advisory access',
                    'Messaging & communications review',
                    'Sensitive announcement guidance',
                    'Leadership communication coaching',
                ],
                'faqs' => [
                    ['question' => 'Is this a retainer service?', 'answer' => 'Yes, Communication Advisory is typically an ongoing retainer, so you have a trusted partner available when communication decisions need to move quickly.'],
                ],
            ],
];
