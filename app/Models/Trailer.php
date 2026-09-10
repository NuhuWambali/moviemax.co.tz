<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Trailer extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'trailer_url',
        'file_path',
        'source_type',
        'views',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'views' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Trailer $trailer) {
            if (!$trailer->slug && $trailer->title && trim((string) $trailer->title) !== '') {
                $trailer->slug = self::uniqueSlug($trailer->title, $trailer->id);
            }
        });
    }

    private static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'trailer';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . ($i++);
        }
        return $slug;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function watches(): HasMany
    {
        return $this->hasMany(TrailerWatch::class);
    }

    public function getYouTubeIdAttribute(): ?string
    {
        if (!$this->trailer_url) return null;
        preg_match('/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $this->trailer_url, $m);
        return $m[1] ?? null;
    }

    public function likesCount(): int
    {
        return $this->reactions()->where('reaction', 'like')->count();
    }

    public function dislikesCount(): int
    {
        return $this->reactions()->where('reaction', 'dislike')->count();
    }

    public function getThumbUrlAttribute(): string
    {
        if (!$this->thumbnail) return '/images/posters/dummy-trailer.png';
        return str_starts_with($this->thumbnail, '/') || str_starts_with($this->thumbnail, 'http')
            ? $this->thumbnail
            : '/storage/' . $this->thumbnail;
    }
}