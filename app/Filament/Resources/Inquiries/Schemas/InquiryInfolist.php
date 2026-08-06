<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Read-only view of an enquiry — what someone sent, laid out to be read rather
 * than edited.
 *
 * An enquiry is a message from outside the business. Opening it in a form invited
 * accidental edits to a record of what somebody actually said, and read as an
 * editing task rather than something to answer. Status is the one field that is
 * genuinely ours to change, so the edit screen still exists for that.
 */
class InquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Message')
                ->schema([
                    TextEntry::make('brief')
                        ->label('What they wrote')
                        ->columnSpanFull()
                        ->prose(),
                    // Consultation requests only.
                    TextEntry::make('preferred_times')
                        ->label('Times they can make')
                        ->columnSpanFull()
                        ->placeholder('-')
                        ->visible(fn ($record): bool => filled($record->preferred_times)),
                    TextEntry::make('timezone')
                        ->label('Their timezone')
                        ->placeholder('-')
                        ->visible(fn ($record): bool => filled($record->timezone)),
                ])
                ->columns(2),

            Section::make('Who sent it')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('email')
                        ->label('Email address')
                        ->copyable()
                        ->copyMessage('Address copied')
                        // The reply is the whole point, so make it one click.
                        ->url(fn ($record): ?string => $record->email ? 'mailto:' . $record->email : null),
                    TextEntry::make('phone')
                        ->label('Phone')
                        ->placeholder('-')
                        ->url(fn ($record): ?string => $record->phone ? 'tel:' . $record->phone : null),
                    TextEntry::make('website_url')
                        ->label('Website')
                        ->placeholder('-'),
                    TextEntry::make('company')->placeholder('-'),
                    TextEntry::make('serviceNeeded.title')
                        ->label('Service')
                        ->placeholder('Not specified'),
                    TextEntry::make('budget_range')
                        ->label('Budget')
                        ->placeholder('-'),
                    TextEntry::make('timeline')->placeholder('-'),
                ])
                ->columns(2),

            Section::make('Handling')
                ->schema([
                    TextEntry::make('kind')
                        ->label('Type')
                        ->badge()
                        ->formatStateUsing(fn (?string $state): string => $state === 'consultation' ? 'Consultation request' : 'General enquiry'),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('created_at')
                        ->label('Received')
                        ->dateTime(),
                ])
                ->columns(3),
        ]);
    }
}
