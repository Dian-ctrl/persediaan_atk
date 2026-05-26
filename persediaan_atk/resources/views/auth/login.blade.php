<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Stok Desa Medini</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Source+Sans+3:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2D6A4F;
            --primary-dark: #1B4332;
            --primary-light: #40916C;
            --primary-pale: #D8F3DC;
            --accent: #B7950B;
            --accent-light: #F4D03F;
            --border: #C8DBC8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Source Sans 3', sans-serif;
            background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 50%, #40916C 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-wrap {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
        }
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-badge {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 24px;
            color: var(--primary-dark);
            margin: 0 auto 16px;
            box-shadow: 0 6px 20px rgba(183,149,11,0.4);
        }
        .login-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
        }
        .login-header p {
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            margin-top: 4px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #2C4A30;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'Source Sans 3', sans-serif;
            font-size: 14px;
            color: #1a2a1e;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(64,145,108,0.12);
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(27,67,50,0.4);
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(27,67,50,0.5);
        }
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #C0392B;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .demo-info {
            margin-top: 16px;
            background: var(--primary-pale);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 12px;
            color: var(--primary-dark);
        }
        .demo-info strong { display: block; margin-bottom: 4px; }
        .demo-info code { background: white; padding: 1px 5px; border-radius: 4px; font-size: 11px; }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.4);
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-header">
        <div class="logo-badge">DM</div>
        <h1>STOK APP</h1>
        <p>Sistem Stok Opname – Balai Desa Medini</p>
    </div>

    <div class="login-card">
        @if($errors->any())
        <div class="alert-error">
            Email atau password salah. Silakan coba lagi.
        </div>
        @endif

        @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@desamedini.go.id" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Masuk ke Sistem</button>
        </form>

        <div class="demo-info">
            <strong>Akun Demo:</strong>
            Admin: <code>admin@desamedini.go.id</code> / <code>admin123</code><br>
            Staff: <code>staff@desamedini.go.id</code> / <code>staff123</code>
        </div>
    </div>

    <p class="footer-text">&copy; {{ date('Y') }} Balai Desa Medini &bull; Sistem Stok Opname v1.0</p>
</div>
</body>
</html>
