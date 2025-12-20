<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin_login');
    }


    public function login(Request $request)
    {
        // 1. Rate Limiting - Cegah brute force attack
        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'username' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.'],
            ]);
        }

        // 2. Validasi input yang lebih ketat
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255', 'alpha_dash'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);

        // 3. Coba login dengan sistem Auth Laravel
        if (Auth::attempt($credentials)) {
            // Reset rate limiter jika berhasil
            RateLimiter::clear($key);

            // Regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            // Langsung arahkan ke halaman welcome
            return redirect()->intended('welcome');
        }

        // 4. Tambah counter untuk rate limiting
        RateLimiter::hit($key, 300); // 5 menit lockout

        // 5. Jika login gagal
        return back()->withErrors([
            'username' => 'Username atau password yang diberikan salah.',
        ])->onlyInput('username');
    }


}

