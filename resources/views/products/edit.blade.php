@extends('layouts.app')

@section('title', 'Ubah Produk - ' . $product->name)
@section('page_heading', 'Form Ubah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Ubah Data Produk: {{ $product->name }}</h6>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- SKU -->
                        <div class="col-12 col-md-4">
                            <label for="sku" class="form-label fw-semibold small">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="sku" 
                                   id="sku" 
                                   class="form-control font-monospace @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku', $product->sku) }}" 
                                   required>
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
                                   value="{{ old('name', $product->name) }}" 
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                       value="{{ old('price', (int)$product->price) }}" 
                                       required>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok Saat Ini (Read-only dengan info transaksi) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold small text-muted">Stok Saat Ini</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light" value="{{ $product->stock }} unit" disabled>
                                <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-outline-success">
                                    <i class="bi bi-arrow-left-right me-1"></i> Sesuaikan Stok
                                </a>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Stok dikelola melalui menu Transaksi Stok demi integritas audit.</small>
                        </div>

                        <!-- Batas Minimum Stok -->
                        <div class="col-12 col-md-6">
                            <label for="min_stock" class="form-label fw-semibold small">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" 
                                   min="0" 
                                   name="min_stock" 
                                   id="min_stock" 
                                   class="form-control @error('min_stock') is-invalid @enderror" 
                                   value="{{ old('min_stock', $product->min_stock) }}" 
                                   required>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multi-supplier -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Supplier / Pemasok (Multi-Pilih)</label>
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
                                                       {{ in_array($supplier->id, old('supplier_ids', $selectedSuppliers)) ? 'checked' : '' }}>
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
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto Produk -->
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-white">Foto Produk</label>
                            <div class="p-3 rounded-3 d-flex flex-wrap align-items-center gap-3" style="background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(166, 156, 172, 0.35);">
                                <!-- Square Preview -->
                                <div id="imagePreviewContainer" class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0 position-relative" style="width: 88px; height: 88px; background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(166, 156, 172, 0.25);">
                                    @if ($product->image_url)
                                        <img id="imagePreview" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                                        <i class="bi bi-image fs-3 d-none" id="placeholderIcon" style="color: var(--colors-muted);"></i>
                                    @else
                                        <i class="bi bi-image fs-3" id="placeholderIcon" style="color: var(--colors-muted);"></i>
                                        <img id="imagePreview" src="" alt="Pratinjau Foto" class="d-none w-100 h-100 object-fit-cover">
                                    @endif
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
                                            <span>{{ $product->image ? 'Ganti Foto Produk' : 'Pilih Foto Produk' }}</span>
                                        </label>
                                        <span id="selectedFileName" class="small font-monospace" style="color: var(--colors-muted);">
                                            {{ $product->image ? basename($product->image) : 'Belum ada file baru dipilih' }}
                                        </span>
                                    </div>

                                    <small class="d-block" style="font-size: 0.76rem; color: var(--colors-muted);">Unggah gambar baru untuk mengganti foto saat ini. Format JPG, PNG, WEBP (Maks. 2MB).</small>

                                    @if ($product->image)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                            <label class="form-check-label small text-danger font-monospace" for="remove_image">
                                                <i class="bi bi-trash me-1"></i>Hapus foto produk saat ini
                                            </label>
                                        </div>
                                    @endif

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
                            <i class="bi bi-save me-1"></i> Perbarui Produk
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
    }
}
</script>
@endsection
