<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\HeroSlide;
use App\Models\Reaction;
use App\Models\Trailer;
use App\Models\TrailerWatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrailerController extends Controller
{
    public function home(Request $request)
    {
        $trendingTrailers = Trailer::where('is_active', true)
                                   ->orderBy('views', 'desc')
                                   ->limit(12)
                                   ->get();

        $recentTrailers = Trailer::where('is_active', true)
                                 ->latest()
                                 ->limit(12)
                                 ->get();

        $featuredTrailer = $trendingTrailers->first() ?? $recentTrailers->first();

        // Hero slideshow (managed in admin)
        $heroSlides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

        // Current user's favourite trailers
        $favTrailers = collect();
        if ($userId = Auth::id()) {
            $favTrailers = Favorite::with('favoritable')
                ->where('user_id', $userId)
                ->where('favoritable_type', Trailer::class)
                ->latest()->limit(12)->get()
                ->pluck('favoritable')->filter();
        }

        return view('welcome', compact('trendingTrailers', 'recentTrailers', 'featuredTrailer', 'heroSlides', 'favTrailers'));
    }

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

    public function show(Request $request, $slug)
    {
        $trailer = Trailer::where('slug', $slug)->first() ?? Trailer::find($slug);

        if (!$trailer || !$trailer->is_active) {
            abort(404);
        }

        if (ctype_digit($slug) && $trailer->slug !== $slug) {
            return redirect()->route('trailers.show', $trailer->slug);
        }

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

        $favCount = Favorite::where('favoritable_type', Trailer::class)
            ->where('favoritable_id', $trailer->id)
            ->count();

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

        return view('trailers.show', compact('trailer', 'interaction', 'favCount', 'comments', 'moreTrailers'));
    }

    public function apiSearch(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%' . $q . '%';

        $results = Trailer::where('is_active', true)
            ->where('title', 'like', $like)
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get()
            ->map(fn ($t) => [
                'type'   => 'trailer',
                'title'  => $t->title,
                'year'   => $t->created_at?->year,
                'poster' => $t->thumb_url,
                'url'    => route('trailers.show', $t->slug),
            ]);

        return response()->json($results);
    }

    public function sitemap()
    {
        $trailers = Trailer::where('is_active', true)->get();

        $e = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $static = [
            ['loc' => url('/'), 'freq' => 'daily', 'priority' => '1.0'],
            ['loc' => url('/trailers'), 'freq' => 'daily', 'priority' => '0.9'],
            ['loc' => url('/about'), 'freq' => 'monthly', 'priority' => '0.5'],
        ];

        foreach ($static as $page) {
            $xml .= '  <url><loc>' . $e($page['loc']) . '</loc><changefreq>' . $e($page['freq']) . '</changefreq><priority>' . $e($page['priority']) . '</priority></url>' . "\n";
        }
        foreach ($trailers as $trailer) {
            $xml .= '  <url><loc>' . $e(url('/trailers/' . $trailer->slug)) . '</loc><lastmod>' . (optional($trailer->updated_at)->toDateString() ?? date('Y-m-d')) . '</lastmod><changefreq>daily</changefreq><priority>0.8</priority></url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}