<?php

namespace App\Filament\Pages\Auth;

use Illuminate\Contracts\Support\Htmlable;

class Login extends \Filament\Auth\Pages\Login
{
    protected string $view = 'filament.pages.auth.login';

    protected static string $layout = 'filament.components.layout.split';

    public function getHeading(): string|Htmlable
    {
        return 'Welcome home';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Please enter your details.';
    }
}
