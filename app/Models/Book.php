<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = [
        'title',
        'title_en',
        'title_ur',
        'slug',
        'author_id',
        'category_id',
        'language',
        'language_en',
        'language_ur',
        'short_description',
        'short_description_en',
        'short_description_ur',
        'description',
        'description_en',
        'description_ur',
        'cover_image',
        'pdf_file',
        'pdf_file_en',
        'pdf_file_ur',
        'external_pdf_url',
        'external_pdf_url_en',
        'external_pdf_url_ur',
        'is_latest',
        'is_featured',
        'is_active',
        'download_allowed',
    ];

    protected function casts(): array
    {
        return [
            'is_latest' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'download_allowed' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function islamicEvents(): BelongsToMany
    {
        return $this->belongsToMany(IslamicEvent::class, 'book_event')
            ->withPivot('display_order')
            ->withTimestamps();
    }

    public function audios(): HasMany
    {
        return $this->hasMany(Audio::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->resolvePdfUrl($this->pdf_file, $this->external_pdf_url);
    }

    public function getLocalizedTitleAttribute(): string
    {
        return $this->localized('title') ?? '';
    }

    public function getLocalizedLanguageAttribute(): string
    {
        return $this->localized('language') ?? '';
    }

    public function getLocalizedShortDescriptionAttribute(): ?string
    {
        return $this->localized('short_description');
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }

    public function getLocalizedPdfUrlAttribute(): ?string
    {
        return $this->localizedPdfUrl();
    }

    public function localizedPdfPath(?string $locale = null): ?string
    {
        $source = $this->localizedPdfSource($locale);

        return ($source['type'] ?? null) === 'local' ? $source['value'] : null;
    }

    public function localizedExternalPdfUrl(?string $locale = null): ?string
    {
        foreach ($this->localizedPdfCandidates($locale) as [, $externalUrl]) {
            $url = $this->validExternalPdfUrl($externalUrl);

            if ($url) {
                return $url;
            }
        }

        return null;
    }

    public function localizedPdfUrl(?string $locale = null): ?string
    {
        $source = $this->localizedPdfSource($locale);

        return match ($source['type'] ?? null) {
            'local' => Storage::disk('public')->url($source['value']),
            'external' => $source['value'],
            default => null,
        };
    }

    public function localizedPdfSource(?string $locale = null): ?array
    {
        foreach ($this->localizedPdfCandidates($locale) as [$path, $externalUrl]) {
            if (filled($path)) {
                return ['type' => 'local', 'value' => $path];
            }

            $url = $this->validExternalPdfUrl($externalUrl);

            if ($url) {
                return ['type' => 'external', 'value' => $url];
            }
        }

        return null;
    }

    public function isUsingFallbackPdf(?string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ur'
            && blank($this->pdf_file_ur)
            && blank($this->validExternalPdfUrl($this->external_pdf_url_ur))
            && filled($this->localizedPdfUrl($locale));
    }

    private function resolvePdfUrl(?string $path, ?string $externalUrl): ?string
    {
        if (filled($path)) {
            return Storage::disk('public')->url($path);
        }

        return $this->validExternalPdfUrl($externalUrl);
    }

    private function validExternalPdfUrl(?string $externalUrl): ?string
    {
        if (blank($externalUrl)) {
            return null;
        }

        return filter_var($externalUrl, FILTER_VALIDATE_URL) ? $externalUrl : null;
    }

    private function localizedPdfCandidates(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ur'
            ? [
                [$this->pdf_file_ur, $this->external_pdf_url_ur],
                [$this->pdf_file_en, $this->external_pdf_url_en],
                [$this->pdf_file, $this->external_pdf_url],
            ]
            : [
                [$this->pdf_file_en, $this->external_pdf_url_en],
                [$this->pdf_file, $this->external_pdf_url],
                [$this->pdf_file_ur, $this->external_pdf_url_ur],
            ];
    }
}
