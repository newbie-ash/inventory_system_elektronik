@extends('layouts.app')

@section('title', 'Beranda & Katalog Gear - Ctrl+R')
@section('page_heading', 'Beranda')

@push('styles')
<style>
    /* Promo Banner Card (Elongated downward per user request) */
    .promo-banner-card {
        background: linear-gradient(135deg, rgba(22, 27, 51, 0.98) 0%, rgba(71, 73, 115, 0.4) 55%, rgba(22, 27, 51, 0.98) 100%) !important;
        border: 1px solid rgba(166, 156, 172, 0.22) !important;
        border-radius: 24px !important;
        box-shadow: 0 18px 42px -10px rgba(0, 0, 0, 0.55) !important;
        min-height: 220px;
        padding: 2.5rem 2.25rem !important;
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
        font-size: 0.82rem;
        letter-spacing: 0.8px;
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

    /* Price Input Containers (Editable by Consumer) */
    .price-input-box {
        background: rgba(13, 12, 29, 0.75);
        border: 1px solid rgba(166, 156, 172, 0.25);
        border-radius: 10px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .price-input-box:focus-within {
        border-color: var(--colors-accent);
        background: rgba(22, 27, 51, 0.95);
        box-shadow: 0 0 12px rgba(241, 218, 196, 0.2);
    }

    .price-prefix {
        color: var(--colors-muted);
        font-size: 0.78rem;
        font-weight: 600;
        user-select: none;
    }

    .price-clean-input {
        background: transparent !important;
        border: none !important;
        outline: none !important;
        color: #FFFFFF !important;
        font-size: 0.82rem;
        font-family: inherit;
        font-weight: 600;
        width: 100%;
        padding: 0;
    }

    .price-clean-input::placeholder {
        color: rgba(166, 156, 172, 0.4);
        font-weight: 400;
    }

    .price-clean-input::-webkit-outer-spin-button,
    .price-clean-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .price-clean-input[type=number] {
        -moz-appearance: textfield;
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
        cursor: pointer;
    }

    .product-grid-card:hover .product-visual-box {
        border-color: rgba(241, 218, 196, 0.45);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
    }

    .product-grid-card:hover .product-visual-box i {
        transform: scale(1.08);
        color: #FFFFFF;
    }

    .product-grid-card:hover .product-visual-box img {
        transform: scale(1.04);
    }

    .product-grid-card:hover .product-card-title {
        color: var(--colors-accent) !important;
    }

    .product-visual-box i {
        transition: transform 0.25s ease, color 0.25s ease;
    }

    .product-visual-box img {
        transition: transform 0.25s ease;
    }

    /* Range slider custom track */
    .form-range::-webkit-slider-thumb {
        background: #FFFFFF;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }


</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 1380px;">

    @php
        $bannerTag = $activeBanner->tag ?? 'PROMO SPESIAL GAJIAN:';
        $bannerTitle = $activeBanner->title ?? 'Potongan hingga 15% untuk kategori PC & Gadget';
        $bannerDesc = $activeBanner->description ?? 'Dapatkan penawaran terbatas untuk produk elektronik & hardware pilihan minggu ini.';
        $bannerBtnText = $activeBanner->button_text ?? 'Klaim Voucher';
        $bannerBtnUrl = $activeBanner->button_url ?? '#katalog-grid';
    @endphp

    <!-- 1. Top Promo Banner (Elongated downward & dynamically managed by admin) -->
    <div class="card p-4 p-md-5 mb-4 promo-banner-card position-relative overflow-hidden d-flex flex-column justify-content-center" id="promo">
        <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
            <div class="col-12 {{ $activeBanner && $activeBanner->image_url ? 'col-md-7 col-lg-7' : 'col-md-8 col-lg-8' }}">
                <span class="promo-tag text-uppercase fw-semibold mb-2 d-inline-block font-monospace">
                    {{ $bannerTag }}
                </span>
                <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.4rem, 2.5vw, 2.1rem); letter-spacing: -0.5px; line-height: 1.25;">
                    {{ $bannerTitle }}
                </h2>
                @if(!empty($bannerDesc))
                    <p class="text-white-50 small mb-3 mb-md-4" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                        {{ $bannerDesc }}
                    </p>
                @endif
                <div class="d-flex align-items-center gap-1.5 carousel-dots pt-1">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="col-12 {{ $activeBanner && $activeBanner->image_url ? 'col-md-5 col-lg-5' : 'col-md-4 col-lg-4' }} text-md-end d-flex flex-column align-items-md-end justify-content-center gap-3">
                @if($activeBanner && $activeBanner->image_url)
                    <img src="{{ $activeBanner->image_url }}" alt="Promo" class="img-fluid rounded-3 object-fit-cover shadow" style="max-height: 125px; max-width: 220px; border: 1px solid rgba(166, 156, 172, 0.25);">
                @endif
                <a href="{{ $bannerBtnUrl }}" class="btn btn-primary px-4 py-2.5 fw-bold" style="border-radius: 12px; font-size: 0.95rem;">
                    {{ $bannerBtnText }}
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

                    <!-- Section: Rentang Harga (Manual Input Containers) -->
                    <div class="mb-4">
                        <div class="filter-section-title">Rentang Harga</div>
                        <input type="range" 
                               class="form-range mb-3" 
                               min="0" 
                               max="50000000" 
                               step="250000" 
                               id="priceSlider"
                               value="{{ request('max_price', 30000000) }}" 
                               oninput="syncSliderToBox(this.value)">

                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <!-- Min Price Manual Container -->
                            <div class="price-input-box flex-grow-1" title="Harga Minimum">
                                <span class="price-prefix">Rp</span>
                                <input type="number" 
                                       name="min_price" 
                                       id="minPriceInput" 
                                       class="price-clean-input" 
                                       placeholder="Min" 
                                       value="{{ request('min_price') }}" 
                                       min="0" 
                                       step="50000">
                            </div>

                            <span class="text-secondary fw-bold px-1">-</span>

                            <!-- Max Price Manual Container -->
                            <div class="price-input-box flex-grow-1" title="Harga Maksimum">
                                <span class="price-prefix">Rp</span>
                                <input type="number" 
                                       name="max_price" 
                                       id="maxPriceInput" 
                                       class="price-clean-input" 
                                       placeholder="Maks" 
                                       value="{{ request('max_price') }}" 
                                       min="0" 
                                       step="50000"
                                       oninput="syncBoxToSlider(this.value)">
                            </div>
                        </div>
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
                                <!-- Visual Representation Box (Clickable Link to Product Detail) -->
                                <a href="{{ route('customer.catalog.show', $p->id) }}" 
                                   class="product-visual-box d-flex align-items-center justify-content-center text-decoration-none overflow-hidden" 
                                   title="Lihat detail {{ $p->name }}">
                                    @if($p->image_url)
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover rounded-3">
                                    @elseif(str_contains($lowerName, 'phone') || str_contains($lowerName, 'smartphone') || str_contains($lowerName, 'redmagic'))
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
                                </a>

                                <!-- Rating Stars & Reviews Count -->
                                @php
                                    $pRating = $p->averageRating();
                                    $pReviews = $p->reviewsCount();
                                @endphp
                                <div class="d-flex align-items-center gap-1.5 mb-1.5 font-monospace" style="font-size: 0.72rem;">
                                    @if($pReviews > 0)
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="text-white fw-bold">{{ number_format($pRating, 1) }}</span>
                                        <span class="text-secondary">({{ $pReviews }})</span>
                                    @else
                                        <i class="bi bi-star text-secondary" style="opacity: 0.5;"></i>
                                        <span class="text-secondary" style="font-size: 0.68rem;">Belum ada ulasan</span>
                                    @endif
                                </div>

                                <!-- Title (Clickable Link to Product Detail) -->
                                <a href="{{ route('customer.catalog.show', $p->id) }}" 
                                   class="fw-semibold text-white d-block text-truncate text-decoration-none mb-1.5 product-card-title" 
                                   style="font-size: 0.9rem;"
                                   title="{{ $p->name }}">
                                    {{ $p->name }}
                                </a>

                                <!-- Price & Real Stock Remaining Row -->
                                <div class="d-flex align-items-baseline justify-content-between mb-2">
                                    <span class="fw-bold text-white font-monospace" style="font-size: 0.9rem;">
                                        Rp {{ number_format($price, 0, ',', '.') }}
                                    </span>
                                    @if($p->stock <= 0)
                                        <span class="small font-monospace fw-semibold" style="color: #EF4444; font-size: 0.72rem;">
                                            Stok Habis
                                        </span>
                                    @elseif($p->stock <= 5)
                                        <span class="small font-monospace fw-semibold" style="color: #F59E0B; font-size: 0.72rem;" title="Stok hampir habis!">
                                            Sisa {{ $p->stock }}
                                        </span>
                                    @else
                                        <span class="small font-monospace fw-semibold" style="color: #10B981; font-size: 0.72rem;">
                                            Sisa {{ $p->stock }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Bottom Action Row: Tambah ke Keranjang + Beli -->
                            <div class="d-flex align-items-center gap-1.5 pt-2 border-top" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="btn btn-sm py-1 px-2.5 d-inline-flex align-items-center justify-content-center" 
                                            style="font-size: 0.85rem; height: 32px; width: 34px; background: rgba(71, 73, 115, 0.4); border: 1px solid rgba(166, 156, 172, 0.35); color: #ffffff; border-radius: 8px; transition: all 0.2s;" 
                                            title="Tambah ke Keranjang"
                                            {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>

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
    function syncSliderToBox(val) {
        const maxBox = document.getElementById('maxPriceInput');
        if (maxBox) {
            maxBox.value = val;
        }
    }
    function syncBoxToSlider(val) {
        const slider = document.getElementById('priceSlider');
        if (slider && val) {
            slider.value = val;
        }
    }
</script>
@endpush
@endsection
