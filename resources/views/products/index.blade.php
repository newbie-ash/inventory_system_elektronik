@extends('layouts.app')

@section('title', 'Katalog Produk')
@section('page_heading', 'Manajemen & Katalog Produk')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <!-- Search, Filter & Sort Form -->
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama produk atau SKU..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="col-12 col-sm-6 col-md-3">
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
            <div class="col-12 col-sm-6 col-md-2">
                <select name="stock_status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>Stok Habis</option>
                </select>
            </div>

            <!-- Sorting Dropdown -->
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="form-select">
                    <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Waktu Dibuat</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama Produk</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Harga Produk</option>
                    <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Jumlah Stok</option>
                </select>
            </div>

            <!-- Sort Direction & Submit -->
            <div class="col-12 col-sm-6 col-md-1 d-flex gap-1">
                <select name="direction" class="form-select px-1 text-center" title="Arah Pengurutan">
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>&uarr; Asc</option>
                    <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>&darr; Desc</option>
                </select>
                <button type="submit" class="btn btn-primary" title="Terapkan Filter">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'stock_status', 'sort', 'direction']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold">Daftar Produk Inventaris</h6>
            <span class="badge bg-secondary-subtle text-secondary">{{ $products->total() }} Produk Ditemukan</span>
        </div>

        @if(Auth::user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
            </a>
        @endif
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>SKU & Nama Produk</th>
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
                            <td class="text-center text-muted small">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $product->name }}</span>
                                    <span class="badge bg-light text-secondary border font-monospace align-self-start" style="font-size: 0.75rem;">
                                        {{ $product->sku }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @forelse ($product->suppliers as $sup)
                                    <span class="badge bg-light text-dark border me-1 mb-1 small">{{ $sup->name }}</span>
                                @empty
                                    <span class="text-muted small">-</span>
                                @endforelse
                            </td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-6">{{ $product->stock }}</span>
                                <div class="small text-muted" style="font-size: 0.75rem;">Min: {{ $product->min_stock }}</div>
                            </td>
                            <td class="text-center">
                                @if ($product->stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif ($product->isLowStock())
                                    <span class="badge bg-warning text-dark">Rendah</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="Detail Produk">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-outline-success" title="Catat Transaksi Stok">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Ubah Produk">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada produk yang sesuai dengan kriteria pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination (Maintains Query String) -->
    @if ($products->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
            <div class="small text-muted">
                Menampilkan data <strong>{{ $products->firstItem() }}</strong> sampai <strong>{{ $products->lastItem() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
