<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — RecipeBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff6b35;
            --primary-d: #e85520;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Nunito', sans-serif; margin: 0; min-height: 100vh; display: flex; }

        /* Left panel */
        .auth-panel-left {
            width: 45%;
            background: linear-gradient(160deg, #1c1c2e 0%, #2d1b0e 60%, #3d2200 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px; position: relative; overflow: hidden;
        }
        .auth-panel-left::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b35' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-panel-left .floating-emojis {
            position: absolute; inset: 0; pointer-events: none;
        }
        .auth-panel-left .floating-emojis span {
            position: absolute; font-size: 2rem; opacity: .15; animation: float 6s ease-in-out infinite;
        }
        .auth-panel-left .floating-emojis span:nth-child(1) { top: 10%; left: 15%; animation-delay: 0s; }
        .auth-panel-left .floating-emojis span:nth-child(2) { top: 25%; right: 20%; animation-delay: 1s; }
        .auth-panel-left .floating-emojis span:nth-child(3) { top: 55%; left: 10%; animation-delay: 2s; }
        .auth-panel-left .floating-emojis span:nth-child(4) { bottom: 20%; right: 15%; animation-delay: .5s; }
        .auth-panel-left .floating-emojis span:nth-child(5) { bottom: 35%; left: 30%; animation-delay: 1.5s; }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-14px); }
        }
        .auth-panel-left .brand-logo {
            width: 72px; height: 72px; border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), #ff9a3c);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 20px; position: relative;
            box-shadow: 0 12px 40px rgba(255,107,53,.4);
        }
        .auth-panel-left h2 { color: #fff; font-weight: 800; font-size: 1.8rem; margin-bottom: 10px; text-align: center; }
        .auth-panel-left p  { color: rgba(255,255,255,.45); font-size: .9rem; text-align: center; max-width: 260px; line-height: 1.6; }
        .auth-panel-left .features { margin-top: 32px; width: 100%; max-width: 280px; }
        .auth-panel-left .feature-item {
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,.6); font-size: .83rem; font-weight: 600;
            margin-bottom: 12px;
        }
        .auth-panel-left .feature-item .fi-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(255,107,53,.2); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; flex-shrink: 0;
        }

        /* Right panel */
        .auth-panel-right {
            flex: 1; display: flex; align-items: center; justify-content: center;
            background: #f7f3ef; padding: 40px 20px;
        }
        .auth-box {
            width: 100%; max-width: 420px;
            background: #fff; border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 8px 40px rgba(0,0,0,.08);
        }
        .auth-box h4 { font-weight: 800; font-size: 1.4rem; margin-bottom: 4px; }
        .auth-box .sub { color: #888; font-size: .88rem; margin-bottom: 28px; }

        .form-label { font-weight: 700; font-size: .8rem; color: #555; margin-bottom: 5px; }
        .form-control {
            border-radius: 10px; border: 1.5px solid #e8e2dc;
            font-size: .88rem; padding: 10px 13px; font-family: 'Nunito', sans-serif;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,.15);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px; background: #f7f3ef;
            border: 1.5px solid #e8e2dc; border-right: none; color: var(--primary);
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; border-left: none; }
        .input-group .form-control:focus { border-left: none; }

        .btn-auth {
            width: 100%; padding: 12px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #ff9a3c);
            border: none; color: #fff; font-weight: 800; font-size: .95rem;
            cursor: pointer; transition: all .2s; font-family: 'Nunito', sans-serif;
            box-shadow: 0 4px 20px rgba(255,107,53,.35);
        }
        .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(255,107,53,.45); }
        .btn-auth:active { transform: translateY(0); }

        .auth-link { color: var(--primary); font-weight: 700; text-decoration: none; }
        .auth-link:hover { color: var(--primary-d); }

        .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #ede8e3; }
        .divider span { color: #bbb; font-size: .78rem; font-weight: 600; }

        .toast-container { z-index: 9999; }
        .toast { border-radius: 12px !important; min-width: 280px; font-family: 'Nunito', sans-serif; font-weight: 600; }

        @media (max-width: 768px) {
            .auth-panel-left { display: none; }
            .auth-panel-right { background: linear-gradient(160deg, #1c1c2e, #3d2200); }
            .auth-box { box-shadow: 0 8px 40px rgba(0,0,0,.2); }
        }
    </style>
</head>
<body>

<div class="auth-panel-left">
    <div class="floating-emojis">
        <span>🍳</span><span>🥘</span><span>🍜</span><span>🥗</span><span>🍰</span>
    </div>
    <div class="brand-logo">🍳</div>
    <h2>RecipeBook</h2>
    <p>Your personal food recipe manager. Save, organize, and share your favorite dishes.</p>
    <div class="features">
        <div class="feature-item">
            <div class="fi-icon"><i class="bi bi-journal-richtext"></i></div>
            Save unlimited recipes
        </div>
        <div class="feature-item">
            <div class="fi-icon"><i class="bi bi-tags"></i></div>
            Organize by category
        </div>
        <div class="feature-item">
            <div class="fi-icon"><i class="bi bi-bar-chart-line"></i></div>
            Track your cooking stats
        </div>
        <div class="feature-item">
            <div class="fi-icon"><i class="bi bi-people"></i></div>
            Manage your team
        </div>
    </div>
</div>

<div class="auth-panel-right">
    <div class="auth-box">
        @yield('content')
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('toast_success'))
    <div class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('toast_success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 4500 }).show());
</script>
</body>
</html>
