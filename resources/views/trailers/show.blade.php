<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $trailer->title }} - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => ($trailer->title ?? 'MOVIEMAX') . ' - Official Trailer | MOVIEMAX', 'seoDescription' => ($trailer->title ?? '') . ': ' . Illuminate\Support\Str::limit($trailer->description ?? 'Watch this trailer on MOVIEMAX.', 160), 'seoImagePath' => $trailer->thumb_url ?? '', 'seoType' => 'video.other', 'seoJsonLd' => [['@type' => 'VideoObject', 'name' => $trailer->title ?? '', 'description' => $trailer->description ?? '', 'thumbnailUrl' => $trailer->thumb_url ?? '', 'uploadDate' => ($trailer->created_at ?? now())->toIso8601String(), 'url' => url('/trailers/' . $trailer->id)], ['@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Trailers', 'item' => url('/trailers')], ['@type' => 'ListItem', 'position' => 3, 'name' => $trailer->title ?? '', 'item' => url('/trailers/' . $trailer->id)]]]]])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #3b82f6;
            --accent-red-dark: #2563eb;
            --accent-purple: #38bdf8;
            --accent-cyan: #38bdf8;
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

        .wrap { max-width: 1100px; margin: 0 auto; padding: 110px 5% 3rem; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
            transition: all 0.3s;
        }
        .back-link:hover { color: var(--accent-cyan); }

        .player-box {
            background: #000;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 30px 80px rgba(0,0,0,0.55);
            position: relative;
        }
        .player-box iframe { width: 100%; aspect-ratio: 16/9; display: block; border: 0; }
        .player-box video { width: 100%; aspect-ratio: 16/9; display: block; background: #000; }

        .trailer-title-block { padding: 1.6rem 0 0.6rem; }
        .trailer-title-block h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            letter-spacing: 2px;
            margin-bottom: 0.6rem;
        }
        .trailer-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        .trailer-meta span { display: inline-flex; align-items: center; gap: 6px; }
        .trailer-desc { color: var(--text-secondary); font-size: 0.93rem; line-height: 1.7; max-width: 760px; margin-top: 1rem; }

        .engagement {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin: 1.4rem 0 2rem;
            padding: 1.1rem 0;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }
        .interaction-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 1.1rem;
            border-radius: 40px;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .interaction-btn:hover { border-color: rgba(59, 130, 246, 0.5); color: var(--text-primary); }
        .interaction-btn .count { font-weight: 700; }
        .interaction-btn.up.active { background: rgba(59, 130, 246, 0.18); border-color: var(--accent-red); color: #ffd700; }
        .interaction-btn.down.active { background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.5); color: #fbbf24; }
        .fav-heart {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 1.1rem;
            border-radius: 40px;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .fav-heart:hover { color: #fbbf24; border-color: rgba(59, 130, 246, 0.5); }
        .fav-heart.active { background: rgba(59, 130, 246, 0.12); border-color: rgba(59, 130, 246, 0.55); color: #fbbf24; }

        /* Comments */
        .comments-section { margin-top: 1.5rem; }
        .comments-section h2 { font-size: 1.25rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 10px; }
        .comment-form {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.4rem;
        }
        .comment-form textarea {
            width: 100%;
            background: rgba(10, 13, 18, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            padding: 0.8rem 1rem;
            resize: vertical;
            min-height: 70px;
        }
        .comment-form textarea:focus { outline: none; border-color: rgba(59, 130, 246, 0.6); }
        .form-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.8rem; margin-top: 0.7rem; }
        .form-actions small { color: var(--text-muted); font-size: 0.72rem; }
        .comment-submit {
            background: var(--accent-red);
            border: none;
            color: #fff;
            padding: 0.55rem 1.3rem;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        .comment-submit:hover { filter: brightness(1.15); }

        .comment {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 0.95rem 1.1rem;
            margin-bottom: 0.8rem;
        }
        .comment-head { display: flex; align-items: center; gap: 10px; margin-bottom: 0.5rem; }
        .comment-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--accent-red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .comment-author { font-size: 0.85rem; font-weight: 600; }
        .comment-date { font-size: 0.7rem; color: var(--text-muted); }
        .comment-body { font-size: 0.88rem; line-height: 1.6; color: var(--text-secondary); white-space: pre-wrap; word-break: break-word; }
        .comment-actions { display: flex; gap: 0.9rem; margin-top: 0.6rem; }
        .comment-reply-btn, .comment-delete-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 0.75rem;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0;
            transition: color 0.2s;
        }
        .comment-reply-btn:hover { color: var(--accent-cyan); }
        .comment-delete-btn:hover { color: #fbbf24; }
        .reply-form {
            margin-top: 0.8rem;
            background: rgba(10, 13, 18, 0.5);
            border-radius: 12px;
            padding: 0.8rem;
        }
        .reply-form textarea {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.85rem;
            padding: 0.6rem 0.8rem;
            resize: vertical;
        }
        .reply-form textarea:focus { outline: none; border-color: rgba(59, 130, 246, 0.6); }
        .comment-replies {
            margin-top: 0.8rem;
            margin-left: 1.3rem;
            border-left: 2px solid rgba(59, 130, 246, 0.25);
            padding-left: 0.9rem;
        }
        .no-comments { color: var(--text-muted); text-align: center; padding: 2.5rem 1rem; font-size: 0.9rem; }

        .more-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.1rem;
            margin-top: 1.2rem;
        }
        .more-card {
            display: block;
            border-radius: 14px;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        .more-card:hover { transform: translateY(-4px); border-color: rgba(59, 130, 246, 0.5); }
        .more-card img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; }
        .more-card h4 { font-size: 0.82rem; padding: 0.7rem 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        footer { margin-top: 3.5rem; border-top: 1px solid var(--glass-border); padding: 1.6rem 5%; text-align: center; color: var(--text-muted); font-size: 0.82rem; }
    </style>
</head>
<body>
@include('partials.navbar')

<div class="wrap">
    <a href="{{ route('trailers') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Trailers</a>

    <div class="player-box">
        @if($trailer->source_type === 'file' && $trailer->file_path)
            <video controls autoplay poster="{{ $trailer->thumb_url }}">
                <source src="{{ $trailer->file_path }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @elseif($trailer->youtube_id)
            <iframe src="https://www.youtube-nocookie.com/embed/{{ $trailer->youtube_id }}?autoplay=1&rel=0&modestbranding=1&iv_load_policy=3"
                    title="{{ $trailer->title }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        @else
            <div style="padding: 6rem 1rem; text-align:center; color: var(--text-muted);">
                <i class="fas fa-video-slash" style="font-size:2.5rem; margin-bottom:1rem;"></i>
                <p>No video source available for this trailer.</p>
            </div>
        @endif
    </div>

    <div class="trailer-title-block">
        <h1>{{ $trailer->title }}</h1>
        <div class="trailer-meta">
            <span><i class="fas fa-eye"></i> {{ number_format($trailer->views) }} views</span>
            <span><i class="fas fa-thumbs-up" style="color: var(--accent-cyan);"></i> {{ $interaction['likes'] }} likes</span>
            <span><i class="fas fa-thumbs-down"></i> {{ $interaction['dislikes'] }} dislikes</span>
            <span><i class="fas fa-clock"></i> {{ $trailer->created_at->diffForHumans() }}</span>
        </div>
        @if($trailer->description)
            <p class="trailer-desc">{{ $trailer->description }}</p>
        @endif
    </div>

    <div class="engagement">
        <button type="button" class="interaction-btn up {{ $interaction['my_reaction'] === 'like' ? 'active' : '' }}" onclick="react(event, 'like', this)">
            <i class="fas fa-thumbs-up"></i><span class="count">{{ $interaction['likes'] }}</span>
        </button>
        <button type="button" class="interaction-btn down {{ $interaction['my_reaction'] === 'dislike' ? 'active' : '' }}" onclick="react(event, 'dislike', this)">
            <i class="fas fa-thumbs-down"></i><span class="count">{{ $interaction['dislikes'] }}</span>
        </button>
        <button type="button" class="fav-heart {{ $interaction['favorited'] ? 'active' : '' }}" onclick="toggleFavorite(event, this)">
            <i class="fas fa-heart"></i> <span>Favorite</span>
        </button>
    </div>

    <div class="comments-section">
        <h2><i class="fas fa-comments" style="color: var(--accent-cyan);"></i> Comments ({{ $comments->count() }})</h2>
        <form class="comment-form" onsubmit="submitComment(event)">
            <textarea id="commentBody" placeholder="Share your thoughts..." required></textarea>
            <div class="form-actions">
                <button type="submit" class="comment-submit" id="commentSubmit"><i class="fas fa-paper-plane"></i> Post Comment</button>
                <small>Comments are public. Please keep it respectful.</small>
            </div>
        </form>

        <div id="commentsList">
            @php
                $canDeleteComment = fn($c) => (bool) auth()->id() && auth()->id() === $c->user_id;
            @endphp
            @forelse($comments as $comment)
                @include('partials.comment-node', [
                    'comment'          => $comment,
                    'depth'            => 0,
                    'itemType'         => 'trailer',
                    'itemId'           => $trailer->id,
                    'canDeleteComment' => $canDeleteComment,
                ])
            @empty
                <div class="no-comments">No comments yet. Be the first to share your thoughts!</div>
            @endforelse
        </div>
    </div>

    @if($moreTrailers->count() > 0)
        <div style="margin-top: 2.5rem;">
            <h2 style="font-size:1.25rem; margin-bottom:1rem;"><i class="fas fa-film" style="color: var(--accent-cyan); margin-right:0.5rem;"></i> More Trailers</h2>
            <div class="more-grid">
                @foreach($moreTrailers as $t)
                    <a href="{{ route('trailers.show', $t->id) }}" class="more-card">
                        <img src="{{ $t->thumb_url }}" alt="{{ $t->title }}" fetchpriority="high">
                        <h4>{{ $t->title }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<footer>
    <p>&copy; {{ date('Y') }} MOVIEMAX &mdash; All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const ITEM_TYPE = 'trailer';
    const ITEM_ID = {{ $trailer->id }};

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
                confirmButtonColor: '#3b82f6',
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

    async function react(e, kind, btn) {
        const data = await jsonPost('/interactions/react', { type: ITEM_TYPE, id: ITEM_ID, reaction: kind });
        if (handleLoginRequired(data)) return;
        const up = document.querySelector('.interaction-btn.up');
        const down = document.querySelector('.interaction-btn.down');
        up.classList.toggle('active', data.reaction === 'like');
        down.classList.toggle('active', data.reaction === 'dislike');
        up.querySelector('.count').textContent = data.like_count;
        down.querySelector('.count').textContent = data.dislike_count;
    }

    async function toggleFavorite(e, btn) {
        const data = await jsonPost('/interactions/favorite-toggle', { type: ITEM_TYPE, id: ITEM_ID });
        if (handleLoginRequired(data)) return;
        btn.classList.toggle('active', data.favorited);
    }

    function toggleReplyForm(id) {
        const el = document.getElementById('reply-form-' + id);
        if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    async function submitComment(ev) {
        ev.preventDefault();
        const body = document.getElementById('commentBody').value.trim();
        if (!body) return;
        const btn = document.getElementById('commentSubmit');
        btn.disabled = true;
        const data = await jsonPost('/interactions/comment', { type: ITEM_TYPE, id: ITEM_ID, body: body });
        if (handleLoginRequired(data)) { btn.disabled = false; return; }
        window.location.reload();
    }

    async function submitReply(rootId) {
        const body = document.getElementById('replyBody-' + rootId).value.trim();
        if (!body) return;
        const data = await jsonPost('/interactions/comment', {
            type: ITEM_TYPE, id: ITEM_ID, body: body, parent_id: rootId
        });
        if (handleLoginRequired(data)) return;
        window.location.reload();
    }

    async function deleteComment(commentId) {
        if (!confirm('Delete this comment?')) return;
        const res = await fetch('/interactions/comment/' + commentId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (handleLoginRequired(data)) return;
        if (data.ok) window.location.reload();
        else if (data.error) Swal.fire({ title: 'Oops', text: data.error, icon: 'error', background: '#161c26', color: '#fff', confirmButtonColor: '#3b82f6' });
    }
</script>
</body>
</html>