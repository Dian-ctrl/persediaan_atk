<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    /**
     * Kredensial admin disimpan di sini (tanpa database).
     * Ubah sesuai kebutuhan.
     */
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'admin123';

    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        if (session('is_admin')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (
            $request->username === self::ADMIN_USERNAME &&
            $request->password === self::ADMIN_PASSWORD
        ) {
            // Tandai sesi sebagai admin
            session(['is_admin' => true, 'admin_name' => 'Administrator']);
            return redirect()->route('dashboard');
        }

        return back()->withInput($request->only('username'))
                     ->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['is_admin', 'admin_name']);
        return redirect()->route('pilih.role');
    }
}
