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
        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Simpan ke session manual (karena kita nggak pakai guard admin)
            session(['admin_logged_in' => true]);
            return redirect('/welcome');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }
}

