<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl + C &bull; Electronics & Inventory Management</title>
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
            --bg-white: #FFFFFF;
            --border-subtle: #E2E8F0;
            --border-hover: #CBD5E1;
            /* 30% Komponen & Teks */
            --nav-slate: #0F172A;
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #64748B;
            /* 10% Tombol Aksen (Electric Blue) */
            --accent-blue: #3B82F6;
            --accent-blue-hover: #2563EB;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            overflow-x: hidden;
            letter-spacing: -0.01em;
        }

        .top-hairline {
            height: 2px;
            background: var(--accent-blue);
            position: sticky;
            top: 0;
            z-index: 1060;
        }

        /* 30% Deep Slate Navbar */
        .glass-navbar {
            background: var(--nav-slate);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            top: 2px;
            z-index: 1050;
            padding: 0.85rem 0;
        }

        .brand-logo {
            font-weight: 700;
            font-size: 1.15rem;
            color: #FFFFFF;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.02em;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--accent-blue);
            display: inline-block;
        }

        .nav-link-custom {
            color: #94A3B8;
            font-size: 0.88rem;
            font-weight: 500;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .nav-link-custom:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.06);
        }

        /* 10% Accent Electric Blue Buttons */
        .btn-minimal-blue, .btn-blue-primary {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            border: 1px solid var(--accent-blue);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .btn-minimal-blue:hover, .btn-blue-primary:hover {
            background-color: var(--accent-blue-hover);
            border-color: var(--accent-blue-hover);
            color: #FFFFFF;
        }

        .btn-minimal-outline {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.15s ease;
        }

        .btn-minimal-outline:hover {
            background-color: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.35);
            color: #FFFFFF;
        }

        /* Clean White Cards */
        .minimal-card {
            background: #FFFFFF;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 1.5rem;
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04);
        }

        .minimal-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            color: inherit;
        }

        /* Hero Carousel */
        .hero-slide {
            min-height: 70vh;
            display: flex;
            align-items: center;
            padding: 4rem 0;
        }

        .hero-headline {
            font-size: clamp(2.2rem, 4.5vw, 3.6rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--text-primary);
        }

        .hero-tag {
            color: var(--accent-blue);
        }

        /* Badges */
        .badge-zinc {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 500;
            background: #F1F5F9;
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-blue-soft {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 600;
            background: rgba(59, 130, 246, 0.1);
            color: #1D4ED8;
            border: 1px solid rgba(59, 130, 246, 0.25);
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Product Visual */
        .product-visual-minimal {
            background: #F8FAFC;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--accent-blue);
            transition: all 0.2s ease;
        }

        .minimal-card:hover .product-visual-minimal {
            background: #EFF6FF;
            transform: scale(1.02);
        }

        .price-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        /* Category Filter Chips */
        .pill-filter {
            background: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .pill-filter:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        .pill-filter.active {
            background: var(--nav-slate);
            border-color: var(--nav-slate);
            color: #FFFFFF;
            font-weight: 600;
        }

        /* Minimal Input */
        .minimal-input {
            background-color: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.88rem;
        }

        .minimal-input:focus {
            background-color: #FFFFFF;
            border-color: var(--accent-blue);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Carousel Indicators */
        .carousel-indicators [data-bs-target] {
            width: 24px;
            height: 3px;
            background-color: #CBD5E1;
            border: none;
            margin: 0 4px;
            border-radius: 2px;
        }

        .carousel-indicators .active {
            background-color: var(--accent-blue);
            width: 40px;
        }
    </style>
</head>
<body>
    <div class="top-hairline"></div>

    <!-- 30% Deep Slate Sticky Navbar -->
    <header class="glass-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Brand -->
            <a href="{{ route('landing') }}" class="brand-logo">
                <span class="brand-dot"></span>
                <span class="font-monospace">Ctrl + C</span>
            </a>

            <!-- Nav Links -->
            <nav class="d-none d-lg-flex align-items-center gap-1">
                <a href="#katalog-gear" class="nav-link-custom">Katalog Produk</a>
                <a href="#arsitektur" class="nav-link-custom">Sistem Stok</a>
                <a href="#ulasan" class="nav-link-custom">Ulasan</a>
            </nav>

            <!-- Actions -->
            <div class="d-flex align-items-center gap-2">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="btn-minimal-blue py-1.5 px-3">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <button type="button" class="btn-minimal-outline py-1.5 px-3 d-none d-sm-inline-flex" data-bs-toggle="modal" data-bs-target="#authModal">
                        <span>Masuk</span>
                    </button>
                    <button type="button" class="btn-minimal-blue py-1.5 px-3" data-bs-toggle="modal" data-bs-target="#authModal" onclick="setTimeout(() => { const t = document.getElementById('modal-register-tab'); if(t) bootstrap.Tab.getOrCreateInstance(t).show(); }, 150);">
                        <span>Daftar</span>
                    </button>
                @endif
            </div>
        </div>
    </header>

    <!-- 1. HERO SLIDE BANNER (Bright & Clean) -->
    <section class="position-relative overflow-hidden bg-white border-bottom" id="hero-slider" style="border-color: var(--border-subtle) !important;">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500">
            <div class="carousel-indicators mb-4">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1: Laptop Flagship -->
                <div class="carousel-item active">
                    <div class="container hero-slide">
                        <div class="row align-items-center g-5 w-100">
                            <div class="col-lg-7">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <span class="badge-blue-soft">FLAGSHIP 2026</span>
                                    <span class="badge-zinc">REDMAGIC EDITION</span>
                                </div>
                                <h1 class="hero-headline mb-3">
                                    Hardware Esensial.<br>
                                    <span class="hero-tag">Performa Maksimal.</span>
                                </h1>
                                <p class="text-secondary fs-6 mb-4 lh-base" style="max-width: 520px;">
                                    Lini laptop dan peripheral performa tinggi. Dilindungi sistem transaksi atomik, zero overselling, serta pembayaran aman Midtrans Snap.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-2.5">
                                    <a href="#katalog-gear" class="btn-minimal-blue py-2.5 px-4">
                                        <span>Jelajahi Produk</span>
                                        <i class="bi bi-arrow-down"></i>
                                    </a>
                                    @if(!Auth::check())
                                        <button type="button" class="btn btn-outline-secondary py-2.5 px-4" data-bs-toggle="modal" data-bs-target="#authModal" style="border-radius: 8px;">
                                            <span>Mulai Bergabung</span>
                                        </button>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary py-2.5 px-4" style="border-radius: 8px;">
                                            <span>Buka Dashboard</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-5 text-center">
                                <div class="product-visual-minimal p-4" style="height: 280px; background: #EFF6FF; border-radius: 16px;">
                                    <i class="bi bi-laptop" style="font-size: 7rem; color: var(--accent-blue);"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Mechanical Keyboard -->
                <div class="carousel-item">
                    <div class="container hero-slide">
                        <div class="row align-items-center g-5 w-100">
                            <div class="col-lg-7">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <span class="badge-blue-soft">PRO INPUT</span>
                                    <span class="badge-zinc">LOW LATENCY</span>
                                </div>
                                <h1 class="hero-headline mb-3">
                                    Presisi Mekanikal.<br>
                                    <span class="hero-tag">Respon Instan.</span>
                                </h1>
                                <p class="text-secondary fs-6 mb-4 lh-base" style="max-width: 520px;">
                                    Keyboard switch magnetik dengan aktuasi linear seimbang. Desain minimalis tanpa ornamen berlebih untuk produktivitas kerja dan gaming profesional.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-2.5">
                                    <a href="#katalog-gear" class="btn-minimal-blue py-2.5 px-4">
                                        <span>Lihat Koleksi Keyboard</span>
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary py-2.5 px-4" data-bs-toggle="modal" data-bs-target="#authModal" style="border-radius: 8px;">
                                        <span>Login Akun</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-5 text-center">
                                <div class="product-visual-minimal p-4" style="height: 280px; background: #EFF6FF; border-radius: 16px;">
                                    <i class="bi bi-keyboard" style="font-size: 7rem; color: var(--accent-blue);"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: OLED Display -->
                <div class="carousel-item">
                    <div class="container hero-slide">
                        <div class="row align-items-center g-5 w-100">
                            <div class="col-lg-7">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <span class="badge-blue-soft">HIGH REFRESH</span>
                                    <span class="badge-zinc">COLOR ACCURATE</span>
                                </div>
                                <h1 class="hero-headline mb-3">
                                    Visual Imersif.<br>
                                    <span class="hero-tag">Kontras Mendalam.</span>
                                </h1>
                                <p class="text-secondary fs-6 mb-4 lh-base" style="max-width: 520px;">
                                    Monitor display warna akurat 240Hz dengan panel bebas pantulan. Manajemen stok terintegrasi otomatis dengan sistem inventaris pusat.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-2.5">
                                    <a href="#katalog-gear" class="btn-minimal-blue py-2.5 px-4">
                                        <span>Eksplor Monitor</span>
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary py-2.5 px-4" data-bs-toggle="modal" data-bs-target="#authModal" style="border-radius: 8px;">
                                        <span>Daftar Baru</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-5 text-center">
                                <div class="product-visual-minimal p-4" style="height: 280px; background: #EFF6FF; border-radius: 16px;">
                                    <i class="bi bi-display" style="font-size: 7rem; color: var(--accent-blue);"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. PRODUCT SHOWCASE SECTION (60% Light Background) -->
    <section class="py-5" id="katalog-gear">
        <div class="container py-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                <div>
                    <span class="badge-zinc mb-2">PRODUK TERSEDIA</span>
                    <h2 class="fw-bold mb-1 text-dark">Katalog Hardware Elektronik</h2>
                    <p class="text-secondary small mb-0">Klik pada produk untuk melanjutkan alur checkout dan transaksi aman</p>
                </div>
                <div class="font-monospace text-secondary small">
                    Menampilkan <span class="text-dark fw-bold">{{ $products->total() }}</span> item aktif
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="p-3 mb-4 rounded-3 bg-white" style="border: 1px solid var(--border-subtle);">
                <form action="{{ route('landing') }}#katalog-gear" method="GET" class="row g-2 align-items-center">
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-color: var(--border-subtle);"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control minimal-input border-start-0" placeholder="Cari nama produk atau SKU..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('landing') }}#katalog-gear" class="btn btn-outline-secondary d-flex align-items-center"><i class="bi bi-x-lg"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-1">
                            <a href="{{ route('landing') }}#katalog-gear" class="pill-filter {{ !request('category') ? 'active' : '' }}">
                                Semua
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('landing', array_merge(request()->query(), ['category' => $category->id])) }}#katalog-gear" class="pill-filter {{ request('category') == $category->id ? 'active' : '' }}">
                                    {{ $category->name }} ({{ $category->products_count }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <!-- Grid Cards -->
            @if($products->isEmpty())
                <div class="p-5 text-center rounded-3 my-4 bg-white" style="border: 1px solid var(--border-subtle);">
                    <i class="bi bi-inbox text-secondary fs-1 d-block mb-2"></i>
                    <h6 class="text-dark">Tidak ada produk yang sesuai dengan pencarian.</h6>
                    <a href="{{ route('landing') }}#katalog-gear" class="btn btn-sm btn-minimal-blue mt-2">Reset Pencarian</a>
                </div>
            @else
                <div class="row g-3 g-md-4">
                    @foreach($products as $product)
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <a href="{{ route('product.preview', $product->id) }}" class="minimal-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-blue-soft" style="font-size: 0.65rem;">
                                        {{ $product->category->name ?? 'HARDWARE' }}
                                    </span>
                                    <span class="font-monospace text-secondary" style="font-size: 0.7rem;">
                                        {{ $product->sku }}
                                    </span>
                                </div>

                                <div class="product-visual-minimal">
                                    @if(str_contains(strtolower($product->name), 'laptop'))
                                        <i class="bi bi-laptop fs-1"></i>
                                    @elseif(str_contains(strtolower($product->name), 'keyboard'))
                                        <i class="bi bi-keyboard fs-1"></i>
                                    @elseif(str_contains(strtolower($product->name), 'mouse'))
                                        <i class="bi bi-mouse fs-1"></i>
                                    @elseif(str_contains(strtolower($product->name), 'monitor'))
                                        <i class="bi bi-display fs-1"></i>
                                    @elseif(str_contains(strtolower($product->name), 'ssd') || str_contains(strtolower($product->name), 'storage'))
                                        <i class="bi bi-device-hdd fs-1"></i>
                                    @else
                                        <i class="bi bi-cpu fs-1"></i>
                                    @endif
                                </div>

                                <h6 class="fw-semibold text-dark mb-2 text-truncate" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h6>

                                <div class="mb-3 font-monospace small">
                                    @if($product->stock <= $product->minimum_stock)
                                        <span class="text-warning fw-medium" style="font-size: 0.72rem;">
                                            <i class="bi bi-exclamation-circle me-1"></i>Stok Kritis: {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="text-secondary" style="font-size: 0.72rem;">
                                            Tersedia: {{ $product->stock }} unit
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-auto pt-3 border-top" style="border-color: var(--border-subtle) !important;">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                        <span class="text-secondary small font-monospace" style="font-size: 0.7rem;">HARGA</span>
                                        <span class="price-text">
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- 10% Accent CTA Button -->
                                    <div class="btn-minimal-blue w-100 py-1.5 font-monospace" style="font-size: 0.78rem;">
                                        <span>Beli Hardware</span>
                                    </div>
                                    <div class="text-center text-muted font-monospace mt-1" style="font-size: 0.65rem;">
                                        *Login untuk verifikasi transaksi
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <!-- 3. QUICK INFO & ARSITEKTUR SISTEM (White Clean) -->
    <section class="py-5 bg-white border-top border-bottom" id="arsitektur" style="border-color: var(--border-subtle) !important;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge-blue-soft mb-2">RELIABILITAS SISTEM</span>
                <h3 class="fw-bold text-dark">Prinsip Manajemen Inventaris</h3>
                <p class="text-secondary small font-monospace">Integritas data transaksi tingkat produksi dengan konsistensi stok</p>
            </div>

            <div class="row g-3 g-md-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="minimal-card">
                        <div class="text-primary mb-3 fs-4" style="color: var(--accent-blue) !important;"><i class="bi bi-shield-lock"></i></div>
                        <h6 class="fw-bold text-dark mb-2">Pessimistic Locking</h6>
                        <p class="text-secondary small mb-0 lh-base">
                            Menggunakan <code>lockForUpdate()</code> di dalam transaksi database untuk mencegah overselling multi-pembeli.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="minimal-card">
                        <div class="text-primary mb-3 fs-4" style="color: var(--accent-blue) !important;"><i class="bi bi-credit-card"></i></div>
                        <h6 class="fw-bold text-dark mb-2">Midtrans Snap</h6>
                        <p class="text-secondary small mb-0 lh-base">
                            Integrasi payment gateway dengan verifikasi token otomatis, mode sandbox, dan webhook teruji.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="minimal-card">
                        <div class="text-primary mb-3 fs-4" style="color: var(--accent-blue) !important;"><i class="bi bi-person-badge"></i></div>
                        <h6 class="fw-bold text-dark mb-2">Role Based Access</h6>
                        <p class="text-secondary small mb-0 lh-base">
                            Otorisasi server-side ketat memisahkan hak Admin (Owner), Staff Gudang, dan Pembeli.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="minimal-card">
                        <div class="text-primary mb-3 fs-4" style="color: var(--accent-blue) !important;"><i class="bi bi-arrow-repeat"></i></div>
                        <h6 class="fw-bold text-dark mb-2">Audit Pembatalan</h6>
                        <p class="text-secondary small mb-0 lh-base">
                            Setiap pembatalan pesanan otomatis memulihkan unit produk ke gudang tanpa selisih stok.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Proof -->
            <div class="p-4 rounded-3" style="background: var(--bg-base); border: 1px solid var(--border-subtle);" id="ulasan">
                <div class="row align-items-center g-4">
                    <div class="col-lg-4 text-center text-lg-start">
                        <h5 class="fw-bold text-dark mb-1">Kepuasan Pengguna</h5>
                        <p class="text-secondary small font-monospace mb-0">Ulasan terverifikasi dari staf operasional dan pembeli</p>
                    </div>
                    <div class="col-lg-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-2 bg-white" style="border: 1px solid var(--border-subtle);">
                                    <p class="text-secondary small mb-2 fst-italic">"Pencatatan stok instan dan alur checkout sangat responsif."</p>
                                    <span class="text-dark small font-monospace fw-semibold">Reza A. &bull; Pembeli Terverifikasi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-2 bg-white" style="border: 1px solid var(--border-subtle);">
                                    <p class="text-secondary small mb-2 fst-italic">"Mutasi barang masuk dan keluar transparan untuk staf gudang."</p>
                                    <span class="text-dark small font-monospace fw-semibold">Dimas P. &bull; Staff Operasional</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 30% Slate Footer -->
    <footer class="py-4" style="background: var(--nav-slate); border-top: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="brand-logo" style="font-size: 1rem;">
                <span class="brand-dot"></span>
                <span class="font-monospace">Ctrl + C</span>
            </div>
            <div class="text-secondary small font-monospace" style="color: #94A3B8 !important;">
                UTS Pemrograman Web 2 &bull; Laravel 12 &bull; MySQL &bull; Midtrans Snap
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    @include('components.auth-modal')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
