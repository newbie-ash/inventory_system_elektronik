@php
    try {
        $unreadNotifications = Auth::check() ? Auth::user()->unreadNotifications()->take(5)->get() : collect();
        $unreadCount = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;
    } catch (\Throwable $e) {
        $unreadNotifications = collect();
        $unreadCount = 0;
    }
@endphp

<div class="dropdown">
    <button class="{{ $buttonClass ?? 'cust-action-icon' }} position-relative border-0" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false" 
            title="Notifikasi"
            @if(isset($buttonStyle)) style="{{ $buttonStyle }}" @endif>
        <i class="bi bi-bell"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger font-monospace" style="font-size: 0.65rem; padding: 2px 5px;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow border p-0" style="min-width: 320px; max-width: 360px;">
        <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom" style="border-color: var(--colors-hairline) !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-white small">Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="badge badge-gradient-danger font-monospace" style="font-size: 0.65rem; padding: 2px 6px;">{{ $unreadCount }}</span>
                @endif
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-muted" style="font-size: 11px;">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="notification-items" style="max-height: 320px; overflow-y: auto;">
            @forelse($unreadNotifications as $notification)
                <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-start w-100 text-reset border-bottom px-3 py-2 notification-item-btn" style="border-color: rgba(255,255,255,0.05) !important;">
                        <div class="d-flex align-items-start gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 28px; height: 28px; background: rgba(220, 53, 69, 0.15); color: #f87171;">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 0.85rem;"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-white small text-truncate" style="font-size: 0.8rem;">
                                    {{ $notification->data['title'] ?? 'Notifikasi Sistem' }}
                                </div>
                                <div class="text-muted small mt-1" style="font-size: 0.74rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $notification->data['message'] ?? '' }}
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.68rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </button>
                </form>
            @empty
                <div class="px-3 py-4 text-center text-muted small">
                    <i class="bi bi-bell-slash fs-4 d-block mb-1 text-secondary opacity-75"></i>
                    <span>Tidak ada notifikasi baru</span>
                </div>
            @endforelse
        </div>

        @if(!($isCustomer ?? false))
            <div class="px-3 py-2 text-center border-top" style="border-color: var(--colors-hairline) !important; background: var(--colors-card-alt);">
                <a href="{{ route('products.low-stock') }}" class="small text-decoration-none d-flex align-items-center justify-content-center gap-1" style="color: var(--colors-accent); font-size: 11px; font-weight: 600;">
                    <span>Buka Daftar Stok Kritis</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</div>
