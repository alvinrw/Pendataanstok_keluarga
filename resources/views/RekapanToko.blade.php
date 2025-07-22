<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Toko</title>
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
            color: #34495e;
        }

        .container {
            max-width: 900px;
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

        .btn {
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-add {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            margin-bottom: 25px;
        }
        
        .action-btn {
            padding: 8px 15px;
            font-size: 0.9rem;
            margin-right: 5px;
        }

        .btn-edit {
             background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        
        .btn-save {
             background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .table-section {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
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
            vertical-align: middle;
        }
        
        td:last-child {
            white-space: nowrap;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        tbody tr:hover {
            background: #f1f3f5;
        }
        
        .no-data-row td {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
        .no-data-row:hover {
            background: white;
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
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slide-down 0.3s ease-out;
        }
        
        @keyframes slide-down {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-header h2 {
            color: #2c3e50;
            font-size: 1.5rem;
        }

        .close-btn {
            color: #aaa;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>🏪 Laporan Penjualan Sederhana</h1>
            <p>Catat setiap transaksi dengan mudah</p>
        </div>

        <div class="content">
            <button class="btn btn-add" onclick="openAddModal()">➕ Tambah Transaksi</button>
            
            <div class="table-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="saleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Tambah Transaksi Baru</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            
            <form id="saleForm">
                <input type="hidden" id="sale-id">
                <div class="form-group">
                    <label for="modal-tanggal">Tanggal:</label>
                    <input type="date" id="modal-tanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="modal-total">Total Harga (Rp):</label>
                    <input type="number" id="modal-total" placeholder="Contoh: 50000" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="modal-catatan">Catatan:</label>
                    <textarea id="modal-catatan" placeholder="Contoh: Pembelian 2 buku dan 1 pensil"></textarea>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadSalesData();
            populateTable();
        });

        let salesData = [];
        const modal = document.getElementById('saleModal');
        const saleForm = document.getElementById('saleForm');

        // --- Fungsi Modal ---
        function openAddModal() {
            saleForm.reset();
            document.getElementById('modal-title').textContent = 'Tambah Transaksi Baru';
            document.getElementById('sale-id').value = '';
            document.getElementById('modal-tanggal').valueAsDate = new Date();
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }
        
        function editItem(id) {
            const itemToEdit = salesData.find(item => item.id === id);
            if (!itemToEdit) return;

            document.getElementById('modal-title').textContent = 'Edit Transaksi';
            document.getElementById('sale-id').value = itemToEdit.id;
            document.getElementById('modal-tanggal').value = itemToEdit.tanggal;
            document.getElementById('modal-total').value = itemToEdit.total;
            document.getElementById('modal-catatan').value = itemToEdit.catatan;

            modal.style.display = 'block';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // --- Fungsi Data (CRUD & Local Storage) ---
        function loadSalesData() {
            const data = localStorage.getItem('salesData');
            salesData = data ? JSON.parse(data) : [];
        }

        function saveSalesData() {
            localStorage.setItem('salesData', JSON.stringify(salesData));
        }
        
        saleForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const saleId = document.getElementById('sale-id').value;

            const sale = {
                id: saleId ? parseInt(saleId) : Date.now(), // Gunakan ID lama atau buat ID baru
                tanggal: document.getElementById('modal-tanggal').value,
                total: parseInt(document.getElementById('modal-total').value),
                catatan: document.getElementById('modal-catatan').value.trim()
            };

            const existingIndex = salesData.findIndex(item => item.id == sale.id);

            if (existingIndex > -1) {
                // Update data yang ada
                salesData[existingIndex] = sale;
            } else {
                // Tambah data baru
                salesData.push(sale);
            }
            
            // Urutkan data berdasarkan tanggal terbaru
            salesData.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal));

            saveSalesData();
            populateTable();
            closeModal();
        });

        function deleteItem(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                salesData = salesData.filter(item => item.id !== id);
                saveSalesData();
                populateTable();
            }
        }

        // --- Fungsi Tampilan ---
        function populateTable() {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (salesData.length === 0) {
                tableBody.innerHTML = `
                    <tr class="no-data-row">
                        <td colspan="5">Belum ada data penjualan. Silakan tambah transaksi baru.</td>
                    </tr>
                `;
                return;
            }

            salesData.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tanggal)}</td>
                        <td>${formatCurrency(item.total)}</td>
                        <td>${item.catatan || '-'}</td>
                        <td>
                            <button class="btn action-btn btn-edit" onclick="editItem(${item.id})">✏️ Edit</button>
                            <button class="btn action-btn btn-delete" onclick="deleteItem(${item.id})">🗑️ Hapus</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        // --- Fungsi Utilitas ---
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
    </script>

</body>
</html>