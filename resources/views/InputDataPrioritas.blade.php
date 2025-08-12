<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Jadwal - My Schedule</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 10;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 12px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .header p {
            opacity: 0.95;
            font-size: 1.1rem;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .form-container {
            padding: 50px 40px;
            position: relative;
            z-index: 5;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 0 24px 24px;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            background: transparent;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
            position: relative;
        }

        .required {
            color: #e74c3c;
            margin-left: 2px;
        }

        input, textarea, select {
            width: 100%;
            padding: 18px 20px;
            border: 2px solid #e8ecf4;
            border-radius: 16px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.98);
            font-family: inherit;
            position: relative;
            z-index: 2;
            box-sizing: border-box;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
            transform: translateY(-2px);
            background: rgba(79, 172, 254, 0.02);
        }

        input:hover, textarea:hover, select:hover {
            border-color: #c3d4e8;
            transform: translateY(-1px);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        .time-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .priority-options {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .priority-option {
            flex: 1;
        }

        .priority-option input[type="radio"] {
            display: none;
        }

        .priority-label {
            display: block;
            padding: 16px 12px;
            text-align: center;
            border: 2px solid #e8ecf4;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .priority-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .priority-option input[type="radio"]:checked + .priority-label {
            color: white;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .priority-low:checked + .priority-label {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-color: #2ecc71;
        }

        .priority-medium:checked + .priority-label {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border-color: #f39c12;
        }

        .priority-high:checked + .priority-label {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-color: #e74c3c;
        }

        .button-group {
            display: flex;
            gap: 20px;
            margin-top: 45px;
        }

        .btn {
            flex: 1;
            padding: 20px 24px;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            font-family: inherit;
        }
        
        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.4);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .back-btn {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 2px solid #e8ecf4;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 20px 24px;
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.1rem;
        }

        .back-btn:hover {
            background: rgba(108, 117, 125, 0.15);
            transform: translateY(-2px);
            border-color: #c3d4e8;
        }

        /* Enhanced notification styles */
        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .notification-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .success-notification {
            background: white;
            border-radius: 24px;
            padding: 40px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            position: relative;
            overflow: hidden;
        }

        .success-notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            animation: bounce 0.6s ease 0.3s both;
        }

        .success-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .success-message {
            font-size: 1.1rem;
            color: #7f8c8d;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .notification-btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .notification-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        }

        .notification-btn.secondary {
            background: transparent;
            color: #6c757d;
            border: 2px solid #e8ecf4;
        }

        .notification-btn.secondary:hover {
            background: #f8f9fa;
            border-color: #c3d4e8;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes popIn {
            to { transform: scale(1); }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0, -20px, 0); }
            70% { transform: translate3d(0, -10px, 0); }
            90% { transform: translate3d(0, -4px, 0); }
        }

        /* Form validation styles */
        .form-group.error input,
        .form-group.error textarea,
        .form-group.error select {
            border-color: #e74c3c;
            box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.15);
            background: rgba(255, 255, 255, 0.98);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 8px;
            display: none;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 3px solid #e74c3c;
        }

        .form-group.error .error-message {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 20px;
            }

            .form-container {
                padding: 30px 25px;
            }

            .time-group {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .priority-options {
                flex-direction: column;
                gap: 10px;
            }

            .button-group {
                flex-direction: column;
                gap: 15px;
            }

            .header {
                padding: 30px 25px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .success-notification {
                padding: 30px 25px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 Input Jadwal Baru</h1>
            <p>Tambahkan kegiatan ke dalam jadwal Anda dengan mudah</p>
        </div>

        <div class="form-container">
            <form id="scheduleForm" action="{{ route('jadwal.store') }}" method="POST">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                @csrf

                <div class="form-group">
                    <label for="activityName">Nama Kegiatan <span class="required">*</span></label>
                    <input type="text" id="activityName" name="activityName" required placeholder="Contoh: Meeting dengan klien">
                    <div class="error-message">Nama kegiatan harus diisi</div>
                </div>

                <div class="form-group">
                    <label for="date">Tanggal <span class="required">*</span></label>
                    <input type="date" id="date" name="date" required>
                    <div class="error-message">Tanggal harus diisi</div>
                </div>

                <div class="form-group">
                    <label>Waktu <span class="required">*</span></label>
                    <div class="time-group">
                        <div>
                            <label for="startTime" style="font-size: 0.9rem; margin-bottom: 8px; color: #6c757d;">Waktu Mulai</label>
                            <input type="time" id="startTime" name="startTime" required>
                        </div>
                    </div>
                    <div class="error-message">Waktu mulai harus diisi</div>
                </div>

                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" id="location" name="location" placeholder="Contoh: Ruang meeting lantai 2">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi atau Catatan</label>
                    <textarea id="description" name="description" placeholder="Tambahkan catatan atau detail kegiatan..."></textarea>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <div class="priority-options">
                        <div class="priority-option">
                            <input type="radio" id="low" name="priority" value="low" class="priority-low">
                            <label for="low" class="priority-label">Rendah</label>
                        </div>
                        <div class="priority-option">
                            <input type="radio" id="medium" name="priority" value="medium" class="priority-medium" checked>
                            <label for="medium" class="priority-label">Sedang</label>
                        </div>
                        <div class="priority-option">
                            <input type="radio" id="high" name="priority" value="high" class="priority-high">
                            <label for="high" class="priority-label">Tinggi</label>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                   <a href="{{ route('welcome') }}" class="back-btn">← Kembali ke Menu Utama</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        💾 Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="notification-overlay" id="notificationOverlay">
        <div class="success-notification">
            <div class="success-icon">✅</div>
            <h3 class="success-title">Berhasil!</h3>
            <p class="success-message">Jadwal baru telah berhasil ditambahkan ke dalam sistem Anda.</p>
            <div style="margin-top: 25px;">
                <button class="notification-btn" onclick="goToMainMenu()">Kembali ke Menu</button>
                <button class="notification-btn secondary" onclick="addAnother()">Tambah Lagi</button>
            </div>
        </div>
    </div>

<script>
    // Menunggu sampai seluruh struktur halaman (DOM) selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {

        // Set tanggal hari ini sebagai default dan fokus ke input pertama
        document.getElementById('date').valueAsDate = new Date();
        document.getElementById('activityName').focus();

        // Fungsi validasi form (tidak berubah)
        function validateForm() {
            let isValid = true;
            const requiredFields = ['activityName', 'date', 'startTime'];
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const formGroup = field.closest('.form-group');
                if (!field.value.trim()) {
                    formGroup.classList.add('error');
                    isValid = false;
                } else {
                    formGroup.classList.remove('error');
                }
            });
            return isValid;
        }

        // Fungsi notifikasi (tidak berubah)
        function showSuccessNotification() {
            document.getElementById('notificationOverlay').classList.add('show');
        }

        function hideNotification() {
            document.getElementById('notificationOverlay').classList.remove('show');
        }

        window.goToMainMenu = function() {
            window.location.href = "{{ route('welcome') }}";
        }

        window.addAnother = function() {
            hideNotification();
            document.getElementById('scheduleForm').reset();
            document.getElementById('date').valueAsDate = new Date();
            document.getElementById('medium').checked = true;
            document.getElementById('activityName').focus();
        }

        // Handler utama untuk submit form
        const scheduleForm = document.getElementById('scheduleForm');
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah refresh halaman
                
                if (!validateForm()) {
                    return;
                }

                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('submitBtn');

                // Non-aktifkan tombol untuk mencegah klik ganda
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Menyimpan...';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {
                    if (!response.ok) {
                        const errorData = await response.json().catch(() => null);
                        throw errorData || new Error(`Server merespons dengan status ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    showSuccessNotification(); // Tampilkan popup sukses yang cantik
                })
                .catch(error => {
                    console.error('Error Details:', error);
                    let userMessage = 'Gagal menyimpan jadwal! Terjadi kesalahan tak terduga.';
                    if (error && error.errors) {
                        const firstErrorKey = Object.keys(error.errors)[0];
                        userMessage = error.errors[firstErrorKey][0];
                    } else if (error && error.message) {
                        userMessage = error.message;
                    }
                    alert(`Terjadi Kesalahan:\n\n${userMessage}`);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '💾 Simpan Jadwal';
                });
            });
        }

        // Event listener lainnya (tidak berubah)
        const notificationOverlay = document.getElementById('notificationOverlay');
        if (notificationOverlay) {
            notificationOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    hideNotification();
                }
            });
        }

        document.querySelectorAll('input[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                const formGroup = this.closest('.form-group');
                if (!this.value.trim()) {
                    formGroup.classList.add('error');
                } else {
                    formGroup.classList.remove('error');
                }
            });
            field.addEventListener('input', function() {
                const formGroup = this.closest('.form-group');
                if (this.value.trim()) {
                    formGroup.classList.remove('error');
                }
            });
        });

    }); // Akhir dari event listener DOMContentLoaded
</script>
</body>
</html>