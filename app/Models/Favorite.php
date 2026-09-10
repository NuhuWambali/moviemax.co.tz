<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'visitor_key',
        'favoritable_type',
        'favoritable_id',
    ];

    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }
}