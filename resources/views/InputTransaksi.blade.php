<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Transaksi Modern</title>
    
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Custom styles to enhance the UI */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom styling for the select dropdown arrow */
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 2.5rem;
        }
    </style>
</head>
<body class="bg-slate-100">

    <div class="min-h-screen flex items-center justify-center p-4">
        
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-8 space-y-6">
            
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi</h1>
                    <p class="text-gray-500">Catat pengeluaranmu dengan mudah.</p>
                </div>
                <!-- Back Button -->
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Transaction Form -->
            <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-5">
                @csrf

                <!-- Date Input -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" required 
                           class="w-full px-4 py-3 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                           value="{{ date('Y-m-d') }}">
                </div>

                <!-- Category Select -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori" required
                            class="w-full px-4 py-3 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 custom-select">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        <option value="Makanan">🍔 Makanan</option>
                        <option value="Bensin">⛽ Bensin</option>
                        <option value="Minuman">🥤 Minuman</option>
                        <option value="Jajan">🛍️ Jajan</option>
                        <option value="Kebutuhan Kampus">📚 Kebutuhan Kampus</option>
                        <option value="Hiburan">🎬 Hiburan</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                </div>
                
                <!-- Amount Input -->
                <div>
                    <label for="jumlah_display" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <!-- This input is for display purposes, showing the formatted number -->
                        <input type="text" id="jumlah_display" required placeholder="50.000"
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300">
                        <!-- This hidden input holds the raw number value for the form submission -->
                        <input type="hidden" name="jumlah" id="jumlah">
                    </div>
                </div>

                <!-- Description Textarea -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400">(opsional)</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Contoh: Makan siang di warung..."
                              class="w-full px-4 py-3 bg-slate-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"></textarea>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105 duration-300">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get the display input and the hidden input elements
        const jumlahDisplay = document.getElementById('jumlah_display');
        const jumlahHidden = document.getElementById('jumlah');

        // Add an event listener to the display input for real-time formatting
        jumlahDisplay.addEventListener('input', function(e) {
            // 1. Get the raw value and remove any non-digit characters
            let rawValue = e.target.value.replace(/[^0-9]/g, '');
            
            // 2. Update the hidden input with the raw, unformatted number
            jumlahHidden.value = rawValue;

            // 3. Format the number with dots for display and update the visible input
            if (rawValue) {
                const formattedValue = parseInt(rawValue, 10).toLocaleString('id-ID');
                e.target.value = formattedValue;
            } else {
                e.target.value = '';
            }
        });
    </script>

</body>
</html>
