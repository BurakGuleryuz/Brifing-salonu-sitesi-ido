<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Toplantı Yönetim Sistemi</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/ido-logo.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ido-navy: #003B5C;
            --ido-blue: #0072BC;
            --ido-orange: #F7941D;
        }
        html, body { height: 100%; margin: 0; }
        body {
            background: linear-gradient(160deg, #002A42 0%, var(--ido-navy) 45%, #0072BC 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .login-card {
            border: none;
            border-top: 4px solid var(--ido-orange);
            border-radius: 12px;
            position: relative;
            z-index: 3;
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

        .ship-decor {
            position: absolute;
            top: 10%;
            right: 8%;
            width: 220px;
            opacity: 0.14;
            z-index: 1;
        }

        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 2;
        }
        .wave-bg svg {
            width: 200%;
            height: 260px;
            display: block;
        }
        .wave-layer-1 { animation: wave-scroll-login 30s linear infinite; }
        .wave-layer-2 { animation: wave-scroll-login 23s linear infinite; }
        .wave-layer-3 { animation: wave-scroll-login 17s linear infinite; }
        .wave-layer-4 { animation: wave-scroll-login 12s linear infinite; }

        @keyframes wave-scroll-login {
            from { transform: translateX(0); }
            to   { transform: translateX(-1200px); }
        }
    </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">

    <svg class="ship-decor" viewBox="0 0 240 140" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 92 C 40 90, 200 90, 230 92 L 214 120 C 160 126, 80 126, 26 120 Z" fill="#ffffff"></path>
        <rect x="55" y="58" width="90" height="34" rx="3" fill="#ffffff"></rect>
        <circle cx="70" cy="75" r="5" fill="#003B5C" opacity="0.5"></circle>
        <circle cx="88" cy="75" r="5" fill="#003B5C" opacity="0.5"></circle>
        <circle cx="106" cy="75" r="5" fill="#003B5C" opacity="0.5"></circle>
        <circle cx="124" cy="75" r="5" fill="#003B5C" opacity="0.5"></circle>
        <rect x="95" y="30" width="20" height="30" rx="2" fill="#ffffff"></rect>
        <path d="M120 30 C 135 26, 145 20, 150 8" stroke="#ffffff" stroke-width="4" fill="none" stroke-linecap="round" opacity="0.7"></path>
        <path d="M128 22 C 140 18, 148 14, 152 4" stroke="#ffffff" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.5"></path>
        <line x1="60" y1="92" x2="60" y2="58" stroke="#ffffff" stroke-width="2"></line>
        <line x1="140" y1="92" x2="140" y2="58" stroke="#ffffff" stroke-width="2"></line>
    </svg>

    <div class="wave-bg">
        <svg viewBox="0 0 2400 260" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <g class="wave-layer-1">
                <path d="M0,60 C120,35 240,85 360,60 C480,35 600,85 720,60 C840,35 960,85 1080,60 C1140,48 1170,58 1200,60 L1200,260 L0,260 Z" fill="#002A42"></path>
                <path d="M0,60 C120,35 240,85 360,60 C480,35 600,85 720,60 C840,35 960,85 1080,60 C1140,48 1170,58 1200,60 L1200,260 L0,260 Z" fill="#002A42" transform="translate(1200,0)"></path>
            </g>
            <g class="wave-layer-2">
                <path d="M0,100 C120,72 240,128 360,100 C480,72 600,128 720,100 C840,72 960,128 1080,100 C1140,86 1170,96 1200,100 L1200,260 L0,260 Z" fill="#0072BC"></path>
                <path d="M0,100 C120,72 240,128 360,100 C480,72 600,128 720,100 C840,72 960,128 1080,100 C1140,86 1170,96 1200,100 L1200,260 L0,260 Z" fill="#0072BC" transform="translate(1200,0)"></path>
            </g>
            <g class="wave-layer-3">
                <path d="M0,150 C120,120 240,180 360,150 C480,120 600,180 720,150 C840,120 960,180 1080,150 C1140,135 1170,146 1200,150 L1200,260 L0,260 Z" fill="#6FB8E6"></path>
                <path d="M0,150 C120,120 240,180 360,150 C480,120 600,180 720,150 C840,120 960,180 1080,150 C1140,135 1170,146 1200,150 L1200,260 L0,260 Z" fill="#6FB8E6" transform="translate(1200,0)"></path>
            </g>
            <g class="wave-layer-4">
                <path d="M0,200 C120,170 240,230 360,200 C480,170 600,230 720,200 C840,170 960,230 1080,200 C1140,186 1170,196 1200,200 L1200,260 L0,260 Z" fill="#C9EAFB"></path>
                <path d="M0,200 C120,170 240,230 360,200 C480,170 600,230 720,200 C840,170 960,230 1080,200 C1140,186 1170,196 1200,200 L1200,260 L0,260 Z" fill="#C9EAFB" transform="translate(1200,0)"></path>
            </g>
        </svg>
    </div>

    <div class="container" style="max-width:420px; position:relative; z-index:3;">
        <div class="card login-card shadow-lg">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <img src="{{ asset('images/ido-logo.jpg') }}" alt="İDO" style="height:60px; width:auto;">
                </div>
                <h1 class="h4 mb-2 text-center">Hesap Oluştur</h1>
                <p class="text-muted text-center small mb-4">Şirket kimlik numaranız kayıt sonrası otomatik atanacaktır.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="password" class="form-control" required minlength="6" maxlength="6" autocomplete="off">
                        <div class="form-text">6 haneli rakam olmalı. Bir rakam en fazla 2 kez tekrar edebilir (örn: 112233 geçerli, 111234 geçersiz).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Şifre (Tekrar)</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="6" maxlength="6" autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>