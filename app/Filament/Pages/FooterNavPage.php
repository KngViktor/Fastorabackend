<?php

namespace App\Filament\Pages;

use App\Models\NavFooter;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

/** Footer navigation links, shown site-wide (equivalent of Payload's Footer global). */
class FooterNavPage extends Page
{
    protected string $view = 'filament.pages.footer-nav-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected static ?string $navigationLabel = 'Footer Nav';

    protected static ?string $title = 'Footer Navigation';

    public static function canAccess(): bool
    {
        return Auth::user()?->isAdminTier() ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(NavFooter::current()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Repeater::make('nav_items')
                    ->label('Links')
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('url')->required()->helperText('e.g. /services'),
                    ])
                    ->columns(2)
                    ->addActionLabel('Add link')
                    ->defaultItems(0),
            ]);
    }

    public function save(): void
    {
        NavFooter::current()->update($this->form->getState());

        Notification::make()->title('Footer navigation saved')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')->label('Save')->action('save'),
        ];
    }
}
