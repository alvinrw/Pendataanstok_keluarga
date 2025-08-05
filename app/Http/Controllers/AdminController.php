<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('KelolaAkun', compact('admins'));
    }

    public function update(Request $request, $id)
    {
        // --- PERUBAHAN DIMULAI DI SINI ---
        // Pengecekan paling awal: jangan izinkan perubahan pada akun super admin (ID 2)
        if ((int)$id === 2) {
            return response()->json(['message' => 'Akun super admin tidak dapat diubah.'], 403); // 403 Forbidden
        }
        // --- AKHIR PERUBAHAN ---

        $admin = Admin::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:6'
        ]);

        $admin->username = $request->username;
        $admin->role = $request->role;

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return response()->json(['message' => 'User updated successfully']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user'
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => 'Pengguna berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        // --- PERUBAHAN DIMULAI DI SINI ---
        // Pengecekan paling awal: jangan izinkan penghapusan akun super admin (ID 2)
        if ((int)$id === 2) {
            return response()->json(['message' => 'Akun super admin tidak dapat dihapus.'], 403); // 403 Forbidden
        }
        // --- AKHIR PERUBAHAN ---

        // Periksa apakah pengguna mencoba menghapus akunnya sendiri
        if ((int)$id === Auth::id()) {
            return response()->json([
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'
            ], 403); // 403 Forbidden
        }

        $user = Admin::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Akun berhasil dihapus.'
        ]);
    }
}