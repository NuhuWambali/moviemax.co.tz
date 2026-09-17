<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Reaction;
use App\Models\Trailer;
use App\Models\TrailerWatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $favoriteTrailers = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Trailer::class)
            ->with('favoritable')->latest()->get()->pluck('favoritable')->filter();

        $watchedTrailers = TrailerWatch::where('user_id', $user->id)
            ->with('trailer')->latest('watched_at')->get()->pluck('trailer')->filter();

        $myComments = Comment::where('user_id', $user->id)
            ->where('commentable_type', Trailer::class)
            ->with('commentable')->latest()->take(50)->get();

        $myLikes = Reaction::where('user_id', $user->id)
            ->where('reactable_type', Trailer::class)
            ->where('reaction', 'like')->with('reactable')->latest()->get()->pluck('reactable');

        $myDislikes = Reaction::where('user_id', $user->id)
            ->where('reactable_type', Trailer::class)
            ->where('reaction', 'dislike')->with('reactable')->latest()->get()->pluck('reactable');

        $stats = [
            'favorites'   => $favoriteTrailers->count(),
            'watched'     => $watchedTrailers->count(),
            'comments'    => $myComments->count(),
            'reactions'   => $myLikes->count() + $myDislikes->count(),
        ];

        return view('profile', compact(
            'user', 'favoriteTrailers', 'watchedTrailers', 'myComments', 'myLikes', 'myDislikes', 'stats'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|string|email|max:255|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Account updated successfully.');
    }
}