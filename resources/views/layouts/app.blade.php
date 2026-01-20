<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
        }
        .sidebar a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white sidebar p-3">
        <h4 class="text-center mb-4">ONEN WIFI</h4>

        <ul class="nav flex-column gap-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                    🏠 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pelanggan.index') }}" class="nav-link text-white">
                    👥 Pelanggan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tagihan.index') }}" class="nav-link text-white">
                    💰 Tagihan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.komplain.index') }}" class="nav-link text-white">
                    📨 Komplain
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan.index') }}" class="nav-link text-white">
                    📊 Laporan
                </a>
            </li>
        </ul>

        <hr>

    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>

</div>

</body>
</html>
