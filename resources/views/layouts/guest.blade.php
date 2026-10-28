<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - Ctrl+R</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans & Monda -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #F8F5EE;
            --bg-solid: #FFFFFF;
            --bg-glass: #FFFFFF;
            --border-glass: #E5E0D5;
            --border-input: #D8D2C4;
            --text-secondary: #706E6B;
            --accent-cream: #1A1A1C;
            --accent-cream-hover: #2C2C2E;
            --text-dark: #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: #1A1A1C;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: absolute;
            top: 20%;
            right: 15%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(240, 235, 224, 0.8) 0%, rgba(248, 245, 238, 0) 70%);
            z-index: 0;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: 15%;
            left: 15%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(235, 230, 218, 0.6) 0%, rgba(248, 245, 238, 0) 70%);
            z-index: 0;
            pointer-events: none;
        }

        .guest-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        /* Brand Header */
        .brand-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .brand-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--accent-cream);
            letter-spacing: -0.5px;
            text-decoration: none;
            display: inline-block;
        }

        /* Glassmorphism Card (Warm Paper Frosted Glass) */
        .glass-auth-card {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 16px;
            padding: 2.25rem 2rem;
            box-shadow: 0 16px 36px -10px rgba(28, 26, 24, 0.06), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95);
        }

        .auth-heading {
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1A1A1C;
            text-align: center;
            margin-bottom: 0.35rem;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 1.75rem;
        }

        /* Input Controls */
        .form-control-glass, .form-select-glass {
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-input) !important;
            color: #1A1A1C !important;
            border-radius: 10px !important;
            height: 48px;
            padding: 0 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-glass::placeholder {
            color: #8E8A83 !important;
        }

        .form-control-glass:focus, .form-select-glass:focus {
            background-color: #FFFFFF !important;
            border-color: var(--accent-cream) !important;
            box-shadow: 0 0 0 3px rgba(26, 26, 28, 0.1) !important;
            outline: none;
        }

        .form-select-glass option {
            background-color: var(--bg-solid);
            color: #1A1A1C;
        }

        /* Primary Button (Black Charcoal CTA) */
        .btn-cream-cta {
            background: #1A1A1C !important;
            color: #FFFFFF !important;
            font-weight: 700 !important;
            border: 1px solid #1A1A1C !important;
            border-radius: 10px !important;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(28, 26, 24, 0.18) !important;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-cream-cta:hover {
            background: #2C2C2E !important;
            border-color: #2C2C2E !important;
            color: #FFFFFF !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(28, 26, 24, 0.25) !important;
        }

        .btn-cream-cta:active {
            transform: scale(0.99);
        }

        /* Social Button */
        .btn-social-glass {
            background-color: #F2EFE9 !important;
            border: 1px solid var(--border-input) !important;
            border-radius: 12px !important;
            color: #1A1A1C !important;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-social-glass:hover {
            background-color: #E5E0D5 !important;
            border-color: var(--accent-cream) !important;
            color: #1A1A1C !important;
        }

        /* Divider */
        .auth-divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-glass);
        }

        .auth-divider span {
            position: relative;
            background: #FFFFFF;
            padding: 0 12px;
            font-size: 0.78rem;
            color: var(--text-secondary);
        }

        /* Checkbox & Footer Link */
        .form-check-input {
            background-color: #FFFFFF;
            border-color: var(--border-input);
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--accent-cream);
            border-color: var(--accent-cream);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .auth-footer a {
            color: #1A1A1C;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .auth-footer a:hover {
            color: #706E6B;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="guest-container">
        <!-- Brand Logo -->
        <div class="brand-header">
            <a href="{{ route('landing') }}" class="brand-title d-inline-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" alt="Ctrl+R" style="width: 44px; height: 44px; object-fit: contain; border-radius: 10px; flex-shrink: 0;">
                <span>Ctrl+R</span>
            </a>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, triggerBtn) {
            const input = document.getElementById(inputId);
            const icon = triggerBtn.querySelector('i');
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
