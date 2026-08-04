<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Copies the bundled seed photography into the public disk.
 *
 * This exists because of a gap that left every image on the live site broken.
 * The seeder writes these files, but `app:deploy` only seeds when the database
 * has no pages — true exactly once. The photography was added to the seeder
 * *after* the first deploy had already populated the database, so the seeder
 * never ran again and the files were never written. Meanwhile storage/app/public
 * carries a `*` .gitignore, so they could not arrive by git either. The result
 * was media rows pointing at seed/… paths that returned 404.
 *
 * Splitting the file copy out of the seeder fixes that permanently: this is
 * pure filesystem work with no database writes, so unlike seeding it is safe to
 * run on every deploy and cannot overwrite anything an editor has changed.
 *
 * Existing files are left alone, so a photo replaced through the admin panel is
 * never clobbered by the bundled original.
 */
class SyncMediaCommand extends Command
{
    protected $signature = 'app:sync-media {--force : Overwrite files that already exist on the public disk.}';

    protected $description = 'Copy bundled seed images onto the public disk, skipping any that are already there';

    public function handle(): int
    {
        $sourceDir = database_path('seeders/images');

        if (! is_dir($sourceDir)) {
            $this->components->error("No source directory at {$sourceDir}");

            return self::FAILURE;
        }

        $files = glob($sourceDir . '/*.{png,jpg,jpeg,webp,gif,svg}', GLOB_BRACE) ?: [];

        if ($files === []) {
            $this->components->warn('No images found to sync.');

            return self::SUCCESS;
        }

        $disk = Storage::disk('public');
        $copied = $skipped = $failed = 0;

        foreach ($files as $source) {
            $target = 'seed/' . basename($source);

            if ($disk->exists($target) && ! $this->option('force')) {
                $skipped++;

                continue;
            }

            $contents = @file_get_contents($source);

            if ($contents === false) {
                $this->components->error("Could not read {$source}");
                $failed++;

                continue;
            }

            if ($disk->put($target, $contents)) {
                $this->line("  copied  {$target}");
                $copied++;
            } else {
                $this->components->error("Could not write {$target}");
                $failed++;
            }
        }

        $this->components->info("Media sync: {$copied} copied, {$skipped} already present, {$failed} failed.");

        // A failure here means broken images on the live site, so surface it as
        // a non-zero exit rather than letting the deploy look clean.
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
