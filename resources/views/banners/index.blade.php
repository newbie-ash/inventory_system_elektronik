@extends('layouts.app')

@section('title', 'Kelola Banner Promo - Ctrl+R')
@section('page_heading', 'Kelola Banner Promo')

@section('content')
<div class="container-fluid px-0" style="max-width: 1200px;">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-white mb-1" style="letter-spacing: -0.5px;">Banner Promo Beranda</h4>
            <p class="small text-secondary mb-0" style="color: var(--colors-muted) !important;">
                Perbarui konten banner, tag promo, judul, deskripsi, dan link voucher yang tampil pada dashboard pembeli.
            </p>
        </div>
        <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNewBanner" aria-expanded="false">
            <i class="bi bi-plus-lg me-1"></i> Buat Banner Baru
        </button>
    </div>

    <!-- Alert Notifications -->
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

    <!-- Collapse: Form Tambah Banner Baru -->
    <div class="collapse mb-4" id="collapseNewBanner">
        <div class="card p-4" style="background: linear-gradient(145deg, rgba(71, 73, 115, 0.25) 0%, rgba(22, 27, 51, 0.85) 100%);">
            <h5 class="fw-bold text-white mb-3"><i class="bi bi-plus-circle me-2" style="color: var(--colors-accent);"></i>Tambah Banner Promo Baru</h5>
            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-white">Tag / Label Promo</label>
                        <input type="text" name="tag" class="form-control" placeholder="Contoh: PROMO AKHIR BULAN:" value="{{ old('tag', 'PROMO SPESIAL:') }}">
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label small text-white">Judul Utama Banner <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Potongan hingga 20% Gaming Gear" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Tuliskan keterangan detail promo atau syarat ketentuan singkat...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Upload Foto Banner Promo -->
                    <div class="col-12">
                        <label class="form-label small text-white fw-semibold">Foto / Gambar Banner Promo (Opsional)</label>
                        <div class="p-3 rounded-3 d-flex flex-wrap align-items-center gap-3" style="background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(166, 156, 172, 0.35);">
                            <div id="newBannerPreviewContainer" class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 position-relative" style="width: 120px; height: 70px; background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(166, 156, 172, 0.25);">
                                <i class="bi bi-image fs-3" id="newBannerPlaceholderIcon" style="color: var(--colors-muted);"></i>
                                <img id="newBannerPreview" src="" alt="Pratinjau Banner" class="d-none w-100 h-100 object-fit-cover">
                            </div>
                            <div class="flex-grow-1" style="min-width: 220px;">
                                <input type="file" name="image" id="newBannerImageInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewNewBannerImage(this)">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <label for="newBannerImageInput" class="btn btn-glass-outline btn-sm font-monospace d-inline-flex align-items-center gap-2 px-3 py-1.5 cursor-pointer mb-0">
                                        <i class="bi bi-cloud-arrow-up-fill" style="color: var(--colors-accent);"></i>
                                        <span>Pilih Gambar Promo</span>
                                    </label>
                                    <span id="newBannerFileName" class="small font-monospace" style="color: var(--colors-muted);">Belum ada gambar dipilih</span>
                                </div>
                                <small class="d-block" style="font-size: 0.76rem; color: var(--colors-muted);">Rekomendasi rasio lanskap. Maksimal 3MB (JPG, PNG, WEBP).</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="form-label small text-white">Teks Tombol CTA <span class="text-danger">*</span></label>
                        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', 'Klaim Voucher') }}" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label small text-white">Target Link / URL <span class="text-danger">*</span></label>
                        <input type="text" name="button_url" class="form-control" value="{{ old('button_url', '#katalog-grid') }}" required>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="newActiveSwitch" checked>
                            <label class="form-check-label small text-white" for="newActiveSwitch">Aktifkan Langsung</label>
                        </div>
                    </div>
                    <div class="col-12 text-end pt-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Banner Baru
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Preview Banner Card (Matching Customer Dashboard Look) -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small font-monospace text-uppercase" style="color: var(--colors-accent); font-weight: 700; letter-spacing: 0.8px;">
                <i class="bi bi-eye-fill me-1"></i> Live Preview Tampilan Pembeli
            </span>
            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.35);">
                Ukuran Real di Beranda
            </span>
        </div>

        <div class="card p-4 p-md-5 promo-banner-preview position-relative overflow-hidden d-flex flex-column justify-content-center"
             style="background: linear-gradient(135deg, rgba(22, 27, 51, 0.98) 0%, rgba(71, 73, 115, 0.4) 55%, rgba(22, 27, 51, 0.98) 100%) !important; border: 1px solid rgba(166, 156, 172, 0.22) !important; border-radius: 24px !important; min-height: 220px; box-shadow: 0 18px 42px -10px rgba(0, 0, 0, 0.55) !important;">
            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-12 col-md-7 col-lg-7">
                    <span id="previewTag" class="text-uppercase fw-semibold mb-2 d-inline-block font-monospace" style="color: var(--colors-muted); font-size: 0.82rem; letter-spacing: 0.75px;">
                        {{ $activeBanner->tag ?? 'PROMO SPESIAL GAJIAN:' }}
                    </span>
                    <h2 id="previewTitle" class="fw-bold text-white mb-2" style="font-size: clamp(1.4rem, 2.5vw, 2.1rem); letter-spacing: -0.5px; line-height: 1.25;">
                        {{ $activeBanner->title ?? 'Potongan hingga 15% untuk kategori PC & Gadget' }}
                    </h2>
                    <p id="previewDesc" class="text-white-50 small mb-3 mb-md-4" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
                        {{ $activeBanner->description ?? 'Dapatkan penawaran terbatas untuk produk elektronik & hardware pilihan minggu ini.' }}
                    </p>
                    <div class="d-flex align-items-center gap-1.5 pt-1">
                        <span style="width: 26px; height: 5px; border-radius: 4px; background: var(--colors-accent); display: inline-block; box-shadow: 0 0 10px rgba(241, 218, 196, 0.4);"></span>
                        <span style="width: 18px; height: 5px; border-radius: 4px; background: rgba(166, 156, 172, 0.35); display: inline-block;"></span>
                        <span style="width: 18px; height: 5px; border-radius: 4px; background: rgba(166, 156, 172, 0.35); display: inline-block;"></span>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-5 text-md-end d-flex flex-column align-items-md-end justify-content-center gap-3">
                    <div id="liveBannerVisualWrapper" class="{{ $activeBanner->image_url ? '' : 'd-none' }}">
                        <img id="liveBannerVisual" src="{{ $activeBanner->image_url ?? '' }}" alt="Banner Graphic" class="img-fluid rounded-3 object-fit-cover shadow" style="max-height: 120px; max-width: 210px; border: 1px solid rgba(166, 156, 172, 0.25);">
                    </div>
                    <span id="previewButton" class="btn btn-primary px-4 py-2.5 fw-bold" style="border-radius: 12px; font-size: 0.95rem; pointer-events: none;">
                        {{ $activeBanner->button_text ?? 'Klaim Voucher' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Edit Banner Aktif -->
    <div class="card p-4 p-md-4 mb-4" style="background: linear-gradient(160deg, rgba(71, 73, 115, 0.22) 0%, rgba(22, 27, 51, 0.8) 100%);">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3" style="border-color: var(--colors-hairline) !important;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square fs-5" style="color: var(--colors-accent);"></i>
                <h5 class="fw-bold text-white mb-0">Edit Banner Promo Utama</h5>
            </div>
            @if($activeBanner->exists)
                <span class="badge" style="background: rgba(241, 218, 196, 0.15); color: var(--colors-accent); border: 1px solid rgba(241, 218, 196, 0.3);">
                    Banner ID: #{{ $activeBanner->id }}
                </span>
            @endif
        </div>

        @if($activeBanner->exists)
            <form action="{{ route('banners.update', $activeBanner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
        @else
            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
        @endif
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-white fw-semibold">Tag / Sub-Heading</label>
                    <input type="text" 
                           name="tag" 
                           id="inputTag"
                           class="form-control" 
                           placeholder="PROMO SPESIAL GAJIAN:" 
                           value="{{ old('tag', $activeBanner->tag) }}"
                           oninput="updatePreview()">
                    <div class="form-text text-secondary" style="font-size: 11px;">Teks kecil di atas judul utama</div>
                </div>

                <div class="col-12 col-md-8">
                    <label class="form-label small text-white fw-semibold">Judul Utama Banner <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="title" 
                           id="inputTitle"
                           class="form-control" 
                           placeholder="Potongan hingga 15% untuk kategori PC & Gadget" 
                           value="{{ old('title', $activeBanner->title) }}" 
                           required
                           oninput="updatePreview()">
                    <div class="form-text text-secondary" style="font-size: 11px;">Gunakan kalimat penawaran yang menarik minat konsumen</div>
                </div>

                <div class="col-12">
                    <label class="form-label small text-white fw-semibold">Deskripsi / Penjelasan Promo</label>
                    <textarea name="description" 
                              id="inputDesc"
                              class="form-control" 
                              rows="2" 
                              placeholder="Keterangan singkat tentang promo ini..."
                              oninput="updatePreview()">{{ old('description', $activeBanner->description) }}</textarea>
                </div>

                <!-- Upload & Edit Foto Banner Promo -->
                <div class="col-12">
                    <label class="form-label small text-white fw-semibold">Foto / Gambar Banner Promo (Opsional)</label>
                    <div class="p-3 rounded-3 d-flex flex-wrap align-items-center gap-3" style="background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(166, 156, 172, 0.35);">
                        <div id="editBannerPreviewContainer" class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 position-relative" style="width: 130px; height: 75px; background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(166, 156, 172, 0.25);">
                            @if($activeBanner->image_url)
                                <img id="editBannerPreview" src="{{ $activeBanner->image_url }}" alt="Banner" class="w-100 h-100 object-fit-cover">
                                <i class="bi bi-image fs-3 d-none" id="editBannerPlaceholderIcon" style="color: var(--colors-muted);"></i>
                            @else
                                <i class="bi bi-image fs-3" id="editBannerPlaceholderIcon" style="color: var(--colors-muted);"></i>
                                <img id="editBannerPreview" src="" alt="Pratinjau Banner" class="d-none w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="flex-grow-1" style="min-width: 220px;">
                            <input type="file" name="image" id="editBannerImageInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewEditBannerImage(this)">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <label for="editBannerImageInput" class="btn btn-glass-outline btn-sm font-monospace d-inline-flex align-items-center gap-2 px-3 py-1.5 cursor-pointer mb-0">
                                    <i class="bi bi-cloud-arrow-up-fill" style="color: var(--colors-accent);"></i>
                                    <span>{{ $activeBanner->image ? 'Ganti Gambar Promo' : 'Unggah Gambar Promo' }}</span>
                                </label>
                                <span id="editBannerFileName" class="small font-monospace" style="color: var(--colors-muted);">
                                    {{ $activeBanner->image ? basename($activeBanner->image) : 'Belum ada gambar baru dipilih' }}
                                </span>
                            </div>
                            <small class="d-block" style="font-size: 0.76rem; color: var(--colors-muted);">Rekomendasi rasio lanskap. Maksimal 3MB (JPG, PNG, WEBP).</small>
                            @if($activeBanner->image)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_banner_image" onchange="toggleRemoveBannerImage(this)">
                                    <label class="form-check-label small text-danger font-monospace" for="remove_banner_image">
                                        <i class="bi bi-trash me-1"></i>Hapus gambar banner promo saat ini
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label small text-white fw-semibold">Teks Tombol Aksi <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="button_text" 
                           id="inputBtnText"
                           class="form-control" 
                           placeholder="Klaim Voucher" 
                           value="{{ old('button_text', $activeBanner->button_text) }}" 
                           required
                           oninput="updatePreview()">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label small text-white fw-semibold">Target Link / URL Aksi <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="button_url" 
                           class="form-control" 
                           placeholder="#katalog-grid atau /catalog?category_id=1" 
                           value="{{ old('button_url', $activeBanner->button_url) }}" 
                           required>
                </div>

                <div class="col-12 d-flex align-items-center justify-content-between pt-3 border-top mt-4" style="border-color: var(--colors-hairline) !important;">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeStatusSwitch" {{ old('is_active', $activeBanner->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label small text-white fw-semibold" for="activeStatusSwitch">
                            Tampilkan Banner di Beranda Pembeli
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 12px;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Banner
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Riwayat Daftar Semua Banner -->
    @if($banners->count() > 1)
        <div class="card p-4" style="background: linear-gradient(160deg, rgba(22, 27, 51, 0.95) 0%, rgba(13, 12, 29, 0.95) 100%);">
            <h6 class="fw-bold text-white mb-3">Daftar Banner Promo Terdaftar</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Judul Banner</th>
                            <th>Tag</th>
                            <th>Tombol</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-white">{{ $item->title }}</div>
                                    <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                </td>
                                <td><span class="badge bg-light text-white font-monospace">{{ $item->tag }}</span></td>
                                <td><span class="small text-secondary">{{ $item->button_text }} ({{ $item->button_url }})</span></td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.4);">Aktif di Beranda</span>
                                    @else
                                        <span class="badge" style="background: rgba(166, 156, 172, 0.15); color: var(--colors-muted);">Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">
                                        @if(!$item->is_active)
                                            <form action="{{ route('banners.update', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="title" value="{{ $item->title }}">
                                                <input type="hidden" name="tag" value="{{ $item->tag }}">
                                                <input type="hidden" name="description" value="{{ $item->description }}">
                                                <input type="hidden" name="button_text" value="{{ $item->button_text }}">
                                                <input type="hidden" name="button_url" value="{{ $item->button_url }}">
                                                <input type="hidden" name="is_active" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" style="height: 32px; font-size: 12px;">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('banners.destroy', $item) }}" 
                                              method="POST"
                                              data-confirm="Banner promo ini akan dihapus dari sistem."
                                              data-confirm-title="Hapus Banner Promo?"
                                              data-confirm-item="{{ $item->title }}"
                                              data-confirm-type="danger"
                                              data-confirm-icon="bi-image"
                                              data-confirm-btn="Ya, Hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="height: 32px; font-size: 12px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updatePreview() {
        const tagInput = document.getElementById('inputTag');
        const titleInput = document.getElementById('inputTitle');
        const descInput = document.getElementById('inputDesc');
        const btnTextInput = document.getElementById('inputBtnText');

        const previewTag = document.getElementById('previewTag');
        const previewTitle = document.getElementById('previewTitle');
        const previewDesc = document.getElementById('previewDesc');
        const previewButton = document.getElementById('previewButton');

        if (previewTag && tagInput) {
            previewTag.textContent = tagInput.value || 'PROMO SPESIAL:';
        }
        if (previewTitle && titleInput) {
            previewTitle.textContent = titleInput.value || 'Judul Banner Promo';
        }
        if (previewDesc && descInput) {
            previewDesc.textContent = descInput.value || '';
            previewDesc.style.display = descInput.value ? 'block' : 'none';
        }
        if (previewButton && btnTextInput) {
            previewButton.textContent = btnTextInput.value || 'Klaim Voucher';
        }
    }

    function previewNewBannerImage(input) {
        const preview = document.getElementById('newBannerPreview');
        const icon = document.getElementById('newBannerPlaceholderIcon');
        const fileNameSpan = document.getElementById('newBannerFileName');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (fileNameSpan) {
                fileNameSpan.textContent = file.name;
                fileNameSpan.classList.add('text-white');
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (icon) icon.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewEditBannerImage(input) {
        const preview = document.getElementById('editBannerPreview');
        const icon = document.getElementById('editBannerPlaceholderIcon');
        const fileNameSpan = document.getElementById('editBannerFileName');
        const liveVisual = document.getElementById('liveBannerVisual');
        const liveVisualWrapper = document.getElementById('liveBannerVisualWrapper');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (fileNameSpan) {
                fileNameSpan.textContent = file.name;
                fileNameSpan.classList.add('text-white');
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (icon) icon.classList.add('d-none');

                if (liveVisual && liveVisualWrapper) {
                    liveVisual.src = e.target.result;
                    liveVisualWrapper.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function toggleRemoveBannerImage(checkbox) {
        const liveVisualWrapper = document.getElementById('liveBannerVisualWrapper');
        if (checkbox.checked) {
            if (liveVisualWrapper) liveVisualWrapper.classList.add('d-none');
        } else {
            if (liveVisualWrapper) liveVisualWrapper.classList.remove('d-none');
        }
    }
</script>
@endpush
@endsection
