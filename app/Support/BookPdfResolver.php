<?php

namespace App\Support;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class BookPdfResolver
{
    public function resolve(Book $book, ?string $locale = null): ?array
    {
        $source = $book->localizedPdfSource($locale);

        if (! $source) {
            return null;
        }

        $fileName = $this->downloadFileName($book);

        if (($source['type'] ?? null) === 'local') {
            $path = $source['value'] ?? null;

            if (blank($path) || ! Storage::disk('public')->exists($path)) {
                return null;
            }

            return [
                'type' => 'local',
                'path' => $path,
                'original_url' => Storage::disk('public')->url($path),
                'preview_url' => Storage::disk('public')->url($path),
                'download_url' => Storage::disk('public')->url($path),
                'file_name' => $fileName,
                'is_google_drive' => false,
            ];
        }

        if (($source['type'] ?? null) !== 'external') {
            return null;
        }

        $url = $source['value'] ?? null;

        if (! GoogleDrivePdf::isSupportedPdfUrl($url)) {
            return null;
        }

        $fileId = GoogleDrivePdf::fileId($url);

        if ($fileId) {
            return [
                'type' => 'google_drive',
                'file_id' => $fileId,
                'original_url' => $url,
                'preview_url' => GoogleDrivePdf::previewUrl($fileId),
                'download_url' => GoogleDrivePdf::downloadUrl($fileId),
                'file_name' => $fileName,
                'is_google_drive' => true,
            ];
        }

        return [
            'type' => 'external',
            'original_url' => $url,
            'preview_url' => $url,
            'download_url' => $url,
            'file_name' => $fileName,
            'is_google_drive' => false,
        ];
    }

    public function downloadFileName(Book $book): string
    {
        $title = Str::of($book->localized_title ?: $book->title ?: $book->slug)
            ->stripTags()
            ->squish()
            ->toString();

        $name = Str::slug($title) ?: $book->slug ?: 'book';

        return $name.'.pdf';
    }
}
