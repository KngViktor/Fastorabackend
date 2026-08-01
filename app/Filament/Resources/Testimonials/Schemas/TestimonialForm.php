<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    use HasMediaSelect;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('quote')->required()->columnSpanFull(),
                TextInput::make('client_name')->required(),
                TextInput::make('role')->helperText('e.g. "Founder"'),
                TextInput::make('company'),
                static::mediaSelect('avatar', 'Avatar'),
                TextInput::make('rating')->required()->numeric()->minValue(1)->maxValue(5)->default(5),
                Toggle::make('show_on_home')->default(true)->label('Show on home'),
                Select::make('services')
                    ->relationship('services', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Related services')
                    ->helperText('Optionally show this testimonial on specific service pages.')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
