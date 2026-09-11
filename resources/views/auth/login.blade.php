<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MovieMax</title>
    @include('partials.seo', ['seoTitle' => 'Login - MovieMax', 'seoDescription' => 'Log in to your MovieMax account to save favorites, react and comment.', 'seoNoindex' => true])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --accent-purple: #ffd700;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-bg: rgba(10, 13, 18, 0.75);
            --glass-border: rgba(255,255,255,0.11);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            background: #05070b;
        }
        .bg {
            position: fixed; inset: 0; z-index: 0;
            background: url('/images/auth-bg.jpg') center/cover no-repeat;
            transform: scale(1.08);
        }
        .bg::after {
            content: '';
            position: absolute; inset: 0;
            background:
                linear-gradient(180deg, rgba(5,7,11,0.55) 0%, rgba(5,7,11,0.35) 40%, rgba(5,7,11,0.88) 100%),
                radial-gradient(ellipse at 50% -20%, rgba(229, 9, 20, 0.22) 0%, transparent 55%);
        }
        .bg::before {
            content: '';
            position: absolute; inset: 0; z-index: 1;
            background:
                radial-gradient(ellipse at 30% 20%, rgba(229, 9, 20, 0.10) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 80%, rgba(255, 215, 0, 0.05) 0%, transparent 50%);
            animation: bgFloat 15s ease-in-out infinite alternate;
        }
        @keyframes bgFloat {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-2%, 2%) rotate(1.5deg); }
        }
        .auth-container { max-width: 460px; width: 100%; position: relative; z-index: 2; }
        .auth-card {
            background: rgba(10, 13, 18, 0.62);
            backdrop-filter: blur(34px) saturate(170%);
            -webkit-backdrop-filter: blur(34px) saturate(170%);
            border-radius: 26px;
            padding: 2.6rem 2.5rem;
            border: 1px solid rgba(255,255,255,0.14);
            box-shadow: 0 40px 80px -30px rgba(0,0,0,0.85), 0 0 120px -40px rgba(229, 9, 20, 0.18), inset 0 1px 0 rgba(255,255,255,0.06);
            animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .auth-card::before {
            content: '';
            position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            width: 120px; height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-red), var(--accent-purple), transparent);
            border-radius: 0 0 4px 4px;
        }
        .auth-card::after {
            content: '';
            position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 120px; height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent-red), transparent);
            border-radius: 4px 4px 0 0;
            opacity: 0.6;
        }
        .logo-wrap {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            letter-spacing: 4px;
            background: linear-gradient(120deg, #ffffff 30%, #f5c518 60%, #e50914 90%);
            background-size: 200% 200%;
            animation: gradientShift 6s ease infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
        }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .logo-badge {
            display: inline-block;
            margin-bottom: 0.75rem;
            padding: 0.28rem 0.9rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.06);
            color: var(--text-secondary);
            font-size: 0.68rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .form-group { margin-bottom: 1.4rem; }
        .form-group label {
            display: block; margin-bottom: 0.5rem;
            color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;
        }
        .input-group {
            display: flex; align-items: center;
            background: rgba(255,255,255,0.045);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.10);
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            background: rgba(255,255,255,0.06);
        }
        .input-group i {
            padding: 0 1.1rem;
            color: var(--accent-red);
            font-size: 0.9rem;
        }
        .input-group input {
            flex: 1; background: transparent; border: none;
            padding: 1rem 1rem 1rem 0;
            color: var(--text-primary); font-size: 0.95rem; outline: none;
        }
        .input-group input::placeholder { color: var(--text-muted); }
        .checkbox {
            display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem;
        }
        .checkbox input {
            width: 18px; height: 18px; cursor: pointer;
            accent-color: var(--accent-red);
        }
        .checkbox label { margin: 0; color: var(--text-secondary); cursor: pointer; font-size: 0.88rem; }
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-red), var(--accent-red-dark));
            color: white; border: none;
            padding: 1rem; border-radius: 14px;
            font-size: 1rem; font-weight: 700;
            cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 20px rgba(229, 9, 20, 0.3);
            position: relative; overflow: hidden;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.45);
        }
        .btn-login::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-login:hover::before { left: 100%; }
        @media (hover: none) { .btn-login:hover { transform: none; } }
        .divider {
            display: flex; align-items: center; gap: 1rem;
            margin: 1.5rem 0; color: var(--text-muted); font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.10);
        }
        .btn-google {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 0.75rem;
            background: #ffffff;
            color: #1f2937; border: 1px solid rgba(255,255,255,0.12);
            padding: 0.95rem; border-radius: 14px;
            font-size: 0.95rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 18px rgba(0,0,0,0.25);
        }
        .btn-google:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(66, 133, 244, 0.25);
        }
        .btn-google:active { transform: translateY(-1px); }
        .btn-google .g-logo {
            width: 20px; height: 20px;
        }
        .register-link {
            text-align: center; margin-top: 1.5rem;
            color: var(--text-secondary); font-size: 0.9rem;
        }
        .register-link a {
            color: var(--accent-red); text-decoration: none; font-weight: 600;
            transition: 0.2s;
        }
        .register-link a:hover { text-decoration: underline; }
        .error-message {
            background: rgba(229,28,37,0.14);
            border: 1px solid rgba(229,28,37,0.35);
            color: #fbbf24;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.88rem;
            backdrop-filter: blur(10px);
        }
        .fg-link {
            text-align: right; margin: -0.75rem 0 1.25rem;
        }
        .fg-link a {
            color: var(--text-muted); text-decoration: none; font-size: 0.8rem;
            transition: 0.2s;
        }
        .fg-link a:hover { color: var(--text-secondary); text-decoration: underline; }
        .hint {
            text-align: center;
            margin-top: 1.25rem;
            color: var(--text-muted);
            font-size: 0.72rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 520px) {
            body { padding: 1rem; }
            .auth-card { padding: 2rem 1.4rem; border-radius: 20px; }
        }
    </style>
</head>
<body>
<div class="bg"></div>
@include('partials.loader')    <div class="auth-container">
        <div class="auth-card">
            <div class="logo-wrap">
                <div class="logo-badge">Movies · Series · Trailers</div>
                <div class="logo">MOVIEMAX</div>
            </div>
            <div class="subtitle">Welcome back! Sign in to continue your movie night.</div>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <input type="hidden" name="redirect" id="redirectInput" value="">
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" required placeholder="Enter your password">
                    </div>
                </div>
                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="divider">or continue with</div>
            <a href="{{ route('google.redirect') }}" class="btn-google">
                <svg class="g-logo" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Continue with Google
            </a>

            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
    <script>
        const params = new URLSearchParams(window.location.search);
        const redirect = params.get('redirect');
        if (redirect && redirect.startsWith('/') && !redirect.startsWith('//')) {
            document.getElementById('redirectInput').value = redirect;
        }
    </script>
</body>
</html>
