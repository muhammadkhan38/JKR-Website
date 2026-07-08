<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = ['name', 'name_en', 'name_ur', 'slug', 'bio', 'bio_en', 'bio_ur'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function getLocalizedNameAttribute(): string
    {
        return $this->localized('name') ?? '';
    }

    public function getLocalizedBioAttribute(): ?string
    {
        return $this->localized('bio');
    }
}
