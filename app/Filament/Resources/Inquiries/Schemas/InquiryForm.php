<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options(['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'])
                    ->default('new')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('company')
                    ->default(null),
                Select::make('service_needed_id')
                    ->relationship('serviceNeeded', 'title')
                    ->default(null),
                Select::make('budget_range')
                    ->label('Budget')
                    ->options([
                        'under-1k' => 'Under $1,000',
                        '1k-5k' => '$1,000 – $5,000',
                        '5k-15k' => '$5,000 – $15,000',
                        '15k-plus' => '$15,000+',
                        'not-sure' => 'Not sure yet',
                    ]),
                Select::make('timeline')
                    ->options([
                        'asap' => 'ASAP',
                        '1-month' => 'Within 1 month',
                        '1-3-months' => '1–3 months',
                        'exploring' => 'Just exploring',
                    ]),
                Textarea::make('brief')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
