@extends('layouts.app')

@section('title', 'Daftar Produk Stok Kritis')
@section('page_heading', 'Monitoring Stok Kritis (Batas Minimum)')

@section('content')
<!-- Banner Peringatan -->
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between p-3 mb-4" role="alert">
    <div class="d-flex align-items-center">
        <div class="bg-warning text-dark rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold text-dark">Perhatian: Terdapat {{ $totalLowStock }} Produk Memerlukan Restock Segera!</h6>
            <small class="text-secondary">Produk di bawah ini memiliki kuantitas stok yang berada pada atau di bawah batas minimum stok yang telah ditentukan.</small>
        </div>
    </div>
    <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn btn-dark btn-sm fw-semibold shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Form Restock Barang Masuk
    </a>
</div>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('products.low-stock') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama produk kritis atau SKU..." 
                           value="{{ $search }}">
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if($search || $categoryId)
                    <a href="{{ route('products.low-stock') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Lengkap Full Width -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold text-dark">Daftar Produk Kritis & Rekomendasi Pemesanan</h6>
            <span class="badge bg-danger-subtle text-danger">{{ $products->total() }} Item Kritis</span>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-boxes me-1"></i> Buka Seluruh Katalog Produk
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th>SKU & Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Batas Minimum</th>
                        <th>Supplier Terdaftar</th>
                        <th class="text-end">Harga Estimasi</th>
                        <th class="text-end" style="min-width: 140px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <code class="small text-muted font-monospace">{{ $product->sku }}</code>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($product->stock <= 0)
                                    <span class="badge bg-danger fs-6 px-3 py-1">0 unit (HABIS)</span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-1">{{ $product->stock }} unit</span>
                                @endif
                            </td>
                            <td class="text-center text-muted fw-semibold">
                                {{ $product->min_stock }} unit
                            </td>
                            <td>
                                @forelse ($product->suppliers as $sup)
                                    <div class="small">
                                        <i class="bi bi-truck text-muted me-1"></i>
                                        <a href="{{ route('suppliers.show', $sup) }}" class="text-decoration-none text-dark fw-medium">
                                            {{ $sup->name }}
                                        </a>
                                        @if($sup->phone)
                                            <span class="text-muted" style="font-size: 0.75rem;">({{ $sup->phone }})</span>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-muted small italic">Belum ada supplier terhubung</span>
                                @endforelse
                            </td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}" 
                                   class="btn btn-success btn-sm fw-semibold shadow-sm text-nowrap">
                                    <i class="bi bi-box-arrow-in-down me-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                                <strong>Level Stok Aman!</strong>
                                <p class="mb-0 text-secondary small">Semua produk saat ini memiliki kuantitas stok di atas batas minimum.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($products->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
            <div class="small text-muted">
                Menampilkan <strong>{{ $products->firstItem() }}</strong> sampai <strong>{{ $products->lastItem() }}</strong> dari <strong>{{ $products->total() }}</strong> produk kritis
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
