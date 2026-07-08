<?php

namespace App\Support\Translations;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class BladeTranslationScanner
{
    public function missingMessageReferences(array $availableMessageKeys): array
    {
        $available = array_flip($availableMessageKeys);
        $missing = [];

        foreach ($this->bladeFiles() as $file) {
            $source = file_get_contents($file->getPathname());

            preg_match_all(
                "/(?:__|@lang|trans|trans_choice)\(\s*['\"]messages\.([^'\"]+)['\"]/u",
                $source,
                $matches,
                PREG_OFFSET_CAPTURE,
            );

            foreach ($matches[1] as [$key, $offset]) {
                if (! isset($available[$key])) {
                    $missing[] = $this->formatHit($file, $source, $offset, 'messages.'.$key);
                }
            }
        }

        return $missing;
    }

    public function hardcodedEnglishText(): array
    {
        $hits = [];

        foreach ($this->bladeFiles() as $file) {
            $source = file_get_contents($file->getPathname());
            $cleanSource = $this->removeIgnoredBlocks($source);

            preg_match_all('/<[^!][^>]*>([^<]*[A-Za-z][^<]*)</u', $cleanSource, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[1] as [$text, $offset]) {
                $visibleText = $this->normalizeVisibleText($text);

                if ($this->isHardcodedEnglish($visibleText)) {
                    $hits[] = $this->formatHit($file, $source, $offset, $visibleText);
                }
            }

            preg_match_all(
                '/\s(placeholder|title|aria-label|alt)\s*=\s*([\'"])(.*?)\2/isu',
                $cleanSource,
                $attributeMatches,
                PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
            );

            foreach ($attributeMatches as $match) {
                $attribute = $match[1][0];
                $value = $match[3][0];

                if (str_contains($value, '{{') || str_contains($value, '__(') || str_contains($value, '$')) {
                    continue;
                }

                $visibleText = $this->normalizeVisibleText($value);

                if ($this->isHardcodedEnglish($visibleText)) {
                    $hits[] = $this->formatHit($file, $source, $match[3][1], $attribute.'="'.$visibleText.'"');
                }
            }
        }

        return $hits;
    }

    /**
     * @return array<int, SplFileInfo>
     */
    private function bladeFiles(): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file;
            }
        }

        return $files;
    }

    private function removeIgnoredBlocks(string $source): string
    {
        $source = preg_replace('/{{--.*?--}}/us', '', $source) ?? $source;
        $source = preg_replace('/@php.*?@endphp/us', '', $source) ?? $source;
        $source = preg_replace('/\{\{.*?\}\}|\{!!.*?!!\}/us', '', $source) ?? $source;
        $source = preg_replace('/@\w+\([^\n]*\)/u', '', $source) ?? $source;
        $source = preg_replace('/@\w+\b/u', '', $source) ?? $source;
        $source = preg_replace('/<script\b[^>]*>.*?<\/script>/ius', '', $source) ?? $source;
        $source = preg_replace('/<style\b[^>]*>.*?<\/style>/ius', '', $source) ?? $source;

        return $source;
    }

    private function normalizeVisibleText(string $text): string
    {
        $text = preg_replace('/\{\{.*?\}\}|\{!!.*?!!\}/us', '', $text) ?? $text;
        $text = preg_replace('/@\w+(?:\s*\([^)]*\))?/u', '', $text) ?? $text;
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function isHardcodedEnglish(string $text): bool
    {
        if ($text === '') {
            return false;
        }

        foreach (['$', '->', '=>', '::', '__(', '[', ']', '@'] as $codeMarker) {
            if (str_contains($text, $codeMarker)) {
                return false;
            }
        }

        return preg_match('/[A-Za-z]{2,}/', $text) === 1;
    }

    private function formatHit(SplFileInfo $file, string $source, int $offset, string $text): string
    {
        $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
        $line = substr_count(substr($source, 0, $offset), "\n") + 1;

        return $relativePath.':'.$line.' '.$text;
    }
}
