<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Notifies the Next.js frontend that content changed, so it can revalidate
 * its cached ISR pages — the cross-app equivalent of Payload's afterChange
 * hooks calling revalidatePath()/revalidateTag() directly (which Laravel
 * can't do, since it's a separate app). See Fastora's
 * src/app/(frontend)/api/revalidate/route.ts for the receiving end.
 *
 * Best-effort: a failed or unreachable frontend must never block a save or
 * delete in the admin, so failures are logged, not thrown.
 */
class RevalidatesFrontend
{
    /**
     * @param  string[]  $paths
     * @param  string[]  $tags
     */
    public static function revalidate(array $paths = [], array $tags = []): void
    {
        // Drop the API response cache first, and unconditionally. Every observer
        // already funnels through here on save and delete, which makes this the
        // one place that catches all of them.
        //
        // It has to run before the early returns below: if the frontend URL or
        // token is unset, the notification is skipped, but this app would still
        // be serving its own stale JSON. Flushing regardless means a save is
        // always reflected in the API, whether or not the frontend hears about it.
        ApiCache::flush();

        if (empty($paths) && empty($tags)) {
            return;
        }

        $frontendUrl = config('services.frontend.url');
        $token = config('services.frontend.token');

        if (! $frontendUrl || ! $token) {
            return;
        }

        try {
            Http::withToken($token)
                ->timeout(5)
                ->post(rtrim($frontendUrl, '/') . '/api/revalidate', [
                    'paths' => $paths,
                    'tags' => $tags,
                ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to notify frontend of content change', [
                'paths' => $paths,
                'tags' => $tags,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
