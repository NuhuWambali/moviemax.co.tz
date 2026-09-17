<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenreFollow extends Model
{
    protected $fillable = [
        'user_id',
        'genre',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function isFollowing(int $userId, string $genre): bool
    {
        return static::where('user_id', $userId)->where('genre', $genre)->exists();
    }
}