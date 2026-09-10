<!-- ============ PAGE LOADER ============ -->
<style>
    #mmPreloader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: var(--bg-deep, #0a0d12);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.2rem;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    #mmPreloader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    .mm-loader-logo {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 2.2rem;
        letter-spacing: 4px;
        background: linear-gradient(120deg, #ffffff 0%, var(--accent-red, #e50914) 60%, var(--accent-purple, #ffd700) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: mmLoaderPulse 1.4s ease-in-out infinite;
    }
    @keyframes mmLoaderPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .mm-loader-ring {
        position: relative;
        width: 96px;
        height: 96px;
    }
    .mm-loader-ring .ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 4px solid rgba(229, 9, 20, 0.15);
        border-top-color: var(--accent-red, #e50914);
        animation: mmLoaderSpin 0.9s linear infinite;
    }
    .mm-loader-ring .ring2 {
        position: absolute;
        inset: 12px;
        border-radius: 50%;
        border: 3px solid rgba(255, 215, 0, 0.15);
        border-bottom-color: var(--accent-purple, #ffd700);
        animation: mmLoaderSpin 1.3s linear infinite reverse;
    }
    @keyframes mmLoaderSpin { to { transform: rotate(360deg); } }
    .mm-loader-percent {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 2rem;
        color: var(--accent-purple, #ffd700);
        letter-spacing: 1px;
        min-width: 3.2ch;
        text-align: center;
    }
    .mm-loader-bar {
        width: 220px;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .mm-loader-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, var(--accent-red, #e50914), var(--accent-purple, #ffd700));
        border-radius: 10px;
        transition: width 0.2s ease;
    }
    @media (prefers-reduced-motion: reduce) {
        .mm-loader-logo, .mm-loader-ring .ring, .mm-loader-ring .ring2 { animation: none; }
    }
</style>
<div id="mmPreloader" aria-hidden="true">
    <div class="mm-loader-logo">MOVIEMAX</div>
    <div class="mm-loader-ring"><div class="ring"></div><div class="ring2"></div></div>
    <div class="mm-loader-percent" id="mmLoaderPercent">0%</div>
    <div class="mm-loader-bar"><div class="mm-loader-bar-fill" id="mmLoaderBar"></div></div>
</div>
<script>
    (function () {
        var el = document.getElementById('mmPreloader');
        var num = document.getElementById('mmLoaderPercent');
        var bar = document.getElementById('mmLoaderBar');
        if (!el) return;
        var start = null;
        var HIDE_AFTER = 600;
        function toPercent(p) {
            if (num) num.textContent = Math.max(0, Math.min(100, Math.round(p))) + '%';
            if (bar) bar.style.width = Math.max(0, Math.min(100, p)) + '%';
        }
        function done() {
            if (!el.classList.contains('hidden')) {
                el.classList.add('hidden');
                document.body.classList.add('loaded');
            }
        }
        function tick(ts) {
            if (!start) start = ts;
            var t = Math.min(1, (ts - start) / 900);
            var eased = 1 - Math.pow(1 - t, 3);
            toPercent(eased * 100);
            if (t >= 1) {
                setTimeout(done, HIDE_AFTER);
                return;
            }
            requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
        window.addEventListener('load', done);
        setTimeout(done, 3500);
    })();
</script>