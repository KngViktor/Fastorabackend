<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the demo testimonials with real client reviews.
 *
 * The demo pair (Amaka Chukwu / Lumen Skincare, Daniel Osei / Northbound
 * Logistics) were placeholders I wrote, quoting results no engagement backs up.
 * They go entirely rather than being edited, since nothing in them is real.
 *
 * Guarded on those two names still being present: once an editor has curated this
 * list, it is theirs.
 */
return new class extends Migration
{
    private const DEMO_NAMES = ['Amaka Chukwu', 'Daniel Osei', 'Amara Chukwu', 'David Osei'];

    public function up(): void
    {
        $testimonials = require database_path('data/testimonials.php');

        $existing = DB::table('testimonials')->pluck('client_name', 'id');

        // Only act while the list is still the demo pair, or already empty.
        foreach ($existing as $name) {
            if (! in_array($name, self::DEMO_NAMES, true)) {
                return;
            }
        }

        $serviceIdBySlug = DB::table('services')->pluck('id', 'slug');

        // Clear the pivot first: the demo rows are about to go and the pivot has a
        // foreign key onto them.
        DB::table('service_testimonial')->whereIn('testimonial_id', $existing->keys())->delete();
        DB::table('testimonials')->whereIn('id', $existing->keys())->delete();

        foreach ($testimonials as $testimonial) {
            $id = DB::table('testimonials')->insertGetId([
                'client_name' => $testimonial['client_name'],
                'role' => $testimonial['role'],
                'company' => $testimonial['company'],
                'quote' => $testimonial['quote'],
                'rating' => $testimonial['rating'],
                'show_on_home' => $testimonial['show_on_home'],
                // No photographs in this repository. Uploading each person's own
                // photo in the admin is the fix; the logo is not a face.
                'avatar_media_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $serviceId = $serviceIdBySlug[$testimonial['service_slug']] ?? null;

            if ($serviceId !== null) {
                DB::table('service_testimonial')->insert([
                    'testimonial_id' => $id,
                    'service_id' => $serviceId,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Reinstating invented quotes with invented results would be the wrong
        // default on a live site.
    }
};
