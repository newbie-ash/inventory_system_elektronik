@extends('layouts.app')

@section('title', 'Catat Transaksi Stok')
@section('page_heading', 'Pencatatan Transaksi Stok (In / Out)')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Form Transaksi Pergerakan Stok</h6>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                </a>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-info border-info-subtle d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-2 mt-1"></i>
                    <div class="small">
                        <strong>Aturan Bisnis Transaksi Stok:</strong>
                        <ul class="mb-0 ps-3 mt-1">
                            <li>Untuk transaksi <strong>Barang Masuk (In)</strong>: Stok produk akan bertambah.</li>
                            <li>Untuk transaksi <strong>Barang Keluar (Out)</strong>: Stok produk akan berkurang dan <strong>tidak boleh melebihi sisa stok fisik saat ini</strong> (mencegah stok bernilai negatif).</li>
                            <li>Proses mutasi stok dan pencatatan audit trail diamankan menggunakan <strong>Database Transaction</strong> otomatis.</li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('stock-movements.store') }}" method="POST" id="stockMovementForm">
                    @csrf

                    <!-- Pilih Produk -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold small">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk Inventaris --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" 
                                        data-stock="{{ $p->stock }}" 
                                        data-price="{{ (int)$p->price }}"
                                        data-category="{{ $p->category->name ?? '-' }}"
                                        {{ (old('product_id', $selectedProductId) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} [{{ $p->sku }}] &mdash; Sisa Stok: {{ $p->stock }} unit
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Info Box Sisa Stok Produk Terpilih -->
                        <div id="productInfoCard" class="card bg-light border-0 mt-2 d-none">
                            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block">Stok Tersedia Saat Ini:</small>
                                    <span class="fs-5 fw-bold text-dark" id="currentStockDisplay">0</span> <span class="small text-muted">unit</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Kategori:</small>
                                    <span class="badge bg-secondary" id="categoryDisplay">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Jenis Transaksi <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" 
                                       class="btn-check" 
                                       name="type" 
                                       id="type_in" 
                                       value="in" 
                                       {{ old('type', $preselectedType) === 'in' ? 'checked' : '' }} 
                                       required>
                                <label class="btn btn-outline-success w-100 py-2 d-flex align-items-center justify-content-center gap-2" for="type_in">
                                    <i class="bi bi-arrow-down-left-circle fs-5"></i>
                                    <span class="fw-bold">Barang Masuk (In)</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" 
                                       class="btn-check" 
                                       name="type" 
                                       id="type_out" 
                                       value="out" 
                                       {{ old('type', $preselectedType) === 'out' ? 'checked' : '' }} 
                                       required>
                                <label class="btn btn-outline-danger w-100 py-2 d-flex align-items-center justify-content-center gap-2" for="type_out">
                                    <i class="bi bi-arrow-up-right-circle fs-5"></i>
                                    <span class="fw-bold">Barang Keluar (Out)</span>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kuantitas / Jumlah -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold small">Jumlah Kuantitas <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" 
                                   min="1" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity', 1) }}" 
                                   required>
                            <span class="input-group-text bg-light">Unit</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Kuantitas harus berupa angka positif (&gt; 0).</small>
                        @error('quantity')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <div id="negativeStockWarning" class="alert alert-danger py-2 px-3 mt-2 d-none small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <strong>Peringatan Stok Negatif:</strong> Jumlah barang keluar melebihi stok yang tersedia saat ini!
                        </div>
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold small">Catatan / Referensi Transaksi</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Contoh: Penerimaan PO INV-2026/01 dari Supplier, Pengambilan barang untuk divisi IT, dll...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="submitBtn">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const productSelect = document.getElementById('product_id');
    const infoCard = document.getElementById('productInfoCard');
    const currentStockDisplay = document.getElementById('currentStockDisplay');
    const categoryDisplay = document.getElementById('categoryDisplay');
    const quantityInput = document.getElementById('quantity');
    const typeIn = document.getElementById('type_in');
    const typeOut = document.getElementById('type_out');
    const warningAlert = document.getElementById('negativeStockWarning');
    const submitBtn = document.getElementById('submitBtn');

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            const category = selectedOption.getAttribute('data-category') || '-';
            
            infoCard.classList.remove('d-none');
            currentStockDisplay.textContent = stock;
            categoryDisplay.textContent = category;
            
            checkNegativeStock(stock);
        } else {
            infoCard.classList.add('d-none');
            warningAlert.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }

    function checkNegativeStock(currentStock) {
        const isOut = typeOut.checked;
        const qty = parseInt(quantityInput.value) || 0;
        
        if (isOut && qty > currentStock) {
            warningAlert.classList.remove('d-none');
            submitBtn.disabled = true;
        } else {
            warningAlert.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    typeIn.addEventListener('change', updateStockInfo);
    typeOut.addEventListener('change', updateStockInfo);
    quantityInput.addEventListener('input', updateStockInfo);

    // Trigger on page load (e.g. if pre-selected or old input)
    updateStockInfo();
</script>
@endpush
@endsection
