<?php

// ============================================================
// DAFTARKAN AdminMiddleware di bootstrap/app.php (Laravel 11)
// ============================================================
// Ganti isi bootstrap/app.php dengan ini:

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan AdminMiddleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


// ============================================================
// JIKA LARAVEL 10 — tambahkan di app/Http/Kernel.php
// Di dalam $routeMiddleware:
//
//   'admin' => \App\Http\Middleware\AdminMiddleware::class,
//
// ============================================================


// ============================================================
// ALUR SISTEM (TANPA DATABASE PENGGUNA)
// ============================================================
//
//  URL /  →  Halaman Pilih Role
//             ├── Klik "Admin"   → /admin/login (form username+password)
//             │                        ↓ berhasil → /dashboard (admin penuh)
//             └── Klik "Pengguna" → /pengguna/dashboard (langsung masuk)
//
//  Kredensial Admin (hardcoded, tidak perlu tabel users):
//    Username : admin
//    Password : admin123
//
//  Ubah kredensial di:
//    app/Http/Controllers/AdminLoginController.php
//    const ADMIN_USERNAME = 'admin';
//    const ADMIN_PASSWORD = 'admin123';
//
// ============================================================
