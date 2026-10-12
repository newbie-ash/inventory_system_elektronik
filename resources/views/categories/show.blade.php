@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name)
@section('page_heading', 'Detail Kategori & Daftar Produk')

@section('content')
<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Informasi Kategori</h6>
            </div>
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark">{{ $category->name }}</h5>
                <p class="text-secondary small mb-3">{{ $category->description ?: 'Tidak ada deskripsi tambahan.' }}</p>

                <div class="p-3 bg-light rounded-3 mb-4">
                    <small class="text-muted d-block">Jumlah Produk Terdaftar</small>
                    <span class="fs-4 fw-bold text-primary">{{ $category->products->count() }}</span> Produk
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i> Ubah
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-light border">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">Daftar Produk di Kategori Ini</h6>
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase text-secondary small">
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($category->products as $product)
                                <tr>
                                    <td><code class="small text-muted">{{ $product->sku }}</code></td>
                                    <td>
                                        <a href="{{ route('products.show', $product) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada produk yang terdaftar di kategori ini.
                                    </td>
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
