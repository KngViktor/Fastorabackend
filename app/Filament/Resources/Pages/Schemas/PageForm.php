<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

/**
 * The flexible page-builder — the direct equivalent of Payload's `blocks`
 * field. Each Builder block below mirrors one of the Next.js block
 * components (src/blocks/*), so content authored here renders through the
 * matching component once the frontend is wired to this API.
 */
class PageForm
{
    use HasMediaSelect;

    protected static function linksRepeater(string $name = 'links'): Repeater
    {
        return Repeater::make($name)
            ->schema([
                TextInput::make('label')->required(),
                TextInput::make('url')->required(),
                Select::make('appearance')
                    ->options(['default' => 'Default', 'outline' => 'Outline'])
                    ->default('default'),
            ])
            ->columns(3)
            ->addActionLabel('Add link')
            ->defaultItems(0)
            ->columnSpanFull();
    }

    protected static function eyebrowHeading(): array
    {
        return [
            TextInput::make('eyebrow'),
            TextInput::make('heading')->required(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Page')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Hero')
                        ->schema([
                            TextInput::make('title')->required(),
                            TextInput::make('slug')->required(),
                            Select::make('hero_type')
                                ->label('Hero type')
                                ->options([
                                    'none' => 'None',
                                    'highImpact' => 'High impact',
                                    'mediumImpact' => 'Medium impact',
                                    'lowImpact' => 'Low impact',
                                ])
                                ->default('lowImpact')
                                ->required()
                                ->live(),
                            static::mediaSelect('heroMedia', 'Hero media')
                                ->visible(fn ($get) => in_array($get('hero_type'), ['highImpact', 'mediumImpact'])),
                            RichEditor::make('hero_rich_text')->label('Hero text')->columnSpanFull(),
                            static::linksRepeater('hero_links'),
                        ])
                        ->columns(2),

                    Tab::make('Content')
                        ->schema([
                            Builder::make('layout')
                                ->columnSpanFull()
                                ->addActionLabel('Add block')
                                ->blocks([
                                    Block::make('cta')
                                        ->label('Call to Action')
                                        ->schema([
                                            RichEditor::make('richText')->label('Text')->columnSpanFull(),
                                            static::linksRepeater(),
                                        ]),

                                    Block::make('content')
                                        ->label('Content')
                                        ->schema([
                                            RichEditor::make('richText')->label('Text')->columnSpanFull(),
                                        ]),

                                    Block::make('mediaBlock')
                                        ->label('Media')
                                        ->schema([
                                            static::mediaSelect('media', 'Image'),
                                            TextInput::make('caption'),
                                        ]),

                                    Block::make('servicesOverview')
                                        ->label('Services Overview')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            TextInput::make('limit')->numeric()->default(6),
                                        ]),

                                    Block::make('whyFastora')
                                        ->label('Why Fastora')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            Repeater::make('points')
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('stat')->required()->helperText('e.g. "89%"'),
                                                    TextInput::make('title')->required(),
                                                    TextInput::make('description')->required(),
                                                ])
                                                ->columns(3)
                                                ->addActionLabel('Add point')
                                                ->defaultItems(0),
                                        ]),

                                    Block::make('selectedWork')
                                        ->label('Selected Work / Case Studies')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            TextInput::make('limit')->numeric()->default(3),
                                        ]),

                                    Block::make('testimonialsBlock')
                                        ->label('Testimonials')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            TextInput::make('limit')->numeric()->default(3),
                                        ]),

                                    Block::make('faq')
                                        ->label('FAQ')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            Repeater::make('items')
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('question')->required(),
                                                    TextInput::make('answer')->required(),
                                                ])
                                                ->addActionLabel('Add question')
                                                ->defaultItems(0),
                                        ]),

                                    Block::make('latestInsights')
                                        ->label('Latest Insights')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            TextInput::make('limit')->numeric()->default(3),
                                        ]),

                                    Block::make('ourProcess')
                                        ->label('Our Process')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            Repeater::make('steps')
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('title')->required(),
                                                    TextInput::make('description')->required(),
                                                ])
                                                ->addActionLabel('Add step')
                                                ->defaultItems(0),
                                        ]),

                                    Block::make('audienceGrid')
                                        ->label('Audience Grid')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            Repeater::make('items')
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('title')->required(),
                                                    TextInput::make('description')->required(),
                                                ])
                                                ->addActionLabel('Add item')
                                                ->defaultItems(0),
                                        ]),

                                    Block::make('archive')
                                        ->label('Archive')
                                        ->schema([
                                            ...static::eyebrowHeading(),
                                            Select::make('relationTo')
                                                ->options(['posts' => 'Posts', 'case-studies' => 'Case Studies', 'services' => 'Services'])
                                                ->default('posts'),
                                            TextInput::make('limit')->numeric()->default(6),
                                        ]),
                                ]),
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
