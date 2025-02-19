<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sisrek Food App')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
        }
        
        .navbar {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand-container {
            display: flex;
            align-items: center;
        }
        .navbar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: white !important;
            margin: 0;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #ffe6e6 !important;
        }

        footer {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
            font-size: 0.9rem;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
        }
        footer span {
            color: #ffe6e6;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
            color: white;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .header h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .header p {
            font-size: 1.5rem;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background-color: #ff758c;
            border: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 30px;
        }
        .btn-primary:hover {
            background-color: #ff9eb3;
        }
        .page-link {
        color: #ff758c; /* Warna teks */
        }

        .page-link:hover {
            color: #ff9eb3; /* Warna teks saat hover */
            text-decoration: none; /* Hilangkan underline */
        }

        .page-item.active .page-link {
            background-color: #ff758c; /* Warna latar belakang elemen aktif */
            border-color: #ff758c;    /* Warna border elemen aktif */
            color: #fff;              /* Warna teks elemen aktif */
        }
        .btn-back {
        background-color: #ff9eb3;
        color: white;
        border: none;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        }

        .btn-back:hover {
            background-color: #ff758c;
            color: white;
            text-decoration: none;
        }

        .cooking-step {
        white-space: pre-line; /* Menjaga format baris baru */
        line-height: 1.8; /* Memberikan jarak antar baris */
        margin-bottom: 1rem; /* Menambahkan jarak antar langkah */
        }

        .table-light {
        background-color: #ffe6f0; /* Warna pink soft */
        color: #333; /* Warna teks gelap agar mudah dibaca */
        font-weight: bold;
        }

        .table-light th {
            color: #ff9eb3;
            text-align: center; /* Agar teks di header sejajar di tengah */
            border-bottom: 2px solid #ffccd5; /* Tambahkan garis bawah dengan warna pink yang sedikit lebih gelap */
        }

        
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="navbar-brand-container">
                <img src="/images/logo.png" alt="Logo">
                <a class="navbar-brand" href="#">Sisrek Food App</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Home</a>
                    </li>
          
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recommendations-form-ingredients') }}">Rec by Ingredients</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Sisrek Food App | Made with <span>&hearts;</span> by Your Team</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
