@extends('layouts.app')

@section('title', 'Profil Saya - Pengaturan Akun')
@section('page_heading', 'Pengaturan Akun')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    /* Dark Theme Cropper Styling */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
    .cropper-view-box {
        outline: 2px solid var(--colors-accent) !important;
        outline-color: var(--colors-accent) !important;
    }
    .cropper-line, .cropper-point {
        background-color: var(--colors-accent) !important;
    }
    .cropper-point.point-se {
        width: 8px;
        height: 8px;
    }
    .crop-stage {
        max-height: 420px;
        min-height: 320px;
        height: 360px;
        background: radial-gradient(circle at 50% 50%, #171b30 0%, #090814 100%);
        border: 1px solid var(--colors-hairline);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .crop-stage img {
        max-width: 100%;
        display: block;
    }
    .cropper-bg {
        background-image: none !important;
        background-color: #0b0d1b !important;
    }
    .avatar-live-circle {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--colors-accent);
        box-shadow: 0 0 18px rgba(226, 232, 240, 0.25);
        background: #000;
        margin: 0 auto;
    }
</style>
@endpush

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
                            <div class="p-3 rounded-3 h-100" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
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
                            <div class="p-3 rounded-3 h-100" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
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

    <!-- Modal Edit, Crop, dan Cocokkan Foto Profil -->
    <div class="modal fade" id="avatarCropModal" tabindex="-1" aria-labelledby="avatarCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border: 1px solid var(--colors-hairline); background: #FFFFFF; box-shadow: 0 20px 40px rgba(0,0,0,0.12);">
                <div class="modal-header border-bottom pb-3" style="border-color: var(--colors-hairline) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-crop text-warning fs-5"></i>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="avatarCropModalLabel" style="font-size: 1.1rem;">
                                Edit, Crop & Cocokkan Foto Profil
                            </h5>
                            <small class="text-muted">Sesuaikan ukuran, orientasi, dan posisi foto agar pas dalam lingkaran avatar.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 p-md-4">
                    <div class="row g-4">
                        <!-- Main Crop Canvas Stage -->
                        <div class="col-12 col-lg-8">
                            <div class="crop-stage mb-2">
                                <img id="cropTargetImage" src="" alt="Pratinjau Foto">
                            </div>
                            <div class="d-flex align-items-center justify-content-between text-muted small px-1 font-monospace" style="font-size: 0.75rem;">
                                <span><i class="bi bi-arrows-move me-1"></i> Geser mouse untuk menggeser foto</span>
                                <span><i class="bi bi-mouse me-1"></i> Scroll mouse untuk zoom</span>
                            </div>
                        </div>

                        <!-- Right Control Panel: Tools & Live Preview -->
                        <div class="col-12 col-lg-4 d-flex flex-column justify-content-between">
                            <div>
                                <!-- Live Circular Preview -->
                                <div class="p-3 rounded-3 mb-3 text-center" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                                    <div class="small text-muted fw-semibold mb-2 font-monospace">HASIL LINGKARAN AVATAR</div>
                                    <div class="avatar-live-circle">
                                        <div id="avatarCirclePreview" style="width: 84px; height: 84px; overflow: hidden; border-radius: 50%;"></div>
                                    </div>
                                    <div class="small text-muted mt-2" style="font-size: 0.72rem;">Pratinjau tampilan profil di navbar & komentar</div>
                                </div>

                                <!-- Editing Tools -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-semibold d-block mb-1">Perbesar / Perkecil (Zoom):</label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-glass-outline btn-sm flex-fill font-monospace" id="cropZoomIn">
                                            <i class="bi bi-zoom-in me-1"></i> Zoom +
                                        </button>
                                        <button type="button" class="btn btn-glass-outline btn-sm flex-fill font-monospace" id="cropZoomOut">
                                            <i class="bi bi-zoom-out me-1"></i> Zoom -
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-semibold d-block mb-1">Putar & Balik Orientasi:</label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-glass-outline btn-sm flex-fill font-monospace" id="cropRotateLeft" title="Putar 90° ke kiri">
                                            <i class="bi bi-arrow-counterclockwise"></i> 90° Kiri
                                        </button>
                                        <button type="button" class="btn btn-glass-outline btn-sm flex-fill font-monospace" id="cropRotateRight" title="Putar 90° ke kanan">
                                            <i class="bi bi-arrow-clockwise"></i> 90° Kanan
                                        </button>
                                        <button type="button" class="btn btn-glass-outline btn-sm font-monospace" id="cropFlipH" title="Balik Horizontal">
                                            <i class="bi bi-symmetry-vertical"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-semibold d-block mb-1">Mencocokkan Frame:</label>
                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning font-monospace text-start py-2 d-flex align-items-center justify-content-between" id="cropFitCenter">
                                            <span><i class="bi bi-aspect-ratio me-1.5"></i> Cocokkan Pas Frame</span>
                                            <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.65rem;">Auto-Fit</span>
                                        </button>
                                        <div class="btn-group btn-group-sm w-100" role="group">
                                            <button type="button" class="btn btn-glass-outline font-monospace active" id="cropRatioCircle">
                                                <i class="bi bi-circle me-1"></i> 1:1 Lingkaran
                                            </button>
                                            <button type="button" class="btn btn-glass-outline font-monospace" id="cropRatioFree">
                                                <i class="bi bi-bounding-box me-1"></i> Bebas
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-top" style="border-color: var(--colors-hairline) !important;">
                                <button type="button" class="btn btn-glass-outline btn-sm font-monospace w-100 mb-2" id="cropReset">
                                    <i class="bi bi-arrow-repeat me-1"></i> Reset Penyesuaian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top pt-3 d-flex justify-content-between" style="border-color: var(--colors-hairline) !important;">
                    <button type="button" class="btn btn-glass-outline font-monospace px-3" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary font-monospace px-4 fw-bold" id="cropApplyBtn">
                        <i class="bi bi-check2-circle me-1.5"></i> Terapkan & Cocokkan Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    let cropperInstance = null;
    let flipScaleX = 1;
    const cropModalEl = document.getElementById('avatarCropModal');
    const cropModal = new bootstrap.Modal(cropModalEl);
    const cropTargetImage = document.getElementById('cropTargetImage');

    function handleAvatarSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!file.type.match(/^image\//i)) {
                alert('Silakan pilih file gambar yang valid (JPEG, PNG, WEBP).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                cropTargetImage.src = e.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    }

    cropModalEl.addEventListener('shown.bs.modal', function () {
        if (cropperInstance) {
            cropperInstance.destroy();
        }
        flipScaleX = 1;

        cropperInstance = new Cropper(cropTargetImage, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.85,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: '#avatarCirclePreview',
            ready: function () {
                const circleBox = document.getElementById('avatarCirclePreview');
                if (circleBox) {
                    circleBox.style.borderRadius = '50%';
                }
            }
        });
    });

    cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
    });

    document.getElementById('cropZoomIn')?.addEventListener('click', () => cropperInstance?.zoom(0.1));
    document.getElementById('cropZoomOut')?.addEventListener('click', () => cropperInstance?.zoom(-0.1));
    document.getElementById('cropRotateLeft')?.addEventListener('click', () => cropperInstance?.rotate(-90));
    document.getElementById('cropRotateRight')?.addEventListener('click', () => cropperInstance?.rotate(90));

    document.getElementById('cropFlipH')?.addEventListener('click', function () {
        if (!cropperInstance) return;
        flipScaleX = -flipScaleX;
        cropperInstance.scaleX(flipScaleX);
    });

    document.getElementById('cropFitCenter')?.addEventListener('click', function () {
        if (!cropperInstance) return;
        cropperInstance.reset();
        cropperInstance.setAspectRatio(1);
    });

    document.getElementById('cropReset')?.addEventListener('click', function () {
        if (!cropperInstance) return;
        flipScaleX = 1;
        cropperInstance.reset();
        cropperInstance.scaleX(1);
    });

    document.getElementById('cropRatioCircle')?.addEventListener('click', function () {
        if (!cropperInstance) return;
        cropperInstance.setAspectRatio(1);
        this.classList.add('active');
        document.getElementById('cropRatioFree')?.classList.remove('active');
    });

    document.getElementById('cropRatioFree')?.addEventListener('click', function () {
        if (!cropperInstance) return;
        cropperInstance.setAspectRatio(NaN);
        this.classList.add('active');
        document.getElementById('cropRatioCircle')?.classList.remove('active');
    });

    document.getElementById('cropApplyBtn')?.addEventListener('click', function () {
        if (!cropperInstance) return;

        const canvas = cropperInstance.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.92);

        // Update sidebar preview
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        if (preview) {
            preview.src = croppedDataUrl;
            preview.classList.remove('d-none');
        }
        if (placeholder) {
            placeholder.classList.add('d-none');
        }

        // Set hidden cropped data URI
        const croppedInput = document.getElementById('avatarCroppedInput');
        if (croppedInput) {
            croppedInput.value = croppedDataUrl;
        }

        // Show status badge on sidebar
        const statusBadge = document.getElementById('avatarStatusBadge');
        if (statusBadge) {
            statusBadge.classList.remove('d-none');
        }

        cropModal.hide();
    });
</script>
@endpush
@endsection
