<?php

namespace App\Support;

final class GoogleDrivePdf
{
    public static function fileId(?string $url): ?string
    {
        if (blank($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (! self::isGoogleDriveHost($host)) {
            return null;
        }

        $path = $parts['path'] ?? '';

        if (preg_match('~/file/d/([^/]+)~', $path, $matches)) {
            return self::cleanFileId($matches[1]);
        }

        parse_str($parts['query'] ?? '', $query);

        foreach (['id', 'file_id', 'fileId'] as $key) {
            if (! empty($query[$key]) && is_string($query[$key])) {
                return self::cleanFileId($query[$key]);
            }
        }

        return null;
    }

    public static function previewUrl(string $fileId): string
    {
        return 'https://drive.google.com/file/d/'.rawurlencode($fileId).'/preview';
    }

    public static function downloadUrl(string $fileId): string
    {
        return 'https://drive.google.com/uc?export=download&id='.rawurlencode($fileId);
    }

    public static function isSupportedPdfUrl(?string $url): bool
    {
        if (blank($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        if (self::fileId($url)) {
            return true;
        }

        $path = strtolower(rawurldecode(parse_url($url, PHP_URL_PATH) ?? ''));

        return str_ends_with($path, '.pdf');
    }

    private static function isGoogleDriveHost(string $host): bool
    {
        return $host === 'drive.google.com'
            || str_ends_with($host, '.drive.google.com')
            || $host === 'docs.google.com'
            || str_ends_with($host, '.docs.google.com');
    }

    private static function cleanFileId(string $fileId): ?string
    {
        $fileId = trim(rawurldecode($fileId));

        return preg_match('/^[A-Za-z0-9_-]+$/', $fileId) === 1 ? $fileId : null;
    }
}
