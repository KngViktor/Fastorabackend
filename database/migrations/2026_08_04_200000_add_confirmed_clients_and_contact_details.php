<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fills in the confirmed client list and the confirmed contact details.
 *
 * The Trusted By block was seeded deliberately empty, because inventing client
 * logos would have implied clients the company did not have. Six are now
 * confirmed, so they go in — as names, since no logo files exist yet.
 *
 * Contact details replace seeded placeholders: the phone number was
 * +234 800 000 0000, which is obviously fake, and the location was "Lagos,
 * Nigeria" against the confirmed "Nigeria · Remote · Africa".
 *
 * The contact email is deliberately NOT touched here. The brief gives
 * hello@fastora.com, but there was an explicit earlier instruction to use
 * workwith@fastora.africa, and the two differ by top-level domain on a site that
 * runs on fastora.africa — which looks more like a typo in the brief than a
 * decision. Getting a contact address wrong costs real enquiries, so it stays as
 * instructed until confirmed.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->addClientsToHomePage();
        $this->updateContactDetails();
    }

    private function addClientsToHomePage(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if ($page === null) {
            return;
        }

        $layout = json_decode($page->layout ?? '[]', true);

        if (! is_array($layout)) {
            return;
        }

        $clients = require database_path('data/confirmed-clients.php');
        $changed = false;

        foreach ($layout as $i => $block) {
            if (($block['type'] ?? null) !== 'trustedBy') {
                continue;
            }

            // Only fill an empty list. Once an editor has added or reordered
            // clients, or attached logos, that is the source of truth.
            if (! empty($block['data']['logos'])) {
                continue;
            }

            $layout[$i]['data']['logos'] = $clients;
            $changed = true;
        }

        if (! $changed) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'layout' => json_encode($layout),
            'updated_at' => now(),
        ]);
    }

    private function updateContactDetails(): void
    {
        $settings = DB::table('site_settings')->first();

        if ($settings === null) {
            return;
        }

        $update = [];

        // Guarded on the placeholder value, so a real number entered in the admin
        // is never overwritten.
        if ($settings->contact_phone === '+234 800 000 0000') {
            $update['contact_phone'] = '+234 703 814 7969';
        }

        if ($settings->address === 'Lagos, Nigeria') {
            $update['address'] = 'Nigeria · Remote · Africa';
        }

        $social = json_decode($settings->social_links ?? '[]', true);

        if (is_array($social)) {
            $hasWhatsapp = false;

            foreach ($social as $link) {
                if (($link['platform'] ?? null) === 'whatsapp') {
                    $hasWhatsapp = true;

                    break;
                }
            }

            if (! $hasWhatsapp) {
                // WhatsApp is the primary channel, so it leads the list. wa.me
                // wants the number without spaces, plus, or leading zero.
                array_unshift($social, [
                    'platform' => 'whatsapp',
                    'url' => 'https://wa.me/2347038147969',
                ]);

                $update['social_links'] = json_encode($social);
            }
        }

        if ($update === []) {
            return;
        }

        $update['updated_at'] = now();

        DB::table('site_settings')->where('id', $settings->id)->update($update);
    }

    public function down(): void
    {
        // Restoring a fake phone number and an empty client list would be the
        // wrong default on a live site.
    }
};
