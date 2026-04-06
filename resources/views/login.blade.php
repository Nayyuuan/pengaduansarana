<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengaduan Sarana</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #fdfaf0; 
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.1);
            border-top: 5px solid #800000; 
        }

        h2 {
            text-align: center;
            color: #800000;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 0.9rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus { border-color: #800000; box-shadow: 0 0 5px rgba(128, 0, 0, 0.2); }

        button {
            width: 100%;
            padding: 12px;
            background: #800000; /* Maroon */
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            transition: 0.3s;
        }

        button:hover { background: #a52a2a; transform: translateY(-2px); }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
        }

        .footer-text a {
            color: #800000;
            text-decoration: none;
            font-weight: bold;
        }

        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>LOGIN</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label><i class="bi bi-person-fill"></i> Username / NIS</label>
            <input type="text" name="username" placeholder="Masukkan Username/NIS" required>
        </div>

        <div class="form-group">
            <label><i class="bi bi-lock-fill"></i> Password</label>
            <input type="password" name="password" placeholder="********" required>
        </div>

        <button type="submit">Masuk Sekarang</button>
    </form>

    <div class="footer-text">
        <p>Belum punya akun? <br> <a href="/register">Daftar Akun Siswa</a></p>
    </div>
</div>

</body>
</html>