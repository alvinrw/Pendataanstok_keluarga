<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Summary;

class SummaryController extends Controller
{
    public function index()
    {
        // Ambil semua data summary dari tabel `summaries`
        $summaries = Summary::all();

        // Kirim ke view
        return view('SummaryAyam', compact('summaries'));
    }
}

