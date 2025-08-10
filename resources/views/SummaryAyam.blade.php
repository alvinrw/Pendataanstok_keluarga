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
                <label for="kloterSelect">Pilih Kloter Siap Jual:</label>
                <select id="kloterSelect" class="kloter-dropdown" onchange="changeKloter()">
                    <option value="">-- Pilih Kloter --</option>
                </select>
            </div>
            <!-- Tombol Tambah Kloter Baru sudah dihapus -->
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
                    <div class="stat-value" id="totalWeight">0</div>
                    <div class="stat-label">Total Berat Terjual (Kg)</div>
                </div>
                <div class="stat-card profit">
                    <span class="stat-icon">📈</span>
                    <div class="stat-value" id="keuntungan">Rp 0</div>
                    <div class="stat-label">Keuntungan Kloter Ini</div>
                </div>
                <div class="stat-card mortality">
                    <span class="stat-icon">💀</span>
                    <div class="stat-value" id="persenMati">0%</div>
                    <div class="stat-label">Persentase Kematian</div>
                </div>
            </div>
        </div>

        <div class="summary-section">
            <h3>📈 Ringkasan Total (Semua Kloter Panen)</h3>
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

    <!-- Modal Update Stok (tidak berubah) -->
    <div id="stockModal" class="modal">
        <!-- ... (Isi modal ini sama seperti sebelumnya) ... -->
    </div>

<script>
    let kloterData = {};
    let currentKloter = null;

    // PERBAIKAN DI SINI:
    // Menggunakan 'pageshow' agar data selalu di-refresh saat halaman ditampilkan,
    // termasuk saat pengguna menekan tombol "kembali" di browser.
    window.addEventListener('pageshow', function(event) {
        // Selalu panggil loadKloterData saat halaman ditampilkan
        // untuk memastikan data selalu yang terbaru.
        loadKloterData();
    });

    function loadKloterData() {
        // Reset tampilan sebelum memuat data baru
        document.getElementById('kloterInfo').style.display = 'none';
        document.getElementById('statsContainer').style.display = 'none';

        fetch('/kloters')
        .then(response => response.json())
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
        .then(response => response.json())
        .then(summary => {
            updateSummaryStats(summary);
        })
        .catch(error => console.error('Error loading summary data:', error));
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

 function updateKloterDropdown(kloters) {
        const select = document.getElementById('kloterSelect');
        select.innerHTML = '<option value="">-- Pilih Kloter --</option>';
        
        kloters.forEach(kloter => {
            const option = document.createElement('option');
            option.value = kloter.id;
            
            // Memberi penanda status pada setiap pilihan
            if (kloter.status === 'Aktif') {
                option.textContent = `${kloter.nama_kloter} (Belum Panen)`;
                // Kloter yang belum panen tidak bisa dipilih untuk dijual
                option.disabled = true; 
            } else { // Status 'Selesai Panen'
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
        // Update kartu lama
        document.getElementById('availableStock').textContent = data.stok_tersedia || 0;
        document.getElementById('totalSales').textContent = data.total_terjual || 0;
        document.getElementById('totalRevenue').textContent = formatCurrency(data.total_pemasukan || 0);
        document.getElementById('totalWeight').textContent = (data.total_berat || 0).toFixed(2);
        
        // Update kartu BARU
        document.getElementById('keuntungan').textContent = formatCurrency(data.keuntungan || 0);
        document.getElementById('persenMati').textContent = `${data.persentase_kematian || 0}%`;

        // Update display di modal
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
        document.getElementById('summaryTotalRevenue').textContent = formatCurrency(summary.total_pemasukan || 0);
    }

    // --- Fungsi untuk Modal Update Stok (tidak ada perubahan signifikan) ---
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
            // Update data lokal
            kloterData[updatedKloter.id] = { ...kloterData[updatedKloter.id], ...updatedKloter };
            currentKloter = kloterData[updatedKloter.id];
            
            updateKloterStats(currentKloter);
            updateKloterDropdown(Object.values(kloterData)); // Refresh dropdown untuk update sisa stok
            document.getElementById('kloterSelect').value = currentKloter.id; // Pilih kembali kloter yg aktif
            closeStockModal();
            loadSummaryData(); // Muat ulang summary total
        })
        .catch(error => console.error('Error updating stock:', error));
    }
</script>
</body>
</html>
