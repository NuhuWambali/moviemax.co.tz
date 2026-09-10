<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'My Profile - MOVIEMAX', 'seoDescription' => 'Your MOVIEMAX profile, activity and account settings.', 'seoNoindex' => true])
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
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        .profile-hero {
            padding: 120px 5% 2rem;
            text-align: center;
            background:
                radial-gradient(900px 350px at 20% -10%, rgba(229, 9, 20, 0.22), transparent 60%),
                radial-gradient(700px 300px at 80% 10%, rgba(229, 9, 20, 0.1), transparent 60%),
                var(--bg-deep);
            border-bottom: 1px solid var(--glass-border);
        }
        .profile-avatar {
            width: 92px; height: 92px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: var(--accent-red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            color: #fff;
            border: 3px solid rgba(229, 9, 20, 0.4);
            box-shadow: 0 12px 40px rgba(229, 9, 20, 0.4);
        }
        .profile-hero h1 { font-family: 'Bebas Neue', sans-serif; font-size: 2.2rem; letter-spacing: 2px; }
        .profile-hero .sub { color: var(--text-muted); font-size: 0.82rem; margin-top: 0.4rem; }
        @if($user->isSystemUser())
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 0.8rem;
            padding: 0.35rem 1rem;
            border-radius: 40px;
            font-size: 0.72rem;
            font-weight: 700;
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.4);
            color: #ffd700;
        }
        @endif

        .stats-grid {
            max-width: 1000px;
            margin: -1rem auto 0;
            padding: 1.4rem 5% 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
        }
        .stat-card .num { font-size: 1.9rem; font-weight: 800; }
        .stat-card .lbl { color: var(--text-muted); font-size: 0.76rem; margin-top: 0.3rem; }
        @media (max-width: 700px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }

        .tabs {
            max-width: 1000px;
            margin: 1.6rem auto 0;
            padding: 0 5%;
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--glass-border);
        }
        .tab-btn {
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--text-secondary);
            padding: 0.75rem 1rem;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
        }
        .tab-btn:hover { color: var(--text-primary); }
        .tab-btn.active { color: var(--accent-cyan); border-bottom-color: var(--accent-red); }

        .tab-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 5% 3.5rem;
        }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; animation: fadeIn 0.35s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

        .section-title { font-size: 1.15rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 10px; }

        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        .item-card { display: block; border-radius: 12px; overflow: hidden; background: var(--bg-card); border: 1px solid var(--glass-border); text-decoration: none; color: inherit; transition: all 0.3s ease; }
        .item-card:hover { transform: translateY(-4px); border-color: rgba(229, 9, 20, 0.5); }
        .item-card img { width: 100%; aspect-ratio: 2/3; object-fit: cover; display: block; }
        .item-card.trailer img { aspect-ratio: 16/9; }
        .item-card h4 { font-size: 0.78rem; padding: 0.6rem 0.7rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .item-type {
            position: absolute; top: 8px; left: 8px;
            background: rgba(10, 13, 18, 0.8);
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--accent-cyan);
            border: 1px solid rgba(229, 9, 20, 0.3);
        }
        .thumb-wrap { position: relative; }

        .history-list { display: flex; flex-direction: column; gap: 0.6rem; }
        .history-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.8rem 1rem;
        }
        .history-row img { width: 70px; height: 92px; object-fit: cover; border-radius: 8px; }
        .history-row .info { flex: 1; min-width: 0; }
        .history-row .info h4 { font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .history-row .info small { color: var(--text-muted); font-size: 0.75rem; }
        .history-row a { color: var(--accent-cyan); text-decoration: none; font-size: 0.8rem; font-weight: 600; white-space: nowrap; }

        .list-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 0.6rem;
        }
        .list-row .avatar-circle {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--accent-red);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; flex-shrink: 0;
        }
        .list-row .info { flex: 1; min-width: 0; }
        .list-row .info h4 { font-size: 0.88rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .list-row .info small { color: var(--text-muted); font-size: 0.74rem; }
        .list-row a.view { color: var(--accent-cyan); text-decoration: none; font-size: 0.8rem; font-weight: 600; }

        .settings-card {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.6rem;
            max-width: 520px;
        }
        .settings-card .form-group { margin-bottom: 1.1rem; }
        .settings-card label { display: block; font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 0.4rem; }
        .settings-card input {
            width: 100%;
            background: rgba(10, 13, 18, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.7rem 0.9rem;
        }
        .settings-card input:focus { outline: none; border-color: rgba(229, 9, 20, 0.6); }
        .save-btn {
            background: var(--accent-red);
            border: none;
            color: #fff;
            padding: 0.65rem 1.5rem;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.9rem;
            font-family: inherit;
        }
        .alert-success {
            background: rgba(229, 9, 20, 0.12);
            border: 1px solid rgba(229, 9, 20, 0.4);
            color: #ffd700;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }
        .alert-error {
            background: rgba(229, 9, 20, 0.12);
            border: 1px solid rgba(229, 9, 20, 0.4);
            color: #fbbf24;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }
        .empty-note { color: var(--text-muted); text-align: center; padding: 2.5rem 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
@include('partials.navbar')

<div class="profile-hero">
    <div class="profile-avatar">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
    <h1>{{ $user->name }}</h1>
    <div class="sub">{{ $user->email }} &middot; Joined {{ $user->created_at->format('M Y') }}</div>
    @if($user->isSystemUser())
        <div class="role-badge"><i class="fas fa-user-cog"></i> System User ({{ ucfirst($user->user_type) }})</div>
    @endif
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="num">{{ $stats['favorites'] }}</div><div class="lbl">Favorites</div></div>
    <div class="stat-card"><div class="num">{{ $stats['watched'] }}</div><div class="lbl">Trailers Watched</div></div>
    <div class="stat-card"><div class="num">{{ $stats['comments'] }}</div><div class="lbl">Comments</div></div>
    <div class="stat-card"><div class="num">{{ $stats['reactions'] }}</div><div class="lbl">Reactions</div></div>
</div>

<div class="tabs">
    <button class="tab-btn active" data-tab="overview"><i class="fas fa-grip"></i> Overview</button>
    <button class="tab-btn" data-tab="favorites"><i class="fas fa-heart"></i> Favorites</button>
    <button class="tab-btn" data-tab="trailers"><i class="fas fa-video"></i> Watched Trailers</button>
    <button class="tab-btn" data-tab="history"><i class="fas fa-history"></i> Watch History</button>
    <button class="tab-btn" data-tab="comments"><i class="fas fa-comments"></i> My Comments</button>
    <button class="tab-btn" data-tab="reactions"><i class="fas fa-thumbs-up"></i> My Reactions</button>
    <button class="tab-btn" data-tab="settings"><i class="fas fa-cog"></i> Settings</button>
</div>

<div class="tab-content">
    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
        </div>
    @endif

    <div class="tab-pane active" id="tab-overview">
        <h2 class="section-title"><i class="fas fa-grip" style="color: var(--accent-cyan);"></i> Welcome back, {{ explode(' ', $user->name)[0] }}</h2>

        @if($favoriteMovies->count() + $favoriteSeries->count() > 0)
            <h2 class="section-title" style="margin-top:1.5rem; font-size:1rem;"><i class="fas fa-heart" style="color: var(--accent-red);"></i> Your Favorites</h2>
            <div class="item-grid">
                @foreach($favoriteMovies->take(6) as $fm)
                    <a href="{{ route('movies.show', $fm->slug) }}" class="item-card">
                        <div class="thumb-wrap">
                            <span class="item-type">MOVIE</span>
                            <img src="{{ $fm->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $fm->title }}">
                        </div>
                        <h4>{{ $fm->title }}</h4>
                    </a>
                @endforeach
                @foreach($favoriteSeries->take(6) as $fs)
                    <a href="{{ route('series.show', $fs->id) }}" class="item-card">
                        <div class="thumb-wrap">
                            <span class="item-type">SERIES</span>
                            <img src="{{ $fs->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $fs->title }}">
                        </div>
                        <h4>{{ $fs->title }}</h4>
                    </a>
                @endforeach
            </div>
        @else
            <p class="empty-note">You haven't added any favorites yet. <a href="{{ route('movies.index') }}" style="color: var(--accent-cyan);">Browse movies</a> and save the ones you love.</p>
        @endif

        @if($watchedTrailers->count() > 0)
            <h2 class="section-title" style="margin-top:2rem; font-size:1rem;"><i class="fas fa-video" style="color: var(--accent-cyan);"></i> Recently Watched Trailers</h2>
            <div class="item-grid" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
                @foreach($watchedTrailers->take(4) as $wt)
                    <a href="{{ route('trailers.show', $wt->id) }}" class="item-card trailer">
                        <div class="thumb-wrap">
                            <span class="item-type">TRAILER</span>
                            <img src="{{ $wt->thumb_url }}" alt="{{ $wt->title }}">
                        </div>
                        <h4>{{ $wt->title }}</h4>
                    </a>
                @endforeach
            </div>
        @endif

        @if($watchHistory->count() > 0)
            <h2 class="section-title" style="margin-top:2rem; font-size:1rem;"><i class="fas fa-history" style="color: var(--accent-cyan);"></i> Recent Activity</h2>
            <div class="history-list">
                @foreach($watchHistory->take(5) as $entry)
                    @php
                        $item = $entry->watchable;
                        $url = $item ? match($entry->watchable_type) {
                            \App\Models\Movie::class => route('movies.show', $item->slug),
                            \App\Models\Series::class => route('series.show', $item->id),
                            default => null,
                        } : null;
                        $thumb = $item->poster_path ?? '/images/posters/dummy-poster.png';
                    @endphp
                    <div class="history-row">
                        <img src="{{ $thumb }}" alt="">
                        <div class="info">
                            <h4>{{ $item->title ?? 'Deleted item' }}</h4>
                            <small>{{ str_replace('App\Models\\', '', $entry->watchable_type) }} &middot; {{ $entry->watched_at->diffForHumans() }}</small>
                        </div>
                        @if($url)<a href="{{ $url }}">View <i class="fas fa-arrow-right"></i></a>@endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="tab-pane" id="tab-favorites">
        <h2 class="section-title"><i class="fas fa-heart" style="color: var(--accent-red);"></i> Favorites</h2>
        @if($favoriteMovies->count() + $favoriteSeries->count() + $favoriteTrailers->count() > 0)
            <div class="item-grid">
                @foreach($favoriteMovies as $fm)
                    <a href="{{ route('movies.show', $fm->slug) }}" class="item-card">
                        <div class="thumb-wrap"><span class="item-type">MOVIE</span><img src="{{ $fm->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $fm->title }}"></div>
                        <h4>{{ $fm->title }}</h4>
                    </a>
                @endforeach
                @foreach($favoriteSeries as $fs)
                    <a href="{{ route('series.show', $fs->id) }}" class="item-card">
                        <div class="thumb-wrap"><span class="item-type">SERIES</span><img src="{{ $fs->poster_path ?? '/images/posters/dummy-poster.png' }}" alt="{{ $fs->title }}"></div>
                        <h4>{{ $fs->title }}</h4>
                    </a>
                @endforeach
                @foreach($favoriteTrailers as $ft)
                    <a href="{{ route('trailers.show', $ft->id) }}" class="item-card trailer">
                        <div class="thumb-wrap"><span class="item-type">TRAILER</span><img src="{{ $ft->thumb_url }}" alt="{{ $ft->title }}"></div>
                        <h4>{{ $ft->title }}</h4>
                    </a>
                @endforeach
            </div>
        @else
            <p class="empty-note">No favorites yet.</p>
        @endif
    </div>

    <div class="tab-pane" id="tab-trailers">
        <h2 class="section-title"><i class="fas fa-video" style="color: var(--accent-cyan);"></i> Trailers Watched ({{ $watchedTrailers->count() }})</h2>
        @if($watchedTrailers->count() > 0)
            <div class="item-grid">
                @foreach($watchedTrailers as $wt)
                    <a href="{{ route('trailers.show', $wt->id) }}" class="item-card trailer">
                        <div class="thumb-wrap"><span class="item-type">TRAILER</span><img src="{{ $wt->thumb_url }}" alt="{{ $wt->title }}"></div>
                        <h4>{{ $wt->title }}</h4>
                    </a>
                @endforeach
            </div>
        @else
            <p class="empty-note">You haven't watched any trailers yet.</p>
        @endif
    </div>

    <div class="tab-pane" id="tab-history">
        <h2 class="section-title"><i class="fas fa-history" style="color: var(--accent-cyan);"></i> Watch History</h2>
        @if($watchHistory->count() > 0)
            <div class="history-list">
                @foreach($watchHistory as $entry)
                    @php
                        $item = $entry->watchable;
                        $url = $item ? match($entry->watchable_type) {
                            \App\Models\Movie::class => route('movies.show', $item->slug),
                            \App\Models\Series::class => route('series.show', $item->id),
                            default => null,
                        } : null;
                        $thumb = $item->poster_path ?? '/images/posters/dummy-poster.png';
                    @endphp
                    <div class="history-row">
                        <img src="{{ $thumb }}" alt="">
                        <div class="info">
                            <h4>{{ $item->title ?? 'Deleted item' }}</h4>
                            <small>{{ str_replace('App\Models\\', '', $entry->watchable_type) }} &middot; Watched {{ $entry->watched_at->diffForHumans() }}</small>
                        </div>
                        @if($url)<a href="{{ $url }}">View <i class="fas fa-arrow-right"></i></a>@endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="empty-note">Nothing in your watch history yet.</p>
        @endif
    </div>

    <div class="tab-pane" id="tab-comments">
        <h2 class="section-title"><i class="fas fa-comments" style="color: var(--accent-cyan);"></i> My Comments ({{ $myComments->count() }})</h2>
        @forelse($myComments as $comment)
            @php
                $target = $comment->commentable;
                $url = $target ? match($comment->commentable_type) {
                    \App\Models\Movie::class => route('movies.show', $target->slug),
                    \App\Models\Series::class => route('series.show', $target->id),
                    \App\Models\Trailer::class => route('trailers.show', $target->id),
                    default => null,
                } : null;
            @endphp
            <div class="list-row">
                <div class="avatar-circle">{{ mb_strtoupper(mb_substr($comment->author_name, 0, 1)) }}</div>
                <div class="info">
                    <h4>{{ \Illuminate\Support\Str::limit($comment->body, 90) }}</h4>
                    <small>on {{ $target->title ?? 'Deleted item' }} &middot; {{ $comment->created_at->diffForHumans() }}</small>
                </div>
                @if($url)<a href="{{ $url }}" class="view">View <i class="fas fa-arrow-right"></i></a>@endif
            </div>
        @empty
            <p class="empty-note">You haven't posted any comments yet.</p>
        @endforelse
    </div>

    <div class="tab-pane" id="tab-reactions">
        <h2 class="section-title" style="margin-bottom:1.5rem;"><i class="fas fa-thumbs-up" style="color: var(--accent-cyan);"></i> My Reactions</h2>
        @php
            $reactUrl = function ($item) {
                if (!$item) return null;
                if ($item instanceof \App\Models\Movie) return route('movies.show', $item->slug);
                if ($item instanceof \App\Models\Series) return route('series.show', $item->id);
                if ($item instanceof \App\Models\Trailer) return route('trailers.show', $item->id);
                return null;
            };
            $reactThumb = function ($item) {
                if (!$item) return '/images/posters/dummy-poster.png';
                if ($item instanceof \App\Models\Trailer) return $item->thumb_url;
                return $item->poster_path ?? '/images/posters/dummy-poster.png';
            };
            $reactClass = fn($item) => $item instanceof \App\Models\Trailer ? ' trailer' : '';
            $reactType = fn($item) => $item instanceof \App\Models\Trailer ? 'TRAILER' : ($item instanceof \App\Models\Series ? 'SERIES' : 'MOVIE');
        @endphp
        @if($myLikes->count() > 0)
            <h2 class="section-title" style="font-size:0.95rem;"><i class="fas fa-heart" style="color:#ffd700;"></i> Liked ({{ $myLikes->count() }})</h2>
            <div class="item-grid" style="margin-bottom:2rem;">
                @foreach($myLikes as $item)
                    @if($url = $reactUrl($item))
                    <a href="{{ $url }}" class="item-card{{ $reactClass($item) }}">
                        <div class="thumb-wrap"><span class="item-type">{{ $reactType($item) }}</span><img src="{{ $reactThumb($item) }}" alt="{{ $item->title }}"></div>
                        <h4>{{ $item->title }}</h4>
                    </a>
                    @endif
                @endforeach
            </div>
        @endif
        @if($myDislikes->count() > 0)
            <h2 class="section-title" style="font-size:0.95rem;"><i class="fas fa-heart-crack" style="color:#fbbf24;"></i> Disliked ({{ $myDislikes->count() }})</h2>
            <div class="item-grid">
                @foreach($myDislikes as $item)
                    @if($url = $reactUrl($item))
                    <a href="{{ $url }}" class="item-card{{ $reactClass($item) }}">
                        <div class="thumb-wrap"><span class="item-type">{{ $reactType($item) }}</span><img src="{{ $reactThumb($item) }}" alt="{{ $item->title }}"></div>
                        <h4>{{ $item->title }}</h4>
                    </a>
                    @endif
                @endforeach
            </div>
        @endif
        @if($myLikes->count() === 0 && $myDislikes->count() === 0)
            <p class="empty-note">No reactions yet. Like or dislike content to see it here.</p>
        @endif
    </div>

    <div class="tab-pane" id="tab-settings">
        <h2 class="section-title"><i class="fas fa-cog" style="color: var(--accent-cyan);"></i> Account Settings</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="settings-card">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave empty to keep current">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat new password">
            </div>
            <button type="submit" class="save-btn"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });
</script>
</body>
</html>