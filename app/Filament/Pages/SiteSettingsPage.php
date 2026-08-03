<?php

namespace App\Filament\Pages;

use App\Models\Media;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

/**
 * Global brand assets, contact details, and footer content shared across
 * every page — the equivalent of Payload's "Site Settings" global. There is
 * always exactly one row (see SiteSetting::current()).
 */
class SiteSettingsPage extends Page
{
    protected string $view = 'filament.pages.site-settings-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    public static function canAccess(): bool
    {
        return Auth::user()?->isAdminTier() ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    protected function mediaOptions(): array
    {
        return Media::query()->pluck('filename', 'id')->all();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('SiteSettings')
                    ->tabs([
                        Tab::make('Brand')
                            ->schema([
                                TextInput::make('site_name')->label('Site name')->required(),
                                TextInput::make('tagline'),
                                Select::make('logo_light_media_id')
                                    ->label('Logo (for light backgrounds)')
                                    ->options(fn () => $this->mediaOptions())
                                    ->searchable(),
                                Select::make('logo_dark_media_id')
                                    ->label('Logo (for dark backgrounds, e.g. footer)')
                                    ->options(fn () => $this->mediaOptions())
                                    ->searchable(),
                                Select::make('favicon_media_id')
                                    ->label('Favicon')
                                    ->options(fn () => $this->mediaOptions())
                                    ->searchable(),
                            ])
                            ->columns(2),

                        Tab::make('Colors')
                            ->schema([
                                TextInput::make('accent_color')->label('Accent')->helperText('Buttons, links, highlights, CTAs.'),
                                TextInput::make('gold_color')
                                    ->label('Gold')
                                    ->helperText('Reserved for emphasis: result figures, statistics, and the hero label. Kept sparing so it stays a premium accent rather than a second brand colour.'),
                                TextInput::make('background_color')->label('Background'),
                                TextInput::make('text_color')->label('Text'),
                                TextInput::make('surface_color')->label('Surface (cards)'),
                                TextInput::make('border_color')->label('Borders'),
                                TextInput::make('muted_text_color')->label('Muted text'),
                                TextInput::make('primary_color')->label('Dark panel background')->helperText('Hero, footer, feature bands.'),
                                TextInput::make('dark_panel_text_color')->label('Dark panel text'),
                            ])
                            ->columns(2),

                        Tab::make('Contact')
                            ->schema([
                                TextInput::make('contact_email')->email()->label('Contact email'),
                                TextInput::make('contact_phone')->label('Contact phone'),
                                TextInput::make('address'),
                                Repeater::make('social_links')
                                    ->columnSpanFull()
                                    ->schema([
                                        Select::make('platform')
                                            ->options([
                                                'instagram' => 'Instagram',
                                                'twitter' => 'X / Twitter',
                                                'linkedin' => 'LinkedIn',
                                                'tiktok' => 'TikTok',
                                                'youtube' => 'YouTube',
                                                'facebook' => 'Facebook',
                                                'whatsapp' => 'WhatsApp',
                                            ])
                                            ->required(),
                                        TextInput::make('url')->required()->url(),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Add social link')
                                    ->defaultItems(0),
                            ])
                            ->columns(2),

                        Tab::make('Footer')
                            ->schema([
                                Textarea::make('footer_text')->columnSpanFull(),
                                TextInput::make('newsletter_heading'),
                                TextInput::make('newsletter_subheading'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()->title('Site settings saved')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')->label('Save')->action('save'),
        ];
    }
}
