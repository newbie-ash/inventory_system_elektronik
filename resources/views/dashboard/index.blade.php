@extends('layouts.app')

@section('title', 'Dashboard Operasional - Ctrl+R')
@section('page_heading', Auth::user()->isAdmin() ? 'Dashboard Eksekutif & Analisis' : 'Dashboard Operasional Staff Gudang')

@push('styles')
<style>
    /* Gradient Glassmorphism KPI Cards */
    .kpi-card {
        background: linear-gradient(145deg, rgba(71, 73, 115, 0.28) 0%, rgba(22, 27, 51, 0.75) 100%) !important;
        border: 1px solid rgba(166, 156, 172, 0.25) !important;
        border-radius: 20px !important;
        box-shadow: 0 14px 35px -8px rgba(0, 0, 0, 0.5), inset 0 1px 0 0 rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        border-color: rgba(241, 218, 196, 0.45) !important;
        box-shadow: 0 20px 42px -10px rgba(0, 0, 0, 0.65), 0 0 26px -6px rgba(241, 218, 196, 0.2) !important;
    }
    .kpi-card::after {
        content: '';
        position: absolute;
        top: -35px;
        right: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(28px);
        opacity: 0.6;
        transition: opacity 0.25s ease;
    }
    .kpi-card:hover::after {
        opacity: 1;
    }

    .kpi-card.theme-accent::after { background: rgba(241, 218, 196, 0.35); }
    .kpi-card.theme-emerald::after { background: rgba(52, 211, 153, 0.35); }
    .kpi-card.theme-blue::after { background: rgba(96, 165, 250, 0.35); }
    .kpi-card.theme-purple::after { background: rgba(192, 132, 252, 0.35); }

    .kpi-icon-squircle {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        transition: transform 0.25s ease;
    }
    .kpi-card:hover .kpi-icon-squircle {
        transform: scale(1.08) rotate(2deg);
    }

    .icon-accent {
        background: linear-gradient(135deg, rgba(241, 218, 196, 0.25) 0%, rgba(71, 73, 115, 0.4) 100%);
        border: 1px solid rgba(241, 218, 196, 0.35);
        color: var(--colors-accent);
    }
    .icon-emerald {
        background: linear-gradient(135deg, rgba(52, 211, 153, 0.25) 0%, rgba(16, 185, 129, 0.15) 100%);
        border: 1px solid rgba(52, 211, 153, 0.35);
        color: #34D399;
    }
    .icon-blue {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.25) 0%, rgba(59, 130, 246, 0.15) 100%);
        border: 1px solid rgba(96, 165, 250, 0.35);
        color: #60A5FA;
    }
    .icon-purple {
        background: linear-gradient(135deg, rgba(192, 132, 252, 0.25) 0%, rgba(147, 51, 234, 0.15) 100%);
        border: 1px solid rgba(192, 132, 252, 0.35);
        color: #C084FC;
    }

    .kpi-value {
        font-size: clamp(1.35rem, 1.7vw, 1.85rem);
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.2;
        color: #FFFFFF;
    }

    /* Hero Banner Cards */
    .hero-banner-card {
        border-radius: 20px !important;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        box-shadow: 0 16px 38px -10px rgba(0, 0, 0, 0.55), inset 0 1px 0 0 rgba(255, 255, 255, 0.1) !important;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-banner-card:hover {
        transform: translateY(-3px);
    }
    .hero-banner-accent {
        background: linear-gradient(135deg, rgba(241, 218, 196, 0.16) 0%, rgba(71, 73, 115, 0.3) 50%, rgba(22, 27, 51, 0.8) 100%) !important;
        border: 1px solid rgba(241, 218, 196, 0.32) !important;
    }
    .hero-banner-blue {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.16) 0%, rgba(71, 73, 115, 0.3) 50%, rgba(22, 27, 51, 0.8) 100%) !important;
        border: 1px solid rgba(96, 165, 250, 0.32) !important;
    }

    /* Live Pulse Indicator */
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #34D399;
        box-shadow: 0 0 10px #34D399;
        display: inline-block;
        animation: pulseAnimation 2s infinite ease-in-out;
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(52, 211, 153, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); }
    }

    /* Quick Action Cards */
    .spin-animation {
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
    .quick-action-card {
        border-radius: 18px !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none !important;
        position: relative;
        overflow: hidden;
    }
    .quick-action-card:hover {
        transform: translateY(-4px);
    }
    .quick-action-in {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.18) 0%, rgba(71, 73, 115, 0.32) 100%) !important;
        border: 1px solid rgba(96, 165, 250, 0.35) !important;
    }
    .quick-action-in:hover {
        border-color: rgba(96, 165, 250, 0.6) !important;
        box-shadow: 0 14px 30px -8px rgba(0, 0, 0, 0.6), 0 0 20px rgba(96, 165, 250, 0.2) !important;
    }
    .quick-action-out {
        background: linear-gradient(135deg, rgba(241, 218, 196, 0.18) 0%, rgba(71, 73, 115, 0.32) 100%) !important;
        border: 1px solid rgba(241, 218, 196, 0.35) !important;
    }
    .quick-action-out:hover {
        border-color: rgba(241, 218, 196, 0.6) !important;
        box-shadow: 0 14px 30px -8px rgba(0, 0, 0, 0.6), 0 0 20px rgba(241, 218, 196, 0.2) !important;
    }
