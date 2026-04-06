<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - SIPESAR</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --maroon: #800000;
            --maroon-light: #a52a2a;
            --cream: #fdfaf0;
            --dark: #2d3436;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--cream);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 10%;
        }

        .content-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 60px;
            max-width: 1100px;
            width: 100%;
        }

        .text-content {
            flex: 1;
            text-align: left;
        }

        .text-content h1 {
            font-size: 3.2rem;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--maroon);
            font-weight: 800;
        }

        .text-content p {
            font-size: 1.1rem;
            color: #636e72;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .hero-image {
            flex: 1;
            text-align: right;
        }

        .hero-image img {
            width: 100%;
            max-width: 480px;
            filter: drop-shadow(0 20px 40px rgba(128, 0, 0, 0.1));
        }

        .cta-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-main {
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-maroon {
            background: var(--maroon);
            color: white;
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.2);
        }

        .btn-secondary-outline {
            border: 2px solid var(--maroon);
            color: var(--maroon);
            background: transparent;
        }

        .btn-main:hover {
            transform: translateY(-5px);
            opacity: 0.9;
        }

        .footer {
            padding: 30px;
            text-align: center;
            font-size: 0.85rem;
            color: #b2bec3;
        }

        /* Responsif untuk HP */
        @media (max-width: 992px) {
            .content-flex {
                flex-direction: column-reverse;
                text-align: center;
                gap: 40px;
            }
            .text-content { text-align: center; }
            .hero-image { text-align: center; }
            .cta-buttons { justify-content: center; }
            .text-content h1 { font-size: 2.5rem; }
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <div class="content-flex">
            <div class="text-content">
                <h1>Sampaikan Keluhan Anda, Bangun Sekolah Lebih Baik.</h1>
                <p>Laporkan setiap kerusakan sarana sekolah secara cepat, transparan, dan terintegrasi langsung dengan petugas sarpras.</p>
                
                <div class="cta-buttons">
                    <a href="/login" class="btn-main btn-primary-maroon">
                        <i class="bi bi-box-arrow-in-right"></i> Login Masuk
                    </a>
                    <a href="/register" class="btn-main btn-secondary-outline">
                        <i class="bi bi-person-plus"></i> Daftar Siswa
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Student Illustration">
            </div>
        </div>
    </div>

</body>
</html>