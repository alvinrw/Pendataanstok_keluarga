<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penjualan Ayam</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
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
            margin-bottom: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .stock-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stock-section.empty {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        .stock-section h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .stock-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stock-card {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stock-card h4 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .stock-card p {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .form-section.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .form-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4facfe;
        }

        .form-group input.error {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .discount-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .discount-section h4 {
            color: #856404;
            margin-bottom: 15px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: normal;
        }

        .radio-group input[type="radio"] {
            width: auto;
        }

        .calculation-section {
            background: #e8f5e8;
            border: 1px solid #c3e6cb;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .calculation-section h4 {
            color: #155724;
            margin-bottom: 15px;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .calc-row.total {
            border-top: 2px solid #155724;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .submit-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        /* Modal styles for stock empty notification */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            animation: modalSlide 0.3s ease;
        }

        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-content h3 {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .modal-content p {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .modal-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .stock-info {
                grid-template-columns: 1fr;
            }

            .radio-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🐔 Input Data Penjualan Ayam</h1>
            <p>Kelola transaksi penjualan ayam dengan mudah</p>
        </div>

        <div class="content">
            <a href="/" class="back-btn">← Kembali ke Menu Utama</a>

            <!-- Stock Section -->
            <div class="stock-section" id="stock-section">
                <h3>📊 Stok Ayam Saat Ini</h3>
                <div class="stock-info">
                    <div class="stock-card">
                        <h4>Total Ayam</h4>
                        <p id="total-ayam">{{ $stokAyam }}</p>
                    </div>
                </div>
            </div>

            <!-- Alert Section -->
            <div id="alert-section"></div>

            <!-- Form Section -->
            <div class="form-section" id="form-section">
                <h3>📝 Form Input Penjualan</h3>
                <form id="form-penjualan" method="POST" action="{{ route('penjualan.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Pembelian:</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pembeli">Nama Pembeli:</label>
                            <input type="text" id="nama_pembeli" name="nama_pembeli" placeholder="Masukkan nama pembeli"
                                required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jumlah_ayam">Jumlah Ayam Dipesan:</label>
                            <input type="number" id="jumlah_ayam" name="jumlah_ayam_dibeli" min="1" placeholder="0" required>
                            <small id="stock-warning" style="color: #e74c3c; font-size: 0.9rem; display: none;"></small>
                        </div>
                        <div class="form-group">
                            <label for="harga_total">Harga Total:</label>
                            <input type="text" id="harga_total" name="harga_asli" placeholder="Rp 0" required>
                        </div>
                    </div>

                    <!-- Discount Section -->
                    <div class="discount-section">
                        <h4>🎯 Diskon 5%</h4>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="diskon" value="1" id="diskon-ya">
                                Ya, berikan diskon 5%
                            </label>
                            <label>
                                <input type="radio" name="diskon" value="0" id="diskon-tidak" checked>
                                Tidak ada diskon
                            </label>
                        </div>
                    </div>

                    <!-- Calculation Section -->
                    <div class="calculation-section">
                        <h4>🔢 Perhitungan Otomatis</h4>
                        <div class="calc-row">
                            <span>Berat per Ayam (Harga ÷ 75):</span>
                            <span id="berat-per-ayam">0 gram</span>
                        </div>
                        <div class="calc-row">
                            <span>Total Berat:</span>
                            <span id="total-berat-calc">0 gram</span>
                        </div>
                        <div class="calc-row">
                            <span>Harga Sebelum Diskon:</span>
                            <span id="harga-sebelum">Rp 0</span>
                        </div>
                        <div class="calc-row">
                            <span>Diskon (5%):</span>
                            <span id="nilai-diskon">Rp 0</span>
                        </div>
                        <div class="calc-row total">
                            <span>Total Akhir:</span>
                            <span id="total-akhir">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">💾 Simpan Data Penjualan</button>

                    @csrf

                    <input type="hidden" name="berat_total" id="berat_total">
                    <input type="hidden" name="harga_asli" id="harga_asli">
                    <input type="hidden" name="harga_total" id="harga_total_final">
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Empty Stock -->
    <div id="empty-stock-modal" class="modal">
        <div class="modal-content">
            <h3>⚠️ Stok Habis!</h3>
            <p>Maaf, stok ayam sudah habis. Silakan tambah stok terlebih dahulu sebelum melakukan penjualan.</p>
            <button class="modal-btn" onclick="closeModal()">Mengerti</button>
        </div>
    </div>

    <script>
        // Get stock from server
        let currentStock = parseInt(document.getElementById('total-ayam').textContent);

        // Format currency dengan titik pemisah ribuan
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        // Format number input untuk rupiah
        function formatRupiahInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                input.value = '';
            }
        }

        // Parse rupiah input to number
        function parseRupiah(rupiahString) {
            return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0;
        }

        // Update stock display
        function updateStockDisplay() {
            const stockElement = document.getElementById('total-ayam');
            const stockSection = document.getElementById('stock-section');
            const formSection = document.getElementById('form-section');
            
            stockElement.textContent = currentStock;
            
            if (currentStock <= 0) {
                stockSection.classList.add('empty');
                formSection.classList.add('disabled');
                showEmptyStockModal();
            } else {
                stockSection.classList.remove('empty');
                formSection.classList.remove('disabled');
            }
        }

        // Show empty stock modal
        function showEmptyStockModal() {
            document.getElementById('empty-stock-modal').style.display = 'block';
        }

        // Close modal
        function closeModal() {
            document.getElementById('empty-stock-modal').style.display = 'none';
        }

        // Validate stock
        function validateStock() {
            const jumlahInput = document.getElementById('jumlah_ayam');
            const jumlah = parseInt(jumlahInput.value) || 0;
            const stockWarning = document.getElementById('stock-warning');
            const submitBtn = document.getElementById('submit-btn');
            
            if (jumlah > currentStock) {
                jumlahInput.classList.add('error');
                stockWarning.style.display = 'block';
                stockWarning.textContent = `❌ Stok tidak mencukupi! Tersedia: ${currentStock} ayam`;
                submitBtn.disabled = true;
                showAlert(`Jumlah ayam yang dipesan (${jumlah}) melebihi stok yang tersedia (${currentStock})!`, 'error');
                return false;
            } else {
                jumlahInput.classList.remove('error');
                stockWarning.style.display = 'none';
                submitBtn.disabled = false;
                
                if (jumlah > 0 && jumlah <= currentStock) {
                    const remaining = currentStock - jumlah;
                    if (remaining === 0) {
                        showAlert(`⚠️ Perhatian: Setelah transaksi ini, stok akan habis!`, 'warning');
                    } else if (remaining <= 5) {
                        showAlert(`⚠️ Perhatian: Setelah transaksi ini, stok tersisa ${remaining} ayam`, 'warning');
                    }
                }
                return true;
            }
        }

        // Calculate values
        function calculateValues() {
            if (!validateStock()) {
                return {
                    beratPerAyam: 0,
                    totalBerat: 0,
                    nilaiDiskon: 0,
                    totalAkhir: 0
                };
            }

            const hargaInput = document.getElementById('harga_total').value;
            const harga = parseRupiah(hargaInput);
            const jumlah = parseInt(document.getElementById('jumlah_ayam').value) || 0;
            const diskon = document.querySelector('input[name="diskon"]:checked').value;

            // Berat per ayam = harga / 75 (dalam gram)
            const beratPerAyam = harga > 0 ? harga / 75 : 0;
            const totalBerat = beratPerAyam;

            // Hitung diskon
            const nilaiDiskon = diskon === '1' ? harga * 0.05 : 0;
            const totalAkhir = harga - nilaiDiskon;

            // Update display
            document.getElementById('berat-per-ayam').textContent = Math.round(beratPerAyam).toLocaleString('id-ID') + ' gram';
            document.getElementById('total-berat-calc').textContent = Math.round(totalBerat).toLocaleString('id-ID') + ' gram';
            document.getElementById('harga-sebelum').textContent = formatCurrency(harga);
            document.getElementById('nilai-diskon').textContent = formatCurrency(nilaiDiskon);
            document.getElementById('total-akhir').textContent = formatCurrency(totalAkhir);

            return {
                beratPerAyam: Math.round(beratPerAyam),
                totalBerat: Math.round(totalBerat),
                nilaiDiskon,
                totalAkhir
            };
        }

        // Show alert
        function showAlert(message, type = 'success') {
            const alertSection = document.getElementById('alert-section');
            alertSection.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => {
                alertSection.innerHTML = '';
            }, 5000);
        }

        // Event listeners
        document.getElementById('harga_total').addEventListener('input', function () {
            formatRupiahInput(this);
            calculateValues();
        });

        document.getElementById('jumlah_ayam').addEventListener('input', function() {
            validateStock();
            calculateValues();
        });

        document.querySelectorAll('input[name="diskon"]').forEach(radio => {
            radio.addEventListener('change', calculateValues);
        });

        // Form submission - Keep original Laravel submission intact
        document.getElementById('form-penjualan').addEventListener('submit', function (e) {
            if (!validateStock()) {
                e.preventDefault();
                return false;
            }

            // Keep original Laravel form submission logic
            const calculations = calculateValues();
            const harga = parseRupiah(document.getElementById('harga_total').value);

            // Update hidden fields for Laravel (keep original logic)
            document.getElementById('berat_total').value = calculations.totalBerat;
            document.getElementById('harga_asli').value = harga;
            document.getElementById('harga_total_final').value = calculations.totalAkhir;

            // Don't modify stock here - let Laravel handle it
            // showAlert added for user feedback only
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('empty-stock-modal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Set default date to today
        document.getElementById('tanggal').valueAsDate = new Date();

        // Initialize
        updateStockDisplay();
    </script>
</body>

</html>