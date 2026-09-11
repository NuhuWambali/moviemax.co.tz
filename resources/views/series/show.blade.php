{{-- resources/views/series/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $series->title }} - MovieMax</title>
    @include('partials.seo', ['seoTitle' => ($series->title ?? '') . (($series->release_year ?? '') ? ' (' . $series->release_year . ')' : '') . ' – Watch & Download | MovieMax', 'seoDescription' => 'Watch, stream or download ' . ($series->title ?? 'this series') . (($series->release_year ?? '') ? ' (' . $series->release_year . ')' : '') . ' online on MovieMax. View series details, cast, trailer, genre, rating and more.', 'seoImagePath' => $series->poster_path ? url($series->poster_path) : asset('images/posters/dummy-poster.png'), 'seoType' => 'video.tv_show', 'seoJsonLd' => [['@type' => 'TVSeries', 'name' => $series->title ?? '', 'description' => $series->description ?? '', 'image' => $series->poster_path ? url($series->poster_path) : asset('images/posters/dummy-poster.png'), 'startDate' => $series->release_year ?? '', 'genre' => $series->genre ?? '', 'url' => url('/series/' . $series->id)], ['@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'TV Series', 'item' => url('/series')], ['@type' => 'ListItem', 'position' => 3, 'name' => $series->title ?? '', 'item' => url('/series/' . $series->id)]]]]])
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ============ NAVBAR ============ */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            background: rgba(10, 13, 18, 0.85);
            z-index: 1000;
            border-bottom: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 3px;
            text-decoration: none;
            z-index: 1001;
            background: var(--accent-red);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradientShift 4s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
            font-size: 0.9rem;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--accent-red);
        }

        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-primary);
            z-index: 1001;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            min-width: 180px;
            border-radius: 12px;
            padding: 0.5rem 0;
            z-index: 1;
            top: 30px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
        }

        .dropdown-content a:hover {
            background: rgba(229, 9, 20, 0.12);
            color: var(--accent-red);
        }

        .container {
            max-width: 1750px;
            margin: 0 auto;
            padding: 100px 5% 3rem;
        }

        /* ============ SERIES BANNER ============ */
        .series-banner-wrapper {
            position: relative;
            width: 100%;
            height: 600px;
            margin-top: -100px;
            overflow: hidden;
        }

        .series-banner-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            transform: scale(1.05);
            transition: transform 8s ease;
        }

        .series-banner-wrapper:hover .series-banner-bg {
            transform: scale(1);
        }

        .series-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(9, 11, 15, 0.3) 0%, rgba(9, 11, 15, 0) 30%, rgba(9, 11, 15, 0.6) 60%, var(--bg-deep) 100%),
                linear-gradient(90deg, rgba(9, 11, 15, 0.7) 0%, transparent 50%),
                linear-gradient(0deg, var(--bg-deep) 0%, transparent 25%);
        }

        .series-banner-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 3rem 5%;
            display: flex;
            gap: 2rem;
            align-items: flex-end;
            z-index: 2;
        }

        /* ============ SERIES POSTER ============ */
        .series-poster {
            width: 180px;
            min-width: 180px;
            border-radius: 16px;
            object-fit: cover;
            aspect-ratio: 2/3;
            box-shadow:
                0 20px 50px rgba(0,0,0,0.25),
                0 0 0 1px rgba(229, 9, 20, 0.2),
                0 0 40px rgba(229, 9, 20, 0.1);
            transition: transform 0.4s, box-shadow 0.4s;
        }

        .series-poster:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow:
                0 30px 60px rgba(0,0,0,0.3),
                0 0 0 1px rgba(229, 9, 20, 0.4),
                0 0 60px rgba(229, 9, 20, 0.15);
        }

        /* ============ SERIES INFO ============ */
        .series-info {
            flex: 1;
            padding-bottom: 0.5rem;
        }

        .series-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4rem;
            letter-spacing: 2px;
            line-height: 1;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.75rem;
        }

        .series-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .series-meta span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .series-meta i {
            color: var(--accent-red);
        }

        .series-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 700px;
        }

        /* ============ LAYOUT ============ */
        .series-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 2rem;
            margin-top: -60px;
            position: relative;
            z-index: 3;
        }

        /* ============ EPISODES CARD ============ */
        .episodes-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }

        .episodes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .episodes-header h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .season-select {
            background: var(--bg-surface);
            border: 1px solid var(--glass-border);
            padding: 0.55rem 1.2rem;
            border-radius: 30px;
            color: var(--text-primary);
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            outline: none;
            transition: 0.3s;
        }

        .season-select:focus {
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.2);
        }

        .episode {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--glass-border);
            transition: 0.25s;
            border-radius: 12px;
            margin: 2px 0;
        }

        .episode:last-child {
            border-bottom: none;
        }

        .episode:hover {
            background: rgba(229, 9, 20, 0.06);
            transform: translateX(4px);
        }

        .episode.completed { background: rgba(76, 175, 80, 0.06); }
        .episode.completed .episode-name { color: rgba(255, 255, 255, 0.55); }
        .episode-checked { color: #4caf50; margin-left: 0.35rem; }

        .episode-left {
            flex: 1;
        }

        .episode-num {
            font-size: 0.75rem;
            color: var(--accent-cyan);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .episode-name {
            font-size: 1rem;
            font-weight: 500;
            margin-top: 0.25rem;
            color: var(--text-primary);
        }

        .episode-duration {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .episode-duration i {
            margin-right: 0.3rem;
        }

        .episode-buttons {
            display: flex;
            gap: 0.6rem;
        }

        .btn-watch, .btn-download {
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.25s;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-watch {
            background: var(--accent-red);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        .btn-watch:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(229, 9, 20, 0.45);
        }

        .btn-download {
            background: rgba(255,255,255,0.08);
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
        }

        .btn-download:hover {
            background: rgba(229, 9, 20, 0.1);
            color: var(--accent-red);
            border-color: rgba(229, 9, 20, 0.3);
            transform: translateY(-1px);
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 1.2rem;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .sidebar-card:hover {
            border-color: rgba(229, 9, 20, 0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .sidebar-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.15rem;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .sidebar-card h3 i {
            color: var(--accent-red);
            margin-right: 0.5rem;
        }

        /* Related Series */
        .related-grid {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .related-item {
            display: flex;
            gap: 0.8rem;
            align-items: center;
            padding: 0.5rem;
            border-radius: 12px;
            transition: 0.25s;
            text-decoration: none;
            color: var(--text-primary);
        }

        .related-item:hover {
            background: rgba(229, 9, 20, 0.08);
            transform: translateX(3px);
        }

        .related-img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .related-info {
            flex: 1;
        }

        .related-title {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .related-meta {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Top Downloads */
        .top-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .top-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 10px;
            transition: 0.25s;
            text-decoration: none;
            color: var(--text-primary);
        }

        .top-item:hover {
            background: rgba(229, 9, 20, 0.06);
        }

        .top-rank {
            font-size: 1.1rem;
            font-weight: 800;
            background: var(--accent-red);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            width: 35px;
        }

        .top-title {
            flex: 1;
            font-size: 0.85rem;
        }

        .top-downloads {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* ============ SAME GENRE SECTION ============ */
        .genre-section {
            margin-top: 2rem;
        }

        .genre-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .genre-title i {
            color: var(--accent-red);
            margin-right: 0.5rem;
        }

        .genre-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .genre-item {
            background: var(--bg-card);
            border-radius: 14px;
            overflow: hidden;
            text-decoration: none;
            transition: 0.3s;
            border: 1px solid var(--glass-border);
        }

        .genre-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            border-color: rgba(229, 9, 20, 0.25);
        }

        .genre-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .genre-item-info {
            padding: 0.6rem 0.8rem;
        }

        .genre-item-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .genre-item-seasons {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* ============ EMPTY STATE ============ */
        .empty {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        /* ============ INTERACTIONS ============ */
        .interaction-bar {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            margin: 1.2rem 0 0.5rem;
        }
        .interaction-btn {
            padding: 0.6rem 1.3rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.06);
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .interaction-btn:hover { border-color: rgba(229, 9, 20, 0.4); transform: translateY(-2px); }
        .interaction-btn.active {
            background: var(--accent-red);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
        .interaction-btn.up.active { background: #fbbf24; box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3); }
        .interaction-btn.down.active { background: #fbbf24; box-shadow: 0 4px 15px rgba(229, 9, 20,0.3); }
        .interaction-btn .count { font-weight: 700; }
        .views-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.82rem;
        }
        .views-pill i { color: var(--accent-red); }

        /* ============ COMMENTS ============ */
        .comments-section {
            max-width: 1000px;
            margin: 2rem 0;
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
            font-family: 'Inter', sans-serif;
        }
        .comment-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(229, 9, 20, 0.3); }
        .comment { padding: 1rem 0; border-bottom: 1px solid var(--glass-border); }
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
        .comment-replies { margin-left: 2.2rem; padding-left: 1.2rem; border-left: 2px solid var(--glass-border); margin-top: 0.6rem; }
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
            .interaction-bar { justify-content: center; }
        }

        /* ============ VIDEO MODAL ============ */
        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            z-index: 2000;
        }

        .close-video {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(0,0,0,0.7);
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
            font-size: 2rem;
            cursor: pointer;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            z-index: 2001;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-video:hover {
            background: var(--accent-red);
            border-color: var(--accent-red);
        }

        .video-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1000px) {
            .series-layout {
                grid-template-columns: 1fr;
            }
            .sidebar {
                order: 2;
            }
            .main-col {
                order: 1;
            }
        }

        @media (max-width: 768px) {
            .menu-btn {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 72%;
                height: 100vh;
                background: rgba(10, 13, 18, 0.97);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2rem;
                transition: 0.35s ease;
                z-index: 1000;
                border-left: 1px solid rgba(229, 9, 20, 0.2);
            }

            .nav-links.active {
                right: 0;
            }

            .nav-links a {
                font-size: 1.1rem;
            }

            .series-banner-wrapper {
                height: 400px;
            }

            .series-banner-bg {
                background-attachment: scroll;
            }

            .series-banner-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .series-poster {
                width: 140px;
                min-width: 140px;
            }

            .series-title {
                font-size: 2.8rem;
            }

            .series-meta {
                justify-content: center;
            }

            .series-description {
                max-width: 100%;
            }

            .episode {
                flex-direction: column;
                text-align: center;
                gap: 0.8rem;
            }

            .episode-buttons {
                justify-content: center;
            }

            .genre-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            }
        }
    </style>
</head>
<body>
@include('partials.loader')    <!-- Navbar -->
    @include('partials.navbar')

    <div class="container">
        <!-- Series Banner -->
        <div class="series-banner-wrapper">
            <div class="series-banner-bg" style="background-image: url('{{ $series->backdrop_path ?? $series->poster_path }}')"></div>
            <div class="series-banner-overlay"></div>
            <div class="series-banner-content">
                <img class="series-poster" src="{{ $series->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $series->title }}" fetchpriority="high">
                <div class="series-info">
                    <h1 class="series-title">{{ $series->title }}</h1>
                    <div class="series-meta">
                        <span><i class="fas fa-calendar"></i> {{ $series->release_year }}</span>
                        <span><i class="fas fa-tag"></i> {{ $series->genre }}</span>
                        <span><i class="fas fa-layer-group"></i> {{ $series->seasons_count }} Seasons</span>
                        <span><i class="fas fa-download"></i> {{ number_format($series->download_count ?? 0) }} downloads</span>
                    </div>
                    <p class="series-description">{{ $series->description }}</p>
                    <div class="interaction-bar">
                        <button class="interaction-btn {{ $interaction['favorited'] ? 'active' : '' }}" type="button" data-type="series" data-id="{{ $series->id }}" onclick="toggleFavorite(event, 'series', {{ $series->id }}, this)" title="Add to favorites">
                            <i class="fas fa-heart"></i> <span class="count">Favourite</span>
                        </button>
                        <button class="interaction-btn up {{ $interaction['my_reaction'] === 'like' ? 'active' : '' }}" type="button" onclick="react(event, 'series', {{ $series->id }}, 'like', this)">
                            <i class="fas fa-thumbs-up"></i> <span class="count">{{ $interaction['likes'] ?? 0 }}</span>
                        </button>
                        <button class="interaction-btn down {{ $interaction['my_reaction'] === 'dislike' ? 'active' : '' }}" type="button" onclick="react(event, 'series', {{ $series->id }}, 'dislike', this)">
                            <i class="fas fa-thumbs-down"></i> <span class="count">{{ $interaction['dislikes'] ?? 0 }}</span>
                        </button>
                    </div>
                    <div class="views-pill">
                        <i class="fas fa-eye"></i> {{ number_format($series->views ?? 0) }} views
                    </div>
                </div>
            </div>
        </div>

        <div class="series-layout">
            <!-- Main Column -->
            <div class="main-col">
                <!-- Episodes Card -->
                <div class="episodes-card">
                    <div class="episodes-header">
                        <h3><i class="fas fa-list"></i> All Episodes</h3>
                        @php
                            $seasons = $episodes->groupBy('season_number');
                        @endphp
                        @if($seasons->count() > 1)
                            <select class="season-select" id="seasonSelect">
                                @foreach($seasons as $num => $eps)
                                    <option value="{{ $num }}">Season {{ $num }} ({{ $eps->count() }} episodes)</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div id="episodesList">
                        @php $firstSeason = $seasons->keys()->first(); @endphp
                        @forelse($episodes as $episode)
                            @php
                                $epId = $episode->id;
                                $epDone = isset($episodeProgress[$epId]) && $episodeProgress[$epId]['completed'];
                                $epResume = isset($episodeProgress[$epId]) ? (int) $episodeProgress[$epId]['progress'] : 0;
                            @endphp
                            <div class="episode {{ $epDone ? 'completed' : '' }}" data-episode-id="{{ $episode->id }}" data-resume="{{ $epResume }}" data-season="{{ $episode->season_number }}" style="{{ $episode->season_number != $firstSeason ? 'display:none' : '' }}">
                                <div class="episode-left">
                                    <div class="episode-num">Episode {{ $episode->episode_number }} {!! $epDone ? '<i class="fas fa-check-circle episode-checked" title="Watched"></i>' : '' !!}</div>
                                    <div class="episode-name">{{ $episode->episode_title ?? $episode->title }}</div>
                                    <div class="episode-duration"><i class="fas fa-clock"></i> {{ $episode->duration_label }}</div>
                                </div>
                                <div class="episode-buttons">
                                    <button class="btn-watch" onclick="watchEpisode({{ $episode->id }}, '{{ addslashes($episode->episode_title ?? $episode->title) }}')">
                                        <i class="fas fa-play"></i> {{ $epDone ? 'Re-watch' : 'Watch' }}
                                    </button>
                                    <button class="btn-download" onclick="downloadEpisode({{ $episode->id }}, '{{ addslashes($episode->episode_title ?? $episode->title) }}')">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="empty">No episodes available yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Same Genre Series -->
                @if($sameGenreSeries->count() > 0)
                <div class="genre-section">
                    <h3 class="genre-title"><i class="fas fa-tag"></i> More {{ $series->genre }} Series</h3>
                    <div class="genre-grid">
                        @foreach($sameGenreSeries as $item)
                            <a href="{{ route('series.show', $item->id) }}" class="genre-item">
                                <img class="genre-img" src="{{ $item->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $item->title }}" loading="lazy">
                                <div class="genre-item-info">
                                    <div class="genre-item-title">{{ $item->title }}</div>
                                    <div class="genre-item-seasons">{{ $item->seasons_count }} Seasons</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Related Series -->
                @if($relatedSeries->count() > 0)
                <div class="sidebar-card">
                    <h3><i class="fas fa-link"></i> You May Also Like</h3>
                    <div class="related-grid">
                        @foreach($relatedSeries as $item)
                            <a href="{{ route('series.show', $item->id) }}" class="related-item">
                                <img class="related-img" src="{{ $item->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $item->title }}" loading="lazy">
                                <div class="related-info">
                                    <div class="related-title">{{ $item->title }}</div>
                                    <div class="related-meta">{{ $item->release_year }} • {{ $item->seasons_count }} Seasons</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Top Downloaded Series -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-fire"></i> Top Downloaded Series</h3>
                    <div class="top-list">
                        @foreach($topDownloads as $index => $item)
                            <a href="{{ route('series.show', $item->id) }}" class="top-item">
                                <div class="top-rank">#{{ $index + 1 }}</div>
                                <div class="top-title">{{ $item->title }}</div>
                                <div class="top-downloads">
                                    <i class="fas fa-download"></i> {{ number_format($item->download_count ?? 0) }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Recently Added Series -->
                @if($recentSeries->count() > 0)
                <div class="sidebar-card">
                    <h3><i class="fas fa-clock"></i> Recently Added</h3>
                    <div class="related-grid">
                        @foreach($recentSeries as $item)
                            <a href="{{ route('series.show', $item->id) }}" class="related-item">
                                <img class="related-img" src="{{ $item->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $item->title }}" loading="lazy">
                                <div class="related-info">
                                    <div class="related-title">{{ $item->title }}</div>
                                    <div class="related-meta">{{ $item->created_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
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

        @include('partials.footer')
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <button class="close-video" onclick="closeVideo()">&times;</button>
        <div class="video-container">
            <video id="videoPlayer" controls autoplay>
                <source id="videoSource" src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <script>
        // ============ SEASON FILTER ============
        const seasonSelect = document.getElementById('seasonSelect');
        if (seasonSelect) {
            seasonSelect.addEventListener('change', function() {
                const val = this.value;
                document.querySelectorAll('.episode').forEach(el => {
                    el.style.display = el.getAttribute('data-season') == val ? 'flex' : 'none';
                });
            });
        }

        // ============ WATCH EPISODE ============
        const mmAuth = @json(auth()->check());
        const mmEpisodeIds = {!! $episodes->pluck('id') !!};
        const mmEpisodeTitles = {!! $episodes->map(fn($e) => [$e->id, $e->episode_title ?? $e->title])->values()->toJson() !!};
        const SERIES_ID = {{ $series->id }};

        function playbackSave(progress, duration) {
            const dur = Math.round(duration || 0);
            try {
                localStorage.setItem('mm_progress_' + currentEpisodeId, JSON.stringify({ p: Math.round(progress || 0), d: dur }));
            } catch (e) {}
            if (mmAuth) {
                fetch('/watch/progress', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
                    body: JSON.stringify({ watchable_type: 'App\\Models\\Movie', watchable_id: currentEpisodeId, progress: Math.round(progress || 0), duration: dur })
                }).catch(() => {});
            }
        }

        let currentEpisodeId = null;

        function watchEpisode(id, title) {
            Swal.fire({
                title: 'Watch ' + title,
                background: '#161c26',
                color: '#ffffff',
                showCancelButton: true,
                confirmButtonColor: '#e50914',
                confirmButtonText: 'Watch Now',
                cancelButtonText: 'Cancel',
                html: `
                    <div style="margin-top: 1rem;">
                        <button onclick="streamNow(${id})" style="width:100%; padding:0.6rem; background:#e50914; border:none; border-radius:8px; color:#fff; cursor:pointer; font-weight:600;">&#9654; Play Episode</button>
                    </div>
                `,
                showConfirmButton: false
            });
        }

        function nextEpisodeId(id) {
            const i = mmEpisodeIds.indexOf(id);
            return i >= 0 && i < mmEpisodeIds.length - 1 ? mmEpisodeIds[i + 1] : null;
        }

        function episodeTitle(id) {
            const found = mmEpisodeTitles.find(e => e[0] === id);
            return found ? found[1] : 'Episode';
        }

        function markEpisodeComplete(id) {
            const row = document.querySelector('.episode[data-episode-id="' + id + '"]');
            if (row && !row.classList.contains('completed')) {
                row.classList.add('completed');
                row.setAttribute('data-resume', '0');
                const num = row.querySelector('.episode-num');
                if (num && !num.querySelector('.episode-checked')) {
                    num.insertAdjacentHTML('beforeend', '<i class="fas fa-check-circle episode-checked" title="Watched"></i>');
                }
                const watchBtn = row.querySelector('.btn-watch');
                if (watchBtn) watchBtn.innerHTML = '<i class="fas fa-play"></i> Re-watch';
            }
        }

        function streamNow(id) {
            Swal.close();
            currentEpisodeId = id;
            const videoModal = document.getElementById('videoModal');
            const videoSource = document.getElementById('videoSource');
            const videoPlayer = document.getElementById('videoPlayer');
            videoSource.src = '/download/stream/' + id;
            videoPlayer.load();
            mmViewTracker.trackVideo(videoPlayer, 'series', SERIES_ID);
            videoModal.style.display = 'block';

            const row = document.querySelector('.episode[data-episode-id="' + id + '"]');
            let resumeAt = parseInt(row ? row.getAttribute('data-resume') : '0', 10) || 0;
            if (!mmAuth) {
                try {
                    const g = JSON.parse(localStorage.getItem('mm_progress_' + id) || 'null');
                    if (g && g.p > 5) resumeAt = g.p;
                } catch (e) {}
            }

            let lastSave = 0;
            videoPlayer.onloadedmetadata = () => {
                if (resumeAt > 5 && resumeAt < (videoPlayer.duration - 15)) {
                    videoPlayer.currentTime = resumeAt;
                }
            };
            videoPlayer.ontimeupdate = () => {
                if (!currentEpisodeId) return;
                const now = Date.now();
                if (videoPlayer.duration && videoPlayer.duration - videoPlayer.currentTime <= 8) {
                    playbackSave(videoPlayer.duration, videoPlayer.duration);
                    markEpisodeComplete(currentEpisodeId);
                    return;
                }
                if (now - lastSave < 5000) return;
                lastSave = now;
                playbackSave(videoPlayer.currentTime, videoPlayer.duration);
            };
            videoPlayer.onended = () => {
                if (!currentEpisodeId) return;
                playbackSave(videoPlayer.duration, videoPlayer.duration);
                markEpisodeComplete(currentEpisodeId);
                const next = nextEpisodeId(currentEpisodeId);
                if (next) {
                    Swal.fire({
                        title: 'Play Next Episode?',
                        text: episodeTitle(next),
                        icon: 'info',
                        background: '#161c26',
                        color: '#ffffff',
                        showCancelButton: true,
                        confirmButtonColor: '#e50914',
                        confirmButtonText: '<i class="fas fa-play"></i> Play',
                        cancelButtonText: 'Close'
                    }).then(res => {
                        if (res.isConfirmed) streamNow(next);
                    });
                }
            };
        }

        // ============ DOWNLOAD EPISODE ============
        function downloadEpisode(id, title) {
            Swal.fire({
                title: 'Download ' + title,
                icon: 'question',
                background: '#161c26',
                color: '#ffffff',
                showCancelButton: true,
                confirmButtonColor: '#e50914',
                confirmButtonText: 'Download',
                cancelButtonText: 'Cancel'
            }).then(res => {
                if (res.isConfirmed) {
                    const a = document.createElement('a');
                    a.href = '/download/movie/' + id;
                    a.download = title + '.mp4';
                    a.click();
                    Swal.fire({
                        title: 'Download Started!',
                        icon: 'success',
                        background: '#161c26',
                        color: '#ffffff',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // ============ CLOSE VIDEO ============
        function closeVideo() {
            const video = document.getElementById('videoPlayer');
            video.pause();
            video.onloadedmetadata = null;
            video.ontimeupdate = null;
            video.onended = null;
            video.removeAttribute('src');
            currentEpisodeId = null;
            document.getElementById('videoModal').style.display = 'none';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeVideo();
        });

        // ============ INTERACTIONS ============
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
                    type: 'series',
                    id: {{ $series->id }},
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
                    type: 'series',
                    id: {{ $series->id }},
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
    </script>
@include('partials.view-tracker')
</body>
</html>
