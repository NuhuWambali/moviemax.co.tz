{{-- resources/views/movies/genre.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $genre }} - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => ($genre ?? 'Genre') . ' - MOVIEMAX', 'seoDescription' => 'Browse ' . ($genre ?? '') . ' movies and TV series on MOVIEMAX. Stream and download the latest ' . ($genre ?? '') . ' titles in HD.'])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #3b82f6;
            --accent-red-dark: #2563eb;
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

        .container { max-width: 1600px; margin: 0 auto; padding: 100px 5% 3rem; }
        .page-header { margin-bottom: 2rem; }
        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif; font-size: 2.8rem; letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }
        .page-header h1 i { color: var(--accent-red); margin-right: 0.5rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.95rem; }

        .section-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.6rem; letter-spacing: 2px;
            margin: 2rem 0 1rem; display: flex; align-items: center; gap: 0.75rem;
        }
        .section-title::after {
            content: ''; flex: 1; height: 1px;
            background: var(--accent-red);
        }

        .movies-grid {
            display: grid; grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem; margin-bottom: 2rem;
        }
        .movie-card {
            background: var(--bg-card); border-radius: 16px; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer; position: relative;
            display: block; color: var(--text-primary); text-decoration: none;
        }
        .movie-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.2);
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
            font-size: 1.1rem; color: white; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
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

        .pro-pagination {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 1rem; margin-top: 2rem; padding: 1rem;
            background: var(--bg-card); border-radius: 16px; border: 1px solid var(--glass-border);
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
            border-color: transparent; color: white; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .page-dots { color: var(--text-muted); padding: 0 0.25rem; }

        .empty-state {
            text-align: center; padding: 4rem;
            background: var(--bg-card); border-radius: 24px; border: 1px solid var(--glass-border);
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

        @media (max-width: 1100px) {
            .movies-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 768px) {
            .movies-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tag"></i> {{ $genre }}</h1>
            <p>Movies and series in the {{ $genre }} genre</p>
        </div>

        @if($series->count() > 0)
            <h2 class="section-title"><i class="fas fa-tv"></i> TV Series</h2>
            <div class="movies-grid">
                @foreach($series as $item)
                    <a class="movie-card" href="/series/{{ $item->id }}">
                        <div class="card-img-wrap">
                            <img class="movie-poster" src="{{ $item->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $item->title }}">
                            <div class="card-overlay"><i class="fas fa-play"></i></div>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title">{{ $item->title }}</div>
                            <div class="movie-meta">
                                <span><i class="fas fa-calendar-alt"></i> {{ $item->release_year }}</span>
                                <span><i class="fas fa-download"></i> {{ number_format($item->download_count ?? 0) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <h2 class="section-title"><i class="fas fa-film"></i> Movies</h2>

        @if($movies->count() > 0)
            <div class="movies-grid">
                @foreach($movies as $movie)
                    <a class="movie-card" href="/movies/{{ $movie->slug }}">
                        <div class="card-img-wrap">
                            <img class="movie-poster" src="{{ $movie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="card-overlay"><i class="fas fa-play"></i></div>
                            <button class="fav-heart" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event,'movie',{{ $movie->id }},this)" title="Add to favorites" aria-label="Add {{ $movie->title }} to favorites"><i class="fas fa-heart"></i></button>
                        </div>
                        <div class="movie-info">
                            <div class="movie-title">{{ $movie->title }}</div>
                            <div class="movie-meta">
                                <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_year }}</span>
                            </div>
                            <div class="movie-meta" style="margin-top: 0.3rem;">
                                <span><i class="fas fa-clock"></i> {{ $movie->duration }}</span>
                                <span><i class="fas fa-eye"></i> {{ number_format($movie->views ?? 0) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($movies->hasPages())
            <div class="pro-pagination">
                <div class="pagination-info">Showing {{ $movies->firstItem() }} to {{ $movies->lastItem() }} of {{ $movies->total() }} results</div>
                <div class="pagination-links">
                    @if(!$movies->onFirstPage())
                        <a href="{{ $movies->url($movies->currentPage() - 1) }}" class="page-link">&laquo;</a>
                    @endif
                    @for($i = 1; $i <= $movies->lastPage(); $i++)
                        @if($i == $movies->currentPage())
                            <span class="page-link active">{{ $i }}</span>
                        @else
                            <a href="{{ $movies->url($i) }}" class="page-link">{{ $i }}</a>
                        @endif
                    @endfor
                    @if($movies->hasMorePages())
                        <a href="{{ $movies->nextPageUrl() }}" class="page-link">&raquo;</a>
                    @endif
                </div>
            </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-film"></i>
                <h3>No movies found in this genre yet</h3>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">Check back soon or browse all <a href="/movies" style="color: var(--accent-red);">movies</a>.</p>
            </div>
        @endif

        <footer>
            <div class="footer-brand">@if(setting("logo_path"))<img src="{{ setting("logo_path") }}" alt="{{ setting("site_name", "MOVIEMAX") }}" class="footer-logo footer-logo"/@else{{ setting("site_name", "MOVIEMAX") }}@endif</div>
            <p>&copy; {{ date('Y') }} MOVIEMAX &mdash; All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mobile menu
        const navLinks = document.getElementById('navLinks');
        function toggleMenu() {
            navLinks.classList.toggle('active');
            document.getElementById('mobileOverlay').classList.toggle('active');
            const menuBtn = document.querySelector('.menu-btn i');
            if (navLinks.classList.contains('active')) { menuBtn.classList.remove('fa-bars'); menuBtn.classList.add('fa-times'); }
            else { menuBtn.classList.remove('fa-times'); menuBtn.classList.add('fa-bars'); }
        }
        document.querySelectorAll('.nav-links a').forEach(link => link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            document.getElementById('mobileOverlay').classList.remove('active');
            const menuBtn = document.querySelector('.menu-btn i');
            menuBtn.classList.remove('fa-times'); menuBtn.classList.add('fa-bars');
        }));
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) nav.classList.add('scrolled'); else nav.classList.remove('scrolled');
        });

        // Guest login prompt + favourites
        function csrfToken() { const m = document.querySelector('meta[name="csrf-token"]'); return m ? m.content : ''; }
        function handleLoginRequired(data) {
            if (data && data.error === 'login_required') {
                if (window.__loginPromptShown) return true;
                window.__loginPromptShown = true;
                Swal.fire({
                    title: 'Login Required',
                    text: data.message || 'Please login or create an account to continue.',
                    icon: 'warning', background: '#161c26', color: '#fff',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Login Now',
                    showCancelButton: true, cancelButtonText: 'Cancel', cancelButtonColor: '#6b7280'
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
    </script>
</body>
</html>