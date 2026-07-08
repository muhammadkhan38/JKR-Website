<?php

namespace App\Console\Commands;

use App\Support\Translations\BladeTranslationScanner;
use App\Support\Translations\MachineTranslator;
use App\Support\Translations\TranslationCatalog;
use Illuminate\Console\Command;
use Throwable;

class SyncTranslations extends Command
{
    protected $signature = 'translations:sync
        {--source=en : Source locale catalog}
        {--locale=ur : Target locale catalog}
        {--translate : Machine-translate missing target strings}
        {--check : Report problems without writing files}
        {--text= : Translate one English text value and print the target translation}
        {--key= : Save --text to this messages.php dot key in both catalogs}';

    protected $description = 'Sync English and Urdu message catalogs, scan Blade views, and optionally machine-translate missing text.';

    public function handle(
        TranslationCatalog $catalog,
        BladeTranslationScanner $scanner,
        MachineTranslator $translator,
    ): int {
        $sourceLocale = (string) $this->option('source');
        $targetLocale = (string) $this->option('locale');
        $text = $this->option('text');

        if ($text !== null) {
            return $this->handleTextTranslation($catalog, $translator, $sourceLocale, $targetLocale, (string) $text);
        }

        return $this->handleCatalogSync($catalog, $scanner, $translator, $sourceLocale, $targetLocale);
    }

    private function handleTextTranslation(
        TranslationCatalog $catalog,
        MachineTranslator $translator,
        string $sourceLocale,
        string $targetLocale,
        string $text,
    ): int {
        try {
            $translated = $translator->translate($text, $targetLocale, $sourceLocale);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->line($translated);

        $key = $this->option('key');

        if ($key !== null) {
            if ($this->option('check')) {
                $this->error('The --key option writes files and cannot be used with --check.');

                return self::FAILURE;
            }

            $sourceCatalog = $catalog->load($sourceLocale);
            $targetCatalog = $catalog->load($targetLocale);
            $catalog->set($sourceCatalog, (string) $key, $text);
            $catalog->set($targetCatalog, (string) $key, $translated);
            $catalog->write($sourceLocale, $sourceCatalog);
            $catalog->write($targetLocale, $targetCatalog);

            $this->info('Saved messages.'.(string) $key.' to '.$sourceLocale.' and '.$targetLocale.'.');
        }

        return self::SUCCESS;
    }

    private function handleCatalogSync(
        TranslationCatalog $catalog,
        BladeTranslationScanner $scanner,
        MachineTranslator $translator,
        string $sourceLocale,
        string $targetLocale,
    ): int {
        $sourceCatalog = $catalog->load($sourceLocale);
        $targetCatalog = $catalog->load($targetLocale);
        $sourceFlat = $catalog->flatten($sourceCatalog);
        $targetFlat = $catalog->flatten($targetCatalog);
        $missing = $catalog->missingKeys($sourceCatalog, $targetCatalog);
        $blank = $catalog->blankKeys($targetCatalog);
        $extra = $catalog->extraKeys($sourceCatalog, $targetCatalog);
        $availableInBoth = array_values(array_intersect(array_keys($sourceFlat), array_keys($targetFlat)));
        $missingViewReferences = $scanner->missingMessageReferences($availableInBoth);
        $hardcodedText = $scanner->hardcodedEnglishText();

        if ($this->option('check')) {
            return $this->reportCheckResults($missing, $blank, $extra, $missingViewReferences, $hardcodedText);
        }

        $keysToFill = array_values(array_unique(array_merge($missing, $blank)));

        foreach ($keysToFill as $key) {
            $value = (string) ($sourceFlat[$key] ?? '');

            if ($this->option('translate')) {
                try {
                    $value = $translator->translate($value, $targetLocale, $sourceLocale);
                } catch (Throwable $exception) {
                    $this->error('Could not translate messages.'.$key.': '.$exception->getMessage());

                    return self::FAILURE;
                }
            }

            $catalog->set($targetCatalog, $key, $value);
        }

        if ($keysToFill !== []) {
            $catalog->write($targetLocale, $targetCatalog);
            $this->info('Filled '.count($keysToFill).' '.$targetLocale.' message key(s).');
        } else {
            $this->info('No missing '.$targetLocale.' message keys found.');
        }

        if ($extra !== []) {
            $this->warn('Extra '.$targetLocale.' keys:');
            $this->line(implode(PHP_EOL, array_map(fn (string $key): string => 'messages.'.$key, $extra)));
        }

        if ($missingViewReferences !== [] || $hardcodedText !== []) {
            $this->warn('Run php artisan translations:sync --check to review Blade translation issues.');
        }

        return self::SUCCESS;
    }

    private function reportCheckResults(
        array $missing,
        array $blank,
        array $extra,
        array $missingViewReferences,
        array $hardcodedText,
    ): int {
        $failed = false;

        if ($missing !== []) {
            $failed = true;
            $this->error('Missing target keys:');
            $this->line(implode(PHP_EOL, array_map(fn (string $key): string => 'messages.'.$key, $missing)));
        }

        if ($blank !== []) {
            $failed = true;
            $this->error('Blank target translations:');
            $this->line(implode(PHP_EOL, array_map(fn (string $key): string => 'messages.'.$key, $blank)));
        }

        if ($missingViewReferences !== []) {
            $failed = true;
            $this->error('Blade views reference missing message keys:');
            $this->line(implode(PHP_EOL, $missingViewReferences));
        }

        if ($hardcodedText !== []) {
            $failed = true;
            $this->error('Blade views contain hardcoded English text:');
            $this->line(implode(PHP_EOL, $hardcodedText));
        }

        if ($extra !== []) {
            $this->warn('Extra target keys:');
            $this->line(implode(PHP_EOL, array_map(fn (string $key): string => 'messages.'.$key, $extra)));
        }

        if (! $failed) {
            $this->info('Translations are in sync and Blade views are covered.');
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
