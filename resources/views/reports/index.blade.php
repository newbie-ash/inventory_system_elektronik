@extends('layouts.app')

@section('title', 'Laporan & Analisis')
@section('page_heading', 'Laporan Inventaris & Analisis')

@section('content')
<!-- Header Ringkasan Eksekutif (Glassmorphic KPI Cards) -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Item Produk</span>
                    <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-boxes fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ number_format($totalProducts) }}</h3>
                <small class="text-secondary">Total keseluruhan item terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Nilai Inventaris</span>
                    <div class="bg-success-subtle text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-success mb-1">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h4>
                <small class="text-secondary">Estimasi total nilai aset gudang</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Barang Masuk</span>
                    <div class="bg-info-subtle text-info rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-down-left-circle fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-primary mb-1">+{{ number_format($grandTotalIn) }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                <small class="text-secondary">Akumulasi penerimaan stok</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Barang Keluar</span>
                    <div class="bg-warning-subtle text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-arrow-up-right-circle fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-1">-{{ number_format($grandTotalOut) }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                <small class="text-secondary">Akumulasi pengeluaran stok</small>
            </div>
        </div>
    </div>
</div>

<!-- Bagian 1: Laporan Agregasi Kategori -->
<div class="card mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Laporan Ringkasan Kategori</h6>
            <small class="text-muted">Agregasi data produk berdasarkan masing-masing kategori</small>
        </div>
        <span class="badge bg-primary-subtle text-primary">{{ $categoryReports->count() }} Kategori</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
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
                                <a href="{{ route('categories.show', $cat->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $cat->total_products }} item</span>
                            </td>
                            <td class="text-center fw-semibold">
                                {{ number_format($cat->total_stock) }} unit
                            </td>
                            <td class="text-end text-muted">
                                Rp {{ number_format($cat->average_price, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold text-success">
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
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between gap-3">
            <div>
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-arrow-left-right text-success me-2"></i>Laporan Rekapitulasi Mutasi Stok per Produk</h6>
                <small class="text-muted">Perbandingan total unit masuk dan keluar untuk setiap produk</small>
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
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset"><i class="bi bi-x"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th>SKU & Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center text-success">Total Masuk</th>
                        <th class="text-center text-danger">Total Keluar</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productReports as $pr)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $pr->name }}</div>
                                <code class="small text-muted font-monospace">{{ $pr->sku }}</code>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $pr->category->name ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($pr->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center fw-bold text-success">
                                +{{ number_format($pr->total_in) }}
                            </td>
                            <td class="text-center fw-bold text-danger">
                                -{{ number_format($pr->total_out) }}
                            </td>
                            <td class="text-center">
                                <span class="fs-6 fw-bold">{{ $pr->current_stock }}</span> unit
                            </td>
                            <td class="text-center">
                                @if ($pr->current_stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif ($pr->current_stock <= $pr->min_stock)
                                    <span class="badge bg-warning text-dark">Rendah</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
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
        <div class="card-footer d-flex align-items-center justify-content-between py-3">
            <div class="small text-muted">
                Menampilkan <strong>{{ $productReports->firstItem() }}</strong> sampai <strong>{{ $productReports->lastItem() }}</strong> dari <strong>{{ $productReports->total() }}</strong> produk
            </div>
            <div>
                {{ $productReports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
