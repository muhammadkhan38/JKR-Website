<?php

namespace App\Models\Concerns;

trait HasLocalizedFields
{
    protected function localized(string $field, ?string $locale = null): ?string
    {
        $locale = $this->normalizeLocale($locale ?? app()->getLocale());
        $fallbackLocale = $this->normalizeLocale(config('app.fallback_locale', 'en'));

        $candidates = array_unique([$locale, $fallbackLocale, 'en', 'ur']);

        foreach ($candidates as $candidate) {
            $value = $this->getAttribute($field.'_'.$candidate);

            if (filled($value)) {
                return $value;
            }
        }

        $legacyValue = $this->getAttribute($field);

        return filled($legacyValue) ? $legacyValue : null;
    }

    protected function normalizeLocale(string $locale): string
    {
        return in_array($locale, array_keys(config('app.supported_locales', ['en' => 'English'])), true)
            ? $locale
            : config('app.fallback_locale', 'en');
    }
}