</style>
@endpush

@section('content')
<!-- Top Stream & Sync Status Bar (Glassmorphic Bar) -->
<div class="glass-card mb-3 p-2.5 px-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5">
    <div class="d-flex align-items-center gap-3">
        <span class="badge badge-gradient-accent d-flex align-items-center gap-2 font-monospace" style="font-size: 0.72rem;">
            <span class="pulse-dot"></span>
            LIVE SYNC
        </span>
        <div class="small font-monospace" style="color: var(--colors-muted);">
            Terakhir diperbarui: <span id="lastUpdated" class="text-white fw-semibold">{{ now()->translatedFormat('H:i:s') }}</span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="form-check form-switch me-2 mb-0">
            <input class="form-check-input bg-dark border-secondary" type="checkbox" role="switch" id="autoRefreshToggle" checked>
            <label class="form-check-label small font-monospace" for="autoRefreshToggle" style="color: var(--colors-muted);">Auto Refresh (5s)</label>
        </div>
        <button type="button" class="btn btn-sm btn-glass-outline font-monospace d-flex align-items-center gap-1.5" id="btnManualRefresh">
            <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
            <span>Refresh</span>
        </button>
    </div>
</div>

@if(Auth::user()->isAdmin())
    <!-- ADMIN SPECIAL SALES SUMMARY BANNER (Gradient Hero Cards) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6">
            <div class="hero-banner-card hero-banner-accent p-3 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge badge-gradient-accent text-uppercase font-monospace mb-1.5" style="font-size: 0.68rem;">
                        Penjualan Hari Ini
                    </span>
                    <div class="fs-4 fw-bold font-monospace text-white mt-0.5" style="letter-spacing: -0.5px;">
                        Rp {{ number_format($salesToday, 0, ',', '.') }}
                    </div>
                    <small class="d-block mt-0.5 font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                        Akumulasi pesanan terverifikasi sistem
                    </small>
                </div>
                <div class="kpi-icon-squircle icon-accent" style="width: 50px; height: 50px; font-size: 1.35rem;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="hero-banner-card hero-banner-blue p-3 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge badge-gradient-info text-uppercase font-monospace mb-1.5" style="font-size: 0.68rem;">
                        Penjualan Bulan Ini
                    </span>
                    <div class="fs-4 fw-bold font-monospace text-white mt-0.5" style="letter-spacing: -0.5px;">
                        Rp {{ number_format($salesThisMonth, 0, ',', '.') }}
                    </div>
                    <small class="d-block mt-0.5 font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                        Total transaksi mutasi kasir & checkout
                    </small>
                </div>
                <div class="kpi-icon-squircle icon-blue" style="width: 50px; height: 50px; font-size: 1.35rem;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- ========================================================================= -->
