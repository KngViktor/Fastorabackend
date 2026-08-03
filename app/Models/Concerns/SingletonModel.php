<?php

namespace App\Models\Concerns;

/**
 * Mirrors Payload's "globals" (Site Settings, Header, Footer): exactly one
 * row ever exists. `current()` fetches that row, creating it with defaults
 * on first access so the Filament settings page always has something to edit.
 */
trait SingletonModel
{
    public static function current(): static
    {
        $model = static::query()->firstOrCreate([]);

        // firstOrCreate() returns the model as it was inserted, holding only
        // the id and timestamps. The column defaults declared in the migration
        // (site name, brand colors) are applied by the database and are not
        // read back, so without this the very first request after a fresh
        // deploy serves nulls for every defaulted field.
        return $model->wasRecentlyCreated ? $model->refresh() : $model;
    }
}
