@extends('layouts.app')

@section('title', 'Katalog Gear & Hardware - Ctrl + C')
@section('page_heading', 'Katalog Hardware')

@section('content')
<div class="container-fluid px-0">
    <!-- Search & Filter Banner -->
    <div class="card mb-4 p-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline);">
        <form action="{{ route('customer.catalog') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari laptop, mouse, keyboard, monitor, atau SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori Hardware</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }} item)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 font-monospace text-uppercase">Cari</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('customer.catalog') }}" class="btn btn-glass-outline"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <!-- Category Filter Chips -->
        <div class="d-flex align-items-center gap-2 overflow-x-auto no-scrollbar pt-3 mt-3 border-top small" style="border-color: var(--colors-hairline) !important;">
            <span class="text-secondary font-monospace fw-semibold me-1">KATEGORI:</span>
            <a href="{{ route('customer.catalog') }}" class="btn btn-sm {{ !request('category_id') ? 'btn-primary' : 'btn-glass-outline' }}">
                SEMUA
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('customer.catalog', ['category_id' => $cat->id, 'search' => request('search')]) }}" class="btn btn-sm {{ request('category_id') == $cat->id ? 'btn-primary' : 'btn-glass-outline' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-4 mb-4">
        @forelse($products as $prod)
            @php
                $price = $prod->selling_price > 0 ? $prod->selling_price : $prod->price;
            @endphp
            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                <div class="card p-3 h-100 d-flex flex-column justify-content-between" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline);">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge font-monospace" style="background: rgba(255, 0, 61, 0.15); color: var(--colors-accent); border: 1px solid var(--colors-accent); font-size: 0.65rem;">{{ $prod->sku }}</span>
                            @if($prod->stock <= 0)
                                <span class="badge font-monospace" style="background: rgba(239, 68, 68, 0.2); color: #F87171; font-size: 0.68rem;">
                                    Stok Habis
                                </span>
                            @elseif($prod->stock <= 5)
                                <span class="badge font-monospace" style="background: rgba(245, 158, 11, 0.2); color: #FBBF24; font-size: 0.68rem;">
                                    Sisa {{ $prod->stock }} unit
                                </span>
                            @else
                                <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.2); color: #34D399; font-size: 0.68rem;">
                                    Sisa {{ $prod->stock }} unit
                                </span>
                            @endif
                        </div>

                        <!-- Visual Representation (Clickable Link) -->
                        <a href="{{ route('customer.catalog.show', $prod) }}" class="p-2 mb-2 text-center d-flex align-items-center justify-content-center overflow-hidden text-decoration-none" style="height: 130px; background: var(--colors-card-alt); color: var(--colors-accent); border: 1px solid var(--colors-hairline); border-radius: 8px; cursor: pointer;" title="Lihat detail {{ $prod->name }}">
                            @if($prod->image_url)
                                <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-100 h-100 object-fit-cover rounded-2">
                            @elseif(str_contains(strtolower($prod->name), 'laptop'))
                                <i class="bi bi-laptop fs-1"></i>
                            @elseif(str_contains(strtolower($prod->name), 'keyboard'))
                                <i class="bi bi-keyboard fs-1"></i>
                            @elseif(str_contains(strtolower($prod->name), 'mouse'))
                                <i class="bi bi-mouse fs-1"></i>
                            @elseif(str_contains(strtolower($prod->name), 'monitor'))
                                <i class="bi bi-display fs-1"></i>
                            @else
                                <i class="bi bi-cpu fs-1"></i>
                            @endif
                        </a>

                        <h5 class="fw-semibold text-white fs-6 mb-1 text-truncate" title="{{ $prod->name }}" style="font-family: 'Roboto', sans-serif;">
                            <a href="{{ route('customer.catalog.show', $prod) }}" class="text-white text-decoration-none">
                                {{ $prod->name }}
                            </a>
                        </h5>

                        @php
                            $catRating = $prod->averageRating();
                            $catReviews = $prod->reviewsCount();
                        @endphp
                        <div class="d-flex align-items-center gap-1.5 mb-2 font-monospace" style="font-size: 0.72rem;">
                            @if($catReviews > 0)
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="text-white fw-bold">{{ number_format($catRating, 1) }}</span>
                                <span class="text-secondary">({{ $catReviews }})</span>
                            @else
                                <i class="bi bi-star text-secondary" style="opacity: 0.5;"></i>
                                <span class="text-secondary" style="font-size: 0.68rem;">Belum ada ulasan</span>
                            @endif
                        </div>
                        <p class="text-secondary small mb-3" style="font-size: 0.78rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.3rem; color: var(--colors-muted) !important;">
                            {{ $prod->description ?: 'Hardware resmi standar turnamen dengan garansi distributor original.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-top" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <span class="text-secondary font-monospace small" style="font-size: 0.72rem;">HARGA:</span>
                            <span class="fs-6 fw-bold font-monospace" style="color: var(--colors-accent);">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Fast Checkout & Cart Form -->
                        <form action="{{ route('customer.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="items[0][product_id]" value="{{ $prod->id }}">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text font-monospace small">Qty</span>
                                <input type="number" name="items[0][quantity]" class="form-control text-center font-monospace" value="1" min="1" max="{{ $prod->stock }}" required>
                            </div>
                            <div class="d-flex gap-1.5">
                                <button type="submit" 
                                        formaction="{{ route('customer.cart.add') }}" 
                                        class="btn btn-glass-outline btn-sm font-monospace py-1.5 px-2.5 d-flex align-items-center justify-content-center" 
                                        style="border: 1px solid rgba(166, 156, 172, 0.35); color: #ffffff; background: rgba(71, 73, 115, 0.35);"
                                        title="Tambah ke Keranjang"
                                        {{ $prod->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-cart-plus fs-6"></i>
                                </button>
                                <button type="submit" 
                                        class="btn btn-primary btn-sm flex-grow-1 font-monospace py-1.5 d-flex align-items-center justify-content-center gap-1.5 text-uppercase"
                                        {{ $prod->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-credit-card-2-front"></i> Beli
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-secondary font-monospace">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                <h6 class="fw-bold text-white">Tidak ada produk yang sesuai dengan kriteria.</h6>
                <p class="small text-secondary">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
                <a href="{{ route('customer.catalog') }}" class="btn btn-sm btn-primary mt-2 text-uppercase">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination (Centered Buyer Slide Style) -->
    @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
