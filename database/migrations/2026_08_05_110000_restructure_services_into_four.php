<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Restructures the ten services into the four the content document defines.
 *
 * The old names are not discarded — each becomes an "includes" item under the
 * parent it now belongs to, so the offering is unchanged in substance while the
 * site presents four things a business can buy rather than ten overlapping ones.
 *
 * Three tables carry foreign keys into services (case_studies.related_service_id,
 * inquiries.service_needed_id, service_testimonial.service_id), so every row is
 * remapped onto its new parent before any old service is removed. Deleting first
 * would either break the constraint or silently null out which service a case
 * study or an enquiry was about.
 *
 * digital-marketing keeps its id and slug, since it exists in both structures.
 * The other nine slugs disappear as pages, so the frontend redirects them to
 * their new parent — see next.config.ts. Without that, nine live URLs would
 * start 404ing.
 */
return new class extends Migration
{
    /** Old slug => the parent it now sits under. */
    private const REPARENT = [
        'strategic-communications' => 'communications-strategy',
        'communication-advisory' => 'communications-strategy',
        'reputation-management' => 'communications-strategy',
        'brand-consulting' => 'brand-positioning',
        'founder-branding' => 'brand-positioning',
        'content-strategy' => 'content-and-storytelling',
        'copywriting' => 'content-and-storytelling',
        'social-media-management' => 'digital-marketing',
        'marketing-strategy' => 'digital-marketing',
    ];

    public function up(): void
    {
        $services = require database_path('data/services.php');

        // Already restructured, or reworked by an editor. Either way, leave it.
        if (DB::table('services')->where('slug', 'communications-strategy')->exists()) {
            return;
        }

        $imagery = $this->existingImagery();

        foreach ($services as $service) {
            $this->upsert($service, $imagery);
        }

        $this->remapReferences();
        $this->removeRetiredServices();
    }

    /**
     * Photographs already attached to the old services, so the four inherit real
     * imagery rather than coming up with empty image slots.
     *
     * @return array{icon: ?int, featured: array<int, ?int>}
     */
    private function existingImagery(): array
    {
        return [
            'icon' => DB::table('services')->whereNotNull('icon_media_id')->value('icon_media_id'),
            'featured' => DB::table('services')
                ->whereNotNull('featured_image_media_id')
                ->orderBy('order')
                ->pluck('featured_image_media_id')
                ->all(),
        ];
    }

    private function upsert(array $service, array $imagery): void
    {
        $position = $service['order'] - 1;

        $row = [
            'title' => $service['title'],
            'summary' => $service['summary'],
            'overview_heading' => $service['overview_heading'],
            'overview_copy' => $service['overview_copy'],
            'problem' => $service['problem'],
            'approach' => $service['approach'],
            'outcomes' => json_encode($service['outcomes']),
            'deliverables' => json_encode($service['deliverables']),
            'good_fit_if' => json_encode($service['good_fit_if']),
            'includes' => json_encode($service['includes']),
            'related_service_slugs' => json_encode($service['related_service_slugs']),
            'cta_heading' => $service['cta_heading'],
            'cta_copy' => $service['cta_copy'],
            'faqs' => json_encode($service['faqs']),
            'order' => $service['order'],
            'featured_on_home' => $service['featured_on_home'],
            'status' => 'published',
            'meta_title' => $service['meta_title'],
            'meta_description' => $service['meta_description'],
            'updated_at' => now(),
        ];

        $existing = DB::table('services')->where('slug', $service['slug'])->first();

        if ($existing !== null) {
            DB::table('services')->where('id', $existing->id)->update($row);

            return;
        }

        DB::table('services')->insert($row + [
            'slug' => $service['slug'],
            'icon_media_id' => $imagery['icon'],
            'featured_image_media_id' => $imagery['featured'][$position] ?? ($imagery['featured'][0] ?? null),
            'published_at' => now(),
            'created_at' => now(),
        ]);
    }

    /**
     * Points every row that referenced a retired service at its new parent.
     */
    private function remapReferences(): void
    {
        $idBySlug = DB::table('services')->pluck('id', 'slug');

        foreach (self::REPARENT as $oldSlug => $newSlug) {
            $oldId = $idBySlug[$oldSlug] ?? null;
            $newId = $idBySlug[$newSlug] ?? null;

            if ($oldId === null || $newId === null || $oldId === $newId) {
                continue;
            }

            DB::table('case_studies')->where('related_service_id', $oldId)->update(['related_service_id' => $newId]);
            DB::table('inquiries')->where('service_needed_id', $oldId)->update(['service_needed_id' => $newId]);

            // The pivot is unique per pair, so a testimonial already linked to the
            // parent would collide. Drop the duplicate rather than repoint it.
            $alreadyLinked = DB::table('service_testimonial')
                ->where('service_id', $newId)
                ->pluck('testimonial_id')
                ->all();

            DB::table('service_testimonial')
                ->where('service_id', $oldId)
                ->whereIn('testimonial_id', $alreadyLinked)
                ->delete();

            DB::table('service_testimonial')->where('service_id', $oldId)->update(['service_id' => $newId]);
        }
    }

    private function removeRetiredServices(): void
    {
        DB::table('services')->whereIn('slug', array_keys(self::REPARENT))->delete();
    }

    public function down(): void
    {
        // The ten cannot be rebuilt from the four: their individual copy is gone
        // from the document this came from, and the references have been remapped.
        // Restoring the schema without that copy would leave nine empty pages.
    }
};
