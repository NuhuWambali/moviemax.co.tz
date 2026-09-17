@php
    $badge = $badge ?? null;
    $favorited = $favorited ?? false;
    $showActions = $showActions ?? true;
    $itemType = 'trailer';
    $itemId = $trailer->id;
    $watchUrl = route('trailers.show', $trailer->slug);
@endphp

@once
    <style>
        .tcard {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #141a22;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
            display: flex;
            flex-direction: column;
        }
        .tcard:hover {
            transform: translateY(-6px);
            border-color: rgba(229, 9, 20, 0.55);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.5);
        }
        .tcard-media {
            position: relative;
            display: block;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: #0a0d12;
            text-decoration: none;
        }
        .tcard-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .tcard:hover .tcard-media img { transform: scale(1.06); }
        .tcard-hover {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10, 13, 18, 0.85) 0%, rgba(10, 13, 18, 0.15) 45%, transparent 75%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .tcard:hover .tcard-hover { opacity: 1; }
        .tcard-play {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: rgba(229, 9, 20, 0.92);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            box-shadow: 0 0 0 6px rgba(229, 9, 20, 0.25), 0 14px 34px rgba(0, 0, 0, 0.5);
            transform: scale(0.7);
            transition: transform 0.35s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }
        .tcard:hover .tcard-play { transform: scale(1); }
        .tcard-play i { margin-left: 3px; }
        .tcard-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            padding: 0.28rem 0.6rem;
            border-radius: 30px;
            background: rgba(10, 13, 18, 0.75);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #ffd24a;
            text-transform: uppercase;
        }
        .tcard-dur {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 2;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            background: rgba(10, 13, 18, 0.8);
            color: #e6ebf2;
            letter-spacing: 0.4px;
        }
        .tcard-body {
            padding: 0.85rem 0.95rem 0.95rem;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            flex: 1;
        }
        .tcard-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #f2f4f8;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }
        .tcard-title:hover { color: #ffd24a; }
        .tcard-meta {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }
        .tcard-chip {
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 0.18rem 0.5rem;
            border-radius: 30px;
            background: rgba(255, 210, 74, 0.14);
            border: 1px solid rgba(255, 210, 74, 0.35);
            color: #ffd24a;
            white-space: nowrap;
        }
        .tcard-meta span:not(.tcard-chip) {
            font-size: 0.72rem;
            color: #8a93a0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .tcard-lang {
            text-transform: uppercase;
            font-weight: 700;
            font-size: 0.64rem;
            color: #aeb6c2;
            padding: 0.12rem 0.4rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 6px;
        }
        .tcard-actions {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .tcard-fav {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.44rem 0.8rem;
            border-radius: 30px;
            font-family: inherit;
            font-size: 0.74rem;
            font-weight: 700;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #c3c9d1;
            transition: all 0.25s ease;
        }
        .tcard-fav:hover { border-color: rgba(229, 9, 20, 0.6); color: #fff; }
        .tcard-fav.active { background: rgba(229, 9, 20, 0.16); border-color: rgba(229, 9, 20, 0.6); color: #ff6b74; }
        .tcard-share {
            width: 32px;
            height: 32px;
            margin-left: auto;
            border-radius: 50%;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #aeb6c2;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }
        .tcard-share:hover { border-color: rgba(255, 210, 74, 0.5); color: #ffd24a; }
        .tcard-empty {
            text-align: center;
            padding: 3rem 1.5rem;
            color: #8a93a0;
            font-size: 0.9rem;
            border: 1px dashed rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            background: #141a22;
        }
        .tcard-empty i { font-size: 2rem; display: block; margin-bottom: 0.8rem; color: #e50914; opacity: 0.7; }
        .mm-toast {
            position: fixed;
            bottom: 92px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: #1c232e;
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            padding: 0.6rem 1.1rem;
            border-radius: 40px;
            font-size: 0.82rem;
            font-weight: 600;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s ease;
            z-index: 5000;
        }
        .mm-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    </style>

    <script>
        window.__mmAuthed = {{ auth()->check() ? 'true' : 'false' }};
        window.mmToastTimer = null;
        function mmToast(msg) {
            let t = document.querySelector('.mm-toast');
            if (!t) { t = document.createElement('div'); t.className = 'mm-toast'; document.body.appendChild(t); }
            t.textContent = msg;
            t.classList.add('show');
            clearTimeout(window.mmToastTimer);
            window.mmToastTimer = setTimeout(() => t.classList.remove('show'), 2200);
        }
        async function mmToggleFav(event, id, btn) {
            event.preventDefault();
            event.stopPropagation();
            if (!window.__mmAuthed) {
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                return;
            }
            const csrf = document.querySelector('meta[name="csrf-token"]');
            try {
                const res = await fetch('/interactions/favorite-toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf ? csrf.content : ''
                    },
                    body: JSON.stringify({ type: 'trailer', id: id })
                });
                const data = await res.json();
                if (data.error === 'login_required') {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                    return;
                }
                btn.classList.toggle('active', data.favorited);
                mmToast(data.favorited ? 'Added to your Watchlist' : 'Removed from your Watchlist');
            } catch (e) {}
        }
        async function mmShare(btn, url, title) {
            const target = url || window.location.href;
            const text = title ? (title + ' — watch on MOVIEMAX') : 'Watch this trailer on MOVIEMAX';
            try {
                if (navigator.share) {
                    await navigator.share({ title: text, url: target });
                    return;
                }
                await navigator.clipboard.writeText(target);
                mmToast('Link copied to clipboard');
            } catch (e) {}
        }
    </script>
@endonce

@if(!empty($trailer))
    <div class="tcard">
        <a href="{{ $watchUrl }}" class="tcard-media" onclick="event.stopPropagation();">
            @if($badge)
                <span class="tcard-badge">{{ $badge }}</span>
            @endif
            @if($trailer->type_display !== 'Official Trailer')
                <span class="tcard-badge" style="left:auto; right:10px; background:rgba(229,9,20,0.9); border:none; color:#fff;">{{ $trailer->type_display }}</span>
            @endif
            <img src="{{ $trailer->thumb_url }}" alt="{{ $trailer->title }}" loading="lazy">
            <span class="tcard-hover"><span class="tcard-play"><i class="fas fa-play"></i></span></span>
            @if($trailer->duration_label)
                <span class="tcard-dur"><i class="fas fa-clock" style="font-size:0.6rem;"></i> {{ $trailer->duration_label }}</span>
            @endif
        </a>
        <div class="tcard-body">
            <a href="{{ $watchUrl }}" class="tcard-title">{{ $trailer->title }}</a>
            <div class="tcard-meta">
                <span class="tcard-chip">{{ $trailer->type_display }}</span>
                @if($trailer->year_label)
                    <span><i class="fas fa-calendar-alt"></i> {{ $trailer->year_label }}</span>
                @endif
                @if($trailer->genre)
                    <span><i class="fas fa-tag"></i> {{ $trailer->genre }}</span>
                @endif
                @if($trailer->language && $trailer->language !== 'English')
                    <span class="tcard-lang">{{ \Illuminate\Support\Str::substr($trailer->language, 0, 3) }}</span>
                @endif
            </div>
            @if($showActions)
                <div class="tcard-actions">
                    <button type="button" class="tcard-fav {{ $favorited ? 'active' : '' }}" onclick="mmToggleFav(event, {{ $itemId }}, this)">
                        <i class="fas fa-heart"></i> {{ $favorited ? 'Saved' : 'Watchlist' }}
                    </button>
                    <button type="button" class="tcard-share" onclick="mmShare(this, '{{ $watchUrl }}', '{{ addslashes($trailer->title) }}')" title="Share">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="tcard-empty">
        <i class="fas fa-video-slash"></i>
        <p>{{ $emptyText ?? 'No trailers available yet.' }}</p>
    </div>
@endif