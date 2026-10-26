@extends('layouts.app')

@section('title', 'Transaksi Pembelian (Stock-In)')
@section('page_heading', 'Transaksi Pembelian (Stock-In)')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-white">Daftar Pembelian (Stock-In)</h4>
            <p class="text-muted small mb-0">Riwayat barang masuk dari pemasok/supplier untuk menambah stok gudang.</p>
        </div>
        <div>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 font-monospace">
                <i class="bi bi-plus-lg"></i>
                <span>+ Buat Pembelian Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card glass-card mb-3 p-3">
        <form action="{{ route('purchases.index') }}" method="GET" class="row g-2.5 align-items-center">
            <div class="col-12 col-md-12 col-lg-3">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                    <input type="text" name="search" class="form-control ps-5" placeholder="No. Pembelian / Pemasok..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <input type="date" name="date_from" class="form-control" title="Dari Tanggal" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <input type="date" name="date_to" class="form-control" title="Sampai Tanggal" value="{{ request('date_to') }}">
            </div>
            <div class="col-6 col-md-12 col-lg-1 d-flex align-items-center gap-1.5">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center flex-grow-1" style="height: 42px;" title="Cari Transaksi">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('purchases.index') }}" class="btn btn-glass-outline d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; padding: 0;" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card glass-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pemasok (Supplier)</th>
                        <th>Pencatat</th>
                        <th>Rincian Item</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($purchases as $p)
                        <tr>
                            <td class="ps-3 font-monospace fw-semibold text-primary">
                                <a href="{{ route('purchases.show', $p) }}" class="text-decoration-none">
                                    {{ $p->purchase_number }}
                                </a>
                            </td>
                            <td>{{ $p->purchased_at ? $p->purchased_at->format('d M Y H:i') : '-' }}</td>
                            <td class="fw-medium text-white">{{ $p->supplier->name ?? 'Pemasok Terhapus' }}</td>
                            <td><span class="badge badge-gradient-neutral font-monospace">{{ $p->user->name ?? '-' }}</span></td>
                            <td>{{ $p->items->count() }} macam item</td>
                            <td class="fw-bold text-white">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td>
                                @if($p->status === 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('purchases.show', $p) }}" class="btn-action btn-action-view" title="Lihat Detail Transaksi">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada transaksi pembelian tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchases->hasPages())
            <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
                {{ $purchases->links('vendor.pagination.buyer-slide') }}
            </div>
        @endif
    </div>
</div>
@endsection
