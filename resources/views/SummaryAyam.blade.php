<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan Ayam</title>
    <link rel="stylesheet" href="{{ asset('css/SummaryAyam.css') }}">
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="window.history.back()">← Kembali ke Menu Utama</button>

        <div class="header">
            <h1>🐔 Dashboard Penjualan Ayam</h1>
            <p>Kelola penjualan dan stok ayam per kloter</p>
        </div>

        <div class="kloter-controls">
            <div class="kloter-selector">
                <label for="kloterSelect">Pilih Kloter:</label>
                <select id="kloterSelect" class="kloter-dropdown" onchange="changeKloter()">
                    <option value="">-- Pilih Kloter --</option>
                </select>
            </div>
            <button class="new-kloter-btn" onclick="openNewKloterModal()">+ Kloter Baru</button>
        </div>

        <div id="kloterInfo" class="current-kloter-info" style="display: none;">
            <h3 id="currentKloterName"></h3>
            <p>Data penjualan dan stok untuk kloter ini</p>
        </div>

        <div id="statsContainer" style="display: none;">
            <div class="stats-grid">
                <div class="stat-card total-sales">
                    <span class="stat-icon">📊</span>
                    <div class="stat-value" id="totalSales">0</div>
                    <div class="stat-label">Total Ayam Terjual (Ekor)</div>
                </div>

                <div class="stat-card available-stock">
                    <span class="stat-icon">🐔</span>
                    <div class="stat-value" id="availableStock">0</div>
                    <div class="stat-label">Stok Tersedia (Ekor)</div>
                    <button class="update-stock-btn" onclick="openStockModal()">Update Stok</button>
                </div>

                <div class="stat-card total-weight">
                    <span class="stat-icon">⚖️</span>
                    <div class="stat-value" id="totalWeight">0</div>
                    <div class="stat-label">Total Berat Terjual (Kg)</div>
                </div>

                <div class="stat-card total-revenue">
                    <span class="stat-icon">💰</span>
                    <div class="stat-value" id="totalRevenue">Rp 0</div>
                    <div class="stat-label">Total Uang Terkumpul</div>
                </div>
            </div>
        </div>

        <div class="summary-section">
            <h3>📈 Ringkasan Total Semua Kloter</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalKloter">0</div>
                    <div class="summary-label">Total Kloter</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalSales">0</div>
                    <div class="summary-label">Total Ayam Terjual</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalStock">0</div>
                    <div class="summary-label">Total Stok Tersedia</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalWeight">0 Kg</div>
                    <div class="summary-label">Total Berat Terjual</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalRevenue">Rp 0</div>
                    <div class="summary-label">Total Uang Terkumpul</div>
                </div>
            </div>
        </div>
    </div>

    <div id="newKloterModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Buat Kloter Baru</h2>
                <span class="close" onclick="closeNewKloterModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="newKloterName">Nama Kloter</label>
                    <input type="text" id="newKloterName" placeholder="Contoh: Kloter 1, Kloter A, dll">
                </div>
                <div class="form-group">
                    <label for="initialStock">Stok Awal</label>
                    <input type="number" id="initialStock" placeholder="Jumlah ayam awal" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeNewKloterModal()">Batal</button>
                <button class="modal-btn modal-btn-success" onclick="createNewKloter()">Buat Kloter</button>
            </div>
        </div>
    </div>

    <div id="stockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Update Stok Ayam</h2>
                <span class="close" onclick="closeStockModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="current-stock">
                    <div class="current-stock-label">Stok Saat Ini</div>
                    <div class="current-stock-value" id="currentStockDisplay">0</div>
                </div>
                <div class="form-group">
                    <label for="newStockInput">Jumlah Stok Baru</label>
                    <input type="number" id="newStockInput" placeholder="Masukkan jumlah stok baru" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-secondary" onclick="closeStockModal()">Batal</button>
                <button class="modal-btn modal-btn-primary" onclick="updateStock()">Update Stok</button>
            </div>
        </div>
    </div>

    <script>
        let kloterData = {};
        let currentKloter = null;
        let summaryData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadKloterData();
        });

        function loadKloterData() {
            fetch('/kloters')
            .then(response => {
                if (!response.ok) { throw new Error('Gagal memuat data kloter.'); }
                return response.json();
            })
            .then(kloters => {
                kloterData = kloters.reduce((acc, kloter) => {
                    acc[kloter.id] = kloter;
                    return acc;
                }, {});
                updateKloterDropdown(kloters);
                loadSummaryData();
            })
            .catch(error => console.error('Error loading kloter data:', error));
        }

        function loadSummaryData() {
            fetch('/summaries')
            .then(response => {
                if (!response.ok) { throw new Error('Gagal memuat data ringkasan.'); }
                return response.json();
            })
            .then(summary => {
                summaryData = summary;
                updateSummaryStats();
            })
            .catch(error => console.error('Error loading summary data:', error));
        }

        function updateSummaryStats() {
            if (!summaryData) return;
            document.getElementById('summaryTotalKloter').textContent = Object.keys(kloterData).length;
            document.getElementById('summaryTotalSales').textContent = summaryData.total_ayam_terjual || 0;
            document.getElementById('summaryTotalStock').textContent = summaryData.stok_ayam || 0;
            document.getElementById('summaryTotalWeight').textContent = (summaryData.total_berat_tertimbang || 0).toFixed(2) + ' Kg';
            document.getElementById('summaryTotalRevenue').textContent = formatCurrency(summaryData.total_pemasukan || 0);
        }

        function updateKloterDropdown(kloters) {
            const select = document.getElementById('kloterSelect');
            select.innerHTML = '<option value="">-- Pilih Kloter --</option>';
            kloters.forEach(kloter => {
                const option = document.createElement('option');
                option.value = kloter.id;
                option.textContent = kloter.nama_kloter;
                select.appendChild(option);
            });
        }

        function changeKloter() {
            const selectedKloterId = document.getElementById('kloterSelect').value;
            if (!selectedKloterId) {
                document.getElementById('kloterInfo').style.display = 'none';
                document.getElementById('statsContainer').style.display = 'none';
                currentKloter = null;
                return;
            }
            currentKloter = kloterData[selectedKloterId];
            document.getElementById('currentKloterName').textContent = currentKloter.nama_kloter;
            document.getElementById('kloterInfo').style.display = 'block';
            document.getElementById('statsContainer').style.display = 'block';
            updateKloterStats(currentKloter);
        }

        function updateKloterStats(data) {
            if (!data) return;
            document.getElementById('totalSales').textContent = data.total_terjual || 0;
            document.getElementById('availableStock').textContent = data.stok_tersedia || 0;
            document.getElementById('totalWeight').textContent = (data.total_berat || 0).toFixed(2);
            document.getElementById('totalRevenue').textContent = formatCurrency(data.total_pemasukan || 0);
            document.getElementById('currentStockDisplay').textContent = data.stok_tersedia || 0;
        }

        function openNewKloterModal() {
            const modal = document.getElementById('newKloterModal');
            modal.style.display = 'block';
            setTimeout(() => { modal.classList.add('show'); }, 10);
            document.getElementById('newKloterName').value = '';
            document.getElementById('initialStock').value = '';
            document.getElementById('newKloterName').focus();
        }

        function closeNewKloterModal() {
            const modal = document.getElementById('newKloterModal');
            modal.classList.remove('show');
            setTimeout(() => { modal.style.display = 'none'; }, 300);
        }

        function createNewKloter() {
            const name = document.getElementById('newKloterName').value.trim();
            const initialStock = parseInt(document.getElementById('initialStock').value) || 0;
            if (!name) { alert('Masukkan nama kloter'); return; }
            fetch('/kloters', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ nama_kloter: name, stok_awal: initialStock })
            })
            .then(response => {
                if (!response.ok) { return response.json().then(err => { throw new Error(err.message || 'Gagal membuat kloter baru.'); }); }
                return response.json();
            })
            .then(() => {
                closeNewKloterModal();
                loadKloterData();
                showNotification(`Kloter "${name}" berhasil dibuat!`);
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        }

        function openStockModal() {
            if (!currentKloter) { alert('Pilih kloter terlebih dahulu'); return; }
            const modal = document.getElementById('stockModal');
            modal.style.display = 'block';
            setTimeout(() => { modal.classList.add('show'); }, 10);
            document.getElementById('currentStockDisplay').textContent = currentKloter.stok_tersedia;
            document.getElementById('newStockInput').value = '';
            document.getElementById('newStockInput').focus();
        }

        function closeStockModal() {
            const modal = document.getElementById('stockModal');
            modal.classList.remove('show');
            setTimeout(() => { modal.style.display = 'none'; }, 300);
        }

        function updateStock() {
            if (!currentKloter) return;
            const newStock = parseInt(document.getElementById('newStockInput').value);
            if (isNaN(newStock) || newStock < 0) { alert('Masukkan jumlah stok yang valid'); return; }
            fetch(`/kloters/${currentKloter.id}/update-stock`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ new_stock: newStock })
            })
            .then(response => {
                if (!response.ok) { return response.json().then(err => { throw new Error(err.message || 'Gagal update stok.'); }); }
                return response.json();
            })
            .then(data => {
                kloterData[data.id] = data;
                updateKloterStats(data);
                closeStockModal();
                loadSummaryData();
                showNotification(`Stok kloter "${data.nama_kloter}" berhasil diupdate menjadi ${newStock} ekor!`);
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0
            }).format(amount);
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px 20px;
                border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 1001;
                transform: translateX(300px); transition: transform 0.3s ease; font-weight: 500; max-width: 300px;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => { notification.style.transform = 'translateX(0)'; }, 10);
            setTimeout(() => {
                notification.style.transform = 'translateX(300px)';
                setTimeout(() => { if (document.body.contains(notification)) { document.body.removeChild(notification); } }, 300);
            }, 3000);
        }

        window.onclick = function(event) {
            const stockModal = document.getElementById('stockModal');
            const newKloterModal = document.getElementById('newKloterModal');
            if (event.target === stockModal) { closeStockModal(); }
            if (event.target === newKloterModal) { closeNewKloterModal(); }
        }

        document.getElementById('newStockInput').addEventListener('keypress', function(e) { if (e.key === 'Enter') { updateStock(); } });
        document.getElementById('newKloterName').addEventListener('keypress', function(e) { if (e.key === 'Enter') { document.getElementById('initialStock').focus(); } });
        document.getElementById('initialStock').addEventListener('keypress', function(e) { if (e.key === 'Enter') { createNewKloter(); } });
    </script>
</body>
</html>