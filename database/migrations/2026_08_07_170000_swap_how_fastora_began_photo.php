<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Replaces the stock studio photo beside "How Fastora began" on the About
 * page with a real photo of the team. That section is the one place on the
 * site introducing who is behind the business, which made it the natural
 * home for the new photo over the About hero or the same stock photo's other
 * appearances (home page intro, About hero) — those are left untouched.
 *
 * Matched by the block's `<h2>` heading, the same approach
 * 2026_08_06_180000 uses, so an editor who has since replaced this image
 * themselves is left alone.
 */
return new class extends Migration
{
    private const HEADING = 'How Fastora began';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $photo = $this->importImage('team-group-photo.jpg', 'The Fastora team');
        $changed = false;

        foreach ($layout as $index => $block) {
            if (($block['type'] ?? null) !== 'content') {
                continue;
            }

            $richText = $block['data']['richText'] ?? '';

            if (! str_starts_with($richText, '<h2>' . self::HEADING . '</h2>')) {
                continue;
            }

            if (($block['data']['image'] ?? null) === $photo->id) {
                continue;
            }

            $layout[$index]['data']['image'] = $photo->id;
            $changed = true;
        }

        if (! $changed) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Not restorable to the exact prior image — matches the pattern of
        // the other one-way content-tweak migrations in this batch.
    }

    /**
     * Copies a seeder image onto the public disk and registers it in the media
     * library. Mirrors DatabaseSeeder::importImage — the migration cannot call
     * it, and duplicating a dozen lines is better than making the seeder's
     * protected helper public just for this.
     */
    private function importImage(string $filename, string $alt): object
    {
        $source = database_path('seeders/images/' . $filename);
        $path = 'seed/' . $filename;

        if (is_file($source) && ! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        $existing = DB::table('media')->where('path', $path)->where('disk', 'public')->first();

        if ($existing !== null) {
            return $existing;
        }

        $dimensions = Storage::disk('public')->exists($path)
            ? @getimagesize(Storage::disk('public')->path($path))
            : false;

        $id = DB::table('media')->insertGetId([
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'mime_type' => str_ends_with(strtolower($filename), '.png') ? 'image/png' : 'image/jpeg',
            'size' => Storage::disk('public')->exists($path) ? Storage::disk('public')->size($path) : 0,
            'alt' => $alt,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('media')->where('id', $id)->first();
    }
};
