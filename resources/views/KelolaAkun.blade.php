<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun Pengguna</title>
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
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Kelola Akun Pengguna</h1>
                <p class="text-gray-500 mt-1">Tambahkan, edit, atau hapus akun pengguna.</p>
            </div>
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </header>

        <!-- Users Table -->
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
                                <button class="editBtn text-yellow-500 hover:text-yellow-700 p-2 transition-colors duration-200" title="Edit"
                                    data-id="{{ $admin->id }}"
                                    data-username="{{ $admin->username }}"
                                    data-role="{{ $admin->role }}">
                                    <i class="fas fa-edit fa-lg"></i>
                                </button>
                                <button class="deleteBtn text-red-500 hover:text-red-700 p-2 transition-colors duration-200" title="Hapus" data-id="{{ $admin->id }}">
                                    <i class="fas fa-trash fa-lg"></i>
                                </button>
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

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg m-4 p-6 z-10 transform scale-95">
            <div class="flex justify-between items-center border-b pb-3 mb-5">
                <h3 class="text-xl font-semibold">Edit Pengguna</h3>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">&times;</button>
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
    
    <!-- (Modal konfirmasi hapus dan notifikasi toast bisa ditambahkan di sini jika diperlukan) -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Modal Elements ---
        const editModal = document.getElementById('editModal');
        const closeEditModalBtn = document.getElementById('closeEditModal');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        // --- Form Elements ---
        const editForm = document.getElementById('editForm');
        const editUserId = document.getElementById('editUserId');
        const editUsername = document.getElementById('editUsername');
        const editPassword = document.getElementById('editPassword');
        const editRole = document.getElementById('editRole');

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

        // --- Event Listeners for Edit Modal ---
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', function () {
                const data = this.dataset;
                
                // Populate the modal form with data from the button
                editUserId.value = data.id;
                editUsername.value = data.username;
                editRole.value = data.role;
                editPassword.value = ''; // Clear password field by default

                showModal(editModal);
            });
        });

        closeEditModalBtn.addEventListener('click', () => hideModal(editModal));
        cancelEditBtn.addEventListener('click', () => hideModal(editModal));
        
        // --- AJAX Form Submission for Edit ---
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const id = editUserId.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Collect form data
            const formData = {
                username: editUsername.value,
                role: editRole.value,
            };

            // Only include password if it's not empty
            if (editPassword.value) {
                formData.password = editPassword.value;
            }

            console.log('Data to be sent (AJAX):', { id, ...formData });
            alert('Data siap dikirim via AJAX. Cek console log untuk detailnya.');
            
            // --- Placeholder for your AJAX call ---
            /*
            fetch(`/users/${id}`, { // Ganti dengan URL endpoint Anda
                method: 'PUT', // atau 'PATCH'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                hideModal(editModal);
                // Tampilkan notifikasi toast sukses di sini
                // location.reload(); // atau perbarui tabel secara dinamis
            })
            .catch((error) => {
                console.error('Error:', error);
                // Tampilkan notifikasi toast error di sini
            });
            */
            
            hideModal(editModal); // Sembunyikan modal setelah submit (untuk demo)
        });
    });
    </script>

</body>
</html>
