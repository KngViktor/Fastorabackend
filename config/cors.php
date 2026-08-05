<?php

/**
 * The API answered every origin with Access-Control-Allow-Origin: * and allowed
 * POST, so any website could submit to /api/contact from a visitor's browser —
 * their IP, their session, your enquiries table.
 *
 * Nothing legitimate needs that. The frontend never calls this API from the
 * browser: page rendering fetches server-side, and the contact form posts to the
 * Next.js route on its own domain, which then calls here server to server.
 * Server-to-server requests send no Origin header and are unaffected by CORS, so
 * restricting origins costs the site nothing.
 *
 * Reads are left open. /api/* GETs return only published content, they are the
 * same data any visitor can see on the site, and keeping them reachable from a
 * browser is genuinely useful — for a future widget, or an embed on another
 * domain. It is the write path that needed closing.
 */
return [

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'HEAD', 'OPTIONS', 'POST'],

    /*
     * The frontend's own origins. Add a staging or preview domain here rather
     * than loosening this back to '*'.
     */
    'allowed_origins' => [
        'https://fastora.africa',
        'https://www.fastora.africa',
        'http://localhost:3000',
        'http://localhost:3200',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Accept', 'Authorization', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 3600,

    /*
     * No cookies or credentials cross origins here — the API is token-checked or
     * public, never session-authenticated from another site.
     */
    'supports_credentials' => false,

];
