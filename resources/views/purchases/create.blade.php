@extends('layouts.app')

@section('title', 'Buat Transaksi Pembelian (Stock-In)')
@section('page_heading', 'Input Pembelian (Stock-In)')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-3">
        <a href="{{ route('purchases.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pembelian
        </a>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Header Info -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="bi bi-info-circle text-primary me-1"></i> Informasi Pembelian
                    </div>
                    <div class="card-body p-4 space-y-3">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Pemasok / Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Tanggal Pembelian</label>
                            <input type="datetime-local" name="purchased_at" class="form-control @error('purchased_at') is-invalid @enderror" value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}">
                            @error('purchased_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Catatan / Keterangan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Contoh: Pengiriman batch 1 invoice #INV-SUP-01">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted mb-1">Total Estimasi Pembelian:</div>
                            <div class="fs-4 fw-bold text-primary font-monospace" id="grandTotalDisplay">Rp 0</div>
                            <div class="text-muted" style="font-size: 0.72rem;">* Total final dihitung murni di server secara akurat.</div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi Pembelian
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Detail Multi-Item Rows -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold"><i class="bi bi-boxes text-primary me-1"></i> Daftar Barang Masuk (Multi-Item)</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @error('items')
                            <div class="alert alert-danger m-3 py-2 small">{{ $message }}</div>
                        @enderror

                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="itemsTable">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th style="width: 45%;" class="ps-3">Produk Elektronik <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Qty Masuk <span class="text-danger">*</span></th>
                                        <th style="width: 25%;">Harga Modal (Rp) <span class="text-danger">*</span></th>
                                        <th style="width: 10%;" class="text-center pe-3">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <!-- Dynamic rows will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <button type="button" class="btn btn-sm btn-light border text-primary" id="addRowBtnBottom">
                            <i class="bi bi-plus-circle me-1"></i> + Tambah Baris Barang Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template Options for Products -->
<template id="productOptionsTemplate">
    <option value="">-- Pilih Produk --</option>
    @foreach($products as $prod)
        <option value="{{ $prod->id }}" data-cost="{{ $prod->purchase_price > 0 ? $prod->purchase_price : round($prod->price * 0.8, 2) }}">
            {{ $prod->sku }} - {{ $prod->name }} (Stok saat ini: {{ $prod->stock }})
        </option>
    @endforeach
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsContainer = document.getElementById('itemsContainer');
    const optionsHtml = document.getElementById('productOptionsTemplate').innerHTML;
    let rowIndex = 0;

    function addRow(productId = '', qty = 1, cost = '') {
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="ps-3">
                <select name="items[${rowIndex}][product_id]" class="form-select form-select-sm product-select" required>
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control form-control-sm qty-input text-center" min="1" value="${qty}" required>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][unit_cost]" class="form-control form-control-sm cost-input text-end" min="0" step="100" placeholder="0" value="${cost}" required>
            </td>
            <td class="text-center pe-3">
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-row-btn" title="Hapus Baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        itemsContainer.appendChild(tr);

        const select = tr.querySelector('.product-select');
        const costInput = tr.querySelector('.cost-input');

        if (productId) {
            select.value = productId;
        }

        select.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const defaultCost = selectedOpt.getAttribute('data-cost');
            if (defaultCost && !costInput.value) {
                costInput.value = defaultCost;
            }
            calculateGrandTotal();
        });

        tr.querySelector('.qty-input').addEventListener('input', calculateGrandTotal);
        costInput.addEventListener('input', calculateGrandTotal);

        tr.querySelector('.remove-row-btn').addEventListener('click', function () {
            if (document.querySelectorAll('.item-row').length > 1) {
                tr.remove();
                calculateGrandTotal();
            } else {
                alert('Transaksi minimal membutuhkan 1 baris produk.');
            }
        });

        rowIndex++;
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            total += (qty * cost);
        });
        document.getElementById('grandTotalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addRowBtn').addEventListener('click', () => addRow());
    document.getElementById('addRowBtnBottom').addEventListener('click', () => addRow());

    // Prepopulate with old items if available
    @if(old('items'))
        @foreach(old('items') as $oldItem)
            addRow("{{ $oldItem['product_id'] ?? '' }}", "{{ $oldItem['quantity'] ?? 1 }}", "{{ $oldItem['unit_cost'] ?? '' }}");
        @endforeach
    @else
        // Initial empty row
        addRow();
    @endif
});
</script>
@endpush
