<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Applies the admin's SMTP settings (Site Settings → Email) over
 * config/mail.php at boot, so every outgoing mail — contact/consultation
 * notifications, password resets — uses whatever an admin configured from
 * the dashboard instead of requiring a developer to edit MAIL_* in .env.
 *
 * A blank `mail_host` means the tab was never touched, so config/mail.php's
 * own env-driven defaults are left completely alone.
 */
class MailSettings
{
    public static function apply(): void
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return;
            }

            $settings = SiteSetting::query()->first();

            if (! $settings || blank($settings->mail_host)) {
                return;
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $settings->mail_host,
                'mail.mailers.smtp.port' => $settings->mail_port ?: 587,
                'mail.mailers.smtp.username' => $settings->mail_username,
                'mail.mailers.smtp.password' => $settings->mail_password,
                'mail.mailers.smtp.encryption' => $settings->mail_encryption ?: null,
                'mail.from.address' => $settings->mail_from_address ?: config('mail.from.address'),
                'mail.from.name' => $settings->mail_from_name ?: config('mail.from.name'),
            ]);
        } catch (Throwable $e) {
            // A misconfigured DB connection must never break the request that
            // happened to trigger this — it should just fall back to whatever
            // config/mail.php already has, the same as before this existed.
            report($e);
        }
    }

    /** Where form-submission notifications go: the dedicated field, or the public contact email. */
    public static function notificationRecipient(): ?string
    {
        try {
            $settings = Schema::hasTable('site_settings') ? SiteSetting::query()->first() : null;

            return $settings?->notification_email ?: $settings?->contact_email ?: null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
