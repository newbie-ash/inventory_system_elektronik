<div class="card glass-card p-4 text-center">
    <!-- Avatar Photo & Change Photo Button -->
    <div class="position-relative d-inline-block mx-auto mb-2">
        <div class="rounded-circle overflow-hidden mx-auto d-flex align-items-center justify-content-center shadow-sm" 
             style="width: 105px; height: 105px; background: var(--colors-card-alt); border: 2px solid var(--colors-hairline);">
            @if($profile->avatar && file_exists(public_path($profile->avatar)))
                <img src="{{ asset($profile->avatar) }}" id="avatarPreview" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover">
            @else
                <img src="" id="avatarPreview" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover d-none">
                <span id="avatarPlaceholder" class="display-6 fw-bold" style="color: #1A1A1C;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            @endif
        </div>
    </div>

    @if(request()->routeIs('customer.settings*'))
        <div class="mb-2">
            <label for="avatarInput" class="btn btn-glass-outline btn-sm font-monospace px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="cursor: pointer; border-radius: 20px;">
                <i class="bi bi-camera"></i> <span>Ganti & Edit Foto</span>
            </label>
            <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="handleAvatarSelected(this)">
            <input type="hidden" name="avatar_cropped" id="avatarCroppedInput">
        </div>
        <div id="avatarStatusBadge" class="small text-warning font-monospace mb-2 d-none">
            <i class="bi bi-check-circle-fill me-1 text-success"></i> Foto telah dicocokkan
        </div>
    @else
        <div class="mb-3">
            <a href="{{ route('customer.settings') }}" class="btn btn-glass-outline btn-sm font-monospace px-3 py-1.5 d-inline-flex align-items-center gap-1.5" style="border-radius: 20px;">
                <i class="bi bi-camera"></i> <span>Ganti Foto</span>
            </a>
        </div>
    @endif

    <h5 class="fw-bold text-white mb-1">{{ $user->name }}</h5>
    <p class="small text-muted mb-2 font-monospace text-truncate px-2">{{ $user->email }}</p>
    <div>
        @if($user->isAdmin())
            <span class="badge badge-gradient-accent font-monospace px-3 py-1">
                <i class="bi bi-shield-check me-1"></i> Administrator
            </span>
        @elseif($user->isStaff())
            <span class="badge badge-gradient-success font-monospace px-3 py-1">
                <i class="bi bi-person me-1"></i> Staff Gudang
            </span>
        @else
            <span class="badge badge-gradient-info font-monospace px-3 py-1">
                <i class="bi bi-bag-check me-1"></i> Pembeli
            </span>
        @endif
    </div>

    <!-- Sidebar Navigation Items: 4 Distinct Pages -->
    <div class="d-flex flex-column text-start w-100 mt-4" style="gap: 10px;">
        <a href="{{ route('customer.settings') }}" 
           class="settings-nav-item {{ request()->routeIs('customer.settings*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span class="fw-semibold">Profil Saya</span>
        </a>

        <a href="{{ route('settings.contact') }}" 
           class="settings-nav-item {{ request()->routeIs('settings.contact*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i>
            <span class="fw-semibold">Kontak & Lokasi</span>
        </a>

        <a href="{{ route('settings.security') }}" 
           class="settings-nav-item {{ request()->routeIs('settings.security*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i>
            <span class="fw-semibold">Keamanan Akun</span>
        </a>

        <a href="{{ route('settings.preferences') }}" 
           class="settings-nav-item {{ request()->routeIs('settings.preferences*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i>
            <span class="fw-semibold">Preferensi Sistem</span>
        </a>
    </div>

    <!-- Direct Logout Button on Sidebar -->
    <div class="pt-4 mt-4 border-top w-100" style="border-color: var(--colors-hairline) !important;">
        <button type="button" 
                class="btn btn-outline-danger w-100 btn-sm py-2 d-flex align-items-center justify-content-center gap-2 font-monospace" 
                style="border-radius: 10px;"
                onclick="document.getElementById('logoutSettingsForm').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar dari Akun</span>
        </button>
    </div>
</div>
