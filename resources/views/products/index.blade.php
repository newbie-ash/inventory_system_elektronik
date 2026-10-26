@extends('layouts.app')

@section('title', 'Katalog Produk')
@section('page_heading', 'Manajemen & Katalog Produk')

@section('content')
<div class="card glass-card mb-3 p-3">
    <!-- Search, Filter & Sort Form -->
    <form method="GET" action="{{ route('products.index') }}" class="row g-2.5 align-items-center">
        <!-- Search -->
        <div class="col-12 col-md-12 col-lg-4">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       placeholder="Cari nama atau SKU produk..." 
                       value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <select name="stock_status" class="form-select">
                <option value="">Semua Status</option>
                <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>Stok Habis</option>
            </select>
        </div>

        <!-- Sorting Dropdown -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <select name="sort" class="form-select">
                <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Waktu Dibuat</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama Produk</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Harga Produk</option>
                <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Jumlah Stok</option>
            </select>
        </div>

        <!-- Sort Direction & Action Buttons -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 d-flex align-items-center gap-2">
            <select name="direction" class="form-select text-center flex-grow-1" title="Arah Pengurutan">
                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>&uarr; Asc</option>
                <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>&darr; Desc</option>
            </select>
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; padding: 0;" title="Terapkan Filter">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if(request()->anyFilled(['search', 'category_id', 'stock_status', 'sort', 'direction']))
                <a href="{{ route('products.index') }}" class="btn btn-glass-outline d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; padding: 0;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Produk Inventaris</h6>
            <span class="badge badge-gradient-accent font-monospace">{{ $products->total() }} Produk Ditemukan</span>
        </div>

        @if(Auth::user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 font-monospace">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Produk Baru</span>
            </a>
        @endif
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th style="min-width: 250px;">SKU & Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td class="text-center font-monospace small" style="color: var(--colors-muted);">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 14px;">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="object-fit-cover flex-shrink-0" style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid rgba(166, 156, 172, 0.25); background: var(--colors-card-alt);">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; border-radius: 8px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(166, 156, 172, 0.2); color: var(--colors-muted);">
                                            <i class="bi bi-box-seam fs-5"></i>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column justify-content-center" style="min-width: 0;">
                                        <span class="fw-semibold text-white" style="line-height: 1.35;">{{ $product->name }}</span>
                                        <span class="badge bg-transparent border font-monospace text-muted align-self-start mt-1" style="border-color: rgba(166, 156, 172, 0.25) !important; font-size: 0.72rem;">
                                            {{ $product->sku }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gradient-info font-monospace">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @forelse ($product->suppliers as $sup)
                                    <span class="badge badge-gradient-accent font-monospace me-1 mb-1">{{ $sup->name }}</span>
                                @empty
                                    <span class="small" style="color: var(--colors-muted);">-</span>
                                @endforelse
                            </td>
                            <td class="text-end font-monospace text-white fw-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="fw-bold font-monospace text-white fs-6">{{ $product->stock }}</span>
                                <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">Min: {{ $product->min_stock }}</div>
                            </td>
                            <td class="text-center">
                                @if ($product->stock <= 0)
                                    <span class="badge badge-gradient-danger font-monospace">Habis</span>
                                @elseif ($product->isLowStock())
                                    <span class="badge badge-gradient-warning font-monospace">Rendah</span>
                                @else
                                    <span class="badge badge-gradient-success font-monospace">Aman</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn-action btn-action-view" title="Detail Produk">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn-action btn-action-move" title="Catat Transaksi Stok">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('products.edit', $product) }}" class="btn-action btn-action-edit" title="Ubah Produk">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn-action btn-action-delete" 
                                                title="Hapus Produk"
                                                onclick="confirmDeleteProduct('{{ route('products.destroy', $product) }}', '{{ addslashes($product->name) }}', '{{ $product->sku }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: var(--colors-muted);">
                                <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--colors-muted);"></i>
                                Tidak ada produk yang sesuai dengan kriteria pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination (Centered Slide Style) -->
    @if ($products->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $products->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>

@include('components.delete-product-modal')
@endsection
