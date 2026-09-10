{{-- Shared cinematic navbar (auth-aware) --}}
<style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
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
    .logo:has(.logo-img) {
        background: none !important;
        -webkit-background-clip: initial !important;
        background-clip: initial !important;
        color: inherit !important;
        animation: none;
    }
    .logo .logo-img {
        height: 64px;
        width: auto;
        max-width: 260px;
        object-fit: contain;
        display: block;
        border-radius: 8px;
    }
    .footer-brand:has(.footer-logo) {
        background: none !important;
        -webkit-background-clip: initial !important;
        background-clip: initial !important;
        color: inherit !important;
    }
    .footer-brand .footer-logo {
        height: 52px;
        width: auto;
        max-width: 240px;
        object-fit: contain;
        border-radius: 8px;
        vertical-align: middle;
    }
    .nav-links {
        display: flex;
        gap: 1.8rem;
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
        white-space: nowrap;
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
    .nav-user {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        margin-left: 0.5rem;
    }
    .nav-profile {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.45rem 1rem;
        border-radius: 40px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text-primary);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .nav-profile::after { display: none; }
    .nav-profile:hover {
        border-color: rgba(229, 9, 20, 0.6);
        background: rgba(229, 9, 20, 0.15);
        color: #fff;
    }
    .nav-profile i { font-size: 0.9rem; }
    .btn-login {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 1.2rem;
        border-radius: 40px;
        background: var(--accent-red);
        color: #fff !important;
        font-weight: 700 !important;
        font-size: 0.85rem !important;
        box-shadow: 0 4px 18px rgba(229, 9, 20, 0.35);
        transition: all 0.3s ease;
    }
    .btn-login:hover { filter: brightness(1.15); transform: translateY(-1px); }
    .btn-login::after { display: none; }
    .logout-form { display: inline-flex; }
    .logout-form button {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 0.88rem;
        font-weight: 500;
        font-family: inherit;
        padding: 0.4rem 0.3rem;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .logout-form button:hover { color: var(--accent-red); }
    .nav-search {
        position: relative;
        margin-left: auto;
        margin-right: 1.5rem;
        width: min(340px, 100%);
    }
    .nav-search > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.45);
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 2;
    }
    .nav-search input {
        width: 100%;
        padding: 0.55rem 1rem 0.55rem 36px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid var(--glass-border);
        color: var(--text-primary, #fff);
        font-size: 0.85rem;
        outline: none;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    .nav-search input:focus {
        border-color: rgba(229, 9, 20, 0.6);
        background: rgba(255, 255, 255, 0.09);
        box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15);
    }
    .nav-search input::placeholder { color: rgba(255, 255, 255, 0.4); }
    .search-results {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        background: #11161d;
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        z-index: 2000;
        max-height: 380px;
        overflow-y: auto;
    }
    .search-results.open { display: block; }
    .search-item {
        display: flex;
        gap: 0.7rem;
        align-items: center;
        padding: 0.6rem 0.8rem;
        text-decoration: none;
        color: var(--text-primary, #fff);
        transition: background 0.2s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .search-item:last-child { border-bottom: none; }
    .search-item:hover { background: rgba(229, 9, 20, 0.12); }
    .search-item img {
        width: 44px;
        height: 62px;
        object-fit: cover;
        border-radius: 7px;
        flex-shrink: 0;
        background: var(--bg-card, #161c26);
    }
    .search-item-info { min-width: 0; }
    .search-item-title {
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .search-item-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.55);
        margin-top: 0.2rem;
    }
    .search-type {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 0.12rem 0.45rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.75);
    }
    .search-type.movie { background: rgba(229, 9, 20, 0.2); color: #ff5c68; }
    .search-type.series { background: rgba(56, 189, 248, 0.2); color: #7db2ff; }
    .search-type.trailer { background: rgba(56, 189, 248, 0.2); color: #c084fc; }
    .search-empty {
        padding: 1rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
    }

    /* ---------- Support widget ---------- */
    .support-fab {
        position: fixed;
        bottom: 1.4rem;
        right: 1.4rem;
        z-index: 1500;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.7rem;
    }
    .support-menu {
        display: none;
        flex-direction: column;
        gap: 0.5rem;
        animation: mmBounce .45s cubic-bezier(.22, 1.2, .36, 1) both;
    }
    .support-menu.open { display: flex; }
    .support-menu a {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.7rem 1.1rem;
        border-radius: 40px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(17, 21, 27, 0.95);
        border: 1px solid var(--glass-border);
        backdrop-filter: blur(14px);
        box-shadow: 0 12px 34px rgba(0, 0, 0, 0.45);
        transition: transform 0.25s ease;
        white-space: nowrap;
    }
    .support-menu a:hover { transform: translateY(-3px); }
    .support-menu a.coffee { color: #ffb020; }
    .support-menu a.whatsapp { color: #25d366; }
    .support-menu a.whatsapp small { display: block; color: rgba(255, 255, 255, 0.6); font-weight: 500; font-size: 0.72rem; }
    .support-menu a i { font-size: 1.05rem; }
    .support-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        background: var(--accent-red);
        color: #fff;
        font-size: 1.35rem;
        box-shadow: 0 8px 26px rgba(229, 9, 20, 0.45);
        transition: transform 0.3s ease, background 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .support-toggle:hover { transform: translateY(-3px) scale(1.05); }

    /* ---------- Scroll reveal ---------- */
    .mm-anim { opacity: 0; }
    .mm-anim.mm-in {
        opacity: 1;
        animation: mmBounce .6s cubic-bezier(.22, 1.2, .36, 1) both;
        animation-delay: calc(var(--mm-i, 0) * 55ms);
    }
    @keyframes mmBounce {
        0%   { opacity: 0; transform: translateY(26px) scale(.97); }
        60%  { opacity: 1; transform: translateY(-6px) scale(1.01); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @media (prefers-reduced-motion: reduce) {
        .mm-anim { opacity: 1; }
        .mm-anim.mm-in { animation: none; }
    }
    @media (max-width: 900px) {
        .support-fab { bottom: 1rem; right: 1rem; }
        .support-toggle { width: 50px; height: 50px; }
    }
    @media (max-width: 900px) {
        .nav-search { display: none; }
    }
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
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 999;
    }
    .mobile-overlay.active { display: block; }

    @media (max-width: 900px) {
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
            gap: 2rem;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
            border-left: 1px solid rgba(229, 9, 20, 0.2);
        }
        .nav-links.active { right: 0; }
        .nav-links a { font-size: 1.1rem; }
        .nav-user { flex-direction: column; gap: 1.2rem; margin-left: 0; }
    }
</style>

<div class="mobile-overlay" id="mobileOverlay" onclick="toggleMenu()"></div>

<nav class="navbar" id="navbar">
    <a href="/" class="logo">
        @if(setting('logo_path'))
            <img src="{{ setting('logo_path') }}" alt="{{ setting('site_name', 'MOVIEMAX') }}" class="logo-img">
        @else
            {{ setting('site_name', 'MOVIEMAX') }}
        @endif
    </a>
    <div class="nav-search">
        <i class="fas fa-search"></i>
        <input type="text" id="navSearchInput" placeholder="Search movies, series..." autocomplete="off">
        <div class="search-results" id="searchResults"></div>
    </div>
    <div class="menu-btn" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
    <div class="nav-links" id="navLinks">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
        <a href="{{ route('movies.index') }}" class="{{ request()->is('movies') || request()->is('movies/*') ? 'active' : '' }}">Movies</a>
        <a href="{{ route('series.index') }}" class="{{ request()->is('series') || request()->is('series/*') ? 'active' : '' }}">TV Series</a>
        <a href="{{ route('trailers') }}" class="{{ request()->is('trailers') || request()->is('trailers/*') ? 'active' : '' }}"><i class="fas fa-video"></i> Trailers</a>
        <a href="{{ route('favorites') }}" class="{{ request()->is('favorites*') ? 'active' : '' }}"><i class="fas fa-heart"></i> Favorites</a>
        <a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a>

        @auth
        <div class="nav-user">
            <a href="{{ route('profile') }}" class="nav-profile @if(auth()->user()->isSystemUser()){{ request()->is('admin/*') ? 'active' : '' }}@endif">
                <i class="fas fa-user-circle"></i>
                {{ explode(' ', auth()->user()->name)[0] }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn-login"><i class="fas fa-user-circle"></i> Login</a>
        @endauth
    </div>
</nav>

@php
    $waDigits = preg_replace('/[^0-9]/', '', setting('whatsapp_number', '+255688349680'));
    $waDisplay = setting('whatsapp_number', '+255688349680');
    $coffeeUrl = setting('buy_me_coffee_url', '');
@endphp
<div class="support-fab" id="supportFab">
    <div class="support-menu" id="supportMenu">
        @if($coffeeUrl)
            <a href="{{ $coffeeUrl }}" target="_blank" rel="noopener" class="coffee">
                <i class="fas fa-mug-hot"></i> Buy Me a Coffee
            </a>
        @endif
        @if($waDigits)
            <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener" class="whatsapp">
                <i class="fab fa-whatsapp"></i>
                <span>Talk to Developer<small>{{ $waDisplay }}</small></span>
            </a>
        @endif
    </div>
    <button class="support-toggle" onclick="toggleSupport(event)" title="Support">
        <i class="fas fa-heart" id="supportIcon"></i>
    </button>
</div>

<script>
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('navbar');
        if (nav && window.scrollY > 50) nav.classList.add('scrolled');
        else if (nav) nav.classList.remove('scrolled');
    });
    function toggleMenu() {
        document.getElementById('navLinks').classList.toggle('active');
        document.getElementById('mobileOverlay').classList.toggle('active');
    }
    document.querySelectorAll('#navLinks a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('navLinks').classList.remove('active');
            document.getElementById('mobileOverlay').classList.remove('active');
        });
    });

    // ============ SUPPORT WIDGET ============
    function toggleSupport(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('supportMenu');
        const icon = document.getElementById('supportIcon');
        const open = menu.classList.toggle('open');
        icon.classList.remove('fa-heart', 'fa-times');
        icon.classList.add(open ? 'fa-times' : 'fa-heart');
    }
    document.addEventListener('click', () => {
        const menu = document.getElementById('supportMenu');
        if (menu && menu.classList.contains('open')) {
            menu.classList.remove('open');
            const icon = document.getElementById('supportIcon');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-heart');
        }
    });

    // ============ SCROLL REVEAL ============
    document.addEventListener('DOMContentLoaded', () => {
        const targets = document.querySelectorAll('.movie-card, .trailer-card, .series-card, .episode-card, .continue-item, .related-card, .hero-card, .genre-card, .support-card, .section-title');
        if (!('IntersectionObserver' in window)) {
            targets.forEach(t => { t.style.opacity = 1; t.classList.add('mm-in'); });
            return;
        }
        const io = new IntersectionObserver(entries => {
            entries.forEach(en => {
                if (!en.isIntersecting) return;
                const parent = en.target.parentElement;
                const idx = parent ? Array.prototype.indexOf.call(parent.children, en.target) : 0;
                en.target.style.setProperty('--mm-i', Math.min(idx, 14));
                en.target.classList.add('mm-in');
                io.unobserve(en.target);
            });
        }, { threshold: 0.12 });
        targets.forEach(t => { t.classList.add('mm-anim'); io.observe(t); });
    });

    // ============ LIVE SEARCH ============
    const searchInput = document.getElementById('navSearchInput');
    const resultsBox = document.getElementById('searchResults');
    if (searchInput) {
        const typeLabels = { movie: 'MOVIE', series: 'SERIES', trailer: 'TRAILER' };
        let debounce = null;

        const closeResults = () => resultsBox.classList.remove('open');

        searchInput.addEventListener('input', () => {
            clearTimeout(debounce);
            const q = searchInput.value.trim();
            if (q.length < 2) { resultsBox.innerHTML = ''; closeResults(); return; }
            debounce = setTimeout(async () => {
                try {
                    const res = await fetch('/api/search?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                    const items = await res.json();
                    if (!items.length) {
                        resultsBox.innerHTML = '<div class="search-empty">No results found</div>';
                        resultsBox.classList.add('open');
                        return;
                    }
                    resultsBox.innerHTML = items.map(i => `
                        <a href="${i.url}" class="search-item">
                            <img src="${i.poster || '/images/posters/dummy-poster.png'}" alt="" onerror="this.src='/images/posters/dummy-poster.png'">
                            <div class="search-item-info">
                                <div class="search-item-title">${i.title}</div>
                                <div class="search-item-meta">
                                    <span class="search-type ${i.type}">${typeLabels[i.type]}</span>
                                    ${i.year ? '<span>' + i.year + '</span>' : ''}
                                </div>
                            </div>
                        </a>`).join('');
                    resultsBox.classList.add('open');
                } catch (e) { closeResults(); }
            }, 250);
        });

        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = searchInput.value.trim();
                window.location.href = '/movies?search=' + encodeURIComponent(q);
            }
            if (e.key === 'Escape') { closeResults(); searchInput.blur(); }
        });

        document.addEventListener('click', ev => {
            if (!document.getElementById('navSearch').contains(ev.target)) closeResults();
        });
    }
</script>