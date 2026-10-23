@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $sale->sale_number)
@section('page_heading', 'Detail Pesanan Pelanggan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="text-decoration-none small text-secondary mb-2 d-inline-block font-monospace">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Pesanan
            </a>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0 text-white font-monospace">{{ $sale->sale_number }}</h4>
                @if($sale->payment_status === 'paid')
                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 font-monospace">LUNAS (PAID)</span>
                @elseif($sale->payment_status === 'pending')
                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 font-monospace">MENUNGGU BAYAR</span>
                @elseif($sale->payment_status === 'refunded')
                    <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-25 font-monospace">REFUNDED</span>
                @else
                    <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 font-monospace">GAGAL</span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-glass-outline btn-sm font-monospace">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </button>
        </div>
    </div>

    <!-- Payment Action Banner (Glassmorphism) -->
    @if($sale->payment_status === 'pending')
        <div class="glass-card mb-4 p-4" style="border-left: 4px solid var(--rm-red); background: rgba(255, 0, 60, 0.06);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold text-white mb-1 font-monospace">
                        <i class="bi bi-credit-card-2-front text-danger me-2"></i>Menunggu Pembayaran Midtrans Snap
                    </h5>
                    <p class="small text-secondary mb-0 font-monospace">
                        Total Tagihan: <span class="fw-bold text-white fs-6">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>. Stok produk telah diamankan di sistem inventaris.
                    </p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    @if(!empty($sale->snap_token))
                        <button id="pay-button" class="btn btn-rm-primary font-monospace py-2 px-3">
                            <i class="bi bi-wallet2 me-1"></i> Bayar via Midtrans Snap
                        </button>
                    @endif

                    <!-- Sandbox Simulator Button for Demo / UTS Evaluation -->
                    <form action="{{ route('customer.orders.simulate-payment', $sale) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-glass-outline font-monospace py-2 px-3 text-success border-success" title="Simulasi instan pembayaran lunas mode sandbox">
                            <i class="bi bi-check-circle me-1"></i> Simulasi Lunas (Sandbox)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @elseif($sale->payment_status === 'paid')
        <div class="glass-card p-3 mb-4 d-flex align-items-center gap-3" style="background: rgba(34, 197, 94, 0.08); border-left: 4px solid #22c55e;">
            <i class="bi bi-check-circle-fill text-success fs-3"></i>
            <div>
                <div class="fw-bold text-white font-monospace">Pembayaran Telah Diverifikasi (Lunas)</div>
                <div class="small text-secondary font-monospace">Pesanan Anda telah selesai diproses oleh sistem dan stok barang telah terpotong secara resmi.</div>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 h-100">
                <div class="text-secondary small font-monospace">DATA PEMESAN</div>
                <div class="fw-bold text-white fs-6 mt-1">{{ $sale->buyer->name ?? Auth::user()->name }}</div>
                <div class="small text-secondary font-monospace mt-1"><i class="bi bi-envelope me-1"></i>{{ $sale->buyer->email ?? Auth::user()->email }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 h-100">
                <div class="text-secondary small font-monospace">WAKTU TRANSAKSI</div>
                <div class="fw-bold text-white fs-6 mt-1">{{ $sale->sold_at ? $sale->sold_at->format('d F Y - H:i') : '-' }}</div>
                <div class="small text-secondary font-monospace mt-1">Metode: <span class="badge bg-white bg-opacity-10 text-white font-monospace text-uppercase">{{ $sale->payment_method }}</span></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="glass-card p-3 h-100">
                <div class="text-secondary small font-monospace">CATATAN PESANAN</div>
                <div class="small text-white fst-italic mt-1">{{ $sale->notes ?: 'Tidak ada catatan khusus.' }}</div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="glass-card mb-4">
        <div class="card-header py-3 fw-bold text-white font-monospace">
            <i class="bi bi-boxes text-danger me-2"></i>Rincian Produk Pesanan
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Produk</th>
                        <th>SKU</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td class="ps-3 fw-bold text-white">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                            <td class="font-monospace text-secondary small">{{ $item->product->sku ?? '-' }}</td>
                            <td class="text-end font-monospace text-secondary">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-center font-monospace text-white">{{ $item->quantity }}</td>
                            <td class="text-end pe-3 font-monospace fw-bold text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-top border-white border-opacity-10">
                        <td colspan="4" class="text-end fw-bold text-secondary font-monospace">GRAND TOTAL:</td>
                        <td class="text-end pe-3 fs-5 fw-bold font-monospace" style="color: var(--rm-red);">
                            Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($sale->payment_status === 'pending' && !empty($sale->snap_token))
    <!-- Midtrans Snap JS Integration -->
    @php
        $snapUrl = config('services.midtrans.is_production') 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key') ?? 'SB-Mid-client-sample';
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                window.snap.pay('{{ $sale->snap_token }}', {
                    onSuccess: function (result) {
                        alert('Pembayaran berhasil dikonfirmasi!');
                        window.location.reload();
                    },
                    onPending: function (result) {
                        alert('Menunggu penyelesaian pembayaran.');
                        window.location.reload();
                    },
                    onError: function (result) {
                        alert('Pembayaran gagal atau dibatalkan.');
                        window.location.reload();
                    },
                    onClose: function () {
                        console.log('Popup Snap ditutup oleh pengguna.');
                    }
                });
            });
        }
    </script>
@endif
@endpush
