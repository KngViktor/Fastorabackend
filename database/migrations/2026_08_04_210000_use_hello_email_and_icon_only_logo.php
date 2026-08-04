<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Two confirmed changes.
 *
 * The contact address becomes hello@fastora.africa. This settles the conflict
 * flagged last time: the brief said hello@fastora.com while the standing
 * instruction was workwith@fastora.africa, and the answer keeps the .africa
 * domain the site actually runs on.
 *
 * The admin login moves with it, since that account was set to the contact
 * address on request. The password is untouched — renaming the row preserves the
 * existing credential, so the same password works at the new address.
 *
 * The logo becomes the mark alone, without the "Fastora" wordmark. The header
 * shows the logo next to the site name already, so the wordmark repeated it.
 * The blue icon is cropped from logo-color.png, because the icon-only files that
 * already existed are charcoal and white and would have lost the brand colour on
 * a white header.
 */
return new class extends Migration
{
    private const OLD_EMAIL = 'workwith@fastora.africa';

    private const NEW_EMAIL = 'hello@fastora.africa';

    public function up(): void
    {
        $this->updateEmail();
        $this->useIconOnlyLogo();
    }

    private function updateEmail(): void
    {
        DB::table('site_settings')
            ->where('contact_email', self::OLD_EMAIL)
            ->update(['contact_email' => self::NEW_EMAIL, 'updated_at' => now()]);

        // Only rename the admin account when the new address is free, so this can
        // never collide with the unique index on users.email.
        $taken = DB::table('users')->where('email', self::NEW_EMAIL)->exists();

        if (! $taken) {
            DB::table('users')
                ->where('email', self::OLD_EMAIL)
                ->update(['email' => self::NEW_EMAIL, 'updated_at' => now()]);
        }
    }

    private function useIconOnlyLogo(): void
    {
        $settings = DB::table('site_settings')->first();

        if ($settings === null) {
            return;
        }

        // Blue on light backgrounds, white on the navy footer. The blue mark on
        // navy is legible but muddy, and there is already a white cut of the icon.
        $blue = $this->registerBrandIcon('icon-color.png', 'Fastora icon');
        $white = $this->registerBrandIcon('icon-white.png', 'Fastora icon, white');

        if ($blue === null) {
            return;
        }

        DB::table('site_settings')->where('id', $settings->id)->update([
            'logo_light_media_id' => $blue,
            'logo_dark_media_id' => $white ?? $blue,
            'favicon_media_id' => $blue,
            'updated_at' => now(),
        ]);
    }

    /**
     * Puts a bundled brand icon on the public disk and returns its media id.
     *
     * The file is copied here rather than left to app:sync-media because deploy
     * order is migrate-then-sync: on this deploy it is not on disk yet, and
     * pointing the settings at an image that 404s until the next deploy would
     * reintroduce exactly the broken-image problem this project already had once.
     */
    private function registerBrandIcon(string $filename, string $alt): ?int
    {
        $path = 'seed/' . $filename;
        $source = database_path('seeders/images/' . $filename);

        if (! Storage::disk('public')->exists($path)) {
            if (! is_file($source)) {
                return null;
            }

            Storage::disk('public')->put($path, file_get_contents($source));
        }

        $existing = DB::table('media')->where('path', $path)->where('disk', 'public')->first();

        if ($existing !== null) {
            return (int) $existing->id;
        }

        $dimensions = @getimagesize(Storage::disk('public')->path($path));

        return (int) DB::table('media')->insertGetId([
            'path' => $path,
            'disk' => 'public',
            'filename' => $filename,
            'mime_type' => 'image/png',
            'size' => Storage::disk('public')->size($path),
            'alt' => $alt,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('contact_email', self::NEW_EMAIL)
            ->update(['contact_email' => self::OLD_EMAIL, 'updated_at' => now()]);

        if (! DB::table('users')->where('email', self::OLD_EMAIL)->exists()) {
            DB::table('users')
                ->where('email', self::NEW_EMAIL)
                ->update(['email' => self::OLD_EMAIL, 'updated_at' => now()]);
        }

        // The logo is left as the icon. Reinstating the wordmark version would
        // undo the change this migration exists to make.
    }
};
