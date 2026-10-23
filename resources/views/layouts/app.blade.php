<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Ctrl + C</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js 4.4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            /* 60% Latar Belakang */
            --bg-base: #F8FAFC;
            --bg-surface: #FFFFFF;
            --bg-card: #FFFFFF;
            --bg-card-hover: #F1F5F9;
            --border-subtle: #E2E8F0;
            --border-subtle-hover: #CBD5E1;
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
            min-height: 100vh;
            letter-spacing: -0.01em;
        }

        .top-hairline {
            height: 2px;
            background: var(--accent-blue);
            position: sticky;
            top: 0;
            z-index: 1060;
        }

        /* Slate Sidebar for Admin & Staff (30% Component) */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--nav-slate);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            color: #94A3B8;
            position: fixed;
            top: 2px;
            left: 0;
            bottom: 0;
            z-index: 1040;
            overflow-y: auto;
            transition: all 0.25s ease;
        }

        .sidebar .brand {
            padding: 1.25rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #FFFFFF;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--accent-blue);
            display: inline-block;
        }

        .brand-title {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #FFFFFF;
        }

        .sidebar .nav-link {
            color: #94A3B8;
            padding: 0.55rem 0.9rem;
            margin: 0.15rem 0.75rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 0.86rem;
            transition: background-color 0.15s ease, color 0.15s ease;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.06);
        }

        .sidebar .nav-link.active {
            color: #FFFFFF;
            background: rgba(59, 130, 246, 0.18);
            border: 1px solid rgba(59, 130, 246, 0.35);
            font-weight: 600;
        }

        .sidebar .section-heading {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748B;
            padding: 1.1rem 1.4rem 0.35rem;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
        }

        /* Customer Layout (No Sidebar) */
        body.customer-layout .main-wrapper {
            margin-left: 0 !important;
        }

        /* 30% Slate Navbar for Customer */
        .customer-top-navbar {
            background: var(--nav-slate);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            top: 2px;
            z-index: 1050;
        }

        /* Clean Top Navbar for Admin & Staff */
        .top-navbar {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 2px;
            z-index: 1020;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        /* Clean White Cards */
        .glass-card, .card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            color: var(--text-primary);
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04);
        }

        .card-header {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            padding: 1rem 1.25rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .card-footer {
            background: var(--bg-surface);
            border-top: 1px solid var(--border-subtle);
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: #FFFFFF;
            border: 1px solid #CBD5E1;
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            font-size: 0.88rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: #FFFFFF;
            border-color: var(--accent-blue);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-select option {
            background-color: #FFFFFF;
            color: var(--text-primary);
        }

        .input-group-text {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: var(--text-secondary);
            border-radius: 8px;
        }

        /* 10% Accent Electric Blue Buttons */
        .btn-rm-primary, .btn-primary, .btn-blue-primary {
            background-color: var(--accent-blue);
            border: 1px solid var(--accent-blue);
            color: #FFFFFF;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .btn-rm-primary:hover, .btn-primary:hover, .btn-primary:focus, .btn-blue-primary:hover {
            background-color: var(--accent-blue-hover);
            border-color: var(--accent-blue-hover);
            color: #FFFFFF;
        }

        .btn-glass-outline, .btn-outline-primary, .btn-outline-secondary {
            background-color: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.55rem 1.15rem;
            font-size: 0.85rem;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        .btn-glass-outline:hover, .btn-outline-primary:hover, .btn-outline-secondary:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
            color: var(--text-primary);
        }

        /* Clean Tables */
        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-primary);
            --bs-table-hover-color: var(--text-primary);
            --bs-table-hover-bg: #F8FAFC;
            border-color: var(--border-subtle);
        }

        .table th {
            color: var(--text-secondary);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid var(--border-subtle);
            background: #F8FAFC;
            padding: 0.75rem 1rem;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
            color: var(--text-primary);
        }

        .badge-rm, .badge-blue {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            background: rgba(59, 130, 246, 0.1);
            color: #1D4ED8;
            border: 1px solid rgba(59, 130, 246, 0.25);
            border-radius: 6px;
            padding: 0.25rem 0.55rem;
        }

        /* Light Mode Utility Harmonizers */
        .text-dark {
            color: var(--text-primary) !important;
        }
        .bg-white {
            background-color: #FFFFFF !important;
        }
        .table-light {
            --bs-table-bg: #F8FAFC !important;
            --bs-table-color: var(--text-secondary) !important;
            background-color: #F8FAFC !important;
        }
        .bg-light {
            background-color: #F8FAFC !important;
        }
        .border-light {
            border-color: var(--border-subtle) !important;
        }
        .modal-content {
            background-color: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            border-radius: 14px;
        }
        .modal-header, .modal-footer {
            border-color: var(--border-subtle);
        }
        .dropdown-menu {
            background-color: #FFFFFF;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            border-radius: 10px;
        }
        .dropdown-item {
            color: var(--text-primary);
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: #F1F5F9;
            color: var(--text-primary);
        }
        .pagination .page-link {
            background-color: #FFFFFF;
            border-color: var(--border-subtle);
            color: var(--text-primary);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            color: #FFFFFF;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #F8FAFC;
            border-color: var(--border-subtle);
            color: var(--text-muted);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .content-area {
                padding: 1.25rem;
            }
        }
    </style>
    @stack('styles')
