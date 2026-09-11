<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Movie extends Model
{
    protected $table = 'movies';
    
    protected static function booted(): void
    {
        static::saving(function (Movie $movie) {
            if (!$movie->slug && $movie->title && trim((string) $movie->title) !== '') {
                $movie->slug = self::uniqueSlug($movie->title, $movie->id);
            }
        });
    }

    private static function uniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'movie';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . ($i++);
        }
        return $slug;
    }
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'genre',
        'release_year',
        'duration',
        'language',
        'rating',
        'views',
        'poster_path',
        'file_path',
        'file_size',
        'trailer_url',
        'is_active',
        'download_count',
        'type',
        'season',
        'episode',
        'episode_title',
        'series_id',
        'episode_number',
        'season_number'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'release_year' => 'integer',
        'episode_number' => 'integer',
        'season_number' => 'integer'
    ];

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return null;
        }
        
        if (filter_var($this->poster_path, FILTER_VALIDATE_URL)) {
            return $this->poster_path;
        }
        
        return asset('storage/' . $this->poster_path);
    }

    /**
     * Playable/downloadable video URL. Cloudinary embed-player links are
     * automatically converted to a direct video file so watch/download work.
     */
    public function getVideoUrlAttribute()
    {
        $path = trim((string) $this->file_path);
        if ($path === '') {
            return null;
        }

        if (preg_match('#^https?://player\.cloudinary\.com/embed/\?#i', $path)) {
            $query = [];
            parse_str((string) parse_url($path, PHP_URL_QUERY), $query);
            $cloud = $query['cloud_name'] ?? null;
            $publicId = $query['public_id'] ?? null;

            if ($cloud && $publicId) {
                $direct = 'https://res.cloudinary.com/' . $cloud . '/video/upload/' . $publicId . '.mp4';
                if (filter_var($direct, FILTER_VALIDATE_URL)) {
                    $path = $direct;
                }
            }
        }

        return $path;
    }

    public function getIsExternalVideoAttribute()
    {
        return preg_match('#^https?://#i', (string) $this->video_url) === 1;
    }

    public function isSeries()
    {
        return $this->type === 'series';
    }

    public function isEpisode()
    {
        return $this->type === 'episode';
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likesCount(): int
    {
        return $this->reactions()->where('reaction', 'like')->count();
    }

    public function dislikesCount(): int
    {
        return $this->reactions()->where('reaction', 'dislike')->count();
    }
}