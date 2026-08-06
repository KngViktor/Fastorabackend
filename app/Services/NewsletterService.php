<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Forwards a newsletter signup to whichever provider is configured in Site
 * Settings → Newsletter. Every provider call is best-effort: the email is
 * already durably stored in newsletter_subscribers before this runs (see
 * NewsletterController), so a provider outage or bad credential loses the
 * integration for that signup, never the signup itself.
 */
class NewsletterService
{
    /**
     * @return bool Whether the configured provider accepted the subscriber.
     *              Returns false (not an exception) when no provider is set,
     *              so the caller can tell "nothing to sync" from "it failed".
     */
    public function subscribe(string $email): bool
    {
        $settings = SiteSetting::current();
        $provider = $settings->newsletter_provider;

        if (blank($provider)) {
            return false;
        }

        try {
            return match ($provider) {
                'mailchimp' => $this->mailchimp($settings, $email),
                'convertkit' => $this->convertkit($settings, $email),
                'brevo' => $this->brevo($settings, $email),
                'mailerlite' => $this->mailerlite($settings, $email),
                'resend' => $this->resendAudience($settings, $email),
                default => false,
            };
        } catch (Throwable $e) {
            Log::warning("Newsletter sync to {$provider} failed", ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Mailchimp's API is sharded by data centre, encoded as the suffix after
     * the dash in every API key (e.g. "...-us21") — there is no separate
     * "region" field to configure.
     */
    private function mailchimp(SiteSetting $settings, string $email): bool
    {
        $apiKey = (string) $settings->newsletter_api_key;
        $datacenter = substr($apiKey, strrpos($apiKey, '-') + 1);
        $subscriberHash = md5(strtolower($email));

        $response = Http::withBasicAuth('anystring', $apiKey)
            ->put("https://{$datacenter}.api.mailchimp.com/3.0/lists/{$settings->newsletter_list_id}/members/{$subscriberHash}", [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'status' => 'subscribed',
            ]);

        return $response->successful();
    }

    /** "List ID" here is a Kit (ConvertKit) form ID — the only thing its public subscribe endpoint accepts. */
    private function convertkit(SiteSetting $settings, string $email): bool
    {
        $response = Http::post("https://api.convertkit.com/v3/forms/{$settings->newsletter_list_id}/subscribe", [
            'api_key' => $settings->newsletter_api_key,
            'email' => $email,
        ]);

        return $response->successful();
    }

    private function brevo(SiteSetting $settings, string $email): bool
    {
        $response = Http::withHeaders(['api-key' => $settings->newsletter_api_key])
            ->post('https://api.brevo.com/v3/contacts', [
                'email' => $email,
                'listIds' => [(int) $settings->newsletter_list_id],
                'updateEnabled' => true,
            ]);

        return $response->successful();
    }

    /** "List ID" here is a MailerLite group ID. */
    private function mailerlite(SiteSetting $settings, string $email): bool
    {
        $response = Http::withToken($settings->newsletter_api_key)
            ->post('https://connect.mailerlite.com/api/subscribers', [
                'email' => $email,
                'groups' => [$settings->newsletter_list_id],
            ]);

        return $response->successful();
    }

    /** "List ID" here is a Resend audience ID — Resend's own signup-list concept, separate from transactional mail. */
    private function resendAudience(SiteSetting $settings, string $email): bool
    {
        $response = Http::withToken($settings->newsletter_api_key)
            ->post("https://api.resend.com/audiences/{$settings->newsletter_list_id}/contacts", [
                'email' => $email,
                'unsubscribed' => false,
            ]);

        return $response->successful();
    }
}
