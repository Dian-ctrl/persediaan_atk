<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Stok Opname – BPJS Ketenagakerjaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --hijau-tua:  #00652E;
            --hijau:      #00873E;
            --hijau-muda: #00a84f;
            --biru-tua:   #1e3a8a;
            --biru:       #1e40af;
            --biru-muda:  #2563eb;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Background dekoratif */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse at 15% 20%, rgba(0,135,62,0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 80%, rgba(30,64,175,0.12) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Grid pattern */
        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(0,135,62,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,135,62,0.04) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative; z-index: 1;
            animation: fadeDown 0.5s ease both;
        }
        .logo-wrap {
            width: 72px; height: 72px; border-radius: 18px;
            background: linear-gradient(135deg, var(--hijau-tua), var(--hijau-muda));
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 900; color: #fff;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(0,135,62,0.3);
        }
        .header h1 {
            font-size: 1.7rem; font-weight: 800; color: #1a1a1a;
            margin-bottom: 6px;
        }
        .header p {
            color: #777; font-size: 0.92rem;
        }
        .header .divider {
            width: 48px; height: 3px;
            background: linear-gradient(90deg, var(--hijau), var(--biru));
            border-radius: 3px;
            margin: 12px auto 0;
        }

        /* ── PILIHAN ROLE ── */
        .choices {
            display: flex;
            gap: 24px;
            position: relative; z-index: 1;
            animation: fadeUp 0.5s ease 0.1s both;
        }

        .role-card {
            width: 240px;
            border-radius: 20px;
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.14);
        }

        /* ── ADMIN CARD ── */
        .card-admin {
            background: #fff;
            border-color: rgba(0,135,62,0.2);
        }
        .card-admin:hover { border-color: var(--hijau); }

        .card-admin .card-top {
            padding: 28px 24px 20px;
            background: linear-gradient(135deg, var(--hijau-tua) 0%, var(--hijau) 60%, var(--hijau-muda) 100%);
            text-align: center;
            position: relative; overflow: hidden;
        }
        .card-admin .card-top::before {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
            top: -40px; right: -30px;
        }
        .card-admin .icon-wrap {
            width: 64px; height: 64px; border-radius: 16px;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.8rem; color: #fff;
        }
        .card-admin .card-top h3 {
            color: #fff; font-weight: 800; font-size: 1.15rem; margin: 0;
        }
        .card-admin .card-top small {
            color: rgba(255,255,255,0.75); font-size: 0.75rem;
        }
        .card-admin .card-body {
            padding: 18px 20px 22px;
        }
        .feature-item {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8rem; color: #555; padding: 5px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .feature-item:last-child { border: none; }
        .feature-item i { font-size: 0.85rem; width: 16px; }
        .fi-green { color: var(--hijau); }
        .fi-blue  { color: var(--biru); }

        .btn-masuk-admin {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: linear-gradient(90deg, var(--hijau-tua), var(--hijau-muda));
            color: #fff; font-weight: 700; font-size: 0.88rem;
            padding: 11px; border-radius: 10px;
            margin-top: 14px; transition: opacity 0.2s;
        }
        .btn-masuk-admin:hover { opacity: 0.88; }

        /* ── PENGGUNA CARD ── */
        .card-pengguna {
            background: #fff;
            border-color: rgba(30,64,175,0.2);
        }
        .card-pengguna:hover { border-color: var(--biru); }

        .card-pengguna .card-top {
            padding: 28px 24px 20px;
            background: linear-gradient(135deg, var(--biru-tua) 0%, var(--biru) 60%, var(--biru-muda) 100%);
            text-align: center;
            position: relative; overflow: hidden;
        }
        .card-pengguna .card-top::before {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
            top: -40px; right: -30px;
        }
        .card-pengguna .icon-wrap {
            width: 64px; height: 64px; border-radius: 16px;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.8rem; color: #fff;
        }
        .card-pengguna .card-top h3 {
            color: #fff; font-weight: 800; font-size: 1.15rem; margin: 0;
        }
        .card-pengguna .card-top small {
            color: rgba(255,255,255,0.75); font-size: 0.75rem;
        }
        .card-pengguna .card-body {
            padding: 18px 20px 22px;
        }

        .btn-masuk-pengguna {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: linear-gradient(90deg, var(--biru-tua), var(--biru-muda));
            color: #fff; font-weight: 700; font-size: 0.88rem;
            padding: 11px; border-radius: 10px;
            margin-top: 14px; transition: opacity 0.2s;
        }
        .btn-masuk-pengguna:hover { opacity: 0.88; }

        /* ── BADGE ── */
        .badge-login { background: #fff3e0; color: #c2410c; border-radius: 20px; padding: 3px 8px; font-size: 0.65rem; font-weight: 700; }
        .badge-nologin { background: #eff6ff; color: var(--biru); border-radius: 20px; padding: 3px 8px; font-size: 0.65rem; font-weight: 700; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 0.75rem;
            color: #aaa;
            position: relative; z-index: 1;
            animation: fadeUp 0.5s ease 0.2s both;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── HOVER GLOW ── */
        .card-admin:hover  .card-top { box-shadow: 0 0 0 4px rgba(0,135,62,0.15); }
        .card-pengguna:hover .card-top { box-shadow: 0 0 0 4px rgba(30,64,175,0.15); }

        @media (max-width: 540px) {
            .choices { flex-direction: column; gap: 16px; }
            .role-card { width: 100%; max-width: 320px; }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="logo-wrap">S</div>
        <h1>Sistem Stok Opname</h1>
        <p>BPJS Ketenagakerjaan – Pilih mode akses Anda</p>
        <div class="divider"></div>
    </div>

    <!-- PILIHAN ROLE -->
    <div class="choices">

        <!-- ADMIN -->
        <a href="{{ route('login.admin') }}" class="role-card card-admin">
            <div class="card-top">
                <div class="icon-wrap">
                    <i class="bi bi-shield-fill-check"></i>
                </div>
                <h3>Administrator</h3>
                <small>Akses penuh sistem</small>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:.75rem;font-weight:700;color:#444">Akses tersedia:</span>
                    <span class="badge-login"><i class="bi bi-lock-fill me-1"></i>Perlu login</span>
                </div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Dashboard & Statistik</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Master Data (Kategori, Satuan)</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Kelola Data Barang</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Barang Masuk & Keluar</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Stok Opname & Laporan</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-green"></i> Manajemen Pengguna</div>
                <div class="btn-masuk-admin">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk sebagai Admin
                </div>
            </div>
        </a>

        <!-- PENGGUNA -->
        <a href="{{ route('pengguna.dashboard') }}" class="role-card card-pengguna">
            <div class="card-top">
                <div class="icon-wrap">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h3>Pengguna</h3>
                <small>Input transaksi harian</small>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:.75rem;font-weight:700;color:#444">Akses tersedia:</span>
                    <span class="badge-nologin"><i class="bi bi-unlock-fill me-1"></i>Tanpa login</span>
                </div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-blue"></i> Lihat Data Barang</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-blue"></i> Input Barang Masuk</div>
                <div class="feature-item"><i class="bi bi-check-circle-fill fi-blue"></i> Input Barang Keluar</div>
                <div class="feature-item"><i class="bi bi-x-circle-fill" style="color:#ddd"></i> <span style="color:#bbb">Stok Opname</span></div>
                <div class="feature-item"><i class="bi bi-x-circle-fill" style="color:#ddd"></i> <span style="color:#bbb">Master Data</span></div>
                <div class="feature-item"><i class="bi bi-x-circle-fill" style="color:#ddd"></i> <span style="color:#bbb">Manajemen Pengguna</span></div>
                <div class="btn-masuk-pengguna">
                    <i class="bi bi-arrow-right-circle-fill"></i> Masuk sebagai Pengguna
                </div>
            </div>
        </a>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        © {{ date('Y') }} BPJS Ketenagakerjaan &nbsp;·&nbsp; Sistem Manajemen Stok Opname
    </div>

</body>
</html>
