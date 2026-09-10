<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MovieMax – Watch, Stream & Download Free Movies, Series & Trailers Online</title>
    @include('partials.seo', ['seoTitle' => 'MovieMax – Watch, Stream & Download Free Movies, Series & Trailers Online', 'seoDescription' => 'MovieMax lets you watch, stream and download free movies, TV series and trailers online. Discover the latest movies, popular series, new releases and exciting trailers.', 'seoKeywords' => 'MovieMax, free movies, watch movies online, stream movies, download movies, free series, TV series, watch series online, download series, movie trailers, latest movies, new movies, HD movies'])
    @if($heroSlides->first()->image_path ?? null)
        <link rel="preload" as="image" href="{{ $heroSlides->first()->image_path }}" fetchpriority="high">
    @endif
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
            --glow-red: 0 0 30px rgba(229, 9, 20, 0.18);
            --glow-purple: 0 0 30px rgba(255, 215, 0, 0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-surface); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        /* ============ ANIMATIONS ============ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translate(-50%, -40%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes glowPulse {
            0%, 100% { text-shadow: 0 0 10px rgba(229, 9, 20, 0.4), 0 0 20px rgba(229, 9, 20, 0.2); }
            50% { text-shadow: 0 0 20px rgba(229, 9, 20, 0.6), 0 0 40px rgba(229, 9, 20, 0.3); }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes borderGlow {
            0%, 100% { border-color: rgba(229, 9, 20, 0.2); }
            50% { border-color: rgba(229, 9, 20, 0.4); }
        }
        .reveal { opacity: 0; transform: translateY(40px); transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ============ NAVBAR ============ */
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
            background: rgba(10, 13, 18, 0.85);
            z-index: 1000;
            transition: all 0.4s ease;
            border-bottom: 1px solid var(--glass-border);
        }
        .navbar.scrolled {
            background: rgba(10, 13, 18, 0.97);
            padding: 0.7rem 5%;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        }
        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 3px;
            background: var(--accent-red);
            background-size: 200% 200%;
            animation: gradientShift 4s ease infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            z-index: 1001;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
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
            background: var(--accent-red);
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
                border-left: 1px solid rgba(229, 9, 20, 0.2);
            }
            .nav-links.active { right: 0; }
            .nav-links a { font-size: 1.1rem; }
        }

        /* ============ HERO SLIDESHOW ============ */
        .hero {
            height: min(88vh, 760px);
            min-height: 560px;
            width: 100%;
            position: relative;
            overflow: hidden;
            margin-bottom: 0;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1s ease, visibility 1s ease;
            pointer-events: none;
        }
        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center center;
            transform: scale(1.08);
        }
        .hero-slide.active .hero-bg { animation: heroZoom 9s ease forwards; }
        @keyframes heroZoom {
            from { transform: scale(1.12); }
            to { transform: scale(1); }
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(9, 11, 15, 0.6) 0%, rgba(9, 11, 15, 0.3) 30%, rgba(9, 11, 15, 0.7) 70%, var(--bg-deep) 100%),
                linear-gradient(90deg, rgba(9, 11, 15, 0.9) 0%, rgba(9, 11, 15, 0.45) 50%, rgba(9, 11, 15, 0.15) 100%),
                radial-gradient(ellipse at 20% 50%, rgba(229, 9, 20, 0.12) 0%, transparent 60%);
        }
        .hero-content {
            position: absolute;
            z-index: 2;
            max-width: 700px;
            left: 8%;
            top: 50%;
            transform: translateY(-50%);
        }
        .hero-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(10, 13, 18, 0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 6;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .hero-arrow:hover {
            background: rgba(229, 9, 20, 0.35);
            border-color: rgba(229, 9, 20, 0.5);
        }
        .hero-arrow.prev { left: 2%; }
        .hero-arrow.next { right: 2%; }
        .hero-dots {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 6;
        }
        .hero-dot {
            width: 10px;
            height: 10px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.35);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .hero-dot:hover { background: rgba(255, 255, 255, 0.6); }
        .hero-dot.active {
            background: var(--accent-red);
            width: 28px;
            box-shadow: 0 0 12px rgba(229, 9, 20, 0.5);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #ffe8c2;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 5rem;
            letter-spacing: 2px;
            line-height: 1;
            margin-bottom: 1rem;
            background: #ffffff;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero p {
            font-size: 1.05rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            line-height: 1.7;
            max-width: 550px;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-primary, .btn-secondary {
            padding: 0.85rem 2rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .btn-primary {
            background: var(--accent-red);
            color: white;
            box-shadow: 0 4px 20px rgba(229, 9, 20, 0.35);
        }
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.45);
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-primary:hover::before { left: 100%; }
        .btn-secondary {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #ffffff;
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.16);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-3px);
        }

        @media (min-width: 1440px) { .hero h1 { font-size: 6rem; } }
        @media (max-width: 768px) {
            .hero { height: 82vh; min-height: 480px; }
            .hero-content { left: 6%; }
            .hero h1 { font-size: 2.4rem; }
            .hero p { font-size: 0.95rem; }
            .hero-arrow { width: 42px; height: 42px; }
        }

        /* ============ SECTIONS ============ */
        .section { padding: 3rem 5%; }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
        }
        .section-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 1px;
            position: relative;
            padding-left: 1rem;
        }
        .section-header h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: var(--accent-red);
            border-radius: 4px;
        }
        .section-link {
            color: var(--accent-red);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s ease;
        }
        .section-link i { transition: transform 0.3s ease; }
        .section-link:hover { gap: 0.7rem; color: var(--accent-red-dark); }
        .section-link:hover i { transform: translateX(3px); }

        /* ============ GENRE FILTER ============ */
        .genre-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            padding: 1.5rem 5%;
            justify-content: center;
        }
        .genre-btn {
            background: var(--bg-card);
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
            padding: 0.45rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: 1px solid var(--glass-border);
        }
        .genre-btn:hover {
            background: rgba(229, 9, 20, 0.08);
            color: var(--accent-red);
            border-color: rgba(229, 9, 20, 0.3);
            transform: translateY(-2px);
        }
        .genre-btn.active {
            background: var(--accent-red);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        /* ============ MOVIE CARDS ============ */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
        }
        .movie-card {
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
        }
        .movie-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(229, 9, 20, 0.2), 0 0 60px -20px rgba(229, 9, 20, 0.15);
        }
        .card-img-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2 / 3;
        }
        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .movie-card:hover .card-img { transform: scale(1.08); }
        .card-img-wrap::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, var(--bg-card) 0%, transparent 100%);
            pointer-events: none;
        }
        .card-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            background: rgba(9, 11, 15, 0.5);
            z-index: 1;
        }
        .movie-card:hover .card-overlay { opacity: 1; }
        .continue-bar {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.12);
            z-index: 2;
        }
        .continue-bar span {
            display: block;
            height: 100%;
            background: var(--accent-red);
            border-radius: 0 2px 2px 0;
        }
        .card-overlay i {
            width: 50px;
            height: 50px;
            background: var(--accent-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
            box-shadow: 0 8px 25px rgba(229, 9, 20, 0.4);
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        .movie-card:hover .card-overlay i { transform: scale(1); }
        .card-info { padding: 0.9rem 1rem 1rem; }
        .card-info h4 {
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .meta {
            display: flex;
            gap: 0.6rem;
            font-size: 0.7rem;
            color: var(--text-muted);
            flex-wrap: wrap;
            align-items: center;
        }
        .meta span { display: flex; align-items: center; gap: 0.25rem; }
        .card-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent-red);
            padding: 0.2rem 0.6rem;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            color: white;
            z-index: 3;
            letter-spacing: 0.5px;
        }

        /* ============ FAVOURITES ============ */
        .fav-heart {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 5;
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
        .fav-count-label {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        /* ============ TRAILER SECTION ============ */
        .trailer-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
        }
        .trailer-card {
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        .trailer-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 1px;
            background: rgba(229, 9, 20, 0.25);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            transition: background 0.4s ease;
            pointer-events: none;
            z-index: 4;
        }
        .trailer-card:hover::before {
            background: rgba(229, 9, 20, 0.5);
        }
        .trailer-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(229, 9, 20, 0.25), 0 0 60px -20px rgba(229, 9, 20, 0.2);
        }
        .trailer-thumb {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2 / 3;
        }
        .trailer-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .trailer-card:hover .trailer-thumb img { transform: scale(1.08); }
        .trailer-thumb::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(9, 11, 15, 0.05) 0%, rgba(9, 11, 15, 0.55) 100%);
            pointer-events: none;
            z-index: 1;
        }
        .trailer-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent-cyan);
            padding: 0.25rem 0.7rem;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            color: white;
            z-index: 3;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .trailer-play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(229, 9, 20, 0.92);
            border: 2px solid rgba(255,255,255,0.28);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            z-index: 3;
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.5);
            transition: all 0.35s ease;
            animation: playPulse 2s ease-in-out infinite;
        }
        .trailer-card:hover .trailer-play {
            transform: translate(-50%, -50%) scale(1.12);
            background: var(--accent-red);
        }
        @keyframes playPulse {
            0%, 100% { box-shadow: 0 8px 30px rgba(229, 9, 20, 0.4), 0 0 0 0 rgba(229, 9, 20, 0.35); }
            50% { box-shadow: 0 8px 30px rgba(229, 9, 20, 0.6), 0 0 0 14px rgba(229, 9, 20, 0); }
        }
        .trailer-info { padding: 0.9rem 1rem 1rem; }
        .trailer-info h4 {
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .trailer-download {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.8rem;
            padding: 0.55rem 0.8rem;
            border-radius: 10px;
            background: rgba(229, 9, 20, 0.15);
            border: 1px solid rgba(229, 9, 20, 0.3);
            color: #ffc46b;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }
        .trailer-download:hover {
            background: var(--accent-red);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.35);
        }
        .trailer-download-off {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.1);
            color: var(--text-muted);
            cursor: default;
        }
        .trailer-download-off:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            transform: none;
            box-shadow: none;
        }
        @media (max-width: 1100px) {
            .trailer-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 768px) {
            .trailer-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .trailer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }
            .trailer-info { padding: 0.6rem 0.7rem 0.8rem; }
            .trailer-info h4 { font-size: 0.8rem; }
            .trailer-download { font-size: 0.68rem; }
        }

        @media (max-width: 1100px) {
            .movie-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 768px) {
            .movie-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .movie-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }
            .card-info { padding: 0.6rem 0.7rem 0.8rem; }
            .card-info h4 { font-size: 0.8rem; }
        }

        

        /* ============ MODAL ============ */
        .movie-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(12px);
        }
        .modal-container {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 90%; max-width: 800px;
            background: #161c26;
            backdrop-filter: blur(30px);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.3), 0 0 80px -20px rgba(229, 9, 20, 0.2);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }
        .modal-info { flex: 1; }
        .modal-info h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 1px;
            margin-bottom: 10px;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .modal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        .modal-meta i { color: var(--accent-red); margin-right: 4px; }
        .modal-info p {
            color: var(--text-secondary);
            font-size: 0.88rem;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .modal-buttons { display: flex; gap: 12px; }
        .modal-btn-watch, .modal-btn-download {
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .modal-btn-watch {
            background: var(--accent-red);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
        .modal-btn-watch:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(229, 9, 20, 0.4);
        }
        .modal-btn-download {
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
        }
        .modal-btn-download:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: rgba(229, 9, 20, 0.3);
            transform: translateY(-2px);
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 20px;
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-close:hover {
            background: rgba(229, 9, 20, 0.2);
            color: var(--accent-red);
            border-color: rgba(229, 9, 20, 0.3);
        }

        /* ============ VIDEO PLAYER ============ */
        .video-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 3000;
            background: #000;
        }
        .video-container {
            position: relative;
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .video-container video { width: 100%; height: 100%; object-fit: contain; }
        .close-video {
            position: absolute;
            top: 20px; right: 30px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            font-size: 1.8rem;
            cursor: pointer;
            z-index: 3001;
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .close-video:hover {
            background: var(--accent-red);
            border-color: var(--accent-red);
        }

        /* ============ TRAILER MODAL ============ */
        .trailer-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 4000;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .trailer-modal.active { display: flex; }
        .trailer-wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: default;
        }
        .trailer-container {
            width: min(92vw, 1200px);
            aspect-ratio: 16 / 9;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 0 60px rgba(229, 9, 20, 0.18), 0 30px 80px rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: default;
        }
        .trailer-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }
        .trailer-container video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
            display: block;
        }
        .trailer-close {
            position: absolute;
            top: 20px;
            right: 24px;
            z-index: 4002;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 1.4rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .trailer-close:hover {
            background: var(--accent-red);
            border-color: var(--accent-red);
            transform: rotate(90deg);
        }
        @media (max-width: 600px) {
            .trailer-close { top: 12px; right: 14px; width: 40px; height: 40px; }
            .trailer-container { width: 95vw; }
        }

        @media (max-width: 600px) {
            .modal-header { flex-direction: column; text-align: center; padding: 20px; }
            .modal-poster { flex: 0 0 auto; max-width: 120px; margin: 0 auto; }
            .modal-buttons { justify-content: center; }
            .modal-close { position: relative; top: auto; right: auto; margin: 0 auto; }
        }
    </style>
</head>
<body>
@include('partials.loader')    @include('partials.navbar')

@php
    $featuredItem = $featuredMovie ?? $featuredSeries ?? null;
    $heroSlides = $heroSlides ?? collect();
    if ($heroSlides->isEmpty()) {
        $slides = [];
        if ($featuredItem) {
            $slides[] = (object) [
                'title' => $featuredItem->title,
                'tagline' => $featuredItem->description ?? '',
                'image_path' => $featuredItem->poster_path ?? '/images/heroes/hero-3.png',
                'trailer_url' => $featuredItem->trailer_url ?? null,
                'link_type' => $featuredMovie ? 'movie' : 'series',
                'link_id' => $featuredItem->id,
            ];
        }
        $slides[] = (object) [
            'title' => 'MOVIEMAX',
            'tagline' => 'Stream and download the latest movies and TV series in stunning quality. Your entertainment, your way.',
            'image_path' => '/images/heroes/hero-3.png',
            'trailer_url' => null,
            'link_type' => null,
            'link_id' => null,
        ];
        $heroSlides = collect($slides);
    }
@endphp

@if($heroSlides->isNotEmpty())
<div class="hero" id="heroSlider">
    @foreach($heroSlides as $i => $slide)
    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-idx="{{ $i }}">
        <div class="hero-bg" style="background-image: url('{{ $slide->image_path ?? '/images/heroes/hero-3.png' }}')"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-bolt"></i>
                FEATURED ON MOVIEMAX
            </div>
            <h1>{{ $slide->title }}</h1>
            <p>{{ $slide->tagline }}</p>
            <div class="btn-group">
                @if($slide->trailer_url)
                    <button type="button" class="btn-primary" onclick="openTrailerModal('{{ addslashes($slide->trailer_url) }}')">
                        <i class="fas fa-video"></i> Watch Trailer
                    </button>
                @endif
                @if($slide->link_type === 'movie')
                    <a href="{{ route('movies.show', $slide->link_id) }}" class="btn-secondary"><i class="fas fa-play"></i> More Info</a>
                @elseif($slide->link_type === 'series')
                    <a href="{{ route('series.show', $slide->link_id) }}" class="btn-secondary"><i class="fas fa-play"></i> More Info</a>
                @else
                    <a href="/movies" class="btn-secondary"><i class="fas fa-compass"></i> Explore</a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    @if($heroSlides->count() > 1)
    <button class="hero-arrow prev" onclick="prevSlide()" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
    <button class="hero-arrow next" onclick="nextSlide()" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
    <div class="hero-dots">
        @foreach($heroSlides as $i => $slide)
            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-dot="{{ $i }}" onclick="goToSlide({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</div>
@endif

<!-- Genre Filter -->
<div class="genre-bar">
    <button class="genre-btn active" data-genre="all">All</button>
    @foreach(['Action', 'Horror', 'Romance', 'War', 'Sci-Fi', 'Comedy', 'Drama', 'Thriller'] as $g)
        <button class="genre-btn" data-genre="{{ $g }}">{{ $g }}</button>
    @endforeach
</div>

@if($continueWatching->count() > 0)
<!-- Continue Watching -->
<div class="section reveal" id="continue-watching">
    <div class="section-header">
        <h2><i class="fas fa-play-circle"></i> Continue Watching</h2>
        <a href="{{ route('movies.index') }}" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="movie-grid">
        @foreach($continueWatching as $entry)
        <a class="movie-card" href="{{ route('movies.show', $entry->movie->slug) }}" title="{{ $entry->movie->title }}">
            <div class="card-img-wrap">
                <img class="card-img" src="{{ $entry->movie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $entry->movie->title }}" loading="lazy">
                <div class="card-overlay"><i class="fas fa-play"></i></div>
                <div class="continue-bar"><span style="width: {{ max(5, min(100, ($entry->ratio ?? 0) * 100)) }}%"></span></div>
            </div>
            <div class="card-info">
                <h4>{{ $entry->movie->title }}</h4>
                <div class="meta">
                    <span><i class="fas fa-calendar-alt"></i> {{ $entry->movie->release_year ?? 'N/A' }}</span>
                    <span><i class="fas fa-clock"></i> {{ $entry->movie->duration ?? 'N/A' }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@if($favMovies->count() > 0 || $favSeries->count() > 0)
<!-- My Favorites -->
<div class="section reveal" id="my-favorites">
    <div class="section-header">
        <h2><i class="fas fa-heart" style="color: var(--accent-red); margin-right: 0.5rem; font-size: 1.2rem;"></i> My Favorites</h2>
        <a href="/favorites" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="movie-grid">
        @foreach($favMovies as $favMovie)
            <div class="movie-card" data-slug="{{ $favMovie->slug }}" data-id="{{ $favMovie->id }}" data-title="{{ $favMovie->title }}" data-poster="{{ $favMovie->poster_path }}" data-year="{{ $favMovie->release_year }}" data-duration="{{ $favMovie->duration }}" data-genre="{{ $favMovie->genre }}" data-description="{{ $favMovie->description }}" data-file="{{ $favMovie->file_path }}">
                <div class="card-img-wrap">
                    <span class="card-badge">FAVORITE</span>
                    <img class="card-img" src="{{ $favMovie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $favMovie->title }}" loading="lazy">
                    <div class="card-overlay"><i class="fas fa-eye"></i></div>
                    <button class="fav-heart active" type="button" data-type="movie" data-id="{{ $favMovie->id }}" onclick="toggleFavorite(event,'movie',{{ $favMovie->id }},this)" title="Remove from favorites"><i class="fas fa-heart"></i></button>
                </div>
                <div class="card-info">
                    <h4>{{ $favMovie->title }}</h4>
                    <div class="meta">
                        <span><i class="fas fa-calendar-alt"></i> {{ $favMovie->release_year ?? 'N/A' }}</span>
                        <span><i class="fas fa-film"></i> {{ $favMovie->genre ?? 'General' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        @foreach($favSeries as $favSer)
            <div class="movie-card" data-genre="{{ $favSer->genre ?? 'General' }}" onclick="location.href='{{ route('series.show', $favSer->id) }}'">
                <div class="card-img-wrap">
                    <span class="card-badge">FAVORITE</span>
                    <img class="card-img" src="{{ $favSer->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $favSer->title }}" loading="lazy">
                    <div class="card-overlay"><i class="fas fa-eye"></i></div>
                    <button class="fav-heart active" type="button" data-type="series" data-id="{{ $favSer->id }}" onclick="toggleFavorite(event,'series',{{ $favSer->id }},this)" title="Remove from favorites"><i class="fas fa-heart"></i></button>
                </div>
                <div class="card-info">
                    <h4>{{ $favSer->title }}</h4>
                    <div class="meta">
                        <span><i class="fas fa-calendar-alt"></i> {{ $favSer->release_year ?? 'N/A' }}</span>
                        <span><i class="fas fa-layer-group"></i> {{ $favSer->seasons_count }} Season{{ $favSer->seasons_count > 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Trending Movies -->
<div class="section reveal">
    <div class="section-header">
        <h2><i class="fas fa-fire" style="color: var(--accent-red); margin-right: 0.5rem; font-size: 1.2rem;"></i> Trending Movies</h2>
        <a href="/movies?sort=trending" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @if($trendingMovies->count() > 0)
        <div class="movie-grid">
            @foreach($trendingMovies as $movie)
                <div class="movie-card" data-slug="{{ $movie->slug }}" data-id="{{ $movie->id }}" data-title="{{ $movie->title }}" data-poster="{{ $movie->poster_path }}" data-year="{{ $movie->release_year }}" data-duration="{{ $movie->duration }}" data-genre="{{ $movie->genre }}" data-description="{{ $movie->description }}" data-file="{{ $movie->file_path }}">
                    <div class="card-img-wrap">
                        <span class="card-badge">TRENDING</span>
                        <img class="card-img" src="{{ $movie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $movie->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                        <button class="fav-heart" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event,'movie',{{ $movie->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                    </div>
                    <div class="card-info">
                        <h4>{{ $movie->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-clock"></i> {{ $movie->duration ?? 'N/A' }}</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($movie->views ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center; padding:3rem; color: var(--text-muted);">No movies available yet.</div>
    @endif
</div>

<!-- Official Trailers -->
<div class="section reveal">
    <div class="section-header">
        <h2><i class="fas fa-video" style="color: var(--accent-cyan); margin-right: 0.5rem; font-size: 1.2rem;"></i> Official Trailers</h2>
        <a href="/trailers" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @if($trailerMovies->count() > 0)
        <div class="trailer-grid">
            @foreach($trailerMovies as $trailerMovie)
                <div class="trailer-card" onclick="openTrailerModal('{{ addslashes($trailerMovie->trailer_url) }}')">
                    <div class="trailer-thumb">
                        <span class="trailer-badge"><i class="fas fa-video"></i> TRAILER</span>
                        <img src="{{ $trailerMovie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $trailerMovie->title }}" loading="lazy">
                        <div class="trailer-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="trailer-info">
                        <h4>{{ $trailerMovie->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $trailerMovie->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-film"></i> {{ $trailerMovie->genre ?? 'General' }}</span>
                            <span><i class="fas fa-star" style="color:#ffd700;"></i> {{ $trailerMovie->rating ?? 'N/A' }}</span>
                        </div>
                        @if($trailerMovie->file_path)
                            <a href="{{ route('movies.show', $trailerMovie->slug) }}" class="trailer-download" onclick="event.stopPropagation();" title="Go to {{ $trailerMovie->title }} to download the full movie">
                                <i class="fas fa-download"></i> Download Movie
                            </a>
                        @else
                            <div class="trailer-download trailer-download-off">
                                <i class="fas fa-play-circle"></i> Watch on Movie Page
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($trailerSeries->count() === 0 && $homeTrailers->count() === 0)
        <div style="text-align:center; padding:3rem; color: var(--text-muted);">No trailers available yet.</div>
    @endif

    @if($trailerSeries->count() > 0)
        <h3 style="color: var(--text-primary); font-family: var(--font-display); font-size: 1.2rem; margin: 2rem 0 1rem;">
            <i class="fas fa-tv" style="color: var(--accent-purple); margin-right: 0.5rem;"></i> Series Trailers
        </h3>
        <div class="trailer-grid">
            @foreach($trailerSeries as $trailerSeriesItem)
                <div class="trailer-card" onclick="openTrailerModal('{{ addslashes($trailerSeriesItem->trailer_url) }}')">
                    <div class="trailer-thumb">
                        <span class="trailer-badge"><i class="fas fa-video"></i> TRAILER</span>
                        <img src="{{ $trailerSeriesItem->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $trailerSeriesItem->title }}" loading="lazy">
                        <div class="trailer-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="trailer-info">
                        <h4>{{ $trailerSeriesItem->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $trailerSeriesItem->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-layer-group"></i> {{ $trailerSeriesItem->seasons_count }} Seasons</span>
                            <span><i class="fas fa-star" style="color:#ffd700;"></i> {{ $trailerSeriesItem->rating ?? 'N/A' }}</span>
                        </div>
                        <a href="{{ route('series.show', $trailerSeriesItem->id) }}" class="trailer-download" onclick="event.stopPropagation();" title="Go to {{ $trailerSeriesItem->title }} to watch the series">
                            <i class="fas fa-eye"></i> Watch Series
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@if($homeTrailers->count() > 0)
        <h3 style="color: var(--text-primary); font-family: var(--font-display); font-size: 1.2rem; margin: 2rem 0 1rem;">
            <i class="fas fa-film" style="color: var(--accent-cyan); margin-right: 0.5rem;"></i> Latest Trailers
        </h3>
        <div class="trailer-grid">
            @foreach($homeTrailers as $homeTrailer)
                @php
                    $playSrc = $homeTrailer->source_type === 'file' && $homeTrailer->file_path
                        ? $homeTrailer->file_path
                        : $homeTrailer->trailer_url;
                @endphp
                <div class="trailer-card" onclick="openTrailerModal('{{ addslashes($playSrc ?? '') }}')">
                    <div class="trailer-thumb">
                        <span class="trailer-badge"><i class="fas fa-video"></i> TRAILER</span>
                        <img src="{{ $homeTrailer->thumb_url }}" alt="{{ $homeTrailer->title }}" loading="lazy">
                        <div class="trailer-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="trailer-info">
                        <h4>{{ $homeTrailer->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $homeTrailer->created_at?->format('M Y') ?? 'N/A' }}</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($homeTrailer->views) }} views</span>
                        </div>
                        <a href="{{ route('trailers.show', $homeTrailer->slug) }}" class="trailer-download" onclick="event.stopPropagation();" title="Go to {{ $homeTrailer->title }}">
                            <i class="fas fa-eye"></i> Watch Trailer
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Trending Series -->
<div class="section reveal">
    <div class="section-header">
        <h2><i class="fas fa-tv" style="color: var(--accent-purple); margin-right: 0.5rem; font-size: 1.2rem;"></i> Trending Series</h2>
        <a href="/series?sort=trending" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @if($trendingSeries->count() > 0)
        <div class="movie-grid">
            @foreach($trendingSeries as $series)
                <div class="movie-card" data-genre="{{ $series->genre ?? 'General' }}" onclick="location.href='{{ route('series.show', $series->id) }}'">
                    <div class="card-img-wrap">
                        <span class="card-badge" style="background: var(--accent-cyan);">SERIES</span>
                        <img class="card-img" src="{{ $series->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $series->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                        <button class="fav-heart" type="button" data-type="series" data-id="{{ $series->id }}" onclick="toggleFavorite(event,'series',{{ $series->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                    </div>
                    <div class="card-info">
                        <h4>{{ $series->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $series->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-layer-group"></i> {{ $series->seasons_count }} Season{{ $series->seasons_count > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center; padding:3rem; color: var(--text-muted);">No series available yet.</div>
    @endif
</div>

<!-- Recent Movies -->
<div class="section reveal">
    <div class="section-header">
        <h2><i class="fas fa-clock" style="color: var(--accent-cyan); margin-right: 0.5rem; font-size: 1.2rem;"></i> Recent Releases</h2>
        <a href="/movies?sort=recent" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @if($recentMovies->count() > 0)
        <div class="movie-grid">
            @foreach($recentMovies as $movie)
                <div class="movie-card" data-slug="{{ $movie->slug }}" data-id="{{ $movie->id }}" data-title="{{ $movie->title }}" data-poster="{{ $movie->poster_path }}" data-year="{{ $movie->release_year }}" data-duration="{{ $movie->duration }}" data-genre="{{ $movie->genre }}" data-description="{{ $movie->description }}" data-file="{{ $movie->file_path }}">
                    <div class="card-img-wrap">
                        <img class="card-img" src="{{ $movie->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $movie->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                        <button class="fav-heart" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event,'movie',{{ $movie->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                    </div>
                    <div class="card-info">
                        <h4>{{ $movie->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $movie->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-film"></i> {{ $movie->genre ?? 'General' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center; padding:3rem; color: var(--text-muted);">No recent releases.</div>
    @endif
</div>

<!-- New Series -->
<div class="section reveal">
    <div class="section-header">
        <h2><i class="fas fa-sparkles" style="color: var(--accent-red); margin-right: 0.5rem; font-size: 1.2rem;"></i> New Series</h2>
        <a href="/series?sort=recent" class="section-link">View all <i class="fas fa-arrow-right"></i></a>
    </div>
    @if($recentSeries->count() > 0)
        <div class="movie-grid">
            @foreach($recentSeries as $series)
                <div class="movie-card" data-genre="{{ $series->genre ?? 'General' }}" onclick="location.href='{{ route('series.show', $series->id) }}'">
                    <div class="card-img-wrap">
                        <img class="card-img" src="{{ $series->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $series->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                        <button class="fav-heart" type="button" data-type="series" data-id="{{ $series->id }}" onclick="toggleFavorite(event,'series',{{ $series->id }},this)" title="Add to favorites"><i class="fas fa-heart"></i></button>
                    </div>
                    <div class="card-info">
                        <h4>{{ $series->title }}</h4>
                        <div class="meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $series->release_year ?? 'N/A' }}</span>
                            <span><i class="fas fa-film"></i> {{ $series->genre ?? 'General' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center; padding:3rem; color: var(--text-muted);">No new series.</div>
    @endif
</div>

@include('partials.footer')

<!-- Movie Options Modal -->
<div id="movieModal" class="movie-modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-poster">
                <img id="modalPoster" src="" alt="">
            </div>
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

<!-- Video Player Modal -->
<div id="videoModal" class="video-modal">
    <button class="close-video" onclick="closeVideo()">&times;</button>
    <div class="video-container">
        <video id="videoPlayer" controls autoplay>
            <source id="videoSource" src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<!-- Trailer Modal -->
<div id="trailerModal" class="trailer-modal">
    <button class="trailer-close" onclick="closeTrailerModal()" aria-label="Close trailer">
        <i class="fas fa-times"></i>
    </button>
    <div class="trailer-wrapper" onclick="closeTrailerModal()">
        <div class="trailer-container">
            <iframe id="trailerFrame" src="" title="Movie Trailer"
                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display:none;"></iframe>
            <video id="trailerVideo" controls playsinline style="display:none;"></video>
        </div>
    </div>
</div>

<script>
    // Mobile Menu
    function toggleMenu() {
        const navLinks = document.getElementById('navLinks');
        const overlay = document.getElementById('mobileOverlay');
        navLinks.classList.toggle('active');
        overlay.classList.toggle('active');
        const menuBtn = document.querySelector('.menu-btn i');
        if (navLinks.classList.contains('active')) {
            menuBtn.classList.remove('fa-bars');
            menuBtn.classList.add('fa-times');
        } else {
            menuBtn.classList.remove('fa-times');
            menuBtn.classList.add('fa-bars');
        }
    }
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('navLinks').classList.remove('active');
            document.getElementById('mobileOverlay').classList.remove('active');
            const menuBtn = document.querySelector('.menu-btn i');
            menuBtn.classList.remove('fa-times');
            menuBtn.classList.add('fa-bars');
        });
    });

    // Navbar scroll
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 50) nav.classList.add('scrolled');
        else nav.classList.remove('scrolled');
    });

    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    revealEls.forEach(el => revealObs.observe(el));

    // Hero slider
    let heroSlides = document.querySelectorAll('#heroSlider .hero-slide');
    let heroDots = document.querySelectorAll('#heroSlider .hero-dot');
    let heroIndex = 0;
    let heroTimer = null;
    function showSlide(i) {
        if (!heroSlides.length) return;
        heroIndex = (i + heroSlides.length) % heroSlides.length;
        heroSlides.forEach((s, idx) => s.classList.toggle('active', idx === heroIndex));
        if (heroDots.length) heroDots.forEach((d, idx) => d.classList.toggle('active', idx === heroIndex));
        resetHeroTimer();
    }
    function nextSlide() { showSlide(heroIndex + 1); }
    function prevSlide() { showSlide(heroIndex - 1); }
    function goToSlide(i) { showSlide(i); }
    function resetHeroTimer() {
        if (heroTimer) clearInterval(heroTimer);
        if (heroSlides.length > 1) heroTimer = setInterval(() => showSlide(heroIndex + 1), 6500);
    }
    resetHeroTimer();

    // Modal
    let currentMovie = null;
    function openMovieModal(movieId, title, poster, year, duration, genre, description, filePath) {
        currentMovie = { id: movieId, title, poster, year, duration, genre, description, filePath };
        document.getElementById('modalPoster').src = poster || '/images/posters/dummy-poster.png';
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalYear').innerText = year || 'N/A';
        document.getElementById('modalDuration').innerText = duration || 'N/A';
        document.getElementById('modalGenre').innerText = genre || 'General';
        document.getElementById('modalDescription').innerText = description || 'No description available.';
        updateButtons(filePath);
        document.getElementById('movieModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function updateButtons(filePath) {
        const watchBtn = document.getElementById('watchBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        if (!filePath) return;
        const file = filePath.toLowerCase();
        if (file.endsWith('.avi')) {
            watchBtn.style.display = 'none';
            downloadBtn.style.display = 'inline-flex';
            downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download (AVI)';
        } else {
            watchBtn.style.display = 'inline-flex';
            downloadBtn.style.display = 'inline-flex';
            downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download';
        }
    }
    function closeModal() {
        document.getElementById('movieModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    function closeVideo() {
        const video = document.getElementById('videoPlayer');
        video.pause();
        video.src = '';
        document.getElementById('videoModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Watch
    document.getElementById('watchBtn').addEventListener('click', function() {
        if (currentMovie && currentMovie.id) {
            closeModal();
            const videoModal = document.getElementById('videoModal');
            const videoSource = document.getElementById('videoSource');
            const videoPlayer = document.getElementById('videoPlayer');
            videoSource.src = '/download/stream/' + currentMovie.id;
            videoPlayer.load();
            videoModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            alert('Stream not available for this movie yet.');
        }
    });

    // Download
    document.getElementById('downloadBtn').addEventListener('click', function() {
        if (currentMovie && currentMovie.id) {
            const a = document.createElement('a');
            a.href = '/download/movie/' + currentMovie.id;
            a.download = currentMovie.title + '.mp4';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            closeModal();
        } else {
            alert('Download not available for this movie yet.');
        }
    });

    // Card clicks - navigate to detail page
    document.querySelectorAll('.movie-card').forEach(card => {
        if (card.tagName === 'A') return;
        card.addEventListener('click', function(e) {
            const slug = this.getAttribute('data-slug');
            if (slug) {
                window.location.href = '/movies/' + slug;
                return;
            }
            e.preventDefault();
            openMovieModal(
                this.getAttribute('data-id'), this.getAttribute('data-title'),
                this.getAttribute('data-poster'), this.getAttribute('data-year'),
                this.getAttribute('data-duration'), this.getAttribute('data-genre'),
                this.getAttribute('data-description'),
                this.getAttribute('data-file')
            );
        });
    });

    // Genre filter
    const filterButtons = document.querySelectorAll('.genre-btn');
    const movieCards = document.querySelectorAll('.movie-card:not(a)');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const genre = this.getAttribute('data-genre');
            movieCards.forEach(card => {
                const movieGenre = card.getAttribute('data-genre');
                if (genre === 'all' || movieGenre === genre) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { closeModal(); closeVideo(); closeTrailerModal(); }
    });

    // Trailer modal
    function extractYouTubeId(url) {
        if (!url) return null;
        const m = url.match(/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
        return m ? m[1] : null;
    }
    function openTrailerModal(url) {
        const frame = document.getElementById('trailerFrame');
        const video = document.getElementById('trailerVideo');
        frame.style.display = 'none';
        frame.src = '';
        video.style.display = 'none';
        video.pause();
        video.removeAttribute('src');
        video.load();
        const id = extractYouTubeId(url);
        if (id) {
            frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1&iv_load_policy=3';
            frame.style.display = '';
        } else if (url) {
            video.src = url;
            video.style.display = '';
            video.play().catch(() => {});
        } else {
            return;
        }
        document.getElementById('trailerModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeTrailerModal() {
        const frame = document.getElementById('trailerFrame');
        frame.src = '';
        frame.style.display = 'none';
        const video = document.getElementById('trailerVideo');
        video.pause();
        video.removeAttribute('src');
        video.style.display = 'none';
        document.getElementById('trailerModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Guest identity + favourites
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
            if (data.favorited) {
                btn.classList.add('active');
                btn.title = 'Remove from favorites';
            } else {
                btn.classList.remove('active');
                btn.title = 'Add to favorites';
            }
        } catch (err) {}
    }
</script>
</body>
</html>
