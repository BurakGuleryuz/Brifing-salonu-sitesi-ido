<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum - Toplantı Yönetim Sistemi</title>
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
            <h1 class="h4 mb-2 text-center">Şifremi Unuttum</h1>
            <p class="text-muted text-center small mb-4">Kimliğinizi doğrulamak için bilgilerinizi girin.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.forgot.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Şirket Kimlik No</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Devam Et</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small">Girişe Dön</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>