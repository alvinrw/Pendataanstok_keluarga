<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Penjualan Ayam dan Toko">
    <title>Sistem Manajemen Penjualan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1e40af;
            --primary-blue-light: #3b82f6;
            --secondary-blue: #60a5fa;
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-700: #374151;
            --gray-900: #111827;
            --success: #10b981;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--blue-50) 0%, var(--blue-100) 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--gray-900);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            color: var(--white);
            padding: 48px 32px;
            text-align: center;
            position: relative;
        }

        .logout-container {
            position: absolute;
            top: 24px;
            right: 32px;
        }

        .logout-btn {
            background: var(--white);
            color: var(--primary-blue);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .logout-btn:hover {
            background: var(--gray-50);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .header p {
            font-size: 1.125rem;
            opacity: 0.95;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 24px;
            padding: 48px 32px;
            background: var(--gray-50);
            flex-wrap: wrap;
        }

        .nav-btn {
            background: var(--white);
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 160px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .nav-btn:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .nav-btn.active {
            background: var(--primary-blue);
            color: var(--white);
        }

        .nav-btn.jadwal {
            border-color: var(--success);
            color: var(--success);
        }

        .nav-btn.jadwal:hover,
        .nav-btn.jadwal.active {
            background: var(--success);
            color: var(--white);
        }

        .options {
            display: none;
            padding: 48px 32px;
            background: var(--white);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .options.active {
            display: block;
        }

        .options h2 {
            font-size: 1.875rem;
            color: var(--gray-900);
            margin-bottom: 32px;
            text-align: center;
            font-weight: 700;
            position: relative;
            padding-bottom: 16px;
        }

        .options h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--primary-blue);
            border-radius: 2px;
        }

        .jadwal-options h2::after {
            background: var(--success);
        }

        .option-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .option-btn {
            background: var(--white);
            color: var(--gray-900);
            padding: 20px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 2px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .option-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .jadwal-options .option-btn:hover {
            border-color: var(--success);
            color: var(--success);
        }

        .option-btn .icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: var(--white);
            margin: 15% auto;
            padding: 32px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal h3 {
            color: var(--gray-900);
            margin-bottom: 16px;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .modal p {
            color: var(--gray-700);
            margin-bottom: 24px;
            font-size: 1rem;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 100px;
            font-size: 0.875rem;
        }

        .confirm-btn {
            background: var(--danger);
            color: var(--white);
        }

        .confirm-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .cancel-btn {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .cancel-btn:hover {
            background: var(--gray-300);
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .header p {
                font-size: 1rem;
            }

            .logout-container {
                position: static;
                text-align: center;
                margin-top: 16px;
            }

            .nav-buttons {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
                padding: 32px 20px;
            }

            .nav-btn {
                width: 100%;
            }

            .options {
                padding: 32px 20px;
            }

            .option-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 32px 20px;
            }

            .header h1 {
                font-size: 1.75rem;
            }

            .nav-btn {
                padding: 14px 24px;
                font-size: 1rem;
            }

            .option-btn {
                padding: 16px 20px;
                font-size: 0.9375rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logout-container">
                <button class="logout-btn" onclick="showLogoutModal()">
                    Logout
                </button>
            </div>
            <h1>Sistem Manajemen Penjualan</h1>
            <p>Kelola data penjualan toko, ayam, dan jadwal kerja dengan mudah dan efisien</p>
        </div>

        <div class="nav-buttons">
            <button class="nav-btn" onclick="showOptions('ayam')">
                Ayam
            </button>
            <button class="nav-btn" onclick="showOptions('toko')">
                Toko
            </button>
            @if(Auth::check() && trim(strtolower(Auth::user()->role)) == 'admin')
                <button class="nav-btn jadwal" onclick="showOptions('jadwal')">
                    Jadwal Alvin
                </button>
            @endif
        </div>

        <div id="ayamOptions" class="options">
            <h2>Data Penjualan Ayam</h2>
            <div class="option-buttons">
                <a href="{{ route('penjualan.form') }}" class="option-btn">
                    <span class="icon">▸</span>
                    <span>Input Data Penjualan Ayam</span>
                </a>
                <a href="{{ url('/RekapanPenjualanAyam') }}" class="option-btn">
                    <span class="icon">▸</span>
                    <span>Rekapan Penjualan Ayam</span>
                </a>
                <a href="{{ route('manajemen.kloter.index') }}" class="option-btn">
                    <span class="icon">▸</span>
                    <span>Kelola Pemeliharaan</span>
                </a>
            </div>
        </div>

        <div id="tokoOptions" class="options">
            <h2>Data Penjualan Toko</h2>
            <div class="option-buttons">
                <a href="{{ route('penjualanToko.form') }}" class="option-btn">
                    <span class="icon">▸</span>
                    <span>Input Data Penjualan Toko</span>
                </a>
                <a href="{{ route('penjualanToko.rekapan') }}" class="option-btn">
                    <span class="icon">▸</span>
                    <span>Rekapan Penjualan Toko</span>
                </a>
            </div>
        </div>

        @if(Auth::check() && trim(strtolower(Auth::user()->role)) == 'admin')
            <div id="jadwalOptions" class="options jadwal-options">
                <h2>Jadwal Kerja Alvin</h2>
                <div class="option-buttons">
                    <a href="{{ route('jadwal.input') }}" class="option-btn">
                        <span class="icon">▸</span>
                        <span>Input Jadwal Baru</span>
                    </a>
                    <a href="{{ route('jadwal.rekapan') }}" class="option-btn">
                        <span class="icon">▸</span>
                        <span>Rekapan Jadwal Alvin</span>
                    </a>
                    <a href="{{ route('transaksi.input') }}" class="option-btn">
                        <span class="icon">▸</span>
                        <span>Input Pengeluaran Baru</span>
                    </a>
                    <a href="{{ route('rekapan') }}" class="option-btn">
                        <span class="icon">▸</span>
                        <span>Rekapan Pengeluaran Alvin</span>
                    </a>
                    <a href="{{ route('akun.index') }}" class="option-btn">
                        <span class="icon">▸</span>
                        <span>Kelola Akun</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Logout</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm-btn" onclick="confirmLogout()">Ya, Logout</button>
                <button class="modal-btn cancel-btn" onclick="closeLogoutModal()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function showOptions(type) {
            const ayamOptions = document.getElementById('ayamOptions');
            const tokoOptions = document.getElementById('tokoOptions');
            const jadwalOptions = document.getElementById('jadwalOptions');
            const buttons = document.querySelectorAll('.nav-btn');

            // Remove active class from all
            if (ayamOptions) ayamOptions.classList.remove('active');
            if (tokoOptions) tokoOptions.classList.remove('active');
            if (jadwalOptions) jadwalOptions.classList.remove('active');
            buttons.forEach(btn => btn.classList.remove('active'));

            // Add active class to selected
            if (type === 'ayam' && ayamOptions) {
                ayamOptions.classList.add('active');
                buttons[0].classList.add('active');
            } else if (type === 'toko' && tokoOptions) {
                tokoOptions.classList.add('active');
                buttons[1].classList.add('active');
            } else if (type === 'jadwal' && jadwalOptions) {
                jadwalOptions.classList.add('active');
                buttons[2].classList.add('active');
            }
        }

        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        window.onclick = function (event) {
            const modal = document.getElementById('logoutModal');
            if (event.target === modal) {
                closeLogoutModal();
            }
        }
    </script>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>

</html>