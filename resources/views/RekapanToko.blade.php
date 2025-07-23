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
            max-width: 1000px;
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

        .btn-back {
            background: linear-gradient(135deg, #6c5ce7 0%, #5a4fcf 100%);
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

        .filter-section {
            background: #f8f9fa;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 15px;
            border: 1px solid #e9ecef;
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .filter-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
        }

        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-filter {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            padding: 10px 20px;
        }

        .btn-reset {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            padding: 10px 20px;
        }

        .summary-section {
            background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .summary-section h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .summary-amount {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .summary-info {
            margin-top: 10px;
            opacity: 0.9;
            font-size: 0.9rem;
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

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>📊 Laporan Penjualan Toko</h1>
            <p>Analisis data penjualan dengan filter tanggal</p>
        </div>

        <div class="content">
           <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>

            
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="start-date">Tanggal Mulai:</label>
                        <input type="date" id="start-date">
                    </div>
                    <div class="filter-group">
                        <label for="end-date">Tanggal Akhir:</label>
                        <input type="date" id="end-date">
                    </div>
                    <div class="filter-buttons">
                        <button class="btn btn-filter" onclick="applyFilter()">🔍 Filter</button>
                        <button class="btn btn-reset" onclick="resetFilter()">🔄 Reset</button>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="summary-section" id="summary-section">
                <h3>💰 Total Akumulasi</h3>
                <div class="summary-amount" id="total-amount">Rp 0</div>
                <div class="summary-info" id="summary-info">Dari seluruh data</div>
            </div>
            
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
                <h2 id="modal-title">Edit Transaksi</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            
           <form id="saleForm" method="POST">
    @csrf

                <input type="hidden" id="sale-id">
                <div class="form-group">
                    <label for="modal-tanggal">Tanggal:</label>
                    <input type="date" id="modal-tanggal" name="tanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="modal-total">Total Harga (Rp):</label>
                  <input type="number" id="modal-total" name="total_harga" placeholder="Contoh: 50000" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="modal-catatan">Catatan:</label>
                   <textarea id="modal-catatan" name="catatan" placeholder="Contoh: Pembelian 2 buku dan 1 pensil"></textarea>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('saleModal');
        const saleForm = document.getElementById('saleForm');

        // Data dari backend Laravel
        let salesData = @json($dataToko ?? []);
        let filteredData = [...salesData]; // Copy untuk data yang difilter

        // Menjalankan fungsi saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            populateTable();
            updateSummary();
        });

        // --- Fungsi Filter ---
        function applyFilter() {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            if (!startDate && !endDate) {
                alert('Pilih minimal satu tanggal untuk filter');
                return;
            }

            filteredData = salesData.filter(item => {
                const itemDate = new Date(item.tanggal);
                const start = startDate ? new Date(startDate) : new Date('1900-01-01');
                const end = endDate ? new Date(endDate) : new Date('2100-12-31');
                
                return itemDate >= start && itemDate <= end;
            });

            populateTable();
            updateSummary();
        }

        function resetFilter() {
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';
            filteredData = [...salesData];
            populateTable();
            updateSummary();
        }

        // --- Fungsi Summary ---
        function updateSummary() {
            const total = filteredData.reduce((sum, item) => sum + parseFloat(item.total_harga || 0), 0);
            const count = filteredData.length;
            
            document.getElementById('total-amount').textContent = formatCurrency(total);
            
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            
            let info = '';
            if (startDate || endDate) {
                if (startDate && endDate) {
                    info = `Dari ${formatDateShort(startDate)} s/d ${formatDateShort(endDate)} (${count} transaksi)`;
                } else if (startDate) {
                    info = `Dari ${formatDateShort(startDate)} ke atas (${count} transaksi)`;
                } else {
                    info = `Sampai ${formatDateShort(endDate)} (${count} transaksi)`;
                }
            } else {
                info = `Dari seluruh data (${count} transaksi)`;
            }
            
            document.getElementById('summary-info').textContent = info;
        }

        // --- Fungsi Modal ---
        function closeModal() {
            modal.style.display = 'none';
        }

        function editItem(id) {
            const itemToEdit = salesData.find(item => item.id == id);
            if (!itemToEdit) return;

            document.getElementById('modal-title').textContent = 'Edit Transaksi';
            document.getElementById('sale-id').value = itemToEdit.id;
            document.getElementById('modal-tanggal').value = itemToEdit.tanggal;
            document.getElementById('modal-total').value = itemToEdit.total_harga;
            document.getElementById('modal-catatan').value = itemToEdit.catatan;

            // Set form action untuk edit
           saleForm.action = `/penjualan-toko/${itemToEdit.id}`;
            
            let methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            methodInput.id = 'method-field';
            
            // Hapus _method lama kalau ada
            const existingMethod = document.getElementById('method-field');
            if (existingMethod) existingMethod.remove();
            
            saleForm.appendChild(methodInput);

            modal.style.display = 'block';
        }

        function deleteItem(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

            fetch(`/penjualan-toko/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => {
                if (response.ok) {
                    // Hapus item dari array salesData
                    salesData = salesData.filter(item => item.id !== id);
                    filteredData = filteredData.filter(item => item.id !== id);
                    // Refresh tabel dan summary
                    populateTable();
                    updateSummary();
                } else {
                    alert('Gagal menghapus data.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus.');
            });
        }

        // --- Fungsi Tampilan ---
        function populateTable() {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (!filteredData || filteredData.length === 0) {
                tableBody.innerHTML = `
                    <tr class="no-data-row">
                        <td colspan="5">Tidak ada data yang sesuai dengan filter.</td>
                    </tr>
                `;
                return;
            }

            filteredData.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tanggal)}</td>
                        <td>${formatCurrency(item.total_harga)}</td>
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
            if (isNaN(amount)) return 'Rp 0';
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }
        
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatDateShort(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
    </script>

</body>
</html>