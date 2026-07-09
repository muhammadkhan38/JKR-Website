<?php

use App\Support\GoogleDrivePdf;

it('extracts file ids from common google drive urls', function (string $url, string $fileId): void {
    expect(GoogleDrivePdf::fileId($url))->toBe($fileId);
})->with([
    'file route' => ['https://drive.google.com/file/d/abc_123-XYZ/view?usp=sharing', 'abc_123-XYZ'],
    'open id' => ['https://drive.google.com/open?id=abc_123-XYZ', 'abc_123-XYZ'],
    'uc id' => ['https://drive.google.com/uc?id=abc_123-XYZ&export=download', 'abc_123-XYZ'],
]);

it('generates google drive preview and download urls', function (): void {
    expect(GoogleDrivePdf::previewUrl('abc_123-XYZ'))->toBe('https://drive.google.com/file/d/abc_123-XYZ/preview')
        ->and(GoogleDrivePdf::downloadUrl('abc_123-XYZ'))->toBe('https://drive.google.com/uc?export=download&id=abc_123-XYZ');
});

it('rejects unsupported external urls', function (): void {
    expect(GoogleDrivePdf::isSupportedPdfUrl('https://example.com/not-a-pdf'))->toBeFalse()
        ->and(GoogleDrivePdf::isSupportedPdfUrl('https://example.com/book.pdf'))->toBeTrue();
});
