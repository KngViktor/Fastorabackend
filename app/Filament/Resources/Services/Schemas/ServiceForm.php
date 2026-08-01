<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ServiceForm
{
    use HasMediaSelect;

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Service')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Details')
                        ->schema([
                            TextInput::make('title')->required()->live(onBlur: true),
                            TextInput::make('slug')->required(),
                            Textarea::make('summary')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('One or two sentences shown on service cards and the services grid.'),
                            static::mediaSelect('icon', 'Icon')
                                ->helperText('Small icon or mark representing this service.'),
                            static::mediaSelect('featuredImage', 'Featured image'),
                            TextInput::make('order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first on the services grid.'),
                            Toggle::make('featured_on_home')->default(true)->label('Featured on home'),
                            RichEditor::make('problem')
                                ->columnSpanFull()
                                ->helperText('What challenge does this service solve for the client?'),
                            RichEditor::make('approach')
                                ->columnSpanFull()
                                ->helperText('How Fastora tackles it.'),
                            Repeater::make('deliverables')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('label')->required(),
                                ])
                                ->addActionLabel('Add deliverable')
                                ->defaultItems(0),
                        ])
                        ->columns(2),

                    Tab::make('FAQs')
                        ->schema([
                            Repeater::make('faqs')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('question')->required(),
                                    Textarea::make('answer')->required(),
                                ])
                                ->addActionLabel('Add FAQ')
                                ->defaultItems(0)
                                ->helperText('Written in direct question-and-answer format so AI search engines can cite them.'),
                        ]),

                    Tab::make('SEO')
                        ->schema([
                            Select::make('status')
                                ->options(['draft' => 'Draft', 'published' => 'Published'])
                                ->default('draft')
                                ->required(),
                            DateTimePicker::make('published_at'),
                            TextInput::make('meta_title'),
                            TextInput::make('meta_description'),
                            static::mediaSelect('metaImage', 'Meta image'),
                        ])
                        ->columns(2),
                ]),
        ]);
    }
}
