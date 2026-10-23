@extends('layouts.app')

@section('title', 'Transaksi Pembelian (Stock-In)')
@section('page_heading', 'Transaksi Pembelian (Stock-In)')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Daftar Pembelian (Stock-In)</h4>
            <p class="text-muted small mb-0">Riwayat barang masuk dari pemasok/supplier untuk menambah stok gudang.</p>
        </div>
        <div>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>+ Buat Pembelian Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('purchases.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1">Pencarian</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="No. Pembelian atau Nama Pemasok..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100">Filter</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
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
                            <td class="fw-medium text-dark">{{ $p->supplier->name ?? 'Pemasok Terhapus' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->user->name ?? '-' }}</span></td>
                            <td>{{ $p->items->count() }} macam item</td>
                            <td class="fw-bold text-dark">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td>
                                @if($p->status === 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('purchases.show', $p) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
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
            <div class="card-footer bg-white border-0 py-3">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
