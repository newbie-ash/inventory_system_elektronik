@extends('layouts.app')

@section('title', 'Transaksi Stok')
@section('page_heading', 'Riwayat & Log Transaksi Stok')

@section('content')
<div class="card glass-card mb-3 p-3">
    <!-- Filter Form -->
    <form method="GET" action="{{ route('stock-movements.index') }}" class="row g-2.5 align-items-center">
        <div class="col-12 col-md-4">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       placeholder="Cari SKU, nama produk, catatan..." 
                       value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter Jenis Transaksi -->
        <div class="col-12 col-sm-6 col-md-3">
            <select name="type" class="form-select">
                <option value="">Semua Jenis Transaksi</option>
                <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Barang Masuk (In)</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Barang Keluar (Out)</option>
            </select>
        </div>

        <!-- Filter Produk Tertentu -->
        <div class="col-12 col-sm-6 col-md-3">
            <select name="product_id" class="form-select">
                <option value="">Semua Produk</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} ({{ $p->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1 font-monospace">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'type', 'product_id']))
                <a href="{{ route('stock-movements.index') }}" class="btn btn-glass-outline font-monospace" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Transaksi Stok Inventaris</h6>
            <span class="badge badge-gradient-accent font-monospace">{{ $movements->total() }} Catatan</span>
        </div>
        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 font-monospace">
            <i class="bi bi-plus-lg"></i>
            <span>Catat Transaksi Baru</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th>Waktu</th>
                        <th>Jenis</th>
                        <th>Produk & SKU</th>
                        <th>Kategori</th>
                        <th class="text-center">Kuantitas</th>
                        <th>Petugas</th>
                        <th>Keterangan / Catatan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $index => $item)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $movements->firstItem() + $index }}
                            </td>
                            <td class="small text-muted">
                                <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                <div class="text-secondary" style="font-size: 0.75rem;">{{ $item->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if ($item->type === 'in')
                                    <span class="badge badge-gradient-success font-monospace">
                                        <i class="bi bi-arrow-down-left"></i> Masuk (In)
                                    </span>
                                @else
                                    <span class="badge badge-gradient-danger font-monospace">
                                        <i class="bi bi-arrow-up-right"></i> Keluar (Out)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $item->product) }}" class="fw-bold text-white text-decoration-none">
                                    {{ $item->product->name ?? 'Produk Dihapus' }}
                                </a>
                                <div class="small text-muted font-monospace">{{ $item->product->sku ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-gradient-info font-monospace">
                                    {{ $item->product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center fw-bold fs-6 font-monospace {{ $item->type === 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $item->type === 'in' ? '+' : '-' }}{{ $item->quantity }}
                            </td>
                            <td>
                                <div class="small fw-semibold text-white">{{ $item->user->name ?? 'Sistem' }}</div>
                                <div class="small text-muted" style="font-size: 0.72rem;">{{ ucfirst($item->user->role ?? '') }}</div>
                            </td>
                            <td class="small text-muted">
                                {{ $item->notes ?: '-' }}
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('stock-movements.show', $item) }}" class="btn-action btn-action-view" title="Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada riwayat transaksi stok yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($movements->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $movements->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
