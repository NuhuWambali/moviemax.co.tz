<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    protected $fillable = [
        'user_id',
        'visitor_key',
        'reactable_type',
        'reactable_id',
        'reaction',
    ];

    protected $casts = [
        'reaction' => 'string',
    ];

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}