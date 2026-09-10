<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>503 - Service Unavailable | MOVIEMAX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600&display=swap">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #0a0d12; color: #fff; font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        .wrap { text-align: center; padding: 2rem; }
        .code {
            font-family: 'Bebas Neue', sans-serif; font-size: 8rem; line-height: 1;
            background: var(--text-primary);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            letter-spacing: 4px; margin-bottom: 1rem;
        }
        .title { font-size: 1.15rem; font-weight: 600; margin-bottom: 0.5rem; }
        .desc { color: #9ca3af; font-size: 0.9rem; margin-bottom: 2rem; max-width: 420px; }
        a.btn {
            display: inline-block; padding: 0.75rem 1.8rem; border-radius: 12px;
            background: #3b82f6; color: #fff;
            text-decoration: none; font-weight: 600; font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35); transition: all 0.3s ease;
        }
        a.btn:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(59, 130, 246, 0.45); }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="code">503</div>
        <div class="title">Maintenance in Progress</div>
        <p class="desc">We're performing scheduled maintenance. Please check back shortly.</p>
        <a class="btn" href="/">Back to Home</a>
    </div>
</body>
</html>