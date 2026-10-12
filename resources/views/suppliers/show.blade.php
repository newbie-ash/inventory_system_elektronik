@extends('layouts.app')

@section('title', 'Supplier: ' . $supplier->name)
@section('page_heading', 'Detail Supplier & Produk Yang Disuplai')

@section('content')
<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Profil Supplier</h6>
            </div>
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark">{{ $supplier->name }}</h5>
                <div class="my-3">
                    <div class="small mb-2"><i class="bi bi-envelope text-primary me-2"></i> {{ $supplier->email ?: 'Tidak ada email' }}</div>
                    <div class="small mb-2"><i class="bi bi-telephone text-primary me-2"></i> {{ $supplier->phone ?: 'Tidak ada telepon' }}</div>
                    <div class="small text-muted"><i class="bi bi-geo-alt text-primary me-2"></i> {{ $supplier->address ?: 'Tidak ada alamat' }}</div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4">
                    <small class="text-muted d-block">Total Produk Tersedia</small>
                    <span class="fs-4 fw-bold text-primary">{{ $supplier->products->count() }}</span> Produk
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i> Ubah
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light border">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">Katalog Produk Dari Supplier Ini</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase text-secondary small">
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($supplier->products as $product)
                                <tr>
                                    <td><code class="small text-muted">{{ $product->sku }}</code></td>
                                    <td>
                                        <a href="{{ route('products.show', $product) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">{{ $product->category->name ?? '-' }}</span>
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
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada produk yang dikaitkan dengan supplier ini.
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
