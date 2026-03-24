<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login OneN WiFi</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- PWA --}}
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="OneN WiFi">
<link rel="apple-touch-icon" href="/icons/icon-192.png">

{{-- Bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

{{-- Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', sans-serif;
    padding: 20px;
}

.login-card {
    width: 100%;
    max-width: 420px;
    border-radius: 14px;
    overflow: hidden;
}

.login-header {
    background: #0d6efd;
    color: #fff;
    padding: 25px;
    text-align: center;
}

.login-header h3 {
    margin: 0;
    font-weight: bold;
}

.login-header small {
    opacity: 0.9;
}

.password-toggle {
    cursor: pointer;
}

/* RESPONSIVE HP */
@media (max-width: 576px) {

    .login-card {
        max-width: 340px;
    }

    .login-header {
        padding: 18px;
    }

    .login-header h3 {
        font-size: 20px;
    }

    .login-header small {
        font-size: 12px;
    }

    .card-body {
        padding: 18px !important;
    }

    .form-label {
        font-size: 14px;
    }

    .form-control {
        font-size: 14px;
        padding: 8px 10px;
    }

    .input-group-text {
        padding: 6px 10px;
    }

    .btn {
        font-size: 14px;
        padding: 8px;
    }

}

</style>
</head>

<body>

<div class="login-card card shadow-lg border-0">

    {{-- HEADER --}}
    <div class="login-header text-center">

        <img src="{{ asset('logo.png') }}" 
            alt="OneN WiFi"
            style="height:60px; margin-bottom:10px;">

        <h3>Login OneN WiFi</h3>
        <small>Sistem Pembayaran & Manajemen WiFi</small>

    </div>

    {{-- BODY --}}
    <div class="card-body p-4">

        {{-- ERROR --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="aris@onenwifi"
                        required
                        autofocus
                    >
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                    >

                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>

                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Login
            </button>

        </form>

    </div>

    {{-- FOOTER --}}
    <div class="text-center py-3 text-muted small">
        © {{ date('Y') }} OneN WiFi
    </div>

</div>

<script>

function togglePassword() {

    const password = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }

}

</script>

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

</body>
</html>