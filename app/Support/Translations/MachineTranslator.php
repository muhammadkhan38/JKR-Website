<?php

namespace App\Support\Translations;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MachineTranslator
{
    public function translate(string $text, string $targetLocale = 'ur', string $sourceLocale = 'en'): string
    {
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        [$maskedText, $placeholders] = $this->maskPlaceholders($text);

        $response = Http::timeout(15)->get('https://translate.googleapis.com/translate_a/single', [
            'client' => 'gtx',
            'sl' => $sourceLocale,
            'tl' => $targetLocale,
            'dt' => 't',
            'q' => $maskedText,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Translation request failed with status '.$response->status().'.');
        }

        $payload = $response->json();
        $translated = '';

        foreach (($payload[0] ?? []) as $segment) {
            $translated .= $segment[0] ?? '';
        }

        if ($translated === '') {
            throw new RuntimeException('Translation response did not contain translated text.');
        }

        return $this->restorePlaceholders($translated, $placeholders);
    }

    private function maskPlaceholders(string $text): array
    {
        $placeholders = [];

        $masked = preg_replace_callback(
            '/:[A-Za-z_][A-Za-z0-9_]*|\{[A-Za-z_][A-Za-z0-9_]*\}/',
            function (array $match) use (&$placeholders): string {
                $token = 'codexplaceholder'.count($placeholders);
                $placeholders[$token] = $match[0];

                return $token;
            },
            $text,
        );

        return [$masked ?? $text, $placeholders];
    }

    private function restorePlaceholders(string $text, array $placeholders): string
    {
        foreach ($placeholders as $token => $placeholder) {
            $text = str_replace($token, $placeholder, $text);
        }

        return $text;
    }
}
