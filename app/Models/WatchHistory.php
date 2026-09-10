<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WatchHistory extends Model
{
    protected $table = 'watch_history';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'watchable_type',
        'watchable_id',
        'watched_at',
        'progress_seconds',
        'duration_seconds',
    ];

    protected $casts = [
        'watched_at' => 'datetime',
        'progress_seconds' => 'integer',
        'duration_seconds' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function watchable(): MorphTo
    {
        return $this->morphTo();
    }
}