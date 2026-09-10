<?php

namespace App\Http\Controllers;

use App\Models\WatchHistory;
use Illuminate\Http\Request;

class WatchProgressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'watchable_type' => 'required|in:App\\Models\\Movie',
            'watchable_id'   => 'required|integer',
            'progress'       => 'required|numeric|min:0',
            'duration'       => 'nullable|numeric|min:0',
        ]);

        WatchHistory::updateOrCreate(
            [
                'user_id'         => $request->user()->id,
                'watchable_type'  => $data['watchable_type'],
                'watchable_id'    => $data['watchable_id'],
            ],
            [
                'watched_at'       => now(),
                'progress_seconds' => (int) $data['progress'],
                'duration_seconds' => (int) ceil($data['duration'] ?? 0),
            ]
        );

        return response()->json(['ok' => true]);
    }
}