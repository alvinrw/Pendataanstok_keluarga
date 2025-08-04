<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin; // ✅ Tambahkan baris ini untuk import model

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('KelolaAkun', compact('admins'));
    }
}
