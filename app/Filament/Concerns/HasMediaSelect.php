<?php

namespace App\Filament\Concerns;

use App\Models\Media;
use Filament\Forms\Components\Select;

/**
 * Shared "pick an existing Media Library item" field, used by every resource
 * that references an image (icon, cover, avatar, hero, logo, favicon, meta).
 * Upload new files via the Media resource; this just attaches one by relation.
 */
trait HasMediaSelect
{
    protected static function mediaSelect(string $relationshipName, string $label): Select
    {
        return Select::make($relationshipName)
            ->relationship($relationshipName, 'filename')
            ->getOptionLabelFromRecordUsing(fn (Media $record) => $record->alt ?: $record->filename)
            ->searchable()
            ->preload()
            ->label($label);
    }
}
