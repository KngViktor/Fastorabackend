<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Installs the four real client case studies and removes the two demo ones.
 *
 * Lumen Skincare and Northbound Logistics were invented for the reference build
 * and the migration that restored them said outright that they should go before
 * the site was promoted. These four replace them: Biografrica, Energia, Unity
 * Key Group and Naturals by Jelique, from the client's content documents.
 *
 * The demo rows are deleted rather than rewritten in place. The earlier
 * migration rewrote rows to keep their ids valid, but nothing points at a case
 * study id — no enquiry, no media, no other table — and there are four studies
 * replacing two, so matching them up by position would be arbitrary.
 *
 * Images travel in this repository under database/seeders/images and are
 * imported the same way the seeder imports them, so app:sync-media restores them
 * on deploy like every other seeded asset.
 *
 * Guarded by slug. Re-running updates the four rows rather than duplicating
 * them, and a case study an editor has since written is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        $studies = require database_path('data/reference-case-studies.php');

        // A fresh database runs migrations before the seeder, so there is no
        // media library to attach a cover image to yet and nothing to replace.
        // The seeder creates these four itself from the same file.
        if (! DB::table('media')->exists()) {
            return;
        }

        $serviceId = fn (string $slug) => DB::table('services')->where('slug', $slug)->value('id');

        foreach ($studies as $study) {
            $cover = $this->importImage($study['cover'][0], $study['cover'][1]);

            $gallery = [];
            foreach ($study['gallery'] as [$filename, $caption]) {
                $gallery[] = [
                    'media_id' => $this->importImage($filename, $caption)->id,
                    'caption' => $caption,
                ];
            }

            $attributes = [
                'title' => $study['title'],
                'summary' => $study['summary'],
                'hero_intro' => $study['hero_intro'],
                'client_name' => $study['client_name'],
                'industry' => $study['industry'],
                'location' => $study['location'],
                'engagement' => $study['engagement'],
                'service_labels' => json_encode($study['service_labels']),
                'cover_image_media_id' => $cover->id,
                'gallery' => json_encode($gallery),
                'order' => $study['order'],
                'featured_on_home' => $study['featured_on_home'],
                // The facts block lists services as text; the linked block below
                // uses slugs. related_service_id is the older single-service
                // link, kept pointing at the first of them so existing filters
                // by service keep working.
                'related_service_id' => $serviceId($study['related_service_slugs'][0]),
                'the_business' => $study['the_business'],
                'what_we_noticed' => $study['what_we_noticed'],
                'our_thinking' => $study['our_thinking'],
                'what_we_did' => $study['what_we_did'],
                'results_heading' => $study['results_heading'] ?? null,
                'results_intro' => $study['results_intro'] ?? null,
                'results' => json_encode($study['results']),
                'results_note' => $study['results_note'] ?? null,
                'results_placement' => $study['results_placement'] ?? null,
                'testimonial_quote' => $study['testimonial_quote'] ?? null,
                'testimonial_author' => $study['testimonial_author'] ?? null,
                'testimonial_role' => $study['testimonial_role'] ?? null,
                'standout_heading' => $study['standout_heading'] ?? null,
                'standout_copy' => $study['standout_copy'] ?? null,
                'takeaway_heading' => $study['takeaway_heading'] ?? null,
                'takeaway_copy' => $study['takeaway_copy'] ?? null,
                'related_service_slugs' => json_encode($study['related_service_slugs']),
                'cta_heading' => $study['cta_heading'],
                'cta_copy' => $study['cta_copy'],
                'cta_label' => 'Book a Conversation',
                'meta_title' => $study['client_name'],
                'meta_description' => $study['meta_description'],
                'status' => 'published',
                'updated_at' => now(),
            ];

            if (DB::table('case_studies')->where('slug', $study['slug'])->exists()) {
                DB::table('case_studies')->where('slug', $study['slug'])->update($attributes);

                continue;
            }

            DB::table('case_studies')->insert($attributes + [
                'slug' => $study['slug'],
                'published_at' => now(),
                'created_at' => now(),
            ]);
        }

        DB::table('case_studies')
            ->whereIn('slug', [
                'lumen-skincare-content-strategy',
                'northbound-logistics-marketing-strategy',
            ])
            ->delete();
    }

    /**
     * Copies a seeder image onto the public disk and registers it in the media
     * library. Mirrors DatabaseSeeder::importImage — the migration cannot call
     * it, and duplicating a dozen lines is better than making the seeder's
     * protected helper public just for this.
     */
    private function importImage(string $filename, string $alt): object
    {
        $source = database_path('seeders/images/' . $filename);
        $path = 'seed/' . $filename;

        if (is_file($source) && ! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }

        $existing = DB::table('media')->where('path', $path)->where('disk', 'public')->first();

        if ($existing !== null) {
            return $existing;
        }

        $dimensions = Storage::disk('public')->exists($path)
            ? @getimagesize(Storage::disk('public')->path($path))
            : false;

        $id = DB::table('media')->insertGetId([
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'mime_type' => str_ends_with(strtolower($filename), '.png') ? 'image/png' : 'image/jpeg',
            'size' => Storage::disk('public')->exists($path) ? Storage::disk('public')->size($path) : 0,
            'alt' => $alt,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('media')->where('id', $id)->first();
    }

    public function down(): void
    {
        DB::table('case_studies')
            ->whereIn('slug', array_column(require database_path('data/reference-case-studies.php'), 'slug'))
            ->delete();
    }
};
