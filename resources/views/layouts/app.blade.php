<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | OneN WiFi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d6efd, #084298);
        }

        .sidebar h4 {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.85);
            border-radius: 8px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,.2);
            color: #fff;
        }

        .content-wrapper {
            min-height: 100vh;
        }

        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 24px;
        }

        .card {
            border-radius: 12px;
        }

        .table thead th {
            vertical-align: middle;
        }
    </style>
</head>

<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    <aside class="sidebar text-white p-3">
        <div class="text-center mb-4">
            <h4>ONEN WIFI</h4>
            <small class="opacity-75">Admin Panel</small>
        </div>

        <ul class="nav flex-column gap-2">

            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.pelanggan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Pelanggan
                </a>
            </li>

            <li>
                <a href="{{ route('admin.tagihan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.tagihan.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    Tagihan
                </a>
            </li>

            <li>
                <a href="{{ route('admin.komplain.index') }}"
                   class="nav-link {{ request()->routeIs('admin.komplain.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots"></i>
                    Komplain
                </a>
            </li>

            <li>
                <a href="{{ route('admin.laporan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i>
                    Laporan
                </a>
            </li>

        </ul>

        <hr class="text-white opacity-50">

        <div class="text-center small opacity-75">
            © {{ date('Y') }} OneN WiFi
        </div>
    </aside>

    {{-- CONTENT --}}
    <div class="flex-grow-1 content-wrapper">

        {{-- TOPBAR --}}
        <div class="topbar d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                Sistem Informasi Tagihan & Pembayaran WiFi
            </h6>

            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="fw-semibold">Admin</span>
            </div>
        </div>

        {{-- PAGE CONTENT --}}
        <main class="p-4">
            @yield('content')
        </main>

    </div>
</div>

{{-- SERVICE WORKER --}}
<script>
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("/service-worker.js")
            .then(reg => console.log("SW Registered:", reg.scope))
            .catch(err => console.log("SW Failed:", err));
    });
}
</script>

</body>
</html>
