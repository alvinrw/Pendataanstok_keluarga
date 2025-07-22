<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penjualan Toko</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #4a6741 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px;
        }

        .back-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        }

        .form-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-section h3 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 1.5rem;
            text-align: center;
            position: relative;
        }

        .form-section h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #a0a8b0;
            font-style: italic;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 30px;
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(39, 174, 96, 0.4);
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .submit-btn .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        .submit-btn.loading .loading-spinner {
            display: inline-block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            animation: slideInDown 0.5s ease;
            border-left: 5px solid;
            position: relative;
        }

        @keyframes slideInDown {
            from { 
                opacity: 0; 
                transform: translateY(-20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left-color: #27ae60;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left-color: #e74c3c;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border-left-color: #f39c12;
        }

        .alert .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .alert .close-btn:hover {
            opacity: 1;
        }

        /* Success notification with confetti effect */
        .success-celebration {
            position: relative;
            overflow: hidden;
        }

        .success-celebration::before {
            content: '🎉';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 24px;
            animation: bounce 1s ease-in-out infinite;
        }

        .success-celebration::after {
            content: '✨';
            position: absolute;
            top: -10px;
            right: 20px;
            font-size: 20px;
            animation: bounce 1s ease-in-out infinite 0.5s;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        /* Enhanced input animations */
        .form-group {
            position: relative;
        }

        .form-group input:focus ~ .input-border,
        .form-group select:focus ~ .input-border,
        .form-group textarea:focus ~ .input-border {
            transform: scaleX(1);
        }

        .input-border {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        /* Glassmorphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }

            .form-section {
                padding: 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }

            .header p {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header {
                padding: 25px;
            }
            
            .form-section {
                padding: 20px;
            }
        }

        /* Floating animation for icons */
        .floating-icon {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><span class="floating-icon">🏪</span> Input Data Penjualan Toko</h1>
            <p>Kelola transaksi penjualan dengan mudah dan efisien</p>
        </div>

        <div class="content">
            <a href="/" class="back-btn">← Kembali ke Menu Utama</a>

            <!-- Alert Section -->
            <div id="alert-section"></div>

            <!-- Form Section -->
            <div class="form-section">
                <h3>📝 Form Input Penjualan</h3>
                <form id="form-penjualan" method="POST" action="{{ route('penjualanToko.store') }}">
    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal">📅 Tanggal Pembelian:</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="total_harga">💵 Total Pemasukan:</label>
                        <input type="text" id="total_harga" name="total_harga" 
                               placeholder="Rp 0" required>
                    </div>

                    <div class="form-group">
                        <label for="catatan">📋 Catatan (Opsional):</label>
                        <textarea id="catatan" name="catatan" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">
                        <span class="loading-spinner"></span>
                        <span class="btn-text">💾 Simpan Data Penjualan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format currency dengan titik pemisah ribuan
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        // Format number input untuk rupiah
        function formatRupiahInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = formatCurrency(parseInt(value)).replace('Rp ', 'Rp ');
            } else {
                input.value = '';
            }
        }

        // Parse rupiah input to number
        function parseRupiah(rupiahString) {
            return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0;
        }

        // Show alert
        function showAlert(message, type = 'success') {
            const alertSection = document.getElementById('alert-section');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            if (type === 'success') {
                alertDiv.classList.add('success-celebration');
            }
            
            alertDiv.innerHTML = `
                ${message}
                <button class="close-btn" onclick="closeAlert(this)">&times;</button>
            `;
            
            alertSection.innerHTML = '';
            alertSection.appendChild(alertDiv);
            
            // Auto hide after 8 seconds for success, 5 seconds for others
            const hideTime = type === 'success' ? 8000 : 5000;
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, hideTime);
        }

        // Close alert manually
        function closeAlert(button) {
            const alert = button.parentNode;
            alert.parentNode.removeChild(alert);
        }

        // Check for success message in URL
        function checkForSuccessMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const message = urlParams.get('message');
            
            if (success === '1' || success === 'true') {
                const successMessage = message || 'Data penjualan berhasil disimpan! 🎉';
                showAlert(`✅ <strong>Berhasil!</strong><br>${successMessage}`, 'success');
                
                // Clear the URL parameters
                const url = new URL(window.location);
                url.searchParams.delete('success');
                url.searchParams.delete('message');
                window.history.replaceState({}, document.title, url.pathname);
                
                // Reset form
                document.getElementById('form-penjualan').reset();
                document.getElementById('tanggal').valueAsDate = new Date();
            }
        }

        // Handle form submission with loading state
        function handleFormSubmit() {
            const form = document.getElementById('form-penjualan');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            
            form.addEventListener('submit', function(e) {
                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                btnText.textContent = 'Menyimpan...';
                
                // Show processing alert
                showAlert('⏳ Sedang menyimpan data penjualan...', 'warning');
            });
        }

        // Event listeners
        document.getElementById('total_harga').addEventListener('input', function () {
            formatRupiahInput(this);
        });

        // Set default date to today
        document.getElementById('tanggal').valueAsDate = new Date();

        // Initialize on page load
        window.addEventListener('load', function() {
            // Add floating animation to form
            document.querySelector('.form-section').style.animation = 'slideInDown 0.8s ease';
            
            // Check for success message
            checkForSuccessMessage();
            
            // Handle form submission
            handleFormSubmit();
        });

        // Handle browser back/forward navigation
        window.addEventListener('popstate', function() {
            checkForSuccessMessage();
        });
    </script>
</body>

</html>