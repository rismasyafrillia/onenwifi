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
            overflow-x: hidden;
        }

        /* ======================
           SIDEBAR
        ====================== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d6efd, #084298);
            color: #fff;
            transition: all .3s ease;
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

        /* ======================
           PROFILE CARD
        ====================== */
        .profile-card {
            background: #fff;
            color: #000;
            border-radius: 12px;
            padding: 15px;
            transition: .2s ease-in-out;
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

        /* ======================
           CONTENT
        ====================== */
        .content {
            padding: 30px;
            width: 100%;
        }

        /* ======================
           MOBILE MODE
        ====================== */
        @media (max-width: 768px) {

            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                height: 100vh;
                z-index: 1050;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                padding: 15px !important;
            }

            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 1049;
                display: none;
            }

            .overlay.show {
                display: block;
            }
        }
    </style>
        <!-- PWA SPLASH SCREEN -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="OneN WiFi">

    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <style>
    /* Fallback splash untuk browser */
    #splash-screen {
        position: fixed;
        inset: 0;
        background: linear-gradient(180deg, #0d6efd, #084298);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    #splash-screen img {
        width: 120px;
        animation: zoom .8s ease-in-out;
    }

    @keyframes zoom {
        from {
            transform: scale(.8);
            opacity: .5;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    </style>
</head>
<body>
<div id="splash-screen">
    <img src="/icons/icon-512.png" alt="OneN WiFi">
</div>

<!-- OVERLAY (HP) -->
<div class="overlay" onclick="toggleSidebar()"></div>

<div class="d-flex min-vh-100 position-relative">

    <!-- SIDEBAR -->
    <div class="sidebar p-3 d-flex flex-column">

        <h4 class="fw-bold mb-4">OneN WiFi</h4>

        <!-- PROFILE -->
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

            <li>
                <a href="{{ route('user.riwayat.index') }}">
                    Riwayat Transaksi
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
    <div class="content">

        <!-- BUTTON MENU (HP) -->
        <button class="btn btn-primary d-md-none mb-3"
                onclick="toggleSidebar()">
            <i class="bi bi-list"></i> Menu
        </button>

        @yield('content')
    </div>

</div>

<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
    document.querySelector('.overlay').classList.toggle('show');
}
</script>

<!-- SERVICE WORKER -->
<script>
if ("serviceWorker" in navigator) {
  navigator.serviceWorker.register("/service-worker.js")
    .then(() => console.log("SW Registered"))
    .catch(err => console.log("SW Failed", err));
}
</script>

<script>
window.addEventListener("load", function () {
    setTimeout(() => {
        const splash = document.getElementById("splash-screen");
        if (splash) splash.remove();
    }, 900);
});
</script>

<!-- BUTTON TEST -->
<button onclick="mintaIzin()" class="btn btn-primary">
Aktifkan Notifikasi
</button>

<button onclick="kirimNotif()" class="btn btn-success">
Test Notifikasi
</button>

<script>
async function subscribeUser() {
    const reg = await navigator.serviceWorker.ready;

    const sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: "{{ env('VAPID_PUBLIC_KEY') }}"
    });

    await fetch("/save-subscription", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(sub)
    });

    console.log("Subscription saved");
}

if ("serviceWorker" in navigator && "PushManager" in window) {
    subscribeUser();
}
</script>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

@stack('scripts')

</body>
</html>
