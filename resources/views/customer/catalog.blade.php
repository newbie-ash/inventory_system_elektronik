@extends('layouts.app')

@section('title', 'Katalog Gear & Hardware - Ctrl + C')
@section('page_heading', 'Katalog Hardware')

@section('content')
<div class="container-fluid px-0">
    <!-- Search & Filter Banner -->
    <div class="card mb-4 p-4 bg-white">
        <form action="{{ route('customer.catalog') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary"><i class="bi bi-search"></i></span>
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
                <button type="submit" class="btn btn-blue-primary w-100 font-monospace">Cari</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('customer.catalog') }}" class="btn btn-glass-outline"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <!-- Category Filter Chips -->
        <div class="d-flex align-items-center gap-2 overflow-x-auto pt-3 mt-3 border-top small">
            <span class="text-secondary font-monospace fw-semibold me-1">KATEGORI:</span>
            <a href="{{ route('customer.catalog') }}" class="btn btn-sm {{ !request('category_id') ? 'btn-dark' : 'btn-outline-secondary' }}" style="border-radius: 8px;">
                SEMUA
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('customer.catalog', ['category_id' => $cat->id, 'search' => request('search')]) }}" class="btn btn-sm {{ request('category_id') == $cat->id ? 'btn-dark' : 'btn-outline-secondary' }}" style="border-radius: 8px;">
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
                <div class="card p-3 h-100 d-flex flex-column justify-content-between bg-white">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-blue" style="font-size: 0.65rem;">{{ $prod->sku }}</span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 font-monospace" style="font-size: 0.68rem;">
                                Stok: {{ $prod->stock }} unit
                            </span>
                        </div>

                        <!-- Visual Icon Representation -->
                        <div class="p-3 mb-2 rounded-3 text-center d-flex align-items-center justify-content-center" style="height: 130px; background: #F8FAFC; color: var(--accent-blue);">
                            @if(str_contains(strtolower($prod->name), 'laptop'))
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
                        </div>

                        <h5 class="fw-bold text-dark fs-6 mb-1 text-truncate" title="{{ $prod->name }}">
                            <a href="{{ route('customer.catalog.show', $prod) }}" class="text-dark text-decoration-none">
                                {{ $prod->name }}
                            </a>
                        </h5>
                        <p class="text-secondary small mb-3" style="font-size: 0.78rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.3rem;">
                            {{ $prod->description ?: 'Hardware resmi standar turnamen dengan garansi distributor original.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <span class="text-secondary font-monospace small" style="font-size: 0.72rem;">HARGA:</span>
                            <span class="fs-6 fw-bold font-monospace text-primary">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Fast Checkout Form with 10% Accent Electric Blue Button -->
                        <form action="{{ route('customer.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="items[0][product_id]" value="{{ $prod->id }}">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text font-monospace small">Qty</span>
                                <input type="number" name="items[0][quantity]" class="form-control text-center font-monospace" value="1" min="1" max="{{ $prod->stock }}" required>
                            </div>
                            <button type="submit" class="btn btn-blue-primary btn-sm w-100 font-monospace py-1.5 d-flex align-items-center justify-content-center gap-1.5">
                                <i class="bi bi-credit-card-2-front"></i> Beli via Midtrans
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-secondary font-monospace">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                <h6 class="fw-bold text-dark">Tidak ada produk yang sesuai dengan kriteria.</h6>
                <p class="small text-secondary">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
                <a href="{{ route('customer.catalog') }}" class="btn btn-sm btn-blue-primary mt-2">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
