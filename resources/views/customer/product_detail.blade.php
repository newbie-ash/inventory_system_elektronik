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
                <h2 class="text-white mb-2" style="font-family: 'Monda', sans-serif; letter-spacing: -0.96px;">{{ $product->name }}</h2>

                @php
                    $avgRating = $product->averageRating();
                    $reviewCount = $product->reviewsCount();
                @endphp
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($reviewCount > 0)
                        <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.35);">
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.85rem;"></i>
                            <span class="text-white fw-bold font-monospace" style="font-size: 0.88rem;">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-secondary small font-monospace">/ 5.0</span>
                        </div>
                        <span class="text-secondary small font-monospace">&bull;</span>
                        <a href="#ulasan-section" class="text-secondary small font-monospace text-decoration-none">
                            <i class="bi bi-chat-left-text me-1"></i>{{ $reviewCount }} Ulasan Pembeli
                        </a>
                    @else
                        <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill" style="background: rgba(166, 156, 172, 0.1); border: 1px solid rgba(166, 156, 172, 0.2);">
                            <i class="bi bi-star text-secondary" style="font-size: 0.85rem;"></i>
                            <span class="text-secondary small font-monospace">Belum ada penilaian</span>
                        </div>
                    @endif
                </div>

                <!-- Product Visual -->
                <div class="mb-4 d-flex align-items-center justify-content-center overflow-hidden" style="height: 240px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: var(--colors-accent);">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-contain">
                    @elseif(str_contains(strtolower($product->name), 'laptop'))
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

                        <!-- Kuantitas Pembelian (Seamless Unified Stepper) -->
                        <div class="mb-3">
                            <label class="form-label small font-monospace fw-semibold text-secondary d-flex align-items-center justify-content-between mb-1.5">
                                <span>KUANTITAS PEMBELIAN</span>
                                <span class="text-secondary" style="font-size: 0.72rem;">Maksimal: {{ $product->stock }} unit</span>
                            </label>
                            <div class="d-flex align-items-stretch" style="border: 1px solid var(--colors-hairline); border-radius: 12px; overflow: hidden; background: rgba(13, 12, 29, 0.6); height: 46px;">
                                <button type="button" 
                                        class="btn d-flex align-items-center justify-content-center text-white px-3" 
                                        onclick="decQty()" 
                                        style="width: 52px; background: rgba(71, 73, 115, 0.25); border: none; border-right: 1px solid var(--colors-hairline); border-radius: 0; font-size: 1.15rem; line-height: 1; transition: background 0.15s ease;" 
                                        onmouseover="this.style.background='rgba(71, 73, 115, 0.5)'" 
                                        onmouseout="this.style.background='rgba(71, 73, 115, 0.25)'" 
                                        title="Kurangi Kuantitas">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" 
                                       name="items[0][quantity]" 
                                       id="qtyInput" 
                                       class="form-control text-center font-monospace fw-bold text-white border-0 shadow-none m-0 p-0" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       required 
                                       style="background: transparent !important; height: 100%; font-size: 1.15rem; border-radius: 0 !important;">
                                <button type="button" 
                                        class="btn d-flex align-items-center justify-content-center text-white px-3" 
                                        onclick="incQty()" 
                                        style="width: 52px; background: rgba(71, 73, 115, 0.25); border: none; border-left: 1px solid var(--colors-hairline); border-radius: 0; font-size: 1.15rem; line-height: 1; transition: background 0.15s ease;" 
                                        onmouseover="this.style.background='rgba(71, 73, 115, 0.5)'" 
                                        onmouseout="this.style.background='rgba(71, 73, 115, 0.25)'" 
                                        title="Tambah Kuantitas">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Catatan Pengiriman -->
                        <div class="mb-3">
                            <label class="form-label small font-monospace fw-semibold text-secondary mb-1.5">CATATAN PENGIRIMAN</label>
                            <textarea name="notes" class="form-control font-monospace" rows="2" placeholder="Contoh: Packing kayu / asuransi pengiriman" style="height: auto !important; min-height: 62px; resize: vertical; padding: 10px 14px; font-size: 0.85rem; line-height: 1.4; border-radius: 12px;"></textarea>
                        </div>

                        <!-- Pilih Metode Pembayaran (Midtrans / COD) -->
                        <div class="mb-3">
                            <label class="form-label small font-monospace fw-semibold text-secondary mb-2 d-flex align-items-center gap-1.5">
                                <i class="bi bi-wallet2" style="color: var(--colors-accent);"></i>PILIH METODE PEMBAYARAN:
                            </label>
                            <div class="d-flex flex-column gap-2">
                                <label class="rounded-3 d-flex align-items-center justify-content-between cursor-pointer payment-method-card" style="padding: 12px 14px; background: rgba(13, 12, 29, 0.6); border: 1px solid rgba(166, 156, 172, 0.25); border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <input type="radio" name="payment_method" value="midtrans" class="form-check-input mt-0" checked onchange="updateDirectBuyButton(this.value)" style="width: 1.15rem; height: 1.15rem; cursor: pointer;">
                                        <div>
                                            <div class="fw-semibold text-white small font-monospace">Midtrans Snap (Online)</div>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.7rem;">QRIS, Virtual Account, GoPay, Kartu</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.65rem; padding: 4px 8px;">Instan</span>
                                </label>

                                <label class="rounded-3 d-flex align-items-center justify-content-between cursor-pointer payment-method-card" style="padding: 12px 14px; background: rgba(13, 12, 29, 0.6); border: 1px solid rgba(166, 156, 172, 0.25); border-radius: 12px; cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <input type="radio" name="payment_method" value="cod" class="form-check-input mt-0" onchange="updateDirectBuyButton(this.value)" style="width: 1.15rem; height: 1.15rem; cursor: pointer;">
                                        <div>
                                            <div class="fw-semibold text-white small font-monospace">COD (Bayar di Tempat)</div>
                                            <div class="text-secondary small font-monospace" style="font-size: 0.7rem;">Bayar tunai ke kurir saat paket tiba</div>
                                        </div>
                                    </div>
                                    <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 0.65rem; padding: 4px 8px;">Tunai COD</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kode Promo / Voucher (Seamless Unified Input Group) -->
                        <div class="mb-3">
                            <label for="directPromoInput" class="form-label small font-monospace fw-semibold text-secondary mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="bi bi-ticket-perforated" style="color: var(--colors-accent);"></i>KODE PROMO / VOUCHER (OPSIONAL):
                            </label>
                            <div class="d-flex align-items-stretch" style="border: 1px solid var(--colors-hairline); border-radius: 12px; overflow: hidden; background: rgba(13, 12, 29, 0.6); height: 44px;">
                                <div class="d-flex align-items-center ps-3 pe-2 text-secondary">
                                    <i class="bi bi-tag-fill" style="color: var(--colors-accent); font-size: 0.9rem;"></i>
                                </div>
                                <input type="text" 
                                       name="promo_code" 
                                       id="directPromoInput" 
                                       class="form-control font-monospace text-uppercase border-0 shadow-none m-0 p-0 text-white" 
                                       placeholder="CONTOH: GAJIAN15" 
                                       autocomplete="off"
                                       style="background: transparent !important; height: 100%; font-size: 0.85rem; border-radius: 0 !important; letter-spacing: 0.5px;">
                                <button type="button" 
                                        class="btn font-monospace text-uppercase fw-bold text-white px-3 d-flex align-items-center justify-content-center" 
                                        onclick="checkDirectPromo()" 
                                        style="border: none; border-left: 1px solid var(--colors-hairline); border-radius: 0; background: rgba(71, 73, 115, 0.35); font-size: 0.78rem; letter-spacing: 0.5px; white-space: nowrap; transition: all 0.2s ease;"
                                        onmouseover="this.style.background='rgba(71, 73, 115, 0.65)'"
                                        onmouseout="this.style.background='rgba(71, 73, 115, 0.35)'">
                                    Terapkan
                                </button>
                            </div>
                            <div id="promoNoticeBox" class="mt-1.5 small font-monospace" style="display: none; font-size: 0.75rem;"></div>
                            <div class="text-secondary small font-monospace mt-2 d-flex align-items-center gap-1.5 flex-wrap" style="font-size: 0.72rem;">
                                <span class="text-muted">Kupon:</span> 
                                <button type="button" onclick="applyDirectCoupon('GAJIAN15')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">GAJIAN15</button>
                                <button type="button" onclick="applyDirectCoupon('GEAR50K')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">GEAR50K</button>
                                <button type="button" onclick="applyDirectCoupon('CTRLR10')" class="badge border-0 font-monospace cursor-pointer py-1 px-2" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); text-decoration: none;">CTRLR10</button>
                            </div>
                        </div>

                        <!-- Subtotal & Total Bayar Breakdown -->
                        <div class="p-3 mb-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); border-radius: 12px;">
                            <div class="d-flex justify-content-between align-items-center small text-secondary font-monospace mb-1.5">
                                <span>Subtotal:</span>
                                <span class="fw-bold text-white font-monospace" id="subtotalDisplay">Rp {{ number_format($price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small font-monospace mb-1.5" id="discountRow" style="display: none !important;">
                                <span class="text-success" id="discountLabel">Diskon Kupon:</span>
                                <span class="text-success fw-bold font-monospace" id="discountDisplay">- Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small text-secondary font-monospace mb-2">
                                <span>Metode Dipilih:</span>
                                <span class="badge font-monospace" id="selectedMethodBadge" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); border: 1px solid var(--colors-accent); font-size: 0.72rem; padding: 4px 8px;">Midtrans Snap</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small font-monospace pt-2.5 border-top" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                                <span class="fw-bold text-white">Total Bayar:</span>
                                <span class="fw-bold fs-6 font-monospace" id="totalDisplay" style="color: var(--colors-accent);">Rp {{ number_format($price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" id="directBuyBtn" class="btn btn-primary w-100 font-monospace text-uppercase fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-size: 0.92rem; height: 48px;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-credit-card me-1" id="directBuyIcon"></i>
                                <span id="directBuyText">Beli via Midtrans Snap &rarr;</span>
                            </button>
                            <button type="submit" formaction="{{ route('customer.cart.add') }}" class="btn btn-glass-outline w-100 font-monospace text-uppercase fw-bold d-flex align-items-center justify-content-center gap-2" style="border: 1.5px solid var(--colors-accent) !important; color: var(--colors-accent) !important; background: rgba(71, 73, 115, 0.25) !important; border-radius: 12px; font-size: 0.92rem; height: 48px;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus me-1 fs-5"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews Section (Ulasan & Penilaian) -->
    <div class="card p-4 mb-4" id="ulasan-section" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 18px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3" style="border-bottom: 1px solid var(--colors-hairline);">
            <div>
                <h5 class="fw-bold text-white font-monospace d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span>Penilaian & Ulasan Pembeli</span>
                </h5>
                <p class="text-secondary small font-monospace mb-0">Ulasan asli dari pembeli yang telah menerima barang di Ctrl+R.</p>
            </div>

            <!-- Score Summary Badge -->
            <div class="d-flex align-items-center gap-3 p-2.5 px-3 rounded-3" style="background: rgba(13, 12, 29, 0.6); border: 1px solid rgba(166, 156, 172, 0.18);">
                <div class="fs-2 fw-bold font-monospace text-warning lh-1">{{ number_format($avgRating, 1) }}</div>
                <div>
                    <div class="d-flex align-items-center gap-1 text-warning mb-0.5" style="font-size: 0.9rem;">
                        @for($s = 1; $s <= 5; $s++)
                            <i class="bi {{ $avgRating >= $s ? 'bi-star-fill' : ($avgRating >= $s - 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                        @endfor
                    </div>
                    <div class="text-secondary small font-monospace" style="font-size: 0.72rem;">{{ $reviewCount }} total ulasan</div>
                </div>
            </div>
        </div>

        <!-- Write Review Form (For Verified Buyers) -->
        @if($userCanReview)
            <div class="card p-3.5 mb-4" style="background: rgba(22, 27, 51, 0.6); border: 1px solid rgba(241, 218, 196, 0.25); border-radius: 14px;">
                <h6 class="text-white fw-bold font-monospace mb-2 d-flex align-items-center gap-2" style="font-size: 0.92rem;">
                    <i class="bi bi-pencil-square" style="color: var(--colors-accent);"></i>
                    <span>{{ $existingReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan Produk Ini' }}</span>
                    <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 0.65rem;">Pembeli Terverifikasi</span>
                </h6>

                <form action="{{ route('customer.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if($userDeliveredSale)
                        <input type="hidden" name="sale_id" value="{{ $userDeliveredSale->id }}">
                    @endif

                    <div class="mb-3">
                        <label class="form-label small font-monospace text-secondary mb-1">Rating Kepuasan (1 - 5 Bintang):</label>
                        <div class="d-flex align-items-center gap-2 star-picker-detail">
                            @php $curVal = $existingReview ? $existingReview->rating : 5; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <label style="cursor: pointer; margin: 0;">
                                    <input type="radio" name="rating" value="{{ $i }}" class="d-none star-detail-radio" {{ $curVal == $i ? 'checked' : '' }} required>
                                    <i class="bi {{ $curVal >= $i ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }} fs-4 star-detail-icon" data-value="{{ $i }}"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea name="comment" rows="2" class="form-control font-monospace small" placeholder="Bagikan pengalaman Anda tentang performa, kualitas bahan, dan keandalan produk ini..." style="background: rgba(13, 12, 29, 0.7); border: 1px solid rgba(166, 156, 172, 0.25); color: #FFFFFF; font-size: 0.85rem; border-radius: 10px;">{{ $existingReview ? $existingReview->comment : '' }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary font-monospace px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="border-radius: 10px;">
                            <i class="bi bi-send-fill"></i>
                            <span>{{ $existingReview ? 'Simpan Perubahan' : 'Kirim Ulasan' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Reviews List -->
        <div class="d-flex flex-column gap-3">
            @forelse($product->reviews as $rev)
                <div class="p-3 rounded-3" style="background: rgba(13, 12, 29, 0.4); border: 1px solid rgba(166, 156, 172, 0.12);">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark font-monospace" style="width: 34px; height: 34px; background: linear-gradient(135deg, var(--colors-accent) 0%, #ebd0b4 100%); font-size: 0.82rem;">
                                {{ strtoupper(substr($rev->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-white small d-flex align-items-center gap-2">
                                    <span>{{ $rev->user->name ?? 'Pembeli' }}</span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace" style="font-size: 0.62rem;">
                                        <i class="bi bi-patch-check-fill me-0.5"></i> Pembeli Terverifikasi
                                    </span>
                                </div>
                                <div class="text-secondary font-monospace" style="font-size: 0.7rem;">
                                    {{ $rev->created_at->translatedFormat('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Star Rating -->
                        <div class="d-flex align-items-center gap-1 text-warning" style="font-size: 0.85rem;">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="bi {{ $rev->rating >= $s ? 'bi-star-fill' : 'bi-star text-secondary' }}"></i>
                            @endfor
                        </div>
                    </div>

                    @if($rev->comment)
                        <p class="text-white small mb-0 lh-base font-monospace" style="font-size: 0.84rem; opacity: 0.95;">
                            {{ $rev->comment }}
                        </p>
                    @endif
                </div>
            @empty
                <div class="text-center py-4 text-secondary font-monospace">
                    <i class="bi bi-chat-square-quote fs-2 text-muted d-block mb-2"></i>
                    <span class="text-white d-block mb-1">Belum Ada Ulasan Pembeli</span>
                    <span class="small text-secondary">Jadilah pembeli pertama yang memberikan rating dan ulasan untuk {{ $product->name }}.</span>
                </div>
            @endforelse
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

    const activePromos = {
        'GAJIAN15': { type: 'percentage', value: 15, min_spend: 1500000, max_discount: 500000, title: 'Diskon 15% Spesial Gajian' },
        'GEAR50K': { type: 'fixed', value: 50000, min_spend: 300000, max_discount: 50000, title: 'Potongan Rp 50.000' },
        'ONGKIRFREE': { type: 'fixed', value: 40000, min_spend: 500000, max_discount: 40000, title: 'Gratis Ongkos Kirim' },
        'CTRLR10': { type: 'percentage', value: 10, min_spend: 200000, max_discount: 250000, title: 'Diskon 10% Member Ctrl+R' }
    };

    function applyDirectCoupon(code) {
        const input = document.getElementById('directPromoInput');
        if (input) {
            input.value = code;
            checkDirectPromo();
        }
    }

    function checkDirectPromo() {
        const input = document.getElementById('directPromoInput');
        const box = document.getElementById('promoNoticeBox');
        const discountRow = document.getElementById('discountRow');
        const discountLabel = document.getElementById('discountLabel');
        const discountDisplay = document.getElementById('discountDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        if (!input) return;

        let qty = parseInt(qtyInput.value) || 1;
        let subtotal = qty * unitPrice;
        let code = (input.value || '').trim().toUpperCase();

        if (!code) {
            if (box) box.style.display = 'none';
            if (discountRow) discountRow.style.setProperty('display', 'none', 'important');
            if (totalDisplay) totalDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            return;
        }

        const promo = activePromos[code];
        if (!promo) {
            if (box) {
                box.style.display = 'block';
                box.style.color = '#F87171';
                box.innerHTML = '<i class="bi bi-x-circle me-1"></i>Kode kupon "' + code + '" tidak ditemukan.';
            }
            if (discountRow) discountRow.style.setProperty('display', 'none', 'important');
            if (totalDisplay) totalDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            return;
        }

        if (subtotal < promo.min_spend) {
            if (box) {
                box.style.display = 'block';
                box.style.color = '#FBBF24';
                box.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Min. belanja Rp ' + promo.min_spend.toLocaleString('id-ID') + ' untuk menggunakan kupon ini.';
            }
            if (discountRow) discountRow.style.setProperty('display', 'none', 'important');
            if (totalDisplay) totalDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            return;
        }

        let discount = 0;
        if (promo.type === 'percentage') {
            discount = (subtotal * promo.value) / 100;
            if (promo.max_discount && discount > promo.max_discount) {
                discount = promo.max_discount;
            }
        } else {
            discount = promo.value;
        }
        if (discount > subtotal) discount = subtotal;

        let finalTotal = Math.max(0, subtotal - discount);

        if (box) {
            box.style.display = 'block';
            box.style.color = '#34D399';
            box.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Kupon aktif: ' + promo.title + ' (-Rp ' + discount.toLocaleString('id-ID') + ')';
        }

        if (discountRow) {
            discountRow.style.removeProperty('display');
            discountRow.style.display = 'flex';
        }
        if (discountLabel) {
            discountLabel.innerText = 'Diskon Kupon (' + code + '):';
        }
        if (discountDisplay) {
            discountDisplay.innerText = '- Rp ' + discount.toLocaleString('id-ID');
        }
        if (totalDisplay) {
            totalDisplay.innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
        }
    }

    function updateSubtotal() {
        let qty = parseInt(qtyInput.value) || 1;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        qtyInput.value = qty;
        let subtotal = qty * unitPrice;
        if (subtotalDisplay) {
            subtotalDisplay.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
        checkDirectPromo();
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

    const promoInputField = document.getElementById('directPromoInput');
    if (promoInputField) {
        promoInputField.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                checkDirectPromo();
            }
        });
    }

    function updateDirectBuyButton(method) {
        const btnText = document.getElementById('directBuyText');
        const btnIcon = document.getElementById('directBuyIcon');
        const badge = document.getElementById('selectedMethodBadge');
        if (method === 'cod') {
            if (btnText) btnText.textContent = 'Pesan via COD (Bayar di Tempat) \u2192';
            if (btnIcon) btnIcon.className = 'bi bi-cash-stack me-1';
            if (badge) {
                badge.textContent = 'COD (Bayar di Tempat)';
                badge.style.background = 'rgba(16, 185, 129, 0.2)';
                badge.style.color = '#34D399';
                badge.style.borderColor = 'rgba(16, 185, 129, 0.4)';
            }
        } else {
            if (btnText) btnText.textContent = 'Beli via Midtrans Snap \u2192';
            if (btnIcon) btnIcon.className = 'bi bi-credit-card me-1';
            if (badge) {
                badge.textContent = 'Midtrans Snap';
                badge.style.background = 'rgba(255, 0, 61, 0.15)';
                badge.style.color = 'var(--colors-accent)';
                badge.style.borderColor = 'var(--colors-accent)';
            }
        }
    }
</script>
@endpush
