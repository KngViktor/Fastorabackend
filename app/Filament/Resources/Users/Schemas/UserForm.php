<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->label('Email address')->email()->required()->unique(ignoreRecord: true),
                Select::make('role')
                    ->options(function () {
                        $options = [
                            'admin' => 'Admin',
                            'editor' => 'Editor',
                            'commenter' => 'Commenter',
                        ];

                        // Only a super_admin can grant the super_admin role —
                        // an admin can promote up to "admin" at most.
                        if (Auth::user()?->isSuperAdmin()) {
                            $options = ['super_admin' => 'Super Admin'] + $options;
                        }

                        return $options;
                    })
                    ->default('editor')
                    ->required()
                    ->helperText('Super Admins manage everything without restriction. Admins manage content and editor/commenter accounts. Editors manage content only. Commenters have read-only access.'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Leave blank to keep the current password.')
                    ->autocomplete('new-password'),
            ]);
    }
}
