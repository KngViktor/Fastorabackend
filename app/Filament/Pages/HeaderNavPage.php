<?php

namespace App\Filament\Pages;

use App\Models\NavHeader;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

/** Header navigation links, shown site-wide (equivalent of Payload's Header global). */
class HeaderNavPage extends Page
{
    protected string $view = 'filament.pages.header-nav-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $navigationLabel = 'Header Nav';

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Header Navigation';

    public static function canAccess(): bool
    {
        return Auth::user()?->isAdminTier() ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(NavHeader::current()->toArray());
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
        NavHeader::current()->update($this->form->getState());

        Notification::make()->title('Header navigation saved')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')->label('Save')->action('save'),
        ];
    }
}
