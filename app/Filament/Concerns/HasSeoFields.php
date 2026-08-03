<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;

/**
 * The SEO controls shared by Pages, Services, Case Studies and Posts, so the
 * four resources present one consistent tab instead of drifting apart.
 *
 * The length limits are Google's practical display cut-offs rather than hard
 * technical limits: titles are truncated around 60 characters and descriptions
 * around 160, so the counters tell an editor when their text will be clipped
 * in results.
 */
trait HasSeoFields
{
    /** @return array<Component> */
    protected static function seoFields(): array
    {
        return [
            TextInput::make('meta_title')
                ->label('Search title')
                ->maxLength(70)
                ->live(onBlur: true)
                ->helperText(fn (?string $state): string => static::lengthHint($state, 60, 'Falls back to the item title when empty.'))
                ->columnSpanFull(),

            Textarea::make('meta_description')
                ->label('Search description')
                ->rows(3)
                ->maxLength(200)
                ->live(onBlur: true)
                ->helperText(fn (?string $state): string => static::lengthHint($state, 160, 'The summary shown under the title in search results.'))
                ->columnSpanFull(),

            static::mediaSelect('metaImage', 'Share image')
                ->helperText('Used when the page is shared on social media. 1200x630 works best.'),

            TextInput::make('meta_canonical_url')
                ->label('Canonical URL')
                ->url()
                ->placeholder('https://fastora.africa/the-real-page')
                ->helperText('Only needed if this content also lives at another address. Leave empty otherwise.'),

            Toggle::make('meta_noindex')
                ->label('Hide from search engines')
                ->helperText('The page stays reachable by anyone with the link, but asks Google and AI crawlers not to list it.'),
        ];
    }

    /** Turns a field's current value into a "42 / 60 characters" style hint. */
    protected static function lengthHint(?string $state, int $ideal, string $context): string
    {
        $length = mb_strlen(trim((string) $state));

        if ($length === 0) {
            return $context;
        }

        return $length > $ideal
            ? "{$length} / {$ideal} characters. Over the limit, search engines will cut this short."
            : "{$length} / {$ideal} characters.";
    }
}
