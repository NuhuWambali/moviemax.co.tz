<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo', [
        'seoTitle' => 'Browse Trailers by Genre | MOVIEMAX',
        'seoDescription' => 'Browse movie trailers by genre — Action, Horror, Comedy, Sci-Fi, Drama and more. Discover the best trailers in every category on MOVIEMAX.',
        'seoType' => 'website',
    ])
    <title>Browse by Genre - MovieMax</title>
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
            padding: 130px 5% 2.4rem;
            text-align: center;
            background:
                radial-gradient(900px 380px at 50% -10%, rgba(229, 9, 20, 0.28), transparent 65%),
                radial-gradient(700px 320px at 12% 0%, rgba(255, 210, 74, 0.08), transparent 60%),
                var(--bg-deep);
        }
        .page-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.6rem, 6vw, 4.4rem);
            letter-spacing: 3px;
        }
        .page-hero h1 .accent {
            background: linear-gradient(120deg, var(--accent-red), #ff5c67);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .page-hero p { color: var(--text-secondary); font-size: 0.95rem; margin: 0.9rem auto 0; max-width: 560px; }

        .genre-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 5% 4rem;
        }
        .genre-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
        }
        .genre-card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 1.3rem 1.3rem;
            border-radius: 16px;
            background: #121820;
            border: 1px solid var(--glass-border);
            text-decoration: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .genre-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(229, 9, 20, 0.14), transparent 55%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .genre-card:hover { transform: translateY(-4px); border-color: rgba(229, 9, 20, 0.5); }
        .genre-card:hover::before { opacity: 1; }
        .genre-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            letter-spacing: 1.5px;
            position: relative;
        }
        .genre-card .genre-count {
            font-size: 0.7rem;
            color: var(--text-muted);
            position: relative;
            white-space: nowrap;
        }
        .genre-card i { color: var(--gold); font-size: 1.1rem; position: relative; }
        .genre-empty {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
            border: 1px dashed var(--glass-border);
            border-radius: 18px;
        }
        .genre-empty i { font-size: 2.4rem; display: block; margin-bottom: 1rem; color: var(--accent-red); opacity: 0.6; }
        a { color: var(--gold); }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

<div class="page-hero">
    <h1>Browse by <span class="accent">Genre</span></h1>
    <p>Pick a genre and discover its best trailers — from blockbuster action to indie horror.</p>
</div>

<div class="genre-wrap">
    @if($genres->count() > 0)
        <div class="genre-grid">
            @foreach($genres as $g)
                <a href="{{ route('genre.show', \Illuminate\Support\Str::slug($g->genre)) }}" class="genre-card">
                    <h3>{{ $g->genre }}</h3>
                    <span class="genre-count">{{ $g->total }} trailer{{ $g->total == 1 ? '' : 's' }} <i class="fas fa-arrow-right"></i></span>
                </a>
            @endforeach
        </div>
    @else
        <div class="genre-empty">
            <i class="fas fa-tags"></i>
            <p>No genres available yet. Trailers with a genre will appear here.</p>
        </div>
    @endif
</div>

@include('partials.footer')
</body>
</html>