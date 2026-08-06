<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Downloads a remote image and stores it on the public disk, returning a
 * path shaped exactly like FileUpload's return value — so a URL an editor
 * pastes is treated identically, downstream, to a file picked from their
 * computer (Media::booted() derives filename/mime/dimensions from it the
 * same way either way).
 *
 * Stored rather than linked directly: a URL that works today can 404 or
 * change tomorrow, and every consumer of Media->url expects a file this app
 * actually controls, not a hotlink to someone else's server.
 */
class MediaDownloader
{
    private const MAX_BYTES = 8 * 1024 * 1024;

    public static function downloadToPublicDisk(string $url): string
    {
        $response = Http::timeout(15)->get($url);

        if (! $response->successful()) {
            throw new RuntimeException("Could not download that URL (HTTP {$response->status()}).");
        }

        $contentType = strtok((string) $response->header('Content-Type'), ';');
        $extension = self::extensionFromContentType($contentType) ?? self::extensionFromUrl($url);

        if (! $extension) {
            throw new RuntimeException("That URL didn't return a recognisable image (got \"{$contentType}\").");
        }

        if (strlen($response->body()) > self::MAX_BYTES) {
            throw new RuntimeException('That image is larger than the 8 MB limit.');
        }

        $path = 'media/' . Str::random(20) . '.' . $extension;

        Storage::disk('public')->put($path, $response->body());

        return $path;
    }

    private static function extensionFromContentType(string $contentType): ?string
    {
        return match ($contentType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            default => null,
        };
    }

    private static function extensionFromUrl(string $url): ?string
    {
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true) ? $extension : null;
    }
}
