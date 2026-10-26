@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)
@section('page_heading', 'Detail Spesifikasi Produk')

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Informasi Utama Produk -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                </span>
                <span class="font-monospace fw-bold text-muted">{{ $product->sku }}</span>
            </div>
            <div class="card-body p-4">
                @if($product->image_url)
                    <div class="mb-3 text-center rounded border bg-light overflow-hidden d-flex align-items-center justify-content-center" style="max-height: 240px; min-height: 160px;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid object-fit-contain" style="max-height: 240px;">
                    </div>
                @endif
                <h4 class="fw-bold text-dark mb-2">{{ $product->name }}</h4>
                <p class="text-secondary small mb-4">{{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk ini.' }}</p>

                <div class="row g-3 py-3 border-top border-bottom mb-4">
                    <div class="col-6">
                        <small class="text-muted d-block">Harga Satuan</small>
                        <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Total Nilai Stok</small>
                        <h5 class="fw-bold text-success mb-0">Rp {{ number_format($product->inventory_value, 0, ',', '.') }}</h5>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Sisa Stok Fisik</small>
                        <h4 class="fw-bold mb-0 {{ $product->isLowStock() ? 'text-danger' : 'text-primary' }}">
                            {{ $product->stock }} <span class="fs-6 fw-normal text-muted">unit</span>
                        </h4>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Batas Min. Stok</small>
                        <h5 class="fw-semibold text-muted mb-0">{{ $product->min_stock }} unit</h5>
                    </div>
                </div>

                <!-- Supplier Relasi Many-to-Many -->
                <div class="mb-4">
                    <label class="fw-semibold small text-muted d-block mb-2">Pemasok / Supplier Terhubung:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse ($product->suppliers as $supplier)
                            <a href="{{ route('suppliers.show', $supplier) }}" class="badge bg-light text-dark border p-2 text-decoration-none">
                                <i class="bi bi-truck me-1"></i> {{ $supplier->name }}
                            </a>
                        @empty
                            <span class="text-muted small">Belum terhubung ke supplier mana pun.</span>
                        @endforelse
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-success fw-semibold">
                        <i class="bi bi-arrow-left-right me-1"></i> Catat Transaksi Masuk / Keluar
                    </a>

                    @if(Auth::user()->isAdmin())
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary flex-grow-1">
                                <i class="bi bi-pencil me-1"></i> Ubah
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="confirmDeleteProduct('{{ route('products.destroy', $product) }}', '{{ addslashes($product->name) }}', '{{ $product->sku }}')">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Histori Transaksi Produk Ini -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <h6 class="mb-0 fw-bold">Riwayat Transaksi Produk Ini</h6>
                </div>
                <a href="{{ route('stock-movements.index', ['product_id' => $product->id]) }}" class="small text-primary text-decoration-none fw-semibold">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr>
                                <th>Jenis</th>
                                <th class="text-center">Kuantitas</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->stockMovements as $movement)
                                <tr>
                                    <td>
                                        @if ($movement->type === 'in')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-arrow-down-left"></i> Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-arrow-up-right"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold {{ $movement->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                    </td>
                                    <td class="small">{{ $movement->user->name ?? '-' }}</td>
                                    <td class="small text-muted text-truncate" style="max-width: 200px;">
                                        {{ $movement->notes ?: '-' }}
                                    </td>
                                    <td class="small text-muted">{{ $movement->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat transaksi untuk produk ini.
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

@include('components.delete-product-modal')
@endsection
