<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const LOCALIZED_KEYS = [
        'madrasa_name',
        'address',
        'short_about',
        'footer_text',
    ];

    public static function valueFor(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function pairs(): array
    {
        return static::query()->pluck('value', 'key')->all();
    }

    public static function localizedPairs(?string $locale = null): array
    {
        $pairs = static::pairs();

        foreach (self::LOCALIZED_KEYS as $key) {
            $pairs[$key] = static::localizedValueFromPairs($pairs, $key, $pairs[$key] ?? null, $locale);
        }

        return $pairs;
    }

    public static function localizedValueFor(string $key, ?string $default = null, ?string $locale = null): ?string
    {
        return static::localizedValueFromPairs(static::pairs(), $key, $default, $locale);
    }

    public static function localizedValueFromPairs(array $pairs, string $key, ?string $default = null, ?string $locale = null): ?string
    {
        $locale = in_array($locale ?? app()->getLocale(), ['en', 'ur'], true) ? ($locale ?? app()->getLocale()) : 'en';
        $fallbackLocale = config('app.fallback_locale', 'en');

        foreach (array_unique([$locale, $fallbackLocale, 'en', 'ur']) as $candidate) {
            $value = $pairs[$key.'_'.$candidate] ?? null;

            if (filled($value)) {
                return $value;
            }
        }

        return filled($pairs[$key] ?? null) ? $pairs[$key] : $default;
    }
}
