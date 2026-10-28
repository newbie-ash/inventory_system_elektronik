@extends('layouts.app')

@section('title', 'Transaksi Penjualan (Stock-Out)')
@section('page_heading', 'Transaksi Penjualan (Stock-Out)')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-white">Daftar Penjualan (Stock-Out)</h4>
            <p class="text-muted small mb-0">Riwayat transaksi penjualan barang keluar dan pesanan pelanggan.</p>
        </div>
        <div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 font-monospace">
                <i class="bi bi-cart-plus"></i>
                <span>+ Input Penjualan Baru (Kasir)</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card glass-card mb-3 p-3">
        <form action="{{ route('sales.index') }}" method="GET" class="row g-2.5 align-items-center">
            <div class="col-12 col-md-12 col-lg-3">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                    <input type="text" name="search" class="form-control ps-5" placeholder="No. Penjualan / Pelanggan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <select name="shipping_status" class="form-select">
                    <option value="">Semua Pengiriman</option>
                    <option value="packing" {{ request('shipping_status') === 'packing' ? 'selected' : '' }}>Sedang Dikemas (Gudang)</option>
                    <option value="shipped" {{ request('shipping_status') === 'shipped' ? 'selected' : '' }}>Sedang Dikirim (Kurir)</option>
                    <option value="delivered" {{ request('shipping_status') === 'delivered' ? 'selected' : '' }}>Selesai / Diterima</option>
                    <option value="pending" {{ request('shipping_status') === 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
                </select>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <select name="payment_status" class="form-select">
                    <option value="">Semua Pembayaran</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <input type="date" name="date_from" class="form-control" title="Dari Tanggal" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <input type="date" name="date_to" class="form-control" title="Sampai Tanggal" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 col-lg-1 d-flex align-items-center gap-1.5">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center flex-grow-1" style="height: 42px;" title="Cari Transaksi">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->anyFilled(['search', 'status', 'payment_status', 'date_from', 'date_to']))
                    <a href="{{ route('sales.index') }}" class="btn btn-glass-outline d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; padding: 0;" title="Reset Filter">
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
                        <th class="ps-3">No. Penjualan</th>
                        <th>Waktu Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Kasir / Petugas</th>
                        <th>Rincian Item</th>
                        <th>Total Penjualan</th>
                        <th>Pembayaran</th>
                        <th>Pengiriman</th>
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
                            <td class="fw-medium text-white">{{ $s->buyer->name ?? 'Walk-In Customer' }}</td>
                            <td><span class="badge badge-gradient-neutral font-monospace">{{ $s->user->name ?? '-' }}</span></td>
                            <td>{{ $s->items->count() }} macam item</td>
                            <td class="fw-bold text-white">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
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
                                @if($s->status === 'cancelled')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Dibatalkan</span>
                                @elseif($s->shipping_status === 'shipped')
                                    <span class="badge badge-gradient-info font-monospace px-2 py-1">
                                        <i class="bi bi-truck me-1"></i> Dikirim
                                    </span>
                                @elseif($s->shipping_status === 'delivered')
                                    <span class="badge badge-gradient-success font-monospace px-2 py-1">
                                        <i class="bi bi-check2-circle me-1"></i> Selesai
                                    </span>
                                @elseif($s->shipping_status === 'packing')
                                    <span class="badge badge-gradient-accent font-monospace px-2 py-1">
                                        <i class="bi bi-box-seam me-1"></i> Dikemas
                                    </span>
                                @else
                                    <span class="badge badge-gradient-warning font-monospace px-2 py-1">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($s->status === 'completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end">
                                    @if($s->status !== 'cancelled')
                                        @if($s->shipping_status === 'packing')
                                            <form action="{{ route('sales.update-shipping-status', $s) }}" 
                                                  method="POST" 
                                                  class="d-inline-flex align-items-center m-0 me-2"
                                                  data-confirm="Pesanan akan diserahkan ke pihak ekspedisi/kurir. Status di akun pembeli otomatis berubah menjadi Sedang Dikirim."
                                                  data-confirm-title="Kirim ke Kurir Logistik?"
                                                  data-confirm-item="Pesanan #{{ $s->sale_number }}"
                                                  data-confirm-type="success"
                                                  data-confirm-icon="bi-truck"
                                                  data-confirm-btn="Ya, Kirim Pesanan">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="shipping_status" value="shipped">
                                                <button type="submit" class="btn btn-sm btn-outline-info font-monospace d-inline-flex align-items-center gap-1.5" style="height: 32px; border-radius: 8px; padding: 0 10px; font-size: 0.78rem;" title="Kirim ke Kurir">
                                                    <i class="bi bi-truck"></i>
                                                    <span>Kirim</span>
                                                </button>
                                            </form>
                                        @elseif($s->shipping_status === 'shipped')
                                            <form action="{{ route('sales.update-shipping-status', $s) }}" 
                                                  method="POST" 
                                                  class="d-inline-flex align-items-center m-0 me-2"
                                                  data-confirm="Tandai pesanan ini telah selesai dan barang telah diterima oleh pembeli."
                                                  data-confirm-title="Tandai Pesanan Selesai?"
                                                  data-confirm-item="Pesanan #{{ $s->sale_number }}"
                                                  data-confirm-type="success"
                                                  data-confirm-icon="bi-check2-circle"
                                                  data-confirm-btn="Ya, Tandai Selesai">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="shipping_status" value="delivered">
                                                <button type="submit" class="btn btn-sm btn-outline-success font-monospace d-inline-flex align-items-center gap-1.5" style="height: 32px; border-radius: 8px; padding: 0 10px; font-size: 0.78rem;" title="Tandai Selesai / Sampai">
                                                    <i class="bi bi-check2"></i>
                                                    <span>Selesai</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                    <a href="{{ route('sales.show', $s) }}" class="btn-action btn-action-view" title="Lihat Invoice & Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
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
            <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
                {{ $sales->links('vendor.pagination.buyer-slide') }}
            </div>
        @endif
    </div>
</div>
@endsection
