<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Kloter Pemeliharaan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; background-color: #f4f7f6; }
        .container { max-width: 1100px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { color: #333; }
        .btn { padding: 10px 20px; text-decoration: none; color: white; border-radius: 8px; font-weight: 500; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-green { background-color: #28a745; }
        .btn-blue { background-color: #007bff; }
        .btn-orange { background-color: #fd7e14; }
        .btn-red { background-color: #dc3545; font-size: 0.9em; }
        .btn-sm { padding: 5px 10px; font-size: 0.8em; }
        .table-wrapper { background: #fff; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e9ecef; white-space: nowrap; }
        th { background-color: #f8f9fa; font-size: 0.9em; text-transform: uppercase; color: #6c757d; }
        .status-aktif { color: #ffc107; font-weight: bold; }
        .status-selesai { color: #28a745; font-weight: bold; }
        .actions { display: flex; gap: 10px; align-items: center;}
        
        /* Gaya untuk Modal */
        .swal2-popup { width: 80% !important; max-width: 950px !important; }
        .modal-content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; text-align: left; }
        .modal-card { padding: 15px; border: 1px solid #eee; border-radius: 8px; }
        .modal-card h3 { margin-top: 0; font-size: 1.2em; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .modal-rekapan-table { width: 100%; font-size: 0.9em; }
        .modal-rekapan-table td { padding: 8px 5px; border-bottom: 1px solid #f2f2f2; }
        .modal-rekapan-table tr:last-child td { border-bottom: none; }
        .modal-summary-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 1.1em; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Kloter</h1>
            <a href="{{ route('manajemen.kloter.create') }}" class="btn btn-green">Tambah Kloter Baru</a>
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

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kloter</th>
                        <th>Status</th>
                        <th>Tanggal Mulai</th>
                        <th>Jumlah DOC Awal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kloters as $kloter)
                        <tr>
                            <td><strong>{{ $kloter->nama_kloter }}</strong></td>
                            <td>
                                @if ($kloter->status == 'Aktif')
                                    <span class="status-aktif">Belum Siap Panen</span>
                                @else
                                    <span class="status-selesai">Sudah Panen</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($kloter->tanggal_mulai)->format('d F Y') }}</td>
                            <td>{{ $kloter->jumlah_doc }} ekor</td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-blue" onclick="openDetailModal({{ $kloter->id }})">Detail</button>
                                    
                                    <!-- PERUBAHAN 1: Tombol diubah menjadi button biasa dengan onclick -->
                                    <button type="button" class="btn btn-orange" onclick="openUpdateStatusModal({{ $kloter->id }}, '{{ $kloter->status }}', '{{ $kloter->nama_kloter }}')">Ubah Status</button>

                                    <form action="{{ route('manajemen.kloter.destroy', $kloter->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus kloter ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; padding: 40px;">Belum ada data kloter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
    // PERUBAHAN 2: Fungsi ubah status diubah menjadi pop-up dengan pilihan
    function openUpdateStatusModal(kloterId, currentStatus, kloterName) {
        Swal.fire({
            title: `Ubah Status Kloter "${kloterName}"`,
            input: 'select',
            inputOptions: {
                'Aktif': 'Belum Siap Panen',
                'Selesai Panen': 'Sudah Panen'
            },
            inputValue: currentStatus, // Menandai status saat ini
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#fd7e14',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus memilih status!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika dikonfirmasi, buat form dinamis dan kirim
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/manajemen-kloter/${kloterId}/update-status`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const hiddenMethod = document.createElement('input');
                hiddenMethod.type = 'hidden';
                hiddenMethod.name = '_method';
                hiddenMethod.value = 'PUT';

                const hiddenToken = document.createElement('input');
                hiddenToken.type = 'hidden';
                hiddenToken.name = '_token';
                hiddenToken.value = csrfToken;

                const hiddenStatus = document.createElement('input');
                hiddenStatus.type = 'hidden';
                hiddenStatus.name = 'status';
                hiddenStatus.value = result.value; // Mengirim status yang dipilih

                form.appendChild(hiddenMethod);
                form.appendChild(hiddenToken);
                form.appendChild(hiddenStatus);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // ... (sisa kode JavaScript lainnya tetap sama) ...
    async function openDetailModal(kloterId) {
        Swal.fire({ title: 'Memuat data kloter...', didOpen: () => { Swal.showLoading() }, allowOutsideClick: false });

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
                            <button type="submit" class="btn btn-red btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>`;
            });
            if (kloter.kematian_ayams.length === 0) kematianHtml = '<tr><td colspan="3">Belum ada data.</td></tr>';

            let pengeluaranHtml = '';
            kloter.pengeluarans.forEach(item => {
                pengeluaranHtml += `<tr>
                    <td>${item.kategori}</td>
                    <td>Rp ${Number(item.jumlah_pengeluaran).toLocaleString('id-ID')}</td>
                    <td>
                        <form action="/pengeluaran/${item.id}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-red btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>`;
            });
            if (kloter.pengeluarans.length === 0) pengeluaranHtml = '<tr><td colspan="3">Belum ada data.</td></tr>';

            const modalHtml = `
                <div class="modal-content-grid">
                    <div class="modal-card">
                        <h3>📝 Input & Edit Data</h3>
                        <p><strong>DOC Awal:</strong> ${kloter.jumlah_doc} ekor <button class="btn btn-orange btn-sm" onclick="openEditDocModal(${kloter.id}, ${kloter.jumlah_doc})">Edit</button></p>
                        <hr>
                        <button class="btn btn-blue" style="width:100%; margin-bottom:10px;" onclick="openKematianModal(${kloter.id})">Input Jumlah Kematian</button>
                        <button class="btn btn-blue" style="width:100%;" onclick="openPengeluaranModal(${kloter.id})">Input Data Keperluan</button>
                    </div>
                    <div class="modal-card">
                        <h3>📜 Riwayat & Rekapan</h3>
                        <div style="max-height: 150px; overflow-y: auto; margin-bottom: 10px;">
                            <strong>Riwayat Kematian:</strong>
                            <table class="modal-rekapan-table"><thead><tr><th>Tanggal</th><th>Jumlah</th><th>Aksi</th></tr></thead><tbody>${kematianHtml}</tbody></table>
                        </div>
                        <div style="max-height: 150px; overflow-y: auto;">
                            <strong>Riwayat Pengeluaran:</strong>
                            <table class="modal-rekapan-table"><thead><tr><th>Kategori</th><th>Jumlah</th><th>Aksi</th></tr></thead><tbody>${pengeluaranHtml}</tbody></table>
                        </div>
                    </div>
                </div>
                <div class="modal-summary-box">
                    <div class="summary-item"><span>💰 Total Pengeluaran:</span><strong>Rp ${rekapan.total_pengeluaran.toLocaleString('id-ID')}</strong></div>
                    <div class="summary-item"><span>🐓 Jumlah Ayam Sekarang:</span><strong>${rekapan.sisa_ayam} ekor</strong></div>
                </div>`;

            Swal.fire({ title: `Detail Kloter: ${kloter.nama_kloter}`, html: modalHtml, showCloseButton: true, showConfirmButton: false });

        } catch (error) {
            Swal.fire('Error!', error.message, 'error');
        }
    }

    function openEditDocModal(kloterId, currentDoc) {
        Swal.fire({
            title: 'Edit Jumlah DOC Awal',
            html: `
                <form id="form-edit-doc" action="/manajemen-kloter/${kloterId}/update-doc" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="number" name="jumlah_doc" class="swal2-input" value="${currentDoc}" required min="0">
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            preConfirm: () => { document.getElementById('form-edit-doc').submit(); }
        });
    }

    function openKematianModal(kloterId) {
        Swal.fire({
            title: 'Catat Kematian Ayam',
            html: `
                <form id="form-kematian" action="/manajemen-kloter/${kloterId}/kematian" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="number" name="jumlah_mati" class="swal2-input" placeholder="Jumlah Mati" required min="1">
                    <input type="date" name="tanggal_kematian" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                    <textarea name="catatan" class="swal2-textarea" placeholder="Catatan (opsional)"></textarea>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => { document.getElementById('form-kematian').submit(); }
        });
    }

    function openPengeluaranModal(kloterId) {
        Swal.fire({
            title: 'Input Data Keperluan',
            html: `
                <form id="form-pengeluaran" action="/manajemen-kloter/${kloterId}/pengeluaran" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <select name="kategori" class="swal2-select" required><option value="">Pilih Kategori</option><option value="Pakan">Pakan</option><option value="Obat">Obat</option><option value="Lainnya">Lainnya</option></select>
                    <input type="number" name="jumlah_pengeluaran" class="swal2-input" placeholder="Harga (Rp)" required min="0">
                    <input type="date" name="tanggal_pengeluaran" class="swal2-input" value="{{ date('Y-m-d') }}" required>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => { document.getElementById('form-pengeluaran').submit(); }
        });
    }
</script>
</body>
</html>