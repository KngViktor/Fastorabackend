<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aligns three services' card summaries, and one service's `includes` order,
 * with the client's content document. The document's wording is close to what
 * was already live but not identical, and `digital-marketing`'s three
 * sub-services were listed in a different order.
 *
 * Guarded on the exact old text/order, so a summary or list an editor has
 * since rewritten is left alone rather than being overwritten back to a
 * version that is no longer current.
 */
return new class extends Migration
{
    private const SUMMARIES = [
        'communications-strategy' => [
            'from' => 'The way your business communicates influences how people understand it.',
            'to' => 'The way your business communicates shapes how people see it. We help you get that right.',
        ],
        'brand-positioning' => [
            'from' => 'Help people recognise what your business stands for and remember why it matters.',
            'to' => 'People remember brands that stand for something. We help you define what that is.',
        ],
        'content-and-storytelling' => [
            'from' => 'Every piece of content should strengthen the story your business is trying to tell.',
            'to' => 'Content is often the first conversation people have with your business. We help make it count.',
        ],
    ];

    private const DIGITAL_MARKETING_INCLUDES = [
        'from' => ['Social Media Management', 'Marketing Strategy', 'Digital Marketing'],
        'to' => ['Social Media Management', 'Digital Marketing', 'Marketing Strategy'],
    ];

    public function up(): void
    {
        $this->applySummaries(fn (array $pair) => $pair['from'], fn (array $pair) => $pair['to']);
        $this->applyIncludesOrder(self::DIGITAL_MARKETING_INCLUDES['from'], self::DIGITAL_MARKETING_INCLUDES['to']);
    }

    public function down(): void
    {
        $this->applySummaries(fn (array $pair) => $pair['to'], fn (array $pair) => $pair['from']);
        $this->applyIncludesOrder(self::DIGITAL_MARKETING_INCLUDES['to'], self::DIGITAL_MARKETING_INCLUDES['from']);
    }

    private function applySummaries(callable $from, callable $to): void
    {
        foreach (self::SUMMARIES as $slug => $pair) {
            DB::table('services')
                ->where('slug', $slug)
                ->where('summary', $from($pair))
                ->update(['summary' => $to($pair), 'updated_at' => now()]);
        }
    }

    private function applyIncludesOrder(array $from, array $to): void
    {
        $service = DB::table('services')->where('slug', 'digital-marketing')->first();

        if ($service === null) {
            return;
        }

        $current = json_decode($service->includes ?? '[]', true);
        $currentLabels = array_column($current ?? [], 'label');

        if ($currentLabels !== $from) {
            return;
        }

        DB::table('services')->where('id', $service->id)->update([
            'includes' => json_encode(array_map(fn (string $label) => ['label' => $label], $to)),
            'updated_at' => now(),
        ]);
    }
};
