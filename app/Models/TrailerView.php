<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailerView extends Model
{
    public $timestamps = false;

    protected $fillable = ['trailer_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function trailer(): BelongsTo
    {
        return $this->belongsTo(Trailer::class);
    }
}