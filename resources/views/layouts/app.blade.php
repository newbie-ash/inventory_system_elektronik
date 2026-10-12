<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventaris') - Inventory Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js 4.4 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-body: #f8fafc;
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
        }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #0f172a;
            color: #cbd5e1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s ease;
        }
        .sidebar .brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #1e293b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.15rem;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 0.925rem;
            transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--primary-color);
        }
        .sidebar .section-heading {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 1rem 1.5rem 0.4rem;
            font-weight: 700;
        }
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .content-area {
            padding: 1.75rem;
            flex-grow: 1;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        .badge-role {
            font-size: 0.75rem;
            padding: 0.35em 0.7em;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="bg-primary text-white rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="bi bi-box-seam-fill fs-5"></i>
            </span>
            <div>
                <div class="lh-1">InventarisApp</div>
                <small class="text-secondary fw-normal" style="font-size: 0.72rem;">Management System</small>
            </div>
        </a>

        <div class="py-3">
            <div class="section-heading">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard Visual</span>
            </a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') || request()->routeIs('products.show') || request()->routeIs('products.edit') || request()->routeIs('products.create') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i>
                <span>Katalog Produk</span>
            </a>

            <!-- Informasi 1 Dipindahkan ke Sidebar: Stok Kritis -->
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

            <!-- Informasi 2 Dipindahkan ke Sidebar: Riwayat Mutasi Transaksi -->
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
                <button class="btn btn-sm btn-light border d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold d-none d-sm-block text-dark">@yield('page_heading', 'Inventaris')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-semibold text-dark lh-sm small">{{ Auth::user()->name }}</div>
                        <div>
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-role">Administrator</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle badge-role">Staff Gudang</span>
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
        <footer class="mt-auto py-3 px-4 bg-white border-top text-center text-muted small">
            &copy; {{ date('Y') }} Inventory Management System &mdash; Migrasi Arsitektur Laravel MVC
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
