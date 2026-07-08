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
        return $this->pdf_file ? Storage::disk('public')->url($this->pdf_file) : null;
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
        $path = $this->localizedPdfPath();

        return $path ? Storage::disk('public')->url($path) : null;
    }

    public function localizedPdfPath(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $paths = $locale === 'ur'
            ? [$this->pdf_file_ur, $this->pdf_file_en, $this->pdf_file]
            : [$this->pdf_file_en, $this->pdf_file, $this->pdf_file_ur];

        foreach ($paths as $path) {
            if (filled($path)) {
                return $path;
            }
        }

        return null;
    }

    public function isUsingFallbackPdf(?string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();

        return $locale === 'ur'
            && blank($this->pdf_file_ur)
            && filled($this->localizedPdfPath($locale));
    }
}
