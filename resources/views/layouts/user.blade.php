<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Panel - OneN WiFi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex" style="min-height:100vh">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white p-3" style="width:240px">
        <h5 class="mb-4">📡 OneN WiFi</h5>

        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link text-white {{ request()->routeIs('user.dashboard') ? 'fw-bold bg-secondary rounded' : '' }}">
                    🏠 Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.profile') }}"
                class="nav-link text-white {{ request()->routeIs('user.profile') ? 'fw-bold bg-secondary rounded' : '' }}">
                    👤 Profil
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.tagihan.index') }}"
                   class="nav-link text-white {{ request()->routeIs('user.tagihan.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                    💰 Tagihan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.komplain.index') }}"
                class="nav-link text-white {{ request()->routeIs('user.komplain.*') ? 'fw-bold bg-secondary rounded' : '' }}">
                    📨 Komplain
                </a>
            </li>

        </ul>
    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4 bg-light">
        @yield('content')
    </div>

</div>

</body>
</html>
