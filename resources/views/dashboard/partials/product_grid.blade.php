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
                            <span class="small font-monospace fw-semibold" style="color: #D97706; font-size: 0.72rem;" title="Stok hampir habis!">
                                Sisa {{ $p->stock }}
                            </span>
                        @else
                            <span class="small font-monospace fw-semibold" style="color: #15803D; font-size: 0.72rem;">
                                Sisa {{ $p->stock }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Bottom Action Row: Tambah ke Keranjang + Beli -->
                <div class="d-flex align-items-center gap-1.5 pt-2 border-top" style="border-color: var(--colors-hairline) !important;">
                    <form action="{{ route('customer.cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" 
                                class="btn btn-sm py-1 px-2.5 d-inline-flex align-items-center justify-content-center" 
                                style="font-size: 0.85rem; height: 32px; width: 34px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: #1A1A1C; border-radius: 8px; transition: all 0.2s;" 
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
    <div class="d-flex justify-content-center mt-4 ajax-pagination-wrapper">
        {{ $products->links('vendor.pagination.buyer-slide') }}
    </div>
@endif
