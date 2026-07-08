<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'category_id',
        'language',
        'short_description',
        'description',
        'cover_image',
        'pdf_file',
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
}
