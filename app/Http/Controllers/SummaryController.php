<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Summary; // Tidak perlu model Summary di sini lagi jika hanya me-return view

class SummaryController extends Controller
{
    public function index()
    {
        // Cukup return view, data akan diisi oleh JavaScript melalui API
        return view('SummaryAyam');
    }

    // Hapus fungsi updateStok() dari sini
    // Karena update summary akan ditangani oleh KloterController
}