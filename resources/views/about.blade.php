{{-- resources/views/about.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>About Us - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'About Us - MOVIEMAX', 'seoDescription' => 'Learn about MOVIEMAX — your destination for streaming and downloading the latest movies and TV series in HD.'])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #3b82f6;
            --accent-purple: #38bdf8;
            --accent-cyan: #38bdf8;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-border: rgba(255,255,255,0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--bg-deep);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-surface); }
        ::-webkit-scrollbar-thumb { background: var(--accent-red); border-radius: 10px; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(10, 13, 18, 0.75);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            transition: 0.3s;
        }

        .logo {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.8rem;
            letter-spacing: 2px;
            background: var(--accent-red);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            z-index: 1001;
            animation: gradientShift 4s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
            font-size: 0.9rem;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--accent-red);
        }

        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-primary);
            z-index: 1001;
        }

        @media (max-width: 768px) {
            .menu-btn { display: block; }
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background: rgba(10, 13, 18, 0.98);
                backdrop-filter: blur(30px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2rem;
                transition: 0.3s ease;
                z-index: 1000;
                border-left: 1px solid var(--glass-border);
            }
            .nav-links.active { right: 0; }
            .nav-links a { font-size: 1.1rem; }
        }

        /* Page Header */
        .page-header {
            padding: 140px 5% 70px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(59, 130, 246, 0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 30%, rgba(59, 130, 246, 0.1) 0%, transparent 60%);
        }

        .page-header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 5rem;
            letter-spacing: 4px;
            background: var(--accent-red);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            color: var(--text-secondary);
            margin-top: 1rem;
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
        }

        /* Container */
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2rem 5% 4rem;
        }

        /* About Section */
        .about-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            margin-bottom: 5rem;
        }

        .about-content h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.8rem;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .about-content p {
            color: var(--text-secondary);
            line-height: 1.9;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .about-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.2rem 1rem;
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            transition: 0.3s;
        }

        .stat-item:hover {
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-3px);
        }

        .stat-number {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.4rem;
            letter-spacing: 1px;
            background: var(--accent-red);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
        }

        .about-image {
            position: relative;
        }

        .about-image img {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            border: 1px solid var(--glass-border);
        }

        .about-image::before {
            content: '';
            position: absolute;
            top: -15px; left: -15px; right: -15px; bottom: -15px;
            background: rgba(59, 130, 246, 0.15);
            border-radius: 30px;
            z-index: -1;
        }

        /* Mission & Vision */
        .mission-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 5rem;
        }

        .mission-card, .vision-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            border: 1px solid var(--glass-border);
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .mission-card::before, .vision-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            padding: 1px;
            background: var(--accent-red);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .mission-card:hover::before, .vision-card:hover::before {
            opacity: 1;
        }

        .mission-card:hover, .vision-card:hover {
            transform: translateY(-8px);
            background: rgba(10, 13, 18, 0.8);
        }

        .mission-card i, .vision-card i {
            font-size: 2.8rem;
            margin-bottom: 1.2rem;
            background: var(--accent-red);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 0 12px rgba(59, 130, 246, 0.4));
        }

        .mission-card h3, .vision-card h3 {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.8rem;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .mission-card p, .vision-card p {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Contact Section */
        .contact-section {
            background: var(--bg-surface);
            backdrop-filter: blur(16px);
            border-radius: 32px;
            padding: 3rem;
            margin-top: 2rem;
            border: 1px solid var(--glass-border);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }

        .contact-info h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.6rem;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            background: var(--text-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .contact-info p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .contact-details {
            margin-bottom: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
            padding: 1rem 1.2rem;
            background: rgba(255,255,255,0.03);
            border-radius: 16px;
            border: 1px solid transparent;
            transition: 0.3s;
        }

        .contact-item:hover {
            background: rgba(59, 130, 246, 0.06);
            border-color: var(--glass-border);
            transform: translateX(8px);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background: rgba(59, 130, 246, 0.12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--accent-red);
        }

        .contact-text h4 {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
            font-weight: 500;
        }

        .contact-text p, .contact-text a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .contact-text a:hover {
            color: var(--accent-red);
        }

        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #3b82f6;
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: 0.3s;
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
        }

        .whatsapp-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
        }

        /* Contact Form */
        .contact-form {
            background: rgba(255,255,255,0.02);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 2.5rem;
            border: 1px solid var(--glass-border);
        }

        .contact-form h3 {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.6rem;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 1rem 1.2rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: 0.3s;
        }

        .form-group input::placeholder, .form-group textarea::placeholder {
            color: var(--text-muted);
        }

        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background: rgba(255,255,255,0.06);
        }

        .submit-btn {
            background: var(--accent-red);
            color: white;
            border: none;
            padding: 1rem;
            width: 100%;
            border-radius: 12px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        /* Map Section */
        .map-section {
            margin-top: 3rem;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
        }

        .map-section iframe {
            width: 100%;
            height: 350px;
            border: none;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2.5rem;
            border-top: 1px solid transparent;
            border-image: var(--accent-red) 1;
            margin-top: 3rem;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .about-section, .mission-vision, .contact-grid {
                grid-template-columns: 1fr;
            }
            .about-image { order: -1; }
        }

        @media (max-width: 768px) {
            .page-header h1 { font-size: 3rem; }
            .about-stats { grid-template-columns: 1fr; gap: 1rem; }
            .contact-section { padding: 1.5rem; }
            .contact-form { padding: 1.5rem; }
            .about-content h2 { font-size: 2rem; }
            .contact-info h2 { font-size: 2rem; }
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="page-header">
        <h1>About MOVIEMAX</h1>
        <p>The ultimate streaming experience, crafted with passion.</p>
    </div>

    <div class="container">
        <!-- About Section -->
        <div class="about-section">
            <div class="about-content">
                <h2>We Are Movie Max</h2>
                <p>At MOVIEMAX, we believe that entertainment should be accessible, affordable, and enjoyable for everyone. Born from the vision of Orange Software Company, we've created a platform that brings the magic of cinema directly to your fingertips.</p>
                <p>Our mission is simple, to provide an easy, seamless way for movie lovers to access the latest films, timeless classics, and binge-worthy TV series, all in stunning quality, anytime, anywhere.</p>
                <p>Whether you're at home on your couch or on the go with your phone, MOVIEMAX ensures that your favorite content is always just a click away.</p>

                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-number">
                            {{ \App\Models\Movie::count() + \App\Models\Series::count() }}+
                        </div>
                        <div class="stat-label">Movies & Series</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4K</div>
                        <div class="stat-label">Ultra HD Quality</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Customer Support</div>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=600&h=400&fit=crop" alt="Cinema">
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="mission-vision">
            <div class="mission-card">
                <i class="fas fa-bullseye"></i>
                <h3>Our Mission</h3>
                <p>To revolutionize the way people consume entertainment by providing a seamless, high-quality streaming experience that brings the world's best movies and series to every screen, everywhere.</p>
            </div>
            <div class="vision-card">
                <i class="fas fa-eye"></i>
                <h3>Our Vision</h3>
                <p>To become Africa's leading entertainment platform, connecting millions of movie lovers with content they love, while supporting local filmmakers and the creative industry.</p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <p>Have questions, feedback, or just want to say hello? We'd love to hear from you! Our team is always ready to assist you.</p>

                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Visit Us</h4>
                                <p>Dar es Salaam, Tanzania</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="contact-text">
                                <h4>WhatsApp</h4>
                                <a href="https://wa.me/255688349680" target="_blank">+255 688 349 680</a>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email Us</h4>
                                <a href="mailto:info@moviemax.co.tz">info@moviemax.co.tz</a>
                            </div>
                        </div>
                    </div>

                    <a href="https://wa.me/255688349680" target="_blank" class="whatsapp-btn">
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

        <!-- Map Section -->
        <div class="map-section">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1014439.9384660947!2d38.800238799999996!3d-6.792354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4b2d2b6b9b6b%3A0x8b9b9b9b9b9b9b9b!2sDar%20es%20Salaam%2C%20Tanzania!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} {{ setting('site_name', 'MOVIEMAX') }} &mdash; A Product of Orange Software Company. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    </script>
</body>
</html>
