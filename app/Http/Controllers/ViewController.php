<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Series;
use App\Models\Trailer;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Count a single view per visitor (session-guarded) for a movie,
     * series or trailer. Called from the player JS after ~5s of playback.
     */
    public function track(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:movie,series,trailer',
            'id'   => 'required|integer|min:1',
        ]);

        $type = $data['type'];
        $id   = (int) $data['id'];

        $sessionKey = 'viewed_' . $type . '_' . $id;
        if ($request->session()->has($sessionKey)) {
            return response()->json(['ok' => false, 'already' => true]);
        }

        if ($type === 'movie') {
            $model = Movie::where('id', $id)->where('is_active', true)->first();
        } elseif ($type === 'series') {
            $model = Series::where('id', $id)->where('is_active', true)->first();
        } else {
            $model = Trailer::where('id', $id)->where('is_active', true)->first();
        }

        if (!$model) {
            return response()->json(['ok' => false]);
        }

        $model->increment('views');
        $request->session()->put($sessionKey, true);

        return response()->json(['ok' => true]);
    }
}