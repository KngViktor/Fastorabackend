<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Support\RevalidatesFrontend;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * One command to run after a deploy, so the sequence cannot be half-remembered.
 *
 * Seeding is the delicate part. The seeder uses updateOrCreate, so running it
 * against a live site would silently overwrite whatever the client had edited.
 * It therefore only runs when the site has no pages at all, which is true
 * exactly once: the first deploy. After that this command is safe to run on
 * every push.
 */
class DeployCommand extends Command
{
    protected $signature = 'app:deploy {--force-seed : Re-run the seeder even if content already exists. Overwrites edited content.}';

    protected $description = 'Run migrations, seed on first deploy only, and rebuild caches';

    public function handle(): int
    {
        $this->components->info('Running migrations');
        Artisan::call('migrate', ['--force' => true], $this->output);

        $hasContent = Page::query()->exists();

        if ($this->option('force-seed')) {
            $this->components->warn('Force-seeding. Existing content will be overwritten.');
            Artisan::call('db:seed', ['--force' => true], $this->output);
        } elseif (! $hasContent) {
            $this->components->info('No content found, seeding starter content');
            Artisan::call('db:seed', ['--force' => true], $this->output);
        } else {
            $this->components->info('Content already exists, skipping seed');
        }

        // Runs unconditionally, unlike seeding. The bundled photography cannot
        // reach the server through git (storage/app/public is gitignored) and
        // the seeder that writes it is skipped once content exists, which is
        // why every image on the live site was 404ing. This is a file copy with
        // no database writes, so it is safe on every push.
        $this->components->info('Syncing bundled media');
        Artisan::call('app:sync-media', [], $this->output);

        // Data migrations write with the query builder, which does not fire the
        // model observers that normally invalidate the API cache. Without this,
        // a migration that edits page content would be invisible behind a cached
        // response until the TTL expired or an editor happened to save something.
        $this->components->info('Clearing the API response cache');
        Artisan::call('cache:clear', [], $this->output);

        $this->components->info('Rebuilding caches');
        Artisan::call('config:cache', [], $this->output);
        Artisan::call('route:cache', [], $this->output);
        Artisan::call('view:cache', [], $this->output);

        // Harmless when the symlink already exists or the host disallows it;
        // the .htaccess maps /storage onto the real folder either way.
        Artisan::call('storage:link', [], $this->output);

        // Clearing this app's cache is only half the job. The frontend fetches
        // with cache: 'force-cache' and no revalidate window, so its own copy of
        // the content persists until something tells it otherwise. Normally an
        // editor's save does that through the observers, but a data migration
        // writes with the query builder and fires nothing — so content changed
        // by a migration would stay invisible on the live site indefinitely.
        //
        // Sweeping the whole site rather than named paths, because a deploy has
        // no idea which pages a migration touched.
        $this->components->info('Asking the frontend to revalidate');
        RevalidatesFrontend::revalidate(
            ['/', '/services', '/case-studies', '/insights', '/about', '/contact'],
            ['pages', 'services', 'case-studies', 'posts', 'site-settings'],
        );

        $this->newLine();
        $this->components->info('Deploy finished.');

        return self::SUCCESS;
    }
}
