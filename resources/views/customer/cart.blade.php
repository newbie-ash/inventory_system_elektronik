@extends('layouts.app')

@section('title', 'Keranjang Belanja - Ctrl+R')
@section('page_heading', 'Keranjang Belanja')

@section('content')
<style>
    /* Cart Table Clean Styling */
    .cart-table th {
        font-family: monospace;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--colors-muted);
        padding-top: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--colors-hairline);
    }
    .cart-table td {
        padding-top: 16px;
        padding-bottom: 16px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(166, 156, 172, 0.1);
    }
    
    /* Dedicated Quantity Stepper */
    .cart-qty-stepper {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(13, 12, 29, 0.65);
        border: 1px solid rgba(166, 156, 172, 0.28);
        border-radius: 9px;
        padding: 3px 4px;
        width: 110px;
        height: 38px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .cart-qty-stepper:focus-within {
        border-color: var(--colors-accent);
        box-shadow: 0 0 0 2px rgba(241, 218, 196, 0.2);
    }
    .cart-qty-btn {
        width: 28px;
        height: 28px;
        padding: 0;
        margin: 0;
        border: 1px solid rgba(166, 156, 172, 0.2);
        background: rgba(71, 73, 115, 0.35);
        border-radius: 6px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .cart-qty-btn:hover:not(:disabled) {
        background: var(--colors-accent);
        color: var(--colors-accent-text);
        border-color: var(--colors-accent);
        transform: scale(1.04);
    }
    .cart-qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        background: rgba(71, 73, 115, 0.15);
        border-color: transparent;
    }
    .cart-qty-input {
        width: 42px;
        height: 28px;
        padding: 0 2px;
        border: none;
        background: transparent;
        color: #ffffff;
        font-family: monospace;
        font-weight: 700;
        font-size: 0.95rem;
        text-align: center;
        outline: none;
        -moz-appearance: textfield;
    }
    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Cart Table Wrap without chunky default scrollbar */
    .cart-table-wrap {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(166, 156, 172, 0.2) transparent;
    }
    .cart-table-wrap::-webkit-scrollbar {
        height: 5px;
    }
    .cart-table-wrap::-webkit-scrollbar-thumb {
        background: rgba(166, 156, 172, 0.25);
        border-radius: 4px;
    }
</style>

<div class="container-fluid px-0" style="max-width: 1280px;">
    <!-- Breadcrumb -->
    <div class="d-flex align-items-center gap-2 mb-3 small font-monospace">
        <a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none">
            <i class="bi bi-house me-1"></i>Beranda
        </a>
        <span class="text-secondary">/</span>
        <span class="text-white">Keranjang Belanja</span>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #6EE7B7; border-radius: 12px;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #FDE68A; border-radius: 12px;">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #FCA5A5; border-radius: 12px;">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <div>
                {{ session('error') ?? $errors->first() }}
            </div>
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="row g-4">
            <!-- Left: Cart Items Table -->
            <div class="col-12 col-lg-8">
                <div class="card p-0" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 16px;">
                    <div class="p-3.5 px-4 d-flex align-items-center justify-content-between border-bottom" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-cart3 fs-5" style="color: var(--colors-accent);"></i>
                            <h5 class="fw-bold text-white mb-0">Daftar Produk di Keranjang</h5>
                        </div>
                        <span class="badge badge-gradient-accent font-monospace">
                            {{ count($cartItems) }} Macam Produk
                        </span>
                    </div>

                    <div class="cart-table-wrap">
                        <table class="table cart-table align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-end px-3" style="white-space: nowrap; width: 120px;">Harga Satuan</th>
                                    <th class="text-center px-3" style="white-space: nowrap; width: 140px;">Jumlah</th>
                                    <th class="text-end px-3" style="white-space: nowrap; width: 130px;">Subtotal</th>
                                    <th class="text-center pe-4" style="width: 65px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    @php
                                        $prod = $item['product'];
                                    @endphp
                                    <tr>
                                        <!-- Product Column with Thumbnail -->
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($prod->image_url)
                                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="object-fit-cover rounded-3 flex-shrink-0" style="width: 52px; height: 52px; border: 1px solid rgba(166, 156, 172, 0.25); background: var(--colors-card-alt);">
                                                @else
                                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(166, 156, 172, 0.2); color: var(--colors-muted);">
                                                        <i class="bi bi-box-seam fs-4"></i>
                                                    </div>
                                                @endif
                                                <div class="d-flex flex-column justify-content-center" style="min-width: 0;">
                                                    <a href="{{ route('customer.catalog.show', $prod) }}" class="fw-semibold text-white text-decoration-none text-truncate" style="max-width: 250px;" title="{{ $prod->name }}">
                                                        {{ $prod->name }}
                                                    </a>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <span class="badge bg-transparent border font-monospace text-muted" style="border-color: rgba(166, 156, 172, 0.25) !important; font-size: 0.7rem;">
                                                            {{ $prod->sku }}
                                                        </span>
                                                        <span class="badge badge-gradient-info font-monospace" style="font-size: 0.68rem;">
                                                            {{ $prod->category->name ?? 'Hardware' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price (Single Line, Right-Aligned) -->
                                        <td class="text-end px-3 font-monospace text-white small" style="white-space: nowrap;">
                                            Rp {{ number_format($item['unit_price'], 0, ',', '.') }}
                                        </td>

                                        <!-- Quantity Control Form with Dedicated Padding -->
                                        <td class="text-center px-3">
                                            <form action="{{ route('customer.cart.update') }}" method="POST" class="d-inline-flex flex-column align-items-center justify-content-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                                
                                                <div class="cart-qty-stepper">
                                                    <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" 
                                                            class="cart-qty-btn" 
                                                            title="Kurangi jumlah"
                                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $prod->stock }}" 
                                                           class="cart-qty-input" 
                                                           onchange="this.form.submit()"
                                                           title="Ketik jumlah pesanan">
                                                    
                                                    <button type="submit" name="quantity" value="{{ min($prod->stock, $item['quantity'] + 1) }}" 
                                                            class="cart-qty-btn" 
                                                            title="Tambah jumlah"
                                                            {{ $item['quantity'] >= $prod->stock ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                                
                                                <div class="small text-muted font-monospace mt-1.5" style="font-size: 0.7rem; letter-spacing: 0.2px;">
                                                    Stok: {{ $prod->stock }} unit
                                                </div>
                                            </form>
                                        </td>

                                        <!-- Subtotal (Single Line, Right-Aligned) -->
                                        <td class="text-end px-3 font-monospace fw-bold" style="white-space: nowrap; color: var(--colors-accent); font-size: 0.95rem;">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </td>

                                        <!-- Remove Item Button -->
                                        <td class="text-center pe-4">
                                            <form action="{{ route('customer.cart.remove', $prod) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  data-confirm="Hapus {{ $prod->name }} dari keranjang belanja Anda?"
                                                  data-confirm-title="Hapus dari Keranjang?"
                                                  data-confirm-item="{{ $prod->name }}"
                                                  data-confirm-type="danger"
                                                  data-confirm-icon="bi-cart-x"
                                                  data-confirm-btn="Ya, Hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm p-1.5 px-2.5 d-inline-flex align-items-center justify-content-center" title="Hapus dari keranjang" style="border-radius: 8px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cart Footer Actions -->
                    <div class="p-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2 border-top" style="border-color: var(--colors-hairline) !important; background: rgba(0,0,0,0.15);">
                        <a href="{{ route('customer.catalog') }}" class="btn btn-glass-outline btn-sm font-monospace text-uppercase py-2 px-3">
                            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                        </a>
                        <form action="{{ route('customer.cart.clear') }}" 
                              method="POST"
                              data-confirm="Seluruh barang di dalam keranjang belanja akan dikosongkan."
                              data-confirm-title="Kosongkan Keranjang Belanja?"
                              data-confirm-type="danger"
                              data-confirm-icon="bi-cart-x-fill"
                              data-confirm-btn="Ya, Kosongkan">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm font-monospace text-uppercase py-2 px-3">
                                <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary & Checkout Card -->
            <div class="col-12 col-lg-4">
                <div class="card p-4 position-sticky" style="top: 85px; background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-top: 3px solid var(--colors-accent) !important; border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <i class="bi bi-receipt-cutoff fs-5" style="color: var(--colors-accent);"></i>
                        <h5 class="fw-bold text-white mb-0">Ringkasan Pesanan</h5>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 small font-monospace">
                        <span class="text-secondary">Subtotal Barang:</span>
                        <span class="text-white fw-semibold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 small font-monospace">
                        <span class="text-secondary">Total Unit:</span>
                        <span class="text-white fw-semibold">{{ array_sum(array_column($cartItems, 'quantity')) }} unit</span>
                    </div>

                    <!-- Voucher & Promo Code Input Column -->
                    <div class="mb-3">
                        @if(!empty($promoCode))
                            <div class="p-2.5 px-3 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(16, 185, 129, 0.12); border: 1px dashed rgba(16, 185, 129, 0.45);">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-tag-fill text-success fs-5"></i>
                                    <div>
                                        <div class="font-monospace fw-bold text-white small">{{ $promoCode }}</div>
                                        <div class="text-success small font-monospace" style="font-size: 0.72rem;">Hemat Rp {{ number_format($discountAmount, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                <form action="{{ route('customer.cart.remove-promo') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2 d-flex align-items-center gap-1" title="Hapus voucher" style="font-size: 0.72rem; border-radius: 6px;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('customer.cart.apply-promo') }}" method="POST" class="mb-1">
                                @csrf
                                <label class="form-label small text-secondary font-monospace mb-1.5 d-flex align-items-center gap-1">
                                    <i class="bi bi-ticket-perforated" style="color: var(--colors-accent);"></i>KODE PROMO / VOUCHER:
                                </label>
                                <div class="d-flex align-items-stretch" style="border: 1px solid var(--colors-hairline); border-radius: 12px; overflow: hidden; background: rgba(13, 12, 29, 0.6); height: 44px;">
                                    <div class="d-flex align-items-center ps-3 pe-2 text-secondary">
                                        <i class="bi bi-tag-fill" style="color: var(--colors-accent); font-size: 0.9rem;"></i>
                                    </div>
                                    <input type="text" 
                                           name="promo_code" 
                                           id="promoInput" 
                                           class="form-control font-monospace text-uppercase border-0 shadow-none m-0 p-0 text-white" 
                                           placeholder="CONTOH: GAJIAN15" 
                                           required 
                                           autocomplete="off"
                                           style="background: transparent !important; height: 100%; font-size: 0.85rem; border-radius: 0 !important; letter-spacing: 0.5px;">
                                    <button type="submit" 
                                            class="btn font-monospace text-uppercase fw-bold text-white px-3 d-flex align-items-center justify-content-center" 
                                            style="border: none; border-left: 1px solid var(--colors-hairline); border-radius: 0; background: rgba(71, 73, 115, 0.35); font-size: 0.78rem; letter-spacing: 0.5px; white-space: nowrap; transition: all 0.2s ease;"
                                            onmouseover="this.style.background='rgba(71, 73, 115, 0.65)'"
                                            onmouseout="this.style.background='rgba(71, 73, 115, 0.35)'">
                                        Terapkan
                                    </button>
                                </div>
                                <div class="text-secondary small font-monospace mt-2 d-flex align-items-center gap-1.5 flex-wrap" style="font-size: 0.72rem;">
                                    <span class="text-muted">Kupon:</span> 
                                    <button type="button" onclick="fillPromo('GAJIAN15')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">GAJIAN15</button>
                                    <button type="button" onclick="fillPromo('GEAR50K')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">GEAR50K</button>
                                    <button type="button" onclick="fillPromo('CTRLR10')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">CTRLR10</button>
                                </div>
                            </form>
                        @endif
                    </div>

                    @if($discountAmount > 0)
                        <div class="d-flex justify-content-between align-items-center mb-2 small font-monospace">
                            <span class="text-success">Diskon Voucher ({{ $promoCode }}):</span>
                            <span class="text-success fw-bold">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="p-3 rounded-3 mb-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="text-secondary small font-monospace">TOTAL TAGIHAN:</span>
                            <span class="fs-4 fw-bold font-monospace" style="color: var(--colors-accent);">
                                Rp {{ number_format($finalTotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="small text-muted font-monospace mt-1" style="font-size: 0.72rem;">
                            <i class="bi bi-shield-check text-success me-1"></i>Harga resmi, stok langsung terverifikasi otomatis.
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <form action="{{ route('customer.checkout') }}" method="POST">
                        @csrf
                        @foreach($cartItems as $idx => $item)
                            <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item['product']->id }}">
                            <input type="hidden" name="items[{{ $idx }}][quantity]" value="{{ $item['quantity'] }}">
                        @endforeach
                        @if(!empty($promoCode))
                            <input type="hidden" name="promo_code" value="{{ $promoCode }}">
                        @endif

                        <!-- Payment Method Choice (Midtrans vs COD) -->
                        <div class="mb-3">
                            <label class="form-label small text-secondary font-monospace mb-2 d-flex align-items-center gap-1">
                                <i class="bi bi-wallet2" style="color: var(--colors-accent);"></i>PILIH METODE PEMBAYARAN:
                            </label>
                            <div class="d-flex flex-column gap-2">
                                <label class="rounded-3 d-flex align-items-center justify-content-between cursor-pointer" style="padding: 12px 14px; background: rgba(13, 12, 29, 0.6); border: 1px solid rgba(166, 156, 172, 0.25); border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <input type="radio" name="payment_method" value="midtrans" class="form-check-input mt-0" checked onchange="handlePaymentMethodChange(this.value)" style="width: 1.15rem; height: 1.15rem; cursor: pointer;">
                                        <div>
                                            <div class="fw-semibold text-white small font-monospace">Midtrans Snap (Online)</div>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.7rem;">QRIS, Virtual Account, GoPay, Kartu</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.65rem; padding: 4px 8px;">Instan</span>
                                </label>

                                <label class="rounded-3 d-flex align-items-center justify-content-between cursor-pointer" style="padding: 12px 14px; background: rgba(13, 12, 29, 0.6); border: 1px solid rgba(166, 156, 172, 0.25); border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <input type="radio" name="payment_method" value="cod" class="form-check-input mt-0" onchange="handlePaymentMethodChange(this.value)" style="width: 1.15rem; height: 1.15rem; cursor: pointer;">
                                        <div>
                                            <div class="fw-semibold text-white small font-monospace">COD (Bayar di Tempat)</div>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.7rem;">Bayar tunai kepada kurir saat pesanan tiba</div>
                                        </div>
                                    </div>
                                    <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 0.65rem; padding: 4px 8px;">Tunai COD</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cartNotes" class="form-label small text-secondary font-monospace">Catatan Pesanan (Opsional):</label>
                            <textarea name="notes" id="cartNotes" rows="2" class="form-control small font-monospace" style="height: auto !important; min-height: 62px; background: rgba(13, 12, 29, 0.6); border: 1px solid var(--colors-hairline); color: #fff; resize: vertical; padding: 10px 14px; font-size: 0.85rem; border-radius: 12px;" placeholder="Tulis catatan pengiriman atau permintaan khusus..."></textarea>
                        </div>

                        <button type="submit" id="checkoutBtn" class="btn btn-primary w-100 font-monospace text-uppercase fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-size: 0.92rem; height: 48px;">
                            <i class="bi bi-credit-card-2-front" id="checkoutBtnIcon"></i>
                            <span id="checkoutBtnText">Lanjut ke Pembayaran Midtrans</span>
                        </button>
                    </form>

                    <script>
                        function fillPromo(code) {
                            const input = document.getElementById('promoInput');
                            if (input) {
                                input.value = code;
                            }
                        }

                        function handlePaymentMethodChange(method) {
                            const btnText = document.getElementById('checkoutBtnText');
                            const btnIcon = document.getElementById('checkoutBtnIcon');
                            if (method === 'cod') {
                                btnText.textContent = 'Konfirmasi Pesanan COD (Bayar di Tempat)';
                                btnIcon.className = 'bi bi-cash-stack';
                            } else {
                                btnText.textContent = 'Lanjut ke Pembayaran Midtrans';
                                btnIcon.className = 'bi bi-credit-card-2-front';
                            }
                        }
                    </script>

                    <div class="mt-3 text-center small text-muted font-monospace" style="font-size: 0.75rem;">
                        <i class="bi bi-lock me-1"></i>Transaksi aman dienkripsi Midtrans Payment Gateway
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="card p-5 text-center my-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 20px;">
            <div class="d-inline-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle" style="width: 80px; height: 80px; background: rgba(241, 218, 196, 0.1); border: 1px solid rgba(241, 218, 196, 0.25); color: var(--colors-accent);">
                <i class="bi bi-cart-x fs-1"></i>
            </div>
            <h4 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">Keranjang Belanja Anda Masih Kosong</h4>
            <p class="text-secondary small font-monospace mx-auto mb-4" style="max-width: 480px;">
                Belum ada produk yang Anda tambahkan ke keranjang. Jelajahi katalog hardware & gear turnamen resmi kami dan temukan penawaran terbaik.
            </p>
            <div>
                <a href="{{ route('customer.catalog') }}" class="btn btn-primary px-4 py-2.5 font-monospace text-uppercase fw-bold" style="border-radius: 12px;">
                    <i class="bi bi-shop me-1.5"></i> Jelajahi Katalog Produk &rarr;
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
