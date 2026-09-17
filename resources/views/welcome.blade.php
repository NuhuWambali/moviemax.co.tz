<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieMax – Watch Free Movie Trailers Online in HD</title>
    @include('partials.seo', ['seoTitle' => 'MovieMax – Watch Free Movie Trailers Online in HD', 'seoDescription' => 'MovieMax lets you watch the latest movie trailers online in HD. Discover new release trailers, trending teasers and the most anticipated films all in one place.'])
    @php
        $heroSlidesCollection = ($heroSlides ?? collect())->toBase();
        $slides = $heroSlidesCollection->count() > 0 ? $heroSlidesCollection : collect([null]);
        $slidesTrailers = collect();
        if ($heroSlidesCollection->count() > 0) {
            $slidesTrailers = \App\Models\Trailer::whereIn('id', $heroSlidesCollection->pluck('link_id')->filter()->unique()->all())->get()->keyBy('id');
        }
        $slideCount = $slides->count();
    @endphp
    @if(!empty($heroSlidesCollection->first()->image_path ?? null))
        <link rel="preload" as="image" href="{{ $heroSlidesCollection->first()->image_path }}" fetchpriority="high">
    @endif
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --gold: #ffd24a;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-bg: rgba(10, 13, 18, 0.75);
            --glass-border: rgba(255, 255, 255, 0.11);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-surface); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        /* ================= HERO ================= */
        .mm-hero {
            position: relative;
            min-height: 82vh;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
            background: #05070a;
        }
        .hero-media {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center 20%;
            transform: scale(1.02);
            transition: transform 7s ease;
        }
        .hero-media.zoom { transform: scale(1.12); }
        .hero-fall { position: absolute; inset: 0; background: radial-gradient(1200px 600px at 70% 20%, rgba(229,9,20,0.22), transparent 60%), linear-gradient(160deg, rgba(5,7,10,0.35) 0%, transparent 55%); }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, var(--bg-deep) 2%, rgba(5,7,10,0.86) 30%, rgba(5,7,10,0.35) 60%, rgba(5,7,10,0.55) 100%); }
        .hero-content {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 5% 6vh;
            transform: translateY(14px);
            animation: heroIn 0.9s cubic-bezier(.18,.89,.32,1.18) forwards;
        }
        @keyframes heroIn { to { transform: translateY(0); opacity: 1; } }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            background: rgba(255, 210, 74, 0.12);
            border: 1px solid rgba(255, 210, 74, 0.4);
            padding: 0.42rem 0.95rem;
            border-radius: 40px;
            margin-bottom: 1rem;
            backdrop-filter: blur(6px);
        }
        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.8rem, 8vw, 5.6rem);
            line-height: 0.98;
            letter-spacing: 3px;
            max-width: 900px;
        }
        .hero-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.7rem;
            margin-top: 1rem;
        }
        .hero-chip {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.85rem;
            border-radius: 30px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .hero-chip.gold { color: var(--gold); border-color: rgba(255, 210, 74, 0.45); background: rgba(255, 210, 74, 0.1); }
        .hero-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.65;
            max-width: 600px;
            margin-top: 1.1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hero-actions { display: flex; align-items: center; gap: 0.8rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .hero-watch {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0.85rem 1.7rem;
            border-radius: 40px;
            background: var(--accent-red);
            color: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            box-shadow: 0 12px 34px rgba(229, 9, 20, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .hero-watch:hover { transform: translateY(-2px); filter: brightness(1.12); box-shadow: 0 16px 42px rgba(229, 9, 20, 0.55); }
        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0.85rem 1.5rem;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .hero-btn:hover { border-color: rgba(229, 9, 20, 0.6); background: rgba(229, 9, 20, 0.14); }
        .hero-btn .fav-on { display: none; }
        .hero-btn.saved .fav-on { display: inline; }
        .hero-btn.saved .fav-off, .hero-btn.saved .fav-label { display: none; }
        .hero-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1px solid var(--glass-border);
            background: rgba(10, 13, 18, 0.55);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .hero-nav:hover { background: var(--accent-red); border-color: transparent; }
        .hero-nav.prev { left: 1.4rem; }
        .hero-nav.next { right: 1.4rem; }
        .hero-dots { position: absolute; bottom: 1.6rem; left: 50%; transform: translateX(-50%); z-index: 5; display: flex; gap: 0.5rem; }
        .hero-dot {
            width: 26px; height: 4px;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.25);
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }
        .hero-dot.active { background: var(--accent-red); width: 42px; }
        @keyframes heroFade { from { opacity: 0; } to { opacity: 1; } }

        /* ================= SECTIONS / CAROUSELS ================= */
        .mm-section { max-width: 1500px; margin: 0 auto; padding: 2.2rem 3% 0.4rem; }
        .section-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 1.15rem;
        }
        .section-header .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.2), rgba(229, 9, 20, 0.05));
            border: 1px solid rgba(229, 9, 20, 0.35);
            color: var(--gold);
            font-size: 1rem;
            flex-shrink: 0;
        }
        .section-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.65rem;
            letter-spacing: 2px;
            color: var(--text-primary);
        }
        .section-header a.view-all {
            margin-left: auto;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: color 0.25s;
            white-space: nowrap;
        }
        .section-header a.view-all:hover { color: var(--gold); }

        .carousel { position: relative; }
        .carousel-track {
            display: flex;
            gap: 1.1rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 0.15rem 0.2rem 0.6rem;
            scrollbar-width: none;
        }
        .carousel-track::-webkit-scrollbar { display: none; }
        .carousel-item {
            flex: 0 0 auto;
            width: 250px;
            scroll-snap-align: start;
        }
        .carousel-btn {
            position: absolute;
            top: 42%;
            transform: translateY(-50%);
            z-index: 4;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--glass-border);
            background: rgba(10, 13, 18, 0.7);
            backdrop-filter: blur(8px);
            color: #fff;
            cursor: pointer;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .carousel-btn:hover { background: var(--accent-red); border-color: transparent; }
        .carousel-btn.prev { left: -14px; }
        .carousel-btn.next { right: -14px; }

        /* Genre chips */
        .genre-chips { display: flex; flex-wrap: wrap; gap: 0.6rem; padding: 0.2rem 0 0.6rem; }
        .genre-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1.15rem;
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.045);
            color: var(--text-secondary);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .genre-chip:hover {
            border-color: rgba(229, 9, 20, 0.55);
            background: rgba(229, 9, 20, 0.12);
            color: #fff;
            transform: translateY(-2px);
        }
        .genre-chip span { color: var(--gold); font-size: 0.68rem; }

        /* Trailer modal */
        .trailer-modal { display: none; position: fixed; inset: 0; z-index: 4000; align-items: center; justify-content: center; padding: 1rem; }
        .trailer-modal.open { display: flex; }
        .modal-backdrop { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(6px); animation: heroFade 0.25s ease; }
        .modal-box {
            position: relative;
            width: min(980px, 100%);
            background: #000;
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            overflow: hidden;
            animation: heroFade 0.3s ease;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.7);
        }
        .modal-box iframe { width: 100%; aspect-ratio: 16/9; display: block; border: 0; }
        .modal-box video { width: 100%; aspect-ratio: 16/9; display: block; border: 0; background: #000; }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .modal-close:hover { background: var(--accent-red); }

        /* Empty row */
        .row-empty {
            padding: 2.6rem 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.88rem;
            border: 1px dashed var(--glass-border);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.02);
        }
        .row-empty i { font-size: 1.8rem; display: block; margin-bottom: 0.7rem; color: var(--accent-red); opacity: 0.6; }

        @media (max-width: 900px) {
            .mm-hero { min-height: 68vh; }
            .hero-nav { display: none; }
            .carousel-item { width: 195px; }
            .carousel-btn { display: none; }
        }
        @media (max-width: 560px) {
            .mm-hero { min-height: 64vh; }
            .hero-actions { gap: 0.6rem; }
            .hero-watch, .hero-btn { padding: 0.72rem 1.15rem; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

<!-- ==================== HERO ==================== -->
<div class="mm-hero" id="mmHero">
    @foreach($slides as $i => $slide)
        @php
            $isFallback = $slide === null;
            $linkedTrailer = $isFallback ? $featuredTrailer : ($slideT = $slidesTrailers->get($slide->link_id) ?? null);
            $bgImage = $isFallback
                ? ($featuredTrailer->thumb_url ?? '/images/heroes/hero-3.png')
                : ($slide->image_path ?? ($linkedTrailer->thumb_url ?? '/images/heroes/hero-3.png'));
            $title = $isFallback ? ($featuredTrailer->title ?? 'Discover Trailers') : $slide->title;
            $tagline = $isFallback ? ($featuredTrailer->description ?? 'The best place to discover, watch and save movie trailers.') : $slide->tagline;
            $trailerForActions = $isFallback ? $featuredTrailer : $linkedTrailer;
        @endphp
        <div class="hero-slide" data-idx="{{ $i }}" style="{{ $i === 0 ? '' : 'display:none;' }}">
            <div class="hero-media" style="background-image:url('{{ $bgImage }}')"></div>
            <div class="hero-fall"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge"><i class="fas fa-bolt"></i> {{ $isFallback ? ($featuredTrailer && $featuredTrailer->trailer_of_the_day ? 'Trailer of the Day' : 'Featured on MovieMax') : 'Featured on MovieMax' }}</span>
                <h1 class="hero-title">{{ $title }}</h1>
                <div class="hero-meta">
                    @if($trailerForActions)
                        @if($trailerForActions->type_display)
                            <span class="hero-chip gold">{{ $trailerForActions->type_display }}</span>
                        @endif
                        @if($trailerForActions->year_label)
                            <span class="hero-chip"><i class="fas fa-calendar-alt"></i> {{ $trailerForActions->year_label }}</span>
                        @endif
                        @if($trailerForActions->genre)
                            <span class="hero-chip"><i class="fas fa-tag"></i> {{ $trailerForActions->genre }}</span>
                        @endif
                        @if($trailerForActions->duration_label)
                            <span class="hero-chip"><i class="fas fa-clock"></i> {{ $trailerForActions->duration_label }}</span>
                        @endif
                        @if($trailerForActions->language)
                            <span class="hero-chip"><i class="fas fa-globe"></i> {{ $trailerForActions->language }}</span>
                        @endif
                    @endif
                </div>
                @if($tagline)
                    <p class="hero-desc">{{ $tagline }}</p>
                @endif
                <div class="hero-actions">
                    @if($trailerForActions)
                        @if($trailerForActions->youtube_id || ($trailerForActions->source_type === 'file' && $trailerForActions->file_path))
                            <button type="button" class="hero-watch" onclick="openTrailerModal('{{ addslashes($trailerForActions->trailer_url ?? $trailerForActions->file_path) }}')">
                                <i class="fas fa-play"></i> Watch Trailer
                            </button>
                        @else
                            <a href="{{ route('trailers.show', $trailerForActions->slug) }}" class="hero-watch"><i class="fas fa-play"></i> Watch Trailer</a>
                        @endif
                        <button type="button" class="hero-btn hero-fav-btn" onclick="mmToggleFav(event, {{ $trailerForActions->id }}, this)">
                            <i class="fas fa-heart fav-on"></i><i class="fas fa-heart fav-off"></i> <span class="fav-label">My List</span>
                        </button>
                        <button type="button" class="hero-btn" onclick="mmShare(this, '{{ route('trailers.show', $trailerForActions->slug) }}', '{{ addslashes($trailerForActions->title) }}')">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                        @if($slideSlug = ($isFallback ? $featuredTrailer->slug : ($linkedTrailer ? $linkedTrailer->slug : null)))
                            <a href="{{ route('trailers.show', $slideSlug) }}" class="hero-btn"><i class="fas fa-arrow-right"></i> More Info</a>
                        @endif
                    @else
                        <a href="{{ route('trailers') }}" class="hero-watch"><i class="fas fa-compass"></i> Explore Trailers</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    @if($slideCount > 1)
        <button class="hero-nav prev" onclick="heroPrev()" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
        <button class="hero-nav next" onclick="heroNext()" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
        <div class="hero-dots">
            @foreach($slides as $i => $slide)
                <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-dot="{{ $i }}" onclick="heroGo({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
    @endif
</div>

@php
    $isFav = fn ($t) => isset($favoritedIds) && $favoritedIds->has($t->id);
@endphp

<!-- ==================== MY FAVORITES ==================== -->
@auth
    @if($favTrailers->count() > 0)
        <section class="mm-section reveal">
            <div class="section-header">
                <span class="icon"><i class="fas fa-heart"></i></span>
                <h2>My Favorite Trailers</h2>
                <a href="{{ route('favorites') }}" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="carousel" data-carousel>
                <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
                <div class="carousel-track">
                    @foreach($favTrailers as $trailer)
                        <div class="carousel-item">
                            @include('partials.trailer-card', ['trailer' => $trailer, 'badge' => 'My List', 'favorited' => true])
                        </div>
                    @endforeach
                </div>
                <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>
    @endif
@endauth

<!-- ==================== JUST DROPPED ==================== -->
@if($recentTrailers->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-rocket"></i></span>
            <h2>Just Dropped</h2>
            <a href="{{ route('latest') }}" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="carousel" data-carousel>
            <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track">
                @foreach($recentTrailers as $trailer)
                    <div class="carousel-item">
                        @include('partials.trailer-card', ['trailer' => $trailer, 'badge' => 'New Release', 'favorited' => $isFav($trailer)])
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
@endif

<!-- ==================== TRENDING ==================== -->
@if($trendingTrailers->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-fire"></i></span>
            <h2>Trending Trailers</h2>
            <a href="{{ route('trending') }}" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="carousel" data-carousel>
            <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track">
                @foreach($trendingTrailers as $trailer)
                    <div class="carousel-item">
                        @include('partials.trailer-card', ['trailer' => $trailer, 'badge' => 'Trending', 'favorited' => $isFav($trailer)])
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
@endif

<!-- ==================== COMING SOON ==================== -->
@if($comingSoon->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-calendar-plus"></i></span>
            <h2>Coming Soon</h2>
        </div>
        <div class="carousel" data-carousel>
            <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track">
                @foreach($comingSoon as $trailer)
                    <div class="carousel-item">
                        @include('partials.trailer-card', [
                            'trailer'   => $trailer,
                            'badge'     => 'Coming Soon · ' . ($trailer->release_date ? $trailer->release_date->format('M j') : ''),
                            'favorited' => $isFav($trailer),
                        ])
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
@endif

<!-- ==================== POPULAR THIS WEEK ==================== -->
@if($popularThisWeek->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-chart-line"></i></span>
            <h2>Popular This Week</h2>
        </div>
        <div class="carousel" data-carousel>
            <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track">
                @foreach($popularThisWeek as $trailer)
                    <div class="carousel-item">
                        @include('partials.trailer-card', ['trailer' => $trailer, 'badge' => 'Hot This Week', 'favorited' => $isFav($trailer)])
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
@endif

<!-- ==================== BROWSE BY GENRE ==================== -->
@if($genres->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-tags"></i></span>
            <h2>Browse by Genre</h2>
            <a href="{{ route('genres') }}" class="view-all">All genres <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="genre-chips">
            @foreach($genres as $g)
                <a href="{{ route('genre.show', \Illuminate\Support\Str::slug($g->genre)) }}" class="genre-chip">
                    <i class="fas fa-film" style="font-size:0.75rem; color:var(--gold);"></i> {{ $g->genre }} <span>{{ $g->total }}</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

<!-- ==================== INTERNATIONAL ==================== -->
@if($international->count() > 0)
    <section class="mm-section reveal">
        <div class="section-header">
            <span class="icon"><i class="fas fa-globe-africa"></i></span>
            <h2>International</h2>
            <a href="{{ route('trailers') }}" class="view-all">View all <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="carousel" data-carousel>
            <button class="carousel-btn prev" data-carousel-prev><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track">
                @foreach($international as $trailer)
                    <div class="carousel-item">
                        @include('partials.trailer-card', [
                            'trailer'   => $trailer,
                            'badge'     => $trailer->country ?: $trailer->language,
                            'favorited' => $isFav($trailer),
                        ])
                    </div>
                @endforeach
            </div>
            <button class="carousel-btn next" data-carousel-next><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>
@endif

@if($slideCount <= 1 && $featuredTrailer === null && $recentTrailers->count() === 0)
    <div class="mm-section" style="padding-bottom: 3rem;">
        <div class="row-empty"><i class="fas fa-video-slash"></i> No trailers available yet. Check back soon!</div>
    </div>
@endif

<!-- ==================== TRAILER MODAL ==================== -->
<div class="trailer-modal" id="trailerModal">
    <div class="modal-backdrop" onclick="closeTrailerModal()"></div>
    <div class="modal-box">
        <button class="modal-close" onclick="closeTrailerModal()" aria-label="Close"><i class="fas fa-times"></i></button>
        <div id="modalPlayer"></div>
    </div>
</div>

@include('partials.footer')

<script>
    // ---------- HERO SLIDESHOW ----------
    const heroSlidesEl = document.querySelectorAll('.mm-hero .hero-slide');
    let heroIdx = 0;
    const heroTimer = heroSlidesEl.length > 1 ? setInterval(() => heroGo(heroIdx + 1), 8000) : null;
    function heroShow(i, direction) {
        heroSlidesEl.forEach((s, k) => {
            const isActive = k === i;
            s.style.display = isActive ? '' : 'none';
            const media = s.querySelector('.hero-media');
            if (media) media.classList.toggle('zoom', isActive);
            s.querySelector('.hero-content').style.animation = 'none';
            if (isActive) { void s.querySelector('.hero-content').offsetWidth; s.querySelector('.hero-content').style.animation = 'heroIn 0.9s cubic-bezier(.18,.89,.32,1.18) forwards'; }
        });
        document.querySelectorAll('.hero-dot').forEach((d, k) => d.classList.toggle('active', k === i));
    }
    function heroGo(i) {
        if (!heroSlidesEl.length) return;
        heroIdx = ((i % heroSlidesEl.length) + heroSlidesEl.length) % heroSlidesEl.length;
        heroShow(heroIdx);
    }
    window.heroPrev = () => heroGo(heroIdx - 1);
    window.heroNext = () => heroGo(heroIdx + 1);

    // ---------- CAROUSEL SCROLL ----------
    document.querySelectorAll('[data-carousel]').forEach(c => {
        const track = c.querySelector('.carousel-track');
        const prev = c.querySelector('[data-carousel-prev]');
        const next = c.querySelector('[data-carousel-next]');
        const step = () => {
            const item = c.querySelector('.carousel-item');
            return item ? item.getBoundingClientRect().width + parseFloat(getComputedStyle(track).gap || 16) : 260;
        };
        prev.addEventListener('click', () => track.scrollBy({ left: -step() * 2, behavior: 'smooth' }));
        next.addEventListener('click', () => track.scrollBy({ left: step() * 2, behavior: 'smooth' }));
    });

    // ---------- TRAILER MODAL ----------
    function extractYoutubeId(url) {
        const m = /(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/.exec(url || '');
        return m ? m[1] : null;
    }
    window.openTrailerModal = function (url) {
        const player = document.getElementById('modalPlayer');
        const id = extractYoutubeId(url);
        if (id) {
            player.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1" title="Trailer" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } else {
            player.innerHTML = '<video src="' + (url || '') + '" controls autoplay></video>';
        }
        document.getElementById('trailerModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.closeTrailerModal = function () {
        const player = document.getElementById('modalPlayer');
        player.innerHTML = '';
        document.getElementById('trailerModal').classList.remove('open');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeTrailerModal(); closeSheet && closeSheet(); }
    });
</script>
</body>
</html>