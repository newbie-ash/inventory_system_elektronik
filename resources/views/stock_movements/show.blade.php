@extends('layouts.app')

@section('title', 'Detail Transaksi Stok ID ' . $stockMovement->id)
@section('page_heading', 'Bukti Transaksi Pergerakan Stok')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Bukti Audit Mutasi Stok ID {{ str_pad($stockMovement->id, 5, '0', STR_PAD_LEFT) }}</h6>
                </div>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <div class="text-center p-4 rounded-3 mb-4 {{ $stockMovement->type === 'in' ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                    <span class="badge {{ $stockMovement->type === 'in' ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2 rounded-pill mb-2">
                        {{ $stockMovement->type === 'in' ? 'BARANG MASUK (IN)' : 'BARANG KELUAR (OUT)' }}
                    </span>
                    <h1 class="fw-bold mb-0 {{ $stockMovement->type === 'in' ? 'text-success' : 'text-danger' }}">
                        {{ $stockMovement->type === 'in' ? '+' : '-' }}{{ $stockMovement->quantity }} <span class="fs-4 fw-normal text-muted">unit</span>
                    </h1>
                    <small class="text-muted">Tanggal: {{ $stockMovement->created_at->translatedFormat('d F Y - H:i:s') }}</small>
                </div>

                <div class="list-group list-group-flush border rounded mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Nama Produk</span>
                        <a href="{{ route('products.show', $stockMovement->product) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $stockMovement->product->name }}
                        </a>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">SKU Produk</span>
                        <span class="font-monospace fw-bold">{{ $stockMovement->product->sku }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Kategori</span>
                        <span>{{ $stockMovement->product->category->name ?? '-' }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Petugas Pencatat</span>
                        <span class="fw-semibold">{{ $stockMovement->user->name ?? '-' }} ({{ ucfirst($stockMovement->user->role ?? '') }})</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">Sisa Stok Fisik Produk Saat Ini</span>
                        <span class="badge bg-primary px-3 py-2 fs-6">{{ $stockMovement->product->stock }} unit</span>
                    </div>
                    <div class="list-group-item py-3">
                        <span class="text-muted small d-block mb-1">Catatan / Referensi:</span>
                        <p class="mb-0 text-dark">{{ $stockMovement->notes ?: 'Tidak ada catatan tambahan.' }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
