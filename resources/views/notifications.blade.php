<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifications - MovieMax</title>
    @include('partials.seo', ['seoTitle' => 'Notifications - MovieMax', 'seoDescription' => 'Your notifications on MovieMax.', 'seoNoindex' => true])
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

        .page-header {
            padding: 130px 5% 2rem;
            text-align: center;
            background: radial-gradient(900px 380px at 50% -10%, rgba(229, 9, 20, 0.24), transparent 65%), var(--bg-deep);
        }
        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.4rem, 5vw, 3.6rem);
            letter-spacing: 3px;
            line-height: 1;
        }
        .page-header p { color: var(--text-secondary); font-size: 0.92rem; margin-top: 0.8rem; }

        .ntf-list { max-width: 820px; margin: 0 auto; padding: 0 3% 3rem; }
        .ntf {
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 1.05rem 1.2rem;
            margin-bottom: 0.7rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.25s ease;
        }
        .ntf:hover { border-color: rgba(229, 9, 20, 0.5); transform: translateY(-1px); }
        .ntf-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.22), rgba(229, 9, 20, 0.06));
            border: 1px solid rgba(229, 9, 20, 0.3);
            color: var(--gold);
        }
        .ntf-body { min-width: 0; flex: 1; }
        .ntf-title { font-size: 0.9rem; font-weight: 700; }
        .ntf-msg { font-size: 0.82rem; color: var(--text-secondary); margin-top: 0.2rem; overflow: hidden; text-overflow: ellipsis; }
        .ntf-time { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.35rem; }
        .ntf.unread { border-color: rgba(229, 9, 20, 0.55); background: linear-gradient(90deg, rgba(229, 9, 20, 0.08), rgba(255, 255, 255, 0.03)); }
        .ntf-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-red);
            margin-top: 6px;
            flex-shrink: 0;
        }
        .empty-state {
            text-align: center;
            padding: 4.5rem 2rem;
            color: var(--text-muted);
            background: var(--bg-card);
            border: 1px dashed var(--glass-border);
            border-radius: 18px;
        }
        .empty-state i { font-size: 2.6rem; margin-bottom: 1.1rem; color: var(--accent-red); opacity: 0.6; display: block; }
        .empty-state a { color: var(--gold); text-decoration: none; }
        .pagination-wrap { margin-top: 2rem; display: flex; justify-content: center; }
    </style>
</head>
<body>
@include('partials.loader')@include('partials.navbar')

<header class="page-header">
    <h1>Notifications</h1>
    <p>New trailer alerts, replies and activity.</p>
</header>

<div class="ntf-list">
    @if($notifications->count() > 0)
        @foreach($notifications as $ntf)
            @php
                $glyph = $ntf->type === 'trailer' ? 'fa-film' : ($ntf->type === 'reply' ? 'fa-reply' : 'fa-bell');
            @endphp
            <a href="{{ $ntf->url ?: route('notifications') }}" class="ntf {{ $ntf->read_at === null ? 'unread' : '' }}">
                <div class="ntf-icon"><i class="fas {{ $glyph }}"></i></div>
                <div class="ntf-body">
                    <div class="ntf-title">{{ $ntf->title }}</div>
                    @if($ntf->message)
                        <div class="ntf-msg">{{ $ntf->message }}</div>
                    @endif
                    <div class="ntf-time">{{ $ntf->created_at->diffForHumans() }}</div>
                </div>
                @if($ntf->read_at === null)
                    <span class="ntf-dot"></span>
                @endif
            </a>
        @endforeach
        <div class="pagination-wrap">
            {{ $notifications->links('partials.pagination') }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>No notifications yet. Follow a genre to get alerts on new trailers.</p>
            <p style="margin-top:0.8rem;"><a href="{{ route('genres') }}">Browse genres <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i></a></p>
        </div>
    @endif
</div>

@include('partials.footer')
</body>
</html>