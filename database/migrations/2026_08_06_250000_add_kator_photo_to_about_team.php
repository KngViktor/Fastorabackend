<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Registers Kator Tarkaa's headshot and wires it into the About page's team
 * block.
 *
 * Self-contained rather than assuming app:sync-media has already copied the
 * file: app:deploy runs migrations before the media sync, so a migration
 * that only *looks up* a Media row by alt text (assuming the sync already
 * ran) would find nothing on the very deploy that introduces the file. This
 * copies it and creates the row itself, the same way the seeder's
 * importImage() does.
 */
return new class extends Migration
{
    private const FILENAME = 'kator-tarkaa.jpg';
    private const ALT = 'Kator Tarkaa, Founder & Digital Communications Strategist at Fastora';

    public function up(): void
    {
        $mediaId = $this->registerPhoto();

        if ($mediaId === null) {
            return;
        }

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
                if (($member['name'] ?? null) === 'Kator Tarkaa' && blank($member['photo'] ?? null)) {
                    $layout[$i]['data']['members'][$m]['photo'] = $mediaId;
                    $changed = true;
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

    private function registerPhoto(): ?int
    {
        $source = database_path('seeders/images/' . self::FILENAME);

        if (! is_file($source)) {
            return null;
        }

        $path = 'seed/' . self::FILENAME;
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $disk->put($path, file_get_contents($source));
        }

        $existing = DB::table('media')->where('path', $path)->where('disk', 'public')->first();

        if ($existing) {
            return $existing->id;
        }

        $dimensions = @getimagesize($disk->path($path));

        return DB::table('media')->insertGetId([
            'disk' => 'public',
            'path' => $path,
            'filename' => self::FILENAME,
            'mime_type' => 'image/jpeg',
            'size' => $disk->size($path),
            'alt' => self::ALT,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) !== 'team') {
                continue;
            }

            foreach ($block['data']['members'] ?? [] as $m => $member) {
                if (($member['name'] ?? null) === 'Kator Tarkaa') {
                    unset($layout[$i]['data']['members'][$m]['photo']);
                }
            }
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }
};
