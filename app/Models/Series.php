<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $fillable = [
        'title',
        'description',
        'genre',
        'release_year',
        'language',
        'rating',
        'poster_path',
        'backdrop_path',
        'trailer_url',
        'seasons_count',
        'is_active'
    ];

    public function episodes()
    {
        return $this->hasMany(Movie::class, 'series_id')->where('type', 'episode');
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