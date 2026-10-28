@extends('layouts.app')

@section('title', 'Pesanan Saya - Ctrl+R')
@section('page_heading', 'Pesanan Saya')

@section('content')
<div class="container-fluid px-0" style="max-width: 1080px;">
    <!-- Top Header -->
    <div class="mb-4">
        <h4 class="mb-1 text-white fw-bold d-flex align-items-center gap-2" style="letter-spacing: -0.5px;">
            <i class="bi bi-bag-check" style="color: var(--colors-accent);"></i>
            <span>Pesanan Saya</span>
        </h4>
        <p class="text-secondary small font-monospace mb-0">Lacak siklus pemesanan, pembayaran, dan status pengiriman barang Anda.</p>
    </div>

    <!-- Alert Feedback -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 font-monospace small" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.35); color: #6EE7B7; border-radius: 12px;">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 font-monospace small" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #FCA5A5; border-radius: 12px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Shopee-Style Order Status Tabs Bar (Image 2) -->
    <div class="card p-2 p-md-3 mb-4" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 18px;">
        <div class="row g-2 text-center">
            <!-- Tab: Belum Bayar (Perlu Dibayar) -->
            <div class="col-6 col-md">
                <a href="{{ route('customer.orders.index', ['tab' => 'unpaid']) }}" 
                   class="order-tab-item p-2.5 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 position-relative {{ $tab === 'unpaid' ? 'active-tab' : '' }}">
                    @if(($counts['unpaid'] ?? 0) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill font-monospace" style="font-size: 0.68rem; z-index: 2; background: #DC2626; color: #FFFFFF; border: 1.5px solid #FFFFFF;">
                            {{ $counts['unpaid'] }}
                        </span>
                    @endif
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Belum Bayar</span>
                </a>
            </div>

            <!-- Tab: Dikemas (Untuk Dikirim) -->
            <div class="col-6 col-md">
                <a href="{{ route('customer.orders.index', ['tab' => 'packing']) }}" 
                   class="order-tab-item p-2.5 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 position-relative {{ $tab === 'packing' ? 'active-tab' : '' }}">
                    @if(($counts['packing'] ?? 0) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill font-monospace" style="font-size: 0.68rem; z-index: 2; background: #1A1A1C; color: #FFFFFF; border: 1.5px solid #FFFFFF;">
                            {{ $counts['packing'] }}
                        </span>
                    @endif
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Dikemas</span>
                </a>
            </div>

            <!-- Tab: Dikirim (Akan Diterima) -->
            <div class="col-6 col-md">
                <a href="{{ route('customer.orders.index', ['tab' => 'shipping']) }}" 
                   class="order-tab-item p-2.5 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 position-relative {{ $tab === 'shipping' ? 'active-tab' : '' }}">
                    @if(($counts['shipping'] ?? 0) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill font-monospace" style="font-size: 0.68rem; z-index: 2; background: #1A1A1C; color: #FFFFFF; border: 1.5px solid #FFFFFF;">
                            {{ $counts['shipping'] }}
                        </span>
                    @endif
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Dikirim</span>
                </a>
            </div>

            <!-- Tab: Beri Penilaian / Selesai -->
            <div class="col-6 col-md">
                <a href="{{ route('customer.orders.index', ['tab' => 'completed']) }}" 
                   class="order-tab-item p-2.5 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 position-relative {{ $tab === 'completed' ? 'active-tab' : '' }}">
                    @if(($counts['completed'] ?? 0) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill font-monospace" style="font-size: 0.68rem; z-index: 2; background: #15803D; color: #FFFFFF; border: 1.5px solid #FFFFFF;">
                            {{ $counts['completed'] }}
                        </span>
                    @endif
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-star fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Beri Penilaian</span>
                </a>
            </div>

            <!-- Tab: Dibatalkan -->
            <div class="col-6 col-md">
                <a href="{{ route('customer.orders.index', ['tab' => 'cancelled']) }}" 
                   class="order-tab-item p-2.5 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 position-relative {{ $tab === 'cancelled' ? 'active-tab' : '' }}">
                    @if(($counts['cancelled'] ?? 0) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill font-monospace" style="font-size: 0.68rem; z-index: 2; background: #DC2626; color: #FFFFFF; border: 1.5px solid #FFFFFF;">
                            {{ $counts['cancelled'] }}
                        </span>
                    @endif
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Dibatalkan</span>
                </a>
            </div>

            <!-- Tab: Semua Pesanan -->
            <div class="col-12 col-md-auto d-flex align-items-center justify-content-center border-start-md" style="border-color: var(--colors-hairline) !important;">
                <a href="{{ route('customer.orders.index', ['tab' => 'all']) }}" 
                   class="order-tab-item p-2.5 px-3 d-flex flex-column align-items-center justify-content-center text-decoration-none rounded-3 {{ $tab === 'all' ? 'active-tab' : '' }}">
                    <div class="tab-icon-wrap mb-1.5">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                    <span class="tab-label fw-semibold">Semua ({{ $counts['all'] ?? 0 }})</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Cards List -->
    @forelse($orders as $order)
        <div class="card mb-3.5 order-card" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 16px; overflow: hidden;">
            <!-- Order Card Header -->
            <div class="card-header py-3 px-3 px-md-4 d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: var(--colors-card-alt) !important; border-bottom: 1px solid var(--colors-hairline);">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="font-monospace fw-bold text-white" style="font-size: 0.95rem;">
                        <i class="bi bi-file-earmark-text text-secondary me-1"></i>
                        {{ $order->sale_number }}
                    </span>
                    <span class="text-secondary small font-monospace">&bull;</span>
                    <span class="text-secondary small font-monospace">
                        {{ $order->sold_at ? $order->sold_at->translatedFormat('d M Y, H:i') : '-' }}
                    </span>
                    @if($order->payment_method === 'cod')
                        <span class="badge font-monospace" style="background: rgba(22, 163, 74, 0.08); color: #15803D; border: 1px solid rgba(22, 163, 74, 0.2); font-size: 0.68rem;">
                            <i class="bi bi-cash-stack"></i> COD
                        </span>
                    @else
                        <span class="badge font-monospace" style="background: var(--colors-card-alt); color: #1A1A1C; border: 1px solid var(--colors-hairline); font-size: 0.68rem;">
                            <i class="bi bi-credit-card"></i> Midtrans
                        </span>
                    @endif
                </div>

                <!-- Status Badge on Right (Shopee Status) -->
                <div>
                    @if($order->status === 'cancelled')
                        <span class="badge badge-gradient-danger font-monospace px-2.5 py-1">
                            <i class="bi bi-x-circle me-1"></i> DIBATALKAN
                        </span>
                    @elseif($order->payment_status === 'pending' && $order->payment_method === 'midtrans')
                        <span class="badge badge-gradient-warning font-monospace px-2.5 py-1">
                            <i class="bi bi-hourglass-split me-1"></i> BELUM BAYAR
                        </span>
                    @elseif($order->shipping_status === 'shipped')
                        <span class="badge badge-gradient-info font-monospace px-2.5 py-1">
                            <i class="bi bi-truck me-1"></i> SEDANG DIKIRIM
                        </span>
                    @elseif($order->shipping_status === 'delivered')
                        <span class="badge badge-gradient-success font-monospace px-2.5 py-1">
                            <i class="bi bi-check-circle-fill me-1"></i> PESANAN SELESAI
                        </span>
                    @else
                        <span class="badge font-monospace px-2.5 py-1" style="background: var(--colors-card-alt); color: #1A1A1C; border: 1px solid var(--colors-hairline); font-weight: 600;">
                            <i class="bi bi-box-seam me-1"></i> SEDANG DIKEMAS
                        </span>
                    @endif
                </div>
            </div>

            <!-- Order Card Body: Items Rows -->
            <div class="card-body p-3 px-md-4">
                <div class="d-flex flex-column gap-3">
                    @foreach($order->items as $item)
                        @php
                            $prod = $item->product;
                        @endphp
                        <div class="d-flex align-items-center justify-content-between gap-3 pb-2.5 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--colors-hairline) !important;">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <div style="width: 58px; height: 58px; border-radius: 10px; overflow: hidden; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                    @if($prod && $prod->image && file_exists(public_path($prod->image)))
                                        <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-cpu fs-4 text-secondary"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h6 class="text-white fw-semibold mb-1 text-truncate" style="font-size: 0.92rem;">
                                        {{ $prod ? $prod->name : 'Produk Tidak Tersedia' }}
                                    </h6>
                                    <div class="small font-monospace text-secondary">
                                        {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0 font-monospace fw-semibold text-white">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Card Footer: Totals & Action Buttons (Shopee Style) -->
            <div class="card-footer py-3 px-3 px-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3" style="background: var(--colors-card-alt) !important; border-top: 1px solid var(--colors-hairline);">
                <div>
                    @if($order->promo_code && $order->discount_amount > 0)
                        <div class="small font-monospace text-success mb-1 d-flex align-items-center gap-1.5">
                            <i class="bi bi-tag-fill"></i>
                            <span>Voucher <strong>{{ $order->promo_code }}</strong> hemat Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-secondary small font-monospace">Total Pesanan:</span>
                        <span class="fw-bold font-monospace fs-5" style="color: var(--colors-accent);">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Status Contextual Action Buttons -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($order->status === 'cancelled')
                        <!-- Cancelled: Re-order -->
                        <form action="{{ route('customer.cart.reorder', $order) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="order-action-btn order-action-primary font-monospace border-0">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Beli Lagi</span>
                            </button>
                        </form>
                    @elseif($order->payment_status === 'pending' && $order->payment_method === 'midtrans')
                        <!-- Unpaid: Pay Now Button -->
                        <a href="{{ route('customer.orders.payment', $order) }}" class="order-action-btn order-action-primary font-monospace fw-bold">
                            <i class="bi bi-credit-card-2-front"></i>
                            <span>Bayar Sekarang</span>
                        </a>
                    @elseif($order->shipping_status === 'shipped')
                        <!-- Shipped: Confirm Delivery ("Pesanan Diterima") -->
                        <form action="{{ route('customer.orders.confirm-delivery', $order) }}" 
                              method="POST" 
                              class="m-0"
                              data-confirm="Pastikan Anda telah menerima paket pesanan secara fisik dan isinya lengkap sebelum mengonfirmasi penerimaan."
                              data-confirm-title="Konfirmasi Pesanan Diterima?"
                              data-confirm-item="Pesanan #{{ $order->sale_number }}"
                              data-confirm-type="success"
                              data-confirm-icon="bi-box2-heart-fill"
                              data-confirm-btn="Ya, Pesanan Diterima">
                            @csrf
                            <button type="submit" class="order-action-btn order-action-success font-monospace border-0">
                                <i class="bi bi-check2-circle"></i>
                                <span>Pesanan Diterima</span>
                            </button>
                        </form>
                    @elseif($order->shipping_status === 'packing')
                        <!-- Packing: Waiting for warehouse staff to dispatch -->
                        <span class="order-action-btn order-action-waiting font-monospace">
                            <i class="bi bi-box-seam text-warning"></i>
                            <span>Menunggu Pengiriman Gudang</span>
                        </span>
                    @elseif($order->shipping_status === 'delivered')
                        <!-- Completed: Review / Penilaian + Reorder -->
                        <button type="button" 
                                class="order-action-btn font-monospace" 
                                style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.35); color: #FCD34D; cursor: pointer;" 
                                data-bs-toggle="modal" 
                                data-bs-target="#reviewModal-{{ $order->id }}">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span>Beri Penilaian</span>
                        </button>

                        <form action="{{ route('customer.cart.reorder', $order) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="order-action-btn order-action-primary font-monospace border-0">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Beli Lagi</span>
                            </button>
                        </form>
                    @endif

                    @if($order->status !== 'cancelled' && in_array($order->shipping_status, ['pending', 'packing']))
                        <!-- Cancel Order Button -->
                        <form action="{{ route('customer.orders.cancel', $order) }}" 
                              method="POST" 
                              class="m-0"
                              data-confirm="Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan langsung dikembalikan ke inventaris."
                              data-confirm-title="Batalkan Pesanan?"
                              data-confirm-item="Pesanan #{{ $order->sale_number }}"
                              data-confirm-type="danger"
                              data-confirm-icon="bi-x-circle-fill"
                              data-confirm-btn="Ya, Batalkan Pesanan">
                            @csrf
                            <button type="submit" class="order-action-btn font-monospace" style="background: #FFFFFF; border: 1px solid rgba(220, 38, 38, 0.4) !important; color: #DC2626;">
                                <i class="bi bi-x-circle"></i>
                                <span>Batalkan</span>
                            </button>
                        </form>
                    @endif

                    <!-- Detailed Audit & Invoice Link -->
                    <a href="{{ route('customer.orders.show', $order) }}" class="order-action-btn order-action-outline font-monospace">
                        <span>Rincian Pesanan</span>
                        <i class="bi bi-chevron-right" style="font-size: 0.72rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        @if($order->shipping_status === 'delivered')
            <!-- Modal Beri Penilaian Produk (Shopee Style) -->
            <div class="modal fade" id="reviewModal-{{ $order->id }}" tabindex="-1" aria-labelledby="reviewModalLabel-{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 20px; color: #FFFFFF; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
                        <div class="modal-header" style="border-bottom: 1px solid var(--colors-hairline); padding: 1.25rem 1.5rem;">
                            <div>
                                <h5 class="modal-title fw-bold font-monospace text-white d-flex align-items-center gap-2 mb-1" id="reviewModalLabel-{{ $order->id }}">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span>Penilaian & Ulasan Produk</span>
                                </h5>
                                <div class="small font-monospace text-secondary">Pesanan #{{ $order->sale_number }}</div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="d-flex flex-column gap-4">
                                @foreach($order->items as $item)
                                    @php
                                        $prod = $item->product;
                                        $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                            ->where('product_id', $item->product_id)
                                            ->where('sale_id', $order->id)
                                            ->first();
                                    @endphp
                                    <form action="{{ route('customer.reviews.store') }}" method="POST" class="p-3.5 rounded-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                                        @csrf
                                        <input type="hidden" name="sale_id" value="{{ $order->id }}">
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                        <!-- Item Info Header -->
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div style="width: 52px; height: 52px; border-radius: 10px; overflow: hidden; background: #FFFFFF; border: 1px solid var(--colors-hairline); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                                @if($prod && $prod->image_url)
                                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="bi bi-cpu fs-4 text-secondary"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-grow-1">
                                                <div class="fw-bold text-white text-truncate" style="font-size: 0.95rem;">{{ $prod ? $prod->name : 'Produk' }}</div>
                                                <div class="text-secondary small font-monospace">Rp {{ number_format($item->unit_price, 0, ',', '.') }} &bull; {{ $item->quantity }} unit</div>
                                            </div>
                                            @if($existingReview)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace" style="font-size: 0.72rem;">
                                                    <i class="bi bi-check-circle me-1"></i> Sudah Dinilai ({{ $existingReview->rating }}★)
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Star Rating Selection (1-5) -->
                                        <div class="mb-3">
                                            <label class="form-label small font-monospace text-secondary mb-1.5 d-block">Kualitas Produk:</label>
                                            <div class="star-rating-group d-flex align-items-center gap-2" data-group="stars-{{ $order->id }}-{{ $item->id }}">
                                                @php $curVal = $existingReview ? $existingReview->rating : 5; @endphp
                                                @for($s = 1; $s <= 5; $s++)
                                                    <label class="star-label" style="cursor: pointer; margin: 0;">
                                                        <input type="radio" name="rating" value="{{ $s }}" class="d-none star-radio" {{ $curVal == $s ? 'checked' : '' }} required>
                                                        <i class="bi {{ $curVal >= $s ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }} fs-3 star-icon" data-value="{{ $s }}"></i>
                                                    </label>
                                                @endfor
                                                <span class="small font-monospace text-secondary ms-2 rating-desc">
                                                    @if($curVal == 5) Sangat Puas (5 Bintang)
                                                    @elseif($curVal == 4) Puas (4 Bintang)
                                                    @elseif($curVal == 3) Cukup (3 Bintang)
                                                    @elseif($curVal == 2) Kurang (2 Bintang)
                                                    @else Sangat Kurang (1 Bintang)
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Review Comment Textarea -->
                                        <div class="mb-3">
                                            <textarea name="comment" rows="2" class="form-control font-monospace small" placeholder="Tuliskan ulasan Anda mengenai kualitas barang, kecepatan respon, dan kepuasan Anda..." style="background: #FFFFFF; border: 1px solid var(--colors-hairline); color: var(--colors-text); font-size: 0.84rem; border-radius: 10px;">{{ $existingReview ? $existingReview->comment : '' }}</textarea>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-sm btn-primary font-monospace px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="border-radius: 10px;">
                                                <i class="bi bi-send-fill"></i>
                                                <span>{{ $existingReview ? 'Simpan Perubahan Penilaian' : 'Kirim Penilaian' }}</span>
                                            </button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <!-- Empty State for Orders -->
        <div class="card text-center py-5 px-3" style="background: var(--colors-primary); border: 1px solid var(--colors-hairline); border-radius: 20px;">
            <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                <i class="bi bi-bag-x fs-1" style="color: var(--colors-muted);"></i>
            </div>
            <h5 class="text-white fw-bold mb-1">Tidak Ada Pesanan pada Kategori Ini</h5>
            <p class="text-secondary small font-monospace mb-4" style="max-width: 440px; margin-left: auto; margin-right: auto;">
                @if($tab === 'unpaid')
                    Anda tidak memiliki pesanan yang menunggu pembayaran online.
                @elseif($tab === 'packing')
                    Tidak ada pesanan yang sedang dikemas oleh tim gudang saat ini.
                @elseif($tab === 'shipping')
                    Tidak ada pesanan yang sedang dalam proses pengiriman kurir.
                @elseif($tab === 'completed')
                    Anda belum menyelesaikan pesanan barang.
                @else
                    Belum ada riwayat pesanan yang tercatat di akun Anda.
                @endif
            </p>
            <div>
                <a href="{{ route('customer.catalog') }}" class="btn btn-primary font-monospace px-4 py-2" style="border-radius: 12px;">
                    <i class="bi bi-shop me-1"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .order-tab-item {
        background: #FFFFFF;
        border: 1px solid var(--colors-hairline);
        color: var(--colors-muted);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        min-height: 84px;
    }
    .order-tab-item:hover {
        background: var(--colors-card-alt);
        border-color: var(--colors-hairline);
        color: var(--colors-text);
        transform: translateY(-2px);
    }
    .order-tab-item.active-tab {
        background: #1A1A1C !important;
        border-color: #1A1A1C;
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(26, 26, 28, 0.25);
    }
    .order-tab-item.active-tab .tab-icon-wrap i {
        color: #FFFFFF !important;
    }
    .order-tab-item.active-tab .tab-label {
        color: #FFFFFF !important;
        font-weight: 700 !important;
    }
    .tab-icon-wrap i {
        color: var(--colors-muted);
        transition: color 0.2s ease;
    }
    .tab-label {
        font-size: 0.82rem;
        letter-spacing: -0.2px;
    }
    .order-card {
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .order-card:hover {
        border-color: #D8D2C4 !important;
    }

    /* Standardized Action Containers (Exact Matching Height & Padding) */
    .order-action-btn {
        height: 38px !important;
        min-height: 38px !important;
        padding: 0 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
        white-space: nowrap !important;
        text-decoration: none !important;
        box-sizing: border-box !important;
        line-height: 1 !important;
        margin: 0 !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .order-action-btn i {
        font-size: 0.95rem;
        line-height: 1;
    }

    .order-action-outline {
        background: #FFFFFF !important;
        border: 1px solid var(--colors-hairline) !important;
        color: #1A1A1C !important;
    }
    .order-action-outline:hover {
        background: var(--colors-card-alt) !important;
        border-color: var(--colors-hairline) !important;
        color: #1A1A1C !important;
        transform: translateY(-1px);
    }

    .order-action-waiting {
        background: var(--colors-card-alt) !important;
        border: 1px solid var(--colors-hairline) !important;
        color: var(--colors-muted) !important;
        cursor: default;
    }

    .order-action-success {
        background: #15803D !important;
        border: 1px solid #15803D !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 8px rgba(21, 128, 61, 0.25) !important;
        cursor: pointer;
    }
    .order-action-success:hover {
        background: #28a745 !important;
        border-color: #28a745 !important;
        transform: translateY(-1px);
    }

    .order-action-primary {
        background: #1A1A1C !important;
        border: 1px solid #1A1A1C !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(26, 26, 28, 0.2) !important;
        cursor: pointer;
    }
    .order-action-primary:hover {
        background: #2C2C2E !important;
        border-color: #2C2C2E !important;
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const descriptions = {
            1: 'Sangat Kurang (1 Bintang)',
            2: 'Kurang (2 Bintang)',
            3: 'Cukup (3 Bintang)',
            4: 'Puas (4 Bintang)',
            5: 'Sangat Puas (5 Bintang)'
        };

        document.querySelectorAll('.star-rating-group').forEach(group => {
            const labels = group.querySelectorAll('.star-label');
            const desc = group.querySelector('.rating-desc');

            labels.forEach(label => {
                label.addEventListener('click', function () {
                    const radio = label.querySelector('.star-radio');
                    const val = parseInt(radio.value, 10);

                    labels.forEach(l => {
                        const icon = l.querySelector('.star-icon');
                        const iconVal = parseInt(icon.getAttribute('data-value'), 10);
                        if (iconVal <= val) {
                            icon.classList.remove('bi-star', 'text-secondary');
                            icon.classList.add('bi-star-fill', 'text-warning');
                        } else {
                            icon.classList.remove('bi-star-fill', 'text-warning');
                            icon.classList.add('bi-star', 'text-secondary');
                        }
                    });

                    if (desc && descriptions[val]) {
                        desc.textContent = descriptions[val];
                    }
                });
            });
        });
    });
</script>
@endpush
