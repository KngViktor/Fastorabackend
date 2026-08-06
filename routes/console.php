<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backstop for the gap app:sync-media itself documents: storage/app/public is
// gitignored, so a redeploy that resets the working tree (or a host that
// never persisted it in the first place) silently drops every bundled seed
// image with no error anywhere in the deploy. Running this on a schedule
// means the host only needs one cron line (`* * * * * php artisan
// schedule:run`) for images to heal themselves instead of staying broken
// until someone happens to SSH in and notice.
Schedule::command('app:sync-media')->daily();
