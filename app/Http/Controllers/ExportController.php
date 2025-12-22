<?php

namespace App\Http\Controllers;

use App\Models\Kloter;
use App\Models\DataPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export rekapan penjualan to CSV
     */
    public function exportRekapanCSV()
    {
        $penjualans = DataPenjualan::with('kloter')
            ->whereNull('deleted_at')
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = 'rekapan_penjualan_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($penjualans) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, [
                'Tanggal',
                'Kloter',
                'Nama Pembeli',
                'Jumlah Ayam',
                'Berat Total (gram)',
                'Harga Asli',
                'Diskon',
                'Harga Total',
            ]);

            // Data
            foreach ($penjualans as $p) {
                fputcsv($file, [
                    $p->tanggal,
                    $p->kloter->nama_kloter ?? '-',
                    $p->nama_pembeli,
                    $p->jumlah_ayam_dibeli,
                    $p->berat_total,
                    'Rp ' . number_format($p->harga_asli, 0, ',', '.'),
                    $p->diskon ? 'Ya' : 'Tidak',
                    'Rp ' . number_format($p->harga_total, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export detail kloter to CSV (comprehensive)
     */
    public function exportKloterCSV(Kloter $kloter)
    {
        $kloter->load([
            'pengeluarans' => fn($q) => $q->whereNull('deleted_at')->orderBy('tanggal_pengeluaran'),
            'kematianAyams' => fn($q) => $q->whereNull('deleted_at')->orderBy('tanggal_kematian'),
            'panens' => fn($q) => $q->whereNull('deleted_at')->orderBy('tanggal_panen'),
            'dataPenjualans' => fn($q) => $q->whereNull('deleted_at')->orderBy('tanggal'),
            'summary'
        ]);

        $filename = 'kloter_' . str_replace(' ', '_', $kloter->nama_kloter) . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($kloter) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ===== INFO KLOTER =====
            fputcsv($file, ['INFORMASI KLOTER']);
            fputcsv($file, ['Nama Kloter', $kloter->nama_kloter]);
            fputcsv($file, ['Status', $kloter->status]);
            fputcsv($file, ['Tanggal Mulai', $kloter->tanggal_mulai]);
            fputcsv($file, ['Jumlah DOC', $kloter->jumlah_doc . ' ekor']);
            fputcsv($file, ['Harga Beli DOC', 'Rp ' . number_format($kloter->harga_beli_doc, 0, ',', '.')]);
            fputcsv($file, []);

            // ===== SUMMARY =====
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Sisa di Kandang', $kloter->sisa_ayam_hidup . ' ekor']);
            fputcsv($file, ['Total Panen', ($kloter->summary->total_panen ?? 0) . ' ekor']);
            fputcsv($file, ['Total Terjual', ($kloter->summary->total_terjual ?? 0) . ' ekor']);
            fputcsv($file, ['Stok Siap Jual', ($kloter->summary->stok_tersedia ?? 0) . ' ekor']);
            fputcsv($file, ['Total Kematian', ($kloter->summary->total_mati ?? 0) . ' ekor']);
            fputcsv($file, ['Total Pengeluaran', 'Rp ' . number_format($kloter->total_pengeluaran, 0, ',', '.')]);
            fputcsv($file, ['Total Pemasukan', 'Rp ' . number_format($kloter->summary->total_pemasukan ?? 0, 0, ',', '.')]);
            fputcsv($file, []);

            // ===== PENGELUARAN =====
            fputcsv($file, ['RIWAYAT PENGELUARAN']);
            fputcsv($file, ['Tanggal', 'Kategori', 'Jumlah', 'Pakan (kg)', 'Catatan']);
            foreach ($kloter->pengeluarans as $p) {
                fputcsv($file, [
                    $p->tanggal_pengeluaran,
                    $p->kategori,
                    'Rp ' . number_format($p->jumlah_pengeluaran, 0, ',', '.'),
                    $p->jumlah_pakan_kg ?? '-',
                    $p->catatan ?? '-',
                ]);
            }
            fputcsv($file, []);

            // ===== KEMATIAN =====
            fputcsv($file, ['RIWAYAT KEMATIAN']);
            fputcsv($file, ['Tanggal', 'Jumlah', 'Catatan']);
            foreach ($kloter->kematianAyams as $k) {
                fputcsv($file, [
                    $k->tanggal_kematian,
                    $k->jumlah_mati . ' ekor',
                    $k->catatan ?? '-',
                ]);
            }
            fputcsv($file, []);

            // ===== PANEN =====
            fputcsv($file, ['RIWAYAT PANEN']);
            fputcsv($file, ['Tanggal', 'Jumlah']);
            foreach ($kloter->panens as $p) {
                fputcsv($file, [
                    $p->tanggal_panen,
                    $p->jumlah_panen . ' ekor',
                ]);
            }
            fputcsv($file, []);

            // ===== PENJUALAN =====
            fputcsv($file, ['RIWAYAT PENJUALAN']);
            fputcsv($file, ['Tanggal', 'Pembeli', 'Jumlah', 'Berat (gram)', 'Harga Total']);
            foreach ($kloter->dataPenjualans as $p) {
                fputcsv($file, [
                    $p->tanggal,
                    $p->nama_pembeli,
                    $p->jumlah_ayam_dibeli . ' ekor',
                    $p->berat_total . ' gram',
                    'Rp ' . number_format($p->harga_total, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
