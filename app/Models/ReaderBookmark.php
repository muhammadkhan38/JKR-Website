<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReaderBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'book_id',
        'page_number',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'page_number' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
