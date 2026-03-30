<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Pengaduan Sarana</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 20px 0;
            background: #fffdf5; /* Cream Background */
        }

        .register-card {
            background: white;
            padding: 30px 40px;
            border-radius: 20px;
            width: 400px;
            box-shadow: 0 15px 35px rgba(128, 0, 0, 0.1);
            border-top: 5px solid #800000;
        }

        h2 {
            text-align: center;
            color: #800000;
            margin-bottom: 25px;
            font-weight: 700;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 0.85rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
        }

        input:focus { border-color: #800000; }

        input[type="file"] {
            border: none;
            padding: 5px 0;
            font-size: 0.8rem;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #800000;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover { background: #a52a2a; }

        .error-list {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.8rem;
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
        }

        .footer-link a {
            color: #800000;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2>DAFTAR SISWA</h2>

    @if ($errors->any())
    <div class="error-list">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/register" method="POST" enctype="multipart/form-data">
        @csrf

        <label>NIS</label>
        <input type="text" name="nis" placeholder="Contoh: 12200987" required>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan Nama Anda" required>

        <label>Kelas</label>
        <input type="text" name="kelas" placeholder="Contoh: XII RPL 1" required>

        <label>Foto Profile</label>
        <input type="file" name="foto_profile" accept="image/*">

        <label>Password</label>
        <input type="password" name="password" placeholder="Buat Password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" placeholder="Ulangi Password" required>

        <button type="submit">Daftar Sekarang</button>
    </form>

    <div class="footer-link">
        <a href="/login"><i class="bi bi-arrow-left"></i> Sudah punya akun? Login</a>
    </div>
</div>

</body>
</html>