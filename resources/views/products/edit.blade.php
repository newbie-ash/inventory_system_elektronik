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
                <form action="{{ route('products.update', $product) }}" method="POST">
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
@endsection
