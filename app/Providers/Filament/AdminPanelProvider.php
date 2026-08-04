<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\ContentOverview;
use App\Filament\Widgets\RecentEnquiries;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->passwordReset()
            ->profile()
            ->brandName('Fastora')
            // The mark alone. brandName already prints "Fastora" beside it, so the
            // wordmark version said it twice — the same reason the public site now
            // uses the icon.
            ->brandLogo(asset('images/brand/icon-color.png'))
            ->brandLogoHeight('2.25rem')
            ->darkModeBrandLogo(asset('images/brand/icon-white.png'))
            ->favicon(asset('images/brand/favicon.png'))
            ->colors([
                // Matches the Next.js frontend's Sky Blue accent (see
                // Fastora's Site Settings → Colors).
                'primary' => Color::hex('#2B7FD6'),
                'gray' => Color::Slate,
                'warning' => Color::hex('#C6A15B'),
            ])
            ->font('Poppins')
            ->viteTheme('resources/css/filament/admin/theme.css')
            // The reference design is light, and Filament otherwise follows the
            // operating system, so anyone on a dark desktop would never see the
            // intended panel. Dark mode stays available from the user menu.
            ->defaultThemeMode(ThemeMode::Light)
            // Groups are declared here rather than inferred, so the sidebar
            // order is deliberate and does not shuffle when a resource is
            // added. Content first because it is the daily work; Site and
            // People are occasional.
            ->navigationGroups([
                NavigationGroup::make('Content'),
                NavigationGroup::make('Library'),
                NavigationGroup::make('Site'),
                NavigationGroup::make('People'),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            // The reference opens its sidebar with the signed-in user rather
            // than hiding identity in a topbar avatar. With four roles that
            // each see different things, that is worth showing outright.
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): View => view('filament.sidebar-user'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            // FilamentInfoWidget is deliberately absent: it advertised the
            // framework version on the client's dashboard instead of telling
            // them anything about their own site.
            ->widgets([
                ContentOverview::class,
                RecentEnquiries::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
