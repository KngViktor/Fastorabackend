<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Support\MediaDownloader;
use Filament\Resources\Pages\CreateRecord;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    /**
     * The form offers a file OR a URL; only `path` is a real column, so a
     * pasted URL is downloaded here and swapped in before the record is
     * created. `url` itself is dropped — Media has no such column.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['path'] ?? null) && filled($data['url'] ?? null)) {
            $data['path'] = MediaDownloader::downloadToPublicDisk($data['url']);
        }

        unset($data['url']);

        return $data;
    }
}
