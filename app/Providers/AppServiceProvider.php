<?php

namespace App\Providers;

use App\Models\CaseStudy;
use App\Models\NavFooter;
use App\Models\NavHeader;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Notifications\FastoraResetPassword;
use App\Observers\CaseStudyObserver;
use App\Observers\PageObserver;
use App\Observers\PostObserver;
use App\Observers\ServiceObserver;
use App\Observers\SiteWideSettingsObserver;
use Filament\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Filament resolves this class from the container when sending a
        // password-reset email — binding our branded subclass here swaps the
        // email's content without touching Filament's request/reset flow.
        $this->app->bind(ResetPassword::class, FastoraResetPassword::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Notify the Next.js frontend to revalidate its cached pages whenever
        // content changes here — see app/Support/RevalidatesFrontend.php.
        Service::observe(ServiceObserver::class);
        CaseStudy::observe(CaseStudyObserver::class);
        Post::observe(PostObserver::class);
        Page::observe(PageObserver::class);
        SiteSetting::observe(SiteWideSettingsObserver::class);
        NavHeader::observe(SiteWideSettingsObserver::class);
        NavFooter::observe(SiteWideSettingsObserver::class);
    }
}
