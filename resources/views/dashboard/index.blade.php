@extends('layouts.app')

@section('title', 'Dashboard Operasional - Ctrl + C')
@section('page_heading', Auth::user()->isAdmin() ? 'Dashboard Eksekutif & Analisis' : 'Dashboard Operasional Staff Gudang')

@push('styles')
<style>
    .kpi-card {
        background: #FFFFFF;
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.08);
    }
    .kpi-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: clamp(1.2rem, 1.5vw, 1.65rem);
        font-weight: 700;
        line-height: 1.2;
        color: #0F172A;
    }
    .kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        background: #EFF6FF;
        color: var(--accent-blue);
    }
</style>
@endpush

@section('content')
<!-- Top Stream & Sync Status Bar -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 p-3 rounded-2 glass-card">
    <div class="d-flex align-items-center gap-3">
        <span class="badge bg-white bg-opacity-10 text-secondary font-monospace" style="font-size: 0.72rem;">
            LIVE SYNC
        </span>
        <div class="text-secondary small font-monospace">
            Terakhir diperbarui: <span id="lastUpdated" class="text-white fw-semibold">{{ now()->translatedFormat('H:i:s') }}</span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="form-check form-switch me-2 mb-0">
            <input class="form-check-input bg-dark border-secondary" type="checkbox" role="switch" id="autoRefreshToggle" checked>
            <label class="form-check-label small text-secondary font-monospace" for="autoRefreshToggle">Auto Refresh (5s)</label>
        </div>
        <button type="button" class="btn btn-sm btn-glass-outline font-monospace d-flex align-items-center gap-1.5" id="btnManualRefresh">
            <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
            <span>Refresh</span>
        </button>
    </div>
</div>

@if(Auth::user()->isAdmin())
    <!-- ADMIN SPECIAL SALES SUMMARY BANNER -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small font-monospace">TOTAL PENJUALAN HARI INI</span>
                    <div class="fs-4 fw-bold font-monospace text-dark mt-1">
                        Rp {{ number_format($salesToday, 0, ',', '.') }}
                    </div>
                </div>
                <div class="kpi-icon">
                    <i class="bi bi-cash"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small font-monospace">TOTAL PENJUALAN BULAN INI</span>
                    <div class="fs-4 fw-bold font-monospace text-dark mt-1">
                        Rp {{ number_format($salesThisMonth, 0, ',', '.') }}
                    </div>
                </div>
                <div class="kpi-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- ========================================================================= -->
<!-- 4 STATISTIC KPI CARDS (Minimalist Clean)                                  -->
<!-- ========================================================================= -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Produk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small font-monospace text-uppercase" style="font-size: 0.72rem;">Total Produk</span>
                <div class="kpi-icon">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div class="kpi-value text-white mb-1" id="kpiTotalProducts">
                {{ number_format($totalProducts) }} <span class="fs-6 fw-normal text-secondary">Item</span>
            </div>
            <div class="text-secondary small font-monospace" style="font-size: 0.72rem;">
                {{ $totalCategories }} Kategori aktif terdaftar
            </div>
        </div>
    </div>

    <!-- Card 2: Nilai Inventory -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small font-monospace text-uppercase" style="font-size: 0.72rem;">Nilai Inventory</span>
                <div class="kpi-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="kpi-value text-white mb-1" id="kpiInventoryValue">
                Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
            </div>
            <div class="text-secondary small font-monospace" style="font-size: 0.72rem;">
                Kalkulasi stok &times; harga satuan
            </div>
        </div>
    </div>

    <!-- Card 3: Barang Masuk (In) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small font-monospace text-uppercase" style="font-size: 0.72rem;">Barang Masuk (In)</span>
                <div class="kpi-icon">
                    <i class="bi bi-arrow-down-left"></i>
                </div>
            </div>
            <div class="kpi-value text-white mb-1" id="kpiStockIn">
                {{ number_format($totalStockIn) }} <span class="fs-6 fw-normal text-secondary">Unit</span>
            </div>
            <div class="text-secondary small font-monospace" style="font-size: 0.72rem;">
                Penerimaan dari supplier
            </div>
        </div>
    </div>

    <!-- Card 4: Barang Keluar (Out) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small font-monospace text-uppercase" style="font-size: 0.72rem;">Barang Keluar (Out)</span>
                <div class="kpi-icon">
                    <i class="bi bi-arrow-up-right"></i>
                </div>
            </div>
            <div class="kpi-value text-white mb-1" id="kpiStockOut">
                {{ number_format($totalStockOut) }} <span class="fs-6 fw-normal text-secondary">Unit</span>
            </div>
            <div class="text-secondary small font-monospace" style="font-size: 0.72rem;">
                Total transaksi pengeluaran
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Critical Banner -->
@if($lowStockCount > 0)
    <div class="glass-card p-3 mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-circle text-warning fs-4"></i>
            <div>
                <strong class="text-white font-monospace">PEMBERITAHUAN STOK MENIPIS (LOW STOCK ALERT):</strong>
                <span class="text-secondary small ms-1">Terdapat <span class="badge bg-secondary font-monospace">{{ $lowStockCount }}</span> produk dalam status stok kritis di bawah minimum.</span>
            </div>
        </div>
        <a href="{{ route('products.low-stock') }}" class="btn btn-sm btn-minimal-outline text-nowrap font-monospace">
            Buka Menu Stok Kritis &rarr;
        </a>
    </div>
