<?php
// app/Http/Controllers/MovieController.php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Series;
use App\Models\Favorite;
use App\Models\Reaction;
use App\Models\HeroSlide;
use App\Models\WatchHistory;
use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function home(Request $request)
    {
        // Movies
        $trendingMovies = Movie::where('is_active', true)
                               ->where('type', 'movie')
                               ->orderBy('download_count', 'desc')
                               ->limit(10)
                               ->get();
        
        $topRatedMovies = Movie::where('is_active', true)
                               ->where('type', 'movie')
                               ->orderBy('rating', 'desc')
                               ->limit(10)
                               ->get();
        
        $recentMovies = Movie::where('is_active', true)
                             ->where('type', 'movie')
                             ->latest()
                             ->limit(6)
                             ->get();
        
        // TV Series
        $trendingSeries = Series::where('is_active', true)
                                ->orderBy('download_count', 'desc')
                                ->limit(6)
                                ->get();
        
        $recentSeries = Series::where('is_active', true)
                              ->latest()
                              ->limit(6)
                              ->get();
        
        // Hero slideshow (managed in admin)
        $heroSlides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

        // Featured content (fallback if no hero slides are configured)
        $featuredMovie = $trendingMovies->first();
        $featuredSeries = $trendingSeries->first();

        // Current user's favorites (shown when logged in)
        $favMovies = collect();
        $favSeries = collect();
        $favTrailers = collect();
        if ($userId = Auth::id()) {
            $favRows = Favorite::with('favoritable')
                ->where('user_id', $userId)
                ->latest()->limit(20)->get();
            $favMovies = $favRows->where('favoritable_type', Movie::class)->pluck('favoritable')->filter();
            $favSeries = $favRows->where('favoritable_type', Series::class)->pluck('favoritable')->filter();
            $favTrailers = $favRows->where('favoritable_type', \App\Models\Trailer::class)->pluck('favoritable')->filter();
        }

        // Trailers (movies that have a trailer URL)
        $trailerMovies = Movie::where('is_active', true)
                               ->where('type', 'movie')
                               ->whereNotNull('trailer_url')
                               ->where('trailer_url', '!=', '')
->orderBy('download_count', 'desc')
                                ->limit(5)
                                ->get();

        $trailerSeries = Series::where('is_active', true)
                               ->whereNotNull('trailer_url')
                               ->where('trailer_url', '!=', '')
                               ->orderBy('download_count', 'desc')
                               ->limit(6)
                               ->get();

        // Dedicated trailers table (added later; keeps the home Official Trailers section populated)
        $homeTrailers = Trailer::where('is_active', true)
                               ->latest()
                               ->limit(8)
                               ->get();
        
        // All genres for filter
        $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Thriller', 'War', 'Western'];

        // Continue watching (logged-in users): in-progress movies/episodes
        $continueWatching = collect();
        if ($userId = Auth::id()) {
            $continueWatching = WatchHistory::where('user_id', $userId)
                ->where('watchable_type', Movie::class)
                ->where('progress_seconds', '>', 0)
                ->whereRaw('progress_seconds < (duration_seconds - 15)')
                ->orderBy('watched_at', 'desc')
                ->limit(6)
                ->get()
                ->map(function ($history) {
                    $movie = $history->watchable;
                    if (!$movie || !$movie->is_active) {
                        return null;
                    }
                    $history->movie = $movie;
                    $history->ratio = $history->duration_seconds > 0
                        ? round($history->progress_seconds / $history->duration_seconds, 2)
                        : 0;
                    return $history;
                })
                ->filter();
        }

        return view('welcome', compact('trendingMovies', 'topRatedMovies', 'recentMovies', 'trendingSeries', 'recentSeries', 'featuredMovie', 'featuredSeries', 'trailerMovies', 'trailerSeries', 'homeTrailers', 'favMovies', 'favSeries', 'favTrailers', 'genres', 'heroSlides', 'continueWatching'));
    }

    public function apiSearch(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%' . $q . '%';
        $results = [];

        foreach (Movie::where('is_active', true)->where('type', 'movie')->where('title', 'like', $like)->limit(4)->get() as $m) {
            $results[] = [
                'type'   => 'movie',
                'title'  => $m->title,
                'year'   => $m->release_year,
                'poster' => $m->poster_path,
                'url'    => route('movies.show', $m->slug),
            ];
        }

        foreach (Series::where('is_active', true)->where('title', 'like', $like)->limit(4)->get() as $s) {
            $results[] = [
                'type'   => 'series',
                'title'  => $s->title,
                'year'   => $s->release_year,
                'poster' => $s->poster_path,
                'url'    => route('series.show', $s->id),
            ];
        }

        foreach (Trailer::where('is_active', true)->where('title', 'like', $like)->limit(3)->get() as $t) {
            $results[] = [
                'type'   => 'trailer',
                'title'  => $t->title,
                'year'   => null,
                'poster' => $t->thumbnail,
                'url'    => route('trailers.show', $t->slug),
            ];
        }

        return response()->json(array_slice($results, 0, 8));
    }

    public function trailers()
    {
        $trailerMovies = Movie::where('is_active', true)
                               ->where('type', 'movie')
                               ->whereNotNull('trailer_url')
                               ->where('trailer_url', '!=', '')
                               ->orderBy('download_count', 'desc')
                               ->get();

        $trailerSeries = Series::where('is_active', true)
                               ->whereNotNull('trailer_url')
                               ->where('trailer_url', '!=', '')
                               ->orderBy('download_count', 'desc')
                               ->get();

        return view('trailers', compact('trailerMovies', 'trailerSeries'));
    }

    public function index(Request $request)
    {
        $query = Movie::where('type', 'movie')->where('is_active', true);
        
        // Search filter
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Genre filter
        if ($request->genre) {
            $query->where('genre', $request->genre);
        }
        
        // Year filter
        if ($request->year) {
            $query->where('release_year', $request->year);
        }
        
        // Sorting
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('release_year', 'asc');
                break;
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'top_rated':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $movies = $query->paginate(10);
        
        return view('movies.index', compact('movies'));
    }

    public function seriesIndex(Request $request)
    {
        $query = Series::where('is_active', true);
        
        // Search filter
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Genre filter
        if ($request->genre) {
            $query->where('genre', $request->genre);
        }
        
        // Year filter
        if ($request->year) {
            $query->where('release_year', $request->year);
        }
        
        // Sorting
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('release_year', 'asc');
                break;
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $series = $query->paginate(24);
        
        return view('series.index', compact('series'));
    }

  
    public function showSeries(Request $request, $id)
    {
        $series = Series::findOrFail($id);
        
        $episodes = Movie::where('series_id', $id)
            ->where('type', 'episode')
            ->where('is_active', true)
            ->orderBy('season_number')
            ->orderBy('episode_number')
            ->get();
        
        // Related series (same genre, excluding current)
        $relatedSeries = Series::where('genre', $series->genre)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->limit(5)
            ->get();
        
        // Same genre series (for the bottom section)
        $sameGenreSeries = Series::where('genre', $series->genre)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->limit(8)
            ->get();
        
        // Top downloaded series
        $topDownloads = Series::where('is_active', true)
            ->orderBy('download_count', 'desc')
            ->limit(5)
            ->get();
        
        // Recently added series
        $recentSeries = Series::where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();

        // Log watch history for logged-in users (profile activity)
        if ($userId = Auth::id()) {
            WatchHistory::updateOrCreate(
                ['user_id' => $userId, 'watchable_type' => 'App\Models\Series', 'watchable_id' => $series->id],
                ['watched_at' => now()]
            );
        }

        $interaction = $this->interactionState($request, $series, \App\Models\Series::class);

        $comments = $series->comments()
            ->with('user', 'replies.user')
            ->whereNull('parent_id')
            ->latest()
            ->get();

        // Per-episode progress + completed flags for logged-in users
        $episodeProgress = [];
        if (Auth::id() && $episodes->isNotEmpty()) {
            $rows = WatchHistory::where('user_id', Auth::id())
                ->where('watchable_type', Movie::class)
                ->whereIn('watchable_id', $episodes->pluck('id'))
                ->get();

            foreach ($rows as $row) {
                $episodeProgress[$row->watchable_id] = [
                    'progress'  => (int) $row->progress_seconds,
                    'completed' => $row->duration_seconds > 0
                        && $row->progress_seconds >= ($row->duration_seconds * 0.9),
                ];
            }
        }

        return view('series.show', compact(
            'series', 
            'episodes', 
            'relatedSeries', 
            'sameGenreSeries', 
            'topDownloads', 
            'recentSeries',
            'interaction',
            'comments',
            'episodeProgress'
        ));
    }

    private function interactionState(Request $request, $item, string $type): array
    {
        $userId = Auth::id();

        return [
            'favorited' => $userId ? Favorite::where('favoritable_type', $type)
                ->where('favoritable_id', $item->id)
                ->where('user_id', $userId)
                ->exists() : false,
            'my_reaction' => $userId ? Reaction::where('reactable_type', $type)
                ->where('reactable_id', $item->id)
                ->where('user_id', $userId)
                ->value('reaction') : null,
            'likes'   => $item->likesCount(),
            'dislikes'=> $item->dislikesCount(),
        ];
    }

    public function show(Request $request, $slug)
    {
        $movie = Movie::where('slug', $slug)->first() ?? Movie::find($slug);

        if (!$movie || !$movie->is_active) {
            abort(404);
        }

        if (ctype_digit($slug) && $movie->slug !== $slug) {
            return redirect()->route('movies.show', $movie->slug);
        }
        
        // If it's an episode, get the series info
        $series = null;
        if ($movie->isEpisode() && $movie->series_id) {
            $series = Series::find($movie->series_id);
        }
        
        // Log watch history for logged-in users (profile activity)
        if ($userId = Auth::id()) {
            WatchHistory::updateOrCreate(
                ['user_id' => $userId, 'watchable_type' => 'App\Models\Movie', 'watchable_id' => $movie->id],
                ['watched_at' => now()]
            );
        }

        $interaction = $this->interactionState($request, $movie, \App\Models\Movie::class);

        $comments = $movie->comments()
            ->with('user', 'replies.user')
            ->whereNull('parent_id')
            ->latest()
            ->get();
        
        // Get related movies (same genre)
        $related = Movie::where('is_active', true)
                        ->where('type', 'movie')
                        ->where('genre', $movie->genre)
                        ->where('id', '!=', $movie->id)
                        ->limit(6)
                        ->get();

        // Most watched movies (site-wide)
        $mostWatched = Movie::where('is_active', true)
                            ->where('type', 'movie')
                            ->where('id', '!=', $movie->id)
                            ->orderBy('views', 'desc')
                            ->limit(6)
                            ->get();

        // Top rated movies (site-wide)
        $topRated = Movie::where('is_active', true)
                         ->where('type', 'movie')
                         ->where('id', '!=', $movie->id)
                         ->orderBy('rating', 'desc')
                         ->limit(6)
                         ->get();

        // Resume point for logged-in viewers
        $resume = null;
        if ($userId = Auth::id()) {
            $resume = WatchHistory::where('user_id', $userId)
                ->where('watchable_type', Movie::class)
                ->where('watchable_id', $movie->id)
                ->first();
        }

        return view('movies.show', compact('movie', 'series', 'related', 'mostWatched', 'topRated', 'interaction', 'comments', 'resume'));
    }

    public function genre($genre)
    {
        $movies = Movie::where('is_active', true)
                       ->where('type', 'movie')
                       ->where('genre', $genre)
                       ->paginate(24);
        
        $series = Series::where('is_active', true)
                        ->where('genre', $genre)
                        ->limit(12)
                        ->get();
        
        return view('movies.genre', compact('movies', 'series', 'genre'));
    }

    public function sitemap()
    {
        $movies = Movie::where('is_active', true)->where('type', 'movie')->get();
        $series = Series::where('is_active', true)->get();
        $trailers = Trailer::where('is_active', true)->get();
        $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Thriller', 'War', 'Western'];

        $e = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $static = [
            ['loc' => url('/'), 'freq' => 'daily', 'priority' => '1.0'],
            ['loc' => url('/movies'), 'freq' => 'daily', 'priority' => '0.9'],
            ['loc' => url('/series'), 'freq' => 'daily', 'priority' => '0.9'],
            ['loc' => url('/trailers'), 'freq' => 'daily', 'priority' => '0.8'],
            ['loc' => url('/about'), 'freq' => 'monthly', 'priority' => '0.5'],
        ];
        foreach ($genres as $genre) {
            $static[] = ['loc' => url('/genre/' . rawurlencode($genre)), 'freq' => 'weekly', 'priority' => '0.6'];
        }

        foreach ($static as $page) {
            $xml .= '  <url><loc>' . $e($page['loc']) . '</loc><changefreq>' . $e($page['freq']) . '</changefreq><priority>' . $e($page['priority']) . '</priority></url>' . "\n";
        }
        foreach ($movies as $movie) {
            $xml .= '  <url><loc>' . $e(url('/movies/' . $movie->slug)) . '</loc><lastmod>' . (optional($movie->updated_at)->toDateString() ?? date('Y-m-d')) . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        }
        foreach ($series as $item) {
            $xml .= '  <url><loc>' . $e(url('/series/' . $item->id)) . '</loc><lastmod>' . (optional($item->updated_at)->toDateString() ?? date('Y-m-d')) . '</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
        }
        foreach ($trailers as $trailer) {
            $xml .= '  <url><loc>' . $e(url('/trailers/' . $trailer->slug)) . '</loc><lastmod>' . (optional($trailer->updated_at)->toDateString() ?? date('Y-m-d')) . '</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}