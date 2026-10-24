<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl+R &bull; Empower Your Electronics Inventory</title>
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
            background: radial-gradient(circle at 50% 30%, rgba(71, 73, 115, 0.32) 0%, rgba(13, 12, 29, 0.82) 100%);
            border: 1px solid rgba(166, 156, 172, 0.18);
            border-radius: 14px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.95rem;
            color: var(--accent-cream);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-glass-card:hover .product-img-box {
            border-color: rgba(241, 218, 196, 0.35);
            background: radial-gradient(circle at 50% 30%, rgba(71, 73, 115, 0.45) 0%, rgba(13, 12, 29, 0.9) 100%);
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

        /* Slide Page / Pagination Glassmorphic Container */
        .pagination-glass-wrap {
            background: linear-gradient(145deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.75) 100%);
            border: 1px solid rgba(166, 156, 172, 0.22);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 18px;
            padding: 0.85rem 1.6rem;
            box-shadow: 0 14px 35px -8px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.08);
            width: 100%;
        }

        .pagination-glass-wrap nav {
            margin-bottom: 0 !important;
        }

        .pagination-glass-wrap .small,
        .pagination-glass-wrap .text-muted,
        .pagination-glass-wrap p {
            color: var(--text-secondary) !important;
            font-size: 0.88rem !important;
            margin-bottom: 0;
            font-weight: 500;
        }

        .pagination-glass-wrap .fw-semibold {
            color: var(--accent-cream) !important;
            font-weight: 700 !important;
        }

        .pagination {
            margin-bottom: 0;
            gap: 6px;
            --bs-pagination-bg: transparent;
            --bs-pagination-border-color: transparent;
        }

        .page-item .page-link {
            background: rgba(71, 73, 115, 0.22) !important;
            border: 1px solid rgba(166, 156, 172, 0.22) !important;
            color: #FFFFFF !important;
            border-radius: 10px !important;
            min-width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0 12px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: none !important;
        }

        .page-item .page-link:hover {
            background: rgba(71, 73, 115, 0.45) !important;
            border-color: var(--accent-cream) !important;
            color: var(--accent-cream) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3) !important;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--accent-cream) 0%, var(--accent-cream-hover) 100%) !important;
            border-color: var(--accent-cream) !important;
            color: var(--text-dark) !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 16px rgba(241, 218, 196, 0.35) !important;
            transform: translateY(-1px);
        }

        .page-item.disabled .page-link {
            background: rgba(22, 27, 51, 0.35) !important;
            border-color: rgba(166, 156, 172, 0.12) !important;
            color: rgba(166, 156, 172, 0.35) !important;
            pointer-events: none;
            cursor: not-allowed;
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
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-cream ms-1">
                        <span>Sign In</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- 2. Hero Section (Image 1 Style) -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <!-- Left Hero Text -->
                <div class="col-lg-6">
                    <div class="mb-3 d-inline-flex align-items-center gap-2">
                        <span class="badge-pill-status">REDMAGIC PRO SERIES</span>
                        <span class="badge-pill-status">OFFICIAL ECOSYSTEM</span>
                    </div>
                    <h1 class="hero-title">
                        Empower Your<br>
                        Electronics Inventory.
                    </h1>
                    <p class="hero-desc">
                        Sistem manajemen inventaris, penjualan, dan pembelian elektronik yang pintar, cepat, dan terintegrasi.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="#katalog-gear" class="btn-cream px-4 py-2.5">
                            <span>Explore Products</span>
                        </a>
                        @if(!Auth::check())
                            <a href="{{ route('register') }}" class="btn-outline-glass px-4 py-2.5">
                                <span>Join Now</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-outline-glass px-4 py-2.5">
                                <span>Buka Dashboard</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right Hero Showcase Card (Glassmorphism & Mockups) -->
                <div class="col-lg-6">
                    <div class="hero-glass-card">
                        <div class="row align-items-center g-3 mb-3">
                            <div class="col-sm-7">
                                <h4 class="fw-bold mb-2" style="color: var(--accent-cream); font-size: 1.25rem;">
                                    Empower Your<br>Electronics Inventory.
                                </h4>
                                <p class="small mb-3" style="color: var(--text-secondary); line-height: 1.5; font-size: 0.8rem;">
                                    Sistem manajemen inventaris, penjualan, dan pembelian elektronik yang pintar,
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="#katalog-gear" class="btn-cream text-nowrap" style="font-size: 0.78rem; padding: 6px 12px;">
                                        Explore Products
                                    </a>
                                    @if(!Auth::check())
                                        <a href="{{ route('register') }}" class="btn-outline-glass text-nowrap" style="font-size: 0.78rem; padding: 6px 12px;">
                                            Join Now
                                        </a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="btn-outline-glass text-nowrap" style="font-size: 0.78rem; padding: 6px 12px;">
                                            Dashboard
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-5 text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 p-2">
                                    <div class="p-2 rounded-3 text-center" style="background: rgba(22, 27, 51, 0.85); border: 1px solid var(--border-glass);">
                                        <i class="bi bi-phone fs-1" style="color: var(--accent-cream);"></i>
                                        <div class="small fw-semibold mt-1" style="font-size: 0.68rem; color: #fff;">Mobile Pro</div>
                                    </div>
                                    <div class="p-2 rounded-3 text-center" style="background: rgba(22, 27, 51, 0.85); border: 1px solid var(--border-glass);">
                                        <i class="bi bi-laptop fs-1" style="color: var(--accent-cream);"></i>
                                        <div class="small fw-semibold mt-1" style="font-size: 0.68rem; color: #fff;">Laptop Ultra</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3 Micro Feature Points -->
                        <div class="pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                            <div class="feature-indicator">
                                <i class="bi bi-clock-history"></i>
                                <span>Real-time Tracking</span>
                            </div>
                            <div class="feature-indicator">
                                <i class="bi bi-people"></i>
                                <span>Multi-role Access</span>
                            </div>
                            <div class="feature-indicator">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Automated Reports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                <!-- Visual Box -->
                                <div class="product-img-box">
                                    <span class="badge-pill-status position-absolute top-0 start-0 m-2">
                                        {{ str_contains($lowerName, 'redmagic') ? 'REDMAGIC Edition' : ($product->stock < 5 ? 'Limited' : 'Best Seller') }}
                                    </span>

                                    @if(str_contains($lowerName, 'phone') || str_contains($lowerName, 'smartphone') || str_contains($lowerName, 'redmagic'))
                                        <i class="bi bi-phone" style="font-size: 4.5rem;"></i>
                                    @elseif(str_contains($lowerName, 'laptop') || str_contains($lowerName, 'macbook'))
                                        <i class="bi bi-laptop" style="font-size: 4.5rem;"></i>
                                    @elseif(str_contains($lowerName, 'earbuds') || str_contains($lowerName, 'headset') || str_contains($lowerName, 'audio'))
                                        <i class="bi bi-earbuds" style="font-size: 4.5rem;"></i>
                                    @elseif(str_contains($lowerName, 'keyboard'))
                                        <i class="bi bi-keyboard" style="font-size: 4.5rem;"></i>
                                    @elseif(str_contains($lowerName, 'mouse'))
                                        <i class="bi bi-mouse" style="font-size: 4.5rem;"></i>
                                    @elseif(str_contains($lowerName, 'monitor') || str_contains($lowerName, 'display'))
                                        <i class="bi bi-display" style="font-size: 4.5rem;"></i>
                                    @else
                                        <i class="bi bi-cpu" style="font-size: 4.5rem;"></i>
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

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <div class="pagination-glass-wrap">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Footer -->
    <footer class="ctrl-footer">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="brand-logo" style="font-size: 1.25rem;">
                Ctrl+R
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
