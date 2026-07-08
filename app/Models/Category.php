<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = ['name', 'name_en', 'name_ur', 'slug', 'description', 'description_en', 'description_ur', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function audios(): HasMany
    {
        return $this->hasMany(Audio::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getLocalizedNameAttribute(): string
    {
        return $this->localized('name') ?? '';
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }
}
