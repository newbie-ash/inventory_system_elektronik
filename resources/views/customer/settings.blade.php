@extends('layouts.app')

@section('title', 'Edit Profil & Pengaturan Akun - Ctrl+R')
@section('page_heading', 'Pengaturan Akun')

@section('content')
<div class="container px-0 px-md-2" style="max-width: 1200px;">
    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Edit Profil Profil Utama</h3>
            <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                Kelola informasi pribadi, kontak, alamat pengiriman, dan keamanan akun Anda
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #6EE7B7; border-radius: 14px;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #FCA5A5; border-radius: 14px;">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Periksa kembali input Anda:</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Sidebar Card (Image 2 Layout) -->
            <div class="col-12 col-lg-4 col-xl-3">
                <div class="card p-4 text-center h-100" style="background: linear-gradient(160deg, rgba(71, 73, 115, 0.22) 0%, rgba(22, 27, 51, 0.8) 100%);">
                    <!-- Avatar Photo & Change Photo Button -->
                    <div class="position-relative d-inline-block mx-auto mb-2">
                        <div class="rounded-circle overflow-hidden mx-auto d-flex align-items-center justify-content-center shadow-lg" 
                             style="width: 105px; height: 105px; background: linear-gradient(135deg, var(--colors-accent) 0%, rgba(71, 73, 115, 0.9) 100%); border: 3px solid rgba(241, 218, 196, 0.35);">
                            @if($profile->avatar && file_exists(public_path($profile->avatar)))
                                <img src="{{ asset($profile->avatar) }}" id="avatarPreview" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="" id="avatarPreview" alt="{{ $user->name }}" class="w-100 h-100 object-fit-cover d-none">
                                <span id="avatarPlaceholder" class="display-6 fw-bold" style="color: var(--colors-accent-text);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="avatarInput" class="btn btn-sm btn-outline-glass px-3 py-1" style="font-size: 0.78rem; cursor: pointer; border-radius: 20px;">
                            <i class="bi bi-camera me-1"></i> Ubah Foto
                        </label>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewAvatarImage(this)">
                    </div>

                    <h5 class="fw-bold text-white mb-1" style="font-size: 1.15rem;">{{ $user->name }}</h5>
                    <p class="small text-secondary mb-4" style="color: var(--colors-muted) !important; font-size: 0.82rem;">
                        {{ $user->email }}
                    </p>

                    <!-- Sidebar Navigation Items (Matching Image 2) -->
                    <div class="d-flex flex-column gap-1.5 text-start w-100 mt-2">
                        <a href="{{ route('customer.settings') }}" 
                           class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none fw-bold"
                           style="background: var(--colors-accent); color: var(--colors-accent-text); box-shadow: 0 4px 14px rgba(241, 218, 196, 0.25);">
                            <i class="bi bi-person fs-5"></i>
                            <span>Profil Saya</span>
                        </a>

                        <a href="#alamat-pengiriman" 
                           class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none text-white-50"
                           style="transition: all 0.2s ease;">
                            <i class="bi bi-geo-alt fs-5"></i>
                            <span class="text-white">Daftar Alamat</span>
                        </a>

                        @if(Auth::user()->isPembeli())
                            <a href="{{ route('customer.orders.index') }}" 
                               class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none text-white-50"
                               style="transition: all 0.2s ease;">
                                <i class="bi bi-clock-history fs-5"></i>
                                <span class="text-white">Riwayat Aktivitas</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" 
                               class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none text-white-50"
                               style="transition: all 0.2s ease;">
                                <i class="bi bi-grid-1x2 fs-5"></i>
                                <span class="text-white">Dashboard Operasional</span>
                            </a>
                        @endif

                        <a href="#keamanan" 
                           class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none text-white-50"
                           style="transition: all 0.2s ease;">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span class="text-white">Keamanan & Password</span>
                        </a>

                        <a href="#notifikasi" 
                           class="d-flex align-items-center gap-2.5 px-3 py-2.5 rounded-3 text-decoration-none text-white-50"
                           style="transition: all 0.2s ease;">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="text-white">Notifikasi</span>
                        </a>
                    </div>

                    <!-- Direct Logout Button on Sidebar -->
                    <div class="pt-4 mt-auto border-top w-100" style="border-color: rgba(166, 156, 172, 0.15) !important;">
                        <button type="button" 
                                class="btn btn-outline-danger w-100 btn-sm py-2 d-flex align-items-center justify-content-center gap-2" 
                                style="border-radius: 12px; font-size: 0.85rem;"
                                onclick="document.getElementById('logoutSettingsForm').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar dari Akun</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column Form Cards (Image 2 Layout) -->
            <div class="col-12 col-lg-8 col-xl-9">
                <!-- 1. Informasi Pribadi -->
                <div class="card p-4 mb-4" style="background: linear-gradient(150deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.7) 100%);">
                    <h5 class="fw-bold text-white mb-3" style="font-size: 1.05rem; letter-spacing: -0.3px;">
                        Informasi Pribadi
                    </h5>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Nama Lengkap</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   value="{{ old('name', $user->name) }}" 
                                   placeholder="Nama Lengkap" 
                                   required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Username</label>
                            <input type="text" 
                                   name="username" 
                                   class="form-control" 
                                   value="{{ old('username', $profile->department ?? strtolower(str_replace(' ', '', $user->name))) }}" 
                                   placeholder="Username">
                        </div>
                    </div>
                </div>

                <!-- 2. Kontak & Alamat -->
                <div class="card p-4 mb-4" id="alamat-pengiriman" style="background: linear-gradient(150deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.7) 100%);">
                    <h5 class="fw-bold text-white mb-3" style="font-size: 1.05rem; letter-spacing: -0.3px;">
                        Kontak & Alamat
                    </h5>
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Alamat Email</label>
                            <div class="position-relative">
                                <input type="email" 
                                       name="email" 
                                       class="form-control pe-5" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="email@example.com" 
                                       required>
                                <span class="badge position-absolute top-50 end-0 translate-middle-y me-2.5" 
                                      style="background: rgba(241, 218, 196, 0.18); color: var(--colors-accent); border: 1px solid rgba(241, 218, 196, 0.4); font-size: 0.68rem; font-weight: 700;">
                                    Terverifikasi
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Nomor Telepon</label>
                            <input type="text" 
                                   name="phone_number" 
                                   class="form-control" 
                                   value="{{ old('phone_number', $profile->phone_number ?? '0812-3456-7890') }}" 
                                   placeholder="0812-3456-7890">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small text-secondary fw-semibold">Alamat Pengiriman Utama</label>
                            <textarea name="address" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Alamat Lengkap di Jawa, Indonesia...">{{ old('address', $profile->bio ?? 'Alamat Lengkap pengiriman pesanan Anda, RT/RW, Kecamatan, Kota/Kabupaten, Kode Pos') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 3. Keamanan Cepat -->
                <div class="card p-4 mb-4" id="keamanan" style="background: linear-gradient(150deg, rgba(71, 73, 115, 0.2) 0%, rgba(22, 27, 51, 0.7) 100%);">
                    <h5 class="fw-bold text-white mb-3" style="font-size: 1.05rem; letter-spacing: -0.3px;">
                        Keamanan Cepat
                    </h5>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Password Saat Ini</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="form-control" 
                                   placeholder="Password Saat Ini">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-secondary fw-semibold">Password Baru</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="form-control" 
                                   placeholder="Password Baru (jika ingin mengganti)">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons: Batal & Simpan Perubahan -->
                <div class="d-flex justify-content-end align-items-center gap-3 pt-2 mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-glass px-4 py-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Hidden Logout Form -->
    <form id="logoutSettingsForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

@push('scripts')
<script>
    function previewAvatarImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
