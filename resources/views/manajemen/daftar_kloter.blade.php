<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kloter Pemeliharaan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #34495e;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        }

        .back-btn {
            color: #556080;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-btn:hover {
            color: #2563eb;
        }

        .kloter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }

        .kloter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .kloter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            padding: 20px;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border-bottom: 4px solid #2563eb;
        }

        .card-header h3 {
            font-size: 1.3rem;
            margin: 0;
        }

        .card-body {
            padding: 25px;
            flex-grow: 1;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item span {
            color: #5a6a7b;
        }

        .info-item strong {
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .card-footer {
            background-color: #f8f9fa;
            padding: 15px 25px;
            border-top: 1px solid #e9ecef;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9em;
            font-weight: 500;
            border-radius: 6px;
        }

        .btn-blue {
            background: #3498db;
        }

        .btn-blue:hover {
            background: #2980b9;
        }

        .btn-red {
            background: #e74c3c;
        }

        .btn-red:hover {
            background: #c0392b;
        }

        .btn-orange {
            background: #f39c12;
        }

        .btn-orange:hover {
            background: #e67e22;
        }

        .btn-green {
            background: #2ecc71;
        }

        .btn-green:hover {
            background: #27ae60;
        }

        .no-data {
            background: white;
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* SweetAlert2 Modal Styles */
        .swal2-popup {
            width: 85% !important;
            max-width: 950px !important;
            border-radius: 15px !important;
        }

        .modal-content-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 25px;
            text-align: left;
        }

        .modal-card {
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fdfdfd;
        }

        .modal-card h3 {
            margin-top: 0;
            font-size: 1.3em;
            color: #2c3e50;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .modal-rekapan-table {
            width: 100%;
            font-size: 0.9em;
        }

        .modal-rekapan-table th {
            background: #f1f3f5;
            padding: 8px;
        }

        .modal-rekapan-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #f2f2f2;
        }

        .modal-rekapan-table tr:last-child td {
            border-bottom: none;
        }

        .modal-summary-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
            border-top: 3px solid #2563eb;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 1.1em;
            margin-bottom: 12px;
            align-items: center;
        }

        .summary-item:last-child {
            margin-bottom: 0;
        }

        .summary-item span {
            font-weight: 500;
        }

        .summary-item strong {
            font-weight: bold;
            font-size: 1.2em;
            color: #2c3e50;
        }

        .editable-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
        }

        /* Tablet (≤1024px) */
        @media (max-width: 1024px) {
            .header h1 {
                font-size: 2rem;
            }

            .kloter-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }

            .card-body {
                padding: 20px;
            }

            .modal-content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* Mobile (≤600px) */
        @media (max-width: 600px) {
            .summary-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 3px;
            }

            .summary-item span {
                font-size: 0.9em;
            }

            .summary-item strong {
                font-size: 1.1em;
                word-break: break-word;
            }

            .modal-summary-box {
                padding: 15px;
            }

            .modal-rekapan-table {
                font-size: 0.7em;
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .modal-rekapan-table th,
            .modal-rekapan-table td {
                padding: 4px 2px;
                font-size: 0.85em;
            }

            /* Fix untuk Analisis Keuntungan di mobile */
            .modal-card {
                font-size: 0.9em;
            }

            .modal-card h3 {
                font-size: 1.2em !important;
                word-break: break-word;
            }

            /* Fix untuk angka yang panjang */
            .modal-card strong {
                word-break: break-word;
                display: inline-block;
                max-width: 100%;
            }

            /* Fix untuk Rasio Performa */
            .modal-card>div {
                font-size: 0.85em;
            }

            /* Fix untuk tabel yang terlalu lebar */
            .modal-content-grid {
                gap: 15px;
            }

            /* Pastikan angka tidak terpotong */
            div[style*="display: flex"] {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Kloter</h1>
        </div>

        <div class="header-actions">
            <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>
            <button type="button" class="btn btn-primary-gradient" onclick="openCreateKloterModal()">+ Tambah Kloter
                Baru</button>
        </div>

        @if(session('success'))
            <p style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </p>
        @endif
        @if ($errors->any())
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 20px;">
                <strong>Error!</strong>
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="kloter-grid">
            @forelse ($kloters as $kloter)
                <div class="kloter-card">
                    <div class="card-header">
                        <h3>{{ $kloter->nama_kloter }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span>Sisa di Kandang</span>
                            <strong style="color: #007bff;">{{ $kloter->sisa_ayam_hidup }} ekor</strong>
                        </div>
                        <div class="info-item">
                            <span>Stok Siap Jual</span>
                            <strong style="color: #28a745;">{{ $kloter->stok_tersedia }} ekor</strong>
                        </div>
                        <div class="info-item">
                            <span> DOC Awal</span>
                            <strong>{{ $kloter->jumlah_doc }} ekor</strong>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="actions">
                            <button class="btn btn-sm btn-blue" onclick="openDetailModal({{ $kloter->id }})">Detail &
                                Aksi</button>
                            <form action="{{ route('manajemen.kloter.destroy', $kloter->id) }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin menghapus kloter ini? Semua data terkait (penjualan, pengeluaran, dll) akan hilang secara permanen.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-red">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <h3>Belum ada data kloter.</h3>
                    <p>Silakan buat yang baru untuk memulai.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>

        function formatNumberInput(inputElem) {
            let value = inputElem.value;
            // Hapus semua karakter kecuali angka
            let numberString = value.replace(/[^0-9]/g, '');
            if (numberString) {
                // Format ulang dengan pemisah ribuan Indonesia (titik)
                let formatted = parseInt(numberString, 10).toLocaleString('id-ID');
                inputElem.value = formatted;
            } else {
                inputElem.value = '';
            }
        }


        function openCreateKloterModal() {
            Swal.fire({
                title: 'Tambah Kloter Baru',
                html: `
                <form id="form-create-kloter" action="{{ route('manajemen.kloter.store') }}" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!-- Input tersembunyi untuk nilai asli -->
                    <input type="hidden" name="harga_beli_doc" id="harga_beli_doc_real">

                    <label style="display: block; margin-bottom: 5px;">Nama Kloter</label>
                    <input type="text" name="nama_kloter" class="swal2-input" placeholder="Contoh: Kloter Agustus 2025" required>
                    
                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Tanggal Ayam Masuk</label>
                    <input type="date" name="tanggal_mulai" class="swal2-input" value="{{ date('Y-m-d') }}" required>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">DOC Awal (Jumlah Ayam)</label>
                    <input type="number" name="jumlah_doc" class="swal2-input" placeholder="Contoh: 100" required min="1">

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Total Harga Beli DOC (Wajib)</label>
                    <input id="harga_beli_doc_formatted" type="text" class="swal2-input" placeholder="Contoh: 600.000 (untuk semua DOC)" required oninput="formatNumberInput(this)">
                </form>
            `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Kloter',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                preConfirm: () => {
                    // Ambil nilai yang sudah diformat, hapus titiknya, lalu kirim
                    const formattedInput = document.getElementById('harga_beli_doc_formatted');
                    const realInput = document.getElementById('harga_beli_doc_real');
                    realInput.value = formattedInput.value.replace(/\./g, '');
                    document.getElementById('form-create-kloter').submit();
                }
            });
        }


        async function openDetailModal(kloterId) {
            Swal.fire({ title: 'Memuat data kloter...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

            try {
                const response = await fetch(`/manajemen-kloter/${kloterId}/detail-json`);
                if (!response.ok) throw new Error('Gagal mengambil data dari server.');

                const { kloter, rekapan, analytics } = await response.json();

                const createHistoryHtml = (items, type) => {
                    if (items.length === 0) return '<tr><td colspan="3" style="text-align:center; padding:10px;">Belum ada data.</td></tr>';
                    return items.map(item => {
                        const date = new Date(item.tanggal_panen || item.tanggal_kematian).toLocaleDateString('id-ID');
                        const amount = item.jumlah_panen || item.jumlah_mati;
                        const routeName = type === 'panen' ? 'panen' : 'kematian';
                        return `<tr>
                        <td>${date}</td>
                        <td>${amount} ekor</td>
                        <td>
                            <form action="/${routeName}/${item.id}" method="POST" onsubmit="return confirm('Hapus data ${type} ini?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-red btn-sm" style="padding: 6px 10px; font-size: 1.1em;" title="Hapus">×</button>
                            </form>
                        </td>
                    </tr>`;
                    }).join('');
                };

                const pengeluaranHtml = kloter.pengeluarans.length === 0 ? '<tr><td colspan="5" style="text-align:center; padding:10px;">Belum ada data.</td></tr>' :
                    kloter.pengeluarans.map(item => `<tr>
                    <td>${item.kategori}</td>
                    <td>${item.jumlah_pakan_kg ? item.jumlah_pakan_kg + ' Kg' : '-'}</td>
                    <td>Rp ${Number(item.jumlah_pengeluaran).toLocaleString('id-ID')}</td>
                    <td>
                        <button class="btn btn-orange btn-sm" style="padding: 6px 10px; font-size: 1.1em; margin-right: 5px;" onclick="openEditPengeluaranModal(${item.id}, '${item.kategori}', ${item.jumlah_pengeluaran}, '${item.tanggal_pengeluaran}', '${item.catatan || ''}', ${item.jumlah_pakan_kg || 0})" title="Edit">✎</button>
                        <form action="/pengeluaran/${item.id}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm" style="padding: 6px 10px; font-size: 1.1em;" title="Hapus">×</button>
                        </form>
                    </td>
                </tr>`).join('');

                const modalHtml = `
                <div class="modal-content-grid">
                    <div class="modal-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border: 1px solid #e0e7ff;">
                        <h3 style="color: #1e40af; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #dbeafe;">Manajemen Data</h3>
                        <div class="editable-item"><p><strong>DOC Awal:</strong> ${kloter.jumlah_doc} ekor</p> <button class="btn btn-sm btn-orange" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; padding: 4px 10px; border-radius: 6px; font-weight: 500;" onclick="openEditDocModal(${kloter.id}, ${kloter.jumlah_doc})" title="Edit">Edit</button></div>
                        <div class="editable-item"><p><strong>Tgl Mulai:</strong> ${new Date(kloter.tanggal_mulai).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</p> <button class="btn btn-sm btn-orange" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; padding: 4px 10px; border-radius: 6px; font-weight: 500;" onclick="openEditTanggalModal(${kloter.id}, '${kloter.tanggal_mulai}')" title="Edit">Edit</button></div>
                        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none; font-weight: 500; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);" onclick="openKematianModal(${kloter.id})">Input Kematian</button>
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none; font-weight: 500; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);" onclick="openPengeluaranModal(${kloter.id})">Input Pengeluaran</button>
                        <button class="btn btn-green" style="width:100%; margin-bottom:10px; background: linear-gradient(135deg, #10b981, #059669); border: none; font-weight: 500; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);" onclick="openPanenModal(${kloter.id}, ${rekapan.sisa_ayam})">Input Panen</button>
                        <a href="/export/kloter/${kloter.id}/csv" class="btn btn-orange" style="width:100%; display:block; text-align:center; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; font-weight: 500; padding: 12px; border-radius: 8px; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); text-decoration: none;">📥 Download Data CSV</a>
                    </div>
                    <div class="modal-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border: 1px solid #e0e7ff;">
                        <h3 style="color: #1e40af; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #dbeafe;">Riwayat Data</h3>
                        <strong style="font-size: 0.9em;">Riwayat Panen:</strong>
                        <div style="max-height: 120px; overflow-y: auto; margin-bottom: 10px; border: 1px solid #eee; border-radius: 5px;">
                            <table class="modal-rekapan-table"><thead><tr><th>Tanggal</th><th>Jumlah</th><th>Aksi</th></tr></thead><tbody>${createHistoryHtml(kloter.panens, 'panen')}</tbody></table>
                        </div>
                        <strong style="font-size: 0.9em;">Riwayat Kematian:</strong>
                        <div style="max-height: 120px; overflow-y: auto; margin-bottom: 10px; border: 1px solid #eee; border-radius: 5px;">
                            <table class="modal-rekapan-table"><thead><tr><th>Tanggal</th><th>Jumlah</th><th>Aksi</th></tr></thead><tbody>${createHistoryHtml(kloter.kematian_ayams, 'kematian')}</tbody></table>
                        </div>
                        <strong style="font-size: 0.9em;">Riwayat Pengeluaran:</strong>
                        <div style="max-height: 120px; overflow-y: auto; border: 1px solid #eee; border-radius: 5px;">
                            <table class="modal-rekapan-table"><thead><tr><th>Kategori</th><th>Pakan</th><th>Harga</th><th>Aksi</th></tr></thead><tbody>${pengeluaranHtml}</tbody></table>
                        </div>
                    </div>
                </div>
                
                <!-- BUSINESS ANALYTICS SECTION -->
                <div class="modal-content-grid" style="margin-top: 20px;">
                    <!-- Analisis Keuntungan Card -->
                    <div class="modal-card" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3); border-radius: 12px;">
                        <h3 style="color: white; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid rgba(255,255,255,0.2);">Analisis Keuntungan</h3>
                        <div style="margin: 15px 0; font-size: 0.95em;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 5px;">
                                <span style="opacity: 0.9;">Total Pengeluaran:</span>
                                <strong style="word-break: break-word;">Rp ${(analytics.breakdown_pengeluaran.DOC.nominal + analytics.breakdown_pengeluaran.Pakan.nominal + analytics.breakdown_pengeluaran.Obat.nominal + analytics.breakdown_pengeluaran['Listrik/Air'].nominal + analytics.breakdown_pengeluaran['Tenaga Kerja'].nominal + analytics.breakdown_pengeluaran['Pemeliharaan Kandang'].nominal + analytics.breakdown_pengeluaran.Lainnya.nominal).toLocaleString('id-ID')}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.3); flex-wrap: wrap; gap: 5px;">
                                <span style="opacity: 0.9;">Total Pemasukan:</span>
                                <strong style="word-break: break-word;">Rp ${Number(analytics.total_pemasukan).toLocaleString('id-ID')}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.2); padding: 12px; border-radius: 8px; flex-wrap: wrap; gap: 10px;">
                                <span style="font-size: 1.1em; font-weight: 600;">Keuntungan Bersih:</span>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.6em; font-weight: bold; word-break: break-word;">Rp ${analytics.keuntungan_bersih.toLocaleString('id-ID')}</div>
                                    <small style="opacity: 0.9; white-space: nowrap;">Margin: ${analytics.margin_keuntungan}%</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rasio Performa Card -->
                    <div class="modal-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border: 1px solid #e0e7ff;">
                        <h3 style="color: #1e40af; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #dbeafe;">Metrik Performa</h3>
                        <div style="margin: 15px 0;">
                            <!-- FCR with Details -->
                            <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span style="font-weight: 600; color: #1e40af;">FCR (Rasio Konversi Pakan)</span>
                                    <strong style="font-size: 1.3em; color: ${analytics.fcr <= 1.7 ? '#10b981' : analytics.fcr <= 2.0 ? '#f59e0b' : '#ef4444'};">
                                        ${analytics.fcr > 0 ? analytics.fcr : '-'}
                                    </strong>
                                </div>
                                
                                <!-- FCR Breakdown Details -->
                                <div style="background: white; padding: 10px; border-radius: 6px; margin-top: 8px; font-size: 0.9em;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="color: #666;">📦 Total Pakan:</span>
                                        <strong>${rekapan.total_pakan_kg.toLocaleString('id-ID')} Kg</strong>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="color: #666;">Total Berat Hidup:</span>
                                        <strong>${(analytics.total_berat_terjual / 0.8).toFixed(2)} Kg</strong>
                                    </div>
                                    <div style="border-top: 1px solid #e5e7eb; margin: 8px 0; padding-top: 8px;">
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #666;">Rumus:</span>
                                            <span style="font-family: monospace; font-size: 0.85em;">${rekapan.total_pakan_kg} ÷ ${(analytics.total_berat_terjual / 0.8).toFixed(2)} = ${analytics.fcr}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Margin Keuntungan -->
                            <div style="margin-bottom: 15px; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-weight: 600; color: #1e40af;">Margin Keuntungan</span>
                                    <strong style="font-size: 1.3em; color: ${analytics.margin_keuntungan >= 25 ? '#10b981' : analytics.margin_keuntungan >= 15 ? '#f59e0b' : '#ef4444'};">
                                        ${analytics.margin_keuntungan}%
                                    </strong>
                                </div>
                            </div>
                            
                            <!-- Mortality Rate -->
                            <div style="padding: 12px; background: #f8f9fa; border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-weight: 600; color: #1e40af;">Tingkat Kematian</span>
                                    <strong style="font-size: 1.3em; color: ${analytics.mortality_rate <= 5 ? '#10b981' : analytics.mortality_rate <= 10 ? '#f59e0b' : '#ef4444'};">
                                        ${analytics.mortality_rate}%
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Breakdown Pengeluaran Detail (Updated with 6 categories) -->
                <div class="modal-card" style="margin-top: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15); border: 1px solid #e0e7ff;">
                    <h3 style="color: #1e40af; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #dbeafe;">Rincian Pengeluaran</h3>
                    <div style="margin: 15px 0;">
                        ${Object.entries(analytics.breakdown_pengeluaran).map(([kategori, data]) => {
                    if (data.nominal === 0) return '';
                    const icons = {
                        'DOC': '🐣',
                        'Pakan': '🌾',
                        'Obat': '💊',
                        'Listrik/Air': '💡',
                        'Tenaga Kerja': '👷',
                        'Pemeliharaan Kandang': '🔧',
                        'Lainnya': '📦'
                    };
                    const colors = {
                        'DOC': '#f97316',
                        'Pakan': '#10b981',
                        'Obat': '#3b82f6',
                        'Listrik/Air': '#f59e0b',
                        'Tenaga Kerja': '#8b5cf6',
                        'Pemeliharaan Kandang': '#ef4444',
                        'Lainnya': '#6b7280'
                    };
                    return `
                                <div style="margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="font-weight: 500;">${kategori}</span>
                                        <strong>${data.persentase}%</strong>
                                    </div>
                                    <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                        <div style="background: ${colors[kategori]}; height: 100%; width: ${data.persentase}%;"></div>
                                    </div>
                                    <small style="color: #666;">Rp ${data.nominal.toLocaleString('id-ID')}</small>
                                </div>
                            `;
                }).join('')}
                    </div>
                </div>

                <!-- KESIMPULAN PERFORMA KLOTER -->
                <div class="modal-card" style="margin-top: 20px; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white;">
                    <h3 style="color: white; border-bottom-color: rgba(255,255,255,0.3);">Kesimpulan Performa Kloter</h3>
                    <div style="margin: 15px 0; font-size: 0.95em;">
                        ${(() => {
                        // Calculate overall performance score
                        let score = 0;
                        let issues = [];
                        let strengths = [];

                        // FCR Analysis
                        if (analytics.fcr > 0) {
                            if (analytics.fcr <= 1.7) {
                                score += 35;
                                strengths.push('Efisiensi pakan sangat baik (FCR ' + analytics.fcr + ')');
                            } else if (analytics.fcr <= 2.0) {
                                score += 25;
                                strengths.push('Efisiensi pakan normal (FCR ' + analytics.fcr + ')');
                            } else {
                                score += 10;
                                issues.push('FCR tinggi (' + analytics.fcr + '), perlu optimasi pakan');
                            }
                        } else {
                            issues.push('Data FCR belum tersedia (belum ada penjualan atau input pakan)');
                        }

                        // Margin Analysis
                        if (analytics.margin_keuntungan >= 25) {
                            score += 35;
                            strengths.push('Margin keuntungan sangat bagus (' + analytics.margin_keuntungan + '%)');
                        } else if (analytics.margin_keuntungan >= 15) {
                            score += 25;
                            strengths.push('Margin keuntungan normal (' + analytics.margin_keuntungan + '%)');
                        } else if (analytics.margin_keuntungan > 0) {
                            score += 10;
                            issues.push('Margin keuntungan rendah (' + analytics.margin_keuntungan + '%), perlu evaluasi harga jual');
                        } else {
                            issues.push('Belum menguntungkan (margin ' + analytics.margin_keuntungan + '%)');
                        }

                        // Mortality Analysis
                        if (analytics.mortality_rate <= 5) {
                            score += 30;
                            strengths.push('Tingkat kematian sangat rendah (' + analytics.mortality_rate + '%)');
                        } else if (analytics.mortality_rate <= 10) {
                            score += 20;
                            strengths.push('Tingkat kematian normal (' + analytics.mortality_rate + '%)');
                        } else {
                            score += 5;
                            issues.push('Tingkat kematian tinggi (' + analytics.mortality_rate + '%), perlu perhatian kesehatan');
                        }

                        // Overall Rating
                        let rating = '';
                        let emoji = '';
                        let recommendation = '';

                        if (score >= 85) {
                            rating = 'EXCELLENT';
                            emoji = '';
                            recommendation = 'Kloter ini berjalan sangat baik! Pertahankan manajemen yang sudah ada.';
                        } else if (score >= 70) {
                            rating = 'BAIK';
                            emoji = '';
                            recommendation = 'Performa kloter baik, ada beberapa area yang bisa dioptimalkan.';
                        } else if (score >= 50) {
                            rating = 'CUKUP';
                            emoji = '';
                            recommendation = 'Performa cukup, perlu perbaikan di beberapa aspek untuk meningkatkan profit.';
                        } else {
                            rating = 'PERLU PERBAIKAN';
                            emoji = '';
                            recommendation = 'Performa kurang optimal, evaluasi menyeluruh diperlukan.';
                        }

                        return `
                                <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <div style="text-align: center; margin-bottom: 10px;">
                                        <div style="font-size: 2.5em;">${emoji}</div>
                                        <div style="font-size: 1.4em; font-weight: bold; margin-top: 5px;">${rating}</div>
                                        <div style="font-size: 1.1em; opacity: 0.9;">Skor Performa: ${score}/100</div>
                                    </div>
                                </div>
                                
                                ${strengths.length > 0 ? `
                                    <div style="background: rgba(16, 185, 129, 0.2); padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                                        <strong style="display: block; margin-bottom: 8px;">Kekuatan:</strong>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            ${strengths.map(s => '<li>' + s + '</li>').join('')}
                                        </ul>
                                    </div>
                                ` : ''}
                                
                                ${issues.length > 0 ? `
                                    <div style="background: rgba(239, 68, 68, 0.2); padding: 12px; border-radius: 6px; margin-bottom: 10px;">
                                        <strong style="display: block; margin-bottom: 8px;">Perlu Perhatian:</strong>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            ${issues.map(i => '<li>' + i + '</li>').join('')}
                                        </ul>
                                    </div>
                                ` : ''}
                                
                                <div style="background: rgba(255,255,255,0.15); padding: 12px; border-radius: 6px; border-left: 4px solid white;">
                                    <strong>💡 Rekomendasi:</strong>
                                    <p style="margin: 5px 0 0 0;">${recommendation}</p>
                                </div>
                            `;
                    })()}
                    </div>
                </div>

                <!-- RINGKASAN BISNIS -->
                <div class="modal-card" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3); border-radius: 12px;">
                    <h3 style="color: white; font-weight: 600; font-size: 1.1em; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid rgba(255,255,255,0.2);">Ringkasan Bisnis</h3>
                    <div style="margin: 20px 0; font-size: 0.95em;">
                        ${(() => {
                        const penjualans = kloter.data_penjualans || [];
                        const jumlahCustomer = penjualans.length;
                        const totalAyamTerjual = penjualans.reduce((sum, p) => sum + (p.jumlah_ayam_dibeli || 0), 0);
                        const totalBeratGram = penjualans.reduce((sum, p) => sum + (p.berat_total || 0), 0);
                        const rataAyam = jumlahCustomer > 0 ? (totalAyamTerjual / jumlahCustomer).toFixed(1) : 0;
                        const rataBeratKg = jumlahCustomer > 0 ? ((totalBeratGram / 1000) / jumlahCustomer).toFixed(2) : 0;
                        const totalPengeluaran = analytics.breakdown_pengeluaran.DOC.nominal + analytics.breakdown_pengeluaran.Pakan.nominal + analytics.breakdown_pengeluaran.Obat.nominal + analytics.breakdown_pengeluaran['Listrik/Air'].nominal + analytics.breakdown_pengeluaran['Tenaga Kerja'].nominal + analytics.breakdown_pengeluaran['Pemeliharaan Kandang'].nominal + analytics.breakdown_pengeluaran.Lainnya.nominal;
                        const beratHidup = (analytics.total_berat_terjual / 0.8).toFixed(2);
                        const totalKematian = kloter.kematian_ayams.reduce((sum, k) => sum + (k.jumlah_mati || 0), 0);

                        return `
                        <!-- PERFORMA PENJUALAN -->
                        <div style="background: rgba(255,255,255,0.12); border-radius: 10px; padding: 18px; margin-bottom: 16px; border: 1px solid rgba(255,255,255,0.15);">
                            <div style="font-size: 0.8em; font-weight: 600; margin-bottom: 14px; opacity: 0.9; letter-spacing: 0.5px;">Performa Penjualan</div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div style="background: rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                    <div style="font-size: 0.75em; opacity: 0.85; margin-bottom: 6px;">Total Pelanggan</div>
                                    <div style="font-size: 1.5em; font-weight: 700;">${jumlahCustomer}</div>
                                </div>
                                <div style="background: rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                    <div style="font-size: 0.75em; opacity: 0.85; margin-bottom: 6px;">Rata-rata/Pelanggan</div>
                                    <div style="font-size: 1.2em; font-weight: 600;">${rataAyam} ekor</div>
                                    <div style="font-size: 0.85em; opacity: 0.75;">${rataBeratKg} Kg</div>
                                </div>
                                <div style="background: rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                    <div style="font-size: 0.75em; opacity: 0.85; margin-bottom: 6px;">Total Terjual</div>
                                    <div style="font-size: 1.2em; font-weight: 600;">${totalAyamTerjual} ekor</div>
                                    <div style="font-size: 0.85em; opacity: 0.75;">${analytics.total_berat_terjual.toFixed(2)} Kg</div>
                                </div>
                                <div style="background: rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                                    <div style="font-size: 0.75em; opacity: 0.85; margin-bottom: 6px;">Berat Hidup Est.</div>
                                    <div style="font-size: 1.2em; font-weight: 600;">${beratHidup} Kg</div>
                                </div>
                            </div>
                            <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.15);">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.9em; opacity: 0.9;">Total Pemasukan</span>
                                    <span style="font-size: 1.4em; font-weight: 700; color: #a5f3fc;">Rp ${Number(analytics.total_pemasukan).toLocaleString('id-ID')}</span>
                                </div>
                            </div>
                        </div>

                        <!-- DATA OPERASIONAL -->
                        <div style="background: rgba(255,255,255,0.12); border-radius: 10px; padding: 18px; margin-bottom: 16px; border: 1px solid rgba(255,255,255,0.15);">
                            <div style="font-size: 0.8em; font-weight: 600; margin-bottom: 14px; opacity: 0.9; letter-spacing: 0.5px;">Data Operasional</div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="opacity: 0.88; font-size: 0.92em;">DOC Awal</span>
                                <strong style="font-weight: 600;">${kloter.jumlah_doc} ekor</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="opacity: 0.88; font-size: 0.92em;">Jumlah Kematian</span>
                                <strong style="font-weight: 600; color: #fca5a5;">${totalKematian} ekor</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="opacity: 0.88; font-size: 0.92em;">Total Terpanen</span>
                                <strong style="font-weight: 600;">${kloter.panens.reduce((sum, p) => sum + (p.jumlah_panen || 0), 0)} ekor</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="opacity: 0.88; font-size: 0.92em;">Total Pengeluaran</span>
                                <strong style="font-weight: 600;">Rp ${totalPengeluaran.toLocaleString('id-ID')}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <span style="opacity: 0.88; font-size: 0.92em;">Total Pakan</span>
                                <strong style="font-weight: 600;">${rekapan.total_pakan_kg.toLocaleString('id-ID')} Kg</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="opacity: 0.88; font-size: 0.92em;">FCR (Pakan/Berat Hidup)</span>
                                <strong style="font-weight: 700; font-size: 1.15em; color: ${analytics.fcr <= 1.8 ? '#86efac' : analytics.fcr <= 2.5 ? '#fde047' : '#fca5a5'};">${analytics.fcr}</strong>
                            </div>
                        </div>

                        <!-- PROFITABILITAS -->
                        <div style="background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0.08) 100%); border-radius: 10px; padding: 20px; border: 1px solid rgba(255,255,255,0.2);">
                            <div style="font-size: 0.8em; font-weight: 600; margin-bottom: 14px; opacity: 0.9; letter-spacing: 0.5px;">Profitabilitas</div>
                            <div style="text-align: center;">
                                <div style="font-size: 0.88em; opacity: 0.85; margin-bottom: 8px;">Keuntungan Bersih</div>
                                <div style="font-size: 2.1em; font-weight: 700; margin-bottom: 6px; color: ${analytics.keuntungan_bersih >= 0 ? '#86efac' : '#fca5a5'};">Rp ${analytics.keuntungan_bersih.toLocaleString('id-ID')}</div>
                                <div style="display: inline-block; background: rgba(255,255,255,0.25); padding: 8px 18px; border-radius: 20px; font-size: 0.95em; font-weight: 600;">
                                    Margin: ${analytics.margin_keuntungan}%
                                </div>
                            </div>
                        </div>
                            `;
                    })()}
                    </div>
                </div>

                <div class="modal-summary-box" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6; border-radius: 10px; padding: 18px; box-shadow: 0 2px 6px rgba(59, 130, 246, 0.15);">
                    <div class="summary-item" style="padding: 10px 0;"><span style="color: #1e40af; font-weight: 600;">Total Pengeluaran</span><strong style="color: #1e40af; font-size: 1.05em;">Rp ${rekapan.total_pengeluaran.toLocaleString('id-ID')}</strong></div>
                    <div class="summary-item" style="padding: 10px 0;"><span style="color: #1e40af; font-weight: 600;">Akumulasi Pakan</span><strong style="color: #1e40af; font-size: 1.05em;">${rekapan.total_pakan_kg.toLocaleString('id-ID')} Kg</strong></div>
                    <div class="summary-item" style="padding: 10px 0;">
                        <span style="color: #1e40af; font-weight: 600;">Sisa Ayam (di Kandang)</span>
                        <div class="editable-item">
                            <strong style="color: #1e40af; font-size: 1.05em;">${rekapan.sisa_ayam} ekor</strong>
                            <button class="btn btn-orange btn-sm" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; padding: 6px 14px; border-radius: 6px; font-weight: 500; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);" onclick="openKoreksiStokModal(${kloter.id}, ${rekapan.sisa_ayam})" title="Koreksi">Koreksi</button>
                        </div>
                    </div>
                </div>`;

                Swal.fire({ title: `Detail Kloter: ${kloter.nama_kloter}`, html: modalHtml, showCloseButton: true, showConfirmButton: false });

            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        }

        // --- FUNGSI-FUNGSI MODAL LAINNYA (TETAP SAMA, HANYA DIPERSINGKAT) ---
        function openKoreksiStokModal(kloterId, currentSisa) {
            Swal.fire({
                title: 'Koreksi Jumlah Ayam', text: 'DOC Awal akan disesuaikan otomatis.', input: 'number', inputValue: currentSisa, showCancelButton: true, confirmButtonText: 'Simpan Koreksi',
                inputValidator: (v) => !v || v < 0 ? 'Masukkan jumlah yang valid!' : null
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/manajemen-kloter/${kloterId}/koreksi-stok`;
                    form.innerHTML = `<input type="hidden" name="_method" value="PUT"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="sisa_ayam_hidup" value="${result.value}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openEditTanggalModal(kloterId, currentTanggal) {
            Swal.fire({
                title: 'Edit Tanggal Mulai',
                html: `<form id="form-edit-tanggal" action="/manajemen-kloter/${kloterId}/update-tanggal" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT"><input type="date" name="tanggal_mulai" class="swal2-input" value="${currentTanggal}" required></form>`,
                showCancelButton: true, confirmButtonText: 'Simpan', preConfirm: () => document.getElementById('form-edit-tanggal').submit()
            });
        }

        function openEditDocModal(kloterId, currentDoc) {
            Swal.fire({
                title: 'Edit Jumlah DOC Awal',
                html: `<form id="form-edit-doc" action="/manajemen-kloter/${kloterId}/update-doc" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="PUT"><input type="number" name="jumlah_doc" class="swal2-input" value="${currentDoc}" required min="0"></form>`,
                showCancelButton: true, confirmButtonText: 'Simpan', preConfirm: () => document.getElementById('form-edit-doc').submit()
            });
        }

        function openKematianModal(kloterId) {
            Swal.fire({
                title: 'Catat Kematian Ayam',
                html: `<form id="form-kematian" action="/manajemen-kloter/${kloterId}/kematian" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="number" name="jumlah_mati" class="swal2-input" placeholder="Jumlah Mati" required min="1"><input type="date" name="tanggal_kematian" class="swal2-input" value="{{ date('Y-m-d') }}" required><textarea name="catatan" class="swal2-textarea" placeholder="Catatan (opsional)"></textarea></form>`,
                showCancelButton: true, confirmButtonText: 'Simpan', preConfirm: () => document.getElementById('form-kematian').submit()
            });
        }

        function togglePakanInput(selectedValue) {
            const pakanGroup = document.getElementById('pakan-input-group');
            const pakanInput = pakanGroup.querySelector('input');
            if (selectedValue === 'Pakan') {
                pakanGroup.style.display = 'block';
                pakanInput.required = true;
            } else {
                pakanGroup.style.display = 'none';
                pakanInput.required = false;
                pakanInput.value = '';
            }
        }



        function openPengeluaranModal(kloterId) {
            Swal.fire({
                title: 'Input Data Keperluan',
                html: `
                <form id="form-pengeluaran" action="/manajemen-kloter/${kloterId}/pengeluaran" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!-- Input tersembunyi untuk nilai asli -->
                    <input type="hidden" name="jumlah_pengeluaran" id="jumlah_pengeluaran_real">
                    
                    <label style="display: block; margin-bottom: 5px;">📂 Kategori</label>
                    <select name="kategori" id="swal-kategori" class="swal2-select" required onchange="togglePakanInput(this.value)">
                        <option value="">Pilih Kategori</option>
                        <option value="Pakan">🌾 Pakan</option>
                        <option value="Obat">💊 Obat/Vitamin</option>
                        <option value="Listrik/Air">💡 Listrik/Air</option>
                        <option value="Tenaga Kerja">👷 Tenaga Kerja</option>
                        <option value="Pemeliharaan Kandang">🔧 Pemeliharaan Kandang</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                    
                    <div style="background: #d1ecf1; border: 1px solid #0c5460; border-radius: 6px; padding: 10px; margin-top: 10px; font-size: 0.9em;">
                        <strong style="color: #0c5460;">Catatan:</strong>
                        <p style="margin: 5px 0 0 0; color: #0c5460;">Modal DOC sudah otomatis tercatat sebagai pengeluaran "DOC" saat buat kloter.</p>
                    </div>

                    <div id="pakan-input-group" style="display: none; margin-top: 10px;">
                        <label style="display: block; margin-bottom: 5px;">Jumlah Pakan (Kg)</label>
                        <input type="number" step="0.01" name="jumlah_pakan_kg" class="swal2-input" placeholder="Contoh: 50.5">
                    </div>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Harga (Rp)</label>
                    <input id="jumlah_pengeluaran_formatted" type="text" class="swal2-input" placeholder="Masukkan jumlah pengeluaran" required oninput="formatNumberInput(this)">
                    
                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal_pengeluaran" class="swal2-input" value="{{ date('Y-m-d') }}" required>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Keterangan (Opsional)</label>
                    <textarea name="catatan" class="swal2-textarea" placeholder="Contoh: Pembelian pakan BR-1 2 karung"></textarea>
                </form>
            `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                preConfirm: () => {
                    // Ambil nilai yang sudah diformat, hapus titiknya, lalu kirim
                    const formattedInput = document.getElementById('jumlah_pengeluaran_formatted');
                    const realInput = document.getElementById('jumlah_pengeluaran_real');
                    realInput.value = formattedInput.value.replace(/\./g, '');
                    document.getElementById('form-pengeluaran').submit();
                }
            });
        }



        window.calculatePanen = function (percent, sisaAyam) {
            const amount = Math.floor(sisaAyam * percent);
            document.getElementById('input-jumlah-panen').value = amount;
        }

        function openPanenModal(kloterId, sisaAyam) {
            Swal.fire({
                title: 'Catat Panen Parsial',
                html: `
                <form id="form-panen" action="/manajemen-kloter/${kloterId}/panen" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <div style="margin-bottom: 15px; text-align: center;">
                        <label style="display:block; margin-bottom:5px; font-weight:bold;">Pilih Persentase Panen</label>
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <button type="button" class="btn btn-sm" style="background:#6c757d; color:white;" onclick="calculatePanen(0.25, ${sisaAyam})">25%</button>
                            <button type="button" class="btn btn-sm" style="background:#6c757d; color:white;" onclick="calculatePanen(0.50, ${sisaAyam})">50%</button>
                            <button type="button" class="btn btn-sm" style="background:#6c757d; color:white;" onclick="calculatePanen(1.0, ${sisaAyam})">100%</button>
                        </div>
                        <small style="color:#666;">Dari sisa ${sisaAyam} ekor</small>
                    </div>

                    <label style="display: block; margin-bottom: 5px;">Jumlah Ayam Dipanen</label>
                    <input type="number" id="input-jumlah-panen" name="jumlah_panen" class="swal2-input" placeholder="0" required min="1" max="${sisaAyam}">
                    
                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Tanggal Panen</label>
                    <input type="date" name="tanggal_panen" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Panen',
                confirmButtonColor: '#28a745',
                preConfirm: () => document.getElementById('form-panen').submit()
            });
        }

        function openEditPengeluaranModal(pengeluaranId, kategori, jumlah, tanggal, catatan, pakanKg) {
            Swal.fire({
                title: 'Edit Data Pengeluaran',
                html: `
                <form id="form-edit-pengeluaran" action="/pengeluaran/${pengeluaranId}" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="jumlah_pengeluaran" id="edit_jumlah_pengeluaran_real">
                    
                    <label style="display: block; margin-bottom: 5px;">📂 Kategori</label>
                    <select name="kategori" id="edit-kategori" class="swal2-select" required onchange="togglePakanInputEdit(this.value)">
                        <option value="DOC" ${kategori === 'DOC' ? 'selected' : ''}>🐣 DOC</option>
                        <option value="Pakan" ${kategori === 'Pakan' ? 'selected' : ''}>🌾 Pakan</option>
                        <option value="Obat" ${kategori === 'Obat' ? 'selected' : ''}>💊 Obat/Vitamin</option>
                        <option value="Listrik/Air" ${kategori === 'Listrik/Air' ? 'selected' : ''}>💡 Listrik/Air</option>
                        <option value="Tenaga Kerja" ${kategori === 'Tenaga Kerja' ? 'selected' : ''}>👷 Tenaga Kerja</option>
                        <option value="Pemeliharaan Kandang" ${kategori === 'Pemeliharaan Kandang' ? 'selected' : ''}>🔧 Pemeliharaan Kandang</option>
                        <option value="Lainnya" ${kategori === 'Lainnya' ? 'selected' : ''}>📦 Lainnya</option>
                    </select>

                    <div id="edit-pakan-input-group" style="display: ${kategori === 'Pakan' ? 'block' : 'none'}; margin-top: 10px;">
                        <label style="display: block; margin-bottom: 5px;">Jumlah Pakan (Kg)</label>
                        <input type="number" step="0.01" name="jumlah_pakan_kg" id="edit-pakan-kg" class="swal2-input" value="${pakanKg}" placeholder="Contoh: 50.5">
                    </div>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Harga (Rp)</label>
                    <input id="edit_jumlah_pengeluaran_formatted" type="text" class="swal2-input" value="${Number(jumlah).toLocaleString('id-ID')}" required oninput="formatNumberInput(this)">
                    
                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal_pengeluaran" class="swal2-input" value="${tanggal}" required>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Keterangan (Opsional)</label>
                    <textarea name="catatan" class="swal2-textarea" placeholder="Contoh: Pembelian pakan BR-1 2 karung">${catatan}</textarea>
                </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                preConfirm: () => {
                    const formattedInput = document.getElementById('edit_jumlah_pengeluaran_formatted');
                    const realInput = document.getElementById('edit_jumlah_pengeluaran_real');
                    realInput.value = formattedInput.value.replace(/\./g, '');
                    document.getElementById('form-edit-pengeluaran').submit();
                }
            });
        }

        function togglePakanInputEdit(selectedValue) {
            const pakanGroup = document.getElementById('edit-pakan-input-group');
            const pakanInput = document.getElementById('edit-pakan-kg');
            if (selectedValue === 'Pakan') {
                pakanGroup.style.display = 'block';
                pakanInput.required = true;
            } else {
                pakanGroup.style.display = 'none';
                pakanInput.required = false;
                pakanInput.value = '';
            }
    }
    </script>
</body>

</html>