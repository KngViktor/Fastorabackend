<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Real headshots for Emmanuel and Ndidiamaka on the About page team block,
 * plus reordering Ndidiamaka's display name to surname-first ("Ndidiamaka
 * Eya"), matching the change the earlier bio-wording migration already made
 * to her bio but not her name.
 *
 * Matched by name/role rather than replayed wholesale from
 * reference-about-page.php, since that file also carries the rest of the
 * About page copy and this change is scoped to two members' photo and one
 * member's name.
 *
 * A fresh database never sees this: the seeder builds the team block from
 * reference-about-page.php directly, which already has both photos and the
 * reordered name.
 */
return new class extends Migration
{
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

        $changed = false;

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) !== 'team') {
                continue;
            }

            foreach ($block['data']['members'] ?? [] as $m => $member) {
                $name = $member['name'] ?? null;

                if ($name === 'Emmanuel Akaluese' && empty($member['photo'])) {
                    $photo = $this->importImage('team-emmanuel-akaluese.jpg', 'Emmanuel Akaluese, Operations Associate at Fastora');
                    $layout[$i]['data']['members'][$m]['photo'] = $photo->id;
                    $changed = true;
                }

                if (in_array($name, ['Eya Ndidiamaka', 'Ndidiamaka Eya'], true)) {
                    if ($name === 'Eya Ndidiamaka') {
                        $layout[$i]['data']['members'][$m]['name'] = 'Ndidiamaka Eya';
                        $changed = true;
                    }

                    if (empty($member['photo'])) {
                        $photo = $this->importImage('team-ndidiamaka-eya.jpg', 'Ndidiamaka Eya, Digital Communications Associate at Fastora');
                        $layout[$i]['data']['members'][$m]['photo'] = $photo->id;
                        $changed = true;
                    }
                }
            }
        }

        if (! $changed) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
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

    public function down(): void
    {
        // Not restorable to the exact prior state — matches the pattern of the
        // other one-way content-tweak migrations in this batch.
    }
};
