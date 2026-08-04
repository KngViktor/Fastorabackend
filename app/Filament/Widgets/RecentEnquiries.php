<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * The newest enquiries, on the first screen after signing in.
 *
 * The dashboard's job is to answer "is there anything I need to do", and for
 * this site that is almost always an unanswered enquiry. Showing them here
 * saves the trip through the sidebar.
 */
class RecentEnquiries extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest enquiries')
            ->query(fn (): Builder => Inquiry::query()->latest()->limit(5))
            ->emptyStateHeading('No enquiries yet')
            ->emptyStateDescription('Submissions from the contact form will appear here.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->weight('medium')
                    ->description(fn (Inquiry $record): ?string => $record->company),

                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->color('gray'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->color('gray'),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Inquiry $record): string => InquiryResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
