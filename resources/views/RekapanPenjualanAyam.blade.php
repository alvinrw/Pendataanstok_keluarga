<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Ayam</title>
    <style>
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

        /* Improved Stock Section */
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

        .stock-card.total-ayam {
            --card-color: linear-gradient(90deg, #ff6b6b 0%, #ee5a52 100%);
        }

        .stock-card.total-berat {
            --card-color: linear-gradient(90deg, #51cf66 0%, #40c057 100%);
        }

        .stock-card.estimasi-nilai {
            --card-color: linear-gradient(90deg, #ffd93d 0%, #ffcd3c 100%);
        }

        .stock-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .stock-card h4 {
            color: #495057;
            font-size: 1rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stock-card p {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .stock-card .subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: normal;
            margin-top: 5px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }

        .filter-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .filter-group input,
        .filter-group select {
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .table-section {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 20px;
        }

        .table-header h3 {
            font-size: 1.3rem;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-diskon {
            background: #ff6b6b;
            color: white;
        }

        .status-normal {
            background: #51cf66;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .no-data h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #495057;
        }

        .export-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 25px;
            font-weight: 600;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .filter-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-top: 3px solid #667eea;
            padding: 20px;
            margin-top: 0;
            border-radius: 0 0 15px 15px;
        }

        .filter-summary-content h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .filter-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-stat {
            color: #495057;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-stat strong {
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            
            .stock-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .filter-stats {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            th, td {
                padding: 10px;
                font-size: 0.9rem;
            }

            .stock-card p {
                font-size: 1.5rem;
            }
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

        <script>
            // Tampilkan notifikasi sukses jika tersedia di session
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data telah dihapus.',
                            confirmButtonColor: '#27ae60'
                        });
                    }
                });
                return false; // Mencegah form dari pengiriman otomatis
            }
        </script>

        <div class="content">
            <a href="/" class="back-btn">← Kembali ke Menu Utama</a>
            <!-- Improved Stock Section -->
            <div class="stock-section">
                <h3>📦 Stok Ayam terjual Saat Ini</h3>
                <div class="stock-grid">
                    <div class="stock-card total-ayam">
                        <div class="icon">🐔</div>
                        <h4>Total Ayam</h4>
                        <p id="total-ayam">{{ $summary->total_ayam_terjual ?? 0 }}</p>
                        <div class="subtitle">Ekor ayam tersedia</div>
                    </div>
                    <div class="stock-card total-berat">
                        <div class="icon">⚖️</div>
                        <h4>Total Berat</h4>
                        <p id="total-berat">{{ number_format($summary->total_berat_tertimbang ?? 0) }} gram</p>
                        <div class="subtitle">Gram total berat</div>
                    </div>
                    <div class="stock-card estimasi-nilai">
                        <div class="icon">💰</div>
                        <h4>Estimasi Nilai</h4>
                        <p id="estimasi-nilai">Rp {{ number_format($summary->total_pemasukan ?? 0, 0, ',', '.') }}</p>
                        <div class="subtitle">Nilai estimasi stok</div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
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
                    </div>
                </div>
            </div>

            <!-- Table Section -->
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
                                <th>Jumlah Ayam</th>
                                <th>Berat Total</th>
                                <th>pakai diskon</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
@foreach ($data as $index => $item)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $item->tanggal }}</td>
    <td>{{ $item->nama_pembeli }}</td>
    <td>{{ $item->jumlah_ayam_dibeli }}</td>
    <td>{{ $item->berat_total }} gram</td>
    <td>{{ $item->diskon ? 'Ya' : 'Tidak' }}</td>
    <td>Rp {{ number_format($item->harga_total, 0, ',', '.') }}</td>
    <td>
        @if (!empty($item->id))
            <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this.form)">Hapus</button>

            </form>
        @else
            <span class="text-danger">ID Data Hilang</span>
        @endif
    </td>
</tr>

