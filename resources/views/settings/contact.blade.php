@extends('layouts.app')

@section('title', 'Kontak & Lokasi - Pengaturan Akun')
@section('page_heading', 'Pengaturan Akun')

@section('content')
<div class="container px-0 px-md-2" style="max-width: 1200px;">
    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Kontak & Lokasi</h3>
            <p class="small text-muted mb-0">
                Kelola alamat email resmi, nomor telepon, dan lokasi domisili / pengiriman pesanan Anda.
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

    <form action="{{ route('settings.contact.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Sidebar Card -->
            <div class="col-12 col-lg-4">
                @include('settings.partials.sidebar')
            </div>

            <!-- Right Column Form Cards -->
            <div class="col-12 col-lg-8">
                <!-- 1. Saluran Kontak -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-inbound-fill text-info fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Saluran Komunikasi</h5>
                        </div>
                        <span class="badge badge-gradient-info font-monospace small">Informasi Terhubung</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-7">
                            <label class="form-label small text-muted fw-semibold d-flex align-items-center justify-content-between mb-1.5">
                                <span>Alamat Email <span class="text-danger">*</span></span>
                                <span class="badge badge-gradient-success font-monospace py-0.5 px-2" style="font-size: 0.68rem;">
                                    <i class="bi bi-patch-check-fill me-1 text-success"></i> Terverifikasi
                                </span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   value="{{ old('email', $user->email) }}" 
                                   placeholder="email@example.com" 
                                   required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label small text-muted fw-semibold mb-1.5">Nomor Telepon / WhatsApp</label>
                            <input type="text" 
                                   name="phone_number" 
                                   class="form-control font-monospace" 
                                   value="{{ old('phone_number', $profile->phone_number ?? '') }}" 
                                   placeholder="0812-3456-7890">
                        </div>
                    </div>
                </div>

                <!-- 2. Lokasi & Alamat Lengkap -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-warning fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Alamat & Penugasan</h5>
                        </div>
                        <span class="badge badge-gradient-neutral font-monospace small">Lokasi Fisik</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small text-muted fw-semibold">
                                {{ Auth::user()->isPembeli() ? 'Alamat Pengiriman Utama' : 'Alamat Domisili / Keterangan Penugasan Gudang' }}
                            </label>
                            <textarea name="address" 
                                      rows="4" 
                                      class="form-control" 
                                      placeholder="Tuliskan nama jalan, RT/RW, kelurahan, kecamatan, kota/kabupaten...">{{ old('address', $profile->bio ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                        <div class="d-flex align-items-center gap-2 text-white small fw-semibold mb-1">
                            <i class="bi bi-building-check text-success"></i>
                            <span>Wilayah Operasional Terdaftar</span>
                        </div>
                        <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                            Wilayah penugasan dan pengiriman sinkron otomatis dengan sistem logistik inventaris Ctrl+R.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons: Batal & Simpan Perubahan -->
                <div class="d-flex justify-content-end align-items-center gap-3 pt-2 mb-4">
                    <a href="{{ route('customer.settings') }}" class="btn btn-glass-outline font-monospace px-4 py-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary font-monospace px-4 py-2 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Kontak & Alamat
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
