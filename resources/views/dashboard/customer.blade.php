@extends('layouts.app')

@section('title', 'Buyer Dashboard - Ctrl + C')
@section('page_heading', 'Dashboard Pelanggan')

@section('content')
<div class="container px-0 px-md-2">
    <!-- 1. Hero Showcase Banner (Clean Light with Blue Accent) -->
    <div class="card mb-4 p-4 p-md-5 position-relative overflow-hidden bg-white" style="border: 1px solid var(--border-subtle); box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.05);">
        <div class="row align-items-center g-4">
            <div class="col-12 col-md-8">
                <div class="d-inline-flex align-items-center gap-2 mb-3">
                    <span class="badge-blue">
                        <i class="bi bi-patch-check-fill me-1"></i> VERIFIED BUYER PORTAL
                    </span>
                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.68rem; border-color: var(--border-subtle) !important;">
                        MIDTRANS SNAP 3D-SECURE
                    </span>
                </div>
                <h2 class="fw-bold display-6 mb-2 text-dark">
                    Selamat Datang, {{ Auth::user()->name }}
                </h2>
                <p class="text-secondary mb-4 fs-6 lh-base" style="max-width: 540px;">
                    Eksplorasi hardware dan peripheral turnamen resmi. Stok produk diamankan secara atomik dengan garansi resmi distributor.
                </p>
                <div class="d-flex flex-wrap gap-2.5">
                    <a href="{{ route('customer.catalog') }}" class="btn btn-blue-primary py-2.5 px-4">
                        <i class="bi bi-cart3 me-1"></i> Buka Katalog Lengkap &rarr;
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-glass-outline py-2.5 px-3">
                        <i class="bi bi-bag-check me-1"></i> Riwayat Pesanan ({{ $myOrdersCount }})
                    </a>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-flex justify-content-center">
                <div class="text-center p-4 rounded-4" style="background: #EFF6FF; border: 1px solid #BFDBFE;">
                    <i class="bi bi-laptop display-1" style="color: var(--accent-blue);"></i>
                    <div class="fw-bold mt-2 font-monospace small text-dark">Ctrl + C ECOSYSTEM</div>
                    <div class="text-secondary" style="font-size: 0.72rem;">ZERO OVERSELLING SUPPLY</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Real-Time Pending Midtrans Order Alert -->
    @if($myOrdersPending)
        <div class="card p-4 mb-4 bg-white" style="border-left: 4px solid var(--accent-blue); border-color: var(--border-subtle);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="p-2.5 rounded-3 d-inline-flex align-items-center justify-content-center" style="background: #EFF6FF; color: var(--accent-blue); width: 44px; height: 44px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-dark fs-6">
                            Menunggu Pembayaran Midtrans Snap: <span class="font-monospace text-primary">#{{ $myOrdersPending->sale_number }}</span>
                        </div>
                        <div class="small text-secondary font-monospace">
                            Total Tagihan: <span class="fw-bold text-dark">Rp {{ number_format($myOrdersPending->total, 0, ',', '.') }}</span> &bull; Stok produk telah diamankan di sistem.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('customer.orders.show', $myOrdersPending->id) }}" class="btn btn-blue-primary btn-sm py-2 px-3">
                        <i class="bi bi-credit-card-2-front-fill me-1"></i> Bayar Sekarang (Midtrans)
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Quick Category Navigation Chips -->
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog', ['search' => 'Laptop']) }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #EFF6FF; color: var(--accent-blue);">
                    <i class="bi bi-laptop fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Laptop</div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog', ['search' => 'Keyboard']) }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #EFF6FF; color: var(--accent-blue);">
                    <i class="bi bi-keyboard fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Keyboard</div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog', ['search' => 'Mouse']) }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #EFF6FF; color: var(--accent-blue);">
                    <i class="bi bi-mouse fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Mouse</div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog', ['search' => 'Monitor']) }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #EFF6FF; color: var(--accent-blue);">
                    <i class="bi bi-display fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Monitor</div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog', ['search' => 'SSD']) }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #EFF6FF; color: var(--accent-blue);">
                    <i class="bi bi-device-hdd fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Storage SSD</div>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="{{ route('customer.catalog') }}" class="card text-center p-3 text-decoration-none h-100 d-block bg-white">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: #F1F5F9; color: var(--text-primary);">
                    <i class="bi bi-grid-fill fs-5"></i>
                </div>
                <div class="fw-semibold text-dark small text-truncate">Semua Produk</div>
            </a>
        </div>
    </div>

    <!-- 4. Real-Time Tracking & Riwayat Pesanan Terkini -->
    <div class="card p-4 mb-4 bg-white">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
            <div>
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-truck me-2" style="color: var(--accent-blue);"></i> Status Tracking Pesanan
                </h5>
                <span class="text-secondary small font-monospace">Pantau status transaksi dan pembayaran pesanan Anda secara real-time</span>
            </div>
            <a href="{{ route('customer.orders.index') }}" class="text-decoration-none small fw-semibold" style="color: var(--accent-blue);">
                Lihat Semua ({{ $myOrdersCount }}) &rarr;
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="p-4 text-center text-secondary font-monospace small">
                <i class="bi bi-bag-x fs-1 d-block mb-2 text-muted"></i>
                Anda belum memiliki riwayat pesanan. Mulai belanja sekarang dari katalog!
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Invoice</th>
                            <th>Tanggal Transaksi</th>
                            <th>Total Tagihan</th>
                            <th>Status Bayar</th>
                            <th>Status Pesanan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td class="font-monospace fw-semibold text-dark">
                                    {{ $order->sale_number }}
                                </td>
                                <td class="text-secondary small font-monospace">
                                    {{ \Carbon\Carbon::parse($order->sold_at)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="font-monospace fw-bold text-dark">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 font-monospace">LUNAS</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 font-monospace">MENUNGGU BAYAR</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 font-monospace">{{ strtoupper($order->payment_status ?? 'UNPAID') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 font-monospace">SELESAI</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 font-monospace">DIBATALKAN</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-glass-outline py-1 px-2.5 font-monospace" style="font-size: 0.75rem;">
                                        Rincian & Bayar <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- 5. Katalog Produk Cepat (Ready Stock) -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Hardware Unggulan Siap Kirim</h5>
                <span class="text-secondary small font-monospace">Unit terbatas dengan stok real-time</span>
            </div>
            <a href="{{ route('customer.catalog') }}" class="text-decoration-none small fw-semibold" style="color: var(--accent-blue);">
                Katalog Lengkap &rarr;
            </a>
        </div>

        <div class="row g-3">
            @foreach($catalogProducts as $p)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 p-3 bg-white" style="border: 1px solid var(--border-subtle);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-blue" style="font-size: 0.65rem;">{{ $p->category->name ?? 'GEAR' }}</span>
                            <span class="text-secondary small font-monospace" style="font-size: 0.7rem;">Stok: {{ $p->stock }}</span>
                        </div>
                        <h6 class="fw-bold text-dark text-truncate mb-1" title="{{ $p->name }}">{{ $p->name }}</h6>
                        <div class="font-monospace fw-bold text-primary mb-3">
                            Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('customer.product.show', $p->id) }}" class="btn btn-blue-primary btn-sm w-100 py-1.5 font-monospace">
                                <i class="bi bi-cart-plus me-1"></i> Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
