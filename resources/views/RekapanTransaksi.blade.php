<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapan Transaksi Modern</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom styles for smooth transitions and modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .toast-notification {
            transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-100 text-gray-800">

    <div class="container mx-auto p-4 md:p-8">

        <!-- Header -->
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Rekapan Transaksi</h1>
            <a href="{{ route('welcome') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </header>

        <!-- Summary & Filter Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Pengeluaran Card -->
            <div
                class="lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg flex flex-col justify-center">
                <p class="text-lg opacity-80">Total Pengeluaran</p>
                <p class="text-4xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                @if(request('tanggal_mulai') && request('tanggal_selesai'))
                    <p class="text-sm opacity-80 mt-2">Periode
                        {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse(request('tanggal_selesai'))->format('d M Y') }}</p>
                @else
                    <p class="text-sm opacity-80 mt-2">Semua transaksi tercatat</p>
                @endif
            </div>

            <!-- Filter Card -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-md">
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2"><i
                        class="fas fa-filter text-gray-400"></i> Filter Transaksi</h3>
                <form action="{{ route('transaksi.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-600 mb-1">Tanggal
                            Mulai</label>
                        <input type="date"
                            class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="md:col-span-2">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-600 mb-1">Tanggal
                            Selesai</label>
                        <input type="date"
                            class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            id="tanggal_selesai" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="md:col-span-1 flex gap-2">
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition-colors">Filter</button>
                        <a href="{{ route('transaksi.index') }}"
                            class="w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm transition-colors text-center">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 whitespace-nowrap">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3">Jumlah</th>
                            <th scope="col" class="px-6 py-3">Deskripsi</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $item)
                            <tr class="bg-white border-b hover:bg-gray-50 whitespace-nowrap">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $item->kategori }}</td>
                                <td class="px-6 py-4 font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $item->deskripsi ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="editBtn text-yellow-500 hover:text-yellow-700 p-2"
                                        data-id="{{ $item->id }}" data-tanggal="{{ $item->tanggal }}"
                                        data-kategori="{{ $item->kategori }}" data-jumlah="{{ $item->jumlah }}"
                                        data-deskripsi="{{ $item->deskripsi }}">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </button>
                                    <button class="deleteBtn text-red-500 hover:text-red-700 p-2" data-id="{{ $item->id }}">
                                        <i class="fas fa-trash fa-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p>Tidak ada data transaksi ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg m-4 p-6 z-10 transform scale-95">
            <div class="flex justify-between items-center border-b pb-3 mb-5">
                <h3 class="text-xl font-semibold">Edit Transaksi</h3>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form id="editForm">
                <input type="hidden" id="editId">
                <div class="space-y-4">
                    <div>
                        <label for="editTanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" id="editTanggal"
                            class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label for="editKategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="editKategori" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg"
                            required>
                            <option value="Makanan">Makanan</option>
                            <option value="Bensin">Bensin</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Jajan">Jajan</option>
                            <option value="Kebutuhan Kampus">Kebutuhan Kampus</option>
                            <option value="Hiburan">Hiburan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="editJumlahDisplay"
                            class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                            <input type="text" id="editJumlahDisplay"
                                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-gray-300 rounded-lg" required>
                            <input type="hidden" id="editJumlah">
                        </div>
                    </div>
                    <div>
                        <label for="editDeskripsi"
                            class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="editDeskripsi" rows="3"
                            class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelEditBtn"
                        class="py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">Batal</button>
                    <button type="submit"
                        class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4 p-6 z-10 transform scale-95">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 fa-lg"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-5">Hapus Transaksi</h3>
                <p class="text-sm text-gray-500 mt-2">Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat
                    dibatalkan.</p>
            </div>
            <div class="flex justify-center gap-4 mt-6">
                <button type="button" id="cancelDeleteBtn"
                    class="py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg w-28">Batal</button>
                <button type="button" id="confirmDeleteBtn"
                    class="py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg w-28">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-5 right-5 z-50 hidden toast-notification transform translate-x-full">
        <div id="toast-content"
            class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // --- Modal Elements ---
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            const closeEditModalBtn = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // --- Form Elements ---
            const editForm = document.getElementById('editForm');
            const editId = document.getElementById('editId');
            const editTanggal = document.getElementById('editTanggal');
            const editKategori = document.getElementById('editKategori');
            const editJumlahDisplay = document.getElementById('editJumlahDisplay');
            const editJumlah = document.getElementById('editJumlah');
            const editDeskripsi = document.getElementById('editDeskripsi');

            // --- Toast Elements ---
            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toast-content');
            let deleteTargetId = null;

            // --- Helper Functions ---
            const showModal = (modalEl) => {
                modalEl.classList.remove('hidden');
                modalEl.classList.add('flex');
                setTimeout(() => {
                    modalEl.querySelector('.z-10').classList.remove('scale-95');
                    modalEl.querySelector('.z-10').classList.add('scale-100');
                }, 10);
            };

            const hideModal = (modalEl) => {
                modalEl.querySelector('.z-10').classList.add('scale-95');
                modalEl.querySelector('.z-10').classList.remove('scale-100');
                setTimeout(() => {
                    modalEl.classList.add('hidden');
                    modalEl.classList.remove('flex');
                }, 300);
            };

            const showToast = (message, isSuccess = true) => {
                const icon = isSuccess
                    ? `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg"><i class="fas fa-check"></i></div>`
                    : `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg"><i class="fas fa-times"></i></div>`;

                toastContent.innerHTML = `${icon}<div class="ml-3 text-sm font-normal">${message}</div>`;
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.remove('translate-x-full'), 10);
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.classList.add('hidden'), 500);
                }, 3000);
            };

            const formatNumber = (inputEl, hiddenEl) => {
                let rawValue = inputEl.value.replace(/[^0-9]/g, '');
                hiddenEl.value = rawValue;
                if (rawValue) {
                    inputEl.value = parseInt(rawValue, 10).toLocaleString('id-ID');
                } else {
                    inputEl.value = '';
                }
            };

            editJumlahDisplay.addEventListener('input', () => formatNumber(editJumlahDisplay, editJumlah));

            // --- Event Listeners for Modals ---
            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const data = this.dataset;
                    editId.value = data.id;
                    editTanggal.value = new Date(data.tanggal).toISOString().split('T')[0];
                    editKategori.value = data.kategori;
                    editDeskripsi.value = data.deskripsi;

                    // Set and format the amount
                    editJumlahDisplay.value = data.jumlah;
                    formatNumber(editJumlahDisplay, editJumlah);

                    showModal(editModal);
                });
            });

            document.querySelectorAll('.deleteBtn').forEach(button => {
                button.addEventListener('click', function () {
                    deleteTargetId = this.dataset.id;
                    showModal(deleteModal);
                });
            });

            closeEditModalBtn.addEventListener('click', () => hideModal(editModal));
            cancelEditBtn.addEventListener('click', () => hideModal(editModal));
            cancelDeleteBtn.addEventListener('click', () => hideModal(deleteModal));

            // --- AJAX Form Submission ---
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const id = editId.value;
                fetch(`/transaksi/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tanggal: editTanggal.value,
                        kategori: editKategori.value,
                        jumlah: editJumlah.value,
                        deskripsi: editDeskripsi.value,
                    })
                })
                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                    .then(({ ok, data }) => {
                        if (ok) {
                            hideModal(editModal);
                            showToast('Data berhasil diperbarui!');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            const errorMessages = Object.values(data.errors || {}).join('<br>');
                            showToast('Gagal update: <br>' + (errorMessages || 'Cek kembali input Anda.'), false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan.', false);
                    });
            });

            confirmDeleteBtn.addEventListener('click', function () {
                if (!deleteTargetId) return;
                fetch(`/transaksi/${deleteTargetId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                    .then(({ ok, data }) => {
                        hideModal(deleteModal);
                        if (ok && data.success) {
                            showToast('Data berhasil dihapus!');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showToast(data.message || 'Gagal menghapus data.', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hideModal(deleteModal);
                        showToast('Terjadi kesalahan.', false);
                    });
            });
        });
    </script>
</body>

</html>