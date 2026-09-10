<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Reaction;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public const VISITOR_COOKIE = 'mm_vkey';

    private function requireLogin(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => 'login_required',
            'message' => 'Please login or create an account to continue.',
        ], 401);
    }

    private function currentIdentity(): array
    {
        return ['user_id' => Auth::id(), 'visitor_key' => null];
    }

    private function resolveItem(string $type, int $id)
    {
        return match ($type) {
            'movie'   => \App\Models\Movie::find($id),
            'series'  => \App\Models\Series::find($id),
            'trailer' => Trailer::find($id),
            default   => null,
        };
    }

    private function morphTypes(string $type): ?array
    {
        return match ($type) {
            'movie'   => ['class' => \App\Models\Movie::class,   'for' => 'movie'],
            'series'  => ['class' => \App\Models\Series::class,  'for' => 'series'],
            'trailer' => ['class' => Trailer::class,             'for' => 'trailer'],
            default   => null,
        };
    }

    public function toggleFavorite(Request $request)
    {
        if (!Auth::check()) return $this->requireLogin();

        $request->validate([
            'type' => 'required|in:movie,series,trailer',
            'id'   => 'required|integer',
        ]);

        $item = $this->resolveItem($request->type, $request->id);
        if (!$item) return response()->json(['error' => 'Item not found.'], 404);

        $identity = $this->currentIdentity();
        $morph    = $this->morphTypes($request->type);

        $favorite = Favorite::where('favoritable_type', $morph['class'])
            ->where('favoritable_id', $item->id)
            ->where('user_id', $identity['user_id'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
        } else {
            Favorite::create(array_merge($identity, [
                'favoritable_type' => $morph['class'],
                'favoritable_id'   => $item->id,
            ]));
            $favorited = true;
        }

        return response()->json([
            'favorited' => $favorited,
            'count'     => $item->favorites()->count(),
        ]);
    }

    public function react(Request $request)
    {
        if (!Auth::check()) return $this->requireLogin();

        $request->validate([
            'type'     => 'required|in:movie,series,trailer',
            'id'       => 'required|integer',
            'reaction' => 'required|in:like,dislike',
        ]);

        $item = $this->resolveItem($request->type, $request->id);
        if (!$item) return response()->json(['error' => 'Item not found.'], 404);

        $identity = $this->currentIdentity();
        $morph    = $this->morphTypes($request->type);

        $query = Reaction::where('reactable_type', $morph['class'])
            ->where('reactable_id', $item->id)
            ->where('user_id', $identity['user_id']);

        $existing = $query->first();
        $current  = null;

        if (!$existing) {
            Reaction::create(array_merge($identity, [
                'reactable_type' => $morph['class'],
                'reactable_id'   => $item->id,
                'reaction'       => $request->reaction,
            ]));
            $current = $request->reaction;
        } elseif ($existing->reaction === $request->reaction) {
            $existing->delete();
        } else {
            $existing->update(['reaction' => $request->reaction]);
            $current = $request->reaction;
        }

        return response()->json([
            'reaction'     => $current,
            'like_count'   => $item->likesCount(),
            'dislike_count'=> $item->dislikesCount(),
        ]);
    }

    public function storeComment(Request $request)
    {
        if (!Auth::check()) return $this->requireLogin();

        $request->validate([
            'type'      => 'required|in:movie,series,trailer',
            'id'        => 'required|integer',
            'body'      => 'required|string|max:2000',
            'parent_id' => 'nullable|integer',
        ]);

        $item = $this->resolveItem($request->type, $request->id);
        if (!$item) return response()->json(['error' => 'Item not found.'], 404);

        $identity = $this->currentIdentity();
        $morph    = $this->morphTypes($request->type);

        $parent = null;
        if ($request->parent_id) {
            $parent = Comment::where('commentable_type', $morph['class'])
                ->where('commentable_id', $item->id)
                ->where('id', $request->parent_id)
                ->first();
            if (!$parent) return response()->json(['error' => 'Reply target not found.'], 422);
        }

        $comment = Comment::create(array_merge($identity, [
            'display_name'      => null,
            'commentable_type'  => $morph['class'],
            'commentable_id'    => $item->id,
            'parent_id'         => $request->parent_id,
            'body'              => trim((string) $request->body),
        ]));

        $rootKey = $parent ? $parent->id : $comment->id;

        return response()->json([
            'id'         => $comment->id,
            'root_id'    => $rootKey,
            'is_reply'   => (bool) $request->parent_id,
            'author'     => $comment->author_name,
            'avatar'     => 'fas fa-user',
            'created'    => $comment->created_at->diffForHumans(),
            'body'       => $comment->body,
            'can_delete' => true,
            'count'      => $item->comments()->whereNull('parent_id')->count(),
        ]);
    }

    public function deleteComment(Request $request, $id)
    {
        if (!Auth::check()) return $this->requireLogin();

        $comment = Comment::findOrFail($id);

        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            return response()->json(['ok' => true]);
        }

        return response()->json(['error' => 'You can only delete your own comment.'], 403);
    }

    public function favorites(Request $request)
    {
        if (!Auth::check()) return redirect('/login');

        $movies = Favorite::where('user_id', Auth::id())
            ->where('favoritable_type', \App\Models\Movie::class)
            ->with('favoritable')->get()->pluck('favoritable')->filter();

        $series = Favorite::where('user_id', Auth::id())
            ->where('favoritable_type', \App\Models\Series::class)
            ->with('favoritable')->get()->pluck('favoritable')->filter();

        $trailers = Favorite::where('user_id', Auth::id())
            ->where('favoritable_type', Trailer::class)
            ->with('favoritable')->get()->pluck('favoritable')->filter();

        return view('favorites', compact('movies', 'series', 'trailers'));
    }
}