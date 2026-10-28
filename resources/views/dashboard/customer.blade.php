@extends('layouts.app')

@section('title', 'Beranda & Katalog Gear - Ctrl+R')
@section('page_heading', 'Beranda')

@push('styles')
<style>
    /* Promo Banner Card (Glassmorphism) */
    .promo-banner-card {
        background: rgba(255, 255, 255, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(24px) saturate(190%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px -4px rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
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
        background: radial-gradient(circle at 75% 50%, rgba(26, 26, 28, 0.04) 0%, transparent 65%);
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
        background: rgba(112, 110, 107, 0.25);
        display: inline-block;
        transition: all 0.2s ease;
    }

    .carousel-dots .dot.active {
        width: 26px;
        background: var(--colors-accent);
        box-shadow: 0 0 8px rgba(26, 26, 28, 0.25);
    }

    /* Price Input Containers (Editable by Consumer) */
    .price-input-box {
        background: var(--colors-card-alt);
        border: 1px solid var(--colors-hairline);
        border-radius: 10px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .price-input-box:focus-within {
        border-color: #1A1A1C;
        background: #FFFFFF;
        box-shadow: 0 0 0 2px rgba(26, 26, 28, 0.1);
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
        color: #1A1A1C !important;
        font-size: 0.82rem;
        font-family: inherit;
        font-weight: 600;
        width: 100%;
        padding: 0;
    }

    .price-clean-input::placeholder {
        color: #706E6B;
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

    /* Left Sidebar Filter (Warm Paper Glassmorphism) */
    .filter-sidebar-card {
        background: rgba(255, 255, 255, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(24px) saturate(190%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(190%) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px -4px rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
    }

    .filter-section-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1A1A1C;
        margin-bottom: 0.75rem;
        letter-spacing: -0.2px;
    }

    .filter-checkbox {
        background-color: var(--colors-card-alt) !important;
        border: 1px solid var(--colors-hairline) !important;
        border-radius: 6px !important;
        cursor: pointer;
    }

    .filter-checkbox:checked {
        background-color: var(--colors-accent) !important;
        border-color: var(--colors-accent) !important;
    }

    /* Product Cards in Grid (Glassmorphism) */
    .product-grid-card {
        background: rgba(255, 255, 255, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(20px) saturate(190%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(190%) !important;
        border-radius: 14px !important;
        padding: 12px !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 6px 20px -2px rgba(28, 26, 24, 0.04), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
    }

    .product-grid-card:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.58) !important;
        border-color: rgba(26, 26, 28, 0.25) !important;
        box-shadow: 0 14px 32px -4px rgba(28, 26, 24, 0.08), inset 0 1px 1px 0 rgba(255, 255, 255, 0.95) !important;
    }

    .product-visual-box {
        background: var(--colors-card-alt);
        border: 1px solid var(--colors-hairline);
        border-radius: 10px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        color: var(--colors-accent);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .product-grid-card:hover .product-visual-box {
        border-color: var(--colors-accent);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
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
                    <img src="{{ $activeBanner->image_url }}" alt="Promo" class="img-fluid rounded-3 object-fit-cover shadow" style="max-height: 125px; max-width: 220px; border: 1px solid var(--colors-hairline);">
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
        <div class="col-12 col-lg-9 col-xl-9 position-relative">
            <!-- Loading Overlay -->
            <div id="catalogLoadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(248, 245, 238, 0.75); z-index: 10; backdrop-filter: blur(2px); border-radius: 16px;">
                <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
                    <span class="visually-hidden">Memuat...</span>
                </div>
            </div>

            <div id="productGridContainer">
                @include('dashboard.partials.product_grid', ['products' => $products])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function syncSliderToBox(val) {
        const maxBox = document.getElementById('maxPriceInput');
        if (maxBox) {
            maxBox.value = val;
            triggerFilter();
        }
    }
    function syncBoxToSlider(val) {
        const slider = document.getElementById('priceSlider');
        if (slider && val) {
            slider.value = val;
            triggerFilter();
        }
    }

    let filterTimeout = null;
    let currentAbortController = null;

    function triggerFilter() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(fetchFilteredCatalog, 300);
    }

    function fetchFilteredCatalog(pageUrl = null) {
        const form = document.getElementById('filterSidebarForm');
        const container = document.getElementById('productGridContainer');
        const overlay = document.getElementById('catalogLoadingOverlay');
        const navbarSearch = document.getElementById('navbarSearchInput');

        if (!container) return;

        let url;
        if (pageUrl) {
            url = new URL(pageUrl, window.location.origin);
        } else {
            url = new URL(form ? form.action : window.location.href, window.location.origin);
            if (form) {
                const formData = new FormData(form);
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        url.searchParams.append(key, value);
                    }
                }
            }
            if (navbarSearch && navbarSearch.value.trim() !== '') {
                url.searchParams.set('search', navbarSearch.value.trim());
            }
        }

        url.searchParams.set('ajax', '1');

        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        if (overlay) {
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        }
        container.style.opacity = '0.5';

        fetch(url.toString(), {
            signal: currentAbortController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            if (overlay) {
                overlay.classList.remove('d-flex');
                overlay.classList.add('d-none');
            }

            // Sync clean URL to history
            const cleanUrl = new URL(url.toString());
            cleanUrl.searchParams.delete('ajax');
            window.history.pushState(null, '', cleanUrl.toString());

            // Re-bind ajax pagination
            bindPaginationLinks();
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                container.style.opacity = '1';
                if (overlay) {
                    overlay.classList.remove('d-flex');
                    overlay.classList.add('d-none');
                }
            }
        });
    }

    function bindPaginationLinks() {
        const paginationLinks = document.querySelectorAll('#productGridContainer .ajax-pagination-wrapper a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchFilteredCatalog(this.href);
                const gridSection = document.getElementById('katalog-grid');
                if (gridSection) {
                    gridSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filterSidebarForm');
        if (form) {
            form.addEventListener('change', triggerFilter);
            form.addEventListener('input', triggerFilter);
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                triggerFilter();
            });
        }

        const navbarSearch = document.getElementById('navbarSearchInput');
        if (navbarSearch) {
            navbarSearch.addEventListener('input', triggerFilter);
        }

        bindPaginationLinks();
    });
</script>
@endpush
@endsection
