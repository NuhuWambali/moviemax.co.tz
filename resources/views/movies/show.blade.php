<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $movie->title }} - MovieMax</title>
    @include('partials.seo', ['seoTitle' => ($movie->title ?? '') . (($movie->release_year ?? '') ? ' (' . $movie->release_year . ')' : '') . ' – Watch & Download | MovieMax', 'seoDescription' => 'Watch, stream or download ' . ($movie->title ?? 'this movie') . (($movie->release_year ?? '') ? ' (' . $movie->release_year . ')' : '') . ' online on MovieMax. View movie details, cast, trailer, genre, rating and more.', 'seoImagePath' => $movie->poster_url ?? asset('images/posters/dummy-poster.png'), 'seoType' => 'video.movie', 'seoJsonLd' => [['@type' => 'Movie', 'name' => $movie->title ?? '', 'description' => $movie->description ?? '', 'image' => $movie->poster_url ?? asset('images/posters/dummy-poster.png'), 'datePublished' => $movie->release_year ?? '', 'genre' => $movie->genre ?? '', 'url' => url('/movies/' . ($movie->slug ?? $movie->id))], ['@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Movies', 'item' => url('/movies')], ['@type' => 'ListItem', 'position' => 3, 'name' => $movie->title ?? '', 'item' => url('/movies/' . ($movie->slug ?? $movie->id))]]]]])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(229, 9, 20, 0.25), 0 0 40px rgba(229, 9, 20, 0.1); }
            50% { box-shadow: 0 0 30px rgba(229, 9, 20, 0.4), 0 0 60px rgba(229, 9, 20, 0.2); }
        }
        @keyframes posterGlow {
            0%, 100% { box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 40px rgba(229, 9, 20, 0.1), 0 0 80px rgba(229, 9, 20, 0.05); }
            50% { box-shadow: 0 25px 60px rgba(0,0,0,0.6), 0 0 60px rgba(229, 9, 20, 0.2), 0 0 100px rgba(229, 9, 20, 0.1); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
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

        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 180px;
            background: var(--bg-card);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.5rem 0;
            z-index: 1001;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            display: block;
            padding: 0.55rem 1.2rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
.dropdown-content a:hover {
            background: rgba(229, 9, 20, 0.1);
            color: var(--text-primary);
        }
        .dropdown-content a::after { display: none !important; }

        .search-icon {
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }
        .search-icon:hover { color: var(--accent-red); }

        .btn-signup {
            background: var(--accent-red);
            padding: 0.5rem 1.2rem !important;
            border-radius: 10px;
            color: white !important;
            font-weight: 600;
            border: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
        .btn-signup::after { display: none !important; }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-login::after { display: none !important; }

        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-primary);
            z-index: 1001;
            transition: 0.3s;
        }
        .menu-btn:hover { color: var(--accent-red); }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
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
            .dropdown { display: none; }
        }

        /* ============ MOVIE BACKDROP ============ */
        .movie-backdrop {
            position: relative;
            min-height: 70vh;
            background-size: cover;
            background-position: center 20%;
            background-attachment: fixed;
        }
        .movie-backdrop-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(9, 11, 15, 0.7) 0%, rgba(9, 11, 15, 0.2) 30%, rgba(9, 11, 15, 0.3) 70%, var(--bg-deep) 100%),
                linear-gradient(90deg, rgba(9, 11, 15, 0.9) 0%, rgba(9, 11, 15, 0.4) 50%, rgba(9, 11, 15, 0.2) 100%),
                radial-gradient(ellipse at 20% 50%, rgba(229, 9, 20, 0.06) 0%, transparent 60%);
        }

        /* ============ MOVIE CONTENT ============ */
        .movie-info {
            position: relative;
            max-width: 1400px;
            margin: -200px auto 0;
            padding: 0 5%;
            z-index: 2;
            animation: fadeUp 0.8s ease;
        }
        .movie-content {
            display: flex;
            gap: 3.5rem;
            align-items: flex-start;
        }
        .movie-poster {
            flex: 0 0 320px;
            animation: posterGlow 4s ease infinite;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .movie-poster:hover {
            transform: scale(1.03) translateY(-5px);
        }
        .movie-poster img {
            width: 100%;
            display: block;
        }

        .movie-details {
            flex: 1;
            padding-top: 1rem;
        }
        .movie-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4.5rem;
            letter-spacing: 2px;
            line-height: 1;
            margin-bottom: 1.2rem;
            background: #ffffff;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.8rem;
            color: var(--text-secondary);
        }
        .movie-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        .movie-meta i { color: var(--accent-red); }

        .movie-description {
            font-size: 1.02rem;
            line-height: 1.75;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 650px;
        }

        /* ============ ACTION BUTTONS ============ */
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        .action-buttons button {
            padding: 1rem 2.2rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .btn-watch {
            background: var(--accent-red);
            color: white;
            box-shadow: 0 4px 25px rgba(229, 9, 20, 0.35);
        }
        .btn-watch:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 35px rgba(229, 9, 20, 0.5);
        }
        .btn-watch::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-watch:hover::before { left: 100%; }

        .btn-download {
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--glass-border) !important;
            color: var(--text-primary);
        }
        .btn-download:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: rgba(229, 9, 20, 0.3) !important;
            transform: translateY(-4px);
        }

        .btn-trailer {
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--glass-border) !important;
            color: var(--text-primary);
        }
        .btn-trailer:hover {
            background: rgba(229, 9, 20, 0.1);
            color: var(--accent-red);
            transform: translateY(-4px);
        }

        /* ============ INTERACTIONS ============ */
        .interaction-bar {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }
        .interaction-btn {
            padding: 0.65rem 1.3rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            background: var(--bg-card);
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .interaction-btn i { transition: transform 0.3s ease; }
        .interaction-btn:hover { border-color: rgba(229, 9, 20, 0.4); transform: translateY(-2px); }
        .interaction-btn.active {
            background: var(--accent-red);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
        .interaction-btn.active i { transform: scale(1.15); }
        .interaction-btn.up.active { background: #fbbf24; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3); }
        .interaction-btn.down.active { background: #fbbf24; box-shadow: 0 4px 15px rgba(229, 9, 20,0.3); }
        .interaction-btn .count { font-weight: 700; }
        .views-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-bottom: 1rem;
        }
        .views-pill i { color: var(--accent-red); }

        /* ============ COMMENTS ============ */
        .comments-section {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
        }
        .comments-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 1px;
            margin-bottom: 1.2rem;
            padding-left: 1rem;
            position: relative;
        }
        .comments-section h2::before {
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
        .comments-section h2 i { color: var(--accent-red); margin-right: 0.4rem; }
        .comment-form { margin-bottom: 2rem; }
        .comment-form textarea {
            width: 100%;
            min-height: 90px;
            padding: 0.9rem 1rem;
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--text-primary);
            background: var(--bg-surface);
            resize: vertical;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .comment-form textarea:focus { outline: none; border-color: var(--accent-red); box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15); }
        .comment-form .comment-name {
            width: 100%;
            max-width: 300px;
            padding: 0.6rem 1rem;
            margin-bottom: 0.6rem;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--text-primary);
            background: var(--bg-surface);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .comment-form .comment-name:focus { outline: none; border-color: var(--accent-red); box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15); }
        .comment-form .form-actions { display: flex; gap: 0.8rem; margin-top: 0.8rem; align-items: center; }
        .comment-form .form-actions small { color: var(--text-muted); }
        .comment-submit {
            padding: 0.65rem 1.6rem;
            border-radius: 50px;
            background: var(--accent-red);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .comment-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(229, 9, 20, 0.3); }
        .comment-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .comment {
            padding: 1rem 0;
            border-bottom: 1px solid var(--glass-border);
        }
        .comment:last-child { border-bottom: none; }
        .comment-head { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.35rem; }
        .comment-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fbbf24;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }
        .comment-author { font-weight: 600; font-size: 0.85rem; color: var(--accent-red); }
        .comment-date { font-size: 0.72rem; color: var(--text-muted); margin-left: auto; }
        .comment-body { color: var(--text-primary); font-size: 0.9rem; line-height: 1.6; margin-bottom: 0.4rem; }
        .comment-actions { display: flex; gap: 1rem; align-items: center; }
        .comment-reply-btn, .comment-delete-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-family: 'Inter', sans-serif;
        }
        .comment-reply-btn:hover { color: var(--accent-red); }
        .comment-delete-btn:hover { color: #fbbf24; }
        .comment-replies {
            margin-left: 2.2rem;
            padding-left: 1.2rem;
            border-left: 2px solid var(--glass-border);
            margin-top: 0.6rem;
        }
        .reply-form { margin-top: 0.6rem; }
        .reply-form input, .reply-form textarea {
            width: 100%;
            padding: 0.55rem 0.9rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            color: var(--text-primary);
            background: var(--bg-surface);
            transition: border-color 0.3s;
        }
        .reply-form input:focus, .reply-form textarea:focus { outline: none; border-color: var(--accent-red); }
        .no-comments { color: var(--text-muted); text-align: center; padding: 2rem; font-size: 0.9rem; }
        @media (max-width: 768px) {
            .comment-replies { margin-left: 1rem; padding-left: 0.8rem; }
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
            animation: fadeUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .trailer-container iframe,
        .trailer-container video {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            background: #000;
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

        /* ============ RELATED SECTION ============ */
        .related-section {
            padding: 4rem 5% 2rem;
        }
        .related-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 1px;
            margin-bottom: 1.8rem;
            position: relative;
            padding-left: 1rem;
        }
        .related-section h2::before {
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
        .related-section h2 i {
            margin-right: 0.5rem;
            color: var(--accent-red);
            font-size: 1.2rem;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
        }

        .related-card {
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-card);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            text-decoration: none;
            display: block;
            border: 1px solid var(--glass-border);
            position: relative;
        }
        .related-card::before {
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
            z-index: 2;
        }
        .related-card:hover::before {
            background: rgba(229, 9, 20, 0.5);
        }
        .related-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px -15px rgba(229, 9, 20, 0.2), 0 0 60px -20px rgba(229, 9, 20, 0.15);
        }
        .related-card .card-img-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 2 / 3;
        }
        .related-card .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .related-card:hover .card-img { transform: scale(1.08); }
        .related-card .card-img-wrap::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, var(--bg-card) 0%, transparent 100%);
            pointer-events: none;
        }
        .related-card .card-overlay {
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
        .related-card:hover .card-overlay { opacity: 1; }
        .related-card .card-overlay i {
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
        .related-card:hover .card-overlay i { transform: scale(1); }
        .related-card .card-info { padding: 0.9rem 1rem 1rem; }
        .related-card .card-info h4 {
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .related-card .card-meta {
            display: flex;
            gap: 0.6rem;
            font-size: 0.7rem;
            color: var(--text-muted);
            flex-wrap: wrap;
            align-items: center;
        }
        .related-card .card-meta span { display: flex; align-items: center; gap: 0.25rem; }

        @media (max-width: 1100px) {
            .related-grid { grid-template-columns: repeat(4, 1fr); }
        }

        @media (max-width: 768px) {
            .related-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .related-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }
        }

        /* ============ FOOTER ============ */
        footer {
            text-align: center;
            padding: 3rem 5% 2rem;
            border-top: 1px solid var(--glass-border);
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 1px;
            background: var(--accent-red);
        }
        footer .footer-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 3px;
            background: var(--accent-red);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }
        footer p { color: var(--text-muted); font-size: 0.78rem; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .movie-backdrop {
                min-height: 50vh;
                background-attachment: scroll;
            }
            .movie-info {
                margin-top: -100px;
            }
            .movie-content {
                flex-direction: column;
                align-items: center;
            }
            .movie-poster {
                flex: 0 0 auto;
                max-width: 260px;
            }
            .movie-title {
                font-size: 2.5rem;
                text-align: center;
            }
            .movie-meta {
                justify-content: center;
            }
            .movie-description {
                text-align: center;
            }
            .action-buttons {
                justify-content: center;
            }
        }

        @media (min-width: 1440px) {
            .movie-title {
                font-size: 5.5rem;
            }
        }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

    <!-- Movie Backdrop Hero -->
    <div class="movie-backdrop" style="background-image: url('{{ $movie->poster_path }}')">
        <div class="movie-backdrop-overlay"></div>
    </div>

    <!-- Movie Content -->
    <div class="movie-info reveal">
        <div class="movie-content">
            <div class="movie-poster">
                <img src="{{ $movie->poster_path }}" alt="{{ $movie->title }}" fetchpriority="high">
            </div>

            <div class="movie-details">
                <h1 class="movie-title">{{ $movie->title }}</h1>

                <div class="movie-meta">
                    <span><i class="fas fa-calendar"></i> {{ $movie->release_year ?? 'N/A' }}</span>
                    <span><i class="fas fa-clock"></i> {{ $movie->duration ?? 'N/A' }}</span>
                    <span><i class="fas fa-film"></i> {{ $movie->genre ?? 'General' }}</span>
                    <span><i class="fas fa-language"></i> {{ $movie->language ?? 'English' }}</span>
                    <span><i class="fas fa-star" style="color: #ffd700;"></i> {{ $movie->rating ?? 'N/A' }}</span>
                </div>

                <p class="movie-description">{{ $movie->description }}</p>

                <div class="action-buttons">
                    <button class="btn-watch" onclick="showWatchOptions()">
                        <i class="fas fa-play"></i> Watch Now
                    </button>
                    <button class="btn-download" onclick="showDownloadOptions()">
                        <i class="fas fa-download"></i> Download
                    </button>
                    @if($movie->trailer_url)
                        <button class="btn-trailer" onclick="watchTrailer('{{ $movie->trailer_url }}')">
                            <i class="fas fa-video"></i> Trailer
                        </button>
                    @endif
                </div>

                <div class="interaction-bar">
                    <button class="interaction-btn {{ $interaction['favorited'] ? 'active' : '' }}" type="button" data-type="movie" data-id="{{ $movie->id }}" onclick="toggleFavorite(event, 'movie', {{ $movie->id }}, this)" title="Add to favorites">
                        <i class="fas fa-heart"></i> <span class="count">Favourite</span>
                    </button>
                    <button class="interaction-btn up {{ $interaction['my_reaction'] === 'like' ? 'active' : '' }}" type="button" onclick="react(event, 'movie', {{ $movie->id }}, 'like', this)">
                        <i class="fas fa-thumbs-up"></i> <span class="count">{{ $interaction['likes'] ?? 0 }}</span>
                    </button>
                    <button class="interaction-btn down {{ $interaction['my_reaction'] === 'dislike' ? 'active' : '' }}" type="button" onclick="react(event, 'movie', {{ $movie->id }}, 'dislike', this)">
                        <i class="fas fa-thumbs-down"></i> <span class="count">{{ $interaction['dislikes'] ?? 0 }}</span>
                    </button>
                </div>
                <div class="views-pill">
                    <i class="fas fa-eye"></i> {{ number_format($movie->views ?? 0) }} views
                </div>
            </div>
        </div>
    </div>

    @php
        $visKey = request()->cookie(App\Http\Controllers\InteractionController::VISITOR_COOKIE);
        $authId = Auth::id();
        $canDeleteComment = function ($c) use ($visKey, $authId) {
            if ($authId && $c->user_id === $authId) return true;
            if (!$c->user_id && $c->visitor_key && $visKey && $c->visitor_key === $visKey) return true;
            return false;
        };
    @endphp

    <!-- Comments -->
    <div class="comments-section reveal">
        <h2><i class="fas fa-comments"></i> Comments ({{ $comments->count() }})</h2>
        <form class="comment-form" onsubmit="submitComment(event)">
            <textarea id="commentBody" placeholder="Share your thoughts..." required></textarea>
            <div class="form-actions">
                <button type="submit" class="comment-submit" id="commentSubmit"><i class="fas fa-paper-plane"></i> Post Comment</button>
                <small>Comments are public. Please keep it respectful.</small>
            </div>
        </form>
        <div id="commentsList">
        @forelse($comments as $comment)
            <div class="comment" id="comment-{{ $comment->id }}">
                <div class="comment-head">
                    <div class="comment-avatar">{{ mb_strtoupper(mb_substr($comment->author_name, 0, 1)) }}</div>
                    <span class="comment-author">{{ $comment->author_name }}</span>
                    <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="comment-body">{!! nl2br(e($comment->body)) !!}</div>
                <div class="comment-actions">
                    <button type="button" class="comment-reply-btn" onclick="toggleReplyForm({{ $comment->id }})"><i class="fas fa-reply"></i> Reply</button>
                    @if($canDeleteComment($comment))
                        <button type="button" class="comment-delete-btn" onclick="deleteComment({{ $comment->id }})"><i class="fas fa-trash"></i> Delete</button>
                    @endif
                </div>
                <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
                    <textarea id="replyBody-{{ $comment->id }}" placeholder="Write a reply..." rows="2" required></textarea>
                    <div class="form-actions">
                        <button type="button" class="comment-submit" onclick="submitReply({{ $comment->id }})"><i class="fas fa-paper-plane"></i> Post Reply</button>
                    </div>
                </div>
                @if($comment->replies->count() > 0)
                    <div class="comment-replies">
                        @foreach($comment->replies as $reply)
                            <div class="comment" id="comment-{{ $reply->id }}">
                                <div class="comment-head">
                                    <div class="comment-avatar">{{ mb_strtoupper(mb_substr($reply->author_name, 0, 1)) }}</div>
                                    <span class="comment-author">{{ $reply->author_name }}</span>
                                    <span class="comment-date">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="comment-body">{!! nl2br(e($reply->body)) !!}</div>
                                <div class="comment-actions">
                                    @if($canDeleteComment($reply))
                                        <button type="button" class="comment-delete-btn" onclick="deleteComment({{ $reply->id }})"><i class="fas fa-trash"></i> Delete</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="no-comments">No comments yet. Be the first to share your thoughts!</div>
        @endforelse
        </div>
    </div>

    <!-- Related Movies -->
    @if($related->count() > 0)
    <div class="related-section reveal">
        <h2><i class="fas fa-film"></i> You May Also Like</h2>
        <div class="related-grid">
            @foreach($related as $relatedMovie)
                <a href="{{ route('movies.show', $relatedMovie->slug) }}" class="related-card">
                    <div class="card-img-wrap">
                        <img class="card-img" src="{{ $relatedMovie->poster_path }}" alt="{{ $relatedMovie->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="card-info">
                        <h4>{{ $relatedMovie->title }}</h4>
                        <div class="card-meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $relatedMovie->release_year ?? 'N/A' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Most Watched -->
    @if($mostWatched->count() > 0)
    <div class="related-section reveal">
        <h2><i class="fas fa-fire"></i> Most Watched</h2>
        <div class="related-grid">
            @foreach($mostWatched as $item)
                <a href="{{ route('movies.show', $item->slug) }}" class="related-card">
                    <div class="card-img-wrap">
                        <img class="card-img" src="{{ $item->poster_path }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="card-info">
                        <h4>{{ $item->title }}</h4>
                        <div class="card-meta">
                            <span><i class="fas fa-eye"></i> {{ number_format($item->views ?? 0) }}</span>
                            <span><i class="fas fa-calendar-alt"></i> {{ $item->release_year ?? 'N/A' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Top Rated -->
    @if($topRated->count() > 0)
    <div class="related-section reveal">
        <h2><i class="fas fa-star"></i> Top Rated</h2>
        <div class="related-grid">
            @foreach($topRated as $item)
                <a href="{{ route('movies.show', $item->slug) }}" class="related-card">
                    <div class="card-img-wrap">
                        <img class="card-img" src="{{ $item->poster_path }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="card-overlay"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="card-info">
                        <h4>{{ $item->title }}</h4>
                        <div class="card-meta">
                            <span><i class="fas fa-star"></i> {{ $item->rating ?? 'N/A' }}/10</span>
                            <span><i class="fas fa-calendar-alt"></i> {{ $item->release_year ?? 'N/A' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    @include('partials.footer')

    <!-- Trailer Modal -->
    <div id="trailerModal" class="trailer-modal">
        <button class="trailer-close" onclick="closeTrailerModal()" aria-label="Close trailer">
            <i class="fas fa-times"></i>
        </button>
        <div class="trailer-wrapper" onclick="closeTrailerModal()">
            <div class="trailer-container">
                <iframe id="trailerFrame" src="" title="Movie Trailer"
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div id="streamModal" class="trailer-modal">
        <button class="trailer-close" onclick="closeStreamModal()" aria-label="Close player">
            <i class="fas fa-times"></i>
        </button>
        <div class="trailer-wrapper" onclick="if(event.target===this)closeStreamModal()">
            <div class="trailer-container">
                <video id="streamVideo" controls autoplay playsinline preload="metadata"></video>
            </div>
        </div>
    </div>

    <script>
        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
        }, { threshold: 0.1 });
        revealEls.forEach(el => revealObs.observe(el));

        function showWatchOptions() {
            Swal.fire({
                title: 'Watch {{ $movie->title }}',
                text: 'Choose your streaming quality',
                icon: 'info',
                background: '#161c26',
                color: '#ffffff',
                showCancelButton: true,
                confirmButtonColor: '#e50914',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-play"></i> Watch Now',
                cancelButtonText: 'Cancel',
                html: `
                    <div style="text-align: left; margin: 1rem 0;">
                        <p style="margin-bottom: 1rem; color: #9ca3af;">Select streaming quality:</p>
                        <button onclick="streamMovie('480p')" style="width: 100%; padding: 0.75rem; margin: 0.5rem 0; background: var(--bg-surface); border: 1px solid var(--glass-border); border-radius: 8px; color: #ffffff; cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-tv"></i> 480p - Standard
                        </button>
                        <button onclick="streamMovie('720p')" style="width: 100%; padding: 0.75rem; margin: 0.5rem 0; background: var(--bg-surface); border: 1px solid var(--glass-border); border-radius: 8px; color: #ffffff; cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-tv"></i> 720p - HD
                        </button>
                        <button onclick="streamMovie('1080p')" style="width: 100%; padding: 0.75rem; margin: 0.5rem 0; background: var(--bg-surface); border: 1px solid var(--glass-border); border-radius: 8px; color: #ffffff; cursor: pointer; transition: 0.3s;">
                            <i class="fas fa-tv"></i> 1080p - Full HD
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                didOpen: () => {
                    const popup = Swal.getPopup();
                    const confirmBtn = Swal.getConfirmButton();
                    if (confirmBtn) confirmBtn.remove();
                }
            });
        }

        function showDownloadOptions() {
            Swal.fire({
                title: 'Download {{ $movie->title }}',
                text: 'Get ready to download this movie.',
                icon: 'info',
                background: '#161c26',
                color: '#ffffff',
                showCancelButton: true,
                confirmButtonColor: '#e50914',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-download"></i> Download Now',
                cancelButtonText: 'Cancel',
                html: `
                    <div style="text-align: left; margin: 1rem 0;">
                        <p><i class="fas fa-check-circle" style="color: #e50914;"></i> High quality video</p>
                        <p><i class="fas fa-check-circle" style="color: #e50914;"></i> Lifetime access</p>
                        <p><i class="fas fa-check-circle" style="color: #e50914;"></i> Download on any device</p>
                    </div>
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    @guest
                        Swal.fire({
                            title: 'Login Required',
                            text: 'Please login to download movies',
                            icon: 'warning',
                            background: '#161c26',
                            color: '#fff',
                            confirmButtonColor: '#e50914',
                            confirmButtonText: 'Login Now'
                        }).then(() => {
                            window.location.href = '/login?redirect={{ route('movies.show', $movie->slug) }}';
                        });
                    @else
                        window.location.href = '/download/movie/{{ $movie->id }}';
                    @endguest
                }
            });
        }

        const movieFile = {!! json_encode($movie->file_path) !!};
        const moviePoster = {!! json_encode($movie->poster_path) !!};
        const movieTrackId = {{ $movie->id }};
        const mmAuth = @json(auth()->check());
        const serverResume = {{ $resume ? (int) $resume->progress_seconds : 0 }};

        function playbackSave(progress, duration) {
            const dur = Math.round(duration || 0);
            const data = { watchable_type: 'App\\Models\\Movie', watchable_id: movieTrackId, progress: Math.round(progress || 0), duration: dur };
            try {
                localStorage.setItem('mm_progress_' + movieTrackId, JSON.stringify({ p: Math.round(progress || 0), d: dur }));
            } catch (e) {}
            if (mmAuth) {
                fetch('/watch/progress', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                }).catch(() => {});
            }
        }

        function streamMovie(quality) {
            Swal.close();
            const video = document.getElementById('streamVideo');
            const isExternal = /^https?:\/\//i.test(movieFile);
            video.src = isExternal ? movieFile : '{{ route('stream.movie', $movie->id) }}';
            if (moviePoster) video.poster = moviePoster;
            document.getElementById('streamModal').classList.add('active');
            document.body.style.overflow = 'hidden';

            let resumeAt = serverResume;
            if (!mmAuth) {
                try {
                    const g = JSON.parse(localStorage.getItem('mm_progress_' + movieTrackId) || 'null');
                    if (g && g.p > 5) resumeAt = g.p;
                } catch (e) {}
            }

            let lastSave = 0;
            video.onloadedmetadata = () => {
                if (resumeAt > 5 && resumeAt < (video.duration - 15)) {
                    video.currentTime = resumeAt;
                }
            };
            video.ontimeupdate = () => {
                const now = Date.now();
                if (video.duration && video.duration - video.currentTime <= 8) {
                    playbackSave(video.duration, video.duration);
                    return;
                }
                if (now - lastSave < 5000) return;
                lastSave = now;
                playbackSave(video.currentTime, video.duration);
            };
            video.onended = () => playbackSave(video.duration, video.duration);
            video.play().catch(() => {});
            mmViewTracker.trackVideo(video, 'movie', {{ $movie->id }});
        }

        function closeStreamModal() {
            const video = document.getElementById('streamVideo');
            video.pause();
            video.onloadedmetadata = null;
            video.ontimeupdate = null;
            video.onended = null;
            video.removeAttribute('src');
            video.load();
            document.getElementById('streamModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function watchTrailer(url) {
            if (url) {
                const id = extractYouTubeId(url);
                if (id) {
                    const frame = document.getElementById('trailerFrame');
                    frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1&iv_load_policy=3';
                    document.getElementById('trailerModal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                    return;
                }
            }
            Swal.fire({
                title: 'No Trailer Available',
                text: 'Trailer for this movie is not available yet.',
                icon: 'info',
                background: '#161c26',
                color: '#ffffff',
                confirmButtonColor: '#e50914'
            });
        }

        function extractYouTubeId(url) {
            if (!url) return null;
            const m = url.match(/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
            return m ? m[1] : null;
        }

        function closeTrailerModal() {
            const frame = document.getElementById('trailerFrame');
            frame.src = '';
            document.getElementById('trailerModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // ============ GUEST IDENTITY + INTERACTIONS ============
        function ensureVkey() {
            let k = localStorage.getItem('mm_vkey');
            if (!k) {
                k = 'mm-' + Math.random().toString(36).slice(2) + Date.now().toString(36);
                localStorage.setItem('mm_vkey', k);
            }
            document.cookie = 'mm_vkey=' + k + '; path=/; max-age=' + (60 * 60 * 24 * 365 * 10);
        }
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
            if (e) { e.preventDefault(); e.stopPropagation(); }
            try {
                const data = await jsonPost('/interactions/favorite-toggle', { type: type, id: id });
                if (handleLoginRequired(data)) return;
                if (data.favorited) { btn.classList.add('active'); btn.title = 'Remove from favorites'; }
                else { btn.classList.remove('active'); btn.title = 'Add to favorites'; }
            } catch (err) {}
        }
        async function react(e, type, id, kind, btn) {
            try {
                const data = await jsonPost('/interactions/react', { type: type, id: id, reaction: kind });
                if (handleLoginRequired(data)) return;
                const up = document.querySelector('.interaction-btn.up');
                const down = document.querySelector('.interaction-btn.down');
                up.classList.toggle('active', data.reaction === 'like');
                down.classList.toggle('active', data.reaction === 'dislike');
                up.querySelector('.count').textContent = data.like_count;
                down.querySelector('.count').textContent = data.dislike_count;
            } catch (err) {}
        }
        function toggleReplyForm(id) {
            const el = document.getElementById('reply-form-' + id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
        async function submitComment(ev) {
            ev.preventDefault();
            const body = document.getElementById('commentBody').value.trim();
            if (!body) return;
            const btn = document.getElementById('commentSubmit');
            btn.disabled = true;
            try {
                const data = await jsonPost('/interactions/comment', {
                    type: 'movie',
                    id: {{ $movie->id }},
                    body: body
                });
                if (handleLoginRequired(data)) { btn.disabled = false; return; }
                window.location.reload();
            } catch (err) {
                btn.disabled = false;
            }
        }
        async function submitReply(rootId) {
            const body = document.getElementById('replyBody-' + rootId).value.trim();
            if (!body) return;
            try {
                const data = await jsonPost('/interactions/comment', {
                    type: 'movie',
                    id: {{ $movie->id }},
                    body: body,
                    parent_id: rootId
                });
                if (handleLoginRequired(data)) return;
                window.location.reload();
            } catch (err) {}
        }
        async function deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;
            try {
                const res = await fetch('/interactions/comment/' + commentId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (handleLoginRequired(data)) return;
                if (data.ok) window.location.reload();
                else if (data.error) Swal.fire({ title: 'Oops', text: data.error, icon: 'error', background: '#161c26', color: '#fff', confirmButtonColor: '#e50914' });
            } catch (err) {}
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeTrailerModal(); }
        });

        // Search
        document.querySelector('.search-icon')?.addEventListener('click', function() {
            const searchTerm = prompt('Search for a movie or series:');
            if (searchTerm && searchTerm.trim()) {
                window.location.href = '/movies?search=' + encodeURIComponent(searchTerm);
            }
        });

        // Trigger reveal on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.reveal').forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight) {
                    el.classList.add('visible');
                }
            });
        });
    </script>
@include('partials.view-tracker')
</body>
</html>
