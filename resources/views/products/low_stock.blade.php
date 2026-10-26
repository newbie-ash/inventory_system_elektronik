@extends('layouts.app')

@section('title', 'Daftar Produk Stok Kritis')
@section('page_heading', 'Monitoring Stok Kritis (Batas Minimum)')

@section('content')
<!-- Banner Peringatan -->
<div class="glass-card mb-3 p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"
     style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.18) 0%, rgba(71, 73, 115, 0.3) 100%) !important; border: 1px solid rgba(239, 68, 68, 0.4) !important;">
    <div class="d-flex align-items-center gap-3">
        <div class="kpi-icon-squircle" style="background: rgba(239, 68, 68, 0.25); border: 1px solid rgba(239, 68, 68, 0.45); color: #F87171; width: 44px; height: 44px;">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold text-white">Perhatian: Terdapat {{ $totalLowStock }} Produk Memerlukan Restock Segera!</h6>
            <small style="color: var(--colors-muted);">Kuantitas stok berada pada atau di bawah batas minimum yang ditentukan.</small>
        </div>
    </div>
    <a href="{{ route('stock-movements.create', ['type' => 'in']) }}" class="btn btn-primary btn-sm font-monospace text-nowrap">
        <i class="bi bi-plus-circle me-1"></i> Form Restock Barang Masuk
    </a>
</div>

<!-- Filter & Pencarian -->
<div class="card glass-card mb-3 p-3">
    <form method="GET" action="{{ route('products.low-stock') }}" class="row g-2.5 align-items-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       placeholder="Cari nama produk kritis atau SKU..." 
                       value="{{ $search }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-2 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-3">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if($search || $categoryId)
                <a href="{{ route('products.low-stock') }}" class="btn btn-glass-outline" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Lengkap Full Width -->
<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Produk Kritis & Rekomendasi Pemesanan</h6>
            <span class="badge badge-gradient-danger font-monospace">{{ $products->total() }} Item Kritis</span>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-glass-outline btn-sm font-monospace">
            <i class="bi bi-boxes me-1"></i> Buka Seluruh Katalog Produk
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
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
                            <td class="text-center font-monospace small" style="color: var(--colors-muted);">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-semibold text-white">{{ $product->name }}</div>
                                <code class="small font-monospace" style="color: var(--colors-muted);">{{ $product->sku }}</code>
                            </td>
                            <td>
                                <span class="badge badge-gradient-info font-monospace">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($product->stock <= 0)
                                    <span class="badge badge-gradient-danger font-monospace">0 unit (HABIS)</span>
                                @else
                                    <span class="badge badge-gradient-warning font-monospace">{{ $product->stock }} unit</span>
                                @endif
                            </td>
                            <td class="text-center font-monospace fw-semibold" style="color: var(--colors-muted);">
                                {{ $product->min_stock }} unit
                            </td>
                            <td>
                                @forelse ($product->suppliers as $sup)
                                    <div class="small">
                                        <i class="bi bi-truck me-1" style="color: var(--colors-muted);"></i>
                                        <a href="{{ route('suppliers.show', $sup) }}" class="text-decoration-none text-white fw-medium">
                                            {{ $sup->name }}
                                        </a>
                                        @if($sup->phone)
                                            <span style="color: var(--colors-muted); font-size: 0.75rem;">({{ $sup->phone }})</span>
                                        @endif
                                    </div>
                                @empty
                                    <span class="small" style="color: var(--colors-muted);">Belum ada supplier</span>
                                @endforelse
                            </td>
                            <td class="text-end font-monospace text-white fw-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}" 
                                   class="btn-action btn-action-move px-2.5 w-auto" style="height: 32px; border-radius: 8px;">
                                    <i class="bi bi-box-arrow-in-down me-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: var(--colors-muted);">
                                <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                                <strong class="text-white">Level Stok Aman!</strong>
                                <p class="mb-0 small" style="color: var(--colors-muted);">Semua produk saat ini memiliki kuantitas stok di atas batas minimum.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($products->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $products->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
