<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kloter Pemeliharaan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
<style>
    
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 20px; color: #34495e; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 2.5rem; color: #2c3e50; text-shadow: 1px 1px 2px rgba(0,0,0,0.1); }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        
        .btn { padding: 12px 24px; text-decoration: none; color: white; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; display: inline-block; }
        .btn-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-primary-gradient:hover { transform: translateY(-3px); box-shadow: 0 6px 12px rgba(102, 126, 234, 0.3); }
        
        .back-btn { color: #556080; text-decoration: none; font-weight: 600; transition: color 0.3s; }
        .back-btn:hover { color: #667eea; }

        .kloter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }
        .kloter-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s, box-shadow 0.3s; }
        .kloter-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.12); }
        .card-header { padding: 20px; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-bottom: 4px solid #667eea; }
        .card-header h3 { font-size: 1.3rem; margin: 0; }
        .card-body { padding: 25px; flex-grow: 1; }
        .info-item { display: flex; justify-content: space-between; align-items: center; font-size: 1rem; padding: 12px 0; border-bottom: 1px solid #f0f2f5; }
        .info-item:last-child { border-bottom: none; }
        .info-item span { color: #5a6a7b; }
        .info-item strong { color: #2c3e50; font-size: 1.1rem; }
        .card-footer { background-color: #f8f9fa; padding: 15px 25px; border-top: 1px solid #e9ecef; }
        .actions { display: flex; gap: 10px; }
        .btn-sm { padding: 8px 16px; font-size: 0.9em; font-weight: 500; border-radius: 6px; }
        .btn-blue { background: #3498db; } .btn-blue:hover { background: #2980b9; }
        .btn-red { background: #e74c3c; } .btn-red:hover { background: #c0392b; }
        .btn-orange { background: #f39c12; } .btn-orange:hover { background: #e67e22; }
        .btn-green { background: #2ecc71; } .btn-green:hover { background: #27ae60; }
        .no-data { background: white; border-radius: 15px; padding: 50px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        /* SweetAlert2 Modal Styles */
        .swal2-popup { width: 85% !important; max-width: 950px !important; border-radius: 15px !important; }
        .modal-content-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 25px; text-align: left; }
        .modal-card { padding: 20px; border: 1px solid #e9ecef; border-radius: 12px; background: #fdfdfd; }
        .modal-card h3 { margin-top: 0; font-size: 1.3em; color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-bottom: 15px; }
        .modal-rekapan-table { width: 100%; font-size: 0.9em; }
        .modal-rekapan-table th { background: #f1f3f5; padding: 8px; }
        .modal-rekapan-table td { padding: 8px 5px; border-bottom: 1px solid #f2f2f2; }
        .modal-rekapan-table tr:last-child td { border-bottom: none; }
        .modal-summary-box { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 25px; border-radius: 12px; margin-top: 20px; border-top: 3px solid #667eea; }
        .summary-item { display: flex; justify-content: space-between; font-size: 1.1em; margin-bottom: 12px; align-items: center; }
        .summary-item:last-child { margin-bottom: 0; }
        .summary-item span { font-weight: 500; }
        .summary-item strong { font-weight: bold; font-size: 1.2em; color: #2c3e50; }
        .editable-item { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 8px 0; }

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
    }
    .modal-summary-box {
        padding: 15px;
    }
    .modal-rekapan-table {
        font-size: 0.8em;
    }
    .modal-rekapan-table th,
    .modal-rekapan-table td {
        padding: 5px;
    }
}


</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐔 Manajemen Kloter</h1>
        </div>

        <div class="header-actions">
            <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>
            <button type="button" class="btn btn-primary-gradient" onclick="openCreateKloterModal()">+ Tambah Kloter Baru</button>
        </div>

        @if(session('success'))
            <p style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</p>
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
                            <span>🐓 Sisa di Kandang</span>
                            <strong style="color: #007bff;">{{ $kloter->sisa_ayam_hidup }} ekor</strong>
                        </div>
                        <div class="info-item">
                            <span>📦 Stok Siap Jual</span>
                            <strong style="color: #28a745;">{{ $kloter->stok_tersedia }} ekor</strong>
                        </div>
                        <div class="info-item">
                            <span>🐣 DOC Awal</span>
                            <strong>{{ $kloter->jumlah_doc }} ekor</strong>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="actions">
                            <button class="btn btn-sm btn-blue" onclick="openDetailModal({{ $kloter->id }})">⚙️ Detail & Aksi</button>
                            <form action="{{ route('manajemen.kloter.destroy', $kloter->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus kloter ini? Semua data terkait (penjualan, pengeluaran, dll) akan hilang secara permanen.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-red">🗑️ Hapus</button>
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

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">Harga Beli DOC (Wajib)</label>
                    <input id="harga_beli_doc_formatted" type="text" class="swal2-input" placeholder="Contoh: 800.000" required oninput="formatNumberInput(this)">
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
            
            const { kloter, rekapan } = await response.json();

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
                                <button type="submit" class="btn btn-red btn-sm" style="padding: 4px 8px; font-size: 0.8em;">Hapus</button>
                            </form>
                        </td>
                    </tr>`;
                }).join('');
            };

            const pengeluaranHtml = kloter.pengeluarans.length === 0 ? '<tr><td colspan="4" style="text-align:center; padding:10px;">Belum ada data.</td></tr>' :
                kloter.pengeluarans.map(item => `<tr>
                    <td>${item.kategori}</td>
                    <td>${item.jumlah_pakan_kg ? item.jumlah_pakan_kg + ' Kg' : '-'}</td>
                    <td>Rp ${Number(item.jumlah_pengeluaran).toLocaleString('id-ID')}</td>
                    <td>
                        <form action="/pengeluaran/${item.id}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm" style="padding: 4px 8px; font-size: 0.8em;">Hapus</button>
                        </form>
                    </td>
                </tr>`).join('');

            const modalHtml = `
                <div class="modal-content-grid">
                    <div class="modal-card">
                        <h3>📝 Input & Edit Data</h3>
                        <div class="editable-item"><p><strong>DOC Awal:</strong> ${kloter.jumlah_doc} ekor</p> <button class="btn btn-sm btn-orange" onclick="openEditDocModal(${kloter.id}, ${kloter.jumlah_doc})">Edit</button></div>
                        <div class="editable-item"><p><strong>Tgl Mulai:</strong> ${new Date(kloter.tanggal_mulai).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})}</p> <button class="btn btn-sm btn-orange" onclick="openEditTanggalModal(${kloter.id}, '${kloter.tanggal_mulai}')">Edit</button></div>
                        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px;" onclick="openKematianModal(${kloter.id})">💀 Input Kematian</button>
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px;" onclick="openPengeluaranModal(${kloter.id})">💸 Input Pengeluaran</button>
                        <button class="btn btn-green" style="width:100%;" onclick="openPanenModal(${kloter.id})">🚜 Input Panen</button>
                    </div>
                    <div class="modal-card">
                        <h3>📜 Riwayat Data</h3>
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
                <div class="modal-summary-box">
                    <div class="summary-item"><span>💰 Total Pengeluaran</span><strong>Rp ${rekapan.total_pengeluaran.toLocaleString('id-ID')}</strong></div>
                    <div class="summary-item"><span>🌾 Akumulasi Pakan</span><strong>${rekapan.total_pakan_kg.toLocaleString('id-ID')} Kg</strong></div>
                    <div class="summary-item">
                        <span>🐓 Sisa Ayam (di Kandang)</span>
                        <div class="editable-item">
                            <strong>${rekapan.sisa_ayam} ekor</strong>
                            <button class="btn btn-orange btn-sm" onclick="openKoreksiStokModal(${kloter.id}, ${rekapan.sisa_ayam})">Koreksi</button>
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
                        <option value="Obat">💊 Obat</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>

                    <div id="pakan-input-group" style="display: none; margin-top: 10px;">
                        <label style="display: block; margin-bottom: 5px;">⚖️ Jumlah Pakan (Kg)</label>
                        <input type="number" step="0.01" name="jumlah_pakan_kg" class="swal2-input" placeholder="Contoh: 50.5">
                    </div>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">💰 Harga (Rp)</label>
                    <input id="jumlah_pengeluaran_formatted" type="text" class="swal2-input" placeholder="Masukkan jumlah pengeluaran" required oninput="formatNumberInput(this)">
                    
                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">📅 Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal_pengeluaran" class="swal2-input" value="{{ date('Y-m-d') }}" required>

                    <label style="display: block; margin-bottom: 5px; margin-top: 10px;">📝 Keterangan (Opsional)</label>
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
    
    
    function openPanenModal(kloterId) {
        Swal.fire({
            title: 'Catat Panen Parsial',
            html: `<form id="form-panen" action="/manajemen-kloter/${kloterId}/panen" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="number" name="jumlah_panen" class="swal2-input" placeholder="Jumlah Ayam Dipanen" required min="1"><input type="date" name="tanggal_panen" class="swal2-input" value="{{ date('Y-m-d') }}" required></form>`,
            showCancelButton: true, confirmButtonText: 'Simpan Panen', confirmButtonColor: '#28a745',
            preConfirm: () => document.getElementById('form-panen').submit()
        });
    }
</script>
</body>
</html>