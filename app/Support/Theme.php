<?php

namespace App\Support;

/**
 * Theme tokens used by the preview/show views.
 *
 * The LLM picks a palette + mood at generation time (see SalesPageGenerator
 * schema). This class validates the picked values and exposes helpers so the
 * views can compose Tailwind classes consistently.
 */
class Theme
{
    public const PALETTES = ['violet', 'emerald', 'rose', 'amber', 'sky', 'fuchsia', 'slate', 'indigo', 'teal'];

    public const MOODS = ['bold', 'minimal', 'elegant', 'playful'];

    public const PALETTE_HINTS = [
        'violet' => 'tech, SaaS, marketing, modern',
        'emerald' => 'wellness, eco, finance, growth, health',
        'rose' => 'beauty, lifestyle, feminine, fashion',
        'amber' => 'food, warmth, home, family, bakery',
        'sky' => 'education, kids, trust, clean tech',
        'fuchsia' => 'creative, entertainment, bold, art',
        'slate' => 'corporate, B2B, legal, luxury monochrome',
        'indigo' => 'enterprise, security, premium, finance',
        'teal' => 'wellness, modern tech, calm, health',
    ];

    public const MOOD_HINTS = [
        'bold' => 'urgent, energetic, sales-heavy, high-impact',
        'minimal' => 'professional, tech, clean, trust-building',
        'elegant' => 'premium, luxury, editorial, sophisticated',
        'playful' => 'kids, food, lifestyle, creative, fun',
    ];

    public static function palette(?string $value): string
    {
        return in_array($value, self::PALETTES, true) ? $value : 'violet';
    }

    public static function mood(?string $value): string
    {
        return in_array($value, self::MOODS, true) ? $value : 'bold';
    }

    /**
     * @return array<string,string>
     */
    public static function classes(string $palette): array
    {
        $p = self::palette($palette);

        return [
            'palette' => $p,
            'bg50' => "bg-{$p}-50",
            'bg100' => "bg-{$p}-100",
            'bg600' => "bg-{$p}-600",
            'bg700' => "bg-{$p}-700",
            'text600' => "text-{$p}-600",
            'text700' => "text-{$p}-700",
            'text100' => "text-{$p}-100",
            'border200' => "border-{$p}-200",
            'heroGradient' => "from-{$p}-600 via-{$p}-800 to-{$p}-900",
            'softGradient' => "from-{$p}-50 to-white",
            'ring300' => "ring-{$p}-300",
            'shadowRing' => "shadow-{$p}-300",
        ];
    }

    /**
     * Pre-generated list of every class used dynamically across themes.
     * Rendered in a hidden <div> in the layout so Tailwind CDN's runtime JIT
     * picks them up regardless of which theme the current page uses.
     *
     * @return string
     */
    public static function safelist(): string
    {
        $shades = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
        $prefixes = ['bg', 'text', 'border', 'from', 'via', 'to', 'ring'];
        $out = [];
        foreach (self::PALETTES as $p) {
            foreach ($prefixes as $pre) {
                foreach ($shades as $s) {
                    $out[] = "{$pre}-{$p}-{$s}";
                }
            }
        }

        return implode(' ', $out);
    }
}
