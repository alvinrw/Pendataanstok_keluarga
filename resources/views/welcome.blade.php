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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 40px;
            background: #f8f9fa;
        }

        .nav-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 30px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.2);
        }

        .nav-btn:active {
            transform: translateY(-2px);
        }

        .options {
            display: none;
            padding: 20px;
        }

        .options.active {
            display: block;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8rem;
            }
            
            .nav-btn {
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
            <p>Kelola data penjualan toko dan ayam dengan mudah</p>
        </div>

        <div class="nav-buttons">
            <button class="nav-btn" onclick="showOptions('ayam')">🐔 Ayam</button>
            <button class="nav-btn" onclick="showOptions('toko')">🏪 Toko</button>
        </div>

        <div id="ayamOptions" class="options">
           
       <a href="{{ route('penjualan.form') }}" class="nav-btn">
            🐔 Input Data Penjualan Ayam
        </a>



            <a href="{{ url('/RekapanPenjualanAyam') }}" class="nav-btn">
             📋 Rekapan Penjualan Ayam
            </a>

             <a href="{{ url('/SummaryAyam') }}" class="nav-btn">
             📊 Summary Penjualan Ayam
            </a>

            
            <div id="ayamSummary" style="display:none; margin-top: 20px;">
                <p>Summary Penjualan Ayam: [Summary Data]</p>
            </div>
        </div>

        <div id="tokoOptions" class="options">
            <h2>Data Penjualan Toko</h2>
        <a href="{{ route('penjualanToko.form') }}" class="nav-btn">📊 Input Data Penjualan Toko</a>

            <button class="nav-btn" onclick="goToPage('rekap-toko')">📈 Rekapan Penjualan Toko</button>
            <button class="nav-btn" onclick="showSummary('toko')">📊 Summary Penjualan Toko</button>
            <div id="tokoSummary" style="display:none; margin-top: 20px;">
                <p>Summary Penjualan Toko: [Summary Data]</p>
            </div>
        </div>
    </div>

    <script>
        function showOptions(type) {
            document.getElementById('ayamOptions').classList.remove('active');
            document.getElementById('tokoOptions').classList.remove('active');
            document.getElementById('ayamSummary').style.display = 'none';
            document.getElementById('tokoSummary').style.display = 'none';

            if (type === 'ayam') {
                document.getElementById('ayamOptions').classList.add('active');
            } else if (type === 'toko') {
                document.getElementById('tokoOptions').classList.add('active');
            }
        }

        function showSummary(type) {
            if (type === 'ayam') {
                const summaryDiv = document.getElementById('ayamSummary');
                summaryDiv.style.display = summaryDiv.style.display === 'none' ? 'block' : 'none';
            } else if (type === 'toko') {
                const summaryDiv = document.getElementById('tokoSummary');
                summaryDiv.style.display = summaryDiv.style.display === 'none' ? 'block' : 'none';
            }
        }

        function goToPage(page) {
            // Di sini Anda bisa tambahkan logika untuk navigasi
            alert(`Navigasi ke halaman: ${page}`);
            // window.location.href = page + '.html'; // Uncomment this line for actual navigation
        }
    </script>
</body>
</html>
