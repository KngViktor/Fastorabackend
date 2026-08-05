<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Creates the consultation page and points the "Book a Consultation" buttons at
 * it.
 *
 * Those buttons went to /contact, which is the general enquiry form — a visitor
 * who wanted to book a session had to explain that in a free-text brief. The new
 * page asks for times directly.
 *
 * Deliberately not a slot-picker calendar. A picker has to be fed by availability
 * kept current by hand, and this app cannot see the calendar those sessions
 * actually live in, so it would happily offer a slot that is already taken —
 * double-booking a prospect is worse than asking for two or three preferred
 * times. If self-serve booking is wanted later, an embedded scheduling tool gives
 * real two-way calendar sync, which no amount of code here can.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = require database_path('data/consultation-page.php');

        // Idempotent: never a second copy, and never overwrites an edited page.
        if (DB::table('pages')->where('slug', $page['slug'])->exists()) {
            return;
        }

        DB::table('pages')->insert([
            'title' => $page['title'],
            'slug' => $page['slug'],
            'hero_type' => $page['hero_type'],
            'hero_eyebrow' => $page['hero_eyebrow'],
            'hero_rich_text' => $page['hero_rich_text'],
            'hero_links' => json_encode([]),
            'faqs' => json_encode([]),
            'layout' => json_encode($page['layout']),
            'status' => 'published',
            'published_at' => now(),
            'meta_title' => $page['meta_title'],
            'meta_description' => $page['meta_description'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->repointConsultationLinks();
    }

    /**
     * Sends every "Book a Consultation" button to the new page.
     *
     * Only rewrites buttons still pointing at /contact with that exact label, so
     * a link an editor has retargeted is left alone. The contact page keeps its
     * own general enquiry form.
     */
    private function repointConsultationLinks(): void
    {
        foreach (DB::table('pages')->get() as $row) {
            if ($row->slug === 'consultation') {
                continue;
            }

            $changed = false;

            $heroLinks = json_decode($row->hero_links ?? '[]', true);

            if (is_array($heroLinks)) {
                foreach ($heroLinks as $i => $link) {
                    if (($link['label'] ?? null) === 'Book a Consultation' && ($link['url'] ?? null) === '/contact') {
                        $heroLinks[$i]['url'] = '/consultation';
                        $changed = true;
                    }
                }
            }

            $layout = json_decode($row->layout ?? '[]', true);

            if (is_array($layout)) {
                foreach ($layout as $b => $block) {
                    foreach ($block['data']['links'] ?? [] as $i => $link) {
                        if (($link['label'] ?? null) === 'Book a Consultation' && ($link['url'] ?? null) === '/contact') {
                            $layout[$b]['data']['links'][$i]['url'] = '/consultation';
                            $changed = true;
                        }
                    }
                }
            }

            if (! $changed) {
                continue;
            }

            DB::table('pages')->where('id', $row->id)->update([
                'hero_links' => json_encode($heroLinks),
                'layout' => json_encode($layout),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('pages')->where('slug', 'consultation')->delete();
    }
};
