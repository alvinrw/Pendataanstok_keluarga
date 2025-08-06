<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Ayam</title>
    <style>
        /* [ SEMUA CSS ANDA YANG SUDAH ADA, TIDAK ADA PERUBAHAN DI SINI ] */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .back-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .stock-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .stock-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .stock-section h3 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }

        .stock-section h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .stock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .stock-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stock-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--card-color);
        }

        .stock-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .stock-card.total-ayam { --card-color: linear-gradient(90deg, #ff6b6b 0%, #ee5a52 100%); }
        .stock-card.total-berat { --card-color: linear-gradient(90deg, #51cf66 0%, #40c057 100%); }
        .stock-card.estimasi-nilai { --card-color: linear-gradient(90deg, #ffd93d 0%, #ffcd3c 100%); }

        .stock-card .icon { font-size: 2.5rem; margin-bottom: 15px; opacity: 0.8; }
        .stock-card h4 { color: #495057; font-size: 1rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .stock-card p { color: #2c3e50; font-size: 2rem; font-weight: bold; margin-bottom: 8px; }
        .stock-card .subtitle { color: #6c757d; font-size: 0.9rem; font-weight: normal; margin-top: 5px; }

        .filter-section { background: #f8f9fa; padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #dee2e6; }
        .filter-section h3 { color: #2c3e50; margin-bottom: 20px; font-size: 1.3rem; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end; }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label { font-weight: 600; color: #495057; margin-bottom: 8px; font-size: 0.9rem; }
        .filter-group input, .filter-group select { padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        .filter-group input:focus, .filter-group select:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        .filter-btn, .reset-btn, .export-btn, .struk-btn, .action-btn { border: none; padding: 12px 20px; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; font-weight: 600; }
        
        .filter-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .filter-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
        .reset-btn { background: linear-gradient(135deg, #adb5bd 0%, #909da7 100%); color: white; }
        .reset-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(144, 157, 167, 0.3); }
        .export-btn { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: white; margin-top: 25px; }
        .export-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3); }

        /* == TOMBOL BARU == */
        .action-btn-group { display: flex; gap: 8px; }
        .action-btn { padding: 8px 12px; font-size: 0.85rem; }
        .struk-btn { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .hapus-btn { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .struk-btn:hover, .hapus-btn:hover { transform: translateY(-2px); box-shadow: 0 2px 8px rgba(0,0,0,0.2); }

        .table-section { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .table-header { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 20px; }
        .table-header h3 { font-size: 1.3rem; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e9ecef; white-space: nowrap; }
        th { background: #f8f9fa; font-weight: 600; color: #495057; font-size: 0.9rem; }
        tr:hover { background: #f8f9fa; }
        .no-data { text-align: center; padding: 40px; color: #6c757d; }
        .no-data h3 { font-size: 1.5rem; margin-bottom: 10px; color: #495057; }

        .filter-summary { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-top: 3px solid #667eea; padding: 20px; margin-top: 0; border-radius: 0 0 15px 15px; }
        .filter-summary-content h4 { color: #2c3e50; margin-bottom: 15px; font-size: 1.1rem; }
        .filter-stats { display: flex; gap: 30px; flex-wrap: wrap; align-items: center; }
        .filter-stat { color: #495057; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
        .filter-stat strong { color: #2c3e50; }

        @media (max-width: 768px) {
            .content { padding: 20px; }
            .stock-grid { grid-template-columns: 1fr; gap: 20px; }
            .filter-row { grid-template-columns: 1fr; }
            .filter-stats { flex-direction: column; gap: 15px; align-items: flex-start; }
            th, td { padding: 10px; font-size: 0.9rem; }
            .stock-card p { font-size: 1.5rem; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>📊 Laporan Penjualan Ayam</h1>
            <p>Analisis data penjualan dan performa bisnis</p>
        </div>

        <div class="content">
            <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>
            
            <div class="stock-section">
                <h3>📦 Ringkasan Penjualan Total</h3>
                <div class="stock-grid">
                    <div class="stock-card total-ayam">
                        <div class="icon">🐔</div>
                        <h4>Total Ayam Terjual</h4>
                        <p id="total-ayam">{{ $summary->total_ayam_terjual ?? 0 }}</p>
                    </div>
                    <div class="stock-card total-berat">
                        <div class="icon">⚖️</div>
                        <h4>Total Berat Terjual</h4>
                        <p id="total-berat">{{ number_format($summary->total_berat_tertimbang ?? 0) }} gr</p>
                    </div>
                    <div class="stock-card estimasi-nilai">
                        <div class="icon">💰</div>
                        <h4>Total Pemasukan</h4>
                        <p id="estimasi-nilai">Rp {{ number_format($summary->total_pemasukan ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <h3>🔍 Filter Laporan</h3>
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="filter-tanggal-dari">Dari Tanggal:</label>
                        <input type="date" id="filter-tanggal-dari" onchange="applyFilter()">
                    </div>
                    <div class="filter-group">
                        <label for="filter-tanggal-sampai">Sampai Tanggal:</label>
                        <input type="date" id="filter-tanggal-sampai" onchange="applyFilter()">
                    </div>
                    <div class="filter-group">
                        <label for="filter-pembeli">Nama Pembeli:</label>
                        <input type="text" id="filter-pembeli" placeholder="Cari pembeli..." onkeyup="applyFilter()">
                    </div>
                    <div class="filter-group">
                        <label for="filter-diskon">Status Diskon:</label>
                        <select id="filter-diskon" onchange="applyFilter()">
                            <option value="">Semua</option>
                            <option value="ya">Dengan Diskon</option>
                            <option value="tidak">Tanpa Diskon</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button class="filter-btn" onclick="applyFilter()">Terapkan Filter</button>
                        <button class="reset-btn" onclick="resetFilter()" style="margin-top: 10px;">Reset</button>
                    </div>
                </div>
            </div>

            <div class="table-section">
                <div class="table-header">
                    <h3>📋 Detail Penjualan</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pembeli</th>
                                <th>Jumlah</th>
                                <th>Berat Total</th>
                                <th>Diskon</th>
                                <th>Harga Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @forelse ($data as $index => $item)
                            <tr 
                                data-id="{{ $item->id }}"
                                data-tanggal="{{ $item->tanggal }}" 
                                data-tanggal-formatted="{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}"
                                data-pembeli="{{ strtolower($item->nama_pembeli) }}" 
                                data-jumlah-ayam="{{ $item->jumlah_ayam_dibeli }}"
                                data-berat-total="{{ $item->berat_total }}"
                                data-harga-total="{{ $item->harga_total }}"
                                data-diskon="{{ $item->diskon ? 'ya' : 'tidak' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                <td>{{ $item->nama_pembeli }}</td>
                                <td>{{ $item->jumlah_ayam_dibeli }} ekor</td>
                                <td>{{ number_format($item->berat_total) }} gram</td>
                                <td>{{ $item->diskon ? 'Ya' : 'Tidak' }}</td>
                                <td>Rp {{ number_format($item->harga_total, 0, ',', '.') }}</td>
                                <td>{{ $item->status ?? 'Lunas' }}</td>
                                <td>
                                    <div class="action-btn-group">
                                        <button class="action-btn struk-btn" onclick="generateStruk(this)">📄 Struk</button>
                                        @if (!empty($item->id))
                                        <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" onsubmit="return confirmDelete(this);">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn hapus-btn">🗑️ Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="no-data">
                                    <h3>🚫 Tidak ada data penjualan</h3>
                                    <p>Belum ada transaksi yang tercatat.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div id="filter-summary" class="filter-summary" style="display: none;">
                    <div class="filter-summary-content">
                        <h4>📊 Ringkasan Hasil Filter</h4>
                        <div class="filter-stats">
                            <span class="filter-stat">🐔 <strong id="filter-total-ayam">0</strong> ayam</span>
                            <span class="filter-stat">⚖️ <strong id="filter-total-berat">0</strong> gram</span>
                            <span class="filter-stat">💰 <strong id="filter-total-uang">Rp 0</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <button class="export-btn" onclick="exportData()">📥 Export ke Excel (CSV)</button>
        </div>
    </div>

<script>
    // =========================================================================
    // HELPER FUNCTIONS & GLOBAL SCOPE
    // =========================================================================

    const formatCurrency = (amount) => 'Rp ' + Number(amount).toLocaleString('id-ID');
    const formatNumber = (number) => Number(number).toLocaleString('id-ID');

    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#27ae60'
        });
        @endif
        // Jalankan filter saat halaman dimuat untuk memastikan ringkasan filter tersembunyi
        applyFilter();
    });

    // =========================================================================
    // ACTION FUNCTIONS (DELETE, PDF, PRINT)
    // =========================================================================

    function confirmDelete(form) {
        event.preventDefault(); 
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data akan hilang secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

   function downloadPdf(htmlContent, fileName) {
    // Buat elemen sementara di luar layar untuk rendering yang akurat
    const tempWrapper = document.createElement('div');
    tempWrapper.style.position = 'fixed';
    tempWrapper.style.left = '-9999px';
    tempWrapper.style.top = '0';
    tempWrapper.style.width = '210mm'; // Lebar kertas A4
    tempWrapper.style.height = 'auto';
    tempWrapper.innerHTML = htmlContent;
    document.body.appendChild(tempWrapper);

    const elementToRender = tempWrapper.querySelector('.invoice-box');

    if (!elementToRender) {
        console.error("Elemen '.invoice-box' tidak ditemukan.");
        Swal.fire('Gagal!', 'Struktur internal template PDF tidak valid.', 'error');
        document.body.removeChild(tempWrapper);
        return;
    }
    
    // --- PERUBAHAN UTAMA & TERAKHIR ---
    // 1. Secara manual kita ukur tinggi total dari elemen yang sudah dirender browser.
    const contentHeight = elementToRender.scrollHeight;

    const options = {
        margin: 0,
        filename: fileName,
        image: { type: 'jpeg', quality: 0.98 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['css', 'legacy'], avoid: '.summary-footer-wrapper' },
        // 2. Kita masukkan hasil pengukuran tinggi ke dalam opsi html2canvas
        html2canvas: {
            scale: 2,
            useCORS: true,
            letterRendering: true,
            // Perintah ini memaksa canvas untuk memiliki tinggi yang sama dengan konten
            windowHeight: contentHeight 
        }
    };

    Swal.fire({
        title: 'Membuat PDF...',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    html2pdf().from(elementToRender).set(options).save().then(function () {
        Swal.close();
        document.body.removeChild(tempWrapper);
    }).catch(function(error) {
        console.error('Error generating PDF:', error);
        Swal.fire('Gagal!', 'Terjadi kesalahan saat membuat PDF.', 'error');
        document.body.removeChild(tempWrapper);
    });
}
    function printContent(htmlContent) {
        const printFrame = document.createElement('iframe');
        printFrame.style.display = 'none';
        document.body.appendChild(printFrame);
        
        const frameDoc = printFrame.contentWindow.document;
        frameDoc.open();
        frameDoc.write(htmlContent);
        frameDoc.close();
        
        printFrame.onload = function() {
            try {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            } catch (e) {
                console.error("Print failed:", e);
                Swal.fire('Gagal', 'Gagal membuka dialog cetak.', 'error');
            } finally {
                setTimeout(() => {
                    document.body.removeChild(printFrame);
                }, 1000);
            }
        };
    }

function generateStruk(buttonEl) {
    const row = buttonEl.closest('tr');
    const data = row.dataset;

    // Kalkulasi (Tidak ada perubahan)
    const parseNumber = (str) => Number(String(str).replace(/[^0-9]/g, ''));
    const HARGA_PER_GRAM = 75;
    const beratTotal = Number(data.beratTotal);
    const hargaTotalFinal = Number(data.hargaTotal);
    const subtotal = beratTotal * HARGA_PER_GRAM;
    const diskonAmount = subtotal - hargaTotalFinal;
    const hasDiskon = diskonAmount > 0;
    
    // Format tanggal ke Bahasa Indonesia (Tidak ada perubahan)
    const tanggalObj = new Date(data.tanggal);
    const tanggalIndonesia = new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    }).format(tanggalObj);

    // Template 1: Preview Struk Kecil (Tidak ada perubahan)
    const receiptHtmlForDisplay = `
        <style>
            .ss-receipt-box{font-family:'Courier New',monospace;width:100%;max-width:350px;margin:0 auto;padding:10px;font-size:15px;line-height:1.6;color:#333;}.ss-receipt-box .header{text-align:center;margin-bottom:10px;}.ss-receipt-box .header h3{margin:0;font-weight:bold;}.ss-receipt-box hr{border:none;border-top:1px dashed #555;margin:10px 0;}.ss-receipt-box .item-row{display:flex;justify-content:space-between;}.ss-receipt-box .footer{text-align:center;margin-top:15px;font-size:13px;}
        </style>
        <div class="ss-receipt-box">
            <div class="header"><h3>Nota Pembelian</h3></div><hr>
            <div class="item-row"><span>Tanggal:</span> <span>${tanggalIndonesia}</span></div>
            <div class="item-row"><span>Pembeli:</span> <span>${data.pembeli.charAt(0).toUpperCase() + data.pembeli.slice(1)}</span></div><hr>
            <div><strong>Ayam Kampung</strong></div>
            <div class="item-row"><span>- Qty</span> <span>${data.jumlahAyam} ekor</span></div>
            <div class="item-row"><span>- Berat</span> <span>${formatNumber(beratTotal)} gr</span></div>
            <div class="item-row"><span>- Subtotal</span> <span>${formatCurrency(subtotal)}</span></div>
            ${hasDiskon ? `<div class="item-row"><span>- Diskon</span> <span>- ${formatCurrency(diskonAmount)}</span></div>` : ''}<hr>
            <div class="item-row" style="font-size:1.1em"><strong><span>TOTAL</span></strong> <strong><span>${formatCurrency(hargaTotalFinal)}</span></strong></div><hr>
            <div class="footer"><p>Ayam kampung asli. Enak dan sehat.<br>Terimakasih telah beli ayam kampung kami.</p></div>
        </div>`;
    
    // Template 2: Nota Formal (untuk PDF & Cetak)
    const receiptHtmlForPrint = `
        <!DOCTYPE html><html><head><meta charset="UTF-8"><title>Invoice - ${data.pembeli}</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; background: #fff; }
            .invoice-box { width: 100%; box-sizing: border-box; padding: 20px; }
            .header { text-align: center; margin-bottom: 2px; page-break-inside: avoid; }
            .info-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; page-break-inside: avoid; }
            .info-table td { padding: 5px 0; vertical-align: top; }
            .items-table { width: 100%; border-collapse: collapse; }
            .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            .items-table th { background-color: #f2f2f2; font-weight: bold; }
            .text-right { text-align: right; }
            
            /* --- PERUBAHAN UTAMA ADA DI SINI --- */
            /* 1. Membuat wrapper baru untuk menyatukan summary dan footer */
            .summary-footer-wrapper {
                margin-top: 20px;
                page-break-inside: avoid; /* Aturan Kunci: Jangan potong blok ini */
            }
            .summary-container { display: flex; justify-content: flex-end; }
            .summary-box { width: 45%; max-width: 300px; }
            .summary-line { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
            .summary-line.total-row { border-top: 2px solid #333; font-weight: bold; font-size: 14px; }
            /* 2. Mengatur ulang margin footer agar lebih konsisten */
            .footer { text-align: center; border-top: 1px solid #ddd; padding-top: 15px; margin-top: 30px; font-size: 10px; color: #666; }
        </style></head><body>
        <div class="invoice-box">
            <div class="header"><h1>Nota Pembelian</h1></div>
            <table class="info-table"><tbody>
                <tr><td><strong>Pembeli:</strong><br>${data.pembeli.charAt(0).toUpperCase() + data.pembeli.slice(1)}</td>
                    <td class="text-right"><strong>Invoice #:</strong> INV-${data.id}<br><strong>Tanggal:</strong> ${tanggalIndonesia}</td></tr>
                <tr><td><strong>Penjual:</strong><br>Eko Wahyudi</td><td></td></tr>
            </tbody></table>
            <table class="items-table"><thead><tr>
                <th>Produk</th><th class="text-right">Jumlah</th><th class="text-right">Berat Total</th>
                <th class="text-right">Harga/gram</th><th class="text-right">Subtotal</th>
            </tr></thead><tbody><tr>
                <td>Ayam Kampung Segar</td><td class="text-right">${data.jumlahAyam} ekor</td>
                <td class="text-right">${formatNumber(beratTotal)} gr</td><td class="text-right">${formatCurrency(HARGA_PER_GRAM)}</td>
                <td class="text-right">${formatCurrency(subtotal)}</td>
            </tr></tbody></table>
            
            <div class="summary-footer-wrapper">
                <div class="summary-container">
                    <div class="summary-box">
                        <div class="summary-line"><span>Subtotal</span><span class="text-right">${formatCurrency(subtotal)}</span></div>
                        ${hasDiskon ? `<div class="summary-line"><span style="color: #e74c3c;">Diskon</span><span class="text-right" style="color: #e74c3c;">- ${formatCurrency(diskonAmount)}</span></div>` : ''}
                        <div class="summary-line total-row"><span>TOTAL</span><span class="text-right">${formatCurrency(hargaTotalFinal)}</span></div>
                    </div>
                </div>
                <div class="footer"><p>Ayam kampung asli. Enak dan sehat.<br>Terima kasih atas pembelian Anda!</p></div>
            </div>

        </div></body></html>`;

    // Menampilkan Popup SweetAlert (Tidak ada perubahan)
    Swal.fire({
        title: 'Rincian Struk',
        html: receiptHtmlForDisplay,
        width: 400,
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '📥 Unduh PDF',
        denyButtonText: '🖨️ Cetak Struk',
        cancelButtonText: 'Tutup',
        confirmButtonColor: '#3498db',
        denyButtonColor: '#6c757d',
    }).then((result) => {
        if (result.isConfirmed) {
            downloadPdf(receiptHtmlForPrint, `Nota_${data.pembeli}_${data.id}.pdf`);
        } else if (result.isDenied) {
            printContent(receiptHtmlForPrint);
        }
    });
}


    // =========================================================================
    // FILTER & EXPORT FUNCTIONS
    // =========================================================================

    function updateFilterSummary(visibleRows) {
        let totalAyam = 0, totalBerat = 0, totalUang = 0;
        
        visibleRows.forEach(row => {
            totalAyam += parseInt(row.dataset.jumlahAyam) || 0;
            totalBerat += parseInt(row.dataset.beratTotal) || 0;
            totalUang += parseInt(row.dataset.hargaTotal) || 0;
        });

        document.getElementById('filter-total-ayam').textContent = formatNumber(totalAyam);
        document.getElementById('filter-total-berat').textContent = formatNumber(totalBerat) + ' gram';
        document.getElementById('filter-total-uang').textContent = formatCurrency(totalUang);
        
        const summaryBox = document.getElementById('filter-summary');
        const filtersActive = [
            document.getElementById('filter-tanggal-dari').value,
            document.getElementById('filter-tanggal-sampai').value,
            document.getElementById('filter-pembeli').value,
            document.getElementById('filter-diskon').value
        ].some(f => f);
        
        summaryBox.style.display = (filtersActive && visibleRows.length > 0) ? 'block' : 'none';
    }

    function applyFilter() {
        const tglDari = document.getElementById('filter-tanggal-dari').value;
        const tglSampai = document.getElementById('filter-tanggal-sampai').value;
        const pembeli = document.getElementById('filter-pembeli').value.toLowerCase();
        const diskon = document.getElementById('filter-diskon').value;

        const allRows = document.querySelectorAll('#table-body tr');
        const visibleRows = [];
        let dataFound = false;

        allRows.forEach(row => {
            if (!row.dataset.id) return; // Skip baris 'no-data'
            
            const rowData = row.dataset;
            const isVisible = 
                (!tglDari || rowData.tanggal >= tglDari) &&
                (!tglSampai || rowData.tanggal <= tglSampai) &&
                (!pembeli || rowData.pembeli.includes(pembeli)) &&
                (!diskon || rowData.diskon === diskon);
            
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) {
                visibleRows.push(row);
                dataFound = true;
            }
        });
        
        const noDataRow = document.querySelector('#table-body .no-data');
        if (noDataRow) {
            noDataRow.closest('tr').style.display = dataFound ? 'none' : '';
        }

        updateFilterSummary(visibleRows);
    }

    function resetFilter() {
        document.getElementById('filter-tanggal-dari').value = '';
        document.getElementById('filter-tanggal-sampai').value = '';
        document.getElementById('filter-pembeli').value = '';
        document.getElementById('filter-diskon').value = '';
        applyFilter();
    }

    function exportData() {
        // PERBAIKAN: Implementasi fungsi export ke CSV
        const visibleRows = Array.from(document.querySelectorAll('#table-body tr')).filter(row => row.style.display !== 'none' && row.dataset.id);

        if (visibleRows.length === 0) {
            Swal.fire('Info', 'Tidak ada data untuk diekspor. Silakan sesuaikan filter Anda.', 'info');
            return;
        }

        let csvContent = "data:text/csv;charset=utf-8,";
        const headers = ["No", "Tanggal", "Pembeli", "Jumlah (ekor)", "Berat Total (gram)", "Diskon", "Harga Total (Rp)", "Status"];
        csvContent += headers.join(",") + "\r\n";

        visibleRows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            const rowData = [
                index + 1,
                cells[1].innerText,
                `"${cells[2].innerText}"`, // Bungkus nama dengan kutip untuk handle koma
                parseInt(cells[3].innerText),
                parseInt(cells[4].innerText.replace(/[^0-9]/g, '')),
                cells[5].innerText,
                parseInt(cells[6].innerText.replace(/[^0-9]/g, '')),
                cells[7].innerText
            ];
            csvContent += rowData.join(",") + "\r\n";
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Laporan_Penjualan_Ayam.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
</body>
</html>