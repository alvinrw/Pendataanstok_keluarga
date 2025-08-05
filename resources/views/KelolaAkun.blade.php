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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
        }
        
        .modal-content {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(240, 147, 251, 0.4);
        }
        
        .table-row {
            transition: all 0.3s ease;
        }
        
        .table-row:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .floating-label {
            transition: all 0.3s ease;
        }
        
        .input-field:focus ~ .floating-label,
        .input-field:not(:placeholder-shown) ~ .floating-label {
            transform: translateY(-1.5rem) scale(0.875);
            color: #667eea;
        }
        
        .role-badge {
            position: relative;
            overflow: hidden;
        }
        
        .role-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        
        .role-badge:hover::before {
            left: 100%;
        }
        
        .action-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .action-btn:hover::before {
            width: 100px;
            height: 100px;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-up {
            animation: slideInUp 0.6s ease-out;
        }
        
        .empty-state {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        /* Toast Notification Styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            max-width: 400px;
            z-index: 1000;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.hide {
            transform: translateX(400px);
        }
        
        .toast-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .toast-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .toast-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        /* Confirmation Modal Styles */
        .confirm-modal {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .confirm-icon {
            animation: bounce 0.6s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -8px, 0);
            }
            70% {
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -100%, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .toast-slide-in {
            animation: slideInDown 0.4s ease-out;
        }
    </style>
</head>
<body>
    <div class="min-h-screen py-8 px-4">
        <div class="container mx-auto max-w-6xl">
            
            <!-- Header -->
            <header class="glass-card rounded-3xl p-8 mb-8 animate-slide-up">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                                Kelola Akun Pengguna
                            </h1>
                            <p class="text-gray-500 mt-2 text-lg">Tambahkan, edit, atau hapus akun pengguna dengan mudah</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button id="addUserBtn" class="btn-primary text-white font-semibold py-3 px-6 rounded-2xl shadow-lg flex items-center gap-3">
                            <i class="fas fa-plus text-lg"></i>
                            <span>Tambah Akun</span>
                        </button>
                        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 text-gray-600 hover:text-purple-600 font-medium py-3 px-6 rounded-2xl hover:bg-white/50 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl animate-slide-up" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-table"></i>
                        Daftar Pengguna
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>Nama Pengguna
                                </th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-shield-alt mr-2"></i>Role
                                </th>
                                <th class="px-8 py-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-2"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($admins as $admin)
                            <tr class="table-row bg-white hover:bg-gray-50">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center shadow-md">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="text-lg font-semibold text-gray-900">{{ $admin->username }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $admin->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if ($admin->role === 'admin')
                                        <span class="role-badge inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-800 bg-gradient-to-r from-blue-100 to-blue-200 rounded-xl shadow-sm">
                                            <i class="fas fa-crown"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="role-badge inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-800 bg-gradient-to-r from-green-100 to-green-200 rounded-xl shadow-sm">
                                            <i class="fas fa-user-check"></i>
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($admin->id !== 2)
                                        <div class="flex justify-center gap-3">
                                            <button class="editBtn action-btn relative w-12 h-12 bg-gradient-to-r from-sky-400 to-sky-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" 
                                                title="Edit Pengguna"
                                                data-id="{{ $admin->id }}"
                                                data-username="{{ $admin->username }}"
                                                data-role="{{ $admin->role }}">
                                                <i class="fas fa-edit relative z-10"></i>
                                            </button>

                                            @if(Auth::id() !== $admin->id)
                                                <button class="deleteBtn action-btn relative w-12 h-12 bg-gradient-to-r from-red-400 to-red-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" 
                                                    title="Hapus Pengguna" 
                                                    data-id="{{ $admin->id }}">
                                                    <i class="fas fa-trash relative z-10"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-100 to-amber-200 text-amber-800 rounded-xl shadow-sm">
                                            <i class="fas fa-shield-alt"></i>
                                            <span class="text-sm font-medium">Super Admin</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-16">
                                    <div class="empty-state rounded-2xl mx-8 p-12 text-center">
                                        <div class="w-24 h-24 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <i class="fas fa-users-slash text-3xl text-white"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Pengguna</h3>
                                        <p class="text-gray-500">Belum ada akun pengguna yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Notifications -->
    <div id="toastContainer"></div>
    
    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black/60"></div>
        <div class="confirm-modal rounded-3xl shadow-2xl w-full max-w-md m-4 p-8 transform scale-95">
            <div class="text-center">
                <div class="confirm-icon w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Konfirmasi Penghapusan</h3>
                <p class="text-gray-600 mb-8">Apakah Anda yakin ingin menghapus akun ini? <br>Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-4 justify-center">
                    <button id="confirmCancel" class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-2xl transition-all duration-300 min-w-[100px]">
                        Batal
                    </button>
                    <button id="confirmDelete" class="py-3 px-6 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-2xl transition-all duration-300 min-w-[100px] shadow-lg">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black/60"></div>
        <div class="modal-content bg-white rounded-3xl shadow-2xl w-full max-w-lg m-4 transform scale-95">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-t-3xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Tambah Pengguna Baru</h3>
                    </div>
                    <button id="closeAddModal" class="text-white/80 hover:text-white text-3xl leading-none transition-colors duration-200">&times;</button>
                </div>
            </div>
            
            <form id="addForm" class="p-8">
                <div class="space-y-6">
                    <div class="relative">
                        <input type="text" id="addUsername" class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:bg-white transition-all duration-300 placeholder-transparent" placeholder="Username" required>
                        <label for="addUsername" class="floating-label absolute left-4 top-4 text-gray-500 font-medium pointer-events-none">Username</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="addPassword" class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:bg-white transition-all duration-300 placeholder-transparent" placeholder="Password" required>
                        <label for="addPassword" class="floating-label absolute left-4 top-4 text-gray-500 font-medium pointer-events-none">Password</label>
                    </div>
                    <div class="relative">
                        <select id="addRole" class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-purple-500 focus:bg-white transition-all duration-300" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <label class="absolute left-4 -top-2 text-sm text-gray-500 font-medium bg-white px-2">Role</label>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" id="cancelAddBtn" class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-2xl transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary text-white py-3 px-6 font-semibold rounded-2xl shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden modal">
        <div class="modal-backdrop fixed inset-0 bg-black/60"></div>
        <div class="modal-content bg-white rounded-3xl shadow-2xl w-full max-w-lg m-4 transform scale-95">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 p-6 rounded-t-3xl">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Edit Pengguna</h3>
                    </div>
                    <button id="closeEditModal" class="text-white/80 hover:text-white text-3xl leading-none transition-colors duration-200">&times;</button>
                </div>
            </div>
            
            <form id="editForm" class="p-8">
                <input type="hidden" id="editUserId">
                <div class="space-y-6">
                    <div class="relative">
                        <input type="text" id="editUsername" class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:bg-white transition-all duration-300 placeholder-transparent" placeholder="Username" required>
                        <label for="editUsername" class="floating-label absolute left-4 top-4 text-gray-500 font-medium pointer-events-none">Username</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="editPassword" class="input-field w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:bg-white transition-all duration-300 placeholder-transparent" placeholder="Password Baru">
                        <label for="editPassword" class="floating-label absolute left-4 top-4 text-gray-500 font-medium pointer-events-none">Password Baru</label>
                        <p class="text-sm text-gray-500 mt-2 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                    </div>
                    <div class="relative">
                        <select id="editRole" class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:border-blue-500 focus:bg-white transition-all duration-300" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                        <label class="absolute left-4 -top-2 text-sm text-gray-500 font-medium bg-white px-2">Role</label>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" id="cancelEditBtn" class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-2xl transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" class="btn-secondary text-white py-3 px-6 font-semibold rounded-2xl shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // --- Toast Notification System ---
        function showToast(message, type = 'success', duration = 4000) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const toastHTML = `
                <div id="${toastId}" class="toast toast-${type} rounded-2xl shadow-2xl text-white p-6 mb-4 toast-slide-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                ${type === 'success' ? '<i class="fas fa-check text-xl"></i>' : 
                                  type === 'error' ? '<i class="fas fa-times text-xl"></i>' : 
                                  '<i class="fas fa-exclamation text-xl"></i>'}
                            </div>
                            <div>
                                <div class="font-semibold text-lg">
                                    ${type === 'success' ? 'Berhasil!' : 
                                      type === 'error' ? 'Error!' : 'Peringatan!'}
                                </div>
                                <div class="text-white/90">${message}</div>
                            </div>
                        </div>
                        <button onclick="hideToast('${toastId}')" class="text-white/80 hover:text-white text-2xl leading-none ml-4">
                            &times;
                        </button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toast = document.getElementById(toastId);
            
            // Show toast
            setTimeout(() => toast.classList.add('show'), 100);
            
            // Auto hide
            setTimeout(() => hideToast(toastId), duration);
        }
        
        function hideToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 400);
            }
        }
        
        // Make hideToast globally available
        window.hideToast = hideToast;

        // --- Confirmation Modal System ---
        let deleteUserId = null;
        const confirmModal = document.getElementById('confirmModal');
        const confirmCancel = document.getElementById('confirmCancel');
        const confirmDelete = document.getElementById('confirmDelete');

        function showConfirmModal(userId) {
            deleteUserId = userId;
            showModal(confirmModal);
        }

        confirmCancel.addEventListener('click', () => {
            hideModal(confirmModal);
            deleteUserId = null;
        });

        confirmDelete.addEventListener('click', () => {
            if (deleteUserId) {
                performDelete(deleteUserId);
                hideModal(confirmModal);
                deleteUserId = null;
            }
        });

        function performDelete(userId) {
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
                showToast('Akun berhasil dihapus!', 'success');
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus akun.', 'error');
            });
        }

        // --- Helper Functions ---
        const showModal = (modalEl) => {
            modalEl.classList.remove('hidden');
            modalEl.classList.add('flex');
            setTimeout(() => {
                modalEl.querySelector('.modal-content, .confirm-modal').classList.remove('scale-95');
                modalEl.querySelector('.modal-content, .confirm-modal').classList.add('scale-100');
            }, 10);
        };

        const hideModal = (modalEl) => {
            modalEl.querySelector('.modal-content, .confirm-modal').classList.add('scale-95');
            modalEl.querySelector('.modal-content, .confirm-modal').classList.remove('scale-100');
            setTimeout(() => {
                modalEl.classList.add('hidden');
                modalEl.classList.remove('flex');
            }, 300);
        };
        
        // --- Add User Modal Logic ---
        const addModal = document.getElementById('addModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeAddModalBtn = document.getElementById('closeAddModal');
        const cancelAddBtn = document.getElementById('cancelAddBtn');
        const addForm = document.getElementById('addForm');
        
        addUserBtn.addEventListener('click', () => {
            addForm.reset();
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

            fetch(`/users`, {
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
                showToast('Akun baru berhasil ditambahkan!', 'success');
                setTimeout(() => location.reload(), 1500);
            })
            .catch((error) => {
                console.error('Error:', error);
                const errorMessage = error.message || 'Terjadi kesalahan saat menambahkan akun.';
                showToast(errorMessage, 'error');
            });
        });

        // --- Delete User Logic ---
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                showConfirmModal(userId);
            });
        });

        // --- Edit User Modal Logic ---
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
                showToast('Data berhasil diperbarui!', 'success');
                setTimeout(() => location.reload(), 1500);
            })
            .catch((error) => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menyimpan data.', 'error');
            });
        });
    });
    </script>

</body>
</html>