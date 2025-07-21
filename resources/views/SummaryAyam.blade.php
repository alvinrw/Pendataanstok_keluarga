<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan Ayam</title>
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .back-button {
            margin-bottom: 20px;
            background: #2196F3;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.3s ease;
        }

        .back-button:hover {
            background: #1976D2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card.total-sales {
            border-color: #4CAF50;
        }

        .stat-card.available-stock {
            border-color: #2196F3;
        }

        .stat-card.total-revenue {
            border-color: #FF9800;
        }

        .stat-icon {
            font-size: 3em;
            margin-bottom: 15px;
            display: block;
        }

        .total-sales .stat-icon {
            color: #4CAF50;
        }

        .available-stock .stat-icon {
            color: #2196F3;
        }

        .total-revenue .stat-icon {
            color: #FF9800;
        }

        .stat-value {
            font-size: 2.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1em;
            color: #666;
            font-weight: 500;
        }

        .update-stock-btn {
            margin-top: 15px;
            background: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .update-stock-btn:hover {
            background: #1976D2;
            transform: translateY(-2px);
        }

        .table-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-header h2 {
            color: #333;
            font-size: 1.8em;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            font-size: 1.1em;
        }

        td {
            font-size: 1em;
            color: #555;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .weight-category {
            font-weight: bold;
            color: #333;
        }

        .stock-number {
            font-size: 1.2em;
            font-weight: bold;
            color: #2196F3;
        }

        .sold-number {
            font-weight: bold;
            color: #FF9800;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #2196F3;
            color: white;
        }

        .btn-edit:hover {
            background: #1976D2;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .modal-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2196F3;
        }

        .current-stock {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .current-stock-label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .current-stock-value {
            font-size: 1.8em;
            font-weight: bold;
            color: #2196F3;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn-primary {
            background: #2196F3;
            color: white;
        }

        .modal-btn-primary:hover {
            background: #1976D2;
        }

        .modal-btn-secondary {
            background: #f5f5f5;
            color: #333;
        }

        .modal-btn-secondary:hover {
            background: #e0e0e0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }

            .modal-content {
                margin: 10% auto;
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <button class="back-button" onclick="window.location.href='/'">← Kembali ke Menu Utama</button>
        <div class="header">
            <h1>🐔 Dashboard Penjualan Ayam</h1>
            <p>Rekapan penjualan dan stok ayam harian</p>
        </div>

        <div class="stats-grid">
            {{-- <div class="stat-card total-sales">
                <span class="stat-icon">📊</span>
                <div class="stat-value" id="totalSales">{{ $finalAyam}}</div>
                <div class="stat-label">Total Penjualan (Ekor)</div>
            </div> --}}

            <div class="stat-card available-stock">
                <span class="stat-icon">🐔</span>
                <div class="stat-value" id="availableStock">10</div>
                <div class="stat-label">Stok Tersedia (Ekor)</div>
                <button class="update-stock-btn" onclick="openStockModal()">Update Stok</button>
            </div>

            {{-- <div class="stat-card total-revenue">
                <span class="stat-icon">💰</span>
                <div class="stat-value" id="totalRevenue">Rp 2.450.000</div>
                <div class="stat-label">Total Pendapatan</div>
            </div> --}}
        </div>
    </div>

    <!-- Modal untuk Update Stok -->
    <div id="stockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Update Stok Ayam</h2>
                <span class="close" onclick="closeStockModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="current-stock">
                    <div class="current-stock-label">Stok Saat Ini</div>
                    <div class="current-stock-value" id="currentStockDisplay">7</div>
                </div>
                <div class="form-group">
                    <label for="newStockInput">Jumlah Stok Baru</label>
                    <input type="number" id="newStockInput" placeholder="Masukkan jumlah stok baru" min="0">
                </div>

{{-- <form action="{{ route('update.stok') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="newStockInput">Jumlah Stok Baru</label>
        <input type="number" id="newStockInput" name="jumlah_stok" placeholder="Masukkan jumlah stok baru" min="0" required>
    </div>

    <div class="modal-footer">
        <button class="modal-btn modal-btn-secondary" type="button" onclick="closeStockModal()">Batal</button>
        <button class="modal-btn modal-btn-primary" type="submit">Update Stok</button>
    </div>
</form> --}}

                
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeStockModal()">Batal</button>
                <button class="modal-btn modal-btn-primary" onclick="updateStock()">Update Stok</button>
            </div>
        </div>
    </div>

    <script>
        // Data penjualan
        let salesData = [
            {
                id: 1,
                weight: "0.6",
                stock: 15,
                sold: 12,
            },
            {
                id: 2,
                weight: "0.7",
                stock: 20,
                sold: 18,
            },
            {
                id: 3,
                weight: "0.8",
                stock: 18,
                sold: 25,
            },
            {
                id: 4,
                weight: "0.9",
                stock: 12,
                sold: 30,
            },
            {
                id: 5,
                weight: "1.0",
                stock: 10,
                sold: 35,
            },
            {
                id: 6,
                weight: "1+",
                stock: 12,
                sold: 5,
            }
        ];

        // Fungsi untuk menghitung total statistik
        function calculateStats() {
            const totalSales = salesData.reduce((sum, item) => sum + item.sold, 0);
            const totalStock = salesData.reduce((sum, item) => sum + item.stock, 0);

            document.getElementById('totalSales').textContent = totalSales;
            document.getElementById('availableStock').textContent = totalStock;
            document.getElementById('currentStockDisplay').textContent = totalStock;
        }

        // Fungsi untuk membuka modal
        function openStockModal() {
            const modal = document.getElementById('stockModal');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            
            // Reset input
            document.getElementById('newStockInput').value = '';
            document.getElementById('newStockInput').focus();
        }

        // Fungsi untuk menutup modal
        function closeStockModal() {
            const modal = document.getElementById('stockModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        // Fungsi untuk memperbarui stok
        function updateStock() {
            const newStock = parseInt(document.getElementById('newStockInput').value);
            
            if (isNaN(newStock) || newStock < 0) {
                alert('Masukkan jumlah stok yang valid (minimal 0).');
                return;
            }

            // Update total stok (dalam implementasi nyata, ini bisa lebih kompleks)
            const currentTotal = salesData.reduce((sum, item) => sum + item.stock, 0);
            const difference = newStock - currentTotal;
            
            // Distribusi stok baru ke kategori pertama (contoh sederhana)
            if (salesData.length > 0) {
                salesData[0].stock = Math.max(0, salesData[0].stock + difference);
            }

            // // Update tampilan
            // calculateStats();
            
            // // Tutup modal
            // closeStockModal();
            
            // // Tampilkan notifikasi
            // showNotification(`Stok berhasil diupdate menjadi ${newStock} ekor!`);
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message) {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4CAF50;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 1001;
                transform: translateX(300px);
                transition: transform 0.3s ease;
            `;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animasi masuk
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Hapus setelah 3 detik
            setTimeout(() => {
                notification.style.transform = 'translateX(300px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Fungsi untuk kembali ke halaman utama
        function goBack() {
            alert('Kembali ke halaman utama (fungsi belum diimplementasikan).');
        }

        // Event listener untuk menutup modal ketika klik di luar
        window.onclick = function(event) {
            const modal = document.getElementById('stockModal');
            if (event.target === modal) {
                closeStockModal();
            }
        }

        // Event listener untuk tombol Enter di input
        document.getElementById('newStockInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateStock();
            }
        });

        // Render statistik saat halaman dimuat
        // calculateStats();
    </script>
</body>
</html>