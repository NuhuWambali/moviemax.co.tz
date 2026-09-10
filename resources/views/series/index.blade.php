{{-- resources/views/series/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TV Series - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'TV Series - MOVIEMAX', 'seoDescription' => 'Browse all TV series on MOVIEMAX by genre, year and popularity. Stream and download complete seasons in HD.'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #3b82f6;
            --accent-red-dark: #2563eb;
            --accent-purple: #38bdf8;
            --accent-cyan: #38bdf8;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-border: rgba(255,255,255,0.11);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ============ NAVBAR ============ */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 0 5%;
            height: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            background: rgba(10, 13, 18, 0.85);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            transition: 0.3s;
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 3px;
            text-decoration: none;
            z-index: 1001;
            background: var(--accent-red);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: logoShift 4s ease infinite;
        }

        @keyframes logoShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.88rem;
            letter-spacing: 0.3px;
            position: relative;
            padding: 4px 0;
            transition: color 0.3s;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0%;
            height: 2px;
            border-radius: 2px;
            background: var(--accent-red);
            transition: width 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--text-primary);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .menu-btn {
            display: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--text-primary);
            z-index: 1001;
            background: none;
            border: none;
            transition: 0.3s;
        }

        .menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .menu-overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            .menu-btn { display: block; }
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 72%;
                height: 100vh;
                background: rgba(10, 13, 18, 0.97);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2.5rem;
                transition: right 0.35s cubic-bezier(.4,0,.2,1);
                z-index: 1000;
                border-left: 1px solid var(--glass-border);
            }
            .nav-links.active { right: 0; }
            .nav-links a { font-size: 1.1rem; }
        }

        .dropdown { position: relative; display: inline-block; }

        .dropdown-content {
            display: none;
            position: absolute;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            min-width: 180px;
            border-radius: 14px;
            padding: 0.5rem 0;
            z-index: 1;
            top: 30px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
        }

        .dropdown:hover .dropdown-content { display: block; }

        .dropdown-content a {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
        }

        .dropdown-content a:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-red);
        }

        /* ============ CONTAINER ============ */
        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 90px 5% 3rem;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            letter-spacing: 2px;
            margin-bottom: 0.4rem;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .page-header h1 i { color: var(--accent-red); margin-right: 0.5rem; }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ============ FILTER BAR ============ */
        .filter-bar {
            background: var(--bg-card);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 1.4rem;
            margin-bottom: 2rem;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
        }

        .filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .filter-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            letter-spacing: 0.3px;
        }

        .filter-select, .filter-input {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            padding: 0.55rem 1.1rem;
            border-radius: 30px;
            color: var(--text-primary);
            font-size: 0.82rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        .filter-select:focus, .filter-input:focus {
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .filter-select:hover { border-color: #e5e7eb; }

        .search-input {
            width: 260px;
            padding-left: 2.2rem;
        }

        .search-wrapper { position: relative; }

        .search-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .clear-filters {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            padding: 0.55rem 1.1rem;
            border-radius: 30px;
            color: var(--text-secondary);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            transition: background 0.3s, color 0.3s;
        }

        .clear-filters:hover {
            background: var(--accent-red);
            color: #fff;
            border-color: var(--accent-red);
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--glass-border);
        }

        .filter-tag {
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.25);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--accent-red);
        }

        .filter-tag i { cursor: pointer; transition: color 0.2s; }
        .filter-tag i:hover { color: var(--accent-red); }

        /* ============ STATS BAR ============ */
        .stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .results-count strong { color: var(--text-secondary); }

        .sort-select {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            padding: 0.45rem 0.9rem;
            border-radius: 20px;
            color: var(--text-primary);
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        .sort-select:focus {
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* ============ SERIES GRID ============ */
        .series-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .series-card {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            transition: transform 0.35s cubic-bezier(.4,0,.2,1), box-shadow 0.35s, border-color 0.35s;
            position: relative;
        }

        .series-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: transparent;
            box-shadow:
                0 0 0 1px rgba(59, 130, 246, 0.4),
                0 0 20px rgba(59, 130, 246, 0.15),
                0 20px 40px rgba(0,0,0,0.2);
        }

        .series-poster-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 2/3;
            overflow: hidden;
        }

        .series-poster {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(.4,0,.2,1);
        }

        .series-card:hover .series-poster {
            transform: scale(1.08);
        }

        .series-poster-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(9, 11, 15, 0.95) 0%, rgba(9, 11, 15, 0.3) 40%, transparent 100%);
            opacity: 0;
            transition: opacity 0.35s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .series-card:hover .series-poster-overlay { opacity: 1; }

        .series-play-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--accent-red);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
            transform: scale(0.7);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(.4,0,.2,1), opacity 0.35s;
        }

        .series-card:hover .series-play-btn {
            transform: scale(1);
            opacity: 1;
        }

        .series-info {
            padding: 0.9rem 1rem 1rem;
        }

        .series-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text-primary);
        }

        .series-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .fav-heart {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            background: rgba(10, 13, 18, 0.8);
            color: #d1d5db;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        }
        .fav-heart:hover { transform: scale(1.12); color: var(--accent-red); }
        .fav-heart.active { color: white; background: var(--accent-red); }

        /* ============ PAGINATION ============ */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.55rem 1.1rem;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.82rem;
            transition: background 0.3s, color 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .pagination a:hover {
            background: var(--accent-red);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
        }

        .pagination .active span {
            background: var(--accent-red);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
        }

        .pagination .disabled span {
            opacity: 0.35;
            cursor: default;
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: var(--bg-card);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
            display: block;
        }

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* ============ FOOTER ============ */
        footer {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 3rem;
            border-top: 1px solid transparent;
            border-image: var(--accent-red) 1;
        }

        /* ============ MODAL ============ */
        .series-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(9, 11, 15, 0.88);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .modal-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 780px;
            background: #161c26;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.35s cubic-bezier(.4,0,.2,1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            padding: 24px;
            gap: 24px;
            position: relative;
        }

        .modal-poster { flex: 0 0 160px; }

        .modal-poster img {
            width: 100%;
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .modal-info { flex: 1; }

        .modal-info h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 1px;
            margin-bottom: 12px;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .modal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .modal-meta i {
            color: var(--accent-purple);
            margin-right: 5px;
        }

        .modal-info p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .modal-buttons { display: flex; gap: 14px; flex-wrap: wrap; }

        .modal-btn-view {
            padding: 10px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-red);
            color: #fff;
        }

        .modal-btn-view:hover {
            transform: scale(1.04);
            box-shadow: 0 6px 24px rgba(59, 130, 246, 0.4);
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 22px;
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.25s, color 0.25s;
        }

        .modal-close:hover {
            background: var(--accent-red);
            color: #fff;
            border-color: var(--accent-red);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translate(-50%, -42%) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group { justify-content: space-between; }
            .search-input { width: 100%; }
            .series-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 1rem;
            }
            .page-header h1 { font-size: 2.2rem; }
            .modal-header {
                flex-direction: column;
                text-align: center;
            }
            .modal-poster {
                flex: 0 0 auto;
                max-width: 130px;
                margin: 0 auto;
            }
            .modal-buttons { justify-content: center; }
            .pagination { flex-wrap: wrap; }
        }

        @media (max-width: 480px) {
            .series-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            .container { padding: 80px 4% 2rem; }
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tv"></i> TV Series</h1>
            <p>Discover and watch the best TV series in stunning quality</p>
        </div>

        <!-- Filter Bar -->
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
                        <input type="text" id="searchInput" class="filter-select search-input" placeholder="Search series..." value="{{ request('search') }}">
                    </div>
                    <button class="clear-filters" onclick="clearAllFilters()">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                </div>
            </div>

            <div class="active-filters" id="activeFilters"></div>
        </div>

        <!-- Stats and Sort -->
        <div class="stats-bar">
            <div class="results-count">
                <i class="fas fa-tv"></i> Showing <strong>{{ $series->firstItem() ?? 0 }}</strong> to <strong>{{ $series->lastItem() ?? 0 }}</strong> of <strong>{{ $series->total() }}</strong> TV series
            </div>
            <div>
                <select class="sort-select" id="sortBy">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Releases</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
            </div>
        </div>

        <!-- Series Grid -->
        @if($series->count() > 0)
            <div class="series-grid" id="seriesGrid">
                @foreach($series as $item)
                    <div class="series-card"
                         data-id="{{ $item->id }}"
                         data-title="{{ $item->title }}"
                         data-poster="{{ $item->poster_path }}"
                         data-year="{{ $item->release_year }}"
                         data-seasons="{{ $item->seasons_count }}"
                         data-genre="{{ $item->genre }}"
                         data-description="{{ $item->description }}">
                        <div class="series-poster-wrap">
                            <img class="series-poster" src="{{ $item->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $item->title }}" loading="lazy">
                            <div class="series-poster-overlay">
                                <div class="series-play-btn">
                                    <i class="fas fa-play" style="margin-left:3px;"></i>
                                </div>
                            </div>
                            <button class="fav-heart" type="button" data-type="series" data-id="{{ $item->id }}" onclick="toggleFavorite(event,'series',{{ $item->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                        </div>
                        <div class="series-info">
                            <div class="series-title">{{ $item->title }}</div>
                            <div class="series-meta">
                                <span><i class="fas fa-calendar"></i> {{ $item->release_year }}</span>
                            </div>
                            <div class="series-meta" style="margin-top: 0.3rem;">
                                <span><i class="fas fa-layer-group"></i> {{ $item->seasons_count }} Seasons</span>
                                <span><i class="fas fa-eye"></i> {{ number_format($item->views ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $series->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-tv"></i>
                <h3>No TV series found</h3>
                <p>Try adjusting your filters or search criteria</p>
                <button class="clear-filters" onclick="clearAllFilters()" style="margin-top: 1rem;">Clear Filters</button>
            </div>
        @endif

        <footer>
            <p>&copy; {{ date('Y') }} {{ setting('site_name', 'MOVIEMAX') }} &mdash; All rights reserved.</p>
        </footer>
    </div>

    <!-- Series Modal -->
    <div id="seriesModal" class="series-modal">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-poster">
                    <img id="modalPoster" src="" alt="">
                </div>
                <div class="modal-info">
                    <h2 id="modalTitle"></h2>
                    <div class="modal-meta">
                        <span><i class="fas fa-calendar"></i> <span id="modalYear"></span></span>
                        <span><i class="fas fa-layer-group"></i> <span id="modalSeasons"></span> Seasons</span>
                        <span><i class="fas fa-tag"></i> <span id="modalGenre"></span></span>
                    </div>
                    <p id="modalDescription"></p>
                    <div class="modal-buttons">
                        <button class="modal-btn-view" id="viewBtn">
                            <i class="fas fa-eye"></i> View Series
                        </button>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
        </div>
    </div>

    <script>
        // ============ MODAL ============
        let currentSeries = null;

        function openSeriesModal(seriesId, title, poster, year, seasons, genre, description) {
            currentSeries = {
                id: seriesId,
                title: title,
                poster: poster,
                year: year,
                seasons: seasons,
                genre: genre,
                description: description
            };

            document.getElementById('modalPoster').src = poster || '/images/posters/dummy-poster.png';
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalYear').innerText = year || 'N/A';
            document.getElementById('modalSeasons').innerText = seasons || 'N/A';
            document.getElementById('modalGenre').innerText = genre || 'General';
            document.getElementById('modalDescription').innerText = description || 'No description available.';

            document.getElementById('seriesModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('seriesModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.getElementById('viewBtn').addEventListener('click', function() {
            if (currentSeries && currentSeries.id) {
                window.location.href = '/series/' + currentSeries.id;
            }
        });

        document.querySelectorAll('.series-card').forEach(card => {
            card.addEventListener('click', function(e) {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const poster = this.getAttribute('data-poster');
                const year = this.getAttribute('data-year');
                const seasons = this.getAttribute('data-seasons');
                const genre = this.getAttribute('data-genre');
                const description = this.getAttribute('data-description');
                openSeriesModal(id, title, poster, year, seasons, genre, description);
            });
        });

        // ============ FAVOURITES ============
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
                    confirmButtonColor: '#3b82f6',
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

        // ============ FILTERS ============
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
            window.location.href = '/series?' + params.toString();
        }

        function clearAllFilters() { window.location.href = '/series'; }

        function displayActiveFilters() {
            const container = document.getElementById('activeFilters');
            const params = new URLSearchParams(window.location.search);
            const filters = [];
            if (params.get('genre')) filters.push({ label: 'Genre: ' + params.get('genre'), key: 'genre' });
            if (params.get('year')) filters.push({ label: 'Year: ' + params.get('year'), key: 'year' });
            if (params.get('search')) filters.push({ label: 'Search: ' + params.get('search'), key: 'search' });
            if (params.get('sort')) filters.push({ label: 'Sort: ' + params.get('sort').replace('_', ' '), key: 'sort' });
            if (filters.length > 0) {
                container.innerHTML = filters.map(f => '<div class="filter-tag">' + f.label + '<i class="fas fa-times" onclick="removeFilter(\'' + f.key + '\')"></i></div>').join('');
            } else {
                container.innerHTML = '<span style="color: var(--text-muted); font-size: 0.7rem;">No active filters</span>';
            }
        }

        function removeFilter(key) {
            const params = new URLSearchParams(window.location.search);
            params.delete(key);
            window.location.href = '/series?' + params.toString();
        }

        document.getElementById('genreFilter').addEventListener('change', updateFilters);
        document.getElementById('yearFilter').addEventListener('change', updateFilters);
        document.getElementById('sortBy').addEventListener('change', updateFilters);

        let searchTimeout;
        document.getElementById('searchInput').addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateFilters, 500);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeModal(); }
        });

        displayActiveFilters();
    </script>
</body>
</html>
