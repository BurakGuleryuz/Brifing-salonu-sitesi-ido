<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Şifre Belirle - Toplantı Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ido-navy: #003B5C;
            --ido-blue: #0072BC;
            --ido-orange: #F7941D;
        }
        body {
            background: linear-gradient(160deg, #002A42 0%, var(--ido-navy) 45%, #0072BC 100%);
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-top: 4px solid var(--ido-orange);
            border-radius: 12px;
        }
        .btn-primary {
            background-color: var(--ido-blue);
            border-color: var(--ido-blue);
        }
        .btn-primary:hover {
            background-color: var(--ido-navy);
            border-color: var(--ido-navy);
        }
        h1.h4 { color: var(--ido-navy); }
    </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container" style="max-width:420px;">
    <div class="card login-card shadow-lg">
        <div class="card-body p-4">
            <h1 class="h4 mb-2 text-center">Yeni Şifre Belirle</h1>
            <p class="text-muted text-center small mb-4">Kimliğiniz doğrulandı. Yeni şifrenizi girin.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.reset') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Yeni Şifre</label>
                    <input type="password" name="password" class="form-control" required minlength="6" maxlength="6">
                    <div class="form-text">Tam olarak 6 karakter olmalıdır.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yeni Şifre (Tekrar)</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6" maxlength="6">
                </div>

                <button type="submit" class="btn btn-primary w-100">Şifreyi Güncelle</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>