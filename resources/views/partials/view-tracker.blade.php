<script>
    /* Global view tracker: posts a 'view' after 5 seconds of actual playback. */
    (function () {
        var TRACKED = {};

        function csrf() {
            var m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.content : '';
        }

        function fire(type, id) {
            var key = type + '_' + id;
            if (TRACKED[key]) return;
            TRACKED[key] = true;
            try {
                fetch('/view-track', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ type: type, id: id })
                }).catch(function () {});
            } catch (e) {}
        }

        // Correct visibility check: offsetParent is always null for
        // position:fixed elements (modals), so we use the viewport rect.
        function isVisible(el) {
            if (!el) return false;
            var r = el.getBoundingClientRect();
            if (r.width <= 0 || r.height <= 0) return false;
            var vw = window.innerWidth || document.documentElement.clientWidth;
            var vh = window.innerHeight || document.documentElement.clientHeight;
            return r.top < vh && r.bottom > 0 && r.left < vw && r.right > 0;
        }

        window.mmViewTracker = {
            fire: fire,

            trackVideo: function (el, type, id) {
                if (!el || el.dataset.mmTrack === type + '_' + id) return;
                el.dataset.mmTrack = type + '_' + id;
                var counted = 0, last = null;
                el.addEventListener('timeupdate', function () {
                    if (el.paused || el.ended || el.seeking) { last = null; return; }
                    if (last !== null && el.currentTime > last) counted += (el.currentTime - last);
                    last = el.currentTime;
                    if (counted >= 5) fire(type, id);
                });
            },

            trackYouTube: function (iframe, type, id) {
                if (!iframe || iframe.dataset.mmTrack === type + '_' + id) return;
                iframe.dataset.mmTrack = type + '_' + id;

                var fired = false;
                function fireOnce() {
                    if (fired) return;
                    fired = true;
                    cleanup();
                    fire(type, id);
                }

                // Track real playback by reading the player's currentTime.
                var counted = 0, last = null, paused = true;

                // 1) postMessage events: when the embed has enablejsapi=1,
                //    YouTube reports onStateChange + infoDelivery to the parent.
                var win = iframe.contentWindow;
                function onMessage(e) {
                    try {
                        if (e.source !== win || !e.data || typeof e.data !== 'object') return;
                        var d = e.data;
                        if (d.event === 'onStateChange') {
                            paused = (d.info !== 1); // 1 == playing
                            if (paused) last = null;
                        } else if (d.event === 'infoDelivery' && d.info && typeof d.info.currentTime === 'number') {
                            if (paused) { last = null; return; }
                            var t = d.info.currentTime;
                            if (last !== null && t > last) counted += (t - last);
                            last = t;
                            if (counted >= 5) fireOnce();
                        }
                    } catch (e2) {}
                }

                // 2) Poll the container while the modal is open; count a view
                //    after ~15s of it being on-screen even if the API is blocked.
                var box = iframe.closest('.player-box') || iframe.closest('.trailer-modal') || iframe;
                var started = null;
                var pollTimer = setInterval(function () {
                    if (document.hidden || !isVisible(box)) { started = null; return; }
                    if (started === null) started = Date.now();
                    if (Date.now() - started >= 15000) fireOnce();
                }, 1000);

                function cleanup() {
                    if (pollTimer) clearInterval(pollTimer);
                    if (win) window.removeEventListener('message', onMessage);
                }

                window.addEventListener('message', onMessage);
            }
        };
    })();
</script>