@endforeach


                        </tbody>
                    </table>
                </div>
                
                <!-- Filter Summary -->
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
        // Data storage
        let dataAyam = [];
        let filteredData = [];

        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        // Format number with thousand separator
        function formatNumber(number) {
            return number.toLocaleString('id-ID');
        }

        // Function to calculate and update stock display
        function updateStockDisplay() {
            const rows = document.querySelectorAll('#table-body tr');
            let totalAyam = 0;
            let totalBerat = 0;
            let totalHarga = 0;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 7) {
                    // Extract data from table cells
                    const jumlahAyam = parseInt(cells[3].textContent) || 0;
                    const beratText = cells[4].textContent.replace(' gram', '');
                    const berat = parseInt(beratText) || 0;
                    const hargaText = cells[6].textContent.replace('Rp ', '').replace(/\./g, '');
                    const harga = parseInt(hargaText) || 0;

                    totalAyam += jumlahAyam;
                    totalBerat += berat;
                    totalHarga += harga;
                }
            });

            // Update the stock display cards
            document.getElementById('total-ayam').textContent = totalAyam;
            document.getElementById('total-berat').textContent = formatNumber(totalBerat) + ' gram';
            document.getElementById('estimasi-nilai').textContent = formatCurrency(totalHarga);
        }

        // Populate table (tidak digunakan untuk Laravel data, hanya untuk filter)
        function populateTable() {
            // Function ini tidak dipanggil karena kita menggunakan data dari Laravel
            // Hanya untuk kompatibilitas dengan kode yang sudah ada
        }

        // Update filter summary
        function updateFilterSummary() {
            const totalAyam = filteredData.reduce((sum, item) => sum + item.jumlah, 0);
            const totalBerat = filteredData.reduce((sum, item) => sum + item.totalBerat, 0);
            const totalUang = filteredData.reduce((sum, item) => sum + item.totalAkhir, 0);

            document.getElementById('filter-total-ayam').textContent = formatNumber(totalAyam);
            document.getElementById('filter-total-berat').textContent = formatNumber(totalBerat);
            document.getElementById('filter-total-uang').textContent = formatCurrency(totalUang);
        }

        // Apply filter
        function applyFilter() {
            const tanggalDari = document.getElementById('filter-tanggal-dari').value;
            const tanggalSampai = document.getElementById('filter-tanggal-sampai').value;
            const pembeli = document.getElementById('filter-pembeli').value.toLowerCase();
            const diskon = document.getElementById('filter-diskon').value;

            filteredData = dataAyam.filter(item => {
                let valid = true;

                if (tanggalDari && item.tanggal < tanggalDari) valid = false;
                if (tanggalSampai && item.tanggal > tanggalSampai) valid = false;
                if (pembeli && !item.pembeli.toLowerCase().includes(pembeli)) valid = false;
                if (diskon && item.diskon !== diskon) valid = false;

                return valid;
            });

            populateTable();
        }

        // Export data to Excel
        function exportData() {
            const headers = ['No', 'Tanggal', 'Pembeli', 'Jumlah Ayam', 'Berat Total (gram)', 'Harga Asli', 'Status Diskon', 'Total Bayar'];
            
            const csvContent = [
                headers.join(','),
                ...filteredData.map((item, index) => [
                    index + 1,
                    item.tanggal,
                    `"${item.pembeli}"`,
                    item.jumlah,
                    item.totalBerat,
                    item.harga,
                    item.diskon === 'ya' ? 'Diskon 5%' : 'Normal',
                    item.totalAkhir
                ].join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `laporan_penjualan_ayam_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Initialize
        function init() {
            const today = new Date();
            const lastMonth = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
            
            document.getElementById('filter-tanggal-dari').value = lastMonth.toISOString().split('T')[0];
            document.getElementById('filter-tanggal-sampai').value = today.toISOString().split('T')[0];
            
            // Update stock display when page loads
            updateStockDisplay();
            // Tidak memanggil populateTable() karena data sudah ada dari Laravel
        }

        // Initialize on page load
        window.onload = init;

        // Also update stock display whenever DOM content is loaded (for Laravel data)
        document.addEventListener('DOMContentLoaded', function() {
            updateStockDisplay();
        });
    </script>
</body>
</html>