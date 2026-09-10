<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trailers - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'Official Trailers - MOVIEMAX', 'seoDescription' => 'Watch the latest official movie and TV series trailers on MOVIEMAX. Discover upcoming releases in HD.'])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --accent-purple: #ffd700;
            --accent-cyan: #ffd700;
            --text-primary: #ffffff;
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
            padding-top: 68px;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }
        body.loaded { opacity: 1; }

        /* ============ HERO ============ */
        .trailer-hero {
            position: relative;
            overflow: hidden;
            padding: 4.5rem 5% 3.5rem;
            text-align: center;
            background:
                radial-gradient(900px 420px at 15% -20%, rgba(229, 9, 20, 0.30), transparent 60%),
                radial-gradient(700px 400px at 85% 5%, rgba(229, 9, 20, 0.14), transparent 60%),
                var(--bg-deep);
            border-bottom: 1px solid var(--glass-border);
        }
        .trailer-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.6rem, 5vw, 4rem);
            letter-spacing: 4px;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            text-shadow: 0 4px 30px rgba(229, 9, 20, 0.25);
        }
        .trailer-hero h1 .accent { color: var(--accent-red); }
        .trailer-hero p { color: var(--text-secondary); max-width: 600px; margin: 0 auto 1.5rem; font-size: 0.97rem; line-height: 1.7; }
        .hero-count-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 0.84rem;
        }
        .hero-count-tag i { color: var(--accent-purple); }
        .hero-search {
            margin-top: 1.7rem;
            display: flex;
            justify-content: center;
        }
        .hero-search form {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            padding: 0.4rem 0.4rem 0.4rem 1.1rem;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
        }
        .hero-search form:focus-within { border-color: rgba(229, 9, 20, 0.6); }
        .hero-search i { color: var(--text-muted); font-size: 0.85rem; }
        .hero-search input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.5rem 0;
        }
        .hero-search input::placeholder { color: var(--text-muted); }
        .hero-search button {
            background: var(--accent-red);
            border: none;
            color: #fff;
            border-radius: 50px;
            padding: 0.6rem 1.4rem;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: filter 0.25s ease, transform 0.25s ease;
        }
        .hero-search button:hover { filter: brightness(1.12); transform: translateY(-1px); }

        .container { max-width: 1280px; margin: 0 auto; padding: 2.8rem 5% 3rem; }

        .section-head {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1.5rem;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 1px;
            color: var(--text-primary);
        }
        .section-head i { color: var(--accent-red); }
        .section-head .line { flex: 1; height: 1px; background: linear-gradient(90deg, var(--glass-border), transparent); margin-left: 0.3rem; }

        /* ============ FEATURED ============ */
        .featured-trailer {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            margin-bottom: 2.8rem;
            display: block;
            text-decoration: none;
            background: var(--bg-card);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.5);
        }
        .featured-trailer img {
            width: 100%;
            aspect-ratio: 21/9;
            object-fit: cover;
            display: block;
            filter: brightness(0.6);
            transition: transform 0.6s ease, filter 0.4s ease;
        }
        .featured-trailer:hover img { transform: scale(1.04); filter: brightness(0.68); }
        .featured-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(10, 13, 18, 0.94) 0%, rgba(10, 13, 18, 0.55) 45%, transparent 80%),
                        linear-gradient(to top, rgba(10, 13, 18, 0.55) 0%, transparent 50%);
            display: flex;
            align-items: center;
        }
        .featured-content { padding: 2.2rem 2.4rem; max-width: 540px; }
        .featured-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--accent-purple);
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 0.9rem;
            padding: 0.3rem 0.9rem;
            border-radius: 30px;
            background: rgba(255, 215, 0, 0.12);
            border: 1px solid rgba(255, 215, 0, 0.3);
        }
        .featured-content h2 { font-size: 1.8rem; line-height: 1.25; margin-bottom: 0.7rem; color: var(--text-primary); }
        .featured-content p { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; }
        .featured-meta {
            display: flex;
            gap: 1.1rem;
            margin-top: 1rem;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
        .featured-meta span { display: inline-flex; align-items: center; gap: 6px; }
        .featured-meta span i { color: var(--accent-purple); }
        .featured-btn {
            margin-top: 1.3rem;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            background: var(--accent-red);
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.45);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }
        .featured-btn:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 14px 36px rgba(229, 9, 20, 0.55); }

        @media (max-width: 700px) {
            .featured-content { padding: 1.2rem; max-width: 100%; }
            .featured-content h2 { font-size: 1.2rem; }
            .featured-trailer img { aspect-ratio: 16/10; }
            .featured-overlay { background: linear-gradient(to top, rgba(10, 13, 18, 0.95) 0%, rgba(10, 13, 18, 0.2) 70%); align-items: flex-end; }
        }

        /* ============ GRID ============ */
        .trailer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.4rem;
        }
        .trailer-card {
            display: block;
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            text-decoration: none;
            color: inherit;
            transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        }
        .trailer-card:hover {
            transform: translateY(-6px);
            border-color: rgba(229, 9, 20, 0.55);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.55);
        }
        .thumb-wrap { position: relative; overflow: hidden; aspect-ratio: 16/9; background: var(--bg-surface); }
        .thumb-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.55s ease;
        }
        .trailer-card:hover .thumb-wrap img { transform: scale(1.08); }
        .thumb-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10, 13, 18, 0.7) 0%, transparent 55%);
            pointer-events: none;
        }
        .thumb-play {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 52px; height: 52px;
            border-radius: 50%;
            background: rgba(229, 9, 20, 0.92);
            border: 2px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.05rem;
            box-shadow: 0 8px 28px rgba(229, 9, 20, 0.5);
            transition: transform 0.35s ease, background 0.35s ease;
        }
        .trailer-card:hover .thumb-play {
            background: var(--accent-purple);
            color: #111;
            transform: translate(-50%, -50%) scale(1.1);
        }
        .trailer-badge {
            position: absolute;
            top: 0.7rem;
            left: 0.7rem;
            z-index: 2;
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.28rem 0.7rem;
            border-radius: 30px;
            background: rgba(229, 9, 20, 0.92);
            color: #fff;
        }
        .trailer-info { padding: 1rem 1.05rem 1.15rem; }
        .trailer-info h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.65rem;
            line-height: 1.4;
            color: var(--text-primary);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .trailer-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            font-size: 0.76rem;
            color: var(--text-muted);
        }
        .trailer-meta span { display: inline-flex; align-items: center; gap: 5px; }
        .trailer-meta span.views i { color: var(--accent-red); }
        .trailer-meta span.likes i { color: var(--accent-purple); }

        /* ============ TRENDING STRIP ============ */
        .trending-row {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            padding-bottom: 1rem;
            scrollbar-width: thin;
            scrollbar-color: var(--accent-red) transparent;
        }
        .trending-row::-webkit-scrollbar { height: 6px; }
        .trending-row::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }
        .trending-item {
            flex: 0 0 290px;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.8rem;
            border-radius: 14px;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            text-decoration: none;
            color: inherit;
            transition: border-color 0.3s ease, transform 0.3s ease, background 0.3s ease;
        }
        .trending-item:hover {
            transform: translateY(-3px);
            border-color: rgba(229, 9, 20, 0.55);
            background: #1a212d;
        }
        .trending-thumb {
            position: relative;
            flex-shrink: 0;
            width: 100px;
            height: 58px;
            border-radius: 10px;
            overflow: hidden;
        }
        .trending-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .trending-thumb .mini-play {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.35);
            color: #fff;
            font-size: 0.75rem;
        }
        .trending-info { min-width: 0; }
        .trending-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .trending-info .views {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.3rem;
        }
        .trending-info .views i { color: var(--accent-red); }
        .trending-rank {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            color: var(--accent-purple);
            flex-shrink: 0;
        }

        /* ============ PAGINATION / EMPTY ============ */
        .pagination-wrap { margin-top: 2.5rem; display: flex; justify-content: center; }
        .pagination { display: flex; gap: 0.4rem; list-style: none; }
        .pagination .page-item .page-link {
            display: inline-flex;
            min-width: 40px;
            height: 40px;
            padding: 0 0.9rem;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .pagination .page-item.active .page-link { background: var(--accent-red); color: #fff; border-color: transparent; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; }
        .pagination .page-link:hover:not(.active) { border-color: rgba(229, 9, 20, 0.5); color: var(--text-primary); }

        .empty-state {
            text-align: center;
            padding: 4.5rem 2rem;
            color: var(--text-muted);
            background: var(--bg-card);
            border: 1px dashed var(--glass-border);
            border-radius: 18px;
        }
        .empty-state i { font-size: 2.6rem; margin-bottom: 1.1rem; color: var(--accent-red); opacity: 0.7; }
        .empty-state p { font-size: 0.95rem; color: var(--text-secondary); }
        .empty-state a { color: var(--accent-purple); text-decoration: none; }

        @media (max-width: 1100px) { .trailer-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 860px)  { .trailer-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px)  { .trailer-grid { grid-template-columns: repeat(2, 1fr); gap: 0.9rem; } .trailer-info h3 { font-size: 0.8rem; } .trending-item { flex: 0 0 250px; } }
    </style>
</head>
<body>
@include('partials.navbar')

<div class="trailer-hero">
    <h1>Official <span class="accent">Trailers</span></h1>
    <p>Watch the latest trailers for the hottest movies and TV series on MOVIEMAX, live your favourites and join the conversation.</p>
    <div class="hero-count-tag">
        <i class="fas fa-video"></i>
        {{ $trailers->total() }} trailer{{ $trailers->total() == 1 ? '' : 's' }} available
    </div>
    <div class="hero-search">
        <form method="GET" action="{{ route('trailers') }}">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trailers...">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<div class="container">
    @php $first = $trailers->first(); @endphp
    @if($first)
        <a href="{{ route('trailers.show', $first->id) }}" class="featured-trailer">
            <img src="{{ $first->thumb_url }}" alt="{{ $first->title }}" fetchpriority="high">
            <div class="featured-overlay">
                <div class="featured-content">
                    <span class="featured-label"><i class="fas fa-star"></i> Featured Trailer</span>
                    <h2>{{ $first->title }}</h2>
                    @if($first->description)
                        <p>{{ \Illuminate\Support\Str::limit($first->description, 150) }}</p>
                    @endif
                    <div class="featured-meta">
                        <span><i class="fas fa-eye"></i> {{ number_format($first->views) }} views</span>
                        <span><i class="fas fa-thumbs-up"></i> {{ $first->likesCount() }} likes</span>
                        <span><i class="fas fa-calendar-alt"></i> {{ $first->created_at->diffForHumans() }}</span>
                    </div>
                    <span class="featured-btn"><i class="fas fa-play"></i> Watch Trailer</span>
                </div>
            </div>
        </a>
    @endif

    @if($trailers->count() > 0)
        <div class="section-head"><i class="fas fa-film"></i> All Trailers <span class="line"></span></div>
        <div class="trailer-grid">
            @foreach($trailers as $trailer)
                <a href="{{ route('trailers.show', $trailer->id) }}" class="trailer-card">
                    <div class="thumb-wrap">
                        <span class="trailer-badge">Trailer</span>
                        <img src="{{ $trailer->thumb_url }}" alt="{{ $trailer->title }}" loading="lazy">
                        <div class="thumb-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="trailer-info">
                        <h3>{{ $trailer->title }}</h3>
                        <div class="trailer-meta">
                            <span class="views"><i class="fas fa-eye"></i> {{ number_format($trailer->views) }}</span>
                            <span class="likes"><i class="fas fa-thumbs-up"></i> {{ $trailer->likesCount() }}</span>
                            <span><i class="fas fa-comments"></i> {{ $trailer->comments()->whereNull('parent_id')->count() }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $trailers->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-video-slash"></i>
            <p>@if(request('search'))
                    No trailers found for "{{ e(request('search')) }}".
                    <br><a href="{{ route('trailers') }}">Clear search</a>
                @else
                    No trailers available yet. Check back soon!
                @endif
            </p>
        </div>
    @endif

    @if($trendingTrailers->count() > 0 && $trendingTrailers->count() !== $trailers->count())
        <div class="section-head" style="margin-top: 2.5rem;"><i class="fas fa-fire"></i> Trending Trailers <span class="line"></span></div>
        <div class="trending-row">
            @foreach($trendingTrailers as $i => $trending)
                <a href="{{ route('trailers.show', $trending->id) }}" class="trending-item">
                    <span class="trending-rank">{{ $i + 1 }}</span>
                    <span class="trending-thumb">
                        <img src="{{ $trending->thumb_url }}" alt="{{ $trending->title }}" loading="lazy">
                        <span class="mini-play"><i class="fas fa-play"></i></span>
                    </span>
                    <span class="trending-info">
                        <h4>{{ $trending->title }}</h4>
                        <span class="views"><i class="fas fa-eye"></i> {{ number_format($trending->views) }} views</span>
                    </span>
                </a>
            @endforeach
        </div>
    @endif
</div>

<style>
    .footer {
        background: var(--bg-surface);
        border-top: 1px solid var(--glass-border);
        padding: 2rem 5%;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .footer .logo { font-family: 'Bebas Neue', sans-serif; font-size: 1.6rem; letter-spacing: 3px; background: #e50914; -webkit-background-clip: text; background-clip: text; color: transparent; }
    .footer-links { display: flex; justify-content: center; gap: 2rem; margin: 1rem 0 0.5rem; flex-wrap: wrap; }
    .footer-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.8rem; transition: color 0.3s; }
    .footer-links a:hover { color: var(--accent-cyan); }
</style>
<div class="footer">
    <div class="logo">MOVIEMAX</div>
    <div class="footer-links">
        <a href="/">Home</a>
        <a href="/movies">Movies</a>
        <a href="/series">TV Series</a>
        <a href="/trailers">Trailers</a>
        <a href="{{ route('about') }}">About</a>
    </div>
    <p>&copy; {{ date('Y') }} {{ setting('site_name', 'MOVIEMAX') }}. All rights reserved.</p>
</div>
</body>
</html>