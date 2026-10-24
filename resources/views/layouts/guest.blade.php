<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi') - Ctrl+R</title>
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
            --bg-main: #0D0C1D;
            --bg-solid: #161B33;
            --bg-glass: rgba(71, 73, 115, 0.15);
            --border-glass: rgba(166, 156, 172, 0.22);
            --border-input: rgba(166, 156, 172, 0.3);
            --text-secondary: #A69CAC;
            --accent-cream: #F1DAC4;
            --accent-cream-hover: #ebd0b4;
            --text-dark: #0D0C1D;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: #FFFFFF;
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
            background: radial-gradient(circle, rgba(71, 73, 115, 0.35) 0%, rgba(13, 12, 29, 0) 70%);
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
            background: radial-gradient(circle, rgba(22, 27, 51, 0.6) 0%, rgba(13, 12, 29, 0) 70%);
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

        /* Glassmorphism Card */
        .glass-auth-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 2.25rem 2rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .auth-heading {
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #FFFFFF;
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
            background-color: rgba(22, 27, 51, 0.85) !important;
            border: 1px solid var(--border-input) !important;
            color: #FFFFFF !important;
            border-radius: 12px !important;
            height: 48px;
            padding: 0 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-glass::placeholder {
            color: rgba(166, 156, 172, 0.6) !important;
        }

        .form-control-glass:focus, .form-select-glass:focus {
            background-color: rgba(22, 27, 51, 0.95) !important;
            border-color: var(--accent-cream) !important;
            box-shadow: 0 0 0 2px rgba(241, 218, 196, 0.2) !important;
            outline: none;
        }

        .form-select-glass option {
            background-color: var(--bg-solid);
            color: #FFFFFF;
        }

        /* Primary Button (Cream CTA) */
        .btn-cream-cta {
            background-color: var(--accent-cream) !important;
            color: var(--text-dark) !important;
            font-weight: 700 !important;
            border: none !important;
            border-radius: 12px !important;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            font-size: 0.95rem;
            transition: background-color 0.15s ease, transform 0.1s ease;
            text-decoration: none;
        }

        .btn-cream-cta:hover {
            background-color: var(--accent-cream-hover) !important;
            color: var(--text-dark) !important;
        }

        .btn-cream-cta:active {
            transform: scale(0.99);
        }

        /* Social Button */
        .btn-social-glass {
            background-color: rgba(22, 27, 51, 0.6) !important;
            border: 1px solid var(--border-input) !important;
            border-radius: 12px !important;
            color: #FFFFFF !important;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-social-glass:hover {
            background-color: rgba(71, 73, 115, 0.3) !important;
            border-color: var(--accent-cream) !important;
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
            background: #191B2F;
            padding: 0 12px;
            font-size: 0.78rem;
            color: var(--text-secondary);
        }

        /* Checkbox & Footer Link */
        .form-check-input {
            background-color: rgba(22, 27, 51, 0.85);
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
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .auth-footer a:hover {
            color: var(--accent-cream);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="guest-container">
        <!-- Brand Logo -->
        <div class="brand-header">
            <a href="{{ route('landing') }}" class="brand-title">Ctrl+R</a>
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
