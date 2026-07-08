<?php

namespace App\Support;

class LocalizedColumns
{
    public static function searchColumns(string $field): array
    {
        $locale = app()->getLocale();

        return array_values(array_unique([
            $field.'_'.$locale,
            $field.'_en',
            $field.'_ur',
            $field,
        ]));
    }

    public static function orderExpression(string $field, ?string $table = null): string
    {
        $locale = app()->getLocale();
        $prefix = $table ? $table.'.' : '';

        $columns = array_values(array_unique([
            $prefix.$field.'_'.$locale,
            $prefix.$field.'_en',
            $prefix.$field,
            $prefix.$field.'_ur',
        ]));

        return 'COALESCE('.implode(', ', $columns).')';
    }
}
