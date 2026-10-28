@extends('layouts.app')

@section('title', 'Preferensi Sistem - Pengaturan Akun')
@section('page_heading', 'Pengaturan Akun')

@section('content')
<div class="container px-0 px-md-2" style="max-width: 1200px;">
    <!-- Page Title -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Preferensi Sistem</h3>
            <p class="small text-muted mb-0">
                Kustomisasi antarmuka pengguna, notifikasi operasional, dan parameter sistem Ctrl+R.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #6EE7B7; border-radius: 14px;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form action="{{ route('settings.preferences.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Sidebar Card -->
            <div class="col-12 col-lg-4">
                @include('settings.partials.sidebar')
            </div>

            <!-- Right Column Form Cards -->
            <div class="col-12 col-lg-8">
                <!-- 1. Tampilan & Desain Antarmuka -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-palette-fill text-warning fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Tampilan & Tema</h5>
                        </div>
                        <span class="badge badge-gradient-accent font-monospace small">UI Styling</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: var(--colors-card-alt); border: 1.5px solid var(--colors-accent);">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-white small">Warm Paper UI</span>
                                    <span class="badge badge-gradient-accent font-monospace" style="font-size: 0.68rem;">Default</span>
                                </div>
                                <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                                    Palet warna Cream (#F8F5EE) &amp; Black Charcoal (#1A1A1C).
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: var(--colors-card-alt); border: 1px dashed var(--colors-hairline); opacity: 0.75;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-white small">Dark Charcoal UI</span>
                                    <span class="badge badge-gradient-neutral font-monospace" style="font-size: 0.68rem;">Mendatang</span>
                                </div>
                                <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                                    Tema gelap alternatif untuk pencahayaan ruang kerja malam hari.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Notifikasi & Peringatan Operasional -->
                <div class="card glass-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--colors-hairline) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-bell-fill text-info fs-5"></i>
                            <h5 class="fw-bold text-white mb-0" style="font-size: 1.05rem;">Notifikasi & Peringatan</h5>
                        </div>
                        <span class="badge badge-gradient-info font-monospace small">Real-Time Alerts</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                            <div>
                                <div class="fw-semibold text-white small">Peringatan Stok Kritis (Low Stock Alert)</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Tampilkan badge indikator merah menyala pada navigasi saat produk menipis (&le; 10 unit).</div>
                            </div>
                            <div class="form-check form-switch mb-0 fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" checked disabled style="cursor: default;">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                            <div>
                                <div class="fw-semibold text-white small">Slide Paginator Interaktif</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Navigasi tabel bergaya slide minimalis dengan kontrol Sebelumnya/Selanjutnya kompak.</div>
                            </div>
                            <div class="form-check form-switch mb-0 fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" checked disabled style="cursor: default;">
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                            <div>
                                <div class="fw-semibold text-white small">Format Regional Indonesia (IDR & Waktu Lokal)</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Mata uang Rupiah (`Rp`), kalender 24 jam (WIB/WITA/WIT), dan pemisah ribuan titik.</div>
                            </div>
                            <div class="form-check form-switch mb-0 fs-5">
                                <input class="form-check-input" type="checkbox" role="switch" checked disabled style="cursor: default;">
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
                        <i class="bi bi-check2-circle me-1"></i> Simpan Preferensi
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
