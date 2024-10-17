<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek apakah kredensial cocok
        if (Auth::attempt($credentials)) {
            // Regenerasi sesi untuk keamanan
            $request->session()->regenerate();

            // Dapatkan pengguna yang sedang login
            $user = Auth::user();

            // Arahkan berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->role === 'user') {
                return redirect()->intended('user/dashboard'); // Ubah sesuai path untuk user
            }
        }

        // Kembalikan dengan pesan error jika login gagal
        return back()->with('loginError', 'Password atau email salah!');
    }

    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();

        // Invalidate session dan regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman utama setelah logout
        return redirect('/');
    }
}
