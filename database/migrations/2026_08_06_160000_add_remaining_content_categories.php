<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the four categories the content document's Insights topic filter
 * lists that don't exist yet (Strategy and Branding already do). Adding a
 * category with no posts in it yet is harmless — it just shows an empty
 * filter result until something is tagged with it.
 */
return new class extends Migration
{
    private const CATEGORIES = [
        ['slug' => 'communication', 'title' => 'Communication'],
        ['slug' => 'content', 'title' => 'Content'],
        ['slug' => 'digital-marketing', 'title' => 'Digital Marketing'],
        ['slug' => 'founder-branding', 'title' => 'Founder Branding'],
    ];

    public function up(): void
    {
        foreach (self::CATEGORIES as $category) {
            DB::table('categories')->insertOrIgnore([
                ...$category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->whereIn('slug', array_column(self::CATEGORIES, 'slug'))->delete();
    }
};
