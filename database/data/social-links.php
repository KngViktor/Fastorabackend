<?php

/**
 * The official Fastora social accounts, shared by the seeder and the migration
 * that applies them to an existing database.
 *
 * WhatsApp leads because it is the primary contact channel, not a social feed;
 * wa.me wants the number with no spaces, plus sign, or leading zero.
 *
 * @return array<int, array{platform: string, url: string}>
 */
return [
    ['platform' => 'whatsapp', 'url' => 'https://wa.me/2347038147969'],
    ['platform' => 'instagram', 'url' => 'https://instagram.com/fastorahq'],
    ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/fastora'],
    ['platform' => 'facebook', 'url' => 'https://facebook.com/fastorahq'],
    ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@fastorahq'],
    ['platform' => 'twitter', 'url' => 'https://x.com/FastoraHQ'],
    ['platform' => 'threads', 'url' => 'https://threads.com/@fastorahq'],
];
