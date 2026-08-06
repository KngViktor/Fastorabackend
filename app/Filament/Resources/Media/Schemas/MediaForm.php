<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('File')
                    ->disk('public')
                    ->directory('media')
                    ->image()
                    ->requiredWithout('url')
                    ->columnSpanFull(),
                TextInput::make('url')
                    ->label('...or paste an image URL')
                    ->url()
                    ->requiredWithout('path')
                    ->helperText("We'll download and store a copy, so the image keeps working even if the original goes away.")
                    ->columnSpanFull(),
                TextInput::make('alt')
                    ->label('Alt text')
                    ->helperText('Describe the image for accessibility and SEO.')
                    ->columnSpanFull(),
                Hidden::make('disk')->default('public'),
            ]);
    }
}
