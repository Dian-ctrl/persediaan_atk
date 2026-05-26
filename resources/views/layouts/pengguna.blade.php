<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Portal Pengguna' }} – Medini Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --green-dark:   #1a5c38;
            --green:        #2d7a4f;
            --green-mid:    #3a9c65;
            --green-light:  #e8f5ee;
            --green-border: #b8ddc9;
            --sidebar-w:    220px;
            --topbar-h:     60px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f7f6; min-height: 100vh; color: #1a1a1a; }

        /* TOPBAR */
        #topbar {
            position: fixed; top: 0; left: 0; right: 0; height: var(--topbar-h);
            background: #fff; border-bottom: 1px solid #e5ebe8;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px 0 0; z-index: 1000;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .brand-wrap {
            display: flex; align-items: center; gap: 10px;
            width: var(--sidebar-w); height: var(--topbar-h); padding: 0 20px;
            text-decoration: none; flex-shrink: 0; border-right: 1px solid #e5ebe8;
        }
        .brand-logo {
            width: 34px; height: 34px; border-radius: 8px;
            background: var(--green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .brand-logo svg { width: 18px; height: 18px; fill: #fff; }
        .brand-text span { display: block; color: #1a1a1a; font-weight: 700; font-size: 0.88rem; }
        .brand-text small { color: #888; font-size: 0.64rem; }

        .topbar-center { flex: 1; max-width: 440px; margin: 0 24px; }
        .search-wrap { display: flex; align-items: center; gap: 8px; background: #f5f7f6; border: 1px solid #e0e7e3; border-radius: 8px; padding: 7px 14px; font-size: 0.84rem; color: #888; }
        .search-wrap i { font-size: 0.9rem; }

        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-icon-btn { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e5ebe8; display: flex; align-items: center; justify-content: center; color: #555; font-size: 1rem; cursor: pointer; background: #fff; text-decoration: none; transition: background 0.15s; }
        .topbar-icon-btn:hover { background: var(--green-light); color: var(--green); }
        .u-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--green); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.82rem; font-weight: 700; flex-shrink: 0; }

        /* SIDEBAR */
        #sidebar {
            position: fixed; top: var(--topbar-h); left: 0; width: var(--sidebar-w);
            height: calc(100vh - var(--topbar-h)); background: #fff;
            border-right: 1px solid #e5ebe8; overflow-y: auto; z-index: 900; display: flex; flex-direction: column;
        }
        #sidebar::-webkit-scrollbar { width: 3px; }
        #sidebar::-webkit-scrollbar-thumb { background: var(--green-border); border-radius: 4px; }

        .nav-section { padding: 20px 14px 6px; font-size: 0.63rem; text-transform: uppercase; letter-spacing: 1.2px; color: #aab8b2; font-weight: 700; }
        .s-link { display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: #4a5a52; text-decoration: none; font-size: 0.84rem; border-radius: 8px; margin: 1px 8px; transition: all 0.15s; }
        .s-link i { font-size: 1rem; width: 18px; text-align: center; color: #9ab3a6; }
        .s-link:hover { background: var(--green-light); color: var(--green); }
        .s-link:hover i { color: var(--green); }
        .s-link.active { background: var(--green-light); color: var(--green); font-weight: 600; }
        .s-link.active i { color: var(--green); }

        .sidebar-footer { margin-top: auto; padding: 14px 16px; border-top: 1px solid #e5ebe8; }
        .sidebar-logout { display: flex; align-items: center; gap: 10px; padding: 8px 8px; color: #888; background: none; border: none; cursor: pointer; font-size: 0.84rem; width: 100%; border-radius: 8px; transition: all 0.15s; }
        .sidebar-logout:hover { background: #fff0f0; color: #dc3545; }

        /* MAIN */
        #main { margin-left: var(--sidebar-w); margin-top: var(--topbar-h); min-height: calc(100vh - var(--topbar-h)); }
        .content-wrap { padding: 24px 28px; }

        /* CARDS */
        .card-md { background: #fff; border-radius: 12px; border: 1px solid #e5ebe8; box-shadow: 0 1px 6px rgba(0,0,0,0.04); overflow: hidden; }
        .card-md-header { padding: 14px 18px; border-bottom: 1px solid #edf2ef; display: flex; align-items: center; justify-content: space-between; }
        .card-md-title { font-weight: 700; font-size: 0.9rem; color: #1a1a1a; display: flex; align-items: center; gap: 8px; }
        .card-md-title i { color: var(--green); }
        .card-md-body { padding: 18px; }

        /* HERO */
        .hero-banner { background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 60%, var(--green-mid) 100%); border-radius: 14px; padding: 28px 32px; color: #fff; position: relative; overflow: hidden; }
        .hero-banner h2 { font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; }
        .hero-banner p { font-size: 0.85rem; color: rgba(255,255,255,0.82); margin-bottom: 18px; }
        .hero-btn { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.35); color: #fff; border-radius: 8px; padding: 8px 18px; font-size: 0.84rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: background 0.18s; }
        .hero-btn:hover { background: rgba(255,255,255,0.28); color: #fff; }

        /* STAT */
        .stat-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #e5ebe8; box-shadow: 0 1px 6px rgba(0,0,0,0.04); display: flex; align-items: center; gap: 14px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .si-green  { background: #e8f5ee; color: var(--green); }
        .si-teal   { background: #e0f4f1; color: #00897b; }
        .si-orange { background: #fff3e0; color: #e65100; }
        .stat-label { font-size: 0.74rem; color: #888; font-weight: 500; }
        .stat-val   { font-size: 1.55rem; font-weight: 800; color: #1a1a1a; line-height: 1.2; }
        .stat-sub   { font-size: 0.71rem; color: var(--green); font-weight: 600; }

        /* TABLE */
        .tbl-md { font-size: 0.84rem; margin: 0; }
        .tbl-md thead th { background: #f5f7f6; color: #555; font-weight: 600; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 11px 14px; border: none; border-bottom: 1px solid #e5ebe8; }
        .tbl-md tbody td { padding: 10px 14px; vertical-align: middle; border-color: #edf2ef; color: #333; }
        .tbl-md tbody tr:hover { background: var(--green-light); }

        /* BUTTONS */
        .btn-green { background: var(--green); color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 0.83rem; font-weight: 600; transition: background 0.18s; }
        .btn-green:hover { background: var(--green-dark); color: #fff; }
        .btn-green-outline { background: transparent; color: var(--green); border: 1.5px solid var(--green); border-radius: 8px; padding: 7px 16px; font-size: 0.83rem; font-weight: 600; transition: all 0.18s; }
        .btn-green-outline:hover { background: var(--green); color: #fff; }

        /* FORMS */
        .form-label { font-weight: 600; font-size: 0.84rem; color: #444; margin-bottom: 5px; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 0.2rem rgba(45,122,79,0.15); }
        .form-control, .form-select { border-radius: 8px; }

        /* ALERTS */
        .alert-green { background: var(--green-light); border: 1px solid var(--green-border); color: var(--green-dark); border-radius: 8px; }

        /* BADGES */
        .badge-green  { background: #e8f5ee; color: var(--green); border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-yellow { background: #fff8e1; color: #795500; border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-red    { background: #fdecea; color: #c62828; border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-kritis { background: #fff3e0; color: #e65100; border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-pending  { background: #fff8e1; color: #795500; border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-approved { background: #e8f5ee; color: var(--green); border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }
        .badge-rejected { background: #fdecea; color: #c62828; border-radius: 20px; padding: 3px 10px; font-size: 0.71rem; font-weight: 700; }

        /* STATUS CARD */
        .status-card { background: linear-gradient(135deg, var(--green) 0%, var(--green-mid) 100%); border-radius: 14px; padding: 24px 22px; color: #fff; height: 100%; }
        .status-card h5 { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.2px; color: rgba(255,255,255,0.7); margin-bottom: 10px; }
        .status-card h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 6px; }
        .status-card p { font-size: 0.8rem; color: rgba(255,255,255,0.8); }

        @media (max-width: 768px) { #sidebar { display: none; } #main { margin-left: 0; } }
    </style>
    @stack('styles')
</head>
<body>

{{-- TOPBAR --}}
<div id="topbar">
    <a href="{{ route('pengguna.dashboard') }}" class="brand-wrap">
        <div class="brand-logo">
            <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div class="brand-text">
            <span>Medini Digital</span>
            <small>Clinical Curator</small>
        </div>
    </a>
    <div class="topbar-center">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <span>Cari permintaan...</span>
        </div>
    </div>
    <div class="topbar-right">
        <a href="#" class="topbar-icon-btn"><i class="bi bi-bell"></i></a>
        <a href="#" class="topbar-icon-btn"><i class="bi bi-gear"></i></a>
        <div class="u-avatar">U</div>
    </div>
</div>

{{-- SIDEBAR --}}
<div id="sidebar">
    <div class="nav-section">Menu</div>
    <a href="{{ route('pengguna.dashboard') }}" class="s-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i> Dashboard ATK
    </a>
    <a href="{{ route('pengguna.barang.index') }}" class="s-link {{ request()->routeIs('pengguna.barang.*') ? 'active' : '' }}">
        <i class="bi bi-boxes"></i> Stok Barang
    </a>
    <a href="{{ route('pengguna.transaksi-keluar.create') }}" class="s-link {{ request()->routeIs('pengguna.transaksi-keluar.*') ? 'active' : '' }}">
        <i class="bi bi-list-check"></i> Permintaan
    </a>
    <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="s-link {{ request()->routeIs('pengguna.transaksi-masuk.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-fill"></i> Laporan Stok
    </a>

    <div class="nav-section">Bantuan</div>
    <a href="#" class="s-link">
        <i class="bi bi-question-circle-fill"></i> Help Center
    </a>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div id="main">
    <div class="content-wrap">
        @if(session('success'))
            <div class="alert alert-green d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
