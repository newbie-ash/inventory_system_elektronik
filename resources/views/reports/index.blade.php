@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('page_heading', 'Laporan Inventaris & Analisis')

@section('content')
<!-- Action Toolbar: Export PDF & Excel Berkop -->
<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-white mb-1">Ikhtisar & Laporan Manajemen</h5>
        <p class="small text-muted mb-0">Audit inventaris menyeluruh, mutasi keluar-masuk barang, dan evaluasi laba kotor.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-sm d-flex align-items-center gap-1.5 font-monospace" style="background: #FFFFFF; border: 1px solid var(--colors-hairline); color: #DC2626; border-radius: 8px; font-weight: 600;" target="_blank" title="Unduh Berkas PDF Berkop Resmi">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            <span>Export PDF Berkop</span>
        </a>
        <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-sm d-flex align-items-center gap-1.5 font-monospace" style="background: #FFFFFF; border: 1px solid var(--colors-hairline); color: #15803D; border-radius: 8px; font-weight: 600;" title="Unduh Spreadsheet Excel Mutasi Stok">
            <i class="bi bi-file-earmark-excel-fill"></i>
            <span>Export Excel Mutasi</span>
        </a>
        <a href="{{ route('reports.export.sales-excel') }}" class="btn btn-sm d-flex align-items-center gap-1.5 font-monospace" style="background: #FFFFFF; border: 1px solid var(--colors-hairline); color: #1A1A1C; border-radius: 8px; font-weight: 600;" title="Unduh Spreadsheet Excel Rekap Penjualan & Laba Rugi">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Excel Laba-Rugi</span>
        </a>
    </div>
</div>

<!-- Header Ringkasan Eksekutif (Glassmorphic KPI Cards) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Item Produk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-accent p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Total Item Produk
                </span>
                <div class="kpi-icon-squircle icon-accent">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1">
                    {{ number_format($totalProducts) }} <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Item</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Total keseluruhan item terdaftar
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Nilai Inventaris -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-emerald p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Total Nilai Inventaris
                </span>
                <div class="kpi-icon-squircle icon-emerald">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" style="color: #15803D;">
                    Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Estimasi total nilai aset gudang
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Barang Masuk -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-accent p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Total Barang Masuk
                </span>
                <div class="kpi-icon-squircle icon-accent">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1">
                    <span style="color: #15803D;">+{{ number_format($grandTotalIn) }}</span> <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Unit</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Akumulasi penerimaan stok
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Barang Keluar -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="kpi-card theme-accent p-3 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.72rem; letter-spacing: 0.5px;">
                    Total Barang Keluar
                </span>
                <div class="kpi-icon-squircle icon-accent">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <div>
                <div class="kpi-value mb-1" style="color: #DC2626;">
                    -{{ number_format($grandTotalOut) }} <span class="fs-6 fw-normal" style="color: var(--colors-muted);">Unit</span>
                </div>
                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">
                    Akumulasi pengeluaran stok
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bagian 1: Laporan Agregasi Kategori -->
<div class="card glass-card mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Laporan Ringkasan Kategori</h6>
            <small style="color: var(--colors-muted);">Agregasi data produk berdasarkan masing-masing kategori</small>
        </div>
        <span class="badge badge-gradient-info font-monospace">{{ $categoryReports->count() }} Kategori</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="text-center">Total Produk</th>
                        <th class="text-center">Total Stok Fisik</th>
                        <th class="text-end">Rata-rata Harga</th>
                        <th class="text-end">Total Nilai Aset</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categoryReports as $cat)
                        <tr>
                            <td>
                                <a href="{{ route('categories.show', $cat->id) }}" class="fw-semibold text-white text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-gradient-accent font-monospace">{{ $cat->total_products }} item</span>
                            </td>
                            <td class="text-center fw-semibold text-white font-monospace">
                                {{ number_format($cat->total_stock) }} unit
                            </td>
                            <td class="text-end font-monospace" style="color: var(--colors-muted);">
                                Rp {{ number_format($cat->average_price, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold font-monospace" style="color: #15803D;">
                                Rp {{ number_format($cat->category_inventory_value, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bagian 2: Laporan Pergerakan Stok per Produk -->
<div class="card glass-card">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between gap-3">
            <div>
                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-arrow-left-right text-success me-2"></i>Laporan Rekapitulasi Mutasi Stok per Produk</h6>
                <small style="color: var(--colors-muted);">Perbandingan total unit masuk dan keluar untuk setiap produk</small>
            </div>

            <!-- Filter Kategori & Search Produk -->
            <form method="GET" action="{{ route('reports.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari SKU / Nama..." value="{{ $search }}" style="max-width: 180px;">
                <select name="category_id" class="form-select form-select-sm" style="max-width: 180px;">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ $categoryId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                @if($search || $categoryId)
                    <a href="{{ route('reports.index') }}" class="btn btn-glass-outline btn-sm" title="Reset"><i class="bi bi-x"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>SKU & Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center" style="color: #15803D;">Total Masuk</th>
                        <th class="text-center" style="color: #DC2626;">Total Keluar</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productReports as $pr)
                        <tr>
                            <td>
                                <div class="fw-semibold text-white">{{ $pr->name }}</div>
                                <code class="small font-monospace" style="color: var(--colors-muted);">{{ $pr->sku }}</code>
                            </td>
                            <td>
                                <span class="badge badge-gradient-accent font-monospace">{{ $pr->category->name ?? '-' }}</span>
                            </td>
                            <td class="text-end font-monospace text-white">
                                Rp {{ number_format($pr->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center fw-bold font-monospace" style="color: #15803D;">
                                +{{ number_format($pr->total_in) }}
                            </td>
                            <td class="text-center fw-bold font-monospace" style="color: #DC2626;">
                                -{{ number_format($pr->total_out) }}
                            </td>
                            <td class="text-center font-monospace">
                                <span class="fs-6 fw-bold text-white">{{ $pr->current_stock }}</span> unit
                            </td>
                            <td class="text-center">
                                @if ($pr->current_stock <= 0)
                                    <span class="badge badge-gradient-danger font-monospace">Habis</span>
                                @elseif ($pr->current_stock <= $pr->min_stock)
                                    <span class="badge badge-gradient-warning font-monospace">Rendah</span>
                                @else
                                    <span class="badge badge-gradient-success font-monospace">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data produk ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($productReports->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $productReports->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
