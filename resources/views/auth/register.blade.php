<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MovieMax</title>
    @include('partials.seo', ['seoTitle' => 'Create Account - MovieMax', 'seoDescription' => 'Create a free MovieMax account to save favorites, react and comment.', 'seoNoindex' => true])
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
        .form-group { margin-bottom: 1.3rem; }
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
            padding: 0.9rem 0.9rem 0.9rem 0;
            color: var(--text-primary); font-size: 0.95rem; outline: none;
        }
        .input-group input::placeholder { color: var(--text-muted); }
        .btn-register {
            width: 100%;
            background: var(--accent-red);
            color: white; border: none;
            padding: 1rem; border-radius: 14px;
            font-size: 1rem; font-weight: 700;
            cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 20px rgba(229, 9, 20, 0.3);
            margin-top: 0.5rem;
            position: relative; overflow: hidden;
        }
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(229, 9, 20, 0.45);
        }
        .btn-register::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }
        .btn-register:hover::before { left: 100%; }
        .login-link {
            text-align: center; margin-top: 1.5rem;
            color: var(--text-secondary); font-size: 0.9rem;
        }
        .login-link a {
            color: var(--accent-red); text-decoration: none; font-weight: 600;
            transition: 0.2s;
        }
        .login-link a:hover { text-decoration: underline; }
        .error-message {
            background: rgba(229,28,37,0.12);
            border: 1px solid rgba(229,28,37,0.3);
            color: #fbbf24;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.88rem;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
@include('partials.loader')    <div class="auth-container">
        <div class="auth-card">
            <div class="logo">MOVIEMAX</div>
            <div class="subtitle">Create your account to get started</div>

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" required placeholder="Min. 8 characters">
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" required placeholder="Repeat password">
                    </div>
                </div>
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
