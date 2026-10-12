<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ctrl + c') - ctrl + c</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js 4.4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            --glass-bg: rgba(255, 255, 255, 0.78);
            --glass-bg-hover: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(255, 255, 255, 0.7);
            --glass-border-subtle: rgba(226, 232, 240, 0.7);
            --glass-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
            --glass-blur: blur(16px);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.10) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            color: #1e293b;
            min-height: 100vh;
        }

        /* Glass Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            color: #cbd5e1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .brand {
            padding: 1.4rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: #ffffff;
            transition: transform 0.2s ease;
        }

        .sidebar .brand:hover {
            transform: translateX(2px);
        }

        .brand-badge {
            background: var(--accent-gradient);
            border-radius: 0.75rem;
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: -0.5px;
        }

        .brand-title {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            font-family: 'JetBrains Mono', monospace;
        }

        .brand-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            letter-spacing: 0.03em;
            font-weight: 500;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.15rem;
            margin: 0.25rem 0.85rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            transition: transform 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: var(--primary-gradient);
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4);
            font-weight: 600;
        }

        .sidebar .section-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 1.25rem 1.6rem 0.4rem;
            font-weight: 700;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Glass Top Navbar */
        .top-navbar {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
            padding: 0.85rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        /* Glassmorphism Cards */
        .card, .glass-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            box-shadow: var(--glass-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--glass-border-subtle);
            padding: 1.1rem 1.4rem;
            font-weight: 600;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }

        .card-footer {
            background: rgba(255, 255, 255, 0.6);
            border-top: 1px solid var(--glass-border-subtle);
            border-bottom-left-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
        }

        /* Modern Glass Inputs & Controls */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 0.65rem;
            padding: 0.55rem 0.95rem;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .input-group-text {
            background-color: rgba(248, 250, 252, 0.85);
            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 0.65rem;
        }

        /* Glass Badges */
        .badge-role {
            font-size: 0.72rem;
            padding: 0.35em 0.8em;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            color: #334155;
        }

        /* Glass Table */
        .table {
            --bs-table-bg: transparent;
        }
        .table > :not(caption) > * > * {
            background-color: transparent;
            padding: 0.9rem 1rem;
        }
        .table-hover > tbody > tr:hover > * {
            background-color: rgba(99, 102, 241, 0.04);
        }

        /* Glass Buttons */
        .btn {
            border-radius: 0.65rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border-color: #6366f1;
            color: #4f46e5;
        }
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            color: #334155;
        }
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
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
<body>
    <!-- Glass Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-badge text-white">
                <i class="bi bi-terminal-fill"></i>
            </span>
            <div>
                <div class="brand-title lh-1">ctrl + c</div>
                <small class="brand-sub">Inventory System</small>
            </div>
        </a>

        <div class="py-3">
            <div class="section-heading">Navigasi Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.show') || request()->routeIs('products.edit') || request()->routeIs('products.create') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i>
                <span>Katalog Produk</span>
            </a>

            <!-- Informasi 1: Stok Kritis -->
            @php
                $lowStockBadgeCount = \App\Models\Product::lowStock()->count();
            @endphp
            <a href="{{ route('products.low-stock') }}" class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('products.low-stock') ? 'active' : '' }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    <span>Stok Kritis</span>
                </div>
                @if($lowStockBadgeCount > 0)
                    <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.72rem;">{{ $lowStockBadgeCount }}</span>
                @endif
            </a>

            <!-- Informasi 2: Riwayat Mutasi Transaksi -->
            <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history text-info"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill text-primary"></i>
                <span>Laporan & Analisis</span>
            </a>

            @if(Auth::check() && Auth::user()->isAdmin())
                <div class="section-heading">Master Data (Admin)</div>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i>
                    <span>Supplier</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen User</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-glass d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold d-none d-sm-block text-dark">@yield('page_heading', 'ctrl + c')</h5>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="background: var(--primary-gradient); width: 38px; height: 38px; font-size: 0.9rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold text-dark lh-sm small">{{ Auth::user()->name }}</div>
                        <div class="mt-1">
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-indigo-subtle text-primary border border-primary-subtle badge-role" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5;">Administrator</span>
                            @else
                                <span class="badge bg-emerald-subtle text-success border border-success-subtle badge-role" style="background: rgba(16, 185, 129, 0.12); color: #059669;">Staff Gudang</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-start ps-3">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="content-area">
            @include('components.alert')

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-3 px-4 text-center text-muted small" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(8px); border-top: 1px solid var(--glass-border-subtle);">
            &copy; {{ date('Y') }} <span class="fw-bold font-monospace text-dark">ctrl + c</span> &mdash; Inventory Management System
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
