<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Category;
use App\Models\Media;
use App\Models\NavFooter;
use App\Models\NavHeader;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Demo content for local development and for verifying the Next.js
     * frontend end-to-end against this API. Not meant for production data.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'hello@fastora.africa'],
            ['name' => 'Fastora Admin', 'password' => Hash::make('Fastora-2026!'), 'role' => 'super_admin'],
        );

        $brandMedia = $this->seedBrandMedia();
        $brandMediaWhite = $this->importImage('icon-white.png', 'Fastora icon, white');

        // Real photography, so the site is not built entirely out of the logo.
        // The hero image is pre-composited: navy gradient, ghosted wordmark and
        // a diagonally-cut team photo in one file, which is exactly what the
        // HighImpact hero component expects as its background.
        $heroImage = $this->importImage('fastora-hero-section.png', 'Two colleagues reviewing work together on a laptop and tablet');
        $studioPhoto = $this->importImage('121758.jpg', 'A communications professional at work in a studio');
        $analyticsPhoto = $this->importImage('119721.jpg', 'Reviewing performance figures on a tablet');
        $contentPhoto = $this->importImage('83416.jpg', 'Planning content across digital channels');
        $strategyPhoto = $this->importImage('32.jpg', 'Mapping a communications strategy across markets');

        SiteSetting::current()->update([
            'site_name' => 'Fastora',
            'tagline' => 'Communications and digital strategy for businesses that want to be understood.',
            'logo_light_media_id' => $brandMedia->id,
            // White cut of the same mark, because the footer sits on navy where the
            // blue version is legible but muddy.
            'logo_dark_media_id' => $brandMediaWhite->id,
            'favicon_media_id' => $brandMedia->id,
            'contact_email' => 'hello@fastora.africa',
            'contact_phone' => '+234 703 814 7969',
            'address' => 'Nigeria · Remote · Africa',
            'social_links' => require database_path('data/social-links.php'),
            'footer_text' => '© ' . now()->year . ' Fastora. All rights reserved.',
            'newsletter_heading' => 'Stay in the loop',
            'newsletter_subheading' => 'Occasional notes on communications and brand strategy.',
        ]);

        NavHeader::current()->update([
            'nav_items' => [
                ['label' => 'Services', 'url' => '/services'],
                ['label' => 'Case Studies', 'url' => '/case-studies'],
                ['label' => 'Insights', 'url' => '/insights'],
                ['label' => 'About', 'url' => '/about'],
                ['label' => 'Contact', 'url' => '/contact'],
            ],
        ]);

        NavFooter::current()->update([
            'nav_items' => [
                ['label' => 'Services', 'url' => '/services'],
                ['label' => 'Case Studies', 'url' => '/case-studies'],
                ['label' => 'Insights', 'url' => '/insights'],
                ['label' => 'About', 'url' => '/about'],
                ['label' => 'Contact', 'url' => '/contact'],
            ],
        ]);

        $strategy = Service::updateOrCreate(['slug' => 'strategic-communications'], [
            'title' => 'Strategic Communications',
            'summary' => 'Clear, consistent messaging that aligns every team around the same story.',
            'featured_image_media_id' => $strategyPhoto->id,
            'order' => 1,
            'featured_on_home' => true,
            'problem' => '<p>Most businesses communicate reactively. Every announcement, pitch, and update sounds like it came from a different company.</p>',
            'approach' => '<p>We build a messaging framework once, then train every team that speaks publicly to use it.</p>',
            'deliverables' => [['label' => 'Messaging framework'], ['label' => 'Spokesperson training'], ['label' => 'Quarterly message audits']],
            'faqs' => [
                ['question' => 'How long does a messaging framework take?', 'answer' => 'Typically two to three weeks from kickoff to a signed-off framework.'],
            ],
            'status' => 'published',
            'published_at' => now()->subMonths(6),
        ]);

        $branding = Service::updateOrCreate(['slug' => 'brand-consulting'], [
            'title' => 'Brand Consulting',
            'summary' => 'Positioning, identity, and voice work that makes a business memorable.',
            'featured_image_media_id' => $studioPhoto->id,
            'order' => 2,
            'featured_on_home' => true,
            'problem' => '<p>A brand that looks and sounds inconsistent erodes trust before a single sales conversation happens.</p>',
            'approach' => '<p>We start with positioning, then carry it through identity, voice, and every touchpoint your audience sees.</p>',
            'deliverables' => [['label' => 'Brand positioning'], ['label' => 'Visual identity system'], ['label' => 'Voice and tone guide']],
            'faqs' => [
                ['question' => 'Do you design logos?', 'answer' => 'Yes, as part of a full identity engagement, not as a standalone service.'],
            ],
            'status' => 'published',
            'published_at' => now()->subMonths(4),
        ]);

        Service::updateOrCreate(['slug' => 'digital-marketing'], [
            'title' => 'Digital Marketing',
            'summary' => 'Paid and organic campaigns built around a clear communications strategy.',
            'featured_image_media_id' => $analyticsPhoto->id,
            'order' => 3,
            'featured_on_home' => true,
            'problem' => '<p>Running ads without a strategy behind them burns budget fast.</p>',
            'approach' => '<p>We plan campaigns around the audience and message work we\'ve already validated.</p>',
            'deliverables' => [['label' => 'Channel strategy'], ['label' => 'Campaign management'], ['label' => 'Monthly performance reporting']],
            'faqs' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(2),
        ]);

        // The remaining services named in the specification. Only the first
        // three are featured on the home page; the rest fill out the services
        // index so the offering is complete rather than a sample of it.
        $moreServices = [
            ['content-strategy', 'Content Strategy', 'A publishing plan that says something worth reading, consistently.',
                'Content gets produced in bursts, with no plan behind what it is meant to achieve.',
                'We set the themes, formats, and cadence, then build a calendar the team can actually keep to.',
                ['Content pillars', 'Editorial calendar', 'Tone of voice guide'], 4],
            ['reputation-management', 'Reputation Management', 'Protecting how a business is perceived, before and during difficult moments.',
                'Reputation is usually only considered once it is already under pressure.',
                'We prepare the positions, spokespeople, and holding statements in advance, then support you live if something breaks.',
                ['Risk audit', 'Crisis playbook', 'Spokesperson preparation'], 5],
            ['founder-branding', 'Founder Branding', 'Positioning the person in front of the business.',
                'Founders are often the most credible voice a company has, and the least prepared to use it.',
                'We define what the founder is known for, then build the content and speaking presence to support it.',
                ['Personal positioning', 'Content programme', 'Speaking and profile support'], 6],
            ['social-media-management', 'Social Media Management', 'Day-to-day channels run with the same discipline as the wider strategy.',
                'Channels drift from the brand when they are handed to whoever has time.',
                'We take ownership of planning, publishing, and community response, working from the agreed messaging.',
                ['Channel management', 'Content production', 'Community management'], 7],
            ['copywriting', 'Copywriting', 'Words that carry the positioning through every touchpoint.',
                'Good strategy is regularly undone by copy written in a hurry.',
                'We write the website, campaign, and sales material so the language stays consistent wherever it appears.',
                ['Website copy', 'Campaign and sales copy', 'Messaging library'], 8],
            ['marketing-strategy', 'Marketing Strategy', 'Where to compete, who to reach, and what to say first.',
                'Activity begins before anyone has agreed the audience or the priority.',
                'We set the market position, audience priorities, and channel plan before any execution starts.',
                ['Market and audience analysis', 'Channel plan', 'Quarterly roadmap'], 9],
            ['communication-advisory', 'Communication Advisory', 'A senior voice to think alongside, on retainer.',
                'Leadership teams often need judgement on a communications decision faster than a project allows.',
                'We stay close as an ongoing advisor, available for the decisions that matter as they come up.',
                ['Ongoing advisory', 'Leadership counsel', 'Quarterly review'], 10],
        ];

        foreach ($moreServices as [$slug, $title, $summary, $problem, $approach, $deliverables, $order]) {
            Service::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'summary' => $summary,
                'featured_image_media_id' => $studioPhoto->id,
                'order' => $order,
                'featured_on_home' => false,
                'problem' => '<p>' . $problem . '</p>',
                'approach' => '<p>' . $approach . '</p>',
                'deliverables' => array_map(fn ($d) => ['label' => $d], $deliverables),
                'faqs' => [],
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ]);
        }

        $acme = CaseStudy::updateOrCreate(['slug' => 'lumen-skincare-content-strategy'], [
            'title' => 'From sporadic posts to a content strategy that compounds',
            'summary' => 'Lumen Skincare had beautiful products but flat social engagement. We rebuilt their content strategy and social media management from the ground up.',
            'client_name' => 'Lumen Skincare',
            'industry' => 'Beauty & Wellness',
            'cover_image_media_id' => $analyticsPhoto->id,
            'gallery' => [['media_id' => $contentPhoto->id]],
            'order' => 1,
            'featured_on_home' => true,
            'related_service_id' => $branding->id,
            'challenge' => '<p>Lumen Skincare had beautiful products but an inconsistent social presence, irregular posting, no clear content strategy, and flat engagement across Instagram and TikTok.</p>',
            'approach' => '<p>We built a platform-specific content strategy and editorial calendar, then took over day-to-day social media management, consistent publishing, community engagement, and monthly reporting tied to real growth metrics.</p>',
            'results' => [
                ['metric' => '+212%', 'label' => 'Engagement rate in 90 days'],
                ['metric' => '3.1x', 'label' => 'Follower growth in 6 months'],
                ['metric' => '48hr', 'label' => 'Average content turnaround'],
            ],
            'status' => 'published',
            'published_at' => now()->subMonths(5),
        ]);

        CaseStudy::updateOrCreate(['slug' => 'northbound-logistics-marketing-strategy'], [
            'title' => 'Turning a strong operation into a clear, trusted brand',
            'summary' => 'Northbound Logistics ran a strong operation but a fragmented digital presence made it hard for prospects to understand their value. We rebuilt their messaging and marketing strategy.',
            'client_name' => 'Northbound Logistics',
            'industry' => 'Logistics & Supply Chain',
            'cover_image_media_id' => $contentPhoto->id,
            'gallery' => [],
            'order' => 2,
            'featured_on_home' => true,
            'related_service_id' => $strategy->id,
            'challenge' => '<p>Northbound\'s messaging was inconsistent across their website, proposals, and social presence, and their marketing had no clear strategy connecting it to business goals, so qualified leads were slipping through before a conversation ever started.</p>',
            'approach' => '<p>We developed a marketing strategy grounded in Northbound\'s actual competitive position, rewrote their core messaging for clarity and trust, and rebuilt their lead-generating campaigns around a single measurable goal: qualified quote requests.</p>',
            'results' => [
                ['metric' => '+40%', 'label' => 'Qualified quote requests in 90 days'],
                ['metric' => '-28%', 'label' => 'Cost per qualified lead'],
                ['metric' => '100%', 'label' => 'Consistent messaging across every channel'],
            ],
            'status' => 'published',
            'published_at' => now()->subMonths(3),
        ]);

        // Avatars stay null on purpose. There is no photograph of either person,
        // and standing the logo in as a face was wrong the first time it was tried.
        Testimonial::updateOrCreate(['client_name' => 'Amaka Chukwu', 'company' => 'Lumen Skincare'], [
            'quote' => 'Fastora rebuilt our entire social presence in six weeks. We went from posting sporadically to a real content strategy, and our engagement tripled.',
            'role' => 'Founder',
            'avatar_media_id' => null,
            'rating' => 5,
            'show_on_home' => true,
        ])->services()->sync([$branding->id]);

        Testimonial::updateOrCreate(['client_name' => 'Daniel Osei', 'company' => 'Northbound Logistics'], [
            'quote' => 'Fastora helped us explain what Northbound actually does, clearly and consistently. Our qualified quote requests are up 40%, and prospects understand our value before they even call.',
            'role' => 'CEO',
            'avatar_media_id' => null,
            'rating' => 5,
            'show_on_home' => true,
        ])->services()->sync([$strategy->id]);

        $strategyCategory = Category::updateOrCreate(['slug' => 'strategy'], ['title' => 'Strategy']);
        $brandingCategory = Category::updateOrCreate(['slug' => 'branding'], ['title' => 'Branding']);

        $post1 = Post::updateOrCreate(['slug' => 'why-most-messaging-frameworks-fail'], [
            'hero_image_media_id' => $contentPhoto->id,
            'title' => 'Why most messaging frameworks fail in the first quarter',
            'content' => '<p>A messaging framework only works if the people using it were part of building it.</p><p>The most common failure mode isn\'t a bad framework, it\'s a framework nobody outside the marketing team ever saw.</p>',
            'tags' => [['tag' => 'messaging'], ['tag' => 'strategy']],
            'status' => 'published',
            'published_at' => now()->subWeeks(2),
        ]);
        $post1->categories()->sync([$strategyCategory->id]);
        $post1->authors()->sync([$admin->id]);

        $post2 = Post::updateOrCreate(['slug' => 'brand-consistency-checklist'], [
            'hero_image_media_id' => $studioPhoto->id,
            'title' => 'A ten-point brand consistency checklist',
            'content' => '<p>Before your next campaign ships, run it against these ten checks.</p><ul><li>Does the headline match your positioning statement?</li><li>Would a new hire recognize the voice as yours?</li></ul>',
            'tags' => [['tag' => 'branding']],
            'status' => 'published',
            'published_at' => now()->subWeeks(1),
        ]);
        $post2->categories()->sync([$brandingCategory->id]);
        $post2->authors()->sync([$admin->id]);

        Page::updateOrCreate(['slug' => 'home'], [
            'title' => 'Home',
            'hero_type' => 'highImpact',
            'hero_eyebrow' => 'Communications & Digital Strategy',
            'hero_rich_text' => '<h1>Communication that earns attention.</h1><p>Fastora is a communications and digital strategy company that helps businesses communicate with purpose, strengthen their brand, and earn the trust they deserve.</p>',
            'hero_links' => [
                ['label' => 'Book a Consultation', 'url' => '/contact', 'appearance' => 'default'],
                ['label' => 'View case studies', 'url' => '/case-studies', 'appearance' => 'outline'],
            ],
            'hero_media_id' => $heroImage->id,
            'layout' => [
                // Six confirmed clients. No logo files yet, so the block renders
                // each as its name until one is attached in the admin.
                ['type' => 'trustedBy', 'data' => [
                    'heading' => 'Trusted by',
                    'logos' => require database_path('data/confirmed-clients.php'),
                ]],

                ['type' => 'aboutFastora', 'data' => [
                    'heading' => 'Good work deserves to be noticed, understood, and remembered.',
                    'richText' => '<p>Many businesses are genuinely good at what they do. Capable teams, quality products, years of experience. Yet they are overlooked because they struggle to communicate their value.</p><p>Fastora exists to close that gap. We help businesses communicate more effectively so they become easier to understand, easier to trust, and harder to ignore.</p>',
                    'image' => $studioPhoto->id,
                    'linkLabel' => 'More about Fastora',
                    'linkUrl' => '/about',
                    'stats' => [
                        ['value' => '10', 'label' => 'Services under one team'],
                        ['value' => 'Africa', 'label' => 'Rooted here, working globally'],
                    ],
                ]],

                ['type' => 'servicesOverview', 'data' => ['eyebrow' => 'What we do', 'heading' => 'Services built around how you communicate', 'limit' => 6]],
                ['type' => 'whyFastora', 'data' => [
                    'eyebrow' => null,
                    'heading' => 'A strategic partner, not just another vendor',
                    'points' => [
                        ['stat' => '10+', 'title' => 'Integrated services', 'description' => 'From strategy to execution, communications and digital work live under one accountable team, not scattered across vendors.'],
                        ['stat' => 'Strategy-first', 'title' => 'We think before we create', 'description' => 'Every recommendation starts with understanding your business, not a template. Strategy guides everything we produce.'],
                        ['stat' => 'Africa', 'title' => 'Proudly African, globally minded', 'description' => "We're committed to raising the standard of business communication across Africa while serving clients and partners around the world."],
                    ],
                ]],

                // Precedes the case studies, as in the reference: how we work,
                // then what it produced.
                ['type' => 'ourProcess', 'data' => [
                    'eyebrow' => null,
                    'heading' => 'How we work with you',
                    'steps' => [
                        ['title' => 'Listen & understand', 'description' => 'We start every engagement by understanding your business, audience, and communication challenge, before recommending anything.'],
                        ['title' => 'Strategise', 'description' => 'We translate that understanding into a clear, practical strategy connected to your actual business objectives.'],
                        ['title' => 'Create & execute', 'description' => 'We bring the strategy to life, content, campaigns, messaging, and digital execution, with the same care at every step.'],
                        ['title' => 'Review & grow', 'description' => "We measure what matters, share what we're learning, and refine the approach as your business and audience evolve."],
                    ],
                ]],
                ['type' => 'selectedWork', 'data' => ['eyebrow' => 'Selected work', 'heading' => 'Results, not just deliverables', 'limit' => 3]],
                ['type' => 'testimonialsBlock', 'data' => ['eyebrow' => 'Client voices', 'heading' => 'What clients say', 'limit' => 3]],
                ['type' => 'latestInsights', 'data' => ['eyebrow' => 'Insights', 'heading' => 'Recent thinking', 'limit' => 3]],
                ['type' => 'faq', 'data' => [
                    'heading' => 'Questions, answered directly',
                    'items' => [
                        ['question' => 'What does Fastora do?', 'answer' => 'Fastora is a communications and digital strategy company. We help businesses communicate more effectively through strategic communications, brand consulting, content strategy, reputation management, founder branding, social media management, copywriting, digital marketing, marketing strategy, and communication advisory, all working toward one goal: helping you become easier to understand, easier to trust, and harder to ignore.'],
                        ['question' => 'How is Fastora different from a typical marketing agency?', 'answer' => 'We start with strategy, not content production. Before we write a caption or launch a campaign, we take time to understand your business, audience, and communication challenge, then build a plan execution can actually follow.'],
                        ['question' => 'How quickly can we start working together?', 'answer' => 'Most engagements begin with a consultation to understand your goals, followed by a proposal within a few days. From there, timelines depend on scope, but we move as quickly as good strategy allows.'],
                        ['question' => 'Does Fastora work with businesses outside Africa?', 'answer' => "Yes. We're proudly African and committed to raising the standard of business communication across Africa, while serving clients and partners around the world."],
                    ],
                ]],

                ['type' => 'cta', 'data' => [
                    'richText' => '<h2>Ready to start your project?</h2><p>Tell us where you want to go, we\'ll come back with how to get there.</p>',
                    'links' => [['label' => 'Book a Consultation', 'url' => '/contact', 'appearance' => 'default']],
                ]],
            ],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
            'meta_title' => 'Fastora, Communications & Digital Strategy',
            'meta_description' => 'Fastora helps businesses communicate with clarity, credibility, and confidence.',
        ]);

        Page::updateOrCreate(['slug' => 'services'], [
            'title' => 'Services',
            'hero_type' => 'none',
            'page_header_eyebrow' => 'What we do',
            'page_header_heading' => 'Services built around how you communicate',
            'page_header_description' => 'Integrated services, each designed to help your business communicate with more clarity, credibility, and confidence.',
            'faqs' => [
                [
                    'question' => 'How do I know which service is right for us?',
                    'answer' => "Book a consultation and we'll help you figure out the right starting point. Most engagements begin with Strategic Communications or Brand Consulting before moving into execution.",
                ],
                [
                    'question' => 'Can we combine multiple services?',
                    'answer' => 'Yes. Most clients combine two or three services. Strategy, content, and digital marketing are a common pairing, delivered as one connected engagement.',
                ],
                [
                    'question' => 'Do you offer one-off projects or only retainers?',
                    'answer' => 'Both. Some services, like Brand Consulting, work well as defined projects. Others, like Social Media Management and Communication Advisory, are typically ongoing retainers.',
                ],
            ],
            'layout' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
        ]);

        // Hero copy and layout come from the shared reference file, so this matches
        // what the rebuild migration produces on an existing database. The
        // aboutFastora block is prepended here rather than living in that file,
        // because it needs a media id the file cannot know.
        $aboutReference = require database_path('data/reference-about-page.php');

        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About',
            'hero_type' => 'lowImpact',
            'hero_rich_text' => $aboutReference['hero_rich_text'],
            'hero_media_id' => $studioPhoto->id,
            'layout' => array_merge([
                // Not part of the reference page, but added on request. A different
                // photograph from the hero directly above it, and no link, since
                // "More about Fastora" would point at this page.
                ['type' => 'aboutFastora', 'data' => [
                    'heading' => 'Good work deserves to be noticed, understood, and remembered.',
                    'richText' => '<p>Many businesses are genuinely good at what they do. Capable teams, quality products, years of experience. Yet they are overlooked because they struggle to communicate their value.</p><p>Fastora exists to close that gap. We help businesses communicate more effectively so they become easier to understand, easier to trust, and harder to ignore.</p>',
                    'image' => $strategyPhoto->id,
                    'linkLabel' => null,
                    'linkUrl' => null,
                    'stats' => [
                        ['value' => '10', 'label' => 'Services under one team'],
                        ['value' => 'Africa', 'label' => 'Rooted here, working globally'],
                    ],
                ]],
            ], $aboutReference['layout']),
            'status' => 'published',
            'published_at' => now()->subMonths(8),
            'meta_title' => 'About Fastora',
            'meta_description' => 'Fastora is a communications and digital strategy company helping businesses communicate with clarity, credibility, and confidence.',
        ]);

        Page::updateOrCreate(['slug' => 'insights'], [
            'title' => 'Insights',
            'hero_type' => 'none',
            'page_header_eyebrow' => 'Insights',
            'page_header_heading' => 'Thinking on communication and brand strategy',
            'page_header_description' => 'Practical ideas on communications, branding, and digital strategy, for businesses that want to be understood, not just seen.',
            'layout' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
            'meta_title' => 'Insights',
            'meta_description' => 'Practical thinking on communications, branding, and digital strategy from the Fastora team.',
        ]);

        Page::updateOrCreate(['slug' => 'case-studies'], [
            'title' => 'Case Studies',
            'hero_type' => 'none',
            'page_header_eyebrow' => 'Case studies',
            'page_header_heading' => 'Results, not just deliverables',
            'page_header_description' => 'A look at how we help businesses communicate with more clarity, credibility, and confidence.',
            'layout' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
        ]);

        Page::updateOrCreate(['slug' => 'contact'], [
            'title' => 'Contact',
            'hero_type' => 'none',
            'page_header_eyebrow' => 'Contact',
            'page_header_heading' => "Let's start your project",
            'page_header_description' => "Tell us where you want to go. We'll come back with how to get there, fast.",
            'faqs' => [
                [
                    'question' => 'What happens after I submit the form?',
                    'answer' => "We'll review your message and follow up within one to two business days to schedule a consultation.",
                ],
                [
                    'question' => 'Is the first consultation free?',
                    'answer' => "Yes. The first consultation is a conversation about your business and communication goals, with no obligation.",
                ],
                [
                    'question' => 'What information should I include in my message?',
                    'answer' => "A short description of your business, what you're hoping to achieve, and which service you're interested in helps us prepare for the call.",
                ],
            ],
            'layout' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
        ]);

        $this->applyReferenceServiceCopy();
    }

    /**
     * Writes the full per-service copy from the reference build.
     *
     * A migration backfills this same content for databases that already exist,
     * but on a fresh install migrations run *before* the seeder, so that
     * migration finds no services and does nothing. Without this call a new
     * database would come up with the short placeholder summaries while a
     * migrated one had the full copy.
     *
     * Both read the same file so the two paths cannot drift.
     */
    protected function applyReferenceServiceCopy(): void
    {
        $copy = require database_path('data/reference-services.php');

        foreach ($copy as $slug => $data) {
            Service::query()->where('slug', $slug)->update([
                'summary' => $data['summary'],
                'problem' => $data['problem'],
                'approach' => $data['approach'],
                'deliverables' => array_map(
                    fn (string $label) => ['label' => $label],
                    $data['deliverables'],
                ),
                'faqs' => $data['faqs'],
            ]);
        }

        // Six featured, in the reference's order, so the home page grid fills its
        // two columns exactly rather than ending on a half-empty row. Set here
        // for the same reason as the copy above: on a fresh database the
        // migration that does this runs before any service exists.
        $featured = [
            'strategic-communications',
            'brand-consulting',
            'content-strategy',
            'social-media-management',
            'digital-marketing',
            'communication-advisory',
        ];

        Service::query()->whereNotIn('slug', $featured)->update(['featured_on_home' => false]);

        foreach ($featured as $position => $slug) {
            Service::query()->where('slug', $slug)->update([
                'featured_on_home' => true,
                'order' => $position + 1,
            ]);
        }
    }

    /**
     * The brand mark used for the header, footer and favicon.
     *
     * The icon alone, without the wordmark: the header already sits beside the
     * site name, so repeating it in the logo said the same thing twice.
     *
     * Goes through importImage rather than reading from public/images/brand,
     * so the file lives in database/seeders/images alongside the photography
     * and app:sync-media restores it on every deploy like everything else. When
     * it was read straight from public/ it was the one asset that could not be
     * re-synced if storage was ever cleared.
     */
    protected function seedBrandMedia(): Media
    {
        return $this->importImage('icon-color.png', 'Fastora icon');
    }

    /**
     * Copies a photo from database/seeders/images onto the public disk and
     * registers it in the media library, so the site launches with real
     * imagery instead of the logo standing in for every picture.
     *
     * The files live in this repository rather than the frontend's so they
     * travel with the deploy that needs them.
     */
    protected function importImage(string $filename, string $alt): Media
    {
        $source = database_path('seeders/images/' . $filename);
        $path = 'seed/' . $filename;

        if (is_file($source) && ! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        $dimensions = Storage::disk('public')->exists($path)
            ? @getimagesize(Storage::disk('public')->path($path))
            : false;

        return Media::updateOrCreate(['path' => $path, 'disk' => 'public'], [
            'filename' => $filename,
            'mime_type' => str_ends_with(strtolower($filename), '.png') ? 'image/png' : 'image/jpeg',
            'size' => Storage::disk('public')->exists($path) ? Storage::disk('public')->size($path) : 0,
            'alt' => $alt,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ]);
    }
}
