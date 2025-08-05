<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun Pengguna</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
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
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kelola Akun Pengguna</h1>
                <p class="text-gray-500 mt-1">Tambahkan, edit, atau hapus akun pengguna.</p>
            </div>
            <div class="flex items-center gap-4">
                <button id="addUserBtn" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Akun</span>
                </button>
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </header>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4">Nama</th>
                            <th scope="col" class="px-6 py-4">Role</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $admin->username }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($admin->role === 'admin')
                                    <span class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Admin</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">User</span>
                                @endif
                            </td>
                <td class="px-6 py-4 text-center space-x-2">
                    {{-- CEK APAKAH INI AKUN SUPER ADMIN (ID=2) --}}
                    @if($admin->id !== 2)
                        {{-- JIKA BUKAN, TAMPILKAN TOMBOL NORMAL --}}
                        <button class="editBtn text-sky-500 hover:text-sky-700 p-2 transition-colors duration-200" title="Ubah"
                            data-id="{{ $admin->id }}"
                            data-username="{{ $admin->username }}"
                            data-role="{{ $admin->role }}">
                            <i class="fas fa-edit fa-lg"></i>
                        </button>

                        {{-- Tetap pertahankan logika agar user tidak bisa hapus diri sendiri --}}
                        @if(Auth::id() !== $admin->id)
                            <button class="deleteBtn text-red-500 hover:text-red-700 p-2 transition-colors duration-200" title="Hapus" data-id="{{ $admin->id }}">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        @endif
                    @else
                        {{-- JIKA INI ADALAH SUPER ADMIN, TAMPILKAN TEKS SAJA --}}
                        <span class="text-xs text-gray-400 italic" title="Akun Super Admin tidak dapat diubah atau dihapus">
                            Dilindungi
                        </span>
                    @endif
                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-gray-500">
                                <i class="fas fa-users-slash fa-3x mb-3"></i>
                                <p>Belum ada akun pengguna yang ditambahkan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="addModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg m-4 p-6 z-10 transform scale-95">
            <div class="flex justify-between items-center border-b pb-3 mb-5">
                <h3 class="text-xl font-semibold">Tambah Pengguna Baru</h3>
                <button id="closeAddModal" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
            </div>
            <form id="addForm">
                <div class="space-y-4">
                    <div>
                        <label for="addUsername" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="addUsername" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="addPassword" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="addPassword" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="addRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="addRole" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelAddBtn" class="py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">Batal</button>
                    <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg m-4 p-6 z-10 transform scale-95">
            <div class="flex justify-between items-center border-b pb-3 mb-5">
                <h3 class="text-xl font-semibold">Edit Pengguna</h3>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
            </div>
            <form id="editForm">
                <input type="hidden" id="editUserId">
                <div class="space-y-4">
                    <div>
                        <label for="editUsername" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="editUsername" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="editPassword" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" id="editPassword" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Kosongkan jika tidak ingin diubah">
                        <p class="text-xs text-gray-500 mt-1">Hanya isi jika Anda ingin mengganti password.</p>
                    </div>
                    <div>
                        <label for="editRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="editRole" class="w-full px-4 py-2 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelEditBtn" class="py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">Batal</button>
                    <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
        
        // --- BARU: Add User Modal Logic ---
        const addModal = document.getElementById('addModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeAddModalBtn = document.getElementById('closeAddModal');
        const cancelAddBtn = document.getElementById('cancelAddBtn');
        const addForm = document.getElementById('addForm');
        
        addUserBtn.addEventListener('click', () => {
            addForm.reset(); // Kosongkan form setiap kali dibuka
            showModal(addModal);
        });
        
        closeAddModalBtn.addEventListener('click', () => hideModal(addModal));
        cancelAddBtn.addEventListener('click', () => hideModal(addModal));

        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = {
                username: document.getElementById('addUsername').value,
                password: document.getElementById('addPassword').value,
                role: document.getElementById('addRole').value,
            };

            console.log('Data to be sent (AJAX - Add):', formData);

            fetch(`/users`, { // Asumsi endpoint untuk membuat user adalah POST /users
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                hideModal(addModal);
                alert('Akun baru berhasil ditambahkan!');
                location.reload(); // Refresh halaman untuk menampilkan data baru
            })
            .catch((error) => {
                console.error('Error:', error);
                // Menampilkan pesan error yang lebih spesifik jika ada
                const errorMessage = error.message || 'Terjadi kesalahan saat menambahkan akun.';
                alert(errorMessage);
            });
        });

// --- Delete User Logic ---
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', function () {
        const userId = this.dataset.id;

        if (confirm('Yakin ingin menghapus akun ini?')) {
            fetch(`/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menghapus akun.');
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                alert('Akun berhasil dihapus!');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus akun.');
            });
        }
    });
});

        // --- Edit User Modal Logic (Existing) ---
        const editModal = document.getElementById('editModal');
        const closeEditModalBtn = document.getElementById('closeEditModal');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const editForm = document.getElementById('editForm');
        
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', function () {
                const data = this.dataset;
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editUsername').value = data.username;
                document.getElementById('editRole').value = data.role;
                document.getElementById('editPassword').value = ''; 
                showModal(editModal);
            });
        });

        closeEditModalBtn.addEventListener('click', () => hideModal(editModal));
        cancelEditBtn.addEventListener('click', () => hideModal(editModal));
        
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.getElementById('editUserId').value;
            const editPassword = document.getElementById('editPassword').value;

            const formData = {
                username: document.getElementById('editUsername').value,
                role: document.getElementById('editRole').value,
            };

            if (editPassword) {
                formData.password = editPassword;
            }

            console.log('Data to be sent (AJAX - Edit):', { id, ...formData });
            
            fetch(`/users/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menyimpan perubahan.');
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                hideModal(editModal);
                alert('Data berhasil diperbarui!');
                location.reload(); 
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data.');
            });
        });
    });
    </script>

</body>
</html>