</head>
@php
    $isCustomer = Auth::check() && Auth::user()->isPembeli();
    $isAdmin = Auth::check() && Auth::user()->isAdmin();
    $isStaff = Auth::check() && Auth::user()->isStaff();
@endphp
<body class="{{ $isCustomer ? 'customer-layout' : '' }}">
    <div class="top-hairline"></div>

    @if(!$isCustomer)
        <!-- Slate Sidebar for Admin & Staff (30% Component) -->
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-dot"></span>
                <div>
                    <div class="brand-title lh-1 font-monospace">Ctrl + C</div>
                    <small class="text-secondary font-monospace" style="font-size: 0.65rem; color: #94A3B8 !important;">
                        {{ $isAdmin ? 'ADMIN &bull; OWNER' : 'STAFF &bull; GUDANG' }}
                    </small>
                </div>
            </a>

            <div class="py-2">
                <div class="section-heading">Navigasi Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard Operasional</span>
                </a>

                @if($isAdmin)
                    <div class="section-heading">Master Data</div>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit') || request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span>Kelola Produk</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Kategori Produk</span>
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span>Pemasok (Suppliers)</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                @endif

                @if($isStaff)
                    <div class="section-heading">Katalog Produk</div>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span>Daftar Produk (Read-Only)</span>
                    </a>
                @endif

                <div class="section-heading">Transaksi Inventaris</div>
                <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Pembelian (Stock-In)</span>
                </a>
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    <span>Penjualan (Stock-Out)</span>
                </a>

                @php
                    $lowStockBadgeCount = \App\Models\Product::lowStock()->count();
                @endphp
                <a href="{{ route('products.low-stock') }}" class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('products.low-stock') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle text-warning"></i>
                        <span>Stok Kritis</span>
                    </div>
                    @if($lowStockBadgeCount > 0)
                        <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.65rem;">{{ $lowStockBadgeCount }}</span>
                    @endif
                </a>

                <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Mutasi Stok</span>
                </a>

                @if($isAdmin)
                    <div class="section-heading">Laporan & Audit</div>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Laporan Bisnis</span>
                    </a>
                @endif
            </div>
        </aside>
    @endif

    <div class="main-wrapper">
        @if($isCustomer)
            <!-- 30% Slate Top Navbar for Buyer -->
            <nav class="customer-top-navbar py-2.5 px-3 px-lg-4">
                <div class="container-fluid px-0">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none shrink-0">
                            <span class="brand-dot"></span>
                            <span class="fw-bold fs-6 text-white font-monospace">Ctrl + C</span>
                        </a>

                        <!-- Search Bar -->
                        <div class="flex-grow-1 max-w-xl mx-lg-3">
                            <form action="{{ route('customer.catalog') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Cari laptop, keyboard, mouse, monitor..." 
                                           value="{{ request('search') }}"
                                           style="border-radius: 8px 0 0 8px; border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.08); color: #FFFFFF;">
                                    <button type="submit" class="btn btn-blue-primary" style="border-radius: 0 8px 8px 0;">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right Menu Shortcuts -->
                        <div class="d-flex align-items-center gap-2 shrink-0">
                            <a href="{{ route('customer.catalog') }}" class="btn btn-sm text-white d-none d-sm-inline-flex align-items-center gap-1.5 {{ request()->routeIs('customer.catalog*') ? 'fw-bold text-primary bg-white' : '' }}" style="background: rgba(255,255,255,0.1); border-radius: 8px;">
                                <i class="bi bi-grid"></i>
                                <span>Katalog</span>
                            </a>

                            <a href="{{ route('customer.orders.index') }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1.5 {{ request()->routeIs('customer.orders*') ? 'fw-bold text-primary bg-white' : '' }}" style="background: rgba(255,255,255,0.1); border-radius: 8px;">
                                <i class="bi bi-bag-check"></i>
                                <span>Pesanan Saya</span>
                            </a>

                            <div class="dropdown">
                                <button class="btn btn-sm text-white d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.1); border-radius: 8px;">
                                    <span class="small">{{ Auth::user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border small">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
                                        <div class="text-secondary" style="font-size: 0.72rem;">{{ Auth::user()->email }}</div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                            <i class="bi bi-house me-2"></i> Buyer Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('customer.orders.index') }}">
                                            <i class="bi bi-bag-check me-2"></i> Riwayat Pesanan
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger py-2" onclick="return confirm('Keluar dari akun?')">
                                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @else
            <!-- Clean Top Navbar for Admin & Staff -->
            <header class="top-navbar">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" type="button">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark">@yield('page_heading', 'Dashboard Operasional')</h6>
                        <small class="text-muted font-monospace" style="font-size: 0.75rem;">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-glass-outline btn-sm d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary"></i>
                            <span class="small fw-semibold text-dark">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border small">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
                                <div class="text-secondary" style="font-size: 0.72rem;">{{ Auth::user()->email }}</div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('landing') }}" target="_blank">
                                    <i class="bi bi-shop me-2"></i> Storefront
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2" onclick="return confirm('Keluar dari sesi?')">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
        @endif

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
