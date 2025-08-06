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
                        <input type="date" id="filter-tanggal-dari">
                    </div>
                    <div class="filter-group">
                        <label for="filter-tanggal-sampai">Sampai Tanggal:</label>
                        <input type="date" id="filter-tanggal-sampai">
                    </div>
                    <div class="filter-group">
                        <label for="filter-pembeli">Nama Pembeli:</label>
                        <input type="text" id="filter-pembeli" placeholder="Cari pembeli...">
                    </div>
                    <div class="filter-group">
                        <label for="filter-diskon">Status Diskon:</label>
                        <select id="filter-diskon">
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
                                data-berat-total="{{ number_format($item->berat_total) }}"
                                data-harga-total="Rp {{ number_format($item->harga_total, 0, ',', '.') }}"
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

            <button class="export-btn" onclick="exportData()">📥 Export ke Excel</button>
        </div>
    </div>

    <script>
        // Notifikasi dan konfirmasi hapus (tidak diubah)
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#27ae60'
                });
            @endif
        });

        function confirmDelete(form) {
            event.preventDefault(); // Mencegah form submit langsung
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

        // --- FUNGSI BARU UNTUK STRUK ---
function printReceipt(htmlContent, pembeli, tanggal) {
    // 1. Membuat nama file dinamis (tidak ada perubahan)
    const namaPembeliFormatted = pembeli.charAt(0).toUpperCase() + pembeli.slice(1);
    const namaFile = `Struk pembelian ${namaPembeliFormatted} ${tanggal}`;

    // 2. Membuka jendela baru yang kosong
    const printWindow = window.open('', '_blank', 'height=700,width=850');

    // 3. Jika jendela berhasil dibuka (tidak diblokir pop-up blocker)
    if (printWindow) {
        // 4. Menulis seluruh konten HTML ke jendela baru tersebut
        printWindow.document.write(`
            <html>
                <head>
                    <title>${namaFile}</title>
                </head>
                <body>
                    ${htmlContent}
                </body>
            </html>
        `);

        // 5. Menutup stream dokumen agar halaman selesai dimuat
        printWindow.document.close();

        // 6. Memberi jeda sesaat agar jendela sempat me-render konten
        setTimeout(function () {
            printWindow.focus();  // Fokus ke jendela baru
            printWindow.print();  // Memanggil dialog cetak
            printWindow.close();  // Menutup jendela setelah dialog cetak ditutup
        }, 250); // Jeda 250 milidetik

    } else {
        // Peringatan jika pop-up diblokir
        Swal.fire(
            'Gagal Membuka Jendela Cetak',
            'Browser Anda mungkin memblokir pop-up. Mohon izinkan pop-up untuk situs ini pada address bar.',
            'warning'
        );
    }
}


function generateStruk(buttonEl) {
    const row = buttonEl.closest('tr');
    const data = row.dataset;

    // Helper (tidak ada perubahan)
    const parseCurrency = (str) => Number(str.replace(/[^0-9]/g, ''));
    const formatCurrency = (num) => 'Rp ' + num.toLocaleString('id-ID');

    // Kalkulasi Biaya (tidak ada perubahan)
    const hargaTotalFinal = parseCurrency(data.hargaTotal);
    const beratTotal = parseCurrency(data.beratTotal);
    let subtotal, diskonAmount;

    if (data.diskon === 'ya') {
        subtotal = Math.round(hargaTotalFinal / 0.95);
        diskonAmount = subtotal - hargaTotalFinal;
    } else {
        subtotal = hargaTotalFinal;
        diskonAmount = 0;
    }
    const hargaPerGram = beratTotal > 0 ? (subtotal / beratTotal) : 0;

    // =======================================================================
    // --- TEMPLATE #1: UNTUK TAMPILAN POPUP (RAMAH SCREENSHOT HP) ---
    // =======================================================================
    const receiptHtmlForDisplay = `
        <style>
            .ss-receipt-box {
                font-family: 'Courier New', monospace;
                width: 100%;
                max-width: 350px;
                margin: 0 auto;
                padding: 10px;
                font-size: 15px;
                line-height: 1.6;
                color: #333;
            }
            .ss-receipt-box .header { text-align: center; margin-bottom: 10px; }
            .ss-receipt-box .header h3 { margin: 0; font-weight: bold; }
            .ss-receipt-box hr { border: none; border-top: 1px dashed #555; margin: 10px 0; }
            .ss-receipt-box .item-row { display: flex; justify-content: space-between; }
            .ss-receipt-box .footer { text-align: center; margin-top: 15px; font-size: 13px; }
        </style>
        <div class="ss-receipt-box">
            <div class="header">
                <h3>Struk Pembelian</h3>
            </div>
            <hr>
            <div class="item-row"><span>Tanggal:</span> <span>${data.tanggalFormatted}</span></div>
            <div class="item-row"><span>Pembeli:</span> <span>${data.pembeli.charAt(0).toUpperCase() + data.pembeli.slice(1)}</span></div>
            <hr>
            <div><strong>Ayam Kampung</strong></div>
            <div class="item-row"><span>- Qty</span> <span>${data.jumlahAyam} ekor</span></div>
            <div class="item-row"><span>- Berat</span> <span>${data.beratTotal} gr</span></div>
            <div class="item-row"><span>- Subtotal</span> <span>${formatCurrency(subtotal)}</span></div>
            ${diskonAmount > 0 ? `
            <div class="item-row"><span>- Diskon</span> <span>- ${formatCurrency(diskonAmount)}</span></div>` : ''}
            <hr>
            <div class="item-row" style="font-size: 1.1em;">
                <strong><span>TOTAL</span></strong> 
                <strong><span>${formatCurrency(hargaTotalFinal)}</span></strong>
            </div>
            <hr>
            <div class="footer">
                <p>Ayam kampung asli. Enak dan sehat.<br>Terimakasih telah beli ayam kampung di kami.</p>
            </div>
        </div>
    `;

    // ===============================================================
    // --- TEMPLATE #2: UNTUK DICETAK/PDF (DESAIN FORMAL) ---
    // ===============================================================
    const receiptHtmlForPrint = `
        <style>
            @page { size: auto; margin: 0mm; }
            body { margin: 0; }
            .invoice-box { max-width: 800px; margin: auto; padding: 30px; font-size: 14px; line-height: 20px; font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif; color: #555; }
            .invoice-box .header { text-align: center; margin-bottom: 20px; }
            .invoice-box .header h1 { margin: 0; color: #000; font-size: 28px; font-weight: bold; }
            .invoice-box .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; }
            .invoice-box .info-block { font-size: 12px; }
            .invoice-box .info-block strong { display: block; margin-bottom: 5px; color: #000; }
            .invoice-box table.details { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
            .invoice-box table.details thead th { background: #eee; border-bottom: 2px solid #ddd; font-weight: bold; color: #333; padding: 8px; }
            .invoice-box table.details tbody td { padding: 10px 8px; border-bottom: 1px solid #eee; }
            .invoice-box table.details tbody tr:last-child td { border-bottom: none; }
            .invoice-box .text-right { text-align: right; }
            .invoice-box .summary { display: flex; justify-content: flex-end; margin-top: 20px; }
            .invoice-box .summary table { width: 40%; }
            .invoice-box .summary td { padding: 5px 8px; }
            .invoice-box .summary tr.total td { border-top: 2px solid #aaa; font-size: 16px; font-weight: bold; color: #000; }
            .invoice-box .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
        </style>
        <div class="invoice-box">
            <div class="header">
                <h1>Struk Pembelian</h1>
            </div>
            <div class="info-section">
                <div class="info-block">
                    <strong>Pembeli:</strong>
                    ${data.pembeli.charAt(0).toUpperCase() + data.pembeli.slice(1)}<br>
                </div>
                <div class="info-block text-right">
                    <strong>Pihak Toko:</strong>
                    Eko Wahyudi<br>
                </div>
            </div>
            <div class="info-section" style="margin-bottom: 20px;">
                <div class="info-block">
                    <strong>Tanggal Pembelian:</strong>
                    ${data.tanggalFormatted}
                </div>
                <div class="info-block text-right">
                    <strong>Jenis Pembelian:</strong>
                    Ayam Kampung
                </div>
            </div>
            <table class="details">
                <thead>
                    <tr>
                        <th>Deskripsi</th><th>Qty</th><th>Berat Ayam</th><th>Harga Satuan (per gr)</th><th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ayam Kampung</td><td>${data.jumlahAyam} ekor</td><td>${data.beratTotal} gr</td><td>Rp. 75 </td><td class="text-right">${formatCurrency(subtotal)}</td>
                    </tr>
                </tbody>
            </table>
            <div class="summary" style="margin-top: 200px;">
                <table>
                    <tr><td>Subtotal</td><td class="text-right">${formatCurrency(subtotal)}</td></tr>
                    <tr><td>Diskon</td><td class="text-right">${formatCurrency(diskonAmount)}</td></tr>
                    <tr class="total"><td>TOTAL</td><td class="text-right">${formatCurrency(hargaTotalFinal)}</td></tr>
                </table>
            </div>
            <div class="footer">
                Ayam kampung asli. Enak dan sehat<br>
                Terimakasih telah beli ayam kampung di kami
            </div>
        </div>
    `;

    // Menampilkan popup SweetAlert dengan desain yang ramah di HP
    Swal.fire({
        title: 'Rincian Struk',
        html: receiptHtmlForDisplay, // <- Menggunakan template untuk display
        width: 'auto',
        maxWidth: '400px', // <- Lebar popup dibuat kecil
        showCancelButton: true,
        confirmButtonText: '🖨️ Cetak PDF', // <- Tombol untuk mencetak
        cancelButtonText: 'Tutup',
        confirmButtonColor: '#3498db',
    }).then((result) => {
        // Jika tombol "Cetak PDF" diklik
        if (result.isConfirmed) {
            // Maka kita panggil fungsi print dengan template untuk PDF
            printReceipt(receiptHtmlForPrint, data.pembeli, data.tanggal);
        }
    });
}


        // --- FUNGSI FILTER DAN LAINNYA (Telah dioptimalkan) ---
        const formatCurrency = (amount) => 'Rp ' + amount.toLocaleString('id-ID');
        const formatNumber = (number) => number.toLocaleString('id-ID');

        function updateFilterSummary(visibleRows) {
            let totalAyam = 0;
            let totalBerat = 0;
            let totalUang = 0;

            visibleRows.forEach(row => {
                totalAyam += parseInt(row.dataset.jumlahAyam) || 0;
                totalBerat += parseInt(row.dataset.beratTotal.replace(/[^0-9]/g, '')) || 0;
                totalUang += parseInt(row.dataset.hargaTotal.replace(/[^0-9]/g, '')) || 0;
            });

            document.getElementById('filter-total-ayam').textContent = formatNumber(totalAyam);
            document.getElementById('filter-total-berat').textContent = formatNumber(totalBerat) + ' gram';
            document.getElementById('filter-total-uang').textContent = formatCurrency(totalUang);
            
            const summaryBox = document.getElementById('filter-summary');
            const filters = [
                document.getElementById('filter-tanggal-dari').value,
                document.getElementById('filter-tanggal-sampai').value,
                document.getElementById('filter-pembeli').value,
                document.getElementById('filter-diskon').value
            ];
            
            summaryBox.style.display = filters.some(f => f) ? 'block' : 'none';
        }

        function applyFilter() {
            const tglDari = document.getElementById('filter-tanggal-dari').value;
            const tglSampai = document.getElementById('filter-tanggal-sampai').value;
            const pembeli = document.getElementById('filter-pembeli').value.toLowerCase();
            const diskon = document.getElementById('filter-diskon').value;

            const allRows = document.querySelectorAll('#table-body tr');
            const visibleRows = [];

            allRows.forEach(row => {
                if (!row.dataset.id) return; // Skip no-data row
                const rowData = row.dataset;
                
                const isVisible = 
                    (!tglDari || rowData.tanggal >= tglDari) &&
                    (!tglSampai || rowData.tanggal <= tglSampai) &&
                    (!pembeli || rowData.pembeli.includes(pembeli)) &&
                    (!diskon || rowData.diskon === diskon);
                
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleRows.push(row);
            });

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
            // Logika export Anda bisa diletakkan di sini
            Swal.fire('Info', 'Fungsi export belum diimplementasikan sepenuhnya.', 'info');
        }

    </script>
</body>
</html>