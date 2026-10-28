@extends('layouts.app')

@section('title', 'Promo & Diskon Eksklusif - Ctrl+R')
@section('page_heading', 'Promo & Voucher')

@push('styles')
<style>
    /* Hero Promo Banner (Glassmorphism) */
    .promo-hero-card {
        background: rgba(255, 255, 255, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(24px) saturate(190%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
        border-radius: 24px !important;
        box-shadow: 0 10px 30px -4px rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
        min-height: 220px;
        padding: 2.5rem 2.25rem !important;
        position: relative;
        overflow: hidden;
    }

    .promo-hero-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 45%;
        background: radial-gradient(circle at 75% 50%, rgba(26, 26, 28, 0.04) 0%, transparent 65%);
        pointer-events: none;
    }

    .countdown-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFFFFF;
        border: 1px solid var(--colors-hairline);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.8rem;
        color: var(--colors-text);
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    /* Ticket-Style Voucher Cards (Glassmorphism) */
    .voucher-card {
        background: rgba(255, 255, 255, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.65) !important;
        border-radius: 20px !important;
        box-shadow: 0 8px 24px -2px rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(24px) saturate(190%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .voucher-card:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.58) !important;
        border-color: rgba(26, 26, 28, 0.25) !important;
        box-shadow: 0 14px 32px -4px rgba(28, 26, 24, 0.08), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
    }

    .voucher-discount-pill {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--colors-accent);
        letter-spacing: -0.5px;
    }

    .voucher-code-box {
        background: var(--colors-card-alt);
        border: 1px dashed var(--colors-hairline);
        border-radius: 10px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .btn-copy-code {
        background: #FFFFFF;
        border: 1px solid var(--colors-hairline);
        color: var(--colors-text);
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-copy-code:hover {
        background: var(--colors-accent);
        color: var(--colors-accent-text);
    }

    /* Product Cards in Promo Grid */
    .promo-product-card {
        background: #FFFFFF !important;
        border: 1px solid var(--colors-hairline) !important;
        border-radius: 18px !important;
        backdrop-filter: blur(8px);
        padding: 14px !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 4px 18px -2px rgba(28, 26, 24, 0.05) !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .promo-product-card:hover {
        transform: translateY(-5px);
        border-color: #D8D2C4 !important;
        box-shadow: 0 10px 24px -4px rgba(28, 26, 24, 0.1) !important;
    }

    .product-visual-box {
        background: var(--colors-card-alt);
        border: 1px solid var(--colors-hairline);
        border-radius: 14px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 0.85rem;
    }

    .badge-discount-tag {
        position: absolute;
        top: 8px;
        left: 8px;
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        color: #FFFFFF;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    }

    /* Category Filter Pills */
    .promo-cat-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--colors-muted);
        background: #FFFFFF;
        border: 1px solid var(--colors-hairline);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .promo-cat-pill:hover {
        color: var(--colors-text);
        border-color: var(--colors-hairline);
        background: var(--colors-card-alt);
    }

    .promo-cat-pill.active {
        background: #1A1A1C;
        color: #FFFFFF;
        border-color: #1A1A1C;
        box-shadow: 0 2px 8px rgba(26, 26, 28, 0.2);
    }

    /* Copy Toast */
    #copyToast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #FFFFFF;
        border: 1px solid var(--colors-hairline);
        color: var(--colors-text);
        border-radius: 14px;
        padding: 12px 20px;
        box-shadow: 0 8px 24px rgba(28, 26, 24, 0.15);
        backdrop-filter: blur(12px);
        z-index: 9999;
        display: none;
        animation: fadeIn 0.25s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 1380px;">

    <!-- 1. Hero Promo Banner (Elongated downward) -->
    <div class="card p-4 p-md-5 mb-4 promo-hero-card position-relative overflow-hidden d-flex flex-column justify-content-center">
        <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
            <div class="col-12 col-md-8 col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="font-monospace text-uppercase fw-semibold" style="color: var(--colors-muted); font-size: 0.82rem; letter-spacing: 0.8px;">
                        {{ $primaryBanner->tag ?? 'PROMO SPESIAL GAJIAN:' }}
                    </span>
                    <span class="countdown-pill">
                        <i class="bi bi-clock-history"></i>
                        <span>Flash Deal: 02 Hari 14 Jam</span>
                    </span>
                </div>
                <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.4rem, 2.6vw, 2.2rem); letter-spacing: -0.5px; line-height: 1.25;">
                    {{ $primaryBanner->title ?? 'Potongan hingga 15% untuk kategori PC & Gadget' }}
                </h2>
                <p class="text-white-50 small mb-3 mb-md-4" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                    {{ $primaryBanner->description ?? 'Gunakan kode voucher saat checkout untuk menikmati potongan harga langsung dan diskon ongkos kirim seluruh Indonesia.' }}
                </p>
                <div class="d-flex align-items-center gap-1.5 pt-1">
                    <span style="width: 26px; height: 5px; border-radius: 4px; background: var(--colors-accent); display: inline-block; box-shadow: 0 0 10px rgba(10, 132, 255, 0.3);"></span>
                    <span style="width: 18px; height: 5px; border-radius: 4px; background: rgba(142, 142, 147, 0.35); display: inline-block;"></span>
                    <span style="width: 18px; height: 5px; border-radius: 4px; background: rgba(142, 142, 147, 0.35); display: inline-block;"></span>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4 text-md-end">
                <a href="#daftar-voucher" class="btn btn-primary px-4 py-2.5 fw-bold" style="border-radius: 12px; font-size: 0.95rem;">
                    <i class="bi bi-ticket-perforated me-1"></i> Klaim Voucher Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Section: Kupon & Voucher Diskon Eksklusif -->
    <div class="mb-5" id="daftar-voucher">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold text-white mb-1" style="letter-spacing: -0.4px;">
                    <i class="bi bi-tag-fill me-2" style="color: var(--colors-accent);"></i>Kupon & Voucher Diskon
                </h4>
                <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                    Klik tombol salin pada kode voucher dan terapkan saat melakukan checkout belanja Anda.
                </p>
            </div>
            <span class="badge" style="background: var(--colors-card-alt); color: var(--colors-accent); border: 1px solid var(--colors-hairline);">
                {{ count($vouchers) }} Voucher Aktif
            </span>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($vouchers as $voucher)
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card p-3 p-xl-4 voucher-card h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: var(--colors-card-alt); color: var(--colors-accent); border: 1px solid var(--colors-hairline); font-size: 0.72rem;">
                                    {{ $voucher['badge'] }}
                                </span>
                                <small class="text-secondary" style="font-size: 0.72rem;">
                                    s/d {{ $voucher['valid_until'] }}
                                </small>
                            </div>
                            <div class="voucher-discount-pill mb-1">
                                {{ $voucher['discount'] }}
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 0.95rem;">
                                {{ $voucher['title'] }}
                            </h6>
                            <p class="small text-secondary mb-3" style="color: var(--colors-muted) !important; font-size: 0.78rem; line-height: 1.4;">
                                {{ $voucher['description'] }}
                            </p>
                        </div>

                        <div class="pt-2 border-top" style="border-color: var(--colors-hairline) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2 small text-secondary" style="font-size: 0.75rem;">
                                <span>Min. Belanja:</span>
                                <span class="text-white fw-bold">Rp {{ number_format($voucher['min_spend'], 0, ',', '.') }}</span>
                            </div>
                            <div class="voucher-code-box">
                                <span class="font-monospace fw-bold text-white" style="font-size: 0.88rem; letter-spacing: 0.5px;">
                                    {{ $voucher['code'] }}
                                </span>
                                <button type="button" class="btn-copy-code" onclick="copyVoucherCode('{{ $voucher['code'] }}', this)">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. Section: Flash Sale / Katalog Produk Berdiskon -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold text-white mb-1" style="letter-spacing: -0.4px;">
                    <i class="bi bi-fire me-2 text-danger"></i>Produk Pilihan Sedang Diskon
                </h4>
                <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                    Penawaran harga terbaik untuk perangkat keras, komputer, dan gaming gear unggulan.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm" style="font-size: 0.82rem;">
                Lihat Seluruh Katalog <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <!-- Category Filter Tabs -->
        <div class="d-flex align-items-center gap-2 overflow-x-auto no-scrollbar mb-4 pb-1">
            <a href="{{ route('customer.promo') }}" class="promo-cat-pill {{ !request('category_id') ? 'active' : '' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('customer.promo', ['category_id' => $cat->id]) }}" class="promo-cat-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">
                    {{ $cat->name }} ({{ $cat->products_count }})
                </a>
            @endforeach
        </div>

        <!-- Product Grid -->
        <div class="row g-3 g-md-4 mb-4">
            @forelse($products as $product)
                @php
                    $displayPrice = $product->selling_price > 0 ? $product->selling_price : $product->price;
                    $fakeOriginalPrice = round($displayPrice * 1.15);
                    $iconClass = 'bi-cpu';
                    $catName = strtolower($product->category->name ?? '');
                    if (str_contains($catName, 'laptop') || str_contains($catName, 'komputer')) {
                        $iconClass = 'bi-laptop';
                    } elseif (str_contains($catName, 'layar') || str_contains($catName, 'monitor')) {
                        $iconClass = 'bi-display';
                    } elseif (str_contains($catName, 'aksesoris') || str_contains($catName, 'mouse') || str_contains($catName, 'keyboard')) {
                        $iconClass = 'bi-mouse';
                    } elseif (str_contains($catName, 'jaringan') || str_contains($catName, 'kabel')) {
                        $iconClass = 'bi-router';
                    }
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card promo-product-card">
                        <div>
                            <div class="product-visual-box overflow-hidden">
                                <span class="badge-discount-tag">-15%</span>
                                @if ($product->image && file_exists(public_path($product->image)))
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                                @elseif ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi {{ $iconClass }}" style="font-size: 3.5rem; color: #1A1A1C; opacity: 0.85;"></i>
                                @endif
                            </div>

                            <span class="badge font-monospace small mb-1" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: var(--colors-muted); font-size: 0.72rem;">
                                {{ $product->category->name ?? 'Hardware' }}
                            </span>

                            <h6 class="fw-bold text-white mb-1 text-truncate" title="{{ $product->name }}" style="font-size: 0.92rem;">
                                {{ $product->name }}
                            </h6>

                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fw-bold" style="color: var(--colors-accent); font-size: 1.05rem;">
                                    Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                </span>
                                <del class="text-secondary small" style="font-size: 0.75rem;">
                                    Rp {{ number_format($fakeOriginalPrice, 0, ',', '.') }}
                                </del>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-color: var(--colors-hairline) !important;">
                            <span class="text-secondary small" style="font-size: 0.72rem;">
                                Stok: <strong class="text-white">{{ $product->stock }}</strong>
                            </span>
                            <div class="d-flex align-items-center gap-1.5">
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="btn btn-sm d-inline-flex align-items-center justify-content-center p-0" 
                                            style="height: 30px; width: 32px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: #1A1A1C; border-radius: 8px;"
                                            title="Tambah ke Keranjang"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                                <a href="{{ route('customer.catalog.show', $product) }}" class="btn btn-sm btn-primary px-3 py-1 fw-bold" style="font-size: 0.8rem; border-radius: 8px; height: 30px; display: inline-flex; align-items: center;">
                                    Beli
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    <p class="text-white mb-1">Belum ada produk promo untuk kategori ini.</p>
                    <a href="{{ route('customer.promo') }}" class="btn btn-primary btn-sm mt-2">Reset Filter Kategori</a>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="d-flex justify-content-center">
                {{ $products->links('vendor.pagination.buyer-slide') }}
            </div>
        @endif
    </div>

    <!-- 4. Section: Panduan Cara Menggunakan Voucher -->
    <div class="card p-4 mb-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 20px;">
        <h5 class="fw-bold text-white mb-3">
            <i class="bi bi-info-circle me-2" style="color: var(--colors-accent);"></i>Cara Menggunakan Kupon Diskon
        </h5>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; background: var(--colors-accent); color: var(--colors-accent-text); flex-shrink: 0;">
                        1
                    </div>
                    <div>
                        <h6 class="text-white fw-bold mb-1">Salin Kode Promo</h6>
                        <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                            Pilih promo yang sesuai dan klik tombol <strong>Salin</strong> pada kartu voucher di atas.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; background: var(--colors-accent); color: var(--colors-accent-text); flex-shrink: 0;">
                        2
                    </div>
                    <div>
                        <h6 class="text-white fw-bold mb-1">Pilih Produk & Checkout</h6>
                        <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                            Masukkan barang belanjaan Anda ke keranjang sesuai ketentuan minimum belanja promo.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; background: var(--colors-accent); color: var(--colors-accent-text); flex-shrink: 0;">
                        3
                    </div>
                    <div>
                        <h6 class="text-white fw-bold mb-1">Bayar via Midtrans</h6>
                        <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                            Tempelkan kode promo pada catatan pesanan lalu selesaikan pembayaran aman via Midtrans Snap.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Toast Notifikasi Tersalin -->
<div id="copyToast">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div>
            <div class="fw-bold" id="toastMessage">Kode Voucher Berhasil Disalin!</div>
            <small class="text-secondary" style="font-size: 11px;">Gunakan saat checkout pesanan Anda.</small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyVoucherCode(code, buttonElement) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                showToast(code);
                toggleButtonState(buttonElement);
            });
        } else {
            // Fallback for older browsers
            const tempInput = document.createElement('input');
            tempInput.value = code;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            showToast(code);
            toggleButtonState(buttonElement);
        }
    }

    function toggleButtonState(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
        btn.style.background = 'var(--colors-accent)';
        btn.style.color = 'var(--colors-accent-text)';

        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    }

    function showToast(code) {
        const toast = document.getElementById('copyToast');
        const msg = document.getElementById('toastMessage');
        if (toast && msg) {
            msg.textContent = `Kode "${code}" Berhasil Disalin!`;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2500);
        }
    }
</script>
@endpush
@endsection