<!-- 4 STATISTIC KPI CARDS (Gradient Glassmorphism)                            -->
<!-- ========================================================================= -->
<div class="row g-3 mb-3">
    <!-- Card 1: Total Produk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-accent p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Total Produk
                </span>
                <div class="kpi-icon-squircle icon-accent">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" id="kpiTotalProducts">
                    {{ number_format($totalProducts) }} <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Item</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    <span class="text-white fw-semibold">{{ $totalCategories }}</span> Kategori aktif terdaftar
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Nilai Inventory -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-emerald p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Nilai Inventory
                </span>
                <div class="kpi-icon-squircle icon-emerald">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" id="kpiInventoryValue" style="color: #6EE7B7;">
                    Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Kalkulasi stok &times; harga satuan
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Barang Masuk (In) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-blue p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Barang Masuk (In)
                </span>
                <div class="kpi-icon-squircle icon-blue">
                    <i class="bi bi-arrow-down-left"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" id="kpiStockIn">
                    {{ number_format($totalStockIn) }} <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Unit</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Penerimaan dari supplier
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Barang Keluar (Out) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-purple p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Barang Keluar (Out)
                </span>
                <div class="kpi-icon-squircle icon-purple">
                    <i class="bi bi-arrow-up-right"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" id="kpiStockOut">
                    {{ number_format($totalStockOut) }} <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Unit</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Total transaksi pengeluaran
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Critical Banner -->
@if($lowStockCount > 0)
    <div class="glass-card p-2.5 px-3 mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" 
         style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.16) 0%, rgba(71, 73, 115, 0.28) 100%) !important; border: 1px solid rgba(239, 68, 68, 0.4) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon-squircle" style="width: 42px; height: 42px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #F87171;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
                <strong class="text-white font-monospace">PEMBERITAHUAN STOK MENIPIS (LOW STOCK ALERT):</strong>
                <span class="small ms-1" style="color: var(--colors-muted);">
                    Terdapat <span class="badge badge-gradient-danger font-monospace">{{ $lowStockCount }}</span> produk dalam status stok kritis di bawah minimum.
                </span>
            </div>
        </div>
        <a href="{{ route('products.low-stock') }}" class="btn btn-sm btn-glass-outline text-nowrap font-monospace">
            Buka Menu Stok Kritis &rarr;
        </a>
    </div>
@endif

