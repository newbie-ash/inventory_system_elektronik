@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - Ctrl+R')
@section('page_heading', 'Riwayat Aktivitas')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="mb-1 text-white" style="letter-spacing: -0.5px;">Riwayat Aktivitas</h4>
            <p class="text-secondary small font-monospace mb-0">Daftar transaksi pesanan barang dan status verifikasi Midtrans Snap Anda.</p>
        </div>
        <div>
            <a href="{{ route('customer.catalog') }}" class="btn btn-primary btn-sm font-monospace text-uppercase">
                <i class="bi bi-cart3 me-1"></i> Buka Katalog Belanja &rarr;
            </a>
        </div>
    </div>

    <div class="card p-0" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline);">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">No. Invoice</th>
                        <th>Waktu Pemesanan</th>
                        <th>Jumlah Barang</th>
                        <th>Total Tagihan</th>
                        <th>Status Pembayaran</th>
                        <th>Status Transaksi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3 font-monospace fw-semibold text-white">
                                <a href="{{ route('customer.orders.show', $order) }}" class="text-white text-decoration-none">
                                    {{ $order->sale_number }}
                                </a>
                            </td>
                            <td class="text-secondary small font-monospace">
                                {{ $order->sold_at ? $order->sold_at->format('d M Y - H:i') : '-' }}
                            </td>
                            <td class="text-white small font-monospace">
                                {{ $order->items->sum('quantity') }} unit ({{ $order->items->count() }} item)
                            </td>
                            <td class="fw-bold text-white font-monospace">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 font-monospace">LUNAS</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 font-monospace">MENUNGGU BAYAR</span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-25 font-monospace">REFUNDED</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 font-monospace">GAGAL</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge bg-white bg-opacity-10 text-white font-monospace">DIPROSES</span>
                                @else
                                    <span class="badge bg-secondary font-monospace">DIBATALKAN</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-glass-outline py-1 px-2.5 font-monospace" style="font-size: 0.75rem;">
                                    Rincian & Bayar <i class="bi bi-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary font-monospace">
                                <i class="bi bi-bag-x fs-1 text-muted d-block mb-2"></i>
                                Anda belum memiliki riwayat pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="p-3 border-top d-flex justify-content-center" style="border-color: var(--colors-hairline) !important;">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
