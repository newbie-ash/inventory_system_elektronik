@extends('layouts.app')

@section('title', 'Profil Saya - Pengaturan Akun')
@section('page_heading', 'Pengaturan Akun')

@section('content')
<div class="container px-0 px-md-2" style="max-width: 1200px;">
    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Edit Profil Profil Utama</h3>
            <p class="small text-muted mb-0">
                Kelola identitas pribadi, avatar, username, dan biodata akun pengguna Anda.
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
            <!-- Left Sidebar Card -->
            <div class="col-12 col-lg-4">
                @include('settings.partials.sidebar')
            </div>

            <!-- Right Column Form Cards -->
            <div class="col-12 col-lg-8">
                <!-- 1. Informasi Profil Pribadi -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-badge text-warning fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Informasi Profil Pribadi</h5>
                        </div>
                        <span class="badge badge-gradient-accent font-monospace small">Data Identitas</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control" 
                                   value="{{ old('name', $user->name) }}" 
                                   placeholder="Nama Lengkap" 
                                   required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted fw-semibold">Username / Departemen</label>
                            <input type="text" 
                                   name="username" 
                                   class="form-control font-monospace" 
                                   value="{{ old('username', $profile->department ?? strtolower(str_replace(' ', '', $user->name))) }}" 
                                   placeholder="Username">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">Biodata / Catatan Diri</label>
                            <textarea name="bio" 
                                      rows="3" 
                                      class="form-control" 
                                      placeholder="Tuliskan keterangan singkat mengenai peran atau deskripsi diri...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Hak Akses & Status Akun -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-info fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Hak Akses & Otorisasi</h5>
                        </div>
                        <span class="badge badge-gradient-info font-monospace small">RBAC System</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(71, 73, 115, 0.16); border: 1px solid var(--colors-hairline);">
                                <small class="text-muted d-block mb-1">Peran Akses Sistem:</small>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-6 fw-bold text-white text-uppercase font-monospace">{{ $user->role }}</span>
                                    <span class="badge badge-gradient-success font-monospace" style="font-size: 0.7rem;">Aktif</span>
                                </div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                    {{ $user->isAdmin() ? 'Akses penuh ke seluruh menu master data, transaksi, laporan bisnis, dan manajemen sistem.' : ($user->isStaff() ? 'Akses operasional gudang, mutasi stok, pembelian, dan penjualan barang.' : 'Akses belanja portal katalog customer dan riwayat pesanan.') }}
                                </small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(71, 73, 115, 0.16); border: 1px solid var(--colors-hairline);">
                                <small class="text-muted d-block mb-1">Waktu Terdaftar:</small>
                                <div class="fs-6 fw-bold text-white font-monospace">{{ $user->created_at ? $user->created_at->format('d F Y H:i') : '-' }}</div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                    User ID: <span class="font-monospace text-white">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span> &bull; Terverifikasi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons: Batal & Simpan Perubahan -->
                <div class="d-flex justify-content-end align-items-center gap-3 pt-2 mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-glass-outline font-monospace px-4 py-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary font-monospace px-4 py-2 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Profil
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
