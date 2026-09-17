<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo', [
        'seoTitle' => $label . ' Trailers | MOVIEMAX',
        'seoDescription' => 'Watch the best ' . $label . ' movie trailers in HD. New ' . $label . ' trailers, teasers and TV spots on MOVIEMAX.',
        'seoType' => 'website',
    ])
    <title>{{ $label }} Trailers - MovieMax</title>
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
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        .page-hero {
            padding: 130px 5% 2rem;
            background:
                radial-gradient(900px 380px at 50% -10%, rgba(229, 9, 20, 0.28), transparent 65%),
                var(--bg-deep);
            border-bottom: 1px solid var(--glass-border);
        }
        .page-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.4rem, 5vw, 3.6rem);
            letter-spacing: 3px;
        }
        .page-hero h1 .accent {
            background: linear-gradient(120deg, var(--accent-red), #ff5c67);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .page-hero p { color: var(--text-secondary); font-size: 0.9rem; margin: 0.7rem 0 1.2rem; max-width: 620px; }
        .breadcrumb { display: flex; gap: 8px; align-items: center; font-size: 0.75rem; color: var(--text-muted); }
        .breadcrumb a { color: var(--text-secondary); text-decoration: none; }
        .breadcrumb a:hover { color: var(--gold); }
        .count-tag {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gold);
            background: rgba(255, 210, 74, 0.1);
            border: 1px solid rgba(255, 210, 74, 0.3);
            padding: 0.35rem 0.9rem;
            border-radius: 30px;
            margin-top: 1rem;
        }
        .genre-follow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 1rem;
            padding: 0.45rem 1.2rem;
            border-radius: 30px;
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--glass-border);
            transition: all 0.25s ease;
        }
        .genre-follow:hover { border-color: rgba(229, 9, 20, 0.55); }
        .genre-follow.following {
            color: var(--gold);
            border-color: rgba(255, 210, 74, 0.45);
            background: rgba(255, 210, 74, 0.1);
        }

        .genre-container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 1.8rem 3% 3rem;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 1.8rem;
            align-items: start;
        }
        .genre-main { min-width: 0; }

        .genre-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1.2rem; }
        .section-head {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 2px;
            margin: 0 0 1.1rem;
        }
        .section-head i { color: var(--gold); font-size: 1.1rem; }
        .section-head .line { flex: 1; height: 1px; background: linear-gradient(90deg, var(--glass-border), transparent); }

        .featured-band {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1.8rem;
            border: 1px solid var(--glass-border);
        }
        .featured-band a { display: block; position: relative; text-decoration: none; color: inherit; }
        .featured-band img { width: 100%; height: 340px; object-fit: cover; display: block; }
        .featured-band .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(10, 13, 18, 0.96) 8%, rgba(10, 13, 18, 0.55) 45%, rgba(10, 13, 18, 0.1) 75%);
            display: flex;
            align-items: flex-end;
            padding: 2.2rem;
        }
        .featured-band .content { max-width: 520px; }
        .featured-band .label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: var(--gold);
            background: rgba(255, 210, 74, 0.12);
            border: 1px solid rgba(255, 210, 74, 0.4);
            padding: 0.4rem 0.9rem;
            border-radius: 30px;
            margin-bottom: 0.8rem;
        }
        .featured-band h2 { font-family: 'Bebas Neue', sans-serif; font-size: clamp(1.6rem, 3.5vw, 2.4rem); letter-spacing: 1.5px; }
        .featured-band p { color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem; line-height: 1.6; }
        .featured-band .meta { display: flex; gap: 1rem; margin-top: 0.9rem; font-size: 0.78rem; color: var(--text-muted); flex-wrap: wrap; }

        .side-box {
            background: #10151c;
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 1.1rem;
            position: sticky;
            top: 90px;
        }
        .side-title { display: flex; align-items: center; gap: 10px; font-family: 'Bebas Neue', sans-serif; letter-spacing: 1.5px; font-size: 1.15rem; margin-bottom: 1rem; }
        .side-title i { color: var(--gold); }
        .side-item { display: flex; align-items: center; gap: 0.8rem; padding: 0.55rem 0.5rem; border-radius: 12px; text-decoration: none; transition: background 0.2s; }
        .side-item:hover { background: rgba(255, 255, 255, 0.05); }
        .side-thumb { width: 66px; height: 40px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
        .side-info { min-width: 0; }
        .side-info h4 { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .side-info span { font-size: 0.7rem; color: var(--text-muted); }

        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); border: 1px dashed var(--glass-border); border-radius: 18px; }
        .empty-state i { font-size: 2.4rem; display: block; margin-bottom: 1rem; color: var(--accent-red); opacity: 0.6; }

        .pagination-wrap { margin-top: 2.5rem; display: flex; justify-content: center; }
        .pagination { display: flex; gap: 0.4rem; list-style: none; }
        .pagination .page-item .page-link {
            display: inline-flex;
            min-width: 40px; height: 40px; padding: 0 0.9rem;
            align-items: center; justify-content: center;
            border-radius: 12px; background: var(--bg-card);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;
            text-decoration: none; transition: all 0.25s ease;
        }
        .pagination .page-item.active .page-link { background: var(--accent-red); color: #fff; border-color: transparent; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; }
        .pagination .page-link:hover:not(.active) { border-color: rgba(229, 9, 20, 0.5); color: var(--text-primary); }

        @media (max-width: 1100px) { .genre-container { grid-template-columns: 1fr; } .side-box { position: static; } }
        @media (max-width: 640px) { .genre-grid { grid-template-columns: repeat(2, 1fr); gap: 0.9rem; } .featured-band img { height: 260px; } }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

<div class="page-hero">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <i class="fas fa-chevron-right" style="font-size:0.6rem;"></i>
        <a href="{{ route('genres') }}">Genres</a> <i class="fas fa-chevron-right" style="font-size:0.6rem;"></i>
        <span>{{ $label }}</span>
    </div>
    <h1><span class="accent">{{ $label }}</span> Trailers</h1>
    <p>Fresh {{ $label }} trailers, teasers and TV spots — curated from around the world.</p>
    <div style="display:flex; align-items:center; justify-content:center; gap:0.7rem; flex-wrap:wrap;">
        <span class="count-tag"><i class="fas fa-film"></i> {{ $trailers->total() }} trailer{{ $trailers->total() == 1 ? '' : 's' }}</span>
        <button type="button" class="genre-follow {{ ($following ?? false) ? 'following' : '' }}" id="genreFollowBtn"
                data-genre="{{ $label }}" onclick="toggleGenreFollow(this)">
            <i class="fas {{ ($following ?? false) ? 'fa-bell' : 'fa-bell-plus' }}"></i>
            <span class="fl-label">{{ ($following ?? false) ? 'Following' : 'Follow Genre' }}</span>
        </button>
    </div>
</div>

<div class="genre-container">
    <div class="genre-main">
        @if($featured)
            <div class="featured-band">
                <a href="{{ route('trailers.show', $featured->slug) }}">
                    <img src="{{ $featured->thumb_url }}" alt="{{ $featured->title }}">
                    <div class="overlay">
                        <div class="content">
                            <span class="label"><i class="fas fa-star"></i> Featured {{ $label }} Trailer</span>
                            <h2>{{ $featured->title }}</h2>
                            @if($featured->description)
                                <p>{{ \Illuminate\Support\Str::limit($featured->description, 140) }}</p>
                            @endif
                            <div class="meta">
                                <span><i class="fas fa-eye"></i> {{ number_format($featured->views) }} views</span>
                                <span><i class="fas fa-thumbs-up"></i> {{ $featured->likesCount() }} likes</span>
                                @if($featured->year_label)<span><i class="fas fa-calendar-alt"></i> {{ $featured->year_label }}</span>@endif
                                @if($featured->duration_label)<span><i class="fas fa-clock"></i> {{ $featured->duration_label }}</span>@endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div class="section-head"><i class="fas fa-film"></i> {{ $label }} Trailers <span class="line"></span></div>

        @if($trailers->count() > 0)
            <div class="genre-grid">
                @foreach($trailers as $trailer)
                    @include('partials.trailer-card', ['trailer' => $trailer])
                @endforeach
            </div>
            <div class="pagination-wrap">
                {{ $trailers->links('partials.pagination') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-video-slash"></i>
                <p>No {{ $label }} trailers available yet. <a href="{{ route('genres') }}" style="color:var(--gold);">Browse other genres</a></p>
            </div>
        @endif
    </div>

    @if($trending->count() > 0)
        <aside class="side-box">
            <div class="side-title"><i class="fas fa-fire"></i> Trending Now</div>
            @foreach($trending as $t)
                <a href="{{ route('trailers.show', $t->slug) }}" class="side-item">
                    <img src="{{ $t->thumb_url }}" alt="{{ $t->title }}" class="side-thumb" loading="lazy">
                    <span class="side-info"><h4>{{ $t->title }}</h4><span><i class="fas fa-eye"></i> {{ number_format($t->views) }}</span></span>
                </a>
            @endforeach
        </aside>
    @endif
</div>

@include('partials.footer')
<script>
    async function toggleGenreFollow(btn) {
        const genre = encodeURIComponent(btn.dataset.genre);
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const btnDisabled = btn.disabled;
        btn.disabled = true;
        try {
            const res = await fetch('/genres/' + genre + '/follow', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            if (res.status === 403) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return;
            }
            const data = await res.json();
            btn.classList.toggle('following', data.following);
            btn.querySelector('.fl-label').textContent = data.following ? 'Following' : 'Follow Genre';
            btn.querySelector('i').className = 'fas ' + (data.following ? 'fa-bell' : 'fa-bell-plus');
        } finally {
            btn.disabled = btnDisabled;
        }
    }
</script>
</body>
</html>