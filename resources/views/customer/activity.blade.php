@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - Ctrl+R')
@section('page_heading', 'Riwayat Aktivitas')

@section('content')
<div class="container-fluid px-0" style="max-width: 980px;">
    <!-- Top Header & Action Controls (Tokopedia Style) -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3 pb-3" style="border-bottom: 1px solid var(--colors-hairline);">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('customer.catalog') }}" class="btn btn-glass-outline btn-sm font-monospace d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; padding: 0; border-radius: 10px;" title="Kembali ke Katalog">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h4 class="mb-0 text-white fw-bold d-flex align-items-center gap-2" style="letter-spacing: -0.5px;">
                    <i class="bi bi-clock-history" style="color: var(--colors-accent);"></i>
                    <span>Riwayat Aktivitas</span>
                    @if($totalCount > 0)
                        <span class="badge font-monospace" style="background: rgba(226, 232, 240, 0.15); color: var(--colors-accent); font-size: 0.75rem; font-weight: 600;">
                            {{ $totalCount }} produk
                        </span>
                    @endif
                </h4>
                <p class="text-secondary small font-monospace mb-0 mt-0.5">Audit jejak produk yang terakhir Anda lihat dan telusuri di Ctrl+R.</p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($totalCount > 0)
                <form action="{{ route('customer.activity.clear') }}" 
                      method="POST"
                      data-confirm="Seluruh catatan penelusuran produk Anda akan dibersihkan dari akun ini."
                      data-confirm-title="Bersihkan Semua Riwayat?"
                      data-confirm-type="danger"
                      data-confirm-icon="bi-trash3-fill"
                      data-confirm-btn="Ya, Bersihkan Semua">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm font-monospace d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-size: 0.78rem;">
                        <i class="bi bi-trash3"></i> Hapus Semua Riwayat
                    </button>
                </form>
            @endif
            <a href="{{ route('customer.cart') }}" class="btn btn-glass-outline btn-sm font-monospace d-inline-flex align-items-center gap-1.5" style="border-radius: 10px; font-size: 0.78rem;">
                <i class="bi bi-cart3"></i> Ke Keranjang
            </a>
        </div>
    </div>

    <!-- Alert Feedback -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 font-monospace small" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.35); color: #15803D; border-radius: 12px;">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($groupedActivities->isEmpty())
        <!-- Empty State -->
        <div class="card text-center py-5 px-3" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 20px;">
            <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                <i class="bi bi-clock-history fs-1" style="color: var(--colors-muted);"></i>
            </div>
            <h5 class="text-white fw-bold mb-1">Belum Ada Riwayat Aktivitas Dilihat</h5>
            <p class="text-secondary small font-monospace mb-4" style="max-width: 460px; margin-left: auto; margin-right: auto;">
                Semua produk yang Anda buka dan telusuri akan tersimpan otomatis di sini agar memudahkan Anda belanja kembali kapan saja.
            </p>
            <div>
                <a href="{{ route('customer.catalog') }}" class="btn btn-primary font-monospace px-4 py-2" style="border-radius: 12px;">
                    <i class="bi bi-grid me-1"></i> Mulai Jelajahi Produk
                </a>
            </div>
        </div>
    @else
        <!-- Grouped Activity Timeline (Tokopedia Style: Hari Ini, Kemarin, Minggu Ini) -->
        <div class="d-flex flex-column gap-4">
            @foreach($groupedActivities as $groupLabel => $items)
                <div>
                    <!-- Date Group Heading -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge font-monospace text-uppercase" style="background: var(--colors-card-alt); color: var(--colors-text); border: 1px solid var(--colors-hairline); font-size: 0.8rem; padding: 6px 14px; border-radius: 20px; letter-spacing: 0.5px;">
                            <i class="bi bi-calendar3 me-1"></i> {{ $groupLabel }}
                        </span>
                        <div class="flex-grow-1" style="height: 1px; background: var(--colors-hairline);"></div>
                        <span class="small font-monospace text-secondary" style="font-size: 0.75rem;">
                            {{ $items->count() }} item
                        </span>
                    </div>

                    <!-- Products List within Date Group -->
                    <div class="d-flex flex-column gap-2.5">
                        @foreach($items as $activity)
                            @php
                                $product = $activity->product;
                                $price = (float) ($product->selling_price > 0 ? $product->selling_price : $product->price);
                            @endphp
                            <div class="card p-3 activity-card" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 16px; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Product Image Thumbnail -->
                                    <a href="{{ route('customer.catalog.show', $product) }}" class="flex-shrink-0 text-decoration-none">
                                        <div style="width: 84px; height: 84px; border-radius: 12px; overflow: hidden; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); display: flex; align-items: center; justify-content: center;">
                                            @if($product->image && file_exists(public_path($product->image)))
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="bi bi-cpu fs-2 text-secondary"></i>
                                            @endif
                                        </div>
                                    </a>

                                    <!-- Product Info (Middle) -->
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                            @if($product->category)
                                                <span class="badge" style="background: var(--colors-card-alt); color: var(--colors-muted); border: 1px solid var(--colors-hairline); font-size: 0.68rem; padding: 2px 7px; border-radius: 6px;">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif
                                            <span class="font-monospace text-secondary" style="font-size: 0.72rem;">
                                                <i class="bi bi-clock me-0.5"></i> {{ $activity->last_viewed_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <a href="{{ route('customer.catalog.show', $product) }}" class="text-white text-decoration-none fw-bold d-block text-truncate mb-1" style="font-size: 0.95rem;" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </a>

                                        <!-- Price & Viewed Count (Tokopedia Tag Style) -->
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <span class="font-monospace fw-bold" style="color: var(--colors-accent); font-size: 1.05rem;">
                                                Rp {{ number_format($price, 0, ',', '.') }}
                                            </span>

                                            <span class="badge font-monospace" style="background: rgba(220, 38, 38, 0.08); color: #DC2626; border: 1px solid rgba(220, 38, 38, 0.25); font-size: 0.7rem; padding: 3px 8px; border-radius: 8px;">
                                                Anda melihat {{ $activity->view_count }} kali
                                            </span>

                                            <span class="small font-monospace {{ $product->stock > 5 ? 'text-secondary' : 'text-warning' }}" style="font-size: 0.75rem;">
                                                Sisa: {{ $product->stock }} unit
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Right Actions: Quick Add to Cart & Remove Item -->
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        @if($product->stock > 0)
                                            <form action="{{ route('customer.cart.add') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 12px; border: 1px solid var(--colors-hairline); color: #1A1A1C; background: var(--colors-card-alt);" title="Tambah ke Keranjang">
                                                    <i class="bi bi-cart-plus fs-5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary disabled d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 12px; opacity: 0.4;" title="Stok Habis" disabled>
                                                <i class="bi bi-cart-x fs-5"></i>
                                            </button>
                                        @endif

                                        <form action="{{ route('customer.activity.destroy', $activity) }}" 
                                              method="POST" 
                                              class="m-0"
                                              data-confirm="Hapus produk ini dari daftar riwayat penelusuran akun Anda?"
                                              data-confirm-title="Hapus dari Riwayat?"
                                              data-confirm-item="{{ $product ? $product->name : 'Produk' }}"
                                              data-confirm-type="danger"
                                              data-confirm-icon="bi-clock-history"
                                              data-confirm-btn="Ya, Hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-glass-outline d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 12px; color: var(--colors-muted);" title="Hapus dari Riwayat">
                                                <i class="bi bi-trash3 fs-6"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
