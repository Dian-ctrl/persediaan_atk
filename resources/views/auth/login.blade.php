<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Medini Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #1a5c38 0%, #2d7a4f 50%, #3a9c65 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .login-card {
            background: #fff; border-radius: 16px; padding: 40px 36px;
            width: 100%; max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }
        .logo-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 28px; }
        .logo-box { width: 44px; height: 44px; border-radius: 10px; background: #2d7a4f; display: flex; align-items: center; justify-content: center; }
        .logo-box svg { width: 22px; height: 22px; fill: #fff; }
        .logo-text span { display: block; font-weight: 800; font-size: 1.05rem; color: #1a1a1a; }
        .logo-text small { color: #888; font-size: 0.7rem; }
        h2 { font-size: 1.3rem; font-weight: 800; color: #1a1a1a; margin-bottom: 4px; }
        .subtitle { font-size: 0.84rem; color: #888; margin-bottom: 24px; }
        .form-label { font-weight: 600; font-size: 0.84rem; color: #444; margin-bottom: 5px; }
        .form-control { border-radius: 8px; border: 1.5px solid #e0e7e3; padding: 10px 14px; font-size: 0.88rem; }
        .form-control:focus { border-color: #2d7a4f; box-shadow: 0 0 0 3px rgba(45,122,79,0.12); outline: none; }
        .btn-login { background: #2d7a4f; color: #fff; border: none; border-radius: 8px; padding: 12px; font-size: 0.9rem; font-weight: 700; width: 100%; cursor: pointer; transition: background 0.18s; }
        .btn-login:hover { background: #1a5c38; }
        .alert-danger { background: #fdecea; border: 1px solid #f5c2c7; color: #842029; border-radius: 8px; padding: 10px 14px; font-size: .84rem; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-wrap">
            <div class="logo-box">
                <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <div class="logo-text">
                <span>Medini Digital</span>
                <small>Balai Desa Medini – ATK System</small>
            </div>
        </div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Masuk untuk mengelola stok ATK Balai Desa Medini</p>

        @if(session('error'))
            <div class="alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.admin.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk sebagai Admin
            </button>
        </form>

        <div style="margin-top:20px;text-align:center">
            <a href="{{ route('pilih.role') }}" style="font-size:.82rem;color:#2d7a4f;text-decoration:none;font-weight:600">
                ← Kembali ke Pilih Role
            </a>
        </div>
    </div>
</body>
</html>