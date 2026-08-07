<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use App\Filament\Concerns\HasMediaSelect;
use App\Filament\Concerns\HasSeoFields;
use App\Models\Media;
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

class CaseStudyForm
{
    use HasMediaSelect;
    use HasSeoFields;

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('CaseStudy')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Details')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('The headline, e.g. "Building trust before asking people to buy."'),
                            TextInput::make('slug')->required(),
                            TextInput::make('client_name')->required(),
                            Textarea::make('summary')
                                ->required()
                                ->columnSpanFull()
                                ->helperText('One line, shown on the case studies grid card.'),
                            RichEditor::make('hero_intro')
                                ->label('Hero intro')
                                ->columnSpanFull()
                                ->helperText('The paragraphs under the headline at the top of the page.'),
                            static::mediaSelect('coverImage', 'Cover image')->required(),
                            Select::make('related_service_id')
                                ->relationship('relatedService', 'title')
                                ->searchable()
                                ->preload()
                                ->label('Primary service')
                                ->helperText('Used to filter case studies by service.'),
                            TextInput::make('order')->numeric()->default(0)->label('Order'),
                            Toggle::make('featured_on_home')->label('Featured on home'),
                        ])
                        ->columns(2),

                    Tab::make('Facts')
                        ->schema([
                            TextInput::make('industry'),
                            TextInput::make('location')->helperText('e.g. "Greater Toronto Area, Canada"'),
                            TextInput::make('engagement')->helperText('e.g. "October 2024 to April 2026"'),
                            Repeater::make('service_labels')
                                ->label('Services delivered')
                                ->columnSpanFull()
                                ->simple(TextInput::make('label')->required())
                                ->helperText('Plain text. Everything delivered on the engagement, including work that has no service page.')
                                ->addActionLabel('Add service')
                                ->defaultItems(0),
                        ])
                        ->columns(2),

                    Tab::make('Story')
                        ->schema([
                            RichEditor::make('the_business')->label('The business')->columnSpanFull(),
                            RichEditor::make('what_we_noticed')->label('What we noticed')->columnSpanFull(),
                            RichEditor::make('our_thinking')->label('Our thinking')->columnSpanFull(),
                            RichEditor::make('what_we_did')->label('What we did')->columnSpanFull(),
                            RichEditor::make('standout_copy')
                                ->label('Standout section')
                                ->columnSpanFull()
                                ->helperText('Optional. A longer section after the results, e.g. "One moment that stood out".'),
                            TextInput::make('standout_heading')
                                ->label('Standout heading')
                                ->helperText('Only shown if the section above has copy.'),
                            TextInput::make('takeaway_heading')->label('Takeaway heading')->default('One takeaway'),
                            RichEditor::make('takeaway_copy')->label('Takeaway')->columnSpanFull(),
                        ])
                        ->columns(2),

                    Tab::make('Results')
                        ->schema([
                            TextInput::make('results_heading')
                                ->label('Heading')
                                ->helperText('e.g. "What changed" or "What the audit revealed".'),
                            Select::make('results_placement')
                                ->label('Position')
                                ->options([
                                    '' => 'After "What we did" (default)',
                                    'after_thinking' => 'Before "What we did"',
                                ])
                                ->helperText('Put the numbers up front when they are what prompted the work.'),
                            RichEditor::make('results_intro')->label('Intro')->columnSpanFull(),
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
                            RichEditor::make('results_note')
                                ->label('Note under the numbers')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Tab::make('Quote & images')
                        ->schema([
                            Textarea::make('testimonial_quote')->label('Client quote')->columnSpanFull(),
                            TextInput::make('testimonial_author')->label('Attributed to'),
                            TextInput::make('testimonial_role')->label('Role and company'),
                            Repeater::make('gallery')
                                ->label('Images')
                                ->columnSpanFull()
                                ->schema([
                                    Select::make('media_id')
                                        ->label('Image')
                                        ->options(fn () => Media::query()->pluck('filename', 'id'))
                                        ->searchable()
                                        ->required(),
                                    TextInput::make('caption')->label('Caption'),
                                ])
                                ->columns(2)
                                ->addActionLabel('Add image')
                                ->defaultItems(0),
                        ])
                        ->columns(2),

                    Tab::make('Closing')
                        ->schema([
                            Repeater::make('related_service_slugs')
                                ->label('Related services')
                                ->columnSpanFull()
                                ->simple(
                                    Select::make('slug')
                                        ->options(fn () => Service::query()->pluck('title', 'slug'))
                                        ->searchable()
                                        ->required(),
                                )
                                ->addActionLabel('Add service')
                                ->defaultItems(0),
                            TextInput::make('cta_heading')->label('CTA heading'),
                            TextInput::make('cta_label')->label('CTA button label')->default('Book a Conversation'),
                            RichEditor::make('cta_copy')->label('CTA copy')->columnSpanFull(),
                        ])
                        ->columns(2),

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
