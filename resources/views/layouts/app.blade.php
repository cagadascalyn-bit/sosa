<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RecipeBook') — Sosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   #ff6b35;
            --primary-d: #e85520;
            --sidebar-bg:#1c1c2e;
            --sidebar-w: 260px;
            --topbar-h:  64px;
            --bg:        #f7f3ef;
            --card-bg:   #ffffff;
            --text:      #2d2d2d;
            --muted:     #888;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Nunito', sans-serif; background: var(--bg); color: var(--text); margin: 0; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 1040;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand .logo-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #ff9a3c);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; flex-shrink: 0;
        }
        .sidebar-brand .brand-text { line-height: 1.2; }
        .sidebar-brand .brand-text strong { color: #fff; font-size: 1rem; font-weight: 800; display: block; }
        .sidebar-brand .brand-text span  { color: rgba(255,255,255,.4); font-size: .72rem; }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; }
        .nav-section-label {
            color: rgba(255,255,255,.25); font-size: .65rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            padding: 14px 10px 6px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,.55); font-weight: 600; font-size: .88rem;
            text-decoration: none; margin-bottom: 2px;
            transition: all .2s;
        }
        .sidebar-link i { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(255,107,53,.25), rgba(255,107,53,.08));
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }
        .sidebar-link.active i { color: var(--primary); }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-footer form button {
            width: 100%; padding: 10px; border-radius: 10px;
            background: rgba(255,107,53,.12); border: 1px solid rgba(255,107,53,.25);
            color: var(--primary); font-weight: 700; font-size: .85rem;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .sidebar-footer form button:hover { background: rgba(255,107,53,.25); }

        /* ── Overlay (mobile) ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin .3s ease;
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid #ede8e3;
            display: flex; align-items: center;
            padding: 0 24px; gap: 16px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar .menu-toggle {
            display: none; background: none; border: none;
            font-size: 1.3rem; color: var(--text); cursor: pointer; padding: 4px;
        }
        .topbar .page-title { font-weight: 800; font-size: 1rem; flex: 1; }
        .topbar .user-pill {
            display: flex; align-items: center; gap: 10px;
            background: var(--bg); border-radius: 50px;
            padding: 6px 14px 6px 6px;
        }
        .topbar .user-pill .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover;
        }
        .topbar .user-pill .avatar-initials {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #ff9a3c);
            color: #fff; font-weight: 800; font-size: .8rem;
            display: flex; align-items: center; justify-content: center;
        }
        .topbar .user-pill span { font-weight: 700; font-size: .85rem; }

        /* ── Page content ── */
        .page-content { padding: 28px 28px; flex: 1; }

        /* ── Cards ── */
        .card {
            border: none; border-radius: 16px;
            background: var(--card-bg);
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .card-header-bar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .card-header-bar h6 { font-weight: 800; font-size: .95rem; margin: 0; }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 16px; padding: 22px 20px; color: #fff;
            position: relative; overflow: hidden;
        }
        .stat-card .stat-icon {
            position: absolute; right: -10px; bottom: -10px;
            font-size: 5rem; opacity: .12;
        }
        .stat-card .stat-label { font-size: .78rem; font-weight: 600; opacity: .8; margin-bottom: 4px; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-sub   { font-size: .75rem; opacity: .7; margin-top: 6px; }

        /* ── Buttons ── */
        .btn-primary   { background: var(--primary); border-color: var(--primary); font-weight: 700; }
        .btn-primary:hover { background: var(--primary-d); border-color: var(--primary-d); }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); font-weight: 600; }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* ── Tables ── */
        .table thead th { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 2px solid #f0ebe5; }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover { background: #fdf8f5; }
        .table td { vertical-align: middle; font-size: .88rem; border-color: #f5f0eb; }

        /* ── Badges ── */
        .badge-category {
            padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700;
            background: #fff3ee; color: var(--primary);
        }

        /* ── Modals ── */
        .modal-content { border: none; border-radius: 18px; overflow: hidden; }
        .modal-header { background: var(--sidebar-bg); color: #fff; border: none; padding: 18px 24px; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-title { font-weight: 800; font-size: .95rem; }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid #f0ebe5; padding: 16px 24px; }
        .form-label { font-weight: 700; font-size: .82rem; color: #555; margin-bottom: 5px; }
        .form-control, .form-select {
            border-radius: 10px; border: 1.5px solid #e8e2dc;
            font-size: .88rem; padding: 9px 13px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,.15);
        }
        .input-group-text { border-radius: 10px 0 0 10px; background: #f7f3ef; border: 1.5px solid #e8e2dc; color: var(--primary); }
        .input-group .form-control { border-radius: 0 10px 10px 0; }

        /* ── Toasts ── */
        .toast-container { z-index: 9999; }
        .toast {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0,0,0,.15) !important;
            min-width: 280px;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .topbar .menu-toggle { display: block; }
            .page-content { padding: 20px 16px; }
        }
        @media (max-width: 575px) {
            .topbar { padding: 0 14px; }
            .topbar .user-pill span { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- Sidebar Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon">🍳</div>
        <div class="brand-text">
            <strong>RecipeBook</strong>
            <span>Food Recipe Manager</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('recipes.index') }}" class="sidebar-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}">
            <i class="bi bi-journal-richtext"></i> My Recipes
        </a>

        <div class="nav-section-label">Management</div>
        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Users
        </a>
        <a href="{{ route('profile.show') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> My Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <header class="topbar">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="user-pill">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar">
            @else
                <div class="avatar-initials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <span>{{ auth()->user()->name }}</span>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

{{-- Toasts --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('toast_success'))
    <div class="toast align-items-center text-bg-success border-0" role="alert" id="toastSuccess">
        <div class="d-flex">
            <div class="toast-body fw-semibold">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('toast_success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('toast_error'))
    <div class="toast align-items-center text-bg-danger border-0" role="alert" id="toastError">
        <div class="d-flex">
            <div class="toast-body fw-semibold">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('toast_error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 4500 }).show());

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
</script>
@yield('scripts')
</body>
</html>
