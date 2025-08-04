<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Penjualan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            padding: 20px;
            animation: backgroundShift 10s ease-in-out infinite alternate;
        }

        @keyframes backgroundShift {
            0% { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); }
            100% { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 50%, #667eea 100%); }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15), 0 0 0 1px rgba(255,255,255,0.2);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            font-weight: 700;
            letter-spacing: -1px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.3rem;
            opacity: 0.9;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 60px 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            flex-wrap: wrap;
        }

        .nav-buttons::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="%23667eea" opacity="0.1"/></svg>') repeat;
            background-size: 50px 50px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-50px, -50px); }
        }

        .nav-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 25px 40px;
            border-radius: 20px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            overflow: hidden;
            min-width: 180px;
            z-index: 1;
        }

        .nav-btn.jadwal {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
        }

        .nav-btn.jadwal:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            box-shadow: 0 20px 40px rgba(40, 167, 69, 0.6);
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .nav-btn:hover::before {
            left: 100%;
        }

        .nav-btn:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.6);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .nav-btn:active {
            transform: translateY(-3px) scale(1.02);
        }

        .options {
            display: none;
            padding: 40px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            min-height: 300px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .options.active {
            display: block;
        }

        .options h2 {
            font-size: 2.2rem;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }

        .options h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .jadwal-options h2::after {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .option-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .option-btn {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #2c3e50;
            padding: 20px 25px;
            border-radius: 15px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .option-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: -1;
        }

        .jadwal-options .option-btn::before {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .option-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
            color: white;
        }

        .jadwal-options .option-btn:hover {
            border-color: #28a745;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        .option-btn:hover::before {
            left: 0;
            opacity: 1;
        }

        .option-btn .icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .option-btn:hover .icon {
            transform: scale(1.2);
        }

        .summary-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-top: 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            display: none;
            animation: slideDown 0.5s ease-in-out;
        }

        .jadwal-summary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .summary-box p {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.2rem;
            }
            
            .header p {
                font-size: 1.1rem;
            }
            
            .nav-buttons {
                flex-direction: column;
                align-items: center;
                gap: 20px;
                padding: 40px 20px;
            }
            
            .nav-btn {
                width: 100%;
                max-width: 300px;
                padding: 20px;
                font-size: 1.1rem;
            }
            
            .options {
                padding: 30px 20px;
            }
            
            .option-buttons {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .container {
                border-radius: 20px;
            }
            
            .header {
                padding: 40px 20px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .nav-btn {
                padding: 18px 25px;
                font-size: 1rem;
                letter-spacing: 1px;
            }
            
            .option-btn {
                padding: 15px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏪 Sistem Manajemen Penjualan</h1>
            <p>Kelola data penjualan toko, ayam, dan jadwal kerja dengan mudah dan efisien</p>
        </div>

        <div class="nav-buttons">
            <button class="nav-btn" onclick="showOptions('ayam')">
                🐔 Ayam
            </button>
            <button class="nav-btn" onclick="showOptions('toko')">
                🏪 Toko
            </button>
            <button class="nav-btn jadwal" onclick="showOptions('jadwal')">
                📅 Jadwal Alvin
            </button>
        </div>

        <div id="ayamOptions" class="options">
            <h2>📊 Data Penjualan Ayam</h2>
            <div class="option-buttons">
                <a href="{{ route('penjualan.form') }}" class="option-btn">
                    <span class="icon">🐔</span>
                    <span>Input Data Penjualan Ayam</span>
                </a>

                <a href="{{ url('/RekapanPenjualanAyam') }}" class="option-btn">
                    <span class="icon">📋</span>
                    <span>Rekapan Penjualan Ayam</span>
                </a>

                <a href="{{ url('/SummaryAyam') }}" class="option-btn">
                    <span class="icon">📊</span>
                    <span>Summary Penjualan Ayam</span>
                </a>
            </div>
            
            <div id="ayamSummary" class="summary-box">
                <p>📈 Summary Penjualan Ayam: [Summary Data akan ditampilkan di sini]</p>
            </div>
        </div>

        <div id="tokoOptions" class="options">
            <h2>🏪 Data Penjualan Toko</h2>
            <div class="option-buttons">
                <a href="{{ route('penjualanToko.form') }}" class="option-btn">
                    <span class="icon">📊</span>
                    <span>Input Data Penjualan Toko</span>
                </a>

                <a href="{{ route('penjualanToko.rekapan') }}" class="option-btn">
                    <span class="icon">📈</span>
                    <span>Rekapan Penjualan Toko</span>
                </a>
            </div>

            <div id="tokoSummary" class="summary-box">
                <p>📈 Summary Penjualan Toko: [Summary Data akan ditampilkan di sini]</p>
            </div>
        </div>

<div id="jadwalOptions" class="options jadwal-options">
    <h2>📅 Jadwal Kerja Alvin</h2>
    <div class="option-buttons">
        
        <a href="{{ route('jadwal.input') }}" class="option-btn">
            <span class="icon">➕</span>
            <span>Input Jadwal Baru</span>
        </a>

        <a href="{{ route('jadwal.rekapan') }}" class="option-btn">
            <span class="icon">📋</span>
            <span>Rekapan Jadwal Alvin</span>
        </a>

        <a href="{{ route('transaksi.input') }}" class="option-btn">
            <span class="icon">➕</span>
            <span>Input Pengeluaran Baru</span>
        </a>

        <a href="{{ route('rekapan') }}" class="option-btn">
            <span class="icon">📋</span>
            <span>Rekapan Pengeluaran Alvin</span>
        </a>
           <a href="{{ route('akun.index') }}" class="option-btn">
            <span class="icon">(📋)</span>
            <span>kelola akun</span>
        </a>


    </div>

    <div id="jadwalSummary" class="summary-box jadwal-summary">
        <p>📈 Summary Jadwal: [Summary Data akan ditampilkan di sini]</p>
    </div>
</div>


</form>
-->
    </div>

    <script>
        function showOptions(type) {
            // Hide all options
            document.getElementById('ayamOptions').classList.remove('active');
            document.getElementById('tokoOptions').classList.remove('active');
            document.getElementById('jadwalOptions').classList.remove('active');
            document.getElementById('ayamSummary').style.display = 'none';
            document.getElementById('tokoSummary').style.display = 'none';
            document.getElementById('jadwalSummary').style.display = 'none';

            // Show selected option with animation
            if (type === 'ayam') {
                setTimeout(() => {
                    document.getElementById('ayamOptions').classList.add('active');
                }, 100);
            } else if (type === 'toko') {
                setTimeout(() => {
                    document.getElementById('tokoOptions').classList.add('active');
                }, 100);
            } else if (type === 'jadwal') {
                setTimeout(() => {
                    document.getElementById('jadwalOptions').classList.add('active');
                    document.getElementById('jadwalSummary').style.display = 'block';
                }, 100);
            }
        }

        function showSummary(type) {
            if (type === 'ayam') {
                const summaryDiv = document.getElementById('ayamSummary');
                if (summaryDiv.style.display === 'none' || summaryDiv.style.display === '') {
                    summaryDiv.style.display = 'block';
                } else {
                    summaryDiv.style.display = 'none';
                }
            } else if (type === 'toko') {
                const summaryDiv = document.getElementById('tokoSummary');
                if (summaryDiv.style.display === 'none' || summaryDiv.style.display === '') {
                    summaryDiv.style.display = 'block';
                } else {
                    summaryDiv.style.display = 'none';
                }
            }
        }

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transform = 'translateY(20px)';
            document.body.style.transition = 'all 0.8s ease-in-out';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
                document.body.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>