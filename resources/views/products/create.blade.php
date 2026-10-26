@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page_heading', 'Form Tambah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Informasi Produk Baru</h6>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- SKU -->
                        <div class="col-12 col-md-4">
                            <label for="sku" class="form-label fw-semibold small">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="sku" 
                                   id="sku" 
                                   class="form-control font-monospace @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku') }}" 
                                   placeholder="Contoh: PRD-ELC-001" 
                                   required>
                            <small class="text-muted" style="font-size: 0.75rem;">Harus unik dan tidak boleh duplikat.</small>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-12 col-md-8">
                            <label for="name" class="form-label fw-semibold small">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Contoh: Laptop Dell Latitude 3420" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label fw-semibold small">Kategori Produk <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga Satuan -->
                        <div class="col-12 col-md-6">
                            <label for="price" class="form-label fw-semibold small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" 
                                       step="100" 
                                       min="0" 
                                       name="price" 
                                       id="price" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', 0) }}" 
                                       required>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok Awal -->
                        <div class="col-12 col-md-6">
                            <label for="stock" class="form-label fw-semibold small">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   name="stock" 
                                   id="stock" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   value="{{ old('stock', 0) }}" 
                                   required>
                            <small class="text-muted" style="font-size: 0.75rem;">Stok awal akan dicatat otomatis sebagai riwayat stok masuk.</small>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Batas Minimum Stok -->
                        <div class="col-12 col-md-6">
                            <label for="min_stock" class="form-label fw-semibold small">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   name="min_stock" 
                                   id="min_stock" 
                                   class="form-control @error('min_stock') is-invalid @enderror" 
                                   value="{{ old('min_stock', 5) }}" 
                                   required>
                            <small class="text-muted" style="font-size: 0.75rem;">Peringatan akan muncul saat stok berada pada atau di bawah batas ini.</small>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multi-supplier (Many to Many) -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Supplier / Pemasok (Opsional, Multi-Pilih)</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="row g-2">
                                    @forelse ($suppliers as $supplier)
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="supplier_ids[]" 
                                                       value="{{ $supplier->id }}" 
                                                       id="sup_{{ $supplier->id }}"
                                                       {{ in_array($supplier->id, old('supplier_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="sup_{{ $supplier->id }}">
                                                    {{ $supplier->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted small">Belum ada supplier yang terdaftar.</div>
                                    @endforelse
                                </div>
                            </div>
                            @error('supplier_ids')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold small">Deskripsi Produk</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Spesifikasi, keterangan barang, atau catatan khusus...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto Produk -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-white">Foto Produk (Opsional)</label>
                            <div class="p-3 rounded-3 d-flex flex-wrap align-items-center gap-3" style="background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(166, 156, 172, 0.35);">
                                <!-- Square Preview -->
                                <div id="imagePreviewContainer" class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 position-relative" style="width: 84px; height: 84px; background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(166, 156, 172, 0.25);">
                                    <i class="bi bi-image fs-3" id="placeholderIcon" style="color: var(--colors-muted);"></i>
                                    <img id="imagePreview" src="" alt="Pratinjau Foto" class="d-none w-100 h-100 object-fit-cover">
                                </div>

                                <!-- Custom Upload Trigger & Status -->
                                <div class="flex-grow-1" style="min-width: 220px;">
                                    <input type="file" 
                                           name="image" 
                                           id="imageInput" 
                                           class="d-none @error('image') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg,image/webp"
                                           onchange="previewProductImage(this)">

                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <label for="imageInput" class="btn btn-glass-outline btn-sm font-monospace d-inline-flex align-items-center gap-2 px-3 py-1.5 cursor-pointer mb-0">
                                            <i class="bi bi-cloud-arrow-up-fill" style="color: var(--colors-accent);"></i>
                                            <span>Pilih Foto Produk</span>
                                        </label>
                                        <span id="selectedFileName" class="small font-monospace" style="color: var(--colors-muted);">Belum ada file dipilih</span>
                                    </div>

                                    <small class="d-block" style="font-size: 0.76rem; color: var(--colors-muted);">Format didukung: JPG, PNG, WEBP. Maksimal 2MB.</small>
                                    @error('image')
                                        <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewProductImage(input) {
    const preview = document.getElementById('imagePreview');
    const icon = document.getElementById('placeholderIcon');
    const fileNameSpan = document.getElementById('selectedFileName');
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
    } else {
        if (fileNameSpan) {
            fileNameSpan.textContent = 'Belum ada file dipilih';
            fileNameSpan.classList.remove('text-white');
        }
        preview.src = '';
        preview.classList.add('d-none');
        if (icon) icon.classList.remove('d-none');
    }
}
</script>
@endsection
