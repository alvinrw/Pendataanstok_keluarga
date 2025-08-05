<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin_login');
    }


public function login(Request $request)
{
    // 1. Validasi input dari form
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    // 2. Coba login dengan sistem Auth Laravel
    // Fungsi ini otomatis mencari user & mengecek password yang sudah di-bcrypt
    if (Auth::attempt($credentials)) {
        // Jika berhasil...
        $request->session()->regenerate(); // Regenerasi session untuk keamanan

        // Langsung arahkan ke halaman welcome
        return redirect()->intended('welcome'); 
    }

    // 3. Jika login gagal...
    return back()->withErrors([
        'username' => 'Username atau password yang diberikan salah.',
    ])->onlyInput('username');
}


    
}

