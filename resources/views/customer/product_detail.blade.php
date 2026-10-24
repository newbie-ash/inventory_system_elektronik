@extends('layouts.app')

@section('title', $product->name . ' - Detail Produk')
@section('page_heading', 'Detail Hardware & Checkout')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-3">
        <a href="{{ route('customer.catalog') }}" class="text-decoration-none small text-secondary font-monospace">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog Produk
        </a>
    </div>

    @php
        $price = $product->selling_price > 0 ? $product->selling_price : $product->price;
    @endphp

    <div class="card p-4 mb-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline);">
        <div class="row g-4">
            <!-- Left Info -->
            <div class="col-12 col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge font-monospace" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); border: 1px solid var(--colors-accent); font-size: 0.68rem;">
                        {{ $product->category->name ?? 'GEAR' }}
                    </span>
                    <span class="badge bg-white bg-opacity-10 text-white font-monospace border border-white border-opacity-10" style="font-size: 0.68rem;">
                        SKU: {{ $product->sku }}
                    </span>
                </div>
                <h2 class="text-white mb-3" style="font-family: 'Monda', sans-serif; letter-spacing: -0.96px;">{{ $product->name }}</h2>

                <!-- Product Visual Placeholder -->
                <div class="mb-4 d-flex align-items-center justify-content-center" style="height: 200px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: var(--colors-accent);">
                    @if(str_contains(strtolower($product->name), 'laptop'))
                        <i class="bi bi-laptop" style="font-size: 6rem;"></i>
                    @elseif(str_contains(strtolower($product->name), 'keyboard'))
                        <i class="bi bi-keyboard" style="font-size: 6rem;"></i>
                    @elseif(str_contains(strtolower($product->name), 'mouse'))
                        <i class="bi bi-mouse" style="font-size: 6rem;"></i>
                    @elseif(str_contains(strtolower($product->name), 'monitor'))
                        <i class="bi bi-display" style="font-size: 6rem;"></i>
                    @else
                        <i class="bi bi-cpu" style="font-size: 6rem;"></i>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="text-secondary small font-monospace mb-1">HARGA RESMI DISTRIBUTOR:</div>
                    <div class="fs-2 fw-bold font-monospace" style="color: var(--colors-accent);">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-semibold text-white font-monospace mb-2">Deskripsi & Spesifikasi Hardware:</h6>
                    <p class="small lh-base" style="white-space: pre-line; color: var(--colors-body);">
                        {{ $product->description ?: 'Hardware resmi standar turnamen dengan garansi distributor original.' }}
                    </p>
                </div>

                <div class="d-flex align-items-center gap-3 p-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                    <i class="bi bi-shield-check fs-3" style="color: var(--colors-accent);"></i>
                    <div class="small font-monospace">
                        <div class="fw-bold text-white">Stok Terkunci Aman di Gudang</div>
                        <div class="text-secondary">{{ $product->stock }} unit siap dikirim dengan verifikasi transaksi instan.</div>
                    </div>
                </div>
            </div>

            <!-- Right Order Card -->
            <div class="col-12 col-md-5">
                <div class="card h-100 p-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-top: 3px solid var(--colors-accent) !important;">
                    <h5 class="fw-semibold text-white font-monospace mb-3">
                        <i class="bi bi-bag-plus me-2" style="color: var(--colors-accent);"></i>Form Pemesanan Cepat
                    </h5>

                    <form action="{{ route('customer.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label class="form-label small font-monospace fw-semibold text-secondary">KUANTITAS PEMBELIAN</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-glass-outline" onclick="decQty()">-</button>
                                <input type="number" name="items[0][quantity]" id="qtyInput" class="form-control text-center font-monospace fw-bold" value="1" min="1" max="{{ $product->stock }}" required>
                                <button type="button" class="btn btn-glass-outline" onclick="incQty()">+</button>
                            </div>
                            <div class="text-secondary font-monospace small mt-1" style="font-size: 0.72rem;">Maksimal: {{ $product->stock }} unit.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-monospace fw-semibold text-secondary">CATATAN PENGIRIMAN</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Packing kayu / asuransi pengiriman"></textarea>
                        </div>

                        <div class="p-3 mb-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                            <div class="d-flex justify-content-between small text-secondary font-monospace mb-1">
                                <span>Subtotal:</span>
                                <span class="fw-bold text-white" id="subtotalDisplay">Rp {{ number_format($price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between small text-secondary font-monospace">
                                <span>Payment Gateway:</span>
                                <span class="badge" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); border: 1px solid var(--colors-accent);">Midtrans Snap</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 font-monospace text-uppercase">
                            <i class="bi bi-credit-card me-1"></i> Beli via Midtrans Snap &rarr;
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-4">
            <h5 class="fw-semibold text-white font-monospace mb-3">Produk Terkait Lainnya</h5>
            <div class="row g-3">
                @foreach($relatedProducts as $rp)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('customer.catalog.show', $rp) }}" class="card p-3 text-decoration-none d-flex flex-column h-100" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline);">
                            <div class="font-monospace text-secondary small">{{ $rp->sku }}</div>
                            <div class="fw-bold text-white small text-truncate mb-1">{{ $rp->name }}</div>
                            <div class="mt-auto pt-2 d-flex justify-content-between align-items-center border-top" style="border-color: var(--colors-hairline) !important;">
                                <span class="fw-bold font-monospace small" style="color: var(--colors-accent);">Rp {{ number_format($rp->selling_price, 0, ',', '.') }}</span>
                                <span class="badge bg-white bg-opacity-10 text-white font-monospace" style="font-size: 0.65rem;">{{ $rp->stock }} pcs</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const unitPrice = {{ $price }};
    const maxStock = {{ $product->stock }};
    const qtyInput = document.getElementById('qtyInput');
    const subtotalDisplay = document.getElementById('subtotalDisplay');

    function updateSubtotal() {
        let qty = parseInt(qtyInput.value) || 1;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        qtyInput.value = qty;
        let subtotal = qty * unitPrice;
        subtotalDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    function incQty() {
        let cur = parseInt(qtyInput.value) || 1;
        if (cur < maxStock) {
            qtyInput.value = cur + 1;
            updateSubtotal();
        }
    }

    function decQty() {
        let cur = parseInt(qtyInput.value) || 1;
        if (cur > 1) {
            qtyInput.value = cur - 1;
            updateSubtotal();
        }
    }

    if (qtyInput) {
        qtyInput.addEventListener('input', updateSubtotal);
    }
</script>
@endpush
