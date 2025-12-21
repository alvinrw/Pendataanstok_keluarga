<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke Sistem Manajemen Penjualan">
    <title>Login - Sistem Manajemen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1e40af;
            --blue-50: #eff6ff;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-700: #374151;
            --gray-900: #111827;
            --danger: #ef4444;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--blue-50) 0%, var(--white) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--white);
            border-radius: 16px;
            padding: 48px 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            color: var(--gray-900);
            font-size: 2rem;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .login-header p {
            color: var(--gray-700);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 0.875rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-100);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s;
            background: var(--white);
            color: var(--gray-900);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .login-btn:hover {
            background: var(--primary-blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .success-message,
        .error-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.875rem;
            display: none;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
            }

            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Selamat Datang</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        <div class="success-message" id="successMessage">
            Login berhasil! Selamat datang kembali.
        </div>

        <div class="error-message" id="errorMessage">
            Username atau password salah. Silakan coba lagi.
        </div>

        <form action="/login-admin" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="login-btn">Masuk</button>
        </form>
    </div>
</body>

</html>