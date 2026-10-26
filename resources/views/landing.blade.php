<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl+R &bull; Empower Your Electronics Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
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
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(circle at 15% 12%, rgba(71, 73, 115, 0.28) 0%, transparent 45%),
                radial-gradient(circle at 85% 18%, rgba(22, 27, 51, 0.65) 0%, transparent 50%),
                radial-gradient(circle at 50% 65%, rgba(71, 73, 115, 0.18) 0%, transparent 50%);
            background-attachment: fixed;
            color: #FFFFFF;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Top Navigation Bar (Frosted Glassmorphism) */
        .ctrl-navbar {
            background: rgba(22, 27, 51, 0.78);
            border-bottom: 1px solid rgba(166, 156, 172, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1050;
            height: 70px;
            display: flex;
            align-items: center;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.35);
            transition: all 0.3s ease;
        }

        .ctrl-navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(241, 218, 196, 0.4) 50%, transparent 100%);
            pointer-events: none;
        }

        .brand-logo {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--accent-cream);
            text-decoration: none;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: transform 0.2s ease;
        }

        .brand-logo:hover {
            color: var(--accent-cream-hover);
            transform: translateY(-1px);
        }

        .nav-link-item {
            color: #FFFFFF;
            font-size: 0.92rem;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .nav-link-item:hover {
            color: var(--accent-cream);
            background-color: rgba(71, 73, 115, 0.2);
        }

        .apple-nav-link {
            color: var(--text-secondary);
            font-size: 0.84rem;
            font-weight: 500;
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 10px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            letter-spacing: -0.1px;
            display: inline-flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .apple-nav-link:hover {
            color: #FFFFFF;
            background-color: rgba(71, 73, 115, 0.28);
            border-color: rgba(166, 156, 172, 0.25);
            transform: translateY(-1px);
        }

        .apple-nav-link.active {
            color: var(--accent-cream);
            font-weight: 700;
            background-color: rgba(241, 218, 196, 0.14);
            border-color: rgba(241, 218, 196, 0.35);
            box-shadow: 0 0 16px rgba(241, 218, 196, 0.12);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .icon-btn-nav {
            color: #FFFFFF;
            background: rgba(71, 73, 115, 0.18);
            border: 1px solid rgba(166, 156, 172, 0.18);
            font-size: 1.1rem;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn-nav:hover {
            color: var(--accent-cream);
            background: rgba(71, 73, 115, 0.35);
            border-color: rgba(241, 218, 196, 0.4);
            transform: translateY(-1px);
        }

        /* Inline Navbar Search Bar */
        .search-nav-form {
            display: inline-flex;
            align-items: center;
            background: rgba(71, 73, 115, 0.22);
            border: 1px solid rgba(166, 156, 172, 0.22);
            border-radius: 12px;
            padding: 0 10px;
            height: 40px;
            gap: 6px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            max-width: 240px;
        }

        .search-nav-form:focus-within {
            background: rgba(22, 27, 51, 0.9);
            border-color: var(--accent-cream);
            box-shadow: 0 0 16px rgba(241, 218, 196, 0.2);
            max-width: 290px;
        }

        .search-nav-btn {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .search-nav-form:focus-within .search-nav-btn,
        .search-nav-btn:hover {
            color: var(--accent-cream);
        }

        .search-nav-input {
            background: transparent !important;
            border: none !important;
            outline: none !important;
            color: #FFFFFF !important;
            font-size: 0.84rem;
            padding: 0;
            width: 100%;
            min-width: 100px;
        }

        .search-nav-input::placeholder {
            color: var(--text-secondary);
            font-size: 0.82rem;
        }

        .search-nav-clear {
            color: var(--text-secondary);
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 2px;
            transition: color 0.15s ease;
        }

        .search-nav-clear:hover {
            color: var(--accent-cream);
        }

        @media (max-width: 575.98px) {
            .search-nav-form {
                max-width: 130px;
                height: 36px;
                padding: 0 8px;
            }
            .search-nav-input {
                font-size: 0.78rem;
                min-width: 55px;
            }
            .search-nav-form:focus-within {
                max-width: 160px;
            }
        }

        /* Cream CTA Buttons */
        .btn-cream {
            background: linear-gradient(135deg, var(--accent-cream) 0%, var(--accent-cream-hover) 100%);
            color: var(--text-dark);
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.92rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 16px rgba(241, 218, 196, 0.25);
        }

        .btn-cream:hover {
            background: linear-gradient(135deg, #FFF0E0 0%, var(--accent-cream) 100%);
            color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(241, 218, 196, 0.4);
        }

        .btn-outline-glass {
            background-color: rgba(71, 73, 115, 0.18);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: #FFFFFF;
            font-weight: 600;
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.92rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-outline-glass:hover {
            background-color: rgba(71, 73, 115, 0.35);
            color: var(--accent-cream);
            border-color: var(--accent-cream);
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero-section {
            padding: 4.5rem 0 3.5rem;
            position: relative;
        }

        .hero-title {
            font-size: clamp(2.4rem, 4.2vw, 3.4rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            color: var(--accent-cream);
            margin-bottom: 1.25rem;
        }

        .hero-desc {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--text-secondary);
            max-width: 480px;
            margin-bottom: 2rem;
        }

        /* Right Hero Showcase Glass Card */
        .hero-glass-card {
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.24) 0%, rgba(22, 27, 51, 0.78) 100%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 22px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 48px -12px rgba(0, 0, 0, 0.6), inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .hero-glass-card:hover {
            border-color: rgba(241, 218, 196, 0.35);
            box-shadow: 0 24px 56px -12px rgba(0, 0, 0, 0.7), 0 0 30px -5px rgba(241, 218, 196, 0.12), inset 0 1px 0 0 rgba(255, 255, 255, 0.15);
        }

        .hero-mockup-wrap {
            position: relative;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .mockup-item {
            border-radius: 14px;
            transition: transform 0.2s ease;
        }

        .mockup-item:hover {
            transform: scale(1.03);
        }

        /* Micro Feature Indicators */
        .feature-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 500;
        }

        .feature-indicator i {
            color: var(--accent-cream);
            font-size: 1.05rem;
        }

        /* Section Headings */
        .section-heading-title {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #FFFFFF;
            margin-bottom: 0.25rem;
        }

        /* Brand Logo Silhouette Watermarks */
        .brand-bg-silhouette-hero {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: clamp(540px, 70vw, 920px);
            height: clamp(540px, 70vw, 920px);
            background-image: url('{{ asset('images/logo.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.045;
            filter: blur(1px) grayscale(100%) brightness(190%);
            pointer-events: none;
            z-index: 0;
            user-select: none;
        }

        .apple-hero-logo-watermark {
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 380px;
            height: 380px;
            background-image: url('{{ asset('images/logo.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.055;
            filter: drop-shadow(0 0 50px rgba(241, 218, 196, 0.45));
            pointer-events: none;
            z-index: 1;
            user-select: none;
        }

        /* Apple-Style Hardware Spotlight Hero */
        .apple-hero-card {
            background: radial-gradient(circle at 50% 20%, rgba(71, 73, 115, 0.45) 0%, rgba(22, 27, 51, 0.95) 75%);
            border: 1px solid rgba(166, 156, 172, 0.28);
            border-radius: 28px;
            padding: 3.5rem 2rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 80px -15px rgba(0, 0, 0, 0.8), inset 0 1px 0 0 rgba(255, 255, 255, 0.15);
            transition: all 0.35s ease;
        }

        .apple-hero-card:hover {
            border-color: rgba(241, 218, 196, 0.4);
            box-shadow: 0 36px 90px -15px rgba(0, 0, 0, 0.85), 0 0 35px rgba(241, 218, 196, 0.12);
        }

        .apple-hero-title {
            font-size: clamp(2.2rem, 4.5vw, 3.8rem);
            font-weight: 800;
            letter-spacing: -1.2px;
            color: #FFFFFF;
            line-height: 1.1;
            margin-bottom: 0.75rem;
        }

        .apple-hero-subtitle {
            font-size: clamp(1.05rem, 2vw, 1.45rem);
            color: var(--accent-cream);
            font-weight: 500;
            max-width: 650px;
            margin: 0 auto 1.5rem;
            line-height: 1.4;
        }

        .btn-apple-pill {
            background: #0071e3;
            color: #FFFFFF !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 9px 24px;
            border-radius: 980px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(0, 113, 227, 0.4);
        }

        .btn-apple-pill:hover {
            background: #0077ed;
            transform: scale(1.04);
            box-shadow: 0 6px 20px rgba(0, 113, 227, 0.55);
        }

        .btn-apple-pill-ghost {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #FFFFFF !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 9px 24px;
            border-radius: 980px;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            backdrop-filter: blur(10px);
            transition: all 0.2s ease;
        }

        .btn-apple-pill-ghost:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #FFFFFF;
            transform: scale(1.04);
        }

        .hero-apple-img-wrap {
            position: relative;
            margin-top: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            max-height: 440px;
        }

        .hero-apple-img {
            max-height: 380px;
            max-width: 90%;
            object-fit: contain;
            filter: drop-shadow(0 25px 45px rgba(0, 0, 0, 0.8));
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .apple-hero-card:hover .hero-apple-img {
            transform: translateY(-8px) scale(1.02);
        }

        /* 2-Column Apple Bento Cards (Image 3 style) */
        .apple-bento-card {
            border-radius: 26px;
            padding: 2.8rem 2.2rem 2.2rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 480px;
            text-align: center;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none !important;
        }

        .apple-bento-card-dark {
            background: radial-gradient(circle at 50% 15%, rgba(71, 73, 115, 0.35) 0%, #161B33 80%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .apple-bento-card-dark:hover {
            border-color: rgba(241, 218, 196, 0.4);
            transform: translateY(-6px);
            box-shadow: 0 30px 65px rgba(0, 0, 0, 0.75), 0 0 30px rgba(241, 218, 196, 0.1);
        }

        .apple-bento-card-contrast {
            background: radial-gradient(circle at 50% 15%, rgba(241, 218, 196, 0.12) 0%, rgba(31, 36, 71, 0.95) 85%);
            border: 1px solid rgba(241, 218, 196, 0.25);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        .apple-bento-card-contrast:hover {
            border-color: var(--accent-cream);
            transform: translateY(-6px);
            box-shadow: 0 30px 65px rgba(0, 0, 0, 0.75), 0 0 30px rgba(241, 218, 196, 0.18);
        }

        .apple-bento-card-split {
            min-height: 400px;
            background: linear-gradient(145deg, rgba(22, 27, 51, 0.9) 0%, rgba(71, 73, 115, 0.25) 100%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
        }

        .apple-bento-card-split:hover {
            border-color: rgba(241, 218, 196, 0.35);
            transform: translateY(-5px);
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.65);
        }

        .apple-bento-img-wrap {
            margin-top: 1.5rem;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .apple-bento-img {
            max-height: 230px;
            max-width: 85%;
            object-fit: contain;
            filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.7));
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .apple-bento-card:hover .apple-bento-img {
            transform: translateY(-6px) scale(1.05);
        }

        .apple-bento-title {
            font-size: clamp(1.4rem, 2.5vw, 1.85rem);
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.5px;
            margin-bottom: 0.45rem;
        }

        .apple-bento-desc {
            font-size: 0.95rem;
            color: var(--text-secondary);
            max-width: 420px;
            margin: 0 auto 1.25rem;
            line-height: 1.45;
        }

        /* Product Catalog Real Image */
        .product-real-img {
            max-width: 90%;
            max-height: 135px;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), filter 0.35s ease;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.65));
            display: block;
            margin: 0 auto;
        }

        .product-glass-card:hover .product-real-img {
            transform: scale(1.08) translateY(-4px);
            filter: drop-shadow(0 16px 28px rgba(0, 0, 0, 0.85));
        }

        /* Product Cards (Glassmorphism & Gradient) */
        .product-glass-card {
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.72) 100%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 18px;
            padding: 1.15rem;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.08);
            position: relative;
        }

        .product-glass-card:hover {
            transform: translateY(-6px);
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.32) 0%, rgba(22, 27, 51, 0.85) 100%);
            border-color: rgba(241, 218, 196, 0.45);
            box-shadow: 0 20px 42px -10px rgba(0, 0, 0, 0.65), 0 0 25px -4px rgba(241, 218, 196, 0.15), inset 0 1px 0 0 rgba(255, 255, 255, 0.16);
        }

        .product-img-box {
            background: radial-gradient(circle at 50% 35%, rgba(71, 73, 115, 0.26) 0%, rgba(13, 12, 29, 0.75) 100%);
            border: 1px solid rgba(166, 156, 172, 0.16);
            border-radius: 16px;
            height: 175px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            color: var(--accent-cream);
            position: relative;
            overflow: hidden;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .product-glass-card:hover .product-img-box {
            border-color: rgba(241, 218, 196, 0.35);
            background: radial-gradient(circle at 50% 35%, rgba(71, 73, 115, 0.4) 0%, rgba(13, 12, 29, 0.88) 100%);
        }

        .product-glass-card:hover .product-img-box i {
            transform: scale(1.08);
            color: #FFFFFF;
        }

        .product-img-box i {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .badge-pill-status {
            background: rgba(71, 73, 115, 0.5);
            color: var(--accent-cream);
            border: 1px solid rgba(241, 218, 196, 0.35);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .product-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 0.35rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
            display: block;
            transition: color 0.15s ease;
        }

        .product-title:hover {
            color: var(--accent-cream);
        }

        .product-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-cream);
            margin-bottom: 0.95rem;
        }

        .btn-buy-card {
            background: linear-gradient(135deg, var(--accent-cream) 0%, var(--accent-cream-hover) 100%);
            color: var(--text-dark);
            font-weight: 700;
            font-size: 0.84rem;
            padding: 7px 14px;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(241, 218, 196, 0.2);
        }

        .btn-buy-card:hover {
            background: linear-gradient(135deg, #FFF0E0 0%, var(--accent-cream) 100%);
            color: var(--text-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(241, 218, 196, 0.35);
        }

        .btn-cart-card {
            background: rgba(71, 73, 115, 0.22);
            border: 1px solid rgba(166, 156, 172, 0.25);
            color: #FFFFFF;
            border-radius: 10px;
            padding: 7px 11px;
            font-size: 0.92rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-cart-card:hover {
            background: rgba(241, 218, 196, 0.15);
            border-color: var(--accent-cream);
            color: var(--accent-cream);
            transform: translateY(-1px);
        }

        /* Filter Chips */
        .filter-chip {
            background-color: var(--bg-solid);
            color: var(--text-secondary);
            border: 1px solid var(--border-glass);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .filter-chip:hover {
            color: #FFFFFF;
            border-color: rgba(241, 218, 196, 0.4);
        }

        .filter-chip.active {
            background-color: var(--accent-cream);
            color: var(--text-dark);
            border-color: var(--accent-cream);
        }

        /* Buyer Slide Pagination (Universal Style) */
        .slide-pagination-wrap {
            margin: 2.5rem 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .slide-nav-btn {
            background: rgba(22, 27, 51, 0.85) !important;
            border: 1px solid rgba(166, 156, 172, 0.25) !important;
            color: #FFFFFF !important;
            font-size: 0.95rem;
            font-weight: 600;
            width: 38px;
            height: 38px;
            padding: 0 !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .slide-nav-btn:hover:not(.disabled) {
            background: rgba(71, 73, 115, 0.5) !important;
            border-color: var(--accent-cream) !important;
            color: var(--accent-cream) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
        }

        .slide-nav-btn.disabled {
            color: rgba(166, 156, 172, 0.35) !important;
            border-color: rgba(166, 156, 172, 0.12) !important;
            background: rgba(22, 27, 51, 0.4) !important;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none !important;
        }

        .slide-nav-page {
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            border-radius: 12px !important;
            background: rgba(22, 27, 51, 0.85) !important;
            border: 1px solid rgba(166, 156, 172, 0.25) !important;
            color: #FFFFFF !important;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .slide-nav-page:hover:not(.active) {
            background: rgba(71, 73, 115, 0.5) !important;
            border-color: var(--accent-cream) !important;
            color: var(--accent-cream) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
        }

        .slide-nav-page.active {
            background: linear-gradient(135deg, var(--accent-cream) 0%, var(--accent-cream-hover) 100%) !important;
            border-color: var(--accent-cream) !important;
            color: var(--text-dark) !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 16px rgba(241, 218, 196, 0.35) !important;
            transform: translateY(-1px);
        }

        .slide-nav-dots {
            color: var(--text-secondary);
            padding: 0 6px;
            font-weight: 700;
            user-select: none;
        }

        /* Footer */
        .ctrl-footer {
            background-color: var(--bg-solid);
            border-top: 1px solid var(--border-glass);
            padding: 2.5rem 0;
            margin-top: 5rem;
        }
    </style>
</head>
<body>

    <!-- 1. Top Navbar (Solid #161B33 with Glass Accents) -->
    <header class="ctrl-navbar">
        <div class="container-fluid px-3 px-xl-5 d-flex align-items-center justify-content-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="brand-logo flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Ctrl+R" style="width: 36px; height: 36px; object-fit: contain; border-radius: 8px;">
                <span>Ctrl+R</span>
            </a>

            <!-- Nav Links (Apple-Style Category Topbar) -->
            <nav class="d-none d-lg-flex align-items-center gap-1 mx-auto">
                <a href="{{ route('landing') }}#katalog-gear" class="apple-nav-link {{ !request('category') ? 'active' : '' }}">
                    Semua
                </a>
                @foreach($categories->take(3) as $cat)
                    <a href="{{ route('landing', ['category' => $cat->id]) }}#katalog-gear" class="apple-nav-link {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
                @if($categories->count() > 3)
                    <div class="dropdown">
                        <button class="apple-nav-link dropdown-toggle border-0 bg-transparent" type="button" data-bs-toggle="dropdown" style="font-size: 0.82rem;">
                            Kategori Lain
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark shadow border small" style="background-color: var(--bg-solid); border-color: var(--border-glass);">
                            @foreach($categories->skip(3) as $cat)
                                <li>
                                    <a class="dropdown-item py-2 {{ request('category') == $cat->id ? 'text-warning fw-bold' : 'text-white' }}" href="{{ route('landing', ['category' => $cat->id]) }}#katalog-gear">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="apple-nav-link text-white fw-semibold">Dashboard</a>
                @endif
            </nav>

            <!-- Search, Cart & Primary Auth / CTA (Pojok Kanan Atas) -->
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <!-- Inline Search Bar beside Icon -->
                <form action="{{ route('landing') }}#katalog-gear" method="GET" class="search-nav-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <button type="submit" class="search-nav-btn" title="Cari Produk">
                        <i class="bi bi-search"></i>
                    </button>
                    <input type="text" 
                           name="search" 
                           class="search-nav-input" 
                           placeholder="Cari hardware..." 
                           value="{{ request('search') }}"
                           aria-label="Cari Produk">
                    @if(request('search'))
                        <a href="{{ route('landing', request()->only('category')) }}#katalog-gear" class="search-nav-clear" title="Reset Pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </form>

                @if(Auth::check() && Auth::user()->isPembeli())
                    <a href="{{ route('customer.orders.index') }}" class="icon-btn-nav" title="Pesanan Saya">
                        <i class="bi bi-cart3"></i>
                    </a>
                @else
                    <a href="#katalog-gear" class="icon-btn-nav" title="Keranjang">
                        <i class="bi bi-cart3"></i>
                    </a>
                @endif

                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="btn-cream ms-1">
                        <i class="bi {{ Auth::user()->isPembeli() ? 'bi-shop' : 'bi-speedometer2' }}"></i>
                        <span>{{ Auth::user()->isPembeli() ? 'Beranda' : 'Dashboard' }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-cream ms-1">
                        <span>Sign In</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- 2. Flagship Hero Showcase (Apple Style) -->
    <section class="hero-section pb-2 position-relative overflow-hidden">
        <!-- Brand Logo Background Silhouette Watermark -->
        <div class="brand-bg-silhouette-hero" aria-hidden="true"></div>

        <div class="container position-relative" style="z-index: 2;">
            @include('components.alert')

            @if($heroProduct)
                @php
                    $heroPrice = $heroProduct->selling_price > 0 ? $heroProduct->selling_price : $heroProduct->price;
                    $heroImg = $heroProduct->image;
                    $pngHero = preg_replace('/\.jpe?g$/i', '.png', $heroImg);
                    if (file_exists(public_path($pngHero))) {
                        $heroImg = $pngHero;
                    }
                @endphp
                <div class="apple-hero-card position-relative overflow-hidden">
                    <!-- Subtle Internal Brand Watermark -->
                    <div class="apple-hero-logo-watermark" aria-hidden="true"></div>

                    <div class="mb-3 d-inline-flex align-items-center gap-2 flex-wrap justify-content-center position-relative" style="z-index: 2;">
                        <span class="badge-pill-status">REDMAGIC ECOSYSTEM</span>
                        <span class="badge-pill-status">FLAGSHIP SHOWCASE</span>
                        <span class="badge-pill-status">{{ $heroProduct->category ? $heroProduct->category->name : 'OFFICIAL GEAR' }}</span>
                        @if($heroProduct->stock > 0)
                            <span class="badge-pill-status" style="background: rgba(46, 213, 115, 0.15); border-color: rgba(46, 213, 115, 0.4); color: #2ed573;">
                                <i class="bi bi-check-circle-fill me-1"></i> Ready Stock ({{ $heroProduct->stock }})
                            </span>
                        @endif
                    </div>

                    <h1 class="apple-hero-title position-relative" style="z-index: 2;">
                        {{ $heroProduct->name }}
                    </h1>
                    <p class="apple-hero-subtitle position-relative" style="z-index: 2;">
                        Performa terdepan dengan teknologi terkini. Desain presisi, efisiensi maksimal, siap mendukung produktivitas dan ekosistem Anda.
                    </p>

                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mb-2 position-relative" style="z-index: 2;">
                        <a href="{{ route('product.preview', $heroProduct->id) }}" class="btn-apple-pill">
                            <span>Beli Sekarang</span>
                            <i class="bi bi-chevron-right fs-6"></i>
                        </a>
                        <a href="#katalog-gear" class="btn-apple-pill-ghost">
                            <span>Jelajahi Produk</span>
                        </a>
                    </div>
                    <div class="small mt-2 position-relative" style="color: var(--accent-cream); font-weight: 600; z-index: 2;">
                        Mulai dari Rp {{ number_format($heroPrice, 0, ',', '.') }}
                    </div>

                    <div class="hero-apple-img-wrap position-relative" style="z-index: 2;">
                        @if($heroImg && file_exists(public_path($heroImg)))
                            <img src="{{ asset($heroImg) }}" class="hero-apple-img" alt="{{ $heroProduct->name }}">
                        @else
                            <i class="bi bi-laptop display-1" style="color: var(--accent-cream); opacity: 0.85;"></i>
                        @endif
                    </div>
                </div>
            @else
                <div class="apple-hero-card position-relative overflow-hidden">
                    <div class="apple-hero-logo-watermark" aria-hidden="true"></div>
                    <div class="mb-3 d-inline-flex align-items-center gap-2 position-relative" style="z-index: 2;">
                        <span class="badge-pill-status">REDMAGIC ECOSYSTEM</span>
                        <span class="badge-pill-status">OFFICIAL SHOWCASE</span>
                    </div>
                    <h1 class="apple-hero-title position-relative" style="z-index: 2;">Empower Your Electronics Inventory.</h1>
                    <p class="apple-hero-subtitle position-relative" style="z-index: 2;">Sistem manajemen inventaris, penjualan, dan pembelian elektronik yang pintar, cepat, dan terintegrasi.</p>
                    <a href="#katalog-gear" class="btn-apple-pill position-relative" style="z-index: 2;"><span>Explore Products</span></a>
                </div>
            @endif
        </div>
    </section>

    <!-- 2.5 Apple-Style Spotlight Bento Grid -->
    @if(isset($spotlightProducts) && $spotlightProducts->count() > 0)
    <section class="py-4">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                <div>
                    <h3 class="section-heading-title">Hardware Spotlight</h3>
                    <p class="small mb-0" style="color: var(--text-secondary);">Perangkat unggulan pilihan untuk melengkapi ekosistem teknologi Anda</p>
                </div>
                <a href="#katalog-gear" class="btn-outline-glass px-3 py-1.5 small" style="width: fit-content;">
                    Lihat Semua ({{ $totalAvailableProducts }}) <i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>

            <!-- Bento Top Row (2 Primary Cards) -->
            <div class="row g-4 mb-4">
                @foreach($spotlightProducts->slice(0, 2) as $index => $spot)
                    @php
                        $spotPrice = $spot->selling_price > 0 ? $spot->selling_price : $spot->price;
                        $cardTheme = $index === 0 ? 'apple-bento-card-dark' : 'apple-bento-card-contrast';
                        $spotImg = $spot->image;
                        $pngSpot = preg_replace('/\.jpe?g$/i', '.png', $spotImg);
                        if (file_exists(public_path($pngSpot))) {
                            $spotImg = $pngSpot;
                        }
                    @endphp
                    <div class="col-lg-6">
                        <div class="apple-bento-card {{ $cardTheme }}">
                            <div>
                                <div class="small fw-bold text-uppercase mb-2" style="color: var(--accent-cream); letter-spacing: 1px;">
                                    {{ $spot->category ? $spot->category->name : 'Featured Hardware' }}
                                </div>
                                <h4 class="apple-bento-title">{{ $spot->name }}</h4>
                                <p class="apple-bento-desc">
                                    {{ $spot->description ? \Illuminate\Support\Str::limit($spot->description, 85) : 'Kualitas terbaik dengan performa tangguh untuk produktivitas Anda.' }}
                                </p>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('product.preview', $spot->id) }}" class="btn-apple-pill" style="padding: 7px 20px; font-size: 0.88rem;">
                                        Selengkapnya
                                    </a>
                                    <a href="{{ route('product.preview', $spot->id) }}" class="btn-apple-pill-ghost" style="padding: 7px 20px; font-size: 0.88rem;">
                                        Rp {{ number_format($spotPrice, 0, ',', '.') }}
                                    </a>
                                </div>
                            </div>
                            <div class="apple-bento-img-wrap">
                                @if($spotImg && file_exists(public_path($spotImg)))
                                    <img src="{{ asset($spotImg) }}" class="apple-bento-img" alt="{{ $spot->name }}" loading="lazy">
                                @else
                                    <i class="bi bi-cpu display-2" style="color: var(--accent-cream);"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bento Bottom Row (2 Split Cards) -->
            @if($spotlightProducts->count() > 2)
            <div class="row g-4">
                @foreach($spotlightProducts->slice(2, 2) as $spot)
                    @php
                        $spotPrice = $spot->selling_price > 0 ? $spot->selling_price : $spot->price;
                        $spotImg = $spot->image;
                        $pngSpot = preg_replace('/\.jpe?g$/i', '.png', $spotImg);
                        if (file_exists(public_path($pngSpot))) {
                            $spotImg = $pngSpot;
                        }
                    @endphp
                    <div class="col-lg-6">
                        <div class="apple-bento-card apple-bento-card-split">
                            <div>
                                <div class="small fw-bold text-uppercase mb-2" style="color: var(--accent-cream); letter-spacing: 1px;">
                                    {{ $spot->category ? $spot->category->name : 'Ecosystem' }}
                                </div>
                                <h4 class="apple-bento-title" style="font-size: 1.45rem;">{{ $spot->name }}</h4>
                                <p class="apple-bento-desc small">
                                    {{ $spot->description ? \Illuminate\Support\Str::limit($spot->description, 75) : 'Stabilitas tinggi dan keandalan penuh untuk kebutuhan kerja harian.' }}
                                </p>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('product.preview', $spot->id) }}" class="btn-apple-pill" style="padding: 6px 18px; font-size: 0.84rem;">
                                        Beli Sekarang
                                    </a>
                                    <span class="small fw-semibold" style="color: var(--accent-cream);">
                                        Rp {{ number_format($spotPrice, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="apple-bento-img-wrap" style="height: 200px;">
                                @if($spotImg && file_exists(public_path($spotImg)))
                                    <img src="{{ asset($spotImg) }}" class="apple-bento-img" style="max-height: 180px;" alt="{{ $spot->name }}" loading="lazy">
                                @else
                                    <i class="bi bi-motherboard display-3" style="color: var(--accent-cream);"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- 3. Featured Products Section (Image 1 Grid) -->
    <section class="py-4" id="katalog-gear">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="section-heading-title mb-0">Featured Products</h3>
                        @if(request('category'))
                            @php
                                $activeCat = $categories->firstWhere('id', request('category'));
                            @endphp
                            @if($activeCat)
                                <span class="badge-pill-status ms-2">{{ $activeCat->name }}</span>
                                <a href="{{ route('landing', request()->only('search')) }}#katalog-gear" class="small text-decoration-none" style="color: var(--accent-cream);" title="Reset Kategori">
                                    <i class="bi bi-x-circle"></i> Reset Kategori
                                </a>
                            @endif
                        @endif
                        @if(request('search'))
                            <span class="badge-pill-status ms-2">Pencarian: "{{ request('search') }}"</span>
                            <a href="{{ route('landing', request()->only('category')) }}#katalog-gear" class="small text-decoration-none" style="color: var(--accent-cream);" title="Reset Pencarian">
                                <i class="bi bi-x-circle"></i> Hapus
                            </a>
                        @endif
                    </div>
                    <p class="small mb-0 mt-1" style="color: var(--text-secondary);">Katalog perangkat elektronik berkualitas dengan ketersediaan stok real-time</p>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row g-3 g-lg-4 mb-4">
                @forelse($products as $product)
                    @php
                        $price = $product->selling_price > 0 ? $product->selling_price : $product->price;
                        $lowerName = strtolower($product->name);
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-glass-card">
                            <div>
                                <!-- Top Meta Row: Badge & Stock -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-pill-status">
                                        {{ str_contains($lowerName, 'redmagic') ? 'REDMAGIC Edition' : ($product->stock < 5 ? 'Stok Tipis' : 'Best Seller') }}
                                    </span>
                                    <span class="small font-monospace text-secondary" style="font-size: 0.68rem;">
                                        Sisa: {{ $product->stock }}
                                    </span>
                                </div>

                                <!-- Visual Box: Clean Framed Container -->
                                <div class="product-img-box">
                                    @php
                                        $catImg = $product->image;
                                        $pngCat = preg_replace('/\.jpe?g$/i', '.png', $catImg);
                                        if (file_exists(public_path($pngCat))) {
                                            $catImg = $pngCat;
                                        }
                                    @endphp

                                    @if($catImg && file_exists(public_path($catImg)))
                                        <img src="{{ asset($catImg) }}" class="product-real-img" alt="{{ $product->name }}" loading="lazy">
                                    @elseif(str_contains($lowerName, 'phone') || str_contains($lowerName, 'smartphone') || str_contains($lowerName, 'redmagic'))
                                        <i class="bi bi-phone" style="font-size: 4rem;"></i>
                                    @elseif(str_contains($lowerName, 'laptop') || str_contains($lowerName, 'macbook'))
                                        <i class="bi bi-laptop" style="font-size: 4rem;"></i>
                                    @elseif(str_contains($lowerName, 'earbuds') || str_contains($lowerName, 'headset') || str_contains($lowerName, 'audio'))
                                        <i class="bi bi-earbuds" style="font-size: 4rem;"></i>
                                    @elseif(str_contains($lowerName, 'keyboard'))
                                        <i class="bi bi-keyboard" style="font-size: 4rem;"></i>
                                    @elseif(str_contains($lowerName, 'mouse'))
                                        <i class="bi bi-mouse" style="font-size: 4rem;"></i>
                                    @elseif(str_contains($lowerName, 'monitor') || str_contains($lowerName, 'display'))
                                        <i class="bi bi-display" style="font-size: 4rem;"></i>
                                    @else
                                        <i class="bi bi-cpu" style="font-size: 4rem;"></i>
                                    @endif
                                </div>

                                <!-- Title -->
                                <a href="{{ route('product.preview', $product->id) }}" class="product-title" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>

                                <!-- Price -->
                                <div class="product-price">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Action Bottom Row: Buy Now + Cart Icon -->
                            <div class="d-flex align-items-center gap-1.5 pt-2 border-top" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                                <a href="{{ route('product.preview', $product->id) }}" class="btn-buy-card">
                                    Buy Now
                                </a>
                                <a href="{{ route('product.preview', $product->id) }}" class="btn-cart-card" title="Tambah ke Keranjang">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center" style="color: var(--text-secondary);">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <h5 class="text-white">Tidak ada produk yang sesuai dengan kriteria.</h5>
                        <p class="small">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
                        <a href="{{ route('landing') }}#katalog-gear" class="btn btn-cream mt-2">Reset Filter</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination (Centered Buyer Slide Style) -->
            @if($products->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links('vendor.pagination.buyer-slide') }}
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Footer -->
    <footer class="ctrl-footer">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="brand-logo" style="font-size: 1.25rem;">
                <img src="{{ asset('images/logo.png') }}" alt="Ctrl+R" style="width: 28px; height: 28px; object-fit: contain; border-radius: 6px;">
                <span>Ctrl+R</span>
            </div>
            <div class="small" style="color: var(--text-secondary);">
                Empower Your Electronics Inventory &bull; Laravel 12 &bull; Midtrans Snap
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
