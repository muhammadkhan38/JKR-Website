<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class IslamicEvent extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = [
        'title',
        'title_en',
        'title_ur',
        'slug',
        'description',
        'description_en',
        'description_ur',
        'banner_image',
        'start_date',
        'end_date',
        'is_active',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_event')
            ->withPivot('display_order')
            ->withTimestamps()
            ->orderByPivot('display_order');
    }

    public function audios(): HasMany
    {
        return $this->hasMany(Audio::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function (Builder $dates) use ($today): void {
                $dates->where(function (Builder $empty): void {
                    $empty->whereNull('start_date')->whereNull('end_date');
                })->orWhere(function (Builder $range) use ($today): void {
                    $range->where(function (Builder $start) use ($today): void {
                        $start->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
                    })->where(function (Builder $end) use ($today): void {
                        $end->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
                    });
                });
            });
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image ? Storage::disk('public')->url($this->banner_image) : null;
    }

    public function getLocalizedTitleAttribute(): string
    {
        return $this->localized('title') ?? '';
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }
}