@if(Auth::user()->isAdmin())
    <!-- ========================================================================= -->
    <!-- 4. ADMIN DASHBOARD SPECIFIC: CHARTS & DIAGRAMS (Rich Canvas Gradients)     -->
    <!-- ========================================================================= -->
    <div class="row g-3 mb-3">
        <!-- Chart 1: Tren Pergerakan Stok (Panoramic 7 Hari) -->
        <div class="col-12">
            <div class="glass-card">
                <div class="card-header d-flex align-items-center justify-content-between py-2.5">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">
                            Tren Stok Masuk vs Stok Keluar (7 Hari)
                        </h6>
                        <small class="font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                            Volume Pembelian (Stock-In) dan Penjualan (Stock-Out)
                        </small>
                    </div>
                    <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.68rem;">ANALISIS MINGGUAN</span>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2 & 3: Balanced Analytics Grid (Equal Columns) -->
    <div class="row g-3 mb-3">
        <!-- Chart 2: Top 5 Nilai Inventaris Terbesar -->
        <div class="col-12 col-lg-6">
            <div class="glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-2.5">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">
                            Top 5 Nilai Inventaris Terbesar
                        </h6>
                        <small class="font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                            Produk dengan akumulasi modal kapital terbesar di gudang
                        </small>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-glass-outline font-monospace" style="font-size: 0.72rem;">
                        Semua Produk
                    </a>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 3: Distribusi Stok Kategori -->
        <div class="col-12 col-lg-6">
            <div class="glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-2.5">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">
                            Distribusi Stok Kategori
                        </h6>
                        <small class="font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                            Komposisi volume unit fisik per kategori
                        </small>
                    </div>
                    <span class="badge badge-gradient-info font-monospace" style="font-size: 0.68rem;">KOMPOSISI UNIT</span>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- ========================================================================= -->
    <!-- 5. STAFF DASHBOARD SPECIFIC: OPERATIONAL SHORTCUTS (Modern Glassmorphism)  -->
    <!-- ========================================================================= -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-lg-7">
            <div class="glass-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--colors-hairline) !important;">
                    <div>
                        <h6 class="fw-semibold text-white mb-0">
                            Pusat Input Transaksi Gudang & Kasir
                        </h6>
                        <small class="font-monospace" style="color: var(--colors-muted);">Shortcut cepat pemrosesan mutasi barang masuk dan keluar</small>
                    </div>
                    <span class="badge badge-gradient-accent font-monospace">STAFF OPERASIONAL</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <a href="{{ route('purchases.create') }}" class="quick-action-card quick-action-in p-3.5 d-flex align-items-center gap-3 h-100">
                            <div class="kpi-icon-squircle icon-blue">
                                <i class="bi bi-box-arrow-in-down fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold text-white mb-1 font-monospace">+ Input Pembelian</h6>
                                <small style="color: var(--colors-muted); font-size: 0.72rem;">Penerimaan stok (Stock-In)</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6">
                        <a href="{{ route('sales.create') }}" class="quick-action-card quick-action-out p-3.5 d-flex align-items-center gap-3 h-100">
                            <div class="kpi-icon-squircle icon-accent">
                                <i class="bi bi-cart-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold text-white mb-1 font-monospace">+ Input Penjualan</h6>
                                <small style="color: var(--colors-muted); font-size: 0.72rem;">Transaksi kasir (Stock-Out)</small>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="p-3 d-flex align-items-center justify-content-between rounded-3" 
                     style="background: rgba(22, 27, 51, 0.7); border: 1px solid var(--colors-hairline);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check" style="color: #34D399;"></i>
                        <span class="small font-monospace" style="color: var(--colors-muted);">Mutasi stok diamankan dengan DB Transaction atomik.</span>
                    </div>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-glass-outline font-monospace">
                        Audit Log &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="glass-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--colors-hairline) !important;">
                    <h6 class="fw-semibold text-white mb-0">
                        Daftar Perlu Restock Cepat
                    </h6>
                    <span class="badge badge-gradient-warning font-monospace" style="font-size: 0.68rem;">RESTOCK ALERT</span>
                </div>

                @if($lowStockProducts->isEmpty())
                    <div class="p-4 text-center font-monospace small" style="color: var(--colors-muted);">
                        <i class="bi bi-check-circle fs-2 d-block mb-2" style="color: #34D399;"></i>
                        Semua stok produk saat ini berada di atas batas minimum.
                    </div>
                @else
                    <div class="d-flex flex-column gap-2.5">
                        @foreach($lowStockProducts as $lp)
                            <div class="p-2.5 d-flex align-items-center justify-content-between rounded-3" 
                                 style="background: rgba(22, 27, 51, 0.7); border: 1px solid var(--colors-hairline);">
                                <div>
                                    <div class="fw-semibold text-white small text-truncate" style="max-width: 200px;">{{ $lp->name }}</div>
                                    <div class="font-monospace" style="color: var(--colors-muted); font-size: 0.68rem;">SKU: {{ $lp->sku }} &bull; Min: {{ $lp->minimum_stock }}</div>
                                </div>
                                <span class="badge badge-gradient-danger font-monospace">Sisa: {{ $lp->stock }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- ========================================================================= -->
<!-- TABEL TRANSAKSI TERAKHIR (Frosted Glass Tables)                           -->
<!-- ========================================================================= -->
<div class="row g-3">
    <!-- Tabel Penjualan Terakhir -->
    <div class="col-12 col-xl-6">
        <div class="glass-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cart-check" style="color: var(--colors-accent);"></i>
                    <h6 class="mb-0 fw-semibold text-white">
                        Penjualan Terkini (Stock-Out)
                    </h6>
                </div>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-glass-outline font-monospace" style="font-size: 0.72rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No. Sale</th>
                                <th>Pembeli / Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="font-monospace fw-semibold text-white">{{ $sale->sale_number }}</td>
                                    <td class="small" style="color: var(--colors-muted);">{{ $sale->buyer->name ?? $sale->user->name ?? '-' }}</td>
                                    <td class="font-monospace fw-semibold text-white small">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($sale->status === 'completed')
                                            <span class="badge badge-gradient-success font-monospace" style="font-size: 0.65rem;">SELESAI</span>
                                        @else
                                            <span class="badge badge-gradient-danger font-monospace" style="font-size: 0.65rem;">CANCEL</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-glass-outline py-1 px-2.5 font-monospace" style="font-size: 0.72rem;">
                                             Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 small font-monospace" style="color: var(--colors-muted);">Belum ada transaksi penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pembelian Terakhir -->
    <div class="col-12 col-xl-6">
        <div class="glass-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-down" style="color: #60A5FA;"></i>
                    <h6 class="mb-0 fw-semibold text-white">
                        Pembelian Terkini (Stock-In)
                    </h6>
                </div>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-glass-outline font-monospace" style="font-size: 0.72rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No. Purchase</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPurchases as $purchase)
                                <tr>
                                    <td class="font-monospace fw-semibold text-white">{{ $purchase->purchase_number }}</td>
                                    <td class="small" style="color: var(--colors-muted);">{{ $purchase->supplier->name ?? '-' }}</td>
                                    <td class="font-monospace fw-semibold text-white small">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($purchase->status === 'completed')
                                            <span class="badge badge-gradient-success font-monospace" style="font-size: 0.65rem;">SELESAI</span>
                                        @else
                                            <span class="badge badge-gradient-danger font-monospace" style="font-size: 0.65rem;">CANCEL</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-glass-outline py-1 px-2.5 font-monospace" style="font-size: 0.72rem;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 small font-monospace" style="color: var(--colors-muted);">Belum ada transaksi pembelian</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(Auth::user()->isAdmin())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Chart.defaults.color = '#A69CAC';
        Chart.defaults.borderColor = 'rgba(166, 156, 172, 0.15)';
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        // Helper function for vertical gradients
        function createVGradient(ctx, topColor, bottomColor) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, topColor);
            gradient.addColorStop(1, bottomColor);
            return gradient;
        }

        // Helper function for horizontal gradients
        function createHGradient(ctx, leftColor, rightColor) {
            const gradient = ctx.createLinearGradient(0, 0, 400, 0);
            gradient.addColorStop(0, leftColor);
            gradient.addColorStop(1, rightColor);
            return gradient;
        }

        const trendCtx = document.getElementById('trendChart')?.getContext('2d');
        let trendChart;
        if (trendCtx) {
            const inGradient = createVGradient(trendCtx, 'rgba(166, 156, 172, 0.85)', 'rgba(71, 73, 115, 0.25)');
            const outGradient = createVGradient(trendCtx, '#F1DAC4', 'rgba(241, 218, 196, 0.2)');

            trendChart = new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [
                        {
                            label: 'Barang Masuk (Stock-In)',
                            data: {!! json_encode($trendStockIn) !!},
                            backgroundColor: inGradient,
                            borderColor: 'rgba(166, 156, 172, 0.9)',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false
                        },
                        {
                            label: 'Barang Keluar (Stock-Out)',
                            data: {!! json_encode($trendStockOut) !!},
                            backgroundColor: outGradient,
                            borderColor: '#F1DAC4',
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'top', 
                            labels: { 
                                boxWidth: 12, 
                                boxHeight: 12,
                                borderRadius: 3,
                                useBorderRadius: true,
                                color: '#FFFFFF',
                                font: { weight: 600 }
                            } 
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(166, 156, 172, 0.12)' }, 
                            ticks: { color: '#A69CAC' } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#A69CAC' } 
                        }
                    }
                }
            });
        }

        const catCtx = document.getElementById('categoryChart')?.getContext('2d');
        let categoryChart;
        if (catCtx) {
            categoryChart = new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($catLabels) !!},
                    datasets: [{
                        data: {!! json_encode($catStocks) !!},
                        backgroundColor: [
                            '#F1DAC4',
                            '#60A5FA',
                            '#34D399',
                            '#C084FC',
                            '#F472B6',
                            '#A69CAC',
                            '#FBBF24',
                            '#38BDF8'
                        ],
                        borderColor: '#161B33',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: { 
                            position: window.innerWidth > 991 ? 'right' : 'bottom', 
                            labels: { 
                                boxWidth: 12, 
                                boxHeight: 12,
                                borderRadius: 4,
                                useBorderRadius: true,
                                font: { size: 12, weight: 500, family: "'Plus Jakarta Sans', sans-serif" }, 
                                color: '#FFFFFF',
                                padding: 12
                            } 
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed || 0;
                                    return ` ${context.label}: ${value} Unit`;
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        const topCtx = document.getElementById('topProductsChart')?.getContext('2d');
        let topChart;
        if (topCtx) {
            const barGradient = createHGradient(topCtx, '#F1DAC4', '#D4A373');

            topChart = new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProductLabels) !!},
                    datasets: [{
                        label: 'Nilai Inventaris (Rp)',
                        data: {!! json_encode($topProductValues) !!},
                        backgroundColor: barGradient,
                        borderColor: '#F1DAC4',
                        borderWidth: 1,
                        borderRadius: 8,
                        barThickness: 16,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.x || 0;
                                    return ' Nilai: Rp ' + new Intl.NumberFormat('id-ID').format(val);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 0,
                                minRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 5,
                                callback: function(val) {
                                    if (val >= 1000000000) {
                                        return 'Rp ' + (val / 1000000000).toFixed(1) + 'B';
                                    }
                                    if (val >= 1000000) {
                                        return 'Rp ' + (val / 1000000).toFixed(0) + ' jt';
                                    }
                                    return 'Rp ' + val;
                                },
                                color: '#A69CAC',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }
                            },
                            grid: { color: 'rgba(166, 156, 172, 0.12)' }
                        },
                        y: { 
                            grid: { display: false }, 
                            ticks: { 
                                color: '#FFFFFF', 
                                font: { size: 12, weight: 500, family: "'Plus Jakarta Sans', sans-serif" } 
                            } 
                        }
                    }
                }
            });
        }

        const autoToggle = document.getElementById('autoRefreshToggle');
        const refreshBtn = document.getElementById('btnManualRefresh');

        function fetchRealtimeData() {
            const refreshIcon = document.getElementById('refreshIcon');
            if (refreshIcon) refreshIcon.classList.add('spin-animation');

            fetch("{{ route('dashboard.realtime') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('lastUpdated').innerText = data.timestamp;
                        if (trendChart && data.chart_trends) {
                            trendChart.data.labels = data.chart_trends.labels;
                            trendChart.data.datasets[0].data = data.chart_trends.stock_in;
                            trendChart.data.datasets[1].data = data.chart_trends.stock_out;
                            trendChart.update();
                        }
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    if (refreshIcon) {
                        setTimeout(() => refreshIcon.classList.remove('spin-animation'), 600);
                    }
                });
        }

        setInterval(() => {
            if (autoToggle && autoToggle.checked) {
                fetchRealtimeData();
            }
        }, 5000);

        if (refreshBtn) {
            refreshBtn.addEventListener('click', fetchRealtimeData);
        }
    });
</script>
@endif
@endpush
