<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use App\Models\TrailerView;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Count a single view per visitor (session-guarded) for a trailer.
     * Called from the player JS after ~5s of playback.
     */
    public function track(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:trailer',
            'id'   => 'required|integer|min:1',
        ]);

        $sessionKey = 'viewed_trailer_' . $data['id'];
        if ($request->session()->has($sessionKey)) {
            return response()->json(['ok' => false, 'already' => true]);
        }

        $trailer = Trailer::where('id', $data['id'])->where('is_active', true)->first();

        if (!$trailer) {
            return response()->json(['ok' => false]);
        }

        $trailer->increment('views');
        $request->session()->put($sessionKey, true);

        TrailerView::create([
            'trailer_id' => $trailer->id,
            'viewed_at'  => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}