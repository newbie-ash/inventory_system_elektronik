@extends('layouts.app')

@section('title', 'Dashboard Realtime & Analisis')
@section('page_heading', 'Dashboard Pemantauan Stok & Analisis Realtime')

@push('styles')
<style>
    .kpi-card {
        border-radius: 0.85rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
    }
    .kpi-value {
        font-size: clamp(1.2rem, 1.4vw, 1.6rem);
        font-weight: 700;
        line-height: 1.2;
    }
    .live-dot {
        width: 9px;
        height: 9px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseLive 2s infinite;
    }
    @keyframes pulseLive {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }
</style>
@endpush

@section('content')
<!-- Header Kontrol Realtime -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-3 border shadow-sm">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 px-3 py-1 bg-success-subtle border border-success-subtle rounded-pill">
            <span class="live-dot"></span>
            <span class="small fw-bold text-success">REALTIME STREAM</span>
        </div>
        <div class="text-muted small">
            Terakhir disinkronkan: <span id="lastUpdated" class="fw-semibold text-dark">{{ now()->translatedFormat('H:i:s') }}</span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="form-check form-switch me-2 mb-0">
            <input class="form-check-input" type="checkbox" role="switch" id="autoRefreshToggle" checked>
            <label class="form-check-label small text-secondary" for="autoRefreshToggle">Auto Refresh (5s)</label>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center gap-1 shadow-sm" id="btnManualRefresh">
            <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
            <span>Sinkronkan Sekarang</span>
        </button>
    </div>
</div>

<!-- 4 KPI Card Widgets (Tata Letak Baru yang Anti-Overflow & Rapi) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Produk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm bg-white kpi-card overflow-hidden">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Produk</span>
                    <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-boxes fs-5"></i>
                    </div>
                </div>
                <div class="kpi-value text-dark mb-1 text-truncate" id="kpiTotalProducts">
                    {{ number_format($totalProducts) }} <span class="fs-6 fw-normal text-muted">Item</span>
                </div>
                <div class="text-secondary small text-truncate" style="font-size: 0.75rem;">
                    {{ $totalCategories }} Kategori aktif terdaftar
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Nilai Inventory (Rapi, Luas, Tidak Meluber) -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm bg-white kpi-card overflow-hidden">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Nilai Inventory</span>
                    <div class="bg-success-subtle text-success rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                </div>
                <div class="kpi-value text-success mb-1 text-truncate" id="kpiInventoryValue" title="Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}">
                    Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
                </div>
                <div class="text-secondary small text-truncate" style="font-size: 0.75rem;">
                    &Sigma; (Stok Fisik &times; Harga Produk)
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Barang Masuk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm bg-white kpi-card overflow-hidden">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Barang Masuk (In)</span>
                    <div class="bg-info-subtle text-info rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-down-left-circle fs-5"></i>
                    </div>
                </div>
                <div class="kpi-value text-dark mb-1 text-truncate" id="kpiStockIn">
                    +{{ number_format($totalStockIn) }} <span class="fs-6 fw-normal text-muted">unit</span>
                </div>
                <div class="text-info fw-medium small text-truncate" style="font-size: 0.75rem;">
                    Total histori penerimaan stok
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Barang Keluar -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm bg-white kpi-card overflow-hidden">
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Barang Keluar (Out)</span>
                    <div class="bg-warning-subtle text-warning rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-up-right-circle fs-5"></i>
                    </div>
                </div>
                <div class="kpi-value text-dark mb-1 text-truncate" id="kpiStockOut">
                    -{{ number_format($totalStockOut) }} <span class="fs-6 fw-normal text-muted">unit</span>
                </div>
                <div class="text-warning-emphasis fw-medium small text-truncate" style="font-size: 0.75rem;">
                    Total histori pengeluaran stok
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banner Akses Cepat Informasi Stok Kritis -->
<div id="lowStockAlertContainer" class="{{ $lowStockCount > 0 ? '' : 'd-none' }}">
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-3 mb-4 rounded-3" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i>
            <div>
                <strong class="text-dark">Pemberitahuan Sistem:</strong> 
                Terdapat <span class="badge bg-danger fs-6" id="kpiLowStockBadge">{{ $lowStockCount }}</span> produk dalam status stok rendah / kritis.
                <div class="small text-secondary d-none d-md-block">Data tabel lengkap telah dipindahkan ke menu khusus di bilah samping (sidebar).</div>
            </div>
        </div>
        <a href="{{ route('products.low-stock') }}" class="btn btn-warning btn-sm fw-bold text-dark shadow-sm text-nowrap">
            <i class="bi bi-arrow-right-circle me-1"></i> Buka Menu Stok Kritis
        </a>
    </div>
