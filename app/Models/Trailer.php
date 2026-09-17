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
        'trailer_type',
        'duration',
        'release_date',
        'year',
        'genre',
        'language',
        'country',
        'featured',
        'trending',
        'trailer_of_the_day',
    ];

    protected $casts = [
        'views' => 'integer',
        'is_active' => 'boolean',
        'duration' => 'integer',
        'release_date' => 'date',
        'year' => 'integer',
        'featured' => 'boolean',
        'trending' => 'boolean',
        'trailer_of_the_day' => 'boolean',
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

    public function viewsLog(): HasMany
    {
        return $this->hasMany(TrailerView::class);
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

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->source_type === 'file' && $this->file_path) {
            return null;
        }
        return $this->youtube_id ? 'https://www.youtube-nocookie.com/embed/' . $this->youtube_id : null;
    }

    public function getDurationLabelAttribute(): ?string
    {
        if (!$this->duration || $this->duration < 1) return null;
        $m = intdiv($this->duration, 60);
        $s = $this->duration % 60;
        return $m > 0 ? sprintf('%dm %02ds', $m, $s) : sprintf('%ds', $s);
    }

    public function getYearLabelAttribute(): ?int
    {
        return $this->year
            ?? ($this->release_date?->year)
            ?? $this->created_at?->year;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return (bool) $this->release_date?->isFuture();
    }

    public function getGenreSlugAttribute(): ?string
    {
        return $this->genre ? Str::slug($this->genre) : null;
    }

    public function getTypeDisplayAttribute(): string
    {
        return $this->trailer_type ?: 'Official Trailer';
    }

    public function toDiscoveryCard(): array
    {
        return [
            'type'         => 'trailer',
            'id'           => $this->id,
            'slug'         => $this->slug,
            'title'        => $this->title,
            'trailer_type' => $this->type_display,
            'genre'        => $this->genre,
            'year'         => $this->year_label,
            'duration'     => $this->duration_label,
            'language'     => $this->language,
            'country'      => $this->country,
            'views'        => $this->views,
            'poster'       => $this->thumb_url,
            'url'          => route('trailers.show', $this->slug),
        ];
    }
}