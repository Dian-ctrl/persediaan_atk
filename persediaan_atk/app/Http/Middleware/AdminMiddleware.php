<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Cek apakah sesi admin sudah aktif.
     * Tidak memerlukan database — hanya cek session 'is_admin'.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!session('is_admin')) {
            return redirect()->route('login.admin')
                             ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        return $next($request);
    }
}
