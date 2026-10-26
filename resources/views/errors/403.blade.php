<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Ctrl+R</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #0D0C1D;
            --bg-solid: #161B33;
            --bg-glass: rgba(71, 73, 115, 0.18);
            --border-glass: rgba(166, 156, 172, 0.22);
            --text-secondary: #A69CAC;
            --accent-cream: #F1DAC4;
            --accent-cream-hover: #ebd0b4;
            --text-dark: #0D0C1D;
        }
        body {
            background-color: var(--bg-main);
            color: #FFFFFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .error-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            max-width: 480px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
        }
        .error-code {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--accent-cream);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">403</div>
        <h4 class="text-white fw-bold mb-2">Akses Ditolak</h4>
        <p class="small mb-4" style="color: var(--text-secondary); font-size: 0.88rem;">
            {{ $exception->getMessage() ?: 'Akun Anda tidak memiliki wewenang untuk membuka halaman ini.' }}
        </p>

        @php
            $user = Auth::user();
            $isBuyer = $user && $user->isPembeli();
            $isStaff = $user && $user->isStaff();
            $isAdmin = $user && $user->isAdmin();

            if ($isBuyer) {
                $homeLabel = 'Kembali ke Beranda';
                $homeIcon = 'bi-house';
                $homeRoute = route('dashboard');
            } elseif ($isStaff) {
                $homeLabel = 'Kembali ke Operasional';
                $homeIcon = 'bi-grid-1x2';
                $homeRoute = route('dashboard');
            } elseif ($isAdmin) {
                $homeLabel = 'Kembali ke Dashboard';
                $homeIcon = 'bi-speedometer2';
                $homeRoute = route('dashboard');
            } else {
                $homeLabel = 'Halaman Utama';
                $homeIcon = 'bi-house';
                $homeRoute = route('landing');
            }
        @endphp

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="{{ $homeRoute }}" class="btn fw-bold py-2 px-3" style="background: var(--accent-cream); color: var(--text-dark); border-radius: 10px;">
                <i class="bi {{ $homeIcon }} me-1"></i> {{ $homeLabel }}
            </a>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn w-100 py-2 px-3 fw-semibold" style="background: rgba(22, 27, 51, 0.8); border: 1px solid var(--border-glass); color: #FFFFFF; border-radius: 10px;">
                        <i class="bi bi-box-arrow-right me-1"></i> Ganti Akun
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn py-2 px-3 fw-semibold" style="background: rgba(22, 27, 51, 0.8); border: 1px solid var(--border-glass); color: #FFFFFF; border-radius: 10px;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk (Login)
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
