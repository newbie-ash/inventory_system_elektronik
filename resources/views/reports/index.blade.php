@extends('layouts.app')

@section('title', 'Laporan & Agregasi Inventaris')
@section('page_heading', 'Laporan Inventaris & Analisis Agregat')

@section('content')
<!-- Header Ringkasan Eksekutif -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-3">
                <span class="text-muted small text-uppercase fw-semibold">Total Item Produk</span>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalProducts) }}</h3>
                <small class="text-secondary">&Sigma; COUNT(products.id)</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-3">
                <span class="text-muted small text-uppercase fw-semibold">Total Nilai Inventaris</span>
                <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h4>
                <small class="text-secondary">&Sigma; SUM(stock &times; price)</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-3">
                <span class="text-muted small text-uppercase fw-semibold">Total Barang Masuk (In)</span>
                <h3 class="fw-bold text-primary mb-0">+{{ number_format($grandTotalIn) }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                <small class="text-secondary">Akumulasi Masuk</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-3">
                <span class="text-muted small text-uppercase fw-semibold">Total Barang Keluar (Out)</span>
                <h3 class="fw-bold text-danger mb-0">-{{ number_format($grandTotalOut) }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                <small class="text-secondary">Akumulasi Keluar</small>
            </div>
        </div>
    </div>
</div>

<!-- Bagian 1: Laporan Agregasi Kategori (P4 Slide 14) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>1. Laporan Ringkasan Kategori (Aggregate GROUP BY)</h6>
            <small class="text-muted">Implementasi Query P4 Slide 14: <code>COUNT(p.id)</code>, <code>SUM(p.stock)</code>, <code>AVG(p.price)</code></small>
        </div>
        <span class="badge bg-primary-subtle text-primary">{{ $categoryReports->count() }} Kategori</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="text-center">Total Produk (COUNT)</th>
                        <th class="text-center">Total Stok Fisik (SUM)</th>
                        <th class="text-end">Rata-rata Harga (AVG)</th>
                        <th class="text-end">Total Nilai Aset Kategori</th>
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

<!-- Bagian 2: Laporan Pergerakan Stok per Produk (P4 Slide 28) -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between gap-3">
            <div>
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-arrow-left-right text-success me-2"></i>2. Laporan Rekapitulasi Mutasi Stok per Produk</h6>
                <small class="text-muted">Implementasi Query P4 Slide 28: <code>SUM(CASE WHEN sm.type='in' THEN quantity...)</code></small>
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
                        <th class="text-center text-success">Total Masuk (IN)</th>
                        <th class="text-center text-danger">Total Keluar (OUT)</th>
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
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
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
