<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use App\Models\Media;
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

class CaseStudyForm
{
    use HasMediaSelect;

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('CaseStudy')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Details')
                        ->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('slug')->required(),
                            Textarea::make('summary')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('Shown on the case studies grid card.'),
                            TextInput::make('client_name')->required(),
                            TextInput::make('industry'),
                            static::mediaSelect('coverImage', 'Cover image')->required(),
                            Repeater::make('gallery')
                                ->columnSpanFull()
                                ->schema([
                                    Select::make('media_id')
                                        ->label('Image')
                                        ->options(fn () => Media::query()->pluck('filename', 'id'))
                                        ->searchable()
                                        ->required(),
                                ])
                                ->addActionLabel('Add image')
                                ->defaultItems(0),
                            TextInput::make('order')->numeric()->default(0)->label('Order'),
                            Toggle::make('featured_on_home')->label('Featured on home'),
                            Select::make('related_service_id')
                                ->relationship('relatedService', 'title')
                                ->searchable()
                                ->preload()
                                ->label('Related service'),
                        ])
                        ->columns(2),

                    Tab::make('Story')
                        ->schema([
                            RichEditor::make('challenge')->columnSpanFull(),
                            RichEditor::make('approach')->columnSpanFull(),
                            Repeater::make('results')
                                ->label('Results / Metrics')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('metric')->required()->helperText('e.g. "+142%"'),
                                    TextInput::make('label')->required()->helperText('e.g. "organic reach in 90 days"'),
                                ])
                                ->columns(2)
                                ->addActionLabel('Add result')
                                ->defaultItems(0),
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
