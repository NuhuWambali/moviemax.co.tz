<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo', [
        'seoTitle' => 'Discover Movie Trailers | MOVIEMAX',
        'seoDescription' => 'Discover the latest movie trailers, teasers and TV spots in HD. Browse trending trailers, upcoming releases, genres and watch instantly on MOVIEMAX.',
        'seoType' => 'website',
    ])
    <title>Discover Trailers - MovieMax</title>
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
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        .page-hero {
            padding: 130px 5% 3rem;
            text-align: center;
            background:
                radial-gradient(900px 380px at 50% -10%, rgba(229, 9, 20, 0.28), transparent 65%),
                radial-gradient(700px 320px at 85% 0%, rgba(255, 210, 74, 0.08), transparent 60%),
                var(--bg-deep);
        }
        .page-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.6rem, 6vw, 4.4rem);
            letter-spacing: 3px;
            line-height: 1;
        }
        .page-hero h1 .accent {
            background: linear-gradient(120deg, var(--accent-red), #ff5c67);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .page-hero p { color: var(--text-secondary); font-size: 0.95rem; margin: 0.9rem auto 1.6rem; max-width: 620px; }
        .hero-count-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--gold);
            background: rgba(255, 210, 74, 0.1);
            border: 1px solid rgba(255, 210, 74, 0.35);
            padding: 0.45rem 1rem;
            border-radius: 40px;
            margin-bottom: 1.4rem;
        }

        .hero-search { max-width: 560px; margin: 0 auto; }
        .hero-search form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            padding: 0.3rem 0.3rem 0.3rem 1.2rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .hero-search form:focus-within {
            border-color: rgba(229, 9, 20, 0.6);
            box-shadow: 0 0 0 4px rgba(229, 9, 20, 0.12);
        }
        .hero-search i { color: var(--text-muted); }
        .hero-search input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.7rem 0.9rem;
        }
        .hero-search button {
            background: var(--accent-red);
            border: none;
            color: #fff;
            font-family: inherit;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.7rem 1.4rem;
            border-radius: 40px;
            cursor: pointer;
            transition: filter 0.2s;
        }
        .hero-search button:hover { filter: brightness(1.12); }

        .sort-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1.8rem 0 0.4rem;
        }
        .sort-tab {
            padding: 0.55rem 1.2rem;
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .sort-tab:hover { border-color: rgba(229, 9, 20, 0.5); color: #fff; }
        .sort-tab.active {
            background: var(--accent-red);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 8px 24px rgba(229, 9, 20, 0.35);
        }

        .filter-bar {
            max-width: 1500px;
            margin: 1rem auto 0;
            padding: 0 3%;
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 0.8rem;
        }
        .filter-group { display: flex; flex-direction: column; gap: 0.35rem; }
        .filter-group label {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
        }
        .filter-select {
            appearance: none;
            min-width: 150px;
            padding: 0.55rem 2.2rem 0.55rem 0.95rem;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.05) url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2210%22 height=%226%22%3E%3Cpath d=%22M0 0l5 6 5-6z%22 fill=%22%238a93a0%22/%3E%3C/svg%3E') no-repeat right 14px center;
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            transition: all 0.25s ease;
        }
        .filter-select:focus { border-color: rgba(229, 9, 20, 0.6); box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.12); }
        .filter-select option { background: #11161d; color: var(--text-primary); }
        .filter-actions { display: flex; align-items: center; gap: 0.5rem; margin-left: auto; }
        .filter-apply, .filter-clear {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 1.3rem;
            border-radius: 40px;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .filter-apply { background: var(--accent-red); border-color: transparent; color: #fff; }
        .filter-apply:hover { filter: brightness(1.12); }
        .filter-clear:hover { border-color: rgba(229, 9, 20, 0.5); }

        .active-filter {
            max-width: 1500px;
            margin: 0 auto;
            padding: 0.3rem 3%;
        }
        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.4rem 0.9rem;
            border-radius: 30px;
            background: rgba(229, 9, 20, 0.14);
            border: 1px solid rgba(229, 9, 20, 0.4);
            color: #fff;
            font-size: 0.76rem;
            font-weight: 600;
            margin: 0.3rem 0.4rem 0 0;
        }
        .active-filter-chip a { color: var(--gold); text-decoration: none; }

        .discover-container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 0.5rem 3% 3rem;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 1.8rem;
            align-items: start;
        }
        .discover-main { min-width: 0; }
        .discover-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 1.2rem;
        }

        .section-head {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 2px;
            margin: 1.6rem 0 1.1rem;
            color: var(--text-primary);
        }
        .section-head i { color: var(--accent-red); font-size: 1.1rem; }
        .section-head .line { flex: 1; height: 1px; background: linear-gradient(90deg, var(--glass-border), transparent); }

        .side-box {
            background: #10151c;
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 1.1rem;
            position: sticky;
            top: 90px;
        }
        .side-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 1.5px;
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }
        .side-title i { color: var(--gold); }
        .side-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.55rem 0.5rem;
            border-radius: 12px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .side-item:hover { background: rgba(255, 255, 255, 0.05); }
        .side-rank {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.25rem;
            color: var(--text-muted);
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }
        .side-item:nth-child(-n+4) .side-rank { color: var(--gold); }
        .side-thumb {
            width: 66px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: #1a212b;
        }
        .side-info { min-width: 0; }
        .side-info h4 {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .side-info span { font-size: 0.7rem; color: var(--text-muted); }

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
        .empty-state a { color: var(--gold); text-decoration: none; }

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

        @media (max-width: 1100px) {
            .discover-container { grid-template-columns: 1fr; }
            .side-box { position: static; }
            .side-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.5rem; }
            .side-rank { display: none; }
            .side-thumb { width: 100%; height: 70px; }
            .side-info h4 { white-space: normal; }
        }
        @media (max-width: 640px) {
            .discover-grid { grid-template-columns: repeat(2, 1fr); gap: 0.9rem; }
            .page-hero { padding: 120px 5% 2rem; }
        }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

