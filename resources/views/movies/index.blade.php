<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Movies - MovieMax</title>
    @include('partials.seo', ['seoTitle' => 'Movies - MovieMax – Watch & Download Free Movies Online', 'seoDescription' => 'Browse all movies on MovieMax by genre, year, rating and popularity. Stream and download the latest HD films free.'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --accent-purple: #ffd700;
            --accent-cyan: #ffd700;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-bg: rgba(10, 13, 18, 0.75);
            --glass-border: rgba(255,255,255,0.11);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translate(-50%, -40%); } to { opacity: 1; transform: translate(-50%, -50%); } }

        /* NAVBAR */
        .navbar {
            position: fixed; top: 0; width: 100%; padding: 1rem 5%;
            display: flex; justify-content: space-between; align-items: center;
            backdrop-filter: blur(24px) saturate(180%);
            background: rgba(10, 13, 18, 0.85);
            z-index: 1000; transition: all 0.4s ease;
            border-bottom: 1px solid var(--glass-border);
        }
        .navbar.scrolled { background: rgba(10, 13, 18, 0.97); padding: 0.7rem 5%; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08); }
        .logo {
            font-family: 'Bebas Neue', sans-serif; font-size: 2rem; letter-spacing: 3px;
            background: var(--accent-red);
            background-size: 200% 200%;
            animation: gradientShift 4s ease infinite;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            text-decoration: none; z-index: 1001;
        }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a {
            color: var(--text-secondary); text-decoration: none; font-weight: 500;
            transition: all 0.3s ease; font-size: 0.88rem; position: relative; padding: 0.2rem 0;
        }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px;
            background: var(--accent-red);
            transition: width 0.3s ease; border-radius: 2px;
        }
        .nav-links a:hover::after, .nav-links a.active::after { width: 100%; }
        .nav-links a:hover, .nav-links a.active { color: var(--text-primary); }
        .menu-btn { display: none; font-size: 1.5rem; cursor: pointer; color: var(--text-primary); z-index: 1001; }
        .mobile-overlay { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45); backdrop-filter: blur(5px); z-index: 999; }
        .mobile-overlay.active { display: block; }
        @media (max-width: 768px) {
            .menu-btn { display: block; }
            .nav-links {
                position: fixed; top: 0; right: -100%; width: 75%; height: 100vh;
                background: rgba(10, 13, 18, 0.98); backdrop-filter: blur(30px);
                flex-direction: column; justify-content: center; align-items: center;
                gap: 2.5rem; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                z-index: 1000; border-left: 1px solid rgba(229, 9, 20, 0.2);
            }
            .nav-links.active { right: 0; }
        }

        /* CONTAINER */
        .container {
            max-width: 1600px; margin: 0 auto; padding: 100px 5% 3rem;
        }
        .page-header { margin-bottom: 2.5rem; }
        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif; font-size: 2.8rem; letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }
        .page-header h1 i { color: var(--accent-red); margin-right: 0.5rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.95rem; }

        /* FILTER BAR */
        .filter-bar {
            background: var(--bg-card);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border-radius: 20px; padding: 1.2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--glass-border);
        }
        .filter-row {
            display: flex; flex-wrap: wrap; gap: 1rem;
            align-items: center; justify-content: space-between;
        }
        .filter-group { display: flex; flex-wrap: wrap; gap: 0.8rem; align-items: center; }
        .filter-label { color: var(--text-muted); font-size: 0.85rem; }
        .filter-select, .filter-input {
            background: var(--bg-card); border: 1px solid var(--glass-border);
            padding: 0.5rem 1rem; border-radius: 12px; color: var(--text-primary); font-size: 0.85rem;
            cursor: pointer; transition: all 0.3s ease;
        }
        .filter-select:hover, .filter-input:hover { border-color: var(--accent-red); }
        .filter-select:focus { outline: none; border-color: var(--accent-red); box-shadow: 0 0 10px rgba(229, 9, 20, 0.15); }
        .search-input { width: 250px; padding-left: 2.5rem; }
        .search-wrapper { position: relative; }
        .search-wrapper i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.8rem;
        }
        .clear-filters {
            background: var(--bg-card); border: 1px solid var(--glass-border);
            padding: 0.5rem 1rem; border-radius: 12px; color: var(--text-primary);
            cursor: pointer; transition: all 0.3s ease; font-size: 0.85rem;
        }
        .clear-filters:hover { background: var(--accent-red); border-color: var(--accent-red); color: white; }
        .active-filters {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            margin-top: 1rem; padding-top: 1rem;
            border-top: 1px solid var(--glass-border);
        }
        .filter-tag {
            background: rgba(229, 9, 20, 0.12);
            padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.72rem;
            display: inline-flex; align-items: center; gap: 0.5rem;
            border: 1px solid rgba(229, 9, 20, 0.2);
            color: var(--text-primary);
        }
        .filter-tag i { cursor: pointer; transition: 0.2s; }
        .filter-tag i:hover { color: var(--accent-red); }

        /* STATS */
        .stats-bar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
        }
        .results-count { color: var(--text-secondary); font-size: 0.85rem; }
        .sort-select {
            background: var(--bg-card); border: 1px solid var(--glass-border);
            padding: 0.4rem 0.8rem; border-radius: 12px; color: var(--text-primary);
            font-size: 0.8rem; cursor: pointer; transition: 0.3s;
        }
        .sort-select:focus { outline: none; border-color: var(--accent-red); }

        /* MOVIE GRID */
        .movies-grid {
            display: grid; grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem; margin-bottom: 2rem;
        }
        .movie-card {
            background: var(--bg-card); border-radius: 16px; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer; position: relative;
        }
        .movie-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(229, 9, 20, 0.2);
        }
        .card-img-wrap { position: relative; overflow: hidden; aspect-ratio: 2/3; }
        .movie-poster { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .movie-card:hover .movie-poster { transform: scale(1.08); }
        .card-img-wrap::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 50%;
            background: linear-gradient(to top, var(--bg-card), transparent); pointer-events: none;
        }
        .card-overlay {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease; background: rgba(9, 11, 15, 0.5); z-index: 1;
        }
        .movie-card:hover .card-overlay { opacity: 1; }
        .card-overlay i {
            width: 50px; height: 50px;
            background: var(--accent-red);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: white; box-shadow: 0 8px 25px rgba(229, 9, 20, 0.4);
            transform: scale(0.8); transition: transform 0.3s ease;
        }
        .movie-card:hover .card-overlay i { transform: scale(1); }
        .fav-heart {
            position: absolute; top: 10px; right: 10px; z-index: 3;
            width: 34px; height: 34px; border-radius: 50%; border: none;
            background: rgba(10, 13, 18, 0.8); color: #d1d5db; font-size: 0.85rem;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        }
        .fav-heart:hover { transform: scale(1.12); color: var(--accent-red); }
        .fav-heart.active { color: white; background: var(--accent-red); }
        .movie-info { padding: 0.9rem 1rem 1rem; }
        .movie-title {
            font-size: 0.88rem; font-weight: 600; margin-bottom: 0.4rem;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .movie-meta {
            display: flex; justify-content: space-between; font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* PAGINATION */
        .pro-pagination {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 1rem; margin-top: 2rem; padding: 1rem;
            background: var(--bg-card); box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border-radius: 16px; border: 1px solid var(--glass-border);
        }
        .pagination-info { color: var(--text-secondary); font-size: 0.85rem; }
        .pagination-links { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
        .page-link {
            min-width: 38px; height: 38px; display: inline-flex; align-items: center;
            justify-content: center; padding: 0 0.75rem;
            background: var(--bg-card); border-radius: 10px; color: var(--text-primary);
            text-decoration: none; font-size: 0.85rem; transition: all 0.3s ease;
            border: 1px solid var(--glass-border);
        }
        .page-link:hover {
            background: var(--accent-red);
            border-color: transparent; color: white; transform: translateY(-2px);
        }
        .page-link.active {
            background: var(--accent-red);
            border-color: transparent; color: white; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
        .page-dots { color: var(--text-muted); padding: 0 0.25rem; }

        /* EMPTY STATE */
        .empty-state {
            text-align: center; padding: 4rem;
            background: var(--bg-card); box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border-radius: 24px; border: 1px solid var(--glass-border);
        }
        .empty-state i { font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem; display: block; }

        footer {
            text-align: center; padding: 3rem 5% 2rem;
            border-top: 1px solid var(--glass-border);
            margin-top: 2rem; position: relative;
        }
        footer::before {
            content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            width: 200px; height: 1px;
            background: var(--accent-red);
        }
        footer .footer-brand {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.5rem; letter-spacing: 3px;
            background: var(--accent-red);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            margin-bottom: 0.5rem;
        }
        footer p { color: var(--text-muted); font-size: 0.78rem; }

        /* MODALS */
        .movie-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000; animation: fadeIn 0.3s ease; }
        .modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); }
        .modal-container {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 90%; max-width: 800px;
            background: #161c26;
            backdrop-filter: blur(30px); border-radius: 24px; overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.3), 0 0 80px -20px rgba(229, 9, 20, 0.2);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .modal-header { display: flex; padding: 24px; gap: 24px; position: relative; }
        .modal-poster { flex: 0 0 160px; }
        .modal-poster img { width: 100%; border-radius: 16px; box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .modal-info { flex: 1; }
        .modal-info h2 {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; letter-spacing: 1px;
            margin-bottom: 10px;
            background: var(--text-primary);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .modal-meta { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; font-size: 0.8rem; color: var(--text-secondary); }
        .modal-meta i { color: var(--accent-red); margin-right: 4px; }
        .modal-info p { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .modal-buttons { display: flex; gap: 12px; }
        .modal-btn-watch, .modal-btn-download {
            padding: 10px 22px; border-radius: 12px; font-weight: 700; font-size: 0.85rem;
            cursor: pointer; transition: all 0.3s ease; border: none;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .modal-btn-watch { background: var(--accent-red); color: white; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3); }
        .modal-btn-watch:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(229, 9, 20, 0.4); }
        .modal-btn-download { background: rgba(255,255,255,0.08); border: 1px solid var(--glass-border); color: var(--text-primary); }
        .modal-btn-download:hover { background: rgba(229, 9, 20, 0.1); border-color: rgba(229, 9, 20, 0.3); transform: translateY(-2px); }
        .modal-close {
            position: absolute; top: 16px; right: 20px;
            background: rgba(255,255,255,0.08); border: 1px solid var(--glass-border);
            color: var(--text-secondary); font-size: 1.5rem; cursor: pointer;
            transition: all 0.3s ease; width: 38px; height: 38px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .modal-close:hover { background: rgba(229, 9, 20, 0.15); color: var(--accent-red); border-color: rgba(229, 9, 20, 0.3); }
        .video-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 3000; background: #000; }
        .video-container { position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
        .video-container video { width: 100%; height: 100%; object-fit: contain; }
        .close-video {
            position: absolute; top: 20px; right: 30px;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(10px);
            color: white; border: 1px solid rgba(255,255,255,0.1);
            font-size: 1.8rem; cursor: pointer; z-index: 3001;
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;
        }
        .close-video:hover { background: var(--accent-red); border-color: var(--accent-red); }

        @media (max-width: 1100px) {
            .movies-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 768px) {
            .filter-row { flex-direction: column; align-items: stretch; }
            .filter-group { justify-content: space-between; }
            .search-input { width: 100%; }
            .movies-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .modal-header { flex-direction: column; text-align: center; padding: 20px; }
            .modal-poster { flex: 0 0 auto; max-width: 120px; margin: 0 auto; }
            .modal-buttons { justify-content: center; }
        }
    </style>
</head>
<body>
@include('partials.loader')    @include('partials.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-film"></i> Movies</h1>
            <p>Discover and watch the latest movies in stunning quality</p>
        </div>

        <div class="filter-bar">
            <div class="filter-row">
                <div class="filter-group">
                    <span class="filter-label"><i class="fas fa-filter"></i> Filter by:</span>
                    <select class="filter-select" id="genreFilter">
                        <option value="">All Genres</option>
                        <option value="Action" {{ request('genre') == 'Action' ? 'selected' : '' }}>Action</option>
                        <option value="Horror" {{ request('genre') == 'Horror' ? 'selected' : '' }}>Horror</option>
                        <option value="Romance" {{ request('genre') == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Sci-Fi" {{ request('genre') == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
                        <option value="Comedy" {{ request('genre') == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                        <option value="Drama" {{ request('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
                        <option value="Thriller" {{ request('genre') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                        <option value="War" {{ request('genre') == 'War' ? 'selected' : '' }}>War</option>
                        <option value="Fantasy" {{ request('genre') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                    </select>
                    <select class="filter-select" id="yearFilter">
                        <option value="">All Years</option>
                        @for($year = date('Y'); $year >= 1980; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <div class="search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" class="filter-select search-input" placeholder="Search movies..." value="{{ request('search') }}">
                    </div>
                    <button class="clear-filters" onclick="clearAllFilters()"><i class="fas fa-times"></i> Clear All</button>
                </div>
            </div>
            <div class="active-filters" id="activeFilters"></div>
        </div>

        <div class="stats-bar">
            <div class="results-count">
                <i class="fas fa-film"></i> Showing <strong>{{ $movies->firstItem() ?? 0 }}</strong> to <strong>{{ $movies->lastItem() ?? 0 }}</strong> of <strong>{{ $movies->total() }}</strong> movies
            </div>
            <div>
                <select class="sort-select" id="sortBy">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Releases</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </div>
        </div>

        @if($movies->count() > 0)
            <div class="movies-grid" id="moviesGrid">
                @foreach($movies as $movie)
                    <div class="movie-card"
                         data-id="{{ $movie->id }}" data-slug="{{ $movie->slug }}" data-title="{{ $movie->title }}"
                         data-poster="{{ $movie->poster_path }}" data-year="{{ $movie->release_year }}"
                         data-duration="{{ $movie->duration }}" data-genre="{{ $movie->genre }}"
                         data-description="{{ $movie->description }}"
                         data-file="{{ $movie->file_path }}">
                        <div class="card-img-wrap">
                            <img class="movie-poster" src="{{ $movie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="card-overlay"><i class="fas fa-play"></i></div>
                            <button class="fav-heart" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event,'movie',{{ $movie->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title">{{ $movie->title }}</div>
                            <div class="movie-meta">
                                <span class="movie-year"><i class="fas fa-calendar-alt"></i> {{ $movie->release_year }}</span>
                            </div>
                            <div class="movie-meta" style="margin-top: 0.3rem;">
                                <span><i class="fas fa-clock"></i> {{ $movie->duration }}</span>
                                <span><i class="fas fa-eye"></i> {{ number_format($movie->views ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($movies->hasPages())
            <div class="pro-pagination">
                <div class="pagination-info">Showing {{ $movies->firstItem() }} to {{ $movies->lastItem() }} of {{ $movies->total() }} results</div>
                <div class="pagination-links">
                    @if(!$movies->onFirstPage())
                        <a href="{{ $movies->url(1) }}" class="page-link"><i class="fas fa-angle-double-left"></i></a>
                    @endif
                    @if(!$movies->onFirstPage())
                        <a href="{{ $movies->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @php $start = max(1, $movies->currentPage() - 2); $end = min($movies->lastPage(), $movies->currentPage() + 2); @endphp
                    @if($start > 1) <span class="page-dots">...</span> @endif
                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $movies->currentPage())
                            <span class="page-link active">{{ $i }}</span>
                        @else
                            <a href="{{ $movies->url($i) }}" class="page-link">{{ $i }}</a>
                        @endif
                    @endfor
                    @if($end < $movies->lastPage()) <span class="page-dots">...</span> @endif
                    @if($movies->hasMorePages())
                        <a href="{{ $movies->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    @endif
                    @if($movies->hasMorePages())
                        <a href="{{ $movies->url($movies->lastPage()) }}" class="page-link"><i class="fas fa-angle-double-right"></i></a>
                    @endif
                </div>
            </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-film"></i>
                <h3>No movies found</h3>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Try adjusting your filters or search criteria</p>
                <button class="clear-filters" onclick="clearAllFilters()" style="margin-top: 1rem;">Clear Filters</button>
            </div>
        @endif

        @include('partials.footer')
    </div>

    <!-- Movie Modal -->
    <div id="movieModal" class="movie-modal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-poster"><img id="modalPoster" src="" alt=""></div>
                <div class="modal-info">
                    <h2 id="modalTitle"></h2>
                    <div class="modal-meta">
                        <span><i class="fas fa-calendar-alt"></i> <span id="modalYear"></span></span>
                        <span><i class="fas fa-clock"></i> <span id="modalDuration"></span></span>
                        <span><i class="fas fa-tag"></i> <span id="modalGenre"></span></span>
                    </div>
                    <p id="modalDescription"></p>
                    <div class="modal-buttons">
                        <button class="modal-btn-watch" id="watchBtn"><i class="fas fa-play"></i> Watch Now</button>
                        <button class="modal-btn-download" id="downloadBtn"><i class="fas fa-download"></i> Download</button>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
        </div>
    </div>

    <div id="videoModal" class="video-modal">
        <button class="close-video" onclick="closeVideo()">&times;</button>
        <div class="video-container">
            <video id="videoPlayer" controls autoplay>
                <source id="videoSource" src="" type="video/mp4">
            </video>
        </div>
    </div>

    <script>
        let currentMovie = null;
        function openMovieModal(movieId, title, poster, year, duration, genre, description, filePath) {
            currentMovie = { id: movieId, title, poster, year, duration, genre, description, filePath };
            document.getElementById('modalPoster').src = poster || '/images/posters/dummy-poster.png';
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalYear').innerText = year || 'N/A';
            document.getElementById('modalDuration').innerText = duration || 'N/A';
            document.getElementById('modalGenre').innerText = genre || 'General';
            document.getElementById('modalDescription').innerText = description || 'No description available.';
            document.getElementById('movieModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function closeModal() { document.getElementById('movieModal').style.display = 'none'; document.body.style.overflow = 'auto'; }
        function closeVideo() {
            const v = document.getElementById('videoPlayer');
            v.pause(); v.src = '';
            document.getElementById('videoModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        document.getElementById('watchBtn').addEventListener('click', function() {
            if (currentMovie && currentMovie.id) {
                closeModal();
                const vs = document.getElementById('videoSource');
                const vp = document.getElementById('videoPlayer');
                vs.src = '/download/stream/' + currentMovie.id;
                vp.load();
                document.getElementById('videoModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            } else {
                Swal.fire({ title: 'Not Available', text: 'Stream not available.', icon: 'info', background: '#161c26', color: '#ffffff' });
            }
        });
        document.getElementById('downloadBtn').addEventListener('click', function() {
            if (currentMovie && currentMovie.id) {
                Swal.fire({
                    title: 'Download ' + currentMovie.title, icon: 'info',
                    background: '#161c26', color: '#ffffff', showCancelButton: true,
                    confirmButtonColor: '#e50914', confirmButtonText: 'Download', cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const a = document.createElement('a');
                        a.href = '/download/movie/' + currentMovie.id;
                        a.download = currentMovie.title + '.mp4';
                        a.click();
                        Swal.fire({ title: 'Download Started!', icon: 'success', background: '#161c26', color: '#ffffff', timer: 2000, showConfirmButton: false });
                        closeModal();
                    }
                });
            }
        });
        // Favourites
        function csrfToken() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.content : '';
        }
        function handleLoginRequired(data) {
            if (data && data.error === 'login_required') {
                if (window.__loginPromptShown) return true;
                window.__loginPromptShown = true;
                Swal.fire({
                    title: 'Login Required',
                    text: data.message || 'Please login or create an account to continue.',
                    icon: 'warning',
                    background: '#161c26',
                    color: '#fff',
                    confirmButtonColor: '#e50914',
                    confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Login Now',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    cancelButtonColor: '#6b7280'
                }).then((r) => {
                    window.__loginPromptShown = false;
                    if (r.isConfirmed) window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                });
                return true;
            }
            return false;
        }
        async function toggleFavorite(e, type, id, btn) {
            e.stopPropagation();
            e.preventDefault();
            try {
                const res = await fetch('/interactions/favorite-toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                    body: JSON.stringify({ type: type, id: id })
                });
                const data = await res.json();
                if (handleLoginRequired(data)) return;
                if (data.favorited) { btn.classList.add('active'); btn.title = 'Remove from favorites'; }
                else { btn.classList.remove('active'); btn.title = 'Add to favorites'; }
            } catch (err) {}
        }
        document.querySelectorAll('.movie-card').forEach(card => {
            card.addEventListener('click', function(e) {
                window.location.href = '/movies/' + this.getAttribute('data-slug');
            });
        });
        function updateFilters() {
            const params = new URLSearchParams();
            const genre = document.getElementById('genreFilter').value;
            const year = document.getElementById('yearFilter').value;
            const search = document.getElementById('searchInput').value;
            const sort = document.getElementById('sortBy').value;
            if (genre) params.set('genre', genre);
            if (year) params.set('year', year);
            if (search) params.set('search', search);
            if (sort && sort !== 'latest') params.set('sort', sort);
            window.location.href = '/movies?' + params.toString();
        }
        function clearAllFilters() { window.location.href = '/movies'; }
        function displayActiveFilters() {
            const container = document.getElementById('activeFilters');
            const params = new URLSearchParams(window.location.search);
            const filters = [];
            if (params.get('genre')) filters.push({ label: 'Genre: ' + params.get('genre'), key: 'genre' });
            if (params.get('year')) filters.push({ label: 'Year: ' + params.get('year'), key: 'year' });
            if (params.get('search')) filters.push({ label: 'Search: ' + params.get('search'), key: 'search' });
            if (params.get('sort')) filters.push({ label: 'Sort: ' + params.get('sort').replace('_', ' '), key: 'sort' });
            container.innerHTML = filters.length > 0
                ? filters.map(f => `<div class="filter-tag">${f.label}<i class="fas fa-times" onclick="removeFilter('${f.key}')"></i></div>`).join('')
                : '<span style="color: var(--text-muted); font-size: 0.72rem;">No active filters</span>';
        }
        function removeFilter(key) {
            const params = new URLSearchParams(window.location.search);
            params.delete(key);
            window.location.href = '/movies?' + params.toString();
        }
        document.getElementById('genreFilter').addEventListener('change', updateFilters);
        document.getElementById('yearFilter').addEventListener('change', updateFilters);
        document.getElementById('sortBy').addEventListener('change', updateFilters);
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateFilters, 500);
        });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeModal(); closeVideo(); } });
        displayActiveFilters();
    </script>
</body>
</html>
