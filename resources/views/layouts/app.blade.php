<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portofolio & Galeri Foto' }}</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    @livewireStyles

    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #334155;
        }

        .footer-custom {
            background-color: #1e293b;
            border-top: 1px solid #334155;
            margin-top: auto;
        }

        /* Anti-select & Anti-drag global untuk elemen gambar */
        img {
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-select: none;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom px-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-camera-fill text-primary fs-4"></i>
                <span>Studio Gallery</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="{{ route('home') }}">Galeri Foto</a>
                    </li>

                    @auth
                        @if (auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm px-3 fw-bold" href="{{ route('admin.albums') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Panel Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-danger border-0">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm px-4 fw-bold rounded-pill" href="{{ route('login') }}">Masuk /
                                Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="footer-custom py-4 text-center text-secondary small">
        <div class="container">
            <p class="mb-1">&copy; {{ date('Y') }} Studio Photography. All rights reserved.</p>
            <p class="mb-0 text-muted" style="font-size: 0.75rem;">Semua foto dilindungi hak cipta. Pengunduhan tanpa
                izin sah dilarang keras.</p>
        </div>
    </footer>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mencegah Klik Kanan pada Seluruh Gambar -->
    <script>
        document.addEventListener('contextmenu', function(e) {
            if (e.target.tagName === 'IMG') {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>