</div>

<!-- Grid Charts & Grafik Visual Realtime -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Tren Mutasi Barang Masuk vs Keluar (Line / Area Chart Realtime) -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up text-primary me-2"></i>Tren Pergerakan Stok 7 Hari Terakhir</h6>
                    <small class="text-muted">Perbandingan volume transaksi Barang Masuk (In) vs Barang Keluar (Out)</small>
                </div>
                <span class="badge bg-light text-secondary border">Realtime Stream</span>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Proporsi Stok Fisik per Kategori (Doughnut Chart) -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-info me-2"></i>Distribusi Stok Kategori</h6>
                    <small class="text-muted">Komposisi kuantitas unit per kategori</small>
                </div>
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Baris Kedua: Top Nilai Aset Produk & Ringkasan Cepat -->
<div class="row g-4 mb-4">
    <!-- Chart 3: Top 5 Produk Nilai Inventaris Terbesar -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bar-chart-fill text-success me-2"></i>Top 5 Produk Nilai Aset Tertinggi</h6>
                    <small class="text-muted">Produk dengan total nilai kapital terbesar di gudang (Stok &times; Harga)</small>
                </div>
                <a href="{{ route('products.index', ['sort' => 'price', 'direction' => 'desc']) }}" class="btn btn-sm btn-outline-secondary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-3">
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Pintasan Akses Cepat Informasi Sidebar -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-compass text-primary me-2"></i>Pusat Navigasi & Aksi Cepat</h6>
                <small class="text-muted">Akses informasi yang telah dipindahkan ke menu sidebar</small>
            </div>
            <div class="card-body p-3 d-flex flex-column gap-2 justify-content-center">
                <a href="{{ route('products.low-stock') }}" class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-decoration-none text-dark hover-shadow">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning-subtle text-warning p-2 rounded-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Menu Stok Kritis</div>
                            <small class="text-muted">Monitoring produk di bawah batas aman</small>
                        </div>
                    </div>
                    <span class="badge bg-danger rounded-pill">{{ $lowStockCount }} item</span>
                </a>

                <a href="{{ route('stock-movements.index') }}" class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-decoration-none text-dark hover-shadow">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info-subtle text-info p-2 rounded-2">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Menu Riwayat Transaksi</div>
                            <small class="text-muted">Audit log pergerakan stok masuk & keluar</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>

                <a href="{{ route('reports.index') }}" class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-decoration-none text-dark hover-shadow">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary p-2 rounded-2">
                            <i class="bi bi-pie-chart-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Laporan & Analisis Agregat</div>
                            <small class="text-muted">Rekapitulasi GROUP BY & matriks mutasi</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi Data Awal dari Server (SSR)
    let trendLabels = @json($trendLabels);
    let trendStockIn = @json($trendStockIn);
    let trendStockOut = @json($trendStockOut);

    let catLabels = @json($catLabels);
    let catStocks = @json($catStocks);

    let topProductLabels = @json($topProductLabels);
    let topProductValues = @json($topProductValues);

    // Palet Warna Elegan Modern
    const palette = [
        '#2563eb', '#10b981', '#f59e0b', '#ef4444', 
        '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'
    ];

    // 1. Chart Tren Mutasi (Area / Line Chart)
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Barang Masuk (In)',
                    data: trendStockIn,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Barang Keluar (Out)',
                    data: trendStockOut,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label}: ${context.parsed.y} unit`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(226, 232, 240, 0.6)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Chart Distribusi Stok per Kategori (Doughnut Chart)
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catStocks,
                backgroundColor: palette,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const val = context.raw || 0;
                            return ` ${context.label}: ${val} unit`;
                        }
                    }
                }
            }
        }
    });

    // 3. Chart Top 5 Produk Nilai Inventaris (Horizontal Bar Chart)
    const ctxTopProducts = document.getElementById('topProductsChart').getContext('2d');
    const topProductsChart = new Chart(ctxTopProducts, {
        type: 'bar',
        data: {
            labels: topProductLabels,
            datasets: [{
                label: 'Nilai Inventaris (Rp)',
                data: topProductValues,
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderRadius: 6,
                borderWidth: 0,
                barThickness: 18,
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
                            return ` Nilai: Rp ${Number(context.raw).toLocaleString('id-ID')}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + ' Jt';
                            return value;
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(226, 232, 240, 0.6)' }
                },
                y: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });

    // Fitur Realtime AJAX Polling
    async function fetchRealtimeDashboardData() {
        const refreshIcon = document.getElementById('refreshIcon');
        refreshIcon.classList.add('spin-animation');

        try {
            const response = await fetch("{{ route('dashboard.realtime') }}");
            if (!response.ok) throw new Error('Network error');
            const result = await response.json();

            // 1. Update KPI Card Values
            document.getElementById('kpiTotalProducts').innerHTML = `${result.kpis.total_products} <span class="fs-6 fw-normal text-muted">Item</span>`;
            document.getElementById('kpiInventoryValue').textContent = result.kpis.total_inventory_value;
            document.getElementById('kpiInventoryValue').setAttribute('title', result.kpis.total_inventory_value);
            document.getElementById('kpiStockIn').innerHTML = `+${result.kpis.total_stock_in} <span class="fs-6 fw-normal text-muted">unit</span>`;
            document.getElementById('kpiStockOut').innerHTML = `-${result.kpis.total_stock_out} <span class="fs-6 fw-normal text-muted">unit</span>`;
            document.getElementById('lastUpdated').textContent = result.timestamp;

            // Update Low Stock Alert Container
            const lowStockAlert = document.getElementById('lowStockAlertContainer');
            const lowStockBadge = document.getElementById('kpiLowStockBadge');
            if (result.kpis.low_stock_count > 0) {
                lowStockAlert.classList.remove('d-none');
                lowStockBadge.textContent = result.kpis.low_stock_count;
            } else {
                lowStockAlert.classList.add('d-none');
            }

            // 2. Update Trend Chart
            trendChart.data.labels = result.chart_trends.labels;
            trendChart.data.datasets[0].data = result.chart_trends.stock_in;
            trendChart.data.datasets[1].data = result.chart_trends.stock_out;
            trendChart.update('none');

            // 3. Update Category Chart
            categoryChart.data.labels = result.chart_categories.labels;
            categoryChart.data.datasets[0].data = result.chart_categories.data;
            categoryChart.update('none');

            // 4. Update Top Products Chart
            topProductsChart.data.labels = result.chart_top_products.labels;
            topProductsChart.data.datasets[0].data = result.chart_top_products.data;
            topProductsChart.update('none');

        } catch (error) {
            console.warn('Realtime fetch warning:', error);
        } finally {
            setTimeout(() => {
                refreshIcon.classList.remove('spin-animation');
            }, 500);
        }
    }

    // Event Listener Tombol Manual
    document.getElementById('btnManualRefresh').addEventListener('click', fetchRealtimeDashboardData);

    // Auto-refresh Interval Timer
    let pollingTimer = null;
    const autoRefreshToggle = document.getElementById('autoRefreshToggle');

    function setupPolling() {
        if (autoRefreshToggle.checked) {
            if (!pollingTimer) {
                pollingTimer = setInterval(fetchRealtimeDashboardData, 5000); // 5 detik
            }
        } else {
            if (pollingTimer) {
                clearInterval(pollingTimer);
                pollingTimer = null;
            }
        }
    }

    autoRefreshToggle.addEventListener('change', setupPolling);
    setupPolling();
</script>
<style>
    .spin-animation {
        animation: spin 0.8s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
</style>
@endpush
