@extends('layouts.app')

@section('title', 'Pembayaran Pesanan #' . $sale->sale_number . ' - Ctrl+R')
@section('page_heading', 'Selesaikan Pembayaran')

@section('content')
<div class="container-fluid px-0" style="max-width: 960px;">
    <!-- Breadcrumb -->
    <div class="d-flex align-items-center gap-2 mb-3 small font-monospace">
        <a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none">
            <i class="bi bi-house me-1"></i>Beranda
        </a>
        <span class="text-secondary">/</span>
        <a href="{{ route('customer.cart') }}" class="text-secondary text-decoration-none">Keranjang</a>
        <span class="text-secondary">/</span>
        <span class="text-white">Pembayaran Online</span>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #6EE7B7; border-radius: 12px;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card p-4 p-md-5 mb-4" style="background: #FFFFFF; border: 1px solid var(--colors-hairline); border-top: 4px solid var(--colors-accent) !important; border-radius: 20px; box-shadow: 0 10px 30px rgba(28, 26, 24, 0.08);">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 72px; height: 72px; background: rgba(26, 26, 28, 0.08); border: 1px solid var(--colors-hairline); color: var(--colors-accent);">
                <i class="bi bi-credit-card-2-front fs-1"></i>
            </div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Portal Pembayaran Online</h3>
            <p class="text-secondary small font-monospace mb-0">
                Pesanan No. <span class="text-white fw-bold">{{ $sale->sale_number }}</span> &bull; Stok barang telah diamankan di gudang
            </p>
        </div>

        <!-- Bill Amount Card -->
        <div class="p-3.5 p-md-4 rounded-3 mb-4 text-center" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
            <div class="text-secondary small font-monospace mb-1">TOTAL JUMLAH YANG HARUS DIBAYAR:</div>
            <div class="fs-1 fw-bold font-monospace mb-2" style="color: var(--colors-accent); letter-spacing: -1px;">
                Rp {{ number_format($sale->total, 0, ',', '.') }}
            </div>

            @if($sale->discount_amount > 0)
                <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill small font-monospace" style="background: rgba(21, 128, 61, 0.1); border: 1px solid rgba(21, 128, 61, 0.3); color: #15803D; font-size: 0.75rem;">
                    <i class="bi bi-tag-fill"></i>
                    <span>Diskon Kupon {{ $sale->promo_code }}: Hemat Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <!-- Voucher / Promo Code Input on Payment Portal -->
        @if($sale->discount_amount <= 0)
            <div class="mb-4 mx-auto p-3 rounded-3" style="max-width: 460px; background: var(--colors-card-alt); border: 1px dashed var(--colors-hairline);">
                <form action="{{ route('customer.orders.apply-promo', $sale) }}" method="POST">
                    @csrf
                    <label class="form-label small text-secondary font-monospace mb-1.5 d-flex align-items-center gap-1.5">
                        <i class="bi bi-ticket-perforated" style="color: var(--colors-accent);"></i>Punya Kode Promo / Voucher?
                    </label>
                    <div class="d-flex align-items-stretch" style="border: 1px solid var(--colors-hairline); border-radius: 12px; overflow: hidden; background: #FFFFFF; height: 44px;">
                        <div class="d-flex align-items-center ps-3 pe-2 text-secondary">
                            <i class="bi bi-tag-fill" style="color: var(--colors-accent); font-size: 0.9rem;"></i>
                        </div>
                        <input type="text" 
                               name="promo_code" 
                               id="paymentPromoInput" 
                               class="form-control font-monospace text-uppercase border-0 shadow-none m-0 p-0" 
                               placeholder="CONTOH: GAJIAN15" 
                               required 
                               autocomplete="off"
                               style="background: transparent !important; color: var(--colors-text) !important; height: 100%; font-size: 0.85rem; border-radius: 0 !important; letter-spacing: 0.5px;">
                        <button type="submit" 
                                class="btn font-monospace text-uppercase fw-bold px-3 d-flex align-items-center justify-content-center" 
                                style="border: none; border-left: 1px solid var(--colors-hairline); border-radius: 0; background: #1A1A1C; color: #FFFFFF; font-size: 0.78rem; letter-spacing: 0.5px; white-space: nowrap; transition: all 0.2s ease;">
                            Terapkan
                        </button>
                    </div>
                    <div class="text-secondary small font-monospace mt-2 d-flex align-items-center gap-1.5 flex-wrap" style="font-size: 0.72rem;">
                        <span class="text-muted">Kupon:</span>
                        <button type="button" onclick="document.getElementById('paymentPromoInput').value='GAJIAN15'" class="badge border font-monospace cursor-pointer py-1 px-2" style="background: #FFFFFF; color: var(--colors-accent); border-color: var(--colors-hairline) !important; text-decoration: none;">GAJIAN15</button>
                        <button type="button" onclick="document.getElementById('paymentPromoInput').value='GEAR50K'" class="badge border font-monospace cursor-pointer py-1 px-2" style="background: #FFFFFF; color: var(--colors-accent); border-color: var(--colors-hairline) !important; text-decoration: none;">GEAR50K</button>
                        <button type="button" onclick="document.getElementById('paymentPromoInput').value='CTRLR10'" class="badge border font-monospace cursor-pointer py-1 px-2" style="background: #FFFFFF; color: var(--colors-accent); border-color: var(--colors-hairline) !important; text-decoration: none;">CTRLR10</button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Payment Actions: Harmonious Uniform Padding, Widths & Heights -->
        <div class="d-flex flex-column gap-3 mb-4 mx-auto" style="max-width: 460px; width: 100%; gap: 14px !important;">
            <button id="pay-button" 
                    type="button" 
                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2 font-monospace text-uppercase fw-bold text-center" 
                    style="width: 100% !important; height: 50px; min-height: 50px; border-radius: 12px !important; font-size: 0.92rem; box-sizing: border-box; margin: 0 !important;">
                <i class="bi bi-wallet2 fs-5"></i>
                <span>Bayar Sekarang via Midtrans Snap</span>
            </button>

            <!-- Switch to Cash on Delivery (COD) -->
            <form action="{{ route('customer.orders.switch-to-cod', $sale) }}" 
                  method="POST"
                  class="w-100 m-0 p-0 d-block"
                  style="width: 100% !important; margin: 0 !important; padding: 0 !important; display: block;"
                  data-confirm="Ubah metode pembayaran pesanan ini menjadi Bayar di Tempat (COD)? Pesanan akan langsung masuk antrean pengemasan di gudang tanpa perlu transfer."
                  data-confirm-title="Ganti ke Bayar di Tempat (COD)?"
                  data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                  data-confirm-type="success"
                  data-confirm-icon="bi-cash-stack"
                  data-confirm-btn="Ya, Ubah ke COD">
                @csrf
                <button type="submit" 
                        class="btn d-flex align-items-center justify-content-center gap-2 font-monospace text-uppercase fw-bold text-center" 
                        style="width: 100% !important; height: 50px; min-height: 50px; border: 1.5px solid rgba(22, 163, 74, 0.3) !important; background: rgba(22, 163, 74, 0.08) !important; color: #15803D !important; border-radius: 12px !important; font-size: 0.92rem; box-sizing: border-box; transition: all 0.2s ease; margin: 0 !important;" 
                        onmouseover="this.style.background='rgba(22, 163, 74, 0.16)'"
                        onmouseout="this.style.background='rgba(22, 163, 74, 0.08)'"
                        title="Bayar tunai ke kurir saat barang sampai">
                    <i class="bi bi-cash-stack fs-5"></i>
                    <span>Bayar di Tempat (Cash on Delivery)</span>
                </button>
            </form>

            <!-- Sandbox Simulator Button -->
            <form action="{{ route('customer.orders.simulate-payment', $sale) }}" 
                  method="POST"
                  class="w-100 m-0 p-0 d-block"
                  style="width: 100% !important; margin: 0 !important; padding: 0 !important; display: block;">
                @csrf
                <button type="submit" 
                        class="btn d-flex align-items-center justify-content-center gap-2 font-monospace text-uppercase fw-bold text-center" 
                        style="width: 100% !important; height: 50px; min-height: 50px; border: 1.5px solid var(--colors-hairline) !important; background: #FFFFFF !important; color: #1A1A1C !important; border-radius: 12px !important; font-size: 0.92rem; box-sizing: border-box; transition: all 0.2s ease; margin: 0 !important;" 
                        onmouseover="this.style.background='var(--colors-card-alt)'"
                        onmouseout="this.style.background='#FFFFFF'"
                        title="Simulasi lunas instan mode sandbox tanpa buka pop-up">
                    <i class="bi bi-check2-circle fs-5"></i>
                    <span>Simulasi Pembayaran Lunas (Sandbox)</span>
                </button>
            </form>

            <!-- Cancel Order Button -->
            <form action="{{ route('customer.orders.cancel', $sale) }}" 
                  method="POST"
                  class="w-100 m-0 p-0 d-block"
                  style="width: 100% !important; margin: 0 !important; padding: 0 !important; display: block;"
                  data-confirm="Apakah Anda yakin ingin membatalkan pesanan ini? Stok barang akan segera dikembalikan ke inventaris."
                  data-confirm-title="Batalkan Pesanan Ini?"
                  data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                  data-confirm-type="danger"
                  data-confirm-icon="bi-x-circle-fill"
                  data-confirm-btn="Ya, Batalkan Pesanan">
                @csrf
                <button type="submit" 
                        class="btn d-flex align-items-center justify-content-center gap-2 font-monospace text-uppercase fw-bold text-center" 
                        style="width: 100% !important; height: 50px; min-height: 50px; border: 1.5px solid rgba(239, 68, 68, 0.4) !important; background: rgba(239, 68, 68, 0.12) !important; color: #FCA5A5 !important; border-radius: 12px !important; font-size: 0.92rem; box-sizing: border-box; transition: all 0.2s ease; margin: 0 !important;" 
                        onmouseover="this.style.background='rgba(239, 68, 68, 0.22)'"
                        onmouseout="this.style.background='rgba(239, 68, 68, 0.12)'"
                        title="Batalkan pesanan ini">
                    <i class="bi bi-x-circle fs-5"></i>
                    <span>Batalkan Pesanan</span>
                </button>
            </form>
        </div>

        <div class="text-center small text-muted font-monospace mb-4" style="font-size: 0.75rem;">
            <i class="bi bi-shield-lock me-1"></i>Transaksi dilindungi enkripsi 256-bit resmi Midtrans Payment Gateway
        </div>

        <!-- Order Items Breakdown -->
        <div class="pt-4 border-top" style="border-color: var(--colors-hairline) !important;">
            <h6 class="fw-bold text-white font-monospace mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-boxes" style="color: var(--colors-accent);"></i>Rincian Barang yang Dipesan
            </h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0 font-monospace small">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--colors-hairline); color: var(--colors-muted);">
                            <th>Produk</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr style="border-bottom: 1px solid var(--colors-hairline);">
                                <td class="text-white fw-semibold">{{ $item->product->name ?? 'Produk' }}</td>
                                <td class="text-center text-white">{{ $item->quantity }} unit</td>
                                <td class="text-end text-white">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold" style="color: var(--colors-accent);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-secondary pt-3">Subtotal Produk:</td>
                            <td class="text-end text-white pt-3">Rp {{ number_format($sale->subtotal > 0 ? $sale->subtotal : $sale->total, 0, ',', '.') }}</td>
                        </tr>
                        @if($sale->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-success">Diskon Promo ({{ $sale->promo_code }}):</td>
                                <td class="text-end text-success fw-bold">- Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end fw-bold text-secondary">Total Tagihan:</td>
                            <td class="text-end fs-6 fw-bold" style="color: var(--colors-accent);">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Direct Navigation Back to Audit History -->
    <div class="text-center mb-5">
        <a href="{{ route('customer.orders.show', $sale) }}" class="text-secondary small font-monospace text-decoration-none">
            <i class="bi bi-clock-history me-1"></i>Lihat Rincian Audit Pesanan di Riwayat Aktivitas &rarr;
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $sale->snap_token }}', {
                onSuccess: function (result) {
                    const trxStatus = (result && result.transaction_status) ? result.transaction_status : 'settlement';
                    window.location.href = "{{ route('customer.orders.show', $sale) }}?transaction_status=" + encodeURIComponent(trxStatus);
                },
                onPending: function (result) {
                    const trxStatus = (result && result.transaction_status) ? result.transaction_status : 'pending';
                    window.location.href = "{{ route('customer.orders.show', $sale) }}?transaction_status=" + encodeURIComponent(trxStatus);
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
@endpush
