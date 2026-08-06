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
                ['label' => 'Book a Consultation', 'url' => '/consultation'],
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

        // Real client reviews from the founder's portfolio and LinkedIn, read from
        // the same file the migration uses so a fresh install matches a migrated one.
        //
        // Avatars stay null: there are photographs on the portfolio page but not in
        // this repository, and the logo is not a face.
        foreach (require database_path('data/testimonials.php') as $review) {
            $record = Testimonial::updateOrCreate(
                ['client_name' => $review['client_name'], 'company' => $review['company']],
                [
                    'quote' => $review['quote'],
                    'role' => $review['role'],
                    'avatar_media_id' => null,
                    'rating' => $review['rating'],
                    'show_on_home' => $review['show_on_home'],
                ],
            );

            $record->services()->sync(array_filter([
                Service::where('slug', $review['service_slug'])->value('id'),
            ]));
        }

        $strategyCategory = Category::updateOrCreate(['slug' => 'strategy'], ['title' => 'Strategy']);
        $brandingCategory = Category::updateOrCreate(['slug' => 'branding'], ['title' => 'Branding']);
        // The content document's topic filter needs all six to choose from,
        // even before there's a post in every one.
        Category::updateOrCreate(['slug' => 'communication'], ['title' => 'Communication']);
        Category::updateOrCreate(['slug' => 'content'], ['title' => 'Content']);
        Category::updateOrCreate(['slug' => 'digital-marketing'], ['title' => 'Digital Marketing']);
        Category::updateOrCreate(['slug' => 'founder-branding'], ['title' => 'Founder Branding']);

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

        $home = require database_path('data/reference-home-page.php');

        Page::updateOrCreate(['slug' => 'home'], [
            'title' => 'Home',
            'hero_type' => 'highImpact',
            'hero_eyebrow' => $home['hero_eyebrow'],
            'hero_rich_text' => $home['hero_rich_text'],
            'hero_links' => $home['hero_links'],
            'hero_media_id' => $heroImage->id,
            'layout' => [
                // Six confirmed clients. No logo files yet, so the block renders
                // each as its name until one is attached in the admin.
                ['type' => 'trustedBy', 'data' => [
                    'heading' => 'Trusted by',
                    'logos' => require database_path('data/confirmed-clients.php'),
                ]],

                ['type' => 'aboutFastora', 'data' => [
                    ...$home['about_fastora'],
                    'image' => $studioPhoto->id,
                ]],

                ['type' => 'whyFastora', 'data' => $home['impact_at_a_glance']],

                ['type' => 'servicesOverview', 'data' => $home['services_overview']],

                ['type' => 'whyFastora', 'data' => $home['why_fastora']],

                ['type' => 'selectedWork', 'data' => $home['selected_work']],
                ['type' => 'testimonialsBlock', 'data' => $home['testimonials_block']],
                ['type' => 'latestInsights', 'data' => $home['latest_insights']],
                ['type' => 'faq', 'data' => $home['faq']],
                ['type' => 'cta', 'data' => $home['cta']],
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
            'page_header_heading' => 'Services built around how people experience your business.',
            'page_header_description' => 'Every interaction shapes how people think about your business. Our services help you communicate more intentionally, strengthen your brand, and support long-term growth.',
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

        // Hero copy and layout come entirely from the shared reference file, so
        // this matches what the rebuild migration produces on an existing
        // database.
        $aboutReference = (require database_path('data/reference-about-page.php'))([
            'origin' => $studioPhoto->id,
            'process' => $strategyPhoto->id,
            'audience' => $contentPhoto->id,
            'name' => $analyticsPhoto->id,
        ]);

        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About',
            'hero_type' => 'lowImpact',
            'hero_rich_text' => $aboutReference['hero_rich_text'],
            'hero_media_id' => $studioPhoto->id,
            'layout' => $aboutReference['layout'],
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
            'page_header_heading' => "Let's talk about your business.",
            'page_header_description' => "Every project starts with a conversation. Tell us what you're working on, where you'd like to go, or the challenge you're trying to solve. We'll take it from there.",
            'faqs' => [
                [
                    'question' => 'Do I need to know which service I need?',
                    'answer' => "Not at all. Many clients come to us with a challenge rather than a clear solution. We'll help you decide what makes the most sense after we've learned more about your business.",
                ],
                [
                    'question' => 'What information should I include in my message?',
                    'answer' => "Tell us a little about your business, what you're hoping to achieve, and any challenges you're facing. If you already know which service you're interested in, you can include that too.",
                ],
                [
                    'question' => 'Is the first conversation free?',
                    'answer' => "Yes. The first conversation is an opportunity for us to understand your business, answer your questions, and decide whether we're the right fit to work together.",
                ],
                [
                    'question' => 'What happens after I get in touch?',
                    'answer' => "We'll review your message and, if it looks like we're a good fit, we'll get in touch to arrange a conversation and discuss the best next step for your business.",
                ],
                [
                    'question' => 'How soon will I hear back?',
                    'answer' => 'We aim to respond to every enquiry within one business day.',
                ],
                [
                    'question' => 'Do you work with businesses outside Nigeria?',
                    'answer' => 'Yes. We work with businesses, founders, and organisations across Africa and in other parts of the world.',
                ],
            ],
            'layout' => [],
            'status' => 'published',
            'published_at' => now()->subMonths(8),
            'meta_title' => 'Contact',
            'meta_description' => "Tell us about your business and where you'd like to go. We respond to every enquiry within one business day.",
        ]);

        // The consultation page, from the same shared file the migration uses, so a
        // fresh install and a migrated database produce the same page.
        $consultation = require database_path('data/consultation-page.php');

        Page::updateOrCreate(['slug' => $consultation['slug']], [
            'title' => $consultation['title'],
            'hero_type' => $consultation['hero_type'],
            'hero_eyebrow' => $consultation['hero_eyebrow'],
            'hero_rich_text' => $consultation['hero_rich_text'],
            'hero_links' => [],
            'faqs' => [],
            'layout' => $consultation['layout'],
            'status' => 'published',
            'published_at' => now(),
            'meta_title' => $consultation['meta_title'],
            'meta_description' => $consultation['meta_description'],
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
