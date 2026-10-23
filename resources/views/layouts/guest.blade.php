<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - Ctrl + C</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* 60% Latar Belakang */
            --bg-base: #F8FAFC;
            --bg-card: #FFFFFF;
            --border-subtle: #E2E8F0;
            --border-input: #CBD5E1;
            /* 30% Komponen & Teks */
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #64748B;
            --nav-slate: #0F172A;
            /* 10% Tombol Aksen (Electric Blue) */
            --accent-blue: #3B82F6;
            --accent-blue-hover: #2563EB;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            letter-spacing: -0.01em;
        }

        .auth-split-wrapper {
            width: 100%;
            max-width: 1040px;
            margin: auto;
        }

        .auth-split-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            padding: 1.25rem;
        }

        .auth-visual-panel {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.3) 0%, rgba(15, 23, 42, 0.92) 100%),
                        url('https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            background-color: #0F172A;
            border-radius: 18px;
            min-height: 580px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem;
            position: relative;
        }

        .auth-brand-logo {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.15rem;
            color: #FFFFFF;
            text-decoration: none;
            letter-spacing: -0.03em;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .auth-brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--accent-blue);
            display: inline-block;
        }

        .auth-back-pill {
            background: rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
            border-radius: 9999px;
            padding: 0.45rem 1.15rem;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .auth-back-pill:hover {
            background: rgba(255, 255, 255, 0.28);
            color: #FFFFFF;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .auth-form-panel {
            padding: 2rem 2.5rem;
        }

        .auth-centered-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);
            padding: 2.5rem;
            max-width: 480px;
            margin: 0 auto;
            width: 100%;
        }

        .form-control-minimal, .form-select-minimal {
            background-color: #F8FAFC;
            border: 1px solid var(--border-input);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.72rem 1rem;
            font-size: 0.9rem;
            width: 100%;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control-minimal::placeholder {
            color: var(--text-muted);
        }

        .form-control-minimal:focus, .form-select-minimal:focus {
            background-color: #FFFFFF;
            border-color: var(--accent-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            color: var(--text-primary);
        }

        .form-select-minimal option {
            background-color: #FFFFFF;
            color: var(--text-primary);
        }

        /* 10% Accent Electric Blue Button */
        .btn-auth-blue {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            border: 1px solid var(--accent-blue);
            font-weight: 600;
            font-size: 0.92rem;
            border-radius: 10px;
            padding: 0.8rem 1.25rem;
            width: 100%;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .btn-auth-blue:hover {
            background-color: var(--accent-blue-hover);
            border-color: var(--accent-blue-hover);
            color: #FFFFFF;
        }

        .btn-auth-social {
            background: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.72rem 1rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            text-decoration: none;
            width: 100%;
            transition: all 0.15s ease;
        }

        .btn-auth-social:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            color: var(--text-primary);
        }

        .dash-indicators {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .dash-indicator {
            height: 3px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.35);
            width: 18px;
        }

        .dash-indicator.active {
            width: 36px;
            background: #FFFFFF;
        }

        @media (max-width: 991.98px) {
            .auth-split-card {
                padding: 1rem;
            }
            .auth-visual-panel {
                min-height: 220px;
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            .auth-form-panel {
                padding: 1rem 0.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-split-wrapper">
        @yield('content')
    </div>

    <div class="mt-4 text-center text-muted small font-monospace">
        <a href="{{ route('landing') }}" class="text-decoration-none text-secondary me-3">
            &larr; Kembali ke Storefront
        </a>
        <span>&bull;</span>
        <span class="ms-3 text-muted">Ctrl + C &bull; UTS Pemrograman Web 2 &copy; 2026</span>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, triggerBtn) {
            const input = document.getElementById(inputId);
            const icon = triggerBtn.querySelector('i');
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
