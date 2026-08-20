@php
    $authUser = \App\Models\User::find(session('user_id'));
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toplantı Yönetim Sistemi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ido-navy: #003B5C;
            --ido-blue: #0072BC;
            --ido-orange: #F7941D;
            --ido-light: #F4F7FA;
            --ido-text: #1A2B3C;
        }

        body {
            background-color: var(--ido-light);
            color: var(--ido-text);
        }

        .navbar.bg-dark {
            background-color: var(--ido-navy) !important;
            border-bottom: 3px solid var(--ido-orange);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background-color: var(--ido-blue);
            border-color: var(--ido-blue);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--ido-navy);
            border-color: var(--ido-navy);
        }

        .btn-outline-info {
            color: var(--ido-orange);
            border-color: var(--ido-orange);
        }
        .btn-outline-info:hover {
            background-color: var(--ido-orange);
            border-color: var(--ido-orange);
            color: #fff;
        }

        .btn-outline-secondary {
            color: var(--ido-navy);
            border-color: var(--ido-navy);
        }
        .btn-outline-secondary:hover {
            background-color: var(--ido-navy);
            border-color: var(--ido-navy);
        }

        a {
            color: var(--ido-blue);
        }
        a:hover {
            color: var(--ido-navy);
        }

        h1, h2, h3 {
            color: var(--ido-navy);
        }

        .card, .border {
            border-color: #dde5ec !important;
        }

        table thead {
            background-color: var(--ido-navy);
        }
        table thead th {
            color: #fff !important;
            border-color: var(--ido-navy) !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('rooms.index') }}">Toplantı Yönetim Sistemi</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="{{ route('rooms.index') }}">Odalar</a>
                <a class="nav-link" href="{{ route('meetings.index') }}">Toplantılar</a>
            </div>
            @if ($authUser)
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        {{ $authUser->name }}
                        @if ($authUser->role === 'ozel_kalem_muduru')
                            <span class="badge bg-warning text-dark ms-1">Özel Kalem Müdürü</span>
                        @elseif ($authUser->role === 'bakim_sorumlusu')
                            <span class="badge ms-1" style="background-color:#F7941D;">Bakım Sorumlusu</span>
                        @endif
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Çıkış</button>
                    </form>
                </div>
            @endif
        </div>
    </nav>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>