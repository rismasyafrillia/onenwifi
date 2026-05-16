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
    <meta name="mobile-web-app-capable" content="yes">

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
                <a href="{{ route('admin.pengajuan.index') }}"
                class="nav-link {{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}">
                    <i class="bi bi-x-circle"></i>
                    Pengajuan Berhenti
                </a>
            </li>

            <li>
                <a href="{{ route('admin.pengajuan-paket.index') }}"
                class="nav-link {{ request()->routeIs('admin.pengajuan-paket.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-repeat"></i>
                    Pengajuan Paket
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
        <div class="topbar d-flex justify-content-between align-items-center shadow-sm">
            <h6 class="mb-0 fw-semibold">
                Sistem Informasi Tagihan & Pembayaran WiFi
            </h6>

            <div class="d-flex align-items-center gap-3">

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="fw-semibold">Admin</span>
                </div>

                {{-- NOTIFIKASI --}}
                <div class="dropdown">
                    <button class="btn btn-light position-relative border-0 shadow-sm rounded-circle"
                            type="button"
                            data-bs-toggle="dropdown"
                            style="width:45px;height:45px;">
                        <i class="bi bi-bell fs-5"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow p-0 overflow-hidden"
                        style="width:360px; border-radius:16px;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom bg-light">
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    Notifikasi
                                </h6>
                                <small class="text-muted">
                                    Aktivitas terbaru sistem
                                </small>
                            </div>
                            @if(auth()->user()->unreadNotifications->count())
                                <span class="badge bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </div>
                        <div style="max-height:420px; overflow-y:auto;">
                            @forelse(auth()->user()->notifications->take(10) as $notif)
                                <a href="{{ $notif->data['url'] ?? '#' }}"
                                class="dropdown-item border-bottom py-3 px-3 notif-item
                                {{ is_null($notif->read_at) ? 'bg-light' : '' }}">

                                    <div class="d-flex">
                                        <div class="me-3">
                                            <div class="notif-icon">
                                                <i class="bi bi-bell-fill"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">

                                            <div class="fw-semibold text-dark">
                                                {{ $notif->data['judul'] ?? '-' }}
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ $notif->data['pesan'] ?? '-' }}
                                            </small>
                                            <small class="text-secondary">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                                    <div class="text-muted mt-2">
                                        Belum ada notifikasi
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @if(auth()->user()->notifications->count())
                        <div class="border-top p-2 bg-white">

                            <a href="{{ route('admin.notifikasi.baca') }}"
                            class="btn btn-primary btn-sm w-100 rounded-pill">
                                Tandai Semua Dibaca
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>

            </div>
        </div>

<style>
.notif-item {
    transition: .2s ease;
}

.notif-item:hover {
    background: #f8f9fa;
}

.notif-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(13,110,253,.1);
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

        {{-- PAGE CONTENT --}}
        <main class="p-4">
            @yield('content')
        </main>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- SERVICE WORKER --}}
<script>
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("/service-worker.js")
.then(reg => {
    reg.update();
    console.log("SW updated");
});
            .then(reg => console.log("SW Registered:", reg.scope))
            .catch(err => console.log("SW Failed:", err));
    });
}
</script>

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

</body>
</html>
