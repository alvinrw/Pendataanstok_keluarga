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
            <p>Kelola penjualan dari kloter yang sudah siap panen</p>
        </div>

        <div class="kloter-controls">
            <div class="kloter-selector">
                <label for="kloterSelect">Pilih Kloter:</label>
                <select id="kloterSelect" class="kloter-dropdown" onchange="changeKloter()">
                    <option value="">-- Pilih Kloter --</option>
                </select>
            </div>
        </div>

        <div id="kloterInfo" class="current-kloter-info" style="display: none;">
            <h3 id="currentKloterName"></h3>
            <p>Data penjualan dan performa untuk kloter ini</p>
        </div>

        <div id="statsContainer" style="display: none;">
            <div class="stats-grid">
                <div class="stat-card available-stock">
                    <span class="stat-icon">🐔</span>
                    <div class="stat-value" id="availableStock">0</div>
                    <div class="stat-label">Stok Tersedia (Ekor)</div>
                    <button class="update-stock-btn" onclick="openStockModal()">Update Stok</button>
                </div>
                <div class="stat-card total-sales">
                    <span class="stat-icon">📊</span>
                    <div class="stat-value" id="totalSales">0</div>
                    <div class="stat-label">Total Ayam Terjual (Ekor)</div>
                </div>
                 <div class="stat-card total-revenue">
                    <span class="stat-icon">💰</span>
                    <div class="stat-value" id="totalRevenue">Rp 0</div>
                    <div class="stat-label">Total Pemasukan Kloter Ini</div>
                </div>
                <div class="stat-card total-weight">
                    <span class="stat-icon">⚖️</span>
                    <div class="stat-value" id="totalWeight">0 Kg</div>
                    <div class="stat-label">Total Berat Terjual (Kg)</div>
                </div>
                <div class="stat-card expense">
                    <span class="stat-icon">💸</span>
                    <div class="stat-value" id="totalPengeluaran">Rp 0</div>
                    <div class="stat-label">Total Pengeluaran</div>
                </div>
                <div class="stat-card mortality">
                    <span class="stat-icon">💀</span>
                    <div class="stat-value" id="jumlahMati">0</div>
                    <div class="stat-label">Jumlah Kematian</div>
                </div>
                <div class="stat-card profit">
                    <span class="stat-icon">📈</span>
                    <div class="stat-value" id="keuntungan">Rp 0</div>
                    <div class="stat-label">Keuntungan Kloter Ini</div>
                </div>
            </div>
        </div>

        <div class="summary-section">
            <h3>📈 Ringkasan Total (Hanya Kloter Panen)</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-value" id="summaryTotalKloter">0</div>
                    <div class="summary-label">Total Kloter Panen</div>
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
                    <div class="summary-label">Total Pemasukan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Stok -->
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

    window.addEventListener('pageshow', function(event) {
        loadKloterData();
    });

    function loadKloterData() {
        document.getElementById('kloterInfo').style.display = 'none';
        document.getElementById('statsContainer').style.display = 'none';

        fetch('/kloters')
        .then(response => response.json())
        .then(kloters => {
            kloterData = kloters.reduce((acc, kloter) => {
                acc[kloter.id] = kloter;
                return acc;
            }, {});
            updateKloterDropdown(Object.values(kloterData));
            loadSummaryData();
        })
        .catch(error => console.error('Error loading kloter data:', error));
    }

    function loadSummaryData() {
        fetch('/summaries')
        .then(response => response.json())
        .then(summary => {
            updateSummaryStats(summary);
        })
        .catch(error => console.error('Error loading summary data:', error));
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount || 0);
    }

    function updateKloterDropdown(kloters) {
        const select = document.getElementById('kloterSelect');
        select.innerHTML = '<option value="">-- Pilih Kloter --</option>';
        
        kloters.forEach(kloter => {
            const option = document.createElement('option');
            option.value = kloter.id;
            
            if (kloter.status === 'Aktif') {
                option.textContent = `${kloter.nama_kloter} (Belum Panen)`;
                option.disabled = true; 
            } else {
                option.textContent = `${kloter.nama_kloter} (Siap Jual - Sisa: ${kloter.stok_tersedia} ekor)`;
                option.disabled = false;
            }
            
            select.appendChild(option);
        });
    }

    function changeKloter() {
        const selectedKloterId = document.getElementById('kloterSelect').value;
        const kloterInfo = document.getElementById('kloterInfo');
        const statsContainer = document.getElementById('statsContainer');

        if (!selectedKloterId) {
            kloterInfo.style.display = 'none';
            statsContainer.style.display = 'none';
            currentKloter = null;
            return;
        }
        
        currentKloter = kloterData[selectedKloterId];
        document.getElementById('currentKloterName').textContent = currentKloter.nama_kloter;
        kloterInfo.style.display = 'block';
        statsContainer.style.display = 'block';
        updateKloterStats(currentKloter);
    }

    function updateKloterStats(data) {
        if (!data) return;
        document.getElementById('availableStock').textContent = data.stok_tersedia || 0;
        document.getElementById('totalSales').textContent = data.total_terjual || 0;
        document.getElementById('totalRevenue').textContent = formatCurrency(data.total_pemasukan);
        document.getElementById('totalWeight').textContent = (data.total_berat || 0).toFixed(2) + ' Kg';
        document.getElementById('totalPengeluaran').textContent = formatCurrency(data.total_pengeluaran);
        document.getElementById('jumlahMati').textContent = `${data.jumlah_kematian || 0} ekor`;
        document.getElementById('keuntungan').textContent = formatCurrency(data.keuntungan);

        const currentStockDisplay = document.getElementById('currentStockDisplay');
        if(currentStockDisplay) {
            currentStockDisplay.textContent = data.stok_tersedia || 0;
        }
    }
    
    function updateSummaryStats(summary) {
        if (!summary) return;
        document.getElementById('summaryTotalKloter').textContent = summary.total_kloter || 0;
        document.getElementById('summaryTotalSales').textContent = summary.total_ayam_terjual || 0;
        document.getElementById('summaryTotalStock').textContent = summary.stok_ayam || 0;
        document.getElementById('summaryTotalWeight').textContent = (summary.total_berat_tertimbang || 0).toFixed(2) + ' Kg';
        document.getElementById('summaryTotalRevenue').textContent = formatCurrency(summary.total_pemasukan);
    }

    function openStockModal() {
        if (!currentKloter) return;
        const stockModal = document.getElementById('stockModal');
        if (stockModal) {
            stockModal.style.display = 'block';
            const newStockInput = document.getElementById('newStockInput');
            if(newStockInput) {
                newStockInput.value = '';
                newStockInput.focus();
            }
        }
    }

    function closeStockModal() {
        const stockModal = document.getElementById('stockModal');
        if (stockModal) {
            stockModal.style.display = 'none';
        }
    }

    function updateStock() {
        if (!currentKloter) return;
        const newStockInput = document.getElementById('newStockInput');
        if (!newStockInput) return;

        const newStock = parseInt(newStockInput.value);
        if (isNaN(newStock) || newStock < 0) { alert('Masukkan jumlah stok yang valid'); return; }

        fetch(`/kloters/${currentKloter.id}/update-stock`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ new_stock: newStock })
        })
        .then(response => response.json())
        .then(updatedKloter => {
            const fullKloterData = { ...kloterData[updatedKloter.id], ...updatedKloter };
            kloterData[updatedKloter.id] = fullKloterData;
            currentKloter = fullKloterData;
            
            updateKloterStats(currentKloter);
            updateKloterDropdown(Object.values(kloterData));
            document.getElementById('kloterSelect').value = currentKloter.id;
            closeStockModal();
            loadSummaryData();
        })
        .catch(error => console.error('Error updating stock:', error));
    }
</script>
</body>
</html>
