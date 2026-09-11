<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - MovieMax Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-deep: #0a0a0d;
            --bg-surface: #111216;
            --bg-card: #17181d;
            --accent: #e31c25;
            --accent-dark: #b30610;
            --text-primary: #f2f4f8;
            --text-secondary: #b0b6c2;
            --text-muted: #83888f;
            --border: rgba(255, 255, 255, 0.08);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            background-image: radial-gradient(ellipse at 20% -10%, rgba(255, 255, 255, 0.04) 0%, transparent 55%);
            color: var(--text-primary);
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        .admin-container { display: flex; min-height: 100vh; }

        /* ---------- Sidebar ---------- */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #131418 0%, #0a0a0d 100%);
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.18) transparent;
        }
        .sidebar-header {
            padding: 1.6rem 1.5rem 1.2rem;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .sidebar-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #fff 30%, #e31c25 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .sidebar-header small { color: var(--text-muted); font-size: 0.78rem; letter-spacing: 1px; text-transform: uppercase; }

        .sidebar-nav { padding: 1.2rem 0.8rem; }
        .nav-section {
            color: var(--text-muted);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 1rem 1.1rem 0.4rem;
        }
        .nav-item {
            padding: 0.72rem 1.1rem;
            margin: 0.2rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-secondary);
            border-radius: 10px;
            border-left: 3px solid transparent;
            transition: 0.2s;
            font-size: 0.92rem;
            font-weight: 500;
        }
        .nav-item:hover { background: rgba(227, 28, 37, 0.1); color: #fff; }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(227,28,37,0.18), rgba(227,28,37,0.04));
            color: #fff;
            border-left: 3px solid var(--accent);
        }
        .nav-item i { width: 22px; text-align: center; color: var(--text-muted); }
        .nav-item.active i, .nav-item:hover i { color: var(--accent); }

        .sidebar-bottom {
            padding: 1.5rem;
            margin-top: auto;
            border-top: 1px solid var(--border);
        }

        /* ---------- Main ---------- */
        .main-content { flex: 1; margin-left: 260px; padding: 1.8rem 2rem 3rem; }
        .top-bar {
            background: rgba(10, 10, 13, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 500;
        }
        .top-bar h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.7rem;
            letter-spacing: 2px;
            color: var(--text-primary);
        }

        .user-menu { position: relative; }
        .user-name {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 0.9rem;
        }
        .user-name:hover { border-color: rgba(227,28,37,0.5); }
        .user-name i:first-child { color: var(--accent); font-size: 1.1rem; }
        .user-dropdown {
            position: absolute;
            top: 54px;
            right: 0;
            background: var(--bg-card);
            min-width: 210px;
            border-radius: 14px;
            padding: 0.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            display: none;
            z-index: 9999;
        }
        .user-dropdown.show { display: block; }
        .user-dropdown a, .user-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.7rem 0.8rem;
            color: var(--text-secondary);
            text-decoration: none;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.88rem;
            border-radius: 8px;
            font-family: inherit;
        }
        .user-dropdown a:hover, .user-dropdown button:hover { background: rgba(227,28,37,0.12); color: #fff; }
        .user-dropdown i { width: 18px; text-align: center; color: var(--text-muted); }
        .user-dropdown hr { border: none; border-top: 1px solid var(--border); margin: 0.4rem 0; }

        /* ---------- Stats ---------- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.8rem;
        }
        .stat-card {
            background: linear-gradient(160deg, var(--bg-card), #111216);
            border-radius: 18px;
            padding: 1.4rem 1.5rem;
            border: 1px solid var(--border);
            transition: 0.25s;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 130px; height: 130px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(227,28,37,0.18), transparent 70%);
        }
        .stat-card:hover { transform: translateY(-4px); border-color: rgba(227,28,37,0.4); }
        .stat-card h3 { color: var(--text-muted); font-size: 0.75rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card .value { font-size: 2.1rem; font-weight: 800; color: #fff; }
        .stat-card .value span { font-size: 0.9rem; color: var(--text-muted); font-weight: 500; }

        /* ---------- Cards ---------- */
        .card {
            background: linear-gradient(160deg, var(--bg-card), #111216);
            border-radius: 18px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        .card h3 { margin-bottom: 1rem; color: #fff; font-size: 1.05rem; }
        .card h3 i { color: var(--accent); margin-right: 0.4rem; }
        .charts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
        .chart-container { position: relative; height: 300px; }
        .recent-section { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem; }

        /* ---------- Tables ---------- */
        .table-card { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .data-table th, .data-table td { padding: 0.85rem 1rem; text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; }
        .data-table th { color: var(--text-muted); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
        .data-table tbody tr:hover { background: rgba(227,28,37,0.05); }
        .data-table img { border-radius: 8px; }
        .thumb-sm { width: 46px; height: 64px; object-fit: cover; border-radius: 8px; }

        /* ---------- Alerts ---------- */
        .alert { padding: 0.9rem 1.2rem; border-radius: 12px; margin-bottom: 1.2rem; font-size: 0.9rem; display: flex; justify-content: space-between; align-items: center; }
        .alert-success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #6ee7b7; }
        .alert-error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #fca5a5; }
        .alert button { background: none; border: none; color: inherit; cursor: pointer; font-size: 1.1rem; }

        /* ---------- Forms ---------- */
        .form-group { margin-bottom: 1.3rem; }
        .form-group label { display: block; margin-bottom: 0.45rem; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; }
        .form-control {
            width: 100%;
            padding: 0.72rem 0.9rem;
            background: #111216;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            transition: 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(227,28,37,0.2); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 110px; }

        /* ---------- Buttons ---------- */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            border: none;
            padding: 0.6rem 1.1rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
            font-family: inherit;
        }
        .btn-primary:hover { filter: brightness(1.15); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(227,28,37,0.35); }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.07);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            padding: 0.6rem 1.1rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.2s;
            font-family: inherit;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239,68,68,0.15);
            color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.35);
            padding: 0.6rem 1.1rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.2s;
            font-family: inherit;
        }
        .btn-danger:hover { background: rgba(239,68,68,0.25); color: #fff; }
        .btn-sm { padding: 0.35rem 0.7rem; font-size: 0.78rem; border-radius: 8px; }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .badge-green { background: rgba(16,185,129,0.15); color: #6ee7b7; }
        .badge-red { background: rgba(239,68,68,0.15); color: #fca5a5; }
        .badge-blue { background: rgba(227,28,37,0.15); color: #fca5a5; }
        .badge-purple { background: rgba(167,139,250,0.15); color: #c4b5fd; }

        .avatar-circle {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(227,28,37,0.4), rgba(255,255,255,0.08));
            border: 1px solid rgba(227,28,37,0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 0.9rem;
        }
        .flex-between { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 0.45rem 0.8rem;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
        }
        .pagination a:hover { border-color: var(--accent); color: #fff; }
        .pagination .active { background: var(--accent); color: #fff; border-color: var(--accent); }

        @media (max-width: 900px) {
            .sidebar { width: 190px; }
            .main-content { margin-left: 190px; padding: 1.2rem; }
            .charts-grid, .recent-section { grid-template-columns: 1fr; }
        }
        @media (max-width: 680px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>MOVIEMAX</h2>
                <small>Content Management</small>
            </div>
            <div class="sidebar-nav">
                <div class="nav-section">Manage</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.movies.index') }}" class="nav-item {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}">
                    <i class="fas fa-film"></i><span>Movies</span>
                </a>
                <a href="{{ route('admin.series.index') }}" class="nav-item {{ request()->routeIs('admin.series.*') ? 'active' : '' }}">
                    <i class="fas fa-tv"></i><span>TV Series</span>
                </a>
                <a href="{{ route('admin.trailers.index') }}" class="nav-item {{ request()->routeIs('admin.trailers.*') ? 'active' : '' }}">
                    <i class="fas fa-clapperboard"></i><span>Trailers</span>
                </a>
                <a href="{{ route('admin.hero-slides.index') }}" class="nav-item {{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i><span>Hero Slides</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i><span>Settings</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i><span>System Users</span>
                </a>
                @endif
                <div class="nav-section">Insights</div>
                <a href="{{ route('admin.analytics.index') }}" class="nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i><span>Analytics</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            <div class="top-bar">
                <h1>@yield('title')</h1>
                <div class="user-menu">
                    <div class="user-name" onclick="toggleDropdown(event)">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('profile') }}"><i class="fas fa-user"></i> My Profile</a>
                        <a href="/"><i class="fas fa-globe"></i> View Site</a>
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success" id="successAlert">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()">&times;</button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleDropdown(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('show');
        }
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const trigger = document.querySelector('.user-name');
            if (dropdown && !trigger.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>