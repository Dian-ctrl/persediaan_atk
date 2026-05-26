<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Portal Pengguna' }} – BPJS Ketenagakerjaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --biru-tua:   #1e3a8a;
            --biru:       #1e40af;
            --biru-muda:  #2563eb;
            --biru-bg:    #eff6ff;
            --biru-border:#dbeafe;
            --hijau:      #00873E;
            --hijau-bg:   #f0fbf5;
            --hijau-border:#c6e8d5;
            --sidebar-w:  248px;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; min-height:100vh; }

        /* TOPBAR */
        #topbar {
            position:fixed; top:0; left:0; right:0; height:58px;
            background:linear-gradient(90deg, var(--biru-tua) 0%, var(--biru) 55%, var(--biru-muda) 100%);
            display:flex; align-items:center; justify-content:space-between;
            padding:0 20px 0 0; z-index:1000;
            box-shadow:0 2px 10px rgba(0,0,0,0.2);
        }
        .brand-wrap {
            display:flex; align-items:center; gap:12px;
            width:var(--sidebar-w); height:58px; padding:0 18px;
            background:rgba(0,0,0,0.15); text-decoration:none; flex-shrink:0;
        }
        .brand-logo {
            width:36px; height:36px; border-radius:8px;
            background:#fff; display:flex; align-items:center; justify-content:center;
            color:var(--biru); font-weight:900; font-size:1.1rem; flex-shrink:0;
        }
        .brand-text span { display:block; color:#fff; font-weight:700; font-size:0.92rem; }
        .brand-text small { color:rgba(255,255,255,0.72); font-size:0.67rem; }
        .topbar-right { display:flex; align-items:center; gap:14px; }
        .topbar-date { color:rgba(255,255,255,0.85); font-size:0.82rem; }
        .topbar-mode {
            background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.28);
            border-radius:20px; padding:4px 12px;
            color:#fff; font-size:0.75rem; font-weight:700;
            display:flex; align-items:center; gap:6px;
        }
        .btn-kembali {
            display:flex; align-items:center; gap:6px;
            background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.25);
            border-radius:20px; padding:5px 12px; color:#fff;
            font-size:0.78rem; font-weight:600; text-decoration:none;
            transition:background 0.18s;
        }
        .btn-kembali:hover { background:rgba(255,255,255,0.25); color:#fff; }

        /* SIDEBAR */
        #sidebar {
            position:fixed; top:58px; left:0; width:var(--sidebar-w);
            height:calc(100vh - 58px); background:#fff;
            border-right:1px solid var(--biru-border);
            overflow-y:auto; z-index:900; display:flex; flex-direction:column;
        }
        .sidebar-user-info {
            padding:16px; background:var(--biru-bg);
            border-bottom:1px solid var(--biru-border);
        }
        .s-mode-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:var(--biru); color:#fff;
            border-radius:20px; padding:4px 12px;
            font-size:0.72rem; font-weight:700; margin-bottom:8px;
        }
        .s-info-text { font-size:0.75rem; color:#64748b; line-height:1.5; }
        .nav-label {
            padding:14px 16px 4px; font-size:0.66rem; text-transform:uppercase;
            letter-spacing:1.4px; color:#93c5fd; font-weight:700;
        }
        .s-link {
            display:flex; align-items:center; gap:11px; padding:11px 16px;
            color:#4a4a4a; text-decoration:none; font-size:0.87rem;
            border-left:3px solid transparent; transition:all 0.18s;
        }
        .s-link i { font-size:1rem; width:20px; text-align:center; color:#93c5fd; transition:color 0.18s; }
        .s-link:hover { background:var(--biru-bg); color:var(--biru); border-left-color:var(--biru-muda); }
        .s-link:hover i { color:var(--biru); }
        .s-link.active { background:linear-gradient(90deg,#dbeafe,var(--biru-bg)); color:var(--biru); font-weight:700; border-left-color:var(--biru); }
        .s-link.active i { color:var(--biru); }

        .sidebar-divider { height:1px; background:var(--biru-border); margin:6px 16px; }

        .sidebar-footer {
            margin-top:auto; padding:12px 16px; text-align:center;
            font-size:0.68rem; color:#93c5fd;
            border-top:1px solid var(--biru-border); background:var(--biru-bg);
        }
        .btn-ganti-mode {
            display:flex; align-items:center; gap:8px; padding:10px 16px;
            color:#64748b; text-decoration:none; font-size:0.84rem;
            border-top:1px solid var(--biru-border); transition:background 0.15s;
        }
        .btn-ganti-mode:hover { background:#f8f9fa; color:var(--biru-tua); }
        .btn-ganti-mode i { color:#93c5fd; font-size:1rem; width:20px; text-align:center; }

        /* MAIN */
        #main { margin-left:var(--sidebar-w); margin-top:58px; min-height:calc(100vh - 58px); }
        .page-header {
            background:linear-gradient(120deg, var(--biru-tua) 0%, var(--biru) 55%, var(--biru-muda) 100%);
            padding:18px 26px; color:#fff;
        }
        .page-header h4 { font-weight:700; font-size:1.2rem; margin:0; }
        .page-header .breadcrumb-wrap { font-size:0.78rem; color:rgba(255,255,255,0.75); margin-top:3px; }
        .content-wrap { padding:22px 26px; }

        /* CARDS */
        .card-biru {
            background:#fff; border-radius:12px;
            border:1px solid var(--biru-border);
            box-shadow:0 2px 10px rgba(30,64,175,0.07);
            overflow:hidden;
        }
        .card-header-biru {
            padding:14px 18px; background:var(--biru-bg);
            border-bottom:1px solid var(--biru-border);
            display:flex; align-items:center; justify-content:space-between;
        }
        .card-header-biru .title { font-weight:700; font-size:0.92rem; color:var(--biru); display:flex; align-items:center; gap:8px; }
        .card-body-biru { padding:18px; }

        /* STAT CARDS */
        .stat-card {
            background:#fff; border-radius:12px; padding:18px;
            border:1px solid var(--biru-border);
            box-shadow:0 2px 10px rgba(30,64,175,0.07);
            display:flex; align-items:center; gap:14px;
            transition:transform 0.2s,box-shadow 0.2s;
        }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 22px rgba(30,64,175,0.13); }
        .stat-icon { width:52px; height:52px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
        .si-blue   { background:#eff6ff; color:var(--biru); }
        .si-green  { background:#f0fdf4; color:var(--hijau); }
        .si-orange { background:#fff7ed; color:#c2410c; }
        .stat-label { font-size:0.76rem; color:#888; font-weight:500; }
        .stat-val   { font-size:1.6rem; font-weight:800; color:#1a1a1a; line-height:1.2; }
        .stat-sub   { font-size:0.72rem; color:var(--biru); font-weight:600; }

        /* TABLE */
        .tbl-biru { font-size:0.86rem; margin:0; }
        .tbl-biru thead th {
            background:var(--biru); color:#fff; font-weight:600;
            font-size:0.78rem; text-transform:uppercase; letter-spacing:0.4px;
            padding:11px 13px; border:none;
        }
        .tbl-biru tbody td { padding:9px 13px; vertical-align:middle; border-color:#eff6ff; color:#333; }
        .tbl-biru tbody tr:hover { background:var(--biru-bg); }

        /* BUTTONS */
        .btn-biru { background:var(--biru); color:#fff; border:none; border-radius:7px; padding:7px 16px; font-size:0.83rem; font-weight:600; transition:background 0.18s; }
        .btn-biru:hover { background:var(--biru-tua); color:#fff; }
        .btn-biru-outline { background:transparent; color:var(--biru); border:1.5px solid var(--biru); border-radius:7px; padding:6px 14px; font-size:0.83rem; font-weight:600; transition:all 0.18s; }
        .btn-biru-outline:hover { background:var(--biru); color:#fff; }

        /* ALERTS */
        .alert-biru  { background:var(--biru-bg); border:1px solid var(--biru-border); color:var(--biru-tua); border-radius:8px; }
        .alert-hijau { background:var(--hijau-bg); border:1px solid var(--hijau-border); color:#00652E; border-radius:8px; }

        /* BADGES */
        .badge-biru   { background:var(--biru); color:#fff; border-radius:20px; padding:3px 10px; font-size:0.72rem; font-weight:600; }
        .badge-hijau  { background:var(--hijau); color:#fff; border-radius:20px; padding:3px 10px; font-size:0.72rem; font-weight:600; }
        .badge-kuning { background:#fff3cd; color:#856404; border-radius:20px; padding:3px 10px; font-size:0.72rem; font-weight:600; }
        .badge-merah  { background:#f8d7da; color:#721c24; border-radius:20px; padding:3px 10px; font-size:0.72rem; font-weight:600; }

        /* FORMS */
        .form-label { font-weight:600; font-size:0.85rem; color:#444; margin-bottom:4px; }
        .form-control:focus, .form-select:focus { border-color:var(--biru); box-shadow:0 0 0 0.2rem rgba(37,99,235,0.15); }

        /* INFO BANNER */
        .info-banner {
            background:linear-gradient(90deg,var(--biru-bg),#f0fdf4);
            border:1px solid var(--biru-border); border-radius:10px;
            padding:12px 16px; font-size:0.83rem; color:var(--biru-tua);
            display:flex; align-items:center; gap:10px; margin-bottom:16px;
        }

        @media(max-width:768px) { #sidebar{display:none;} #main{margin-left:0;} }
    </style>
    @stack('styles')
</head>
<body>

{{-- TOPBAR --}}
<div id="topbar">
    <a href="{{ route('pengguna.dashboard') }}" class="brand-wrap">
        <div class="brand-logo">S</div>
        <div class="brand-text">
            <span>STOK APP</span>
            <small>BPJS Ketenagakerjaan</small>
        </div>
    </a>
    <div class="topbar-right">
        <span class="topbar-date d-none d-md-block">
            <i class="bi bi-calendar3 me-1"></i>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </span>
        <span class="topbar-mode"><i class="bi bi-person-fill"></i> Mode Pengguna</span>
        <a href="{{ route('pilih.role') }}" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Ganti Mode
        </a>
    </div>
</div>

{{-- SIDEBAR --}}
<div id="sidebar">
    <div class="sidebar-user-info">
        <div class="s-mode-badge"><i class="bi bi-person-fill"></i> Mode Pengguna</div>
        <div class="s-info-text">
            Akses tanpa login · Stok otomatis<br>diperbarui saat input transaksi.
        </div>
    </div>

    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('pengguna.dashboard') }}" class="s-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-divider"></div>
    <div class="nav-label">Inventori</div>

    <a href="{{ route('pengguna.barang.index') }}" class="s-link {{ request()->routeIs('pengguna.barang.*') ? 'active' : '' }}">
        <i class="bi bi-archive-fill"></i> Data Barang
    </a>

    <div class="sidebar-divider"></div>
    <div class="nav-label">Transaksi</div>

    <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="s-link {{ request()->routeIs('pengguna.transaksi-masuk.*') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-in-down-right"></i> Barang Masuk
    </a>
    <a href="{{ route('pengguna.transaksi-keluar.index') }}" class="s-link {{ request()->routeIs('pengguna.transaksi-keluar.*') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-up-right"></i> Barang Keluar
    </a>

    <a href="{{ route('pilih.role') }}" class="btn-ganti-mode" style="margin-top:auto">
        <i class="bi bi-arrow-left-right"></i> Ganti Mode Akses
    </a>

    <div class="sidebar-footer">
        © {{ date('Y') }} BPJS Ketenagakerjaan<br>Mode Pengguna – Tanpa Login
    </div>
</div>

{{-- MAIN --}}
<div id="main">
    <div class="page-header">
        <h4><i class="bi bi-{{ $pageIcon ?? 'speedometer2' }} me-2"></i>{{ $pageTitle ?? 'Dashboard' }}</h4>
        <div class="breadcrumb-wrap">
            <i class="bi bi-person-fill me-1"></i> Mode Pengguna
            @isset($pageTitle)
                <span class="mx-1">/</span> {{ $pageTitle }}
            @endisset
        </div>
    </div>

    <div class="content-wrap">
        @if(session('success'))
            <div class="alert alert-hijau d-flex align-items-center gap-2 mb-3">
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
