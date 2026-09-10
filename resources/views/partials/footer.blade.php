{{-- Shared cinematic footer --}}
@php
    $waNum = setting('whatsapp_number', '+255688349680');
    $waDigits = preg_replace('/[^0-9]/', '', $waNum);
@endphp
<style>
    .mm-footer {
        background:
            radial-gradient(900px 300px at 50% 0%, rgba(229, 9, 20, 0.10), transparent 70%),
            linear-gradient(180deg, var(--bg-surface), var(--bg-deep));
        border-top: 1px solid var(--glass-border);
        margin-top: 3.5rem;
        color: var(--text-secondary, #c3c9d1);
    }
    .mm-footer::before {
        content: '';
        display: block;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--accent-red, #e50914), #ffd700, var(--accent-red, #e50914), transparent);
    }
    .mm-footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 5% 2rem;
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr 1.3fr;
        gap: 2.5rem;
    }
    .mm-footer-brand {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 2.2rem;
        letter-spacing: 3px;
        background: linear-gradient(90deg, var(--accent-red, #e50914), #ff5c2a);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        line-height: 1;
    }
    .mm-footer-tag {
        margin: 0.9rem 0 1.2rem;
        font-size: 0.85rem;
        line-height: 1.7;
        color: var(--text-muted, #8a93a0);
        max-width: 300px;
    }
    .mm-footer-social {
        display: flex;
        gap: 0.7rem;
    }
    .mm-footer-social a {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        color: var(--text-secondary) !important;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .mm-footer-social a:hover {
        color: #fff !important;
        background: var(--accent-red, #e50914);
        border-color: var(--accent-red, #e50914);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(229, 9, 20, 0.35);
    }
    .mm-footer-col h4 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.15rem;
        letter-spacing: 2px;
        color: var(--text-primary, #fff);
        margin-bottom: 1.1rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    .mm-footer-col h4::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 28px;
        height: 2px;
        background: var(--accent-red, #e50914);
        border-radius: 2px;
    }
    .mm-footer-col a,
    .mm-footer-col span {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary, #c3c9d1);
        text-decoration: none;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        transition: color 0.25s ease, transform 0.25s ease;
    }
    .mm-footer-col a:hover {
        color: var(--text-primary, #fff);
        transform: translateX(4px);
    }
    .mm-footer-col a i,
    .mm-footer-col span i {
        width: 20px;
        text-align: center;
        color: var(--accent-red, #e50914);
        font-size: 0.8rem;
    }
    .mm-footer-bottom {
        border-top: 1px solid var(--glass-border);
        padding: 1.2rem 5%;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        font-size: 0.75rem;
        color: var(--text-muted, #8a93a0);
    }
    .mm-footer-bottom p { margin: 0; }
    @media (max-width: 900px) {
        .mm-footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
    }
    @media (max-width: 560px) {
        .mm-footer-inner { grid-template-columns: 1fr; }
        .mm-footer-bottom { flex-direction: column; text-align: center; }
    }
</style>
<footer class="mm-footer">
    <div class="mm-footer-inner">
        <div class="mm-footer-col">
            <div class="mm-footer-brand">{{ setting('site_name', 'MOVIEMAX') }}</div>
            <p class="mm-footer-tag">Watch, stream &amp; download free movies, series and trailers online in HD quality — anytime, anywhere, on any device.</p>
            <div class="mm-footer-social">
                <a href="https://www.facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="mm-footer-col">
            <h4>Explore</h4>
            <a href="/"><i class="fas fa-home"></i> Home</a>
            <a href="/movies"><i class="fas fa-film"></i> Movies</a>
            <a href="/series"><i class="fas fa-tv"></i> TV Series</a>
            <a href="/trailers"><i class="fas fa-video"></i> Trailers</a>
        </div>
        <div class="mm-footer-col">
            <h4>Company</h4>
            <a href="/about"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="/about#contact"><i class="fas fa-envelope"></i> Contact</a>
            <a href="/login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="/register"><i class="fas fa-user-plus"></i> Create Account</a>
        </div>
        <div class="mm-footer-col">
            <h4>Get in Touch</h4>
            <span><i class="fas fa-map-marker-alt"></i> Dar es Salaam, Tanzania</span>
            <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ $waNum }}</a>
            <a href="mailto:info@moviemax.co.tz"><i class="fas fa-envelope"></i> info@moviemax.co.tz</a>
            <a href="mailto:support@moviemax.co.tz"><i class="fas fa-headset"></i> support@moviemax.co.tz</a>
        </div>
    </div>
    <div class="mm-footer-bottom">
        <p>&copy; {{ date('Y') }} {{ setting('site_name', 'MOVIEMAX') }}. All rights reserved.</p>
        <p>Made with <i class="fas fa-heart" style="color: var(--accent-red, #e50914);"></i> for movie lovers &middot; A Product of Orange Software Company</p>
    </div>
</footer>