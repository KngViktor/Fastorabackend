<?php

namespace App\Observers;

use App\Support\RevalidatesFrontend;

/**
 * Shared by SiteSetting, NavHeader, and NavFooter — all three affect every
 * page's shared layout (brand colors, logo, nav links, footer), so a change
 * revalidates "/" with Next.js's "layout" type, which cascades to every page
 * nested under it rather than just the homepage.
 */
class SiteWideSettingsObserver
{
    public function saved(): void
    {
        RevalidatesFrontend::revalidate(['/']);
    }
}
