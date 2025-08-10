<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Input Data Penjualan Ayam</title>
    <style>
        /* ... (CSS Anda yang sudah ada tetap sama, tidak perlu diubah) ... */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 2.2rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); }
        .header p { font-size: 1rem; opacity: 0.9; }
        .content { padding: 30px; }
        .back-btn { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-size: 1rem; cursor: pointer; margin-bottom: 20px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .back-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .stock-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; text-align: center; display: none; }
        .stock-section.empty { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.8; } 100% { opacity: 1; } }
        .stock-section h3 { font-size: 1.5rem; margin-bottom: 15px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); }
        .stock-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
        .stock-card { background: rgba(255, 255, 255, 0.2); padding: 15px; border-radius: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .stock-card h4 { font-size: 1.1rem; margin-bottom: 10px; }
        .stock-card p { font-size: 1.8rem; font-weight: bold; }
        .form-section { background: #f8f9fa; padding: 30px; border-radius: 15px; margin-bottom: 20px; }
        .form-section.disabled { opacity: 0.5; pointer-events: none; }
        .form-section h3 { color: #2c3e50; margin-bottom: 20px; font-size: 1.3rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #4facfe; }
        .form-group input.error { border-color: #e74c3c; background-color: #fdf2f2; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .discount-section { background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .discount-section h4 { color: #856404; margin-bottom: 15px; }
        .radio-group { display: flex; gap: 20px; align-items: center; }
        .radio-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal; }
        .radio-group input[type="radio"] { width: auto; }
        .calculation-section { background: #e8f5e8; border: 1px solid #c3e6cb; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .calculation-section h4 { color: #155724; margin-bottom: 15px; }
        .calc-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 5px 0; }
        .calc-row.total { border-top: 2px solid #155724; padding-top: 10px; font-weight: bold; font-size: 1.1rem; }
        .submit-btn { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: all 0.3s ease; width: 100%; margin-top: 20px; }
        .submit-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); }
        .submit-btn:disabled { background: #95a5a6; cursor: not-allowed; transform: none; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐔 Input Data Penjualan Ayam</h1>
            <p>Kelola transaksi penjualan ayam dengan mudah</p>
        </div>

        <div class="content">
            <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>


            <div class="stock-section" style="display: block; background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); margin-bottom: 20px;">
        <h3>Total Stok Gabungan Saat Ini</h3>
        <div class="stock-info">
            <div class="stock-card">
                <h4>Semua Kloter</h4>
                <p>{{ $grandTotalStock }} Ekor</p>
            </div>
        </div>
    </div>
            <!-- Kloter Selector Section -->
            <div class="form-section" style="margin-bottom: 20px;">
                <h3>1. Pilih Kloter Terlebih Dahulu</h3>
                <div class="form-group">
                    <label for="kloter-select">Pilih Kloter yang Akan Dijual:</label>
                    <select id="kloter-select" class="form-group select">
                        <option value="">-- Pilih Kloter --</option>
                        @foreach ($kloters as $kloter)
                            <option
                                value="{{ $kloter->id }}"
                                data-stock="{{ $kloter->stok_tersedia }}"
                                @if ($kloter->stok_tersedia <= 0) disabled @endif
                            >
                                {{ $kloter->nama_kloter }}
                                @if ($kloter->stok_tersedia > 0)
                                    (Stok: {{ $kloter->stok_tersedia }})
                                @else
                                    (Stok Habis)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Stock Section (Initially Hidden) -->
            <div class="stock-section" id="stock-section">
                <h3>📊 Info Stok Kloter</h3>
                <div class="stock-info">
                    <div class="stock-card">
                        <h4 id="kloter-name-display">Nama Kloter</h4>
                        <p id="total-ayam">0</p>
                    </div>
                </div>
            </div>

            <!-- Alert Section -->
            <div id="alert-section"></div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <!-- Form Section (Disabled by default) -->
            <div class="form-section disabled" id="form-section">
                <h3>📝 2. Form Input Penjualan</h3>
                <form id="form-penjualan" method="POST" action="{{ route('penjualan.store') }}">
                    @csrf
                    <input type="hidden" name="kloter_id" id="kloter_id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Pembelian:</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pembeli">Nama Pembeli:</label>
                            <input type="text" id="nama_pembeli" name="nama_pembeli" placeholder="Masukkan nama pembeli" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jumlah_ayam">Jumlah Ayam Dipesan:</label>
                            <input type="number" id="jumlah_ayam" name="jumlah_ayam_dibeli" min="1" placeholder="0" required>
                            <small id="stock-warning" style="color: #e74c3c; font-size: 0.9rem; display: none;"></small>
                        </div>
                        <div class="form-group">
                            <label for="harga_total_input">Harga Total:</label>
                            <input type="text" id="harga_total_input" placeholder="Rp 0" required>
                        </div>
                    </div>

                    <div class="discount-section">
                        <h4>🎯 Diskon 5%</h4>
                        <div class="radio-group">
                            <label><input type="radio" name="diskon" value="1" id="diskon-ya"> Ya, berikan diskon 5%</label>
                            <label><input type="radio" name="diskon" value="0" id="diskon-tidak" checked> Tidak ada diskon</label>
                        </div>
                    </div>

                    <div class="calculation-section">
                        <h4>🔢 Perhitungan Otomatis</h4>
                        <div class="calc-row"><span>Berat per Ayam (Harga ÷ 75):</span><span id="berat-per-ayam">0 gram</span></div>
                        <div class="calc-row"><span>Total Berat:</span><span id="total-berat-calc">0 gram</span></div>
                        <div class="calc-row"><span>Harga Sebelum Diskon:</span><span id="harga-sebelum">Rp 0</span></div>
                        <div class="calc-row"><span>Diskon (5%):</span><span id="nilai-diskon">Rp 0</span></div>
                        <div class="calc-row total"><span>Total Akhir:</span><span id="total-akhir">Rp 0</span></div>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">💾 Simpan Data Penjualan</button>

                    <!-- Hidden fields untuk dikirim ke Controller -->
                    <input type="hidden" name="berat_total" id="berat_total_hidden">
                    <input type="hidden" name="harga_asli" id="harga_asli_hidden">
                    <input type="hidden" name="harga_total" id="harga_total_final_hidden">
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ELEMEN-ELEMEN PENTING
        const kloterSelect = document.getElementById('kloter-select');
        const stockSection = document.getElementById('stock-section');
        const formSection = document.getElementById('form-section');
        const totalAyamDisplay = document.getElementById('total-ayam');
        const kloterNameDisplay = document.getElementById('kloter-name-display');
        const hiddenKloterId = document.getElementById('kloter_id');
        const jumlahAyamInput = document.getElementById('jumlah_ayam');
        const hargaTotalInput = document.getElementById('harga_total_input');
        const submitBtn = document.getElementById('submit-btn');
        const form = document.getElementById('form-penjualan');

        let currentStock = 0;
        let selectedKloterName = '';

        // EVENT LISTENER UTAMA: SAAT KLOTER DIPILIH
        kloterSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kloterId = selectedOption.value;

            if (kloterId) {
                currentStock = parseInt(selectedOption.dataset.stock) || 0;
                selectedKloterName = selectedOption.text.split('(')[0].trim();

                totalAyamDisplay.textContent = currentStock;
                kloterNameDisplay.textContent = selectedKloterName;
                hiddenKloterId.value = kloterId;

                stockSection.style.display = 'block';
                formSection.classList.remove('disabled');

                resetFormInputs();
                validateStock();
            } else {
                currentStock = 0;
                stockSection.style.display = 'none';
                formSection.classList.add('disabled');
                hiddenKloterId.value = '';
                resetFormInputs();
            }
        });

        function resetFormInputs() {
            form.reset();
            document.getElementById('tanggal').valueAsDate = new Date();
            calculateValues();
        }
        
        function formatCurrency(amount) { return 'Rp ' + Math.round(amount).toLocaleString('id-ID'); }
        function formatRupiahInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            input.value = value ? 'Rp ' + parseInt(value).toLocaleString('id-ID') : '';
        }
        function parseRupiah(rupiahString) { return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0; }

        function validateStock() {
            const jumlah = parseInt(jumlahAyamInput.value) || 0;
            const stockWarning = document.getElementById('stock-warning');
            
            if (currentStock === 0) {
                 submitBtn.disabled = true;
                 return false;
            }

            if (jumlah > currentStock) {
                jumlahAyamInput.classList.add('error');
                stockWarning.textContent = `❌ Stok tidak cukup! Tersedia: ${currentStock} ayam`;
                stockWarning.style.display = 'block';
                submitBtn.disabled = true;
                return false;
            } else {
                jumlahAyamInput.classList.remove('error');
                stockWarning.style.display = 'none';
                submitBtn.disabled = false;
                return true;
            }
        }

        function calculateValues() {
            if (!validateStock()) {
                // Reset tampilan kalkulasi
                document.getElementById('berat-per-ayam').textContent = '0 gram';
                document.getElementById('total-berat-calc').textContent = '0 gram';
                document.getElementById('harga-sebelum').textContent = 'Rp 0';
                document.getElementById('nilai-diskon').textContent = 'Rp 0';
                document.getElementById('total-akhir').textContent = 'Rp 0';
                return null;
            }

            const harga = parseRupiah(hargaTotalInput.value);
            const jumlah = parseInt(jumlahAyamInput.value) || 0;
            const diskon = document.querySelector('input[name="diskon"]:checked').value === '1';

            const beratPerAyam = harga > 0 ? harga / 75 : 0;
            const totalBerat = beratPerAyam ; // Total berat = berat per ayam * jumlah ayam
            const hargaAsli = harga ; // Harga asli = harga per ayam * jumlah ayam

            const nilaiDiskon = diskon ? hargaAsli * 0.05 : 0;
            const totalAkhir = hargaAsli - nilaiDiskon;

            document.getElementById('berat-per-ayam').textContent = Math.round(beratPerAyam).toLocaleString('id-ID') + ' gram';
            document.getElementById('total-berat-calc').textContent = Math.round(totalBerat).toLocaleString('id-ID') + ' gram';
            document.getElementById('harga-sebelum').textContent = formatCurrency(hargaAsli);
            document.getElementById('nilai-diskon').textContent = formatCurrency(nilaiDiskon);
            document.getElementById('total-akhir').textContent = formatCurrency(totalAkhir);

            return { totalBerat: Math.round(totalBerat), hargaAsli: Math.round(hargaAsli), totalAkhir: Math.round(totalAkhir) };
        }

        hargaTotalInput.addEventListener('input', () => { formatRupiahInput(hargaTotalInput); calculateValues(); });
        jumlahAyamInput.addEventListener('input', calculateValues);
        document.querySelectorAll('input[name="diskon"]').forEach(radio => radio.addEventListener('change', calculateValues));

        form.addEventListener('submit', function(e) {
            const calculations = calculateValues();
            if (!calculations || !validateStock()) {
                e.preventDefault();
                alert('Silakan periksa kembali input Anda. Jumlah ayam mungkin melebihi stok.');
                return;
            }

            document.getElementById('berat_total_hidden').value = calculations.totalBerat;
            document.getElementById('harga_asli_hidden').value = calculations.hargaAsli;
            document.getElementById('harga_total_final_hidden').value = calculations.totalAkhir;
        });

        document.getElementById('tanggal').valueAsDate = new Date();
    });
    </script>
</body>
</html>