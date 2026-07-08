<?php

namespace App\Support\Translations;

class TranslationCatalog
{
    public function path(string $locale): string
    {
        return lang_path($locale.'/messages.php');
    }

    public function load(string $locale): array
    {
        $path = $this->path($locale);

        return file_exists($path) ? require $path : [];
    }

    public function flatten(array $catalog, string $prefix = ''): array
    {
        $flat = [];

        foreach ($catalog as $key => $value) {
            $dotKey = $prefix === '' ? (string) $key : $prefix.'.'.$key;

            if (is_array($value)) {
                $flat += $this->flatten($value, $dotKey);
            } else {
                $flat[$dotKey] = $value;
            }
        }

        return $flat;
    }

    public function set(array &$catalog, string $dotKey, string $value): void
    {
        $segments = explode('.', $dotKey);
        $target = &$catalog;

        foreach ($segments as $segment) {
            if (! isset($target[$segment]) || ! is_array($target[$segment])) {
                $target[$segment] = [];
            }

            $target = &$target[$segment];
        }

        $target = $value;
    }

    public function missingKeys(array $sourceCatalog, array $targetCatalog): array
    {
        return array_values(array_diff(
            array_keys($this->flatten($sourceCatalog)),
            array_keys($this->flatten($targetCatalog)),
        ));
    }

    public function extraKeys(array $sourceCatalog, array $targetCatalog): array
    {
        return array_values(array_diff(
            array_keys($this->flatten($targetCatalog)),
            array_keys($this->flatten($sourceCatalog)),
        ));
    }

    public function blankKeys(array $targetCatalog): array
    {
        return array_keys(array_filter(
            $this->flatten($targetCatalog),
            fn ($value): bool => trim((string) $value) === '',
        ));
    }

    public function write(string $locale, array $catalog): void
    {
        $path = $this->path($locale);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, "<?php\n\nreturn ".$this->exportArray($catalog).";\n");
    }

    private function exportArray(array $array, int $level = 0): string
    {
        $indent = str_repeat('    ', $level);
        $childIndent = str_repeat('    ', $level + 1);
        $lines = ['['];

        foreach ($array as $key => $value) {
            $exportedKey = var_export($key, true);
            $exportedValue = is_array($value)
                ? $this->exportArray($value, $level + 1)
                : var_export($value, true);

            $lines[] = $childIndent.$exportedKey.' => '.$exportedValue.',';
        }

        $lines[] = $indent.']';

        return implode("\n", $lines);
    }
}
