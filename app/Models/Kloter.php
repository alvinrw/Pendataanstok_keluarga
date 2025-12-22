<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kloter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kloter',
        'status',
        'tanggal_mulai',
        'jumlah_doc',
        'harga_beli_doc',
        'tanggal_panen',
        'harga_jual_total',
        'sisa_ayam_hidup',
        'total_pengeluaran',
    ];

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function kematianAyams()
    {
        return $this->hasMany(KematianAyam::class);
    }

    public function dataPenjualans()
    {
        return $this->hasMany(DataPenjualan::class);
    }

    public function panens()
    {
        return $this->hasMany(Panen::class);
    }

    public function summary()
    {
        return $this->hasOne(KloterSummary::class);
    }

    // ========================================
    // BUSINESS ANALYTICS ACCESSOR METHODS
    // ========================================

    /**
     * Calculate Modal DOC (biaya beli DOC awal)
     * harga_beli_doc sudah total harga, bukan per ekor
     */
    public function getModalDocAttribute()
    {
        return $this->harga_beli_doc;
    }

    /**
     * Get Total Terjual from summary (backward compatibility)
     */
    public function getTotalTerjualAttribute()
    {
        return $this->summary->total_terjual ?? 0;
    }

    /**
     * Get Stok Tersedia from summary (backward compatibility)
     */
    public function getStokTersediaAttribute()
    {
        return $this->summary->stok_tersedia ?? 0;
    }

    /**
     * Get Total Berat from summary (backward compatibility)
     */
    public function getTotalBeratAttribute()
    {
        return $this->summary->total_berat_kg ?? 0;
    }

    /**
     * Calculate Total Pemasukan from summary (backward compatibility)
     */
    public function getTotalPemasukanAttribute()
    {
        return $this->summary->total_pemasukan ?? 0;
    }

    /**
     * Calculate Total Berat Terjual (total weight sold in Kg)
     * berat_total disimpan dalam gram, jadi perlu dibagi 1000 untuk dapat Kg
     */
    public function getTotalBeratTerjualAttribute()
    {
        return $this->dataPenjualans()->sum('berat_total') / 1000;
    }

    /**
     * Calculate Keuntungan Bersih (net profit)
     * Formula: Total Pemasukan - (Modal DOC + Total Pengeluaran)
     * Modal DOC terpisah, tidak dihitung dobel dari pengeluaran
     */
    public function getKeuntunganBersihAttribute()
    {
        $totalPemasukan = $this->total_pemasukan;
        // DOC sudah termasuk dalam pengeluarans dengan kategori 'DOC'
        // Jadi kita hanya perlu menghitung total dari pengeluarans
        $totalPengeluaran = $this->pengeluarans()->sum('jumlah_pengeluaran');

        return $totalPemasukan - $totalPengeluaran;
    }

    /**
     * Calculate Margin Keuntungan (profit margin %)
     * Formula: (Keuntungan Bersih ÷ Total Pemasukan) × 100%
     */
    public function getMarginKeuntunganAttribute()
    {
        // Total pengeluaran dari semua kategori
        $totalPengeluaran = $this->pengeluarans()->sum('jumlah_pengeluaran');

        if ($totalPengeluaran <= 0) {
            return 0;
        }

        // Margin = (Keuntungan Bersih / Total Pengeluaran) × 100
        // Ini adalah Markup/ROI, bukan Profit Margin
        return round(($this->keuntungan_bersih / $totalPengeluaran) * 100, 1);
    }

    /**
     * Calculate FCR (Feed Conversion Ratio)
     * Formula: Total Pakan (Kg) ÷ Total Berat Ayam Hidup (Kg)
     * Berat Hidup = Berat Karkas ÷ 0.8 (karena karkas = 80% dari berat hidup)
     */
    public function getFcrAttribute()
    {
        $totalPakanKg = $this->pengeluarans()->sum('jumlah_pakan_kg');
        $totalBeratKarkas = $this->total_berat_terjual; // dalam Kg
        $totalBeratHidup = $totalBeratKarkas / 0.8; // konversi ke berat hidup

        if ($totalBeratHidup <= 0) {
            return 0;
        }

        return round($totalPakanKg / $totalBeratHidup, 2);
    }

    /**
     * Get Breakdown Pengeluaran by Kategori (with new categories)
     */
    public function getBreakdownPengeluaranAttribute()
    {
        $breakdown = $this->pengeluarans()
            ->selectRaw('kategori, SUM(jumlah_pengeluaran) as total')
            ->groupBy('kategori')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->kategori => $item->total];
            });

        $total = $this->total_pengeluaran;

        return [
            'DOC' => [
                'nominal' => $breakdown['DOC'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['DOC'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Pakan' => [
                'nominal' => $breakdown['Pakan'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Pakan'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Obat' => [
                'nominal' => $breakdown['Obat'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Obat'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Listrik/Air' => [
                'nominal' => $breakdown['Listrik/Air'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Listrik/Air'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Tenaga Kerja' => [
                'nominal' => $breakdown['Tenaga Kerja'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Tenaga Kerja'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Pemeliharaan Kandang' => [
                'nominal' => $breakdown['Pemeliharaan Kandang'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Pemeliharaan Kandang'] ?? 0) / $total) * 100, 1) : 0
            ],
            'Lainnya' => [
                'nominal' => $breakdown['Lainnya'] ?? 0,
                'persentase' => $total > 0 ? round((($breakdown['Lainnya'] ?? 0) / $total) * 100, 1) : 0
            ],
        ];
    }

    /**
     * Calculate Mortality Rate (tingkat kematian)
     */
    public function getMortalityRateAttribute()
    {
        $totalMati = $this->kematianAyams()->sum('jumlah_mati');

        if ($this->jumlah_doc <= 0) {
            return 0;
        }

        return round(($totalMati / $this->jumlah_doc) * 100, 1);
    }

}

