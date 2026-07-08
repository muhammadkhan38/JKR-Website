<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $table = 'audios';

    protected $fillable = [
        'book_id',
        'category_id',
        'islamic_event_id',
        'title',
        'title_en',
        'title_ur',
        'slug',
        'speaker',
        'speaker_en',
        'speaker_ur',
        'description',
        'description_en',
        'description_ur',
        'audio_file',
        'duration',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function islamicEvent(): BelongsTo
    {
        return $this->belongsTo(IslamicEvent::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_file ? Storage::disk('public')->url($this->audio_file) : null;
    }

    public function getLocalizedTitleAttribute(): string
    {
        return $this->localized('title') ?? '';
    }

    public function getLocalizedSpeakerAttribute(): ?string
    {
        return $this->localized('speaker');
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }
}
