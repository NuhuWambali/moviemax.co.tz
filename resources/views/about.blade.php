{{-- resources/views/about.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>About Us - MovieMax</title>
    @include('partials.seo', ['seoTitle' => 'About Us - MovieMax', 'seoDescription' => 'Learn about MovieMax — your destination for watching, streaming and downloading free movies, TV series and trailers online.'])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --accent-gold: #ffd700;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-border: rgba(255,255,255,0.07);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-surface); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        /* ============ HERO ============ */
        .about-hero {
            position: relative;
            padding: 140px 5% 90px;
            text-align: center;
            overflow: hidden;
            border-bottom: 1px solid var(--glass-border);
            background-image:
                radial-gradient(800px 400px at 20% -10%, rgba(229, 9, 20, 0.35), transparent 60%),
                radial-gradient(700px 380px at 80% 10%, rgba(229, 9, 20, 0.18), transparent 60%),
                linear-gradient(180deg, rgba(10, 13, 18, 0.78) 0%, rgba(10, 13, 18, 0.92) 60%, rgba(10, 13, 18, 0.98) 100%),
                url('https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1600&h=800&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        @media (hover: none), (max-width: 760px) { .about-hero { background-attachment: scroll; } }
        .about-hero .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.1rem;
            border-radius: 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-bottom: 1.5rem;
        }
        .about-hero .badge i { color: var(--accent-gold); }
        .about-hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 6vw, 5rem);
            letter-spacing: 5px;
            line-height: 1;
            color: var(--text-primary);
            text-shadow: 0 6px 40px rgba(229, 9, 20, 0.4);
        }
        .about-hero h1 span { color: var(--accent-red); }
        .about-hero p.sub {
            color: var(--text-secondary);
            max-width: 620px;
            margin: 1.2rem auto 0;
            font-size: 1.05rem;
            line-height: 1.8;
        }
        .about-hero .cta-row {
            margin-top: 2.2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 0.8rem 1.8rem;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-cta.solid {
            background: linear-gradient(135deg, #ff4757, var(--accent-red-dark));
            color: #fff;
            box-shadow: 0 8px 26px rgba(229, 9, 20, 0.4);
        }
        .btn-cta.solid:hover { transform: translateY(-2px); filter: brightness(1.12); box-shadow: 0 12px 34px rgba(229, 9, 20, 0.55); }
        .btn-cta.ghost {
            color: var(--text-primary);
            border: 1px solid var(--glass-border);
            background: rgba(255,255,255,0.04);
        }
        .btn-cta.ghost:hover { border-color: var(--accent-red); color: var(--accent-red); transform: translateY(-2px); }

        /* ============ STATS ============ */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
            max-width: 1200px;
            margin: -46px auto 0;
            padding: 0 5%;
            position: relative;
            z-index: 2;
        }
        .stat-card {
            background: linear-gradient(160deg, var(--bg-card), var(--bg-surface));
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 1.6rem 1.2rem;
            text-align: center;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.45);
            transition: all 0.35s ease;
        }
        .stat-card:hover { transform: translateY(-6px); border-color: rgba(229, 9, 20, 0.5); }
        .stat-card .num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            background: linear-gradient(90deg, var(--accent-red), #ff5c2a);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
        }
        .stat-card .lbl { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem; }

        .container { max-width: 1200px; margin: 0 auto; padding: 4.5rem 5% 3rem; }

        /* ============ STORY ============ */
        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            letter-spacing: 3px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .section-title i { color: var(--accent-red); font-size: 1.7rem; }
        .section-sub { color: var(--text-secondary); max-width: 640px; margin-bottom: 2.5rem; line-height: 1.8; }
        .story-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 3.5rem;
            align-items: center;
        }
        .story-text p { color: var(--text-secondary); line-height: 1.9; margin-bottom: 1.2rem; font-size: 0.97rem; }
        .story-text p strong { color: var(--text-primary); }
        .story-img {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
        }
        .story-img::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 55%, rgba(10, 13, 18, 0.6));
        }
        .story-img img { width: 100%; display: block; aspect-ratio: 4/3; object-fit: cover; }
        .story-img .play-chip {
            position: absolute;
            bottom: 16px;
            left: 16px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 0.6rem 1.2rem;
            border-radius: 40px;
            background: rgba(10, 13, 18, 0.8);
            border: 1px solid var(--glass-border);
            font-size: 0.82rem;
            color: var(--text-primary);
        }
        .story-img .play-chip i { color: var(--accent-red); }

        /* ============ MISSION / VISION / VALUES ============ */
        .mvv-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.6rem;
            margin-top: 1rem;
        }
        .mvv-card {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 22px;
            padding: 2.4rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }
        .mvv-card:hover { transform: translateY(-8px); border-color: rgba(229, 9, 20, 0.55); box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4); }
        .mvv-card .ico {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.2rem;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--accent-red);
            background: rgba(229, 9, 20, 0.12);
            box-shadow: inset 0 0 0 1px rgba(229, 9, 20, 0.25);
        }
        .mvv-card h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.7rem;
            letter-spacing: 2px;
            margin-bottom: 0.9rem;
        }
        .mvv-card p { color: var(--text-secondary); line-height: 1.8; font-size: 0.92rem; }

        /* ============ FEATURES ============ */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.4rem;
            margin-top: 1rem;
        }
        .feature {
            display: flex;
            gap: 1.1rem;
            align-items: flex-start;
            padding: 1.5rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }
        .feature:hover { background: rgba(229, 9, 20, 0.05); border-color: rgba(229, 9, 20, 0.4); transform: translateY(-3px); }
        .feature .fico {
            min-width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: var(--accent-gold);
            background: rgba(255, 215, 0, 0.08);
        }
        .feature h4 { font-size: 1rem; margin-bottom: 0.4rem; }
        .feature p { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.7; }

        /* ============ CONTACT ============ */
        .contact-section {
            background: linear-gradient(160deg, var(--bg-surface), var(--bg-deep));
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 3rem;
            margin-top: 1.5rem;
        }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; }
        .contact-item-wrap {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.1rem;
            padding: 1rem 1.2rem;
            background: rgba(255,255,255,0.03);
            border-radius: 16px;
            border: 1px solid transparent;
            transition: all 0.3s;
        }
        .contact-item-wrap:hover { background: rgba(229, 9, 20, 0.06); border-color: var(--glass-border); transform: translateX(8px); }
        .contact-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--accent-red);
            background: rgba(229, 9, 20, 0.12);
        }
        .contact-text h4 { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-bottom: 0.2rem; }
        .contact-text p, .contact-text a { color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .contact-text a:hover { color: var(--accent-red); }
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: #fff;
            padding: 0.9rem 1.8rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 0.8rem;
            transition: all 0.3s;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.3);
        }
        .whatsapp-btn:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 12px 30px rgba(37, 211, 102, 0.45); }
        .contact-form {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2.2rem;
        }
        .contact-form h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 2px;
            margin-bottom: 1.4rem;
            color: var(--text-primary);
        }
        .form-group { margin-bottom: 1rem; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.95rem 1.2rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            resize: vertical;
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: var(--text-muted); }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.15);
            background: rgba(255,255,255,0.06);
        }
        .submit-btn {
            background: linear-gradient(135deg, #ff4757, var(--accent-red-dark));
            color: #fff;
            border: none;
            padding: 0.95rem;
            width: 100%;
            border-radius: 12px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(229, 9, 20, 0.35); }

        /* ============ MAP ============ */
        .map-section {
            margin-top: 2.5rem;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
        }
        .map-section iframe { width: 100%; height: 350px; border: none; display: block; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .stats-strip { grid-template-columns: repeat(2, 1fr); }
            .story-grid, .contact-grid { grid-template-columns: 1fr; }
            .mvv-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 620px) {
            .stats-strip { grid-template-columns: 1fr 1fr; margin-top: -40px; }
            .feature-grid { grid-template-columns: 1fr; }
            .feature { padding: 1.1rem; }
            .contact-section { padding: 1.5rem; }
            .contact-form { padding: 1.4rem; }
            .section-title { font-size: 2rem; }
        }
    </style>
</head>
<body>
@include('partials.loader')    @include('partials.navbar')

    <div class="about-hero">
        <div class="badge"><i class="fas fa-star"></i> Made for movie lovers, by movie lovers</div>
        <h1>ABOUT <span>MOVIEMAX</span></h1>
        <p class="sub">Your one-stop destination to watch, stream and download free movies, TV series and trailers online — in stunning HD, on any device, anytime.</p>
        <div class="cta-row">
            <a href="/movies" class="btn-cta solid"><i class="fas fa-film"></i> Browse Movies</a>
            <a href="/trailers" class="btn-cta ghost"><i class="fas fa-video"></i> Watch Trailers</a>
        </div>
    </div>

    <div class="stats-strip">
        <div class="stat-card">
            <div class="num">{{ \App\Models\Movie::count() + \App\Models\Series::count() }}+</div>
            <div class="lbl">Movies &amp; Series</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ \App\Models\User::count() }}+</div>
            <div class="lbl">Happy Members</div>
        </div>
        <div class="stat-card">
            <div class="num">4K</div>
            <div class="lbl">Ultra HD Quality</div>
        </div>
        <div class="stat-card">
            <div class="num">24/7</div>
            <div class="lbl">Member Support</div>
        </div>
    </div>

    <div class="container">
        <!-- Story -->
        <div class="story-grid">
            <div class="story-text">
                <h2 class="section-title"><i class="fas fa-film"></i> Our Story</h2>
                <p><strong>MovieMax</strong> was born from a simple belief: entertainment should be free, accessible and enjoyable for everyone. Born from the vision of <strong>Orange Software Company</strong>, we built a platform that puts the magic of cinema right at your fingertips.</p>
                <p>We bring you the latest <strong>movies</strong>, timeless <strong>series</strong> and the hottest official <strong>trailers</strong> — all in stunning quality, ready to <strong>watch online</strong>, <strong>stream</strong> in HD, or <strong>download</strong> to enjoy offline.</p>
                <p>Whether you're curled up on your couch or on the go with your phone, MovieMax makes sure your favorite content is always just one click away.</p>
            </div>
            <div class="story-img">
                <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&h=600&fit=crop" alt="Movie theater" loading="lazy">
                <span class="play-chip"><i class="fas fa-play"></i> Press Play &amp; Enjoy</span>
            </div>
        </div>

        <!-- Mission / Vision / Values -->
        <div style="margin-top:4.5rem;">
            <h2 class="section-title"><i class="fas fa-bullseye"></i> What Drives Us</h2>
            <div class="mvv-grid">
                <div class="mvv-card">
                    <div class="ico"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To make world-class entertainment free and easy for everyone by delivering a seamless streaming experience across every screen — with new movies, series and trailers added regularly.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>To become Africa's leading entertainment platform, connecting millions of movie lovers with the content they love — while championing local filmmakers and the creative industry.</p>
                </div>
                <div class="mvv-card">
                    <div class="ico"><i class="fas fa-gem"></i></div>
                    <h3>Our Values</h3>
                    <p>Free access for all. Crystal-clear HD quality. Instant streaming and downloads. Honest, reliable service and support that always puts our members first.</p>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div style="margin-top:4.5rem;">
            <h2 class="section-title"><i class="fas fa-thumbs-up"></i> What You Get</h2>
            <div class="feature-grid">
                <div class="feature">
                    <div class="fico"><i class="fas fa-play-circle"></i></div>
                    <div>
                        <h4>Stream Movies &amp; Series</h4>
                        <p>Watch instantly in HD with our fast built-in player — no buffers, no complexity.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="fico"><i class="fas fa-download"></i></div>
                    <div>
                        <h4>Free Downloads</h4>
                        <p>Download your favorite titles and enjoy them offline, on any device, anytime.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="fico"><i class="fas fa-video"></i></div>
                    <div>
                        <h4>Official Trailers</h4>
                        <p>Stay ahead of the curve with the latest official trailers for upcoming releases.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="fico"><i class="fas fa-heart"></i></div>
                    <div>
                        <h4>Favorites &amp; Progress</h4>
                        <p>Save favorites, react to content and pick up right where you left off on any device.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="contact-section" id="contact">
            <div class="contact-grid">
                <div>
                    <h2 class="section-title"><i class="fas fa-envelope"></i> Get In Touch</h2>
                    <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 1.6rem;">Questions, feedback, or just want to say hello? We'd love to hear from you — our team is always ready to help.</p>

                    <div class="contact-item-wrap">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-text">
                            <h4>Visit Us</h4>
                            <p>Dar es Salaam, Tanzania</p>
                        </div>
                    </div>
                    <div class="contact-item-wrap">
                        <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                        <div class="contact-text">
                            <h4>WhatsApp</h4>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number', '+255688349680')) }}" target="_blank">{{ setting('whatsapp_number', '+255 688 349 680') }}</a>
                        </div>
                    </div>
                    <div class="contact-item-wrap">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-text">
                            <h4>Email Us</h4>
                            <a href="mailto:info@moviemax.co.tz">info@moviemax.co.tz</a>
                        </div>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('whatsapp_number', '+255688349680')) }}" target="_blank" class="whatsapp-btn">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>

                <div class="contact-form">
                    <h3>Send us a message</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="map-section">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1014439.9384660947!2d38.800238799999996!3d-6.792354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4b2d2b6b9b6b%3A0x8b9b9b9b9b9b9b9b!2sDar%20es%20Salaam%2C%20Tanzania!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>

    @include('partials.footer')

    <script>
        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    </script>
</body>
</html>