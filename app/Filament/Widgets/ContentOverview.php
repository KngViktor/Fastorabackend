<?php

namespace App\Filament\Widgets;

use App\Models\CaseStudy;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * What is actually on the site, and what needs attention.
 *
 * Replaces Filament's stock info widget, which advertised the framework rather
 * than telling the client anything about their own content. Each figure counts
 * published items, since a draft is not on the site yet, and each tile links
 * to the list it summarises.
 */
class ContentOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newEnquiries = Inquiry::query()->where('status', 'new')->count();
        $draftPosts = Post::query()->where('status', 'draft')->count();

        return [
            Stat::make('New enquiries', $newEnquiries)
                ->description($newEnquiries > 0 ? 'Waiting for a reply' : 'Nothing outstanding')
                ->descriptionIcon($newEnquiries > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($newEnquiries > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.inquiries.index')),

            Stat::make('Services', Service::published()->count())
                ->description('Live on the site')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary')
                ->url(route('filament.admin.resources.services.index')),

            Stat::make('Case studies', CaseStudy::published()->count())
                ->description('Live on the site')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary')
                ->url(route('filament.admin.resources.case-studies.index')),

            Stat::make('Insights', Post::published()->count())
                ->description($draftPosts > 0 ? "{$draftPosts} still in draft" : 'All published')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color($draftPosts > 0 ? 'gray' : 'primary')
                ->url(route('filament.admin.resources.posts.index')),
        ];
    }
}
