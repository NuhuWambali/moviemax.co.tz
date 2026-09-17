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
        $recentTrailers = Trailer::where('is_active', true)->latest()->limit(12)->get();

        $trendingTrailers = Trailer::where('is_active', true)
                                   ->orderBy('views', 'desc')
                                   ->limit(12)
                                   ->get();

        $comingSoon = Trailer::where('is_active', true)
                             ->whereNotNull('release_date')
                             ->where('release_date', '>=', now()->toDateString())
                             ->orderBy('release_date')
                             ->limit(12)
                             ->get();

        $popularThisWeek = Trailer::where('is_active', true)
            ->whereHas('viewsLog', fn ($q) => $q->where('viewed_at', '>=', now()->subDays(7)))
            ->withCount(['viewsLog' => fn ($q) => $q->where('viewed_at', '>=', now()->subDays(7))])
            ->orderByDesc('views_log_count')
            ->limit(12)
            ->get();

        if ($popularThisWeek->isEmpty()) {
            $popularThisWeek = $trendingTrailers;
        }

        $international = Trailer::where('is_active', true)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('country')->whereNotIn('country', ['', 'United States']);
                })->orWhere(function ($q2) {
                    $q2->whereNotNull('language')->whereNotIn('language', ['', 'English']);
                });
            })
            ->latest()->limit(12)->get();

        $genres = Trailer::where('is_active', true)
            ->whereNotNull('genre')->where('genre', '!=', '')
            ->selectRaw('genre, count(*) as total')
            ->groupBy('genre')->orderBy('total', 'desc')->limit(12)
            ->get();

        $featuredTrailer = Trailer::where('is_active', true)->where('trailer_of_the_day', true)->first()
            ?? Trailer::where('is_active', true)->where('featured', true)->orderBy('views', 'desc')->first()
            ?? $trendingTrailers->first()
            ?? $recentTrailers->first();

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

        $favoritedIds = $favTrailers->pluck('id')->flip();

        return view('welcome', compact(
            'recentTrailers',
            'trendingTrailers',
            'comingSoon',
            'popularThisWeek',
            'international',
            'genres',
            'featuredTrailer',
            'heroSlides',
            'favTrailers',
            'favoritedIds'
        ));
    }

    public function index(Request $request)
    {
        $query = Trailer::where('is_active', true);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort') ?? $request->route('sort') ?? 'latest';

        switch ($sort) {
            case 'trending':
                $query->orderBy('views', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'featured':
                $query->where('featured', true)->latest();
                break;
            default:
                $query->latest();
        }

        $trailers = $query->paginate(12)->withQueryString();
        $trendingTrailers = Trailer::where('is_active', true)->orderBy('views', 'desc')->take(6)->get();
        $activeSort = $request->get('sort', 'latest');

        return view('trailers', compact('trailers', 'trendingTrailers', 'activeSort'));
    }

    public function genres()
    {
        $genres = Trailer::where('is_active', true)
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->selectRaw('genre, count(*) as total')
            ->groupBy('genre')
            ->orderBy('total', 'desc')
            ->get();

        $total = Trailer::where('is_active', true)->count();

        return view('genres', compact('genres', 'total'));
    }

    public function genre($genre)
    {
        $slug = strtolower(trim($genre));

        $match = Trailer::where('is_active', true)->get('genre')
            ->pluck('genre')->unique()->filter()
            ->first(fn ($g) => strtolower($g) === $slug || str_slug($g) === $slug);

        $query = Trailer::where('is_active', true);

        if ($match) {
            $query->where('genre', $match);
            $label = $match;
        } else {
            $query->whereRaw('LOWER(genre) = ?', [$slug]);
            $label = ucwords(str_replace('-', ' ', $slug));
        }

        $featured = (clone $query)->orderBy('views', 'desc')->first();
        $trailers = $query->latest()->paginate(15)->withQueryString();
        $trending = Trailer::where('is_active', true)->orderBy('views', 'desc')->take(6)->get();

        if ($featured) {
            $trailersCollection = $trailers->getCollection()->reject(fn ($t) => $t->id === $featured->id);
            $trailers->setCollection($trailersCollection);
        }

        return view('genre', compact('genre', 'trailers', 'featured', 'trending', 'label'));
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