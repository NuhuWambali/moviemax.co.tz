<?php
// app/Models/VisitorTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorTracking extends Model
{
    protected $table = 'visitor_tracking';
    
    protected $fillable = [
        'fingerprint',
        'session_id',
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'is_unique',
        'visit_count',
        'last_visit'
    ];
    
    protected $casts = [
        'last_visit' => 'datetime',
        'is_unique' => 'boolean',
        'visit_count' => 'integer'
    ];
    
    // Get online visitors (active in last 5 minutes)
    public static function getOnlineVisitors()
    {
        return self::where('last_visit', '>=', now()->subMinutes(5))->count();
    }
}