<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login OneN WiFi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    </style>
</head>
<body>

<div class="login-card card shadow-lg border-0">
    
    {{-- HEADER --}}
    <div class="login-header">
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
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="admin@gmail.com"
                           required autofocus>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="••••••••"
                           required>
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

</body>
</html>
