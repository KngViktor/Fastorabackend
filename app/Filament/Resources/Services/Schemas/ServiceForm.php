<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use App\Filament\Concerns\HasSeoFields;
use App\Models\Service;
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
    use HasSeoFields;

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
                                ->label('Supporting copy')
                                ->columnSpanFull()
                                ->helperText('The longer intro under the page heading, framing the problem this service solves.'),
                            Repeater::make('includes')
                                ->label('Includes')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('label')->required(),
                                ])
                                ->addActionLabel('Add item')
                                ->defaultItems(0)
                                ->helperText('The named services grouped under this one, listed on the card, e.g. "Reputation Management".'),
                        ])
                        ->columns(2),

                    Tab::make('Page')
                        ->schema([
                            TextInput::make('overview_heading')
                                ->label('Overview heading')
                                ->columnSpanFull(),
                            RichEditor::make('overview_copy')
                                ->label('Overview copy')
                                ->columnSpanFull(),
                            Repeater::make('outcomes')
                                ->label('What this helps you achieve')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('label')->required(),
                                ])
                                ->addActionLabel('Add outcome')
                                ->defaultItems(0),
                            Repeater::make('deliverables')
                                ->label("What's included")
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('label')->required(),
                                ])
                                ->addActionLabel('Add item')
                                ->defaultItems(0)
                                ->helperText('The longer list on the page. "Includes" on the Details tab is the short version for cards.'),
                            RichEditor::make('approach')
                                ->label('Our approach')
                                ->columnSpanFull(),
                            Repeater::make('good_fit_if')
                                ->label('This service is a good fit if...')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('label')->required(),
                                ])
                                ->addActionLabel('Add reason')
                                ->defaultItems(0),
                            Repeater::make('related_service_slugs')
                                ->label('Related services')
                                ->columnSpanFull()
                                ->simple(
                                    Select::make('slug')
                                        ->options(fn (): array => Service::query()
                                            ->orderBy('order')
                                            ->pluck('title', 'slug')
                                            ->all())
                                        ->required(),
                                )
                                ->addActionLabel('Add related service')
                                ->defaultItems(0)
                                ->helperText('Shown as "You may also need" and linked to those pages.'),
                            TextInput::make('cta_heading')
                                ->label('Closing call to action heading')
                                ->columnSpanFull(),
                            RichEditor::make('cta_copy')
                                ->label('Closing call to action copy')
                                ->columnSpanFull(),
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
                            ...static::seoFields(),
                        ])
                        ->columns(2),
                ]),
        ]);
    }
}
