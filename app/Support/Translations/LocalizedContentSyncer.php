<?php

namespace App\Support\Translations;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocalizedContentSyncer
{
    private const TABLES = [
        'categories' => ['name', 'description'],
        'authors' => ['name', 'bio'],
        'books' => ['title', 'language', 'short_description', 'description'],
        'islamic_events' => ['title', 'description'],
        'audios' => ['title', 'speaker', 'description'],
    ];

    public function __construct(private readonly MachineTranslator $translator) {}

    public function sync(bool $apply = false, ?int $limit = null): array
    {
        $changes = [];

        foreach (self::TABLES as $table => $fields) {
            $query = DB::table($table)->orderBy('id');

            if ($limit !== null) {
                $query->limit($limit);
            }

            foreach ($query->get() as $row) {
                $rowChanges = [];

                foreach ($fields as $field) {
                    $pairChanges = $this->syncField(
                        table: $table,
                        rowLabel: $this->rowLabel($row),
                        field: $field,
                        legacy: $row->{$field} ?? null,
                        english: $row->{$field.'_en'} ?? null,
                        urdu: $row->{$field.'_ur'} ?? null,
                        apply: $apply,
                    );

                    $rowChanges = array_merge($rowChanges, $pairChanges);
                    $changes = array_merge($changes, $this->formatChanges($table, $this->rowLabel($row), $field, $pairChanges));
                }

                if ($apply && $rowChanges !== []) {
                    $rowChanges['updated_at'] = now();
                    DB::table($table)->where('id', $row->id)->update($rowChanges);
                }
            }
        }

        $changes = array_merge($changes, $this->syncSettings($apply));

        return $changes;
    }

    private function syncSettings(bool $apply): array
    {
        $changes = [];
        $pairs = Setting::pairs();

        foreach (Setting::LOCALIZED_KEYS as $key) {
            $pairChanges = $this->syncField(
                table: 'settings',
                rowLabel: $key,
                field: $key,
                legacy: $pairs[$key] ?? null,
                english: $pairs[$key.'_en'] ?? null,
                urdu: $pairs[$key.'_ur'] ?? null,
                apply: $apply,
            );

            foreach ($pairChanges as $column => $value) {
                $settingKey = match ($column) {
                    $key => $key,
                    $key.'_en' => $key.'_en',
                    $key.'_ur' => $key.'_ur',
                    default => $column,
                };

                if ($apply) {
                    DB::table('settings')->updateOrInsert(
                        ['key' => $settingKey],
                        ['value' => $value, 'updated_at' => now(), 'created_at' => now()],
                    );
                }
            }

            $changes = array_merge($changes, $this->formatChanges('settings', $key, $key, $pairChanges));
        }

        return $changes;
    }

    private function syncField(
        string $table,
        string $rowLabel,
        string $field,
        ?string $legacy,
        ?string $english,
        ?string $urdu,
        bool $apply,
    ): array {
        $legacy = $this->clean($legacy);
        $english = $this->clean($english);
        $urdu = $this->clean($urdu);

        $changes = [];

        if ($this->hasUrduScript($english) && (! filled($urdu) || $this->same($english, $urdu))) {
            $sourceUrdu = filled($urdu) ? $urdu : $english;
            $changes[$field.'_ur'] = $sourceUrdu;
            $changes[$field.'_en'] = $this->translateOrPreview($sourceUrdu, 'en', 'ur', $apply);
            $changes[$field] = $changes[$field.'_en'];

            return $this->withoutUnchanged($changes, $legacy, $english, $urdu, $field);
        }

        if (! filled($english) && filled($urdu)) {
            $changes[$field.'_en'] = $this->translateOrPreview($urdu, 'en', 'ur', $apply);
            $changes[$field] = $changes[$field.'_en'];

            return $this->withoutUnchanged($changes, $legacy, $english, $urdu, $field);
        }

        if (! filled($english) && filled($legacy)) {
            if ($this->hasUrduScript($legacy)) {
                $changes[$field.'_ur'] = filled($urdu) ? $urdu : $legacy;
                $changes[$field.'_en'] = $this->translateOrPreview($legacy, 'en', 'ur', $apply);
                $changes[$field] = $changes[$field.'_en'];
            } else {
                $changes[$field.'_en'] = $legacy;

                if (! filled($urdu) || $this->same($legacy, $urdu)) {
                    $changes[$field.'_ur'] = $this->translateOrPreview($legacy, 'ur', 'en', $apply);
                }
            }

            return $this->withoutUnchanged($changes, $legacy, $english, $urdu, $field);
        }

        if (filled($english) && (! filled($urdu) || $this->same($english, $urdu) || ! $this->hasUrduScript($urdu))) {
            $changes[$field.'_ur'] = $this->translateOrPreview($english, 'ur', 'en', $apply);
        }

        if (filled($english) && (! filled($legacy) || $this->hasUrduScript($legacy))) {
            $changes[$field] = $english;
        }

        return $this->withoutUnchanged($changes, $legacy, $english, $urdu, $field);
    }

    private function translateOrPreview(string $text, string $targetLocale, string $sourceLocale, bool $apply): string
    {
        if (! $apply) {
            return '[translate '.$sourceLocale.'->'.$targetLocale.'] '.$text;
        }

        return $this->translator->translate($text, $targetLocale, $sourceLocale);
    }

    private function withoutUnchanged(array $changes, ?string $legacy, ?string $english, ?string $urdu, string $field): array
    {
        $current = [
            $field => $legacy,
            $field.'_en' => $english,
            $field.'_ur' => $urdu,
        ];

        return array_filter(
            $changes,
            fn (?string $value, string $column): bool => ! $this->same($value, $current[$column] ?? null),
            ARRAY_FILTER_USE_BOTH,
        );
    }

    private function formatChanges(string $table, string $rowLabel, string $field, array $changes): array
    {
        return array_map(
            fn (string $column, ?string $value): array => [
                'table' => $table,
                'row' => $rowLabel,
                'field' => $field,
                'column' => $column,
                'value' => Str::limit((string) $value, 120),
            ],
            array_keys($changes),
            $changes,
        );
    }

    private function clean(?string $value): ?string
    {
        $value = $value === null ? null : trim($value);

        return $value === '' ? null : $value;
    }

    private function same(?string $first, ?string $second): bool
    {
        return $this->clean($first) === $this->clean($second);
    }

    private function hasUrduScript(?string $value): bool
    {
        return filled($value) && preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}]/u', $value) === 1;
    }

    private function rowLabel(object $row): string
    {
        return (string) ($row->slug ?? $row->key ?? $row->id);
    }
}
