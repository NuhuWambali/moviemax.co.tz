<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reaction;
use App\Models\Trailer;
use App\Models\TrailerWatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrailerController extends Controller
{
    public function index(Request $request)
    {
        $query = Trailer::where('is_active', true)->latest();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $trailers = $query->paginate(12);
        $trendingTrailers = Trailer::where('is_active', true)->orderBy('views', 'desc')->take(6)->get();

        return view('trailers', compact('trailers', 'trendingTrailers'));
    }

    public function show(Request $request, $id)
    {
        $trailer = Trailer::where('is_active', true)->findOrFail($id);

        $trailer->increment('views');

        if ($userId = Auth::id()) {
            TrailerWatch::updateOrCreate(
                ['user_id' => $userId, 'trailer_id' => $trailer->id],
                ['watched_at' => now()]
            );
        }

        $interaction = [
            'favorited'   => false,
            'my_reaction' => null,
            'likes'       => $trailer->likesCount(),
            'dislikes'    => $trailer->dislikesCount(),
        ];

        if ($userId = Auth::id()) {
            $interaction['favorited'] = Favorite::where('favoritable_type', Trailer::class)
                ->where('favoritable_id', $trailer->id)
                ->where('user_id', $userId)->exists();
            $interaction['my_reaction'] = Reaction::where('reactable_type', Trailer::class)
                ->where('reactable_id', $trailer->id)
                ->where('user_id', $userId)->value('reaction');
        }

        $comments = $trailer->comments()
            ->with('user', 'replies.user')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $moreTrailers = Trailer::where('is_active', true)
            ->where('id', '!=', $trailer->id)
            ->latest()->take(8)->get();

        return view('trailers.show', compact('trailer', 'interaction', 'comments', 'moreTrailers'));
    }
}