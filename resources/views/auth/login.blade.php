<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MOVIEMAX</title>
    @include('partials.seo', ['seoTitle' => 'Login - MOVIEMAX', 'seoDescription' => 'Log in to your MOVIEMAX account to save favorites, react and comment.', 'seoNoindex' => true])
    <style>
        :root {
            --bg-deep: #0a0d12;
            --bg-surface: #0f141b;
            --bg-card: #161c26;
            --accent-red: #e50914;
            --accent-red-dark: #b30610;
            --accent-purple: #38bdf8;
            --text-primary: #f2f4f8;
            --text-secondary: #c3c9d1;
            --text-muted: #8a93a0;
            --glass-bg: rgba(10, 13, 18, 0.75);
            --glass-border: rgba(255,255,255,0.11);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at 30% 20%, rgba(229, 9, 20, 0.06) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 80%, rgba(229, 9, 20, 0.06) 0%, transparent 50%);
            animation: bgFloat 15s ease-in-out infinite alternate;
        }
        @keyframes bgFloat {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-5%, 3%) rotate(3deg); }
        }
        .auth-container { max-width: 440px; width: 100%; position: relative; z-index: 1; }
        .auth-card {
            background: rgba(10, 13, 18, 0.6);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-radius: 24px;
            padding: 2.5rem;
            border: 1px solid rgba(229, 9, 20, 0.18);
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.5), 0 0 80px -30px rgba(229, 9, 20, 0.08);
            animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .logo {
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            letter-spacing: 4px;
            background: var(--text-primary);
            background-size: 200% 200%;
            animation: gradientShift 4s ease infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
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
            background: rgba(255,255,255,0.03);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            border-color: var(--accent-red);
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.12);
            background: rgba(255,255,255,0.05);
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
            background: var(--accent-red);
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
            background: rgba(229,28,37,0.12);
            border: 1px solid rgba(229,28,37,0.3);
            color: #ff5c68;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.88rem;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="logo">MOVIEMAX</div>
            <div class="subtitle">Welcome back! Sign in to continue.</div>

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
