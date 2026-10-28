@extends('layouts.app')

@section('title', 'Detail Aktivitas & Faktur #' . $sale->sale_number)
@section('page_heading', 'Riwayat Aktivitas & Faktur Pesanan')

@section('content')
<div class="container-fluid px-0" style="max-width: 1200px;">
    <!-- Breadcrumb & Header Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="text-decoration-none small text-secondary mb-2 d-inline-flex align-items-center gap-1 font-monospace">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Pesanan
            </a>
            <div class="d-flex align-items-center flex-wrap gap-2.5">
                <h3 class="mb-0 text-white font-monospace fw-bold" style="letter-spacing: -0.5px;">{{ $sale->sale_number }}</h3>
                @if($sale->payment_status === 'paid')
                    <span class="badge badge-gradient-success font-monospace fs-6 px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-check-circle-fill"></i> LUNAS (PAID)
                    </span>
                @elseif($sale->payment_method === 'cod')
                    <span class="badge font-monospace fs-6 px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="background: rgba(22, 163, 74, 0.08); color: #15803D; border: 1px solid rgba(22, 163, 74, 0.2);">
                        <i class="bi bi-cash-stack"></i> COD (BAYAR DI TEMPAT)
                    </span>
                @elseif($sale->payment_status === 'pending')
                    <span class="badge badge-gradient-warning font-monospace fs-6 px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-hourglass-split"></i> MENUNGGU PEMBAYARAN
                    </span>
                @elseif($sale->payment_status === 'refunded')
                    <span class="badge badge-gradient-info font-monospace fs-6 px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> REFUNDED
                    </span>
                @else
                    <span class="badge badge-gradient-danger font-monospace fs-6 px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-x-circle-fill"></i> GAGAL
                    </span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($sale->payment_status === 'pending' && $sale->payment_method === 'midtrans')
                <a href="{{ route('customer.orders.payment', $sale) }}" class="order-action-btn order-action-primary font-monospace fw-bold">
                    <i class="bi bi-credit-card-2-front"></i>
                    <span>Halaman Pembayaran</span>
                </a>

                <form action="{{ route('customer.orders.switch-to-cod', $sale) }}" 
                      method="POST" 
                      class="m-0"
                      data-confirm="Ubah metode pembayaran menjadi Bayar di Tempat (COD)? Pesanan akan langsung masuk antrean pengemasan gudang."
                      data-confirm-title="Ubah ke Bayar di Tempat (COD)?"
                      data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                      data-confirm-type="success"
                      data-confirm-icon="bi-cash-stack"
                      data-confirm-btn="Ya, Ubah ke COD">
                    @csrf
                    <button type="submit" class="order-action-btn font-monospace" style="background: #FFFFFF; border: 1px solid rgba(22, 163, 74, 0.4) !important; color: #15803D;">
                        <i class="bi bi-cash-stack"></i>
                        <span>Ubah ke COD</span>
                    </button>
                </form>
            @elseif($sale->shipping_status === 'shipped')
                <form action="{{ route('customer.orders.confirm-delivery', $sale) }}" 
                      method="POST" 
                      class="m-0"
                      data-confirm="Pastikan Anda telah menerima paket pesanan secara fisik dan isinya lengkap sebelum mengonfirmasi penerimaan."
                      data-confirm-title="Konfirmasi Pesanan Diterima?"
                      data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                      data-confirm-type="success"
                      data-confirm-icon="bi-box2-heart-fill"
                      data-confirm-btn="Ya, Pesanan Diterima">
                    @csrf
                    <button type="submit" class="order-action-btn order-action-success font-monospace border-0">
                        <i class="bi bi-check2-circle"></i>
                        <span>Pesanan Diterima</span>
                    </button>
                </form>
            @elseif($sale->shipping_status === 'packing')
                <span class="order-action-btn order-action-waiting font-monospace">
                    <i class="bi bi-box-seam text-warning"></i>
                    <span>Menunggu Pengiriman Gudang</span>
                </span>
            @elseif($sale->shipping_status === 'delivered')
                <form action="{{ route('customer.cart.reorder', $sale) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="order-action-btn order-action-primary font-monospace border-0">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Beli Lagi</span>
                    </button>
                </form>
            @endif

            @if($sale->status !== 'cancelled' && in_array($sale->shipping_status, ['pending', 'packing']))
                <form action="{{ route('customer.orders.cancel', $sale) }}" 
                      method="POST" 
                      class="m-0"
                      data-confirm="Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan langsung dikembalikan ke inventaris."
                      data-confirm-title="Batalkan Pesanan?"
                      data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                      data-confirm-type="danger"
                      data-confirm-icon="bi-x-circle-fill"
                      data-confirm-btn="Ya, Batalkan Pesanan">
                    @csrf
                    <button type="submit" class="order-action-btn font-monospace" style="background: #FFFFFF; border: 1px solid rgba(220, 38, 38, 0.4) !important; color: #DC2626;">
                        <i class="bi bi-x-circle"></i>
                        <span>Batalkan Pesanan</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('customer.orders.invoice.pdf', $sale) }}" class="order-action-btn order-action-outline font-monospace text-decoration-none" target="_blank" title="Unduh Berkas Faktur PDF Berkop Resmi">
                <i class="bi bi-file-earmark-pdf text-danger"></i>
                <span>Download PDF</span>
            </a>

            <button onclick="window.print()" class="order-action-btn order-action-outline font-monospace">
                <i class="bi bi-printer"></i>
                <span>Cetak Invoice</span>
            </button>
        </div>
    </div>

    <!-- Audit Log & Aktivitas Konsumen Banner (No Payment CTA) -->
    <div class="card mb-4 p-3.5 p-md-4 rounded-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-left: 4px solid var(--colors-accent) !important; box-shadow: 0 4px 20px -2px rgba(28, 26, 24, 0.05);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: var(--colors-card-alt); color: var(--colors-accent); border: 1px solid var(--colors-hairline);">
                    <i class="bi bi-journal-check fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-white mb-1 font-monospace">
                        Audit Catatan Aktivitas Pesanan Konsumen
                    </h6>
                    <p class="small text-secondary mb-0 font-monospace">
                        @if($sale->payment_status === 'paid')
                            <span class="text-success"><i class="bi bi-check-circle me-1"></i>Transaksi telah lunas diverifikasi. Pesanan sedang disiapkan di gudang.</span>
                        @elseif($sale->payment_method === 'cod')
                            <span class="text-success"><i class="bi bi-truck me-1"></i>Metode Bayar di Tempat (COD). Siapkan uang tunai saat kurir tiba mengantarkan paket.</span>
                        @else
                            <span class="text-warning"><i class="bi bi-hourglass-split me-1"></i>Pesanan tercatat di sistem inventaris dan menunggu pembayaran online.</span>
                            <a href="{{ route('customer.orders.payment', $sale) }}" class="ms-2 badge badge-gradient-accent text-decoration-none font-monospace py-1 px-2.5">
                                Menuju Portal Pembayaran &rarr;
                            </a>
                        @endif
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="small font-monospace text-muted">ID Transaksi:</span>
                <code class="px-2 py-1 rounded font-monospace" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: #1A1A1C;">{{ $sale->sale_number }}</code>
            </div>
        </div>
    </div>

    <!-- 3 Summary Info Cards -->
    <div class="row g-3 mb-4">
        <!-- 1. Data Pemesan -->
        <div class="col-12 col-md-4">
            <div class="card p-3.5 h-100" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 14px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-person-fill" style="color: var(--colors-accent);"></i>
                    <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.75rem;">Data Konsumen</span>
                </div>
                <div class="fw-bold text-white fs-6 mb-1">{{ $sale->buyer->name ?? ($sale->user->name ?? 'Pelanggan') }}</div>
                <div class="text-secondary small font-monospace d-flex align-items-center gap-1.5">
                    <i class="bi bi-envelope"></i>
                    <span>{{ $sale->buyer->email ?? ($sale->user->email ?? '-') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Waktu & Metode Transaksi -->
        <div class="col-12 col-md-4">
            <div class="card p-3.5 h-100" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 14px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-calendar-event" style="color: var(--colors-accent);"></i>
                    <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.75rem;">Waktu & Metode Transaksi</span>
                </div>
                <div class="fw-bold text-white fs-6 mb-1 font-monospace">
                    {{ $sale->sold_at ? $sale->sold_at->translatedFormat('d F Y – H:i') : '-' }}
                </div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="small font-monospace text-secondary">Metode:</span>
                    @if($sale->payment_method === 'cod')
                        <span class="badge font-monospace" style="background: rgba(22, 163, 74, 0.08); color: #15803D; border: 1px solid rgba(22, 163, 74, 0.2); font-size: 0.7rem;">COD (BAYAR DI TEMPAT)</span>
                    @else
                        <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.7rem;">MIDTRANS SNAP (ONLINE)</span>
                    @endif
                </div>
                @if($sale->shipping_courier)
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="small font-monospace text-secondary">Pengiriman:</span>
                        <span class="badge bg-transparent border font-monospace" style="border-color: var(--colors-hairline) !important; font-size: 0.7rem; color: var(--colors-text) !important;">
                            {{ $sale->shipping_courier }}
                        </span>
                        @if($sale->shipping_destination)
                            <span class="text-secondary small font-monospace">({{ ucwords($sale->shipping_destination) }})</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. Catatan & Kupon Promo -->
        <div class="col-12 col-md-4">
            <div class="card p-3.5 h-100" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 14px;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-chat-left-text" style="color: var(--colors-accent);"></i>
                    <span class="small font-monospace text-uppercase" style="color: var(--colors-muted); font-size: 0.75rem;">Catatan & Voucher</span>
                </div>
                <div class="small text-white mb-1.5" style="line-height: 1.4;">
                    {{ $sale->notes ?: 'Tidak ada catatan pesanan khusus.' }}
                </div>
                @if($sale->promo_code)
                    <div class="mt-auto pt-1 d-flex align-items-center gap-1.5 font-monospace" style="font-size: 0.75rem;">
                        <span class="text-secondary">Voucher:</span>
                        <span class="badge badge-gradient-accent">{{ $sale->promo_code }} (-Rp {{ number_format($sale->discount_amount, 0, ',', '.') }})</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card mb-4 p-0" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 16px;">
        <div class="p-3.5 px-4 d-flex align-items-center justify-content-between border-bottom" style="border-color: var(--colors-hairline) !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-boxes fs-5" style="color: var(--colors-accent);"></i>
                <h5 class="fw-bold text-white mb-0">Rincian Produk Pesanan</h5>
            </div>
            <span class="badge badge-gradient-accent font-monospace">
                {{ $sale->items->sum('quantity') }} Total Unit Barang
            </span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid var(--colors-hairline);">
                        <th class="ps-4">Produk</th>
                        <th>Kode SKU</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-end pe-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        @php
                            $prod = $item->product;
                        @endphp
                        <tr style="border-bottom: 1px solid var(--colors-hairline);">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if($prod && $prod->image_url)
                                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="object-fit-cover rounded-3 flex-shrink-0" style="width: 44px; height: 44px; border: 1px solid var(--colors-hairline); background: var(--colors-card-alt);">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: var(--colors-muted);">
                                            <i class="bi bi-box-seam fs-5"></i>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column justify-content-center" style="min-width: 0;">
                                        <span class="fw-semibold text-white">{{ $prod->name ?? 'Produk Dihapus' }}</span>
                                        <span class="badge bg-transparent border font-monospace text-muted align-self-start mt-0.5" style="border-color: var(--colors-hairline) !important; font-size: 0.7rem;">
                                            {{ $prod->category->name ?? 'Hardware' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="font-monospace text-secondary small">
                                <code>{{ $prod->sku ?? '-' }}</code>
                            </td>
                            <td class="text-end font-monospace text-white small">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="text-center font-monospace text-white fw-bold">
                                {{ $item->quantity }}
                            </td>
                            <td class="text-end pe-4 font-monospace fw-bold" style="color: var(--colors-accent);">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if($sale->discount_amount > 0 || $sale->shipping_cost > 0)
                        <tr>
                            <td colspan="4" class="text-end text-secondary font-monospace pt-3">Subtotal Produk:</td>
                            <td class="text-end pe-4 text-white font-monospace pt-3">
                                Rp {{ number_format($sale->subtotal > 0 ? $sale->subtotal : $sale->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    @if($sale->discount_amount > 0)
                        <tr>
                            <td colspan="4" class="text-end text-success font-monospace py-1">Diskon Voucher ({{ $sale->promo_code }}):</td>
                            <td class="text-end pe-4 text-success font-monospace fw-bold py-1">
                                - Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    @if($sale->shipping_cost > 0 || $sale->shipping_courier)
                        <tr>
                            <td colspan="4" class="text-end text-secondary font-monospace py-1">
                                Ongkos Kirim ({{ $sale->shipping_courier ?? 'Kurir' }}{{ $sale->shipping_destination ? ' - ' . ucwords($sale->shipping_destination) : '' }}):
                            </td>
                            <td class="text-end pe-4 text-white font-monospace fw-semibold py-1">
                                Rp {{ number_format($sale->shipping_cost, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    <tr class="border-top" style="border-color: var(--colors-hairline) !important; background: var(--colors-card-alt);">
                        <td colspan="4" class="text-end fw-semibold text-secondary font-monospace py-3">TOTAL TAGIHAN:</td>
                        <td class="text-end pe-4 fs-5 fw-bold font-monospace py-3" style="color: var(--colors-accent);">
                            Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