<div class="page-hero">
    <h1>Discover <span class="accent">Trailers</span></h1>
    <p>Watch the latest official trailers, teasers and TV spots — browse trending releases and upcoming movies, then save your favourites.</p>
    @if($trailers->total() > 0)
        <div class="hero-count-tag"><i class="fas fa-video"></i> {{ $trailers->total() }} trailer{{ $trailers->total() == 1 ? '' : 's' }} available</div>
    @endif
    <div class="hero-search">
        <form method="GET" action="{{ route('trailers') }}">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search movies, actors, genres...">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

@php
    $qParams = array_filter(request()->only(['search', 'trailer_type', 'genre', 'year', 'language']), fn ($v) => $v !== '' && $v !== null);
    $tabs = [
        ['label' => 'Latest',     'href' => route('latest', $qParams), 'key' => 'latest'],
        ['label' => 'Trending',   'href' => route('trending', $qParams), 'key' => 'trending'],
        ['label' => 'Most Liked', 'href' => route('trailers', $qParams + ['sort' => 'most_liked']), 'key' => 'most_liked'],
        ['label' => 'Most Saved', 'href' => route('trailers', $qParams + ['sort' => 'most_saved']), 'key' => 'most_saved'],
        ['label' => 'Featured',   'href' => route('trailers', $qParams + ['sort' => 'featured']), 'key' => 'featured'],
        ['label' => 'A–Z',        'href' => route('trailers', $qParams + ['sort' => 'a_z']), 'key' => 'a_z'],
    ];
    $activeFilterCount = count(array_filter(array_diff_key($qParams, ['search' => '']), fn ($v) => $v !== ''));
@endphp

<div class="sort-tabs">
    @foreach($tabs as $tab)
        <a href="{{ $tab['href'] }}" class="sort-tab {{ $activeSort === $tab['key'] ? 'active' : '' }}">{{ $tab['label'] }}</a>
    @endforeach
</div>

