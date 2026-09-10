<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Favorites - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'My Favorites - MOVIEMAX', 'seoDescription' => 'Your saved movies, series and trailers on MOVIEMAX.', 'seoNoindex' => true])
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

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            background: var(--glass-bg);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            background: rgba(10, 13, 18, 0.97);
            padding: 0.7rem 5%;
            box-shadow: 0 4px 30px rgba(0,0,0,0.5);
        }
        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 3px;
            background: var(--text-primary);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            z-index: 1001;
        }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.88rem;
            position: relative;
            padding: 0.2rem 0;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-cyan);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        .nav-links a:hover::after,
        .nav-links a.active::after { width: 100%; }
        .nav-links a:hover,
        .nav-links a.active { color: var(--text-primary); }
        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
            z-index: 1001;
            transition: 0.3s;
        }
        .menu-btn:hover { color: var(--accent-red); }
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            z-index: 999;
        }
        .mobile-overlay.active { display: block; }
        @media (max-width: 768px) {
            .menu-btn { display: block; }
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 75%;
                height: 100vh;
                background: rgba(10, 13, 18, 0.98);
                backdrop-filter: blur(30px);
                -webkit-backdrop-filter: blur(30px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2.5rem;
                transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                z-index: 1000;
                border-left: 1px solid var(--glass-border);
            }
            .nav-links.active { right: 0; }
            .nav-links a { font-size: 1.1rem; }
        }

        /* Hero header */
        .page-header {
            padding: 130px 5% 40px;
            text-align: center;
            position: relative;
        }
        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(229, 9, 20, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 20%, rgba(229, 9, 20, 0.08) 0%, transparent 55%);
        }
        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.4rem, 6vw, 4rem);
            letter-spacing: 4px;
            background: var(--accent-cyan);
            background-size: 250% 250%;
            animation: gradientShift 6s ease infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
        }
        .page-header p {
            color: var(--text-secondary);
            margin-top: 0.8rem;
            position: relative;
            font-size: 1rem;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Sections */
        .section { padding: 2.5rem 5% 3rem; max-width: 1400px; margin: 0 auto; }
        .section-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 2px;
            margin-bottom: 1.6rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .section-header h2 i { color: var(--accent-red); }

        .fav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .fav-card {
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        .fav-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(229, 9, 20, 0.35);
            border-color: rgba(229, 9, 20, 0.4);
        }
        .fav-thumb {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2 / 3;
        }
        .fav-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .fav-card:hover .fav-thumb img { transform: scale(1.08); }
        .fav-heart {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(10, 13, 18, 0.75);
            backdrop-filter: blur(8px);
            border: 1px solid var(--glass-border);
            color: var(--accent-red);
            font-size: 1rem;
            cursor: pointer;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .fav-heart:hover {
            background: rgba(229, 9, 20, 0.2);
            transform: scale(1.1);
        }
        .fav-info { padding: 0.9rem 1rem 1rem; }
        .fav-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fav-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .fav-meta span { display: inline-flex; align-items: center; gap: 0.3rem; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: var(--text-muted); }
        .empty-state h3 { font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; letter-spacing: 2px; color: var(--text-primary); }
        .empty-state p { margin: 0.5rem 0 1.5rem; }
        .empty-state a {
            display: inline-block;
            padding: 0.8rem 1.6rem;
            border-radius: 30px;
            background: var(--accent-red);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        @media (max-width: 600px) {
            .fav-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.8rem; }
            .fav-info { padding: 0.6rem 0.7rem 0.8rem; }
        }

        /* Footer */
        .footer {
            background: var(--bg-surface);
            border-top: 1px solid var(--glass-border);
            padding: 2rem 5%;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    @include('partials.navbar')

    <header class="page-header">
        <h1>My Favorites</h1>
        <p>Movies and series you've saved for later.</p>
    </header>

    @if($movies->isEmpty() && $series->isEmpty())
        <div class="section">
            <div class="empty-state">
                <i class="fas fa-heart"></i>
                <h3>No favorites yet</h3>
                <p>Tap the heart on any movie or series to save it here.</p>
                <a href="/movies"><i class="fas fa-film"></i> Browse Movies</a>
            </div>
        </div>
    @else
        @if($movies->isNotEmpty())
            <section class="section">
                <div class="section-header">
                    <h2><i class="fas fa-film"></i> Favorite Movies ({{ $movies->count() }})</h2>
                </div>
                <div class="fav-grid">
                    @foreach($movies as $movie)
                        <div class="fav-card" onclick="location.href='{{ route('movies.show', $movie->slug) }}'">
                            <div class="fav-thumb">
                                <button class="fav-heart" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event,'movie',{{ $movie->id }},this)" title="Remove from favorites"><i class="fas fa-heart"></i></button>
                                <img src="{{ $movie->poster_path ?? '/images/posters/dummy-movie.png' }}" alt="{{ $movie->title }}">
                            </div>
                            <div class="fav-info">
                                <h4>{{ $movie->title }}</h4>
                                <div class="fav-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_year ?? 'N/A' }}</span>
                                    <span><i class="fas fa-tag"></i> {{ $movie->genre ?? 'General' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($series->isNotEmpty())
            <section class="section">
                <div class="section-header">
                    <h2><i class="fas fa-tv"></i> Favorite Series ({{ $series->count() }})</h2>
                </div>
                <div class="fav-grid">
                    @foreach($series as $item)
                        <div class="fav-card" onclick="location.href='{{ route('series.show', $item->id) }}'">
                            <div class="fav-thumb">
                                <button class="fav-heart" type="button" data-type="series" data-id="{{ $item->id }}" onclick="toggleFavorite(event,'series',{{ $item->id }},this)" title="Remove from favorites"><i class="fas fa-heart"></i></button>
                                <img src="{{ $item->poster_path ?? '/images/posters/dummy-series.png' }}" alt="{{ $item->title }}">
                            </div>
                            <div class="fav-info">
                                <h4>{{ $item->title }}</h4>
                                <div class="fav-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ $item->release_year ?? 'N/A' }}</span>
                                    <span><i class="fas fa-layer-group"></i> {{ $item->seasons_count }} Seasons</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endif

    <footer class="footer">
        <p>&copy; {{ date('Y') }} {{ setting('site_name', 'MOVIEMAX') }} &mdash; All rights reserved.</p>
    </footer>

    <script>
        // ============ FAVORITES ============
        function csrfToken() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.content : '';
        }
        async function jsonPost(url, payload) {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            return res.json();
        }
        async function toggleFavorite(e, type, id, btn) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            try {
                const data = await jsonPost('/interactions/favorite-toggle', { type: type, id: id });
                if (!data.favorited) {
                    const card = btn.closest('.fav-card');
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => card.remove(), 300);
                }
            } catch (err) {}
        }
    </script>
</body>
</html>