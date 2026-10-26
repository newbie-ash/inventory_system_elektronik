@extends('layouts.app')

@section('title', 'Keamanan Akun - Pengaturan Akun')
@section('page_heading', 'Pengaturan Akun')

@section('content')
<div class="container px-0 px-md-2" style="max-width: 1200px;">
    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Keamanan Akun</h3>
            <p class="small text-muted mb-0">
                Kelola kata sandi akun, perlindungan kredensial, dan otentikasi sesi Anda.
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

    <form action="{{ route('settings.security.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Sidebar Card -->
            <div class="col-12 col-lg-4">
                @include('settings.partials.sidebar')
            </div>

            <!-- Right Column Form Cards -->
            <div class="col-12 col-lg-8">
                <!-- 1. Perbarui Kata Sandi -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-key-fill text-danger fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Perbarui Kata Sandi</h5>
                        </div>
                        <span class="badge badge-gradient-danger font-monospace small">Kredensial</span>
                    </div>

                    <div class="alert alert-dark d-flex align-items-start gap-2 py-2.5 px-3 mb-4" style="background: rgba(71, 73, 115, 0.2); border: 1px solid rgba(166, 156, 172, 0.2); color: var(--colors-muted); border-radius: 10px; font-size: 0.82rem;">
                        <i class="bi bi-shield-exclamation text-warning fs-6 mt-0.5"></i>
                        <div>
                            <strong class="text-white d-block">Tips Keamanan:</strong>
                            Gunakan minimal 6 karakter kombinasi huruf besar, huruf kecil, angka, dan simbol untuk melindungi akun dari akses tak sah.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="current_password" 
                               class="form-control" 
                               placeholder="Masukkan kata sandi saat ini" 
                               required>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted fw-semibold">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="new_password" 
                                   class="form-control" 
                                   placeholder="Minimal 6 karakter" 
                                   required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted fw-semibold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi kata sandi baru" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- 2. Status Autentikasi & Sesi -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Status Keamanan & Sesi</h5>
                        </div>
                        <span class="badge badge-gradient-success font-monospace small">Enkripsi Aktif</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(71, 73, 115, 0.16); border: 1px solid var(--colors-hairline);">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted">Metode Enkripsi:</span>
                                    <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.68rem;">Bcrypt</span>
                                </div>
                                <div class="fw-semibold text-white small">Algoritma Hash Standar Laravel</div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    Seluruh password terenkripsi one-way hash aman dengan salt dinamis.
                                </small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(71, 73, 115, 0.16); border: 1px solid var(--colors-hairline);">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted">Status Sesi:</span>
                                    <span class="badge badge-gradient-success font-monospace" style="font-size: 0.68rem;">Live</span>
                                </div>
                                <div class="fw-semibold text-white small">Sesi Web Terproteksi CSRF</div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    Semua request dilindungi token anti-pemalsuan otomatis.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons: Batal & Simpan Perubahan -->
                <div class="d-flex justify-content-end align-items-center gap-3 pt-2 mb-4">
                    <a href="{{ route('customer.settings') }}" class="btn btn-glass-outline font-monospace px-4 py-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary font-monospace px-4 py-2 fw-bold">
                        <i class="bi bi-shield-check me-1"></i> Perbarui Kata Sandi
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
@endsection
