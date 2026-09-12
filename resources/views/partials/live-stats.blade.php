{{-- Real-time stats: polls the interactions/stats endpoint and updates the page in place. Requires $itemType and $itemId. --}}
<script>
    (function () {
        var TYPE = @json($itemType);
        var ID = @json($itemId);

        function fmt(n) {
            n = Number(n || 0);
            return n.toLocaleString();
        }

        function setCommentCount(n) {
            var el = document.querySelector('.comment-total');
            if (el) el.textContent = fmt(n);
        }

        function refresh() {
            fetch('/interactions/stats?type=' + encodeURIComponent(TYPE) + '&id=' + encodeURIComponent(ID), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            }).then(function (res) { return res.json(); }).then(function (d) {
                if (!d || !d.ok) return;
                var vp = document.querySelector('.views-pill');
                if (vp) vp.innerHTML = '<i class="fas fa-eye"></i> ' + fmt(d.views) + ' views';
                var up = document.querySelector('.interaction-btn.up .count');
                var down = document.querySelector('.interaction-btn.down .count');
                if (up) up.textContent = fmt(d.likes);
                if (down) down.textContent = fmt(d.dislikes);
                var fav = document.querySelector('.fav-count');
                if (fav) fav.textContent = fmt(d.favorites);
                setCommentCount(d.comments);
                var mv = document.querySelector('.mm-meta-views');
                var ml = document.querySelector('.mm-meta-likes');
                var md = document.querySelector('.mm-meta-dislikes');
                if (mv) mv.textContent = fmt(d.views);
                if (ml) ml.textContent = fmt(d.likes);
                if (md) md.textContent = fmt(d.dislikes);
            }).catch(function () {});
        }

        refresh();
        window.__mmLiveStats = { refresh: refresh };
        setInterval(refresh, 8000);
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) refresh();
        });
    })();

    // Helpers to render a comment node client-side (mirrors partials/comment-node.blade.php)
    window.mmEscape = function (s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };
    window.mmEcho = function (s) { return window.mmEscape(String(s == null ? '' : s)).replace(/\n/g, '<br>'); };
    window.mmCommentNode = function (d, isReply) {
        var el = document.createElement('div');
        el.className = 'comment';
        el.id = 'comment-' + d.id;
        el.innerHTML =
            '<div class="comment-head">' +
            '<div class="comment-avatar"></div>' +
            '<span class="comment-author"></span>' +
            '<span class="comment-date"></span>' +
            '</div>' +
            '<div class="comment-body"></div>' +
            '<div class="comment-actions">' +
            '<button type="button" class="comment-reply-btn"><i class="fas fa-reply"></i> Reply</button>' +
            (d.can_delete ? '<button type="button" class="comment-delete-btn"><i class="fas fa-trash"></i> Delete</button>' : '') +
            '</div>' +
            '<div class="reply-form" style="display:none;">' +
            '<textarea rows="2" required placeholder="Write a reply..."></textarea>' +
            '<div class="form-actions"><button type="button" class="comment-submit"><i class="fas fa-paper-plane"></i> Post Reply</button></div>' +
            '</div>' +
            (isReply ? '' : '<div class="comment-replies"></div>');
        el.querySelector('.comment-avatar').textContent = String(d.author || '?').charAt(0).toUpperCase();
        el.querySelector('.comment-author').textContent = d.author || 'Guest';
        el.querySelector('.comment-date').textContent = d.created || '';
        el.querySelector('.comment-body').innerHTML = window.mmEcho(d.body);
        var rb = el.querySelector('.comment-reply-btn');
        rb.onclick = function () {
            var rf = el.querySelector('.reply-form');
            rf.style.display = rf.style.display === 'none' ? 'block' : 'none';
        };
        var sb = el.querySelector('.comment-submit');
        sb.onclick = function () {
            var txt = el.querySelector('textarea').value.trim();
            if (!txt) return;
            sb.disabled = true;
            window.mmPostComment(d.root_id || d.id, txt, function () {
                sb.disabled = false;
                rf.style.display = 'none';
                el.querySelector('textarea').value = '';
            });
        };
        if (d.can_delete) {
            el.querySelector('.comment-delete-btn').onclick = function () {
                if (!confirm('Delete this comment?')) return;
                window.mmDeleteComment(d.id, el, d.is_reply);
            };
        }
        return el;
    };
    window.mmDeleteComment = function (id, el, isReply) {
        fetch('/interactions/comment/' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (function () { var m = document.querySelector('meta[name="csrf-token"]'); return m ? m.content : ''; })(),
                'Accept': 'application/json'
            }
        }).then(function (res) { return res.json(); }).then(function (data) {
            if (data && data.ok) {
                var parent = el.parentElement;
                el.remove();
                if (parent && parent.classList.contains('comment-replies')) {
                    var root = parent.parentElement;
                    if (!parent.children.length) {
                        parent.remove();
                        if (root) root.querySelector('.reply-form').style.display = 'none';
                    }
                }
                var total = document.querySelector('.comment-total');
                if (total) {
                    var n = parseInt(total.textContent.replace(/[^\d]/g, ''), 10) || 0;
                    if (!isReply) total.textContent = (Math.max(0, n - 1)).toLocaleString();
                }
                if (window.__mmLiveStats) window.__mmLiveStats.refresh();
            } else if (data && data.error) {
                if (typeof handleLoginRequired === 'function') handleLoginRequired(data);
                else if (window.Swal) Swal.fire({ title: 'Oops', text: data.error, icon: 'error', background: '#161c26', color: '#fff', confirmButtonColor: '#e50914' });
            }
        }).catch(function () {});
    };
</script>