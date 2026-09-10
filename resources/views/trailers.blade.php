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
            padding-top: 68px;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }
        body.loaded { opacity: 1; }

        .trailer-hero {
            position: relative;
            padding: 4rem 5% 3rem;
            text-align: center;
            background:
                radial-gradient(1000px 400px at 20% -10%, rgba(229, 9, 20, 0.25), transparent 60%),
                radial-gradient(800px 350px at 80% 10%, rgba(229, 9, 20, 0.12), transparent 60%),
                var(--bg-deep);
            border-bottom: 1px solid var(--glass-border);
        }
        .trailer-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.4rem, 5vw, 4rem);
            letter-spacing: 4px;
            background: var(--accent-cyan);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.8rem;
        }
        .trailer-hero p { color: var(--text-secondary); max-width: 620px; margin: 0 auto 1.4rem; font-size: 0.95rem; }
        .hero-count-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 0.82rem;
        }
        .hero-search {
            margin-top: 1.6rem;
            display: flex;
            justify-content: center;
        }
        .hero-search form {
            display: flex;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            padding: 0.3rem;
            max-width: 480px;
            width: 100%;
        }
        .hero-search input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.55rem 1rem;
        }
        .hero-search button {
            background: var(--accent-red);
            border: none;
            color: #fff;
            border-radius: 50px;
            padding: 0.55rem 1.3rem;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: inherit;
        }

        .container { max-width: 1300px; margin: 0 auto; padding: 2.5rem 5% 3rem; }

        .section-head {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1.4rem;
            font-size: 1.4rem;
            font-weight: 700;
        }
        .section-head i { color: var(--accent-cyan); }

        .featured-trailer {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            margin-bottom: 2.8rem;
            display: block;
            text-decoration: none;
            background: var(--bg-card);
            box-shadow: 0 20px 60px rgba(0,0,0,0.45);
        }
        .featured-trailer img {
            width: 100%;
            aspect-ratio: 21/9;
            object-fit: cover;
            display: block;
            filter: brightness(0.75);
            transition: transform 0.5s ease;
        }
        .featured-trailer:hover img { transform: scale(1.03); }
        .featured-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(10, 13, 18, 0.92) 0%, rgba(10, 13, 18, 0.35) 60%, transparent 100%);
            display: flex;
            align-items: center;
        }
        .featured-content { padding: 2rem 2.2rem; max-width: 470px; }
        .featured-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--accent-cyan);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.7rem;
        }
        .featured-content h2 { font-size: 1.7rem; margin-bottom: 0.5rem; }
        .featured-content p { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.5; }
        .featured-btn {
            margin-top: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1.3rem;
            border-radius: 40px;
            background: var(--accent-red);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(229, 9, 20, 0.4);
        }
        @media (max-width: 600px) {
            .featured-content { padding: 1.2rem; max-width: 100%; }
            .featured-content h2 { font-size: 1.1rem; }
            .featured-trailer img { aspect-ratio: 16/9; }
        }

        .trailer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
            transition: all 0.35s ease;
            position: relative;
        }
        .trailer-card:hover { transform: translateY(-6px); border-color: rgba(229, 9, 20, 0.5); box-shadow: 0 18px 45px rgba(0,0,0,0.5); }
        .thumb-wrap { position: relative; overflow: hidden; }
        .thumb-wrap img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; transition: transform 0.5s ease; }
        .trailer-card:hover .thumb-wrap img { transform: scale(1.08); }
        .thumb-play {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 54px; height: 54px;
            border-radius: 50%;
            background: rgba(229, 9, 20, 0.92);
            border: 2px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.5);
            transition: all 0.35s ease;
        }
        .trailer-card:hover .thumb-play { background: var(--accent-cyan); transform: translate(-50%, -50%) scale(1.12); }
        .trailer-info { padding: 0.95rem 1rem 1.1rem; }
        .trailer-info h3 {
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .trailer-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .trailer-meta span { display: inline-flex; align-items: center; gap: 4px; }

        .pagination-wrap { margin-top: 2.5rem; display: flex; justify-content: center; }
        .pagination-wrap nav { display: flex; gap: 0.4rem; }
        .pagination-wrap a, .pagination-wrap span.disabled, .pagination-wrap .pagination .page-link, .pagination-wrap .page-item {
            color: var(--text-secondary);
            text-decoration: none;
        }
        .pagination { display: flex; gap: 0.4rem; list-style: none; }
        .pagination .page-item .page-link {
            display: inline-flex;
            min-width: 38px;
            height: 38px;
            padding: 0 0.8rem;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .pagination .page-item.active .page-link { background: var(--accent-red); color: #fff; border-color: transparent; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; }
        .pagination .page-link:hover:not(.active) { border-color: rgba(229, 9, 20, 0.5); color: var(--text-primary); }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; }

        @media (max-width: 560px) {
            .trailer-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.9rem; }
            .trailer-info { padding: 0.7rem 0.8rem 0.9rem; }
            .trailer-info h3 { font-size: 0.8rem; }
        }
    </style>
</head>
<body>
@include('partials.navbar')

<div class="trailer-hero">
    <h1>Official Trailers</h1>
    <p>Watch the latest trailers for the hottest movies and TV series on MOVIEMAX, live your favourites and join the conversation.</p>
    <div class="hero-count-tag">
        <i class="fas fa-video" style="color: var(--accent-cyan);"></i>
        {{ $trailers->total() }} trailer{{ $trailers->total() == 1 ? '' : 's' }} available
    </div>
    <div class="hero-search">
        <form method="GET" action="{{ route('trailers') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trailers...">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
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
                    <p>{{ \Illuminate\Support\Str::limit($first->description, 130) }}</p>
                    <span class="featured-btn"><i class="fas fa-play"></i> Watch Trailer</span>
                </div>
            </div>
        </a>
    @endif

    <div class="section-head"><i class="fas fa-film"></i> All Trailers</div>

    @if($trailers->count())
        <div class="trailer-grid">
            @foreach($trailers as $trailer)
                <a href="{{ route('trailers.show', $trailer->id) }}" class="trailer-card">
                    <div class="thumb-wrap">
                        <img src="{{ $trailer->thumb_url }}" alt="{{ $trailer->title }}" loading="lazy">
                        <div class="thumb-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="trailer-info">
                        <h3>{{ $trailer->title }}</h3>
                        <div class="trailer-meta">
                            <span><i class="fas fa-eye"></i> {{ number_format($trailer->views) }}</span>
                            <span><i class="fas fa-thumbs-up"></i> {{ $trailer->likesCount() }}</span>
                            <span><i class="fas fa-comment"></i> {{ $trailer->comments()->whereNull('parent_id')->count() }}</span>
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
            <p>No trailers found{{ request('search') ? ' for "' . e(request('search')) . '"' : '' }}.</p>
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