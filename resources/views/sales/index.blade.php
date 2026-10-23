@extends('layouts.app')

@section('title', 'Transaksi Penjualan (Stock-Out)')
@section('page_heading', 'Transaksi Penjualan (Stock-Out)')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Daftar Penjualan (Stock-Out)</h4>
            <p class="text-muted small mb-0">Riwayat transaksi penjualan barang keluar dan pesanan pelanggan.</p>
        </div>
        <div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-cart-plus"></i>
                <span>+ Input Penjualan Baru (Kasir)</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('sales.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1">Pencarian</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="No. Penjualan atau Nama Pelanggan..." value="{{ request('search') }}">
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
                    <label class="form-label small text-muted mb-1">Status Pembayaran</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">Semua Pembayaran</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
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
                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-dark w-100">Cari</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                        <th class="ps-3">No. Penjualan</th>
                        <th>Waktu Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Kasir / Petugas</th>
                        <th>Rincian Item</th>
                        <th>Total Penjualan</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($sales as $s)
                        <tr>
                            <td class="ps-3 font-monospace fw-semibold text-primary">
                                <a href="{{ route('sales.show', $s) }}" class="text-decoration-none">
                                    {{ $s->sale_number }}
                                </a>
                            </td>
                            <td>{{ $s->sold_at ? $s->sold_at->format('d M Y H:i') : '-' }}</td>
                            <td class="fw-medium text-dark">{{ $s->buyer->name ?? 'Walk-In Customer' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $s->user->name ?? '-' }}</span></td>
                            <td>{{ $s->items->count() }} macam item</td>
                            <td class="fw-bold text-dark">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                            <td>
                                @if($s->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Lunas</span>
                                @elseif($s->payment_status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Pending</span>
                                @elseif($s->payment_status === 'refunded')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Refunded</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Gagal</span>
                                @endif
                                <span class="d-block text-muted text-uppercase mt-0.5" style="font-size: 0.68rem;">{{ $s->payment_method }}</span>
                            </td>
                            <td>
                                @if($s->status === 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('sales.show', $s) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Lihat Invoice">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada transaksi penjualan tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
