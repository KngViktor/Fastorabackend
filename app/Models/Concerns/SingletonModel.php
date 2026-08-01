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
        return static::query()->firstOrCreate([]);
    }
}
