<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Support\MediaDownloader;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Lets pasting a new URL replace the file the same way it does on
     * create — `path` already holds the existing file, so only a freshly
     * pasted `url` (not the unchanged, already-satisfied requiredWithout)
     * triggers a re-download here.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['url'] ?? null)) {
            $data['path'] = MediaDownloader::downloadToPublicDisk($data['url']);
        }

        unset($data['url']);

        return $data;
    }
}
