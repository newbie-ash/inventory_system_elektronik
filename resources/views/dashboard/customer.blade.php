@extends('layouts.app')

@section('title', 'Beranda & Katalog Gear - Ctrl+R')
@section('page_heading', 'Beranda')

@push('styles')
<style>
    /* Promo Banner Card */
    .promo-banner-card {
        background: linear-gradient(135deg, rgba(22, 27, 51, 0.98) 0%, rgba(71, 73, 115, 0.4) 55%, rgba(22, 27, 51, 0.98) 100%) !important;
        border: 1px solid rgba(166, 156, 172, 0.22) !important;
        border-radius: 20px !important;
        box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.5) !important;
        min-height: 140px;
    }

    .promo-banner-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 45%;
        background: radial-gradient(circle at 75% 50%, rgba(241, 218, 196, 0.12) 0%, transparent 65%);
        pointer-events: none;
    }

    .promo-tag {
        color: var(--colors-muted);
        font-size: 0.76rem;
        letter-spacing: 0.75px;
    }

    .carousel-dots .dot {
        width: 18px;
        height: 5px;
        border-radius: 4px;
        background: rgba(166, 156, 172, 0.35);
        display: inline-block;
        transition: all 0.2s ease;
    }

    .carousel-dots .dot.active {
        width: 26px;
        background: var(--colors-accent);
        box-shadow: 0 0 10px rgba(241, 218, 196, 0.4);
    }

    /* Left Sidebar Filter (Image 1 Style) */
    .filter-sidebar-card {
        background: linear-gradient(160deg, rgba(71, 73, 115, 0.22) 0%, rgba(22, 27, 51, 0.85) 100%) !important;
        border: 1px solid rgba(166, 156, 172, 0.22) !important;
        border-radius: 20px !important;
    }

    .filter-section-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.75rem;
        letter-spacing: -0.2px;
    }

    .filter-checkbox {
        background-color: rgba(13, 12, 29, 0.8) !important;
        border: 1px solid rgba(166, 156, 172, 0.35) !important;
        border-radius: 6px !important;
        cursor: pointer;
    }

    .filter-checkbox:checked {
        background-color: var(--colors-accent) !important;
        border-color: var(--colors-accent) !important;
    }

    /* Product Cards in Grid */
    .product-grid-card {
        background: linear-gradient(150deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.75) 100%) !important;
        border: 1px solid rgba(166, 156, 172, 0.22) !important;
        border-radius: 16px !important;
        padding: 12px !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 10px 24px -8px rgba(0, 0, 0, 0.45) !important;
    }

    .product-grid-card:hover {
        transform: translateY(-5px);
        border-color: rgba(241, 218, 196, 0.45) !important;
        box-shadow: 0 18px 36px -10px rgba(0, 0, 0, 0.65), 0 0 20px -4px rgba(241, 218, 196, 0.15) !important;
    }

    .product-visual-box {
        background: radial-gradient(circle at 50% 30%, rgba(71, 73, 115, 0.35) 0%, rgba(13, 12, 29, 0.85) 100%);
        border: 1px solid rgba(166, 156, 172, 0.16);
        border-radius: 12px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        color: var(--colors-accent);
        transition: all 0.25s ease;
    }

    .product-grid-card:hover .product-visual-box {
        border-color: rgba(241, 218, 196, 0.3);
    }

    .product-grid-card:hover .product-visual-box i {
        transform: scale(1.08);
        color: #FFFFFF;
    }

    .product-visual-box i {
        transition: transform 0.25s ease, color 0.25s ease;
    }

    /* Range slider custom track */
    .form-range::-webkit-slider-thumb {
        background: #FFFFFF;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }

    /* Slide Page Pagination (Image 2 Style) */
    .slide-pagination-wrap {
        margin: 2.5rem 0 1rem;
    }

    .slide-nav-btn {
        background: rgba(22, 27, 51, 0.7);
        border: 1px solid rgba(166, 156, 172, 0.25);
        color: #FFFFFF;
        font-size: 0.84rem;
        font-weight: 500;
        padding: 7px 16px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .slide-nav-btn:hover:not(.disabled) {
        background: rgba(71, 73, 115, 0.4);
        border-color: rgba(241, 218, 196, 0.4);
        color: var(--colors-accent);
        transform: translateY(-1px);
    }

    .slide-nav-btn.disabled {
        color: rgba(166, 156, 172, 0.35);
        border-color: rgba(166, 156, 172, 0.12);
        background: rgba(22, 27, 51, 0.35);
        cursor: not-allowed;
        pointer-events: none;
    }

    .slide-nav-page {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(22, 27, 51, 0.7);
        border: 1px solid rgba(166, 156, 172, 0.25);
        color: #FFFFFF;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .slide-nav-page:hover:not(.active) {
        background: rgba(71, 73, 115, 0.4);
        border-color: rgba(241, 218, 196, 0.4);
        color: var(--colors-accent);
        transform: translateY(-1px);
    }

    .slide-nav-page.active {
        background: rgba(71, 73, 115, 0.45);
        border: 1.5px solid rgba(255, 255, 255, 0.85);
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.25);
    }

    .slide-nav-dots {
        color: var(--colors-muted);
        padding: 0 4px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 1380px;">

    <!-- 1. Top Promo Banner (Home Layout Style) -->
    <div class="card p-4 mb-4 promo-banner-card position-relative overflow-hidden" id="promo">
        <div class="row align-items-center g-3 position-relative" style="z-index: 2;">
            <div class="col-12 col-md-8 col-lg-8">
                <span class="promo-tag text-uppercase fw-semibold mb-1 d-block">
                    PROMO SPESIAL GAJIAN:
                </span>
                <h2 class="fw-bold text-white mb-3" style="font-size: clamp(1.25rem, 2.3vw, 1.8rem); letter-spacing: -0.5px; line-height: 1.25;">
                    Potongan hingga 15% untuk kategori PC & Gadget
                </h2>
                <div class="d-flex align-items-center gap-1.5 carousel-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4 text-md-end">
                <a href="#katalog-grid" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                    Klaim Voucher
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Main 2-Column Grid (Left: Image 1 Filter Sidebar, Right: Full Catalog Grid) -->
    <div class="row g-4 mb-4" id="katalog-grid">
        <!-- Left Filter Column (Image 1 Style) -->
        <div class="col-12 col-lg-3 col-xl-3">
            <div class="card p-3 p-xl-4 filter-sidebar-card">
                <form action="{{ route('dashboard') }}" method="GET" id="filterSidebarForm">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <!-- Section: Kategori (Checkboxes) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Kategori</div>
                        <div class="d-flex flex-column gap-1.5">
                            @foreach($categories as $cat)
                                @php
                                    $isSelected = (request()->has('categories') && in_array($cat->id, (array) request('categories'))) || request('category_id') == $cat->id;
                                @endphp
                                <div class="form-check d-flex align-items-center gap-2 mb-1">
                                    <input class="form-check-input filter-checkbox m-0" 
                                           type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $cat->id }}" 
                                           id="cat_{{ $cat->id }}"
                                           {{ $isSelected ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="cat_{{ $cat->id }}" style="font-size: 0.86rem; cursor: pointer;">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section: Rentang Harga (Image 1 Style) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Rentang Harga</div>
                        <input type="range" 
                               class="form-range mb-2" 
                               min="500000" 
                               max="30000000" 
                               step="500000" 
                               id="priceSlider"
                               value="{{ request('max_price', 30000000) }}" 
                               oninput="updatePriceBoxes(this.value)">

                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <input type="text" 
                                   class="form-control form-control-sm text-center py-1 text-white font-monospace" 
                                   style="background: rgba(13, 12, 29, 0.7); border: 1px solid rgba(166, 156, 172, 0.2); font-size: 0.78rem; border-radius: 8px;" 
                                   value="Rp 500k" 
                                   readonly>
                            <span class="text-secondary">-</span>
                            <input type="text" 
                                   id="priceBoxMax"
                                   class="form-control form-control-sm text-center py-1 text-white font-monospace" 
                                   style="background: rgba(13, 12, 29, 0.7); border: 1px solid rgba(166, 156, 172, 0.2); font-size: 0.78rem; border-radius: 8px;" 
                                   value="Rp {{ number_format(request('max_price', 30000000)/1000000, 0) }}jt" 
                                   readonly>
                        </div>
                        <input type="hidden" name="min_price" value="500000">
                        <input type="hidden" name="max_price" id="maxPriceInput" value="{{ request('max_price', 30000000) }}">
                    </div>

                    <!-- Section: Kondisi (Image 1 Style) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Kondisi</div>
                        <div class="form-check d-flex align-items-center gap-2 mb-1.5">
                            <input class="form-check-input filter-checkbox m-0" type="checkbox" name="condition_new" id="condNew" checked>
                            <label class="form-check-label text-white small" for="condNew" style="font-size: 0.86rem; cursor: pointer;">
                                Baru
                            </label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input filter-checkbox m-0" type="checkbox" name="condition_used" id="condUsed">
                            <label class="form-check-label text-white small" for="condUsed" style="font-size: 0.86rem; cursor: pointer;">
                                Bekas
                            </label>
                        </div>
                    </div>

                    <!-- Section: Rating Filter (Image 1 Style) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Rating</div>
                        <div class="d-flex align-items-center gap-2 text-warning mb-1" style="font-size: 0.78rem; cursor: pointer;">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <span class="text-white ms-1">5</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-warning" style="font-size: 0.78rem; cursor: pointer;">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star text-muted"></i>
                            <i class="bi bi-star text-muted"></i>
                            <span class="text-white ms-1">3</span>
                        </div>
                    </div>

                    <!-- Section: Status Stok (Image 1 Style) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Status Stok</div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input filter-checkbox m-0" 
                                   type="checkbox" 
                                   name="in_stock" 
                                   value="1" 
                                   id="inStockCheck"
                                   {{ request('in_stock') ? 'checked' : '' }}>
                            <label class="form-check-label text-white small" for="inStockCheck" style="font-size: 0.86rem; cursor: pointer;">
                                Tampilkan yang Ready saja
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons: Terapkan Filter & Reset -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('dashboard') }}" class="d-block text-center small text-secondary mt-2 text-decoration-none">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Right Product Grid Column -->
        <div class="col-12 col-lg-9 col-xl-9">
            <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-xl-4">
                @forelse($products as $p)
                    @php
                        $price = $p->selling_price > 0 ? $p->selling_price : $p->price;
                        $lowerName = strtolower($p->name);
                    @endphp
                    <div class="col">
                        <div class="card product-grid-card h-100 d-flex flex-column justify-content-between">
                            <div>
                                <!-- Visual Representation Box -->
                                <div class="product-visual-box">
                                    @if(str_contains($lowerName, 'phone') || str_contains($lowerName, 'smartphone') || str_contains($lowerName, 'redmagic'))
                                        <i class="bi bi-phone" style="font-size: 3.4rem;"></i>
                                    @elseif(str_contains($lowerName, 'laptop') || str_contains($lowerName, 'macbook') || str_contains($lowerName, 'asus'))
                                        <i class="bi bi-laptop" style="font-size: 3.4rem;"></i>
                                    @elseif(str_contains($lowerName, 'tws') || str_contains($lowerName, 'earbuds') || str_contains($lowerName, 'headset') || str_contains($lowerName, 'audio'))
                                        <i class="bi bi-headphones" style="font-size: 3.4rem;"></i>
                                    @elseif(str_contains($lowerName, 'monitor') || str_contains($lowerName, 'display'))
                                        <i class="bi bi-display" style="font-size: 3.4rem;"></i>
                                    @elseif(str_contains($lowerName, 'keyboard'))
                                        <i class="bi bi-keyboard" style="font-size: 3.4rem;"></i>
                                    @elseif(str_contains($lowerName, 'mouse'))
                                        <i class="bi bi-mouse" style="font-size: 3.4rem;"></i>
                                    @else
                                        <i class="bi bi-cpu" style="font-size: 3.4rem;"></i>
                                    @endif
                                </div>

                                <!-- Note: Rating stars removed per user request ("hapus dulu bintang pada product") -->

                                <!-- Title -->
                                <a href="{{ route('customer.catalog.show', $p->id) }}" 
                                   class="fw-semibold text-white d-block text-truncate text-decoration-none mb-1.5" 
                                   style="font-size: 0.9rem;"
                                   title="{{ $p->name }}">
                                    {{ $p->name }}
                                </a>

                                <!-- Price & Stock Status Row -->
                                <div class="d-flex align-items-baseline justify-content-between mb-2">
                                    <span class="fw-bold text-white" style="font-size: 0.9rem;">
                                        Rp {{ number_format($price, 0, ',', '.') }}
                                    </span>
                                    <span style="color: #10B981; font-size: 0.72rem; font-weight: 600;">
                                        {{ $p->stock > 0 ? 'Stok Ready' : 'Habis' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Bottom Action Row: Tambah ke Keranjang + Beli -->
                            <div class="d-flex align-items-center gap-1.5 pt-2 border-top" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                                <a href="{{ route('customer.catalog.show', $p->id) }}" 
                                   class="btn btn-outline-glass btn-sm py-1 px-2.5 d-inline-flex align-items-center justify-content-center" 
                                   style="font-size: 0.8rem; height: 32px;" 
                                   title="Tambah ke Keranjang">
                                    <i class="bi bi-cart3"></i>
                                </a>

                                <form action="{{ route('customer.checkout') }}" method="POST" class="d-inline flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="items[0][product_id]" value="{{ $p->id }}">
                                    <input type="hidden" name="items[0][quantity]" value="1">
                                    <button type="submit" 
                                            class="btn btn-primary btn-sm w-100 py-1 fw-bold" 
                                            style="font-size: 0.8rem; height: 32px;"
                                            {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                        Beli
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="text-white mb-1">Tidak ada produk yang sesuai dengan filter.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm mt-2">Reset Filter</a>
                    </div>
                @endforelse
            </div>

            <!-- Slide Page Pagination (Image 2 Style) -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $products->links('vendor.pagination.buyer-slide') }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updatePriceBoxes(val) {
        document.getElementById('maxPriceInput').value = val;
        let millionVal = (val / 1000000).toFixed(0);
        document.getElementById('priceBoxMax').value = 'Rp ' + millionVal + 'jt';
    }
</script>
@endpush
@endsection