@endif

@if(Auth::user()->isAdmin())
    <!-- ========================================================================= -->
    <!-- 4. ADMIN DASHBOARD SPECIFIC: CHARTS & DIAGRAMS (MINIMALIST)               -->
    <!-- ========================================================================= -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Chart 1: Tren Pergerakan Stok -->
        <div class="col-12 col-xl-8">
            <div class="glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">
                            Tren Stok Masuk vs Stok Keluar (7 Hari)
                        </h6>
                        <small class="text-secondary font-monospace" style="font-size: 0.72rem;">Volume Pembelian (Stock-In) dan Penjualan (Stock-Out)</small>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Proporsi Stok Kategori -->
        <div class="col-12 col-xl-4">
            <div class="glass-card h-100">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-semibold text-white">
                        Distribusi Stok Kategori
                    </h6>
                    <small class="text-secondary font-monospace" style="font-size: 0.72rem;">Komposisi volume unit fisik per kategori</small>
                </div>
                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 3 & Master Control -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="glass-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">
                            Top 5 Nilai Inventaris Terbesar
                        </h6>
                        <small class="text-secondary font-monospace" style="font-size: 0.72rem;">Produk dengan akumulasi modal kapital terbesar di gudang</small>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-glass-outline font-monospace" style="font-size: 0.72rem;">
                        Semua Produk
                    </a>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="glass-card h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-semibold text-white mb-2">
                        Pusat Kontrol Master Data
                    </h6>
                    <p class="text-secondary small font-monospace mb-3">Akses cepat pengelolaan entitas bisnis inventaris:</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-glass-outline d-flex align-items-center justify-content-between py-2 text-start font-monospace small">
                            <span>+ Tambah Produk Baru</span>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>
                        <a href="{{ route('categories.create') }}" class="btn btn-glass-outline d-flex align-items-center justify-content-between py-2 text-start font-monospace small">
                            <span>+ Tambah Kategori</span>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-glass-outline d-flex align-items-center justify-content-between py-2 text-start font-monospace small">
                            <span>+ Tambah Supplier</span>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-glass-outline d-flex align-items-center justify-content-between py-2 text-start font-monospace small">
                            <span>Kelola Pengguna (3 Role)</span>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>
                    </div>
                </div>
                
                <div class="pt-3 border-top mt-3" style="border-color: var(--border-subtle) !important;">
                    <a href="{{ route('reports.index') }}" class="btn btn-minimal-red w-100 py-2 font-monospace" style="font-size: 0.8rem;">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Buka Laporan Bisnis
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- ========================================================================= -->
    <!-- 5. STAFF DASHBOARD SPECIFIC: OPERATIONAL SHORTCUTS                        -->
    <!-- ========================================================================= -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-lg-7">
            <div class="glass-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <div>
                        <h6 class="fw-semibold text-white mb-0">
                            Pusat Input Transaksi Gudang & Kasir
                        </h6>
                        <small class="text-secondary font-monospace">Shortcut cepat pemrosesan mutasi barang masuk dan keluar</small>
                    </div>
                    <span class="badge bg-white bg-opacity-10 text-secondary font-monospace">STAFF</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <a href="{{ route('purchases.create') }}" class="glass-card p-3 d-flex align-items-center gap-3 text-decoration-none h-100">
                            <div class="kpi-icon">
                                <i class="bi bi-box-arrow-in-down fs-4 text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold text-white mb-1 font-monospace">+ Input Pembelian</h6>
                                <small class="text-secondary" style="font-size: 0.72rem;">Penerimaan stok (Stock-In)</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6">
                        <a href="{{ route('sales.create') }}" class="glass-card p-3 d-flex align-items-center gap-3 text-decoration-none h-100">
                            <div class="kpi-icon">
                                <i class="bi bi-cart-check fs-4 text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold text-white mb-1 font-monospace">+ Input Penjualan</h6>
                                <small class="text-secondary" style="font-size: 0.72rem;">Transaksi kasir (Stock-Out)</small>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="p-3 rounded-2 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-subtle);">
                    <span class="small font-monospace text-secondary">Mutasi stok diamankan dengan DB Transaction atomik.</span>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-glass-outline font-monospace">
                        Audit Log &rarr;
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="glass-card h-100 p-4">
                <h6 class="fw-semibold text-white mb-3 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                    Daftar Perlu Restock Cepat
                </h6>

                @if($lowStockProducts->isEmpty())
                    <div class="p-4 text-center text-secondary font-monospace small">
                        <i class="bi bi-check-circle fs-3 d-block mb-2 text-muted"></i>
                        Semua stok produk saat ini berada di atas batas minimum.
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($lowStockProducts as $lp)
                            <div class="p-2.5 rounded-2 d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-subtle);">
                                <div>
                                    <div class="fw-semibold text-white small text-truncate" style="max-width: 200px;">{{ $lp->name }}</div>
                                    <div class="text-secondary font-monospace" style="font-size: 0.68rem;">SKU: {{ $lp->sku }} &bull; Min: {{ $lp->minimum_stock }}</div>
                                </div>
                                <span class="badge bg-secondary font-monospace">Sisa: {{ $lp->stock }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- ========================================================================= -->
<!-- TABEL TRANSAKSI TERAKHIR                                                  -->
<!-- ========================================================================= -->
<div class="row g-3 g-md-4">
    <!-- Tabel Penjualan Terakhir -->
    <div class="col-12 col-xl-6">
        <div class="glass-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-semibold text-dark">
                    Penjualan Terkini (Stock-Out)
                </h6>
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
                                    <td class="font-monospace fw-semibold text-dark">{{ $sale->sale_number }}</td>
                                    <td class="small text-secondary">{{ $sale->buyer->name ?? $sale->user->name ?? '-' }}</td>
                                    <td class="font-monospace fw-semibold text-dark small">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($sale->status === 'completed')
                                            <span class="badge bg-white bg-opacity-10 text-white font-monospace" style="font-size: 0.65rem;">SELESAI</span>
                                        @else
                                            <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">CANCEL</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-glass-outline py-0.5 px-2 font-monospace" style="font-size: 0.72rem;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-3 small font-monospace">Belum ada transaksi penjualan</td>
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
                <h6 class="mb-0 fw-semibold text-dark">
                    Pembelian Terkini (Stock-In)
                </h6>
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
                                    <td class="font-monospace fw-semibold text-dark">{{ $purchase->purchase_number }}</td>
                                    <td class="small text-secondary">{{ $purchase->supplier->name ?? '-' }}</td>
                                    <td class="font-monospace fw-semibold text-dark small">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($purchase->status === 'completed')
                                            <span class="badge bg-white bg-opacity-10 text-white font-monospace" style="font-size: 0.65rem;">SELESAI</span>
                                        @else
                                            <span class="badge bg-secondary font-monospace" style="font-size: 0.65rem;">CANCEL</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-glass-outline py-0.5 px-2 font-monospace" style="font-size: 0.72rem;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-3 small font-monospace">Belum ada transaksi pembelian</td>
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
        Chart.defaults.color = '#64748B';
        Chart.defaults.borderColor = '#F1F5F9';
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        const trendCtx = document.getElementById('trendChart')?.getContext('2d');
        let trendChart;
        if (trendCtx) {
            trendChart = new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [
                        {
                            label: 'Barang Masuk (Stock-In)',
                            data: {!! json_encode($trendStockIn) !!},
                            backgroundColor: '#0F172A',
                            borderRadius: 4
                        },
                        {
                            label: 'Barang Keluar (Stock-Out)',
                            data: {!! json_encode($trendStockOut) !!},
                            backgroundColor: '#3B82F6',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10, color: '#475569' } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                        x: { grid: { display: false } }
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
                            '#3B82F6',
                            '#0F172A',
                            '#64748B',
                            '#94A3B8',
                            '#CBD5E1',
                            '#E2E8F0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 }, color: '#475569' } }
                    },
                    cutout: '72%'
                }
            });
        }

        const topCtx = document.getElementById('topProductsChart')?.getContext('2d');
        let topChart;
        if (topCtx) {
            topChart = new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProductLabels) !!},
                    datasets: [{
                        label: 'Nilai Inventaris (Rp)',
                        data: {!! json_encode($topProductValues) !!},
                        backgroundColor: '#3B82F6',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            ticks: {
                                callback: val => 'Rp ' + (val / 1000000).toFixed(1) + 'M'
                            },
                            grid: { color: '#F1F5F9' }
                        },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        const autoToggle = document.getElementById('autoRefreshToggle');
        const refreshBtn = document.getElementById('btnManualRefresh');

        function fetchRealtimeData() {
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
                .catch(err => console.error(err));
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
