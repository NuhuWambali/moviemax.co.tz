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
                var loaded = false;
                function to(v) { return (typeof v === 'number') ? v : 0; }
                function attach() {
                    try {
                        var m = (iframe.src || '').match(/embed\/([\w-]{11})/) || (iframe.src || '').match(/v=([\w-]{11})/);
                        var videoId = m ? m[1] : '';
                        if (!videoId) return;
                        var player = new YT.Player(iframe, {
                            videoId: videoId,
                            playerVars: { autoplay: 1, rel: 0, modestbranding: 1, iv_load_policy: 3 },
                            events: {
                                onReady: function () {
                                    var counted = 0, last = null, fired = false;
                                    var timer = setInterval(function () {
                                        var p = player;
                                        if (!p || typeof p.getPlayerState !== 'function') return;
                                        if (p.getPlayerState() !== 1) { last = null; return; }
                                        var t = to(p.getCurrentTime());
                                        if (last !== null && t > last) counted += (t - last);
                                        last = t;
                                        if (!fired && counted >= 5) {
                                            fired = true;
                                            clearInterval(timer);
                                            fire(type, id);
                                        }
                                    }, 1000);
                                }
                            }
                        });
                    } catch (e) {}
                }
                if (window.YT && window.YT.Player) { attach(); return; }
                if (!loaded) {
                    loaded = true;
                    window.__mmYTReady = window.onYouTubeIframeAPIReady;
                    window.onYouTubeIframeAPIReady = function () {
                        attach();
                        if (window.__mmYTReady) window.__mmYTReady();
                    };
                    var s = document.createElement('script');
                    s.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(s);
                }
            }
        };
    })();
</script>