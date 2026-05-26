@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    </div>
    <div>
        <h1>Profil Akun</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Profil</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;max-width:820px">

    <!-- AVATAR CARD -->
    <div class="card" style="text-align:center;padding:28px 20px">
        <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--primary-dark),var(--primary-light));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-family:'Cinzel',serif;font-size:28px;font-weight:700;color:white;box-shadow:0 6px 20px rgba(27,67,50,0.3)">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <div style="font-weight:700;font-size:16px;color:var(--text-dark)">{{ auth()->user()->name }}</div>
        <div style="font-size:13px;color:var(--text-muted);margin-top:4px">{{ auth()->user()->email }}</div>
        <div style="margin-top:10px">
            <span class="badge {{ auth()->user()->role=='admin' ? 'badge-green' : 'badge-gray' }}">
                {{ auth()->user()->role == 'admin' ? 'Administrator' : 'Staff' }}
            </span>
        </div>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted)">
            Bergabung {{ auth()->user()->created_at->format('d F Y') }}
        </div>
    </div>

    <!-- FORMS -->
    <div style="display:flex;flex-direction:column;gap:16px">

        <!-- UBAH PROFIL -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Ubah Informasi Profil
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.update') }}">
                    @csrf @method('PUT')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Profil
                    </button>
                </form>
            </div>
        </div>

        <!-- UBAH PASSWORD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Ubah Password
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.password') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Password Lama <span class="required">*</span></label>
                        <input type="password" name="current_password" class="form-control" placeholder="Password saat ini" required>
                        @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="form-group">
                            <label class="form-label">Password Baru <span class="required">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Ganti Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
