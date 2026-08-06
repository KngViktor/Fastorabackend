<?php

/**
 * Privacy Policy and Terms & Conditions, shared by the seeder and the
 * migration that creates them on an existing database.
 *
 * Written to reflect what this app actually does — the specific forms it
 * collects data through, the specific cookie it sets, the specific
 * providers a save in Site Settings can route data to — rather than generic
 * boilerplate. Still worth a lawyer's pass before treating as final, the
 * same as any policy page, but it isn't placeholder text.
 */
return [
    'privacy_policy' => [
        'title' => 'Privacy Policy',
        'meta_title' => 'Privacy Policy',
        'meta_description' => 'How Fastora collects, uses, and protects the information you share with us.',
        'hero_rich_text' => '<h1>Privacy Policy</h1>'
            . '<p>This explains what information we collect when you use this website, why, and how you can control it.</p>',
        'body' => '<p><em>Last updated: 6 August 2026.</em></p>'

            . '<h2>Information we collect</h2>'
            . '<p>We collect information you choose to give us, and a small amount automatically.</p>'
            . '<ul>'
            . '<li><strong>Contact and consultation forms.</strong> Your name, email address, and message, and — if you use the consultation form — your phone number, company, the service you\'re interested in, your budget range, timeline, preferred meeting times, and timezone.</li>'
            . '<li><strong>Newsletter sign-up.</strong> Just your email address.</li>'
            . '<li><strong>Usage data.</strong> Only if you accept analytics cookies (see below), we collect anonymised information about how you use the site through Google Analytics.</li>'
            . '</ul>'

            . '<h2>How we use it</h2>'
            . '<ul>'
            . '<li>To respond to your enquiry or consultation request.</li>'
            . '<li>To send the Fastora Journal to people who\'ve subscribed to it.</li>'
            . '<li>To understand how the site is used, so we can improve it — only when you\'ve accepted analytics cookies.</li>'
            . '</ul>'
            . '<p>We do not sell, rent, or trade your information to anyone.</p>'

            . '<h2>Cookies</h2>'
            . '<p>We use one cookie-equivalent by default: a note in your browser\'s local storage that remembers whether you\'ve accepted or declined analytics cookies. This is necessary for the site to work as intended and doesn\'t require consent on its own.</p>'
            . '<p>If you accept analytics cookies, Google Analytics sets its own cookies to recognise repeat visits and measure site usage. If you decline, or haven\'t made a choice yet, none of this runs and no analytics cookie is set. We don\'t use advertising or tracking cookies.</p>'
            . '<p>You can change your choice at any time by clearing your browser\'s local storage for this site, which brings the cookie banner back.</p>'

            . '<h2>Who we share information with</h2>'
            . '<ul>'
            . '<li><strong>Our hosting provider</strong>, to run this website and store the information above.</li>'
            . '<li><strong>Our email provider</strong>, to send you a reply or the newsletter.</li>'
            . '<li><strong>A newsletter platform</strong>, if we\'ve connected one — used only to deliver the Fastora Journal to subscribers.</li>'
            . '<li><strong>Google Analytics</strong>, only if you\'ve accepted analytics cookies.</li>'
            . '</ul>'
            . '<p>None of these are permitted to use your information for their own purposes.</p>'

            . '<h2>How long we keep it</h2>'
            . '<p>We keep enquiry and consultation details for as long as reasonably needed to respond to you and maintain a record of the conversation, or until you ask us to delete them. Newsletter subscriptions are kept until you unsubscribe.</p>'

            . '<h2>Your rights</h2>'
            . '<ul>'
            . '<li>You can ask what information we hold about you, or ask us to correct or delete it, by emailing us.</li>'
            . '<li>You can unsubscribe from the newsletter at any time using the link in every email we send, or by asking us directly.</li>'
            . '</ul>'

            . '<h2>Changes to this policy</h2>'
            . '<p>We may update this policy from time to time. The date at the top shows when it was last changed.</p>'

            . '<h2>Contact us</h2>'
            . '<p>If you have any questions about this policy or how we handle your information, reach us at %s or %s.</p>',
    ],

    'terms_conditions' => [
        'title' => 'Terms & Conditions',
        'meta_title' => 'Terms & Conditions',
        'meta_description' => 'The terms that govern your use of the Fastora website and our services.',
        'hero_rich_text' => '<h1>Terms &amp; Conditions</h1>'
            . '<p>The terms that apply when you use this website or engage us for our services.</p>',
        'body' => '<p><em>Last updated: 6 August 2026.</em></p>'

            . '<h2>Acceptance of these terms</h2>'
            . '<p>By using this website, you agree to these terms. If you don\'t agree with them, please don\'t continue using the site.</p>'

            . '<h2>Use of this website</h2>'
            . '<p>You may browse this site and use its forms to get in touch with us. You agree not to misuse it — for example, by submitting false information, attempting to disrupt the site, or scraping content without our permission.</p>'

            . '<h2>Our services</h2>'
            . '<p>Any communications, branding, content, or digital marketing services we provide are governed by the specific agreement or proposal you and Fastora agree to separately — these website terms don\'t replace that. A consultation booked through this site is an initial conversation and doesn\'t itself create a contract for paid work.</p>'
            . '<p>Communications and marketing outcomes depend on many factors outside our control — market conditions, audience behaviour, platform algorithms, and your own business decisions among them. We bring care, expertise, and honesty to every engagement, but we don\'t guarantee specific results, rankings, follower counts, or revenue figures.</p>'

            . '<h2>Intellectual property</h2>'
            . '<p>The content on this site — text, images, logos, and design — belongs to Fastora or is used with permission, unless stated otherwise. You may not reproduce or redistribute it without our consent.</p>'

            . '<h2>Limitation of liability</h2>'
            . '<p>We work to keep this site accurate and available, but we make no guarantee it will always be error-free or uninterrupted. To the extent permitted by law, Fastora isn\'t liable for any loss or damage arising from your use of this website.</p>'

            . '<h2>Links to other sites</h2>'
            . '<p>This site may link to third-party websites, including client work and social platforms. We aren\'t responsible for the content or practices of sites we don\'t control.</p>'

            . '<h2>Governing law</h2>'
            . '<p>These terms are governed by the laws of the Federal Republic of Nigeria, without regard to conflict-of-law principles.</p>'

            . '<h2>Changes to these terms</h2>'
            . '<p>We may update these terms from time to time. The date at the top shows when they were last changed.</p>'

            . '<h2>Contact us</h2>'
            . '<p>Questions about these terms? Reach us at %s or %s.</p>',
    ],
];
