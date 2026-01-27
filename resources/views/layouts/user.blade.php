<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Panel - OneN WiFi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">

    <style>
        body {
            background: #f5f6fa;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d6efd, #084298);
            color: #fff;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar .nav-link {
            padding: 10px 14px;
            border-radius: 8px;
            transition: .2s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.15);
        }

        .sidebar .active {
            background: rgba(255,255,255,.25);
            font-weight: 600;
        }

        /* PROFILE CARD */
        .profile-card {
            background: #fff;
            color: #000;
            border-radius: 12px;
            padding: 15px;
            transition: .2s ease-in-out;
            cursor: pointer;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <div class="sidebar p-3 d-flex flex-column">

        <h4 class="fw-bold mb-4">OneN WiFi</h4>

        <!-- CARD PROFIL (KLIK) -->
        <a href="{{ route('user.profile') }}" class="mb-4">
            <div class="profile-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                </div>

                <div class="mt-2 text-primary fw-semibold small">
                    Lihat Profil <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>

        <!-- MENU -->
        <ul class="nav flex-column gap-2">

            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.tagihan.index') }}"
                   class="nav-link {{ request()->routeIs('user.tagihan.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> Tagihan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.komplain.index') }}"
                   class="nav-link {{ request()->routeIs('user.komplain.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots me-2"></i> Komplain
                </a>
            </li>

        </ul>

        <!-- LOGOUT -->
        <div class="mt-auto pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-light w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 content">
        @yield('content')
    </div>

</div>

<!-- SERVICE WORKER -->
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
