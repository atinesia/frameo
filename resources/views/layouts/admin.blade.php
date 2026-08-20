<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Photo Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @livewireStyles
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background-color: #1e293b;
            min-height: 100vh;
            border-right: 1px solid #334155;
        }

        .nav-link {
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #38bdf8;
            background-color: #334155;
            border-radius: 8px;
        }

        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
                <h4 class="text-white fw-bold mb-4"><i class="bi bi-camera me-2"></i>Studio Admin</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.albums') ? 'active' : '' }}"
                            href="{{ route('admin.albums') }}">
                            <i class="bi bi-journal-album me-2"></i>Album Foto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.photos.upload') ? 'active' : '' }}"
                            href="{{ route('admin.photos.upload') }}">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Foto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.watermark') ? 'active' : '' }}"
                            href="{{ route('admin.watermark') }}">
                            <i class="bi bi-sliders me-2"></i>Watermark Setting
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="#">
                            <i class="bi bi-bag-check me-2"></i>Transaksi / Pesanan
                        </a>
                    </li>
                </ul>
                <div class="mt-auto pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center justify-content-between text-white mb-3">
                        <div class="small fw-semibold">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm fw-semibold">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar / Logout
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
