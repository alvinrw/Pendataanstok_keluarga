<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kloter Pemeliharaan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/daftar_kloter.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐔 Manajemen Kloter</h1>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>
                <button type="button" class="btn btn-green" onclick="openCreateKloterModal()">+ Tambah Kloter Baru</button>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <strong>❌ Error!</strong>
                <ul style="margin-top: 10px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>🏷️ Nama Kloter</th>
                        <th>🐔 Sisa Ayam di Kandang</th>
                        <th>✅ Stok Siap Jual</th>
                        <th>📅 Tanggal Mulai</th>
                        <th>🐣 DOC Awal</th>
                        <th>⚙️ Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kloters as $kloter)
                        <tr>
                            <td><strong>{{ $kloter->nama_kloter }}</strong></td>
                            <td><strong style="color: #007bff;">{{ $kloter->sisa_ayam_hidup }} ekor</strong></td>
                            <td><strong style="color: #28a745;">{{ $kloter->stok_tersedia }} ekor</strong></td>
                            <td>{{ \Carbon\Carbon::parse($kloter->tanggal_mulai)->format('d F Y') }}</td>
                            <td>{{ $kloter->jumlah_doc }} ekor</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-blue btn-sm" onclick="openDetailModal({{ $kloter->id }})">📋 Detail</button>
                                    <form action="{{ route('manajemen.kloter.destroy', $kloter->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus kloter ini? Semua data terkait akan hilang.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red btn-sm">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                📋 Belum ada data kloter.<br>
                                <small>Silakan buat kloter baru untuk memulai.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    function openCreateKloterModal() {
        Swal.fire({
            title: '🆕 Tambah Kloter Baru',
            html: `
                <form id="form-create-kloter" action="{{ route('manajemen.kloter.store') }}" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📝 Nama Kloter</label>
                    <input type="text" name="nama_kloter" class="swal2-input" placeholder="Contoh: Kloter Agustus 2025" required>
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #333;">📅 Tanggal Ayam Masuk</label>
                    <input type="date" name="tanggal_mulai" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #333;">🐣 DOC Awal (Jumlah Ayam)</label>
                    <input type="number" name="jumlah_doc" class="swal2-input" placeholder="Contoh: 100" required min="1">
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #333;">💰 Harga Beli DOC (Wajib)</label>
                    <input type="number" name="harga_beli_doc" class="swal2-input" placeholder="Contoh: 800000" required min="0">
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan Kloter',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#28a745',
            width: 600,
            preConfirm: () => {
                document.getElementById('form-create-kloter').submit();
            }
        });
    }

    async function openDetailModal(kloterId) {
        Swal.fire({ 
            title: '⏳ Memuat data kloter...', 
            didOpen: () => Swal.showLoading(), 
            allowOutsideClick: false 
        });

        try {
            const response = await fetch(`/manajemen-kloter/${kloterId}/detail-json`);
            if (!response.ok) throw new Error('Gagal mengambil data dari server.');
            
            const data = await response.json();
            const { kloter, rekapan } = data;

            let kematianHtml = '';
            kloter.kematian_ayams.forEach(item => {
                kematianHtml += `<tr>
                    <td>${new Date(item.tanggal_kematian).toLocaleDateString('id-ID')}</td>
                    <td>${item.jumlah_mati} ekor</td>
                    <td>
                        <form action="/kematian/${item.id}" method="POST" onsubmit="return confirm('Hapus data kematian ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>`;
            });
            if (kloter.kematian_ayams.length === 0) kematianHtml = '<tr><td colspan="3" style="text-align:center; color: #6c757d;">Belum ada data kematian.</td></tr>';

            let pengeluaranHtml = '';
            kloter.pengeluarans.forEach(item => {
                pengeluaranHtml += `<tr>
                    <td>${item.kategori}</td>
                    <td>Rp ${Number(item.jumlah_pengeluaran).toLocaleString('id-ID')}</td>
                    <td>
                        <form action="/pengeluaran/${item.id}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>`;
            });
            if (kloter.pengeluarans.length === 0) pengeluaranHtml = '<tr><td colspan="3" style="text-align:center; color: #6c757d;">Belum ada data pengeluaran.</td></tr>';

            let panenHtml = '';
            kloter.panens.forEach(item => {
                panenHtml += `<tr>
                    <td>${new Date(item.tanggal_panen).toLocaleDateString('id-ID')}</td>
                    <td>${item.jumlah_panen} ekor</td>
                    <td>
                        <form action="/panen/${item.id}" method="POST" onsubmit="return confirm('Hapus data panen ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>`;
            });
            if (kloter.panens.length === 0) panenHtml = '<tr><td colspan="3" style="text-align:center; color: #6c757d;">Belum ada data panen.</td></tr>';

            const modalHtml = `
                <div class="modal-content-grid">
                    <div class="modal-card">
                        <h3>📝 Input & Edit Data</h3>
                        <div class="editable-item">
                            <p><strong>🐣 DOC Awal:</strong> ${kloter.jumlah_doc} ekor</p> 
                            <button class="btn btn-orange btn-sm" onclick="openEditDocModal(${kloter.id}, ${kloter.jumlah_doc})">✏️ Edit</button>
                        </div>
                        <div class="editable-item">
                            <p><strong>📅 Tanggal Mulai:</strong> ${new Date(kloter.tanggal_mulai).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})}</p> 
                            <button class="btn btn-orange btn-sm" onclick="openEditTanggalModal(${kloter.id}, '${kloter.tanggal_mulai}')">✏️ Edit</button>
                        </div>
                        <hr style="margin: 15px 0; border: 1px solid #e9ecef;">
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px;" onclick="openKematianModal(${kloter.id})">💀 Input Kematian</button>
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px;" onclick="openPengeluaranModal(${kloter.id})">💸 Input Keperluan</button>
                        <button class="btn btn-green" style="width:100%;" onclick="openPanenModal(${kloter.id})">🌾 Input Panen</button>
                    </div>
                    <div class="modal-card">
                        <h3>📜 Riwayat & Rekapan</h3>
                        <div class="scrollable-section">
                            <strong>🌾 Riwayat Panen:</strong>
                            <table class="modal-rekapan-table">
                                <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Aksi</th></tr></thead>
                                <tbody>${panenHtml}</tbody>
                            </table>
                        </div>
                        <div class="scrollable-section">
                            <strong>💀 Riwayat Kematian:</strong>
                            <table class="modal-rekapan-table">
                                <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Aksi</th></tr></thead>
                                <tbody>${kematianHtml}</tbody>
                            </table>
                        </div>
                        <div class="scrollable-section">
                            <strong>💸 Riwayat Pengeluaran:</strong>
                            <table class="modal-rekapan-table">
                                <thead><tr><th>Kategori</th><th>Jumlah</th><th>Aksi</th></tr></thead>
                                <tbody>${pengeluaranHtml}</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-summary-box">
                    <div class="summary-item">
                        <span>💰 Total Pengeluaran:</span>
                        <strong>Rp ${rekapan.total_pengeluaran.toLocaleString('id-ID')}</strong>
                    </div>
                    <div class="summary-item">
                        <span>🌾 Jumlah yang Sudah Terpanen:</span>
                        <strong>${rekapan.total_panen} ekor</strong>
                    </div>
                    <div class="summary-item">
                        <span>🐓 Jumlah Ayam Kloter Ini (di Kandang):</span>
                        <div class="editable-item" style="margin: 0;">
                            <strong>${rekapan.sisa_ayam} ekor</strong>
                            <button class="btn btn-orange btn-sm" onclick="openKoreksiStokModal(${kloter.id}, ${rekapan.sisa_ayam})">🔧 Koreksi</button>
                        </div>
                    </div>
                </div>`;

            Swal.fire({ 
                title: `📋 Detail Kloter: ${kloter.nama_kloter}`, 
                html: modalHtml, 
                showCloseButton: true, 
                showConfirmButton: false,
                width: '90%'
            });

        } catch (error) {
            Swal.fire('❌ Error!', error.message, 'error');
        }
    }
    
    function openKoreksiStokModal(kloterId, currentSisa) {
        Swal.fire({
            title: '🔧 Koreksi Jumlah Ayam Sekarang',
            text: 'DOC Awal akan disesuaikan secara otomatis.',
            input: 'number',
            inputValue: currentSisa,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan Koreksi',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#fd7e14',
            inputValidator: (value) => {
                if (!value || value < 0) {
                    return 'Masukkan jumlah yang valid!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/manajemen-kloter/${kloterId}/koreksi-stok`;
                form.innerHTML = `
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="sisa_ayam_hidup" value="${result.value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function openEditTanggalModal(kloterId, currentTanggal) {
        Swal.fire({
            title: '📅 Edit Tanggal Mulai',
            html: `
                <form id="form-edit-tanggal" action="/manajemen-kloter/${kloterId}/update-tanggal" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">📅 Tanggal Mulai Baru:</label>
                    <input type="date" name="tanggal_mulai" class="swal2-input" value="${currentTanggal}" required>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan Perubahan',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#fd7e14',
            preConfirm: () => document.getElementById('form-edit-tanggal').submit()
        });
    }

    function openEditDocModal(kloterId, currentDoc) {
        Swal.fire({
            title: '🐣 Edit Jumlah DOC Awal',
            html: `
                <form id="form-edit-doc" action="/manajemen-kloter/${kloterId}/update-doc" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">🐣 Jumlah DOC Awal:</label>
                    <input type="number" name="jumlah_doc" class="swal2-input" value="${currentDoc}" required min="0">
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan Perubahan',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#fd7e14',
            preConfirm: () => document.getElementById('form-edit-doc').submit()
        });
    }

    function openKematianModal(kloterId) {
        Swal.fire({
            title: '💀 Catat Kematian Ayam',
            html: `
                <form id="form-kematian" action="/manajemen-kloter/${kloterId}/kematian" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">💀 Jumlah Mati:</label>
                    <input type="number" name="jumlah_mati" class="swal2-input" placeholder="Jumlah Mati" required min="1">
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600;">📅 Tanggal Kematian:</label>
                    <input type="date" name="tanggal_kematian" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600;">📝 Catatan (opsional):</label>
                    <textarea name="catatan" class="swal2-textarea" placeholder="Catatan tambahan..."></textarea>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#007bff',
            preConfirm: () => document.getElementById('form-kematian').submit()
        });
    }

    function openPengeluaranModal(kloterId) {
        Swal.fire({
            title: '💸 Input Data Keperluan',
            html: `
                <form id="form-pengeluaran" action="/manajemen-kloter/${kloterId}/pengeluaran" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">📂 Kategori:</label>
                    <select name="kategori" class="swal2-select" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Pakan">🌾 Pakan</option>
                        <option value="Obat">💊 Obat</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600;">💰 Harga (Rp):</label>
                    <input type="number" name="jumlah_pengeluaran" class="swal2-input" placeholder="Harga (Rp)" required min="0">
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600;">📅 Tanggal Pengeluaran:</label>
                    <input type="date" name="tanggal_pengeluaran" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '💾 Simpan',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#007bff',
            preConfirm: () => document.getElementById('form-pengeluaran').submit()
        });
    }
    
    function openPanenModal(kloterId) {
        Swal.fire({
            title: '🌾 Catat Panen Parsial',
            html: `
                <form id="form-panen" action="/manajemen-kloter/${kloterId}/panen" method="POST" style="text-align: left;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">🐔 Jumlah Ayam Dipanen:</label>
                    <input type="number" name="jumlah_panen" class="swal2-input" placeholder="Jumlah Ayam Dipanen" required min="1">
                    <label style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600;">📅 Tanggal Panen:</label>
                    <input type="date" name="tanggal_panen" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '🌾 Simpan Panen',
            cancelButtonText: '❌ Batal',
            confirmButtonColor: '#28a745',
            preConfirm: () => { document.getElementById('form-panen').submit(); }
        });
    }
</script>
</body>
</html>