<div class="filter-bar">
    <div class="filter-group">
        <label for="fType">Trailer Type</label>
        <select class="filter-select" id="fType" name="trailer_type">
            <option value="">All types</option>
            @foreach($trailerTypes as $type)
                <option value="{{ $type }}" {{ request('trailer_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="fGenre">Genre</label>
        <select class="filter-select" id="fGenre" name="genre">
            <option value="">All genres</option>
            @foreach($genres as $genre)
                <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>{{ $genre }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="fYear">Year</label>
        <select class="filter-select" id="fYear" name="year">
            <option value="">Any year</option>
            @foreach($years as $year)
                <option value="{{ $year }}" {{ (string) request('year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label for="fLang">Language</label>
        <select class="filter-select" id="fLang" name="language">
            <option value="">Any language</option>
            @foreach($languages as $lang)
                <option value="{{ $lang }}" {{ request('language') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-actions">
        @if($activeFilterCount > 0)
            <a href="{{ route('trailers', array_merge(array_filter(['search' => request('search')], fn ($v) => !empty($v)), ['sort' => $activeSort])) }}" class="filter-clear"><i class="fas fa-times"></i> Clear</a>
        @endif
        <button type="button" class="filter-apply" onclick="applyFilters()"><i class="fas fa-filter"></i> Apply</button>
    </div>
</div>

@if($activeFilterCount > 0)
    <div class="active-filter">
        @php
            $chipLabels = [
                'trailer_type' => 'Type',
                'genre'        => 'Genre',
                'year'         => 'Year',
                'language'     => 'Language',
            ];
            $baseChips = array_filter(['search' => request('search')], fn ($v) => !empty($v));
        @endphp
        @foreach($chipLabels as $key => $label)
            @if($val = request($key))
                <span class="active-filter-chip"><i class="fas fa-filter"></i> {{ $label }}: {{ $val }}
                    <a href="{{ route('trailers', $baseChips + array_diff_key($qParams, [$key => '']) + ['sort' => $activeSort]) }}"><i class="fas fa-times-circle"></i></a>
                </span>
            @endif
        @endforeach
    </div>
@endif

<script>
    function applyFilters() {
        const params = new URLSearchParams(window.location.search);
        ['trailer_type', 'genre', 'year', 'language'].forEach(k => {
            const v = document.getElementById('f' + k.charAt(0).toUpperCase() + k.slice(1)).value;
            if (v) params.set(k, v); else params.delete(k);
        });
        window.location.href = '{{ route("trailers") }}?' + params.toString();
    }
</script>

<div class="discover-container">
    <div class="discover-main">
        <div class="section-head"><i class="fas fa-film"></i> {{ request('search') ? 'Results for "' . e(request('search')) . '"' : 'All Trailers' }} <span class="line"></span></div>

        @if($trailers->count() > 0)
            <div class="discover-grid">
                @foreach($trailers as $trailer)
                    @include('partials.trailer-card', [
                        'trailer'   => $trailer,
                        'badge'     => $trailer->is_upcoming ? 'Coming Soon' : null,
                        'favorited' => false,
                    ])
                @endforeach
            </div>
            <div class="pagination-wrap">
                {{ $trailers->links('partials.pagination') }}
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
    </div>

    @if($trendingTrailers->count() > 0 && $trendingTrailers->count() !== $trailers->total())
        <aside class="side-box">
            <div class="side-title"><i class="fas fa-fire"></i> Trending Now</div>
            <div class="side-row">
                @foreach($trendingTrailers as $i => $trending)
                    <a href="{{ route('trailers.show', $trending->slug) }}" class="side-item">
                        <span class="side-rank">{{ $i + 1 }}</span>
                        <img src="{{ $trending->thumb_url }}" alt="{{ $trending->title }}" class="side-thumb" loading="lazy">
                        <span class="side-info">
                            <h4>{{ $trending->title }}</h4>
                            <span><i class="fas fa-eye"></i> {{ number_format($trending->views) }} views</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </aside>
    @endif
</div>

@include('partials.footer')
</body>
</html>