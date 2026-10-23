@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan (Kasir)')
@section('page_heading', 'Input Penjualan (Stock-Out)')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-3">
        <a href="{{ route('sales.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penjualan
        </a>
    </div>

    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Header Information -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="bi bi-person-check text-primary me-1"></i> Data Transaksi & Pelanggan
                    </div>
                    <div class="card-body p-4 space-y-3">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Akun Pembeli (Opsional)</label>
                            <select name="buyer_id" class="form-select @error('buyer_id') is-invalid @enderror">
                                <option value="">-- Walk-In Customer (Umum / Tunai) --</option>
                                @foreach($buyers as $b)
                                    <option value="{{ $b->id }}" {{ old('buyer_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }} ({{ $b->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Pilih akun jika pembeli sudah terdaftar di sistem.</div>
                            @error('buyer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Tunai / Cash (Langsung Lunas)</option>
                                <option value="midtrans" {{ old('payment_method') === 'midtrans' ? 'selected' : '' }}>Midtrans Snap (QRIS, VA, E-Wallet Sandbox)</option>
                                <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank Manual</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Catatan Pesanan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Contoh: Pembelian langsung kasir toko offline">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted mb-1">Total Estimasi Tagihan:</div>
                            <div class="fs-4 fw-bold text-success font-monospace" id="grandTotalDisplay">Rp 0</div>
                            <div class="text-muted" style="font-size: 0.72rem;">* Harga diambil murni dari data produk resmi database.</div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold shadow-sm">
                                <i class="bi bi-cart-check me-1"></i> Proses Transaksi Penjualan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Detail Multi-Item Rows -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold"><i class="bi bi-basket text-primary me-1"></i> Item Barang yang Dijual (Multi-Item)</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Item
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
                                        <th style="width: 48%;" class="ps-3">Pilih Produk Elektronik <span class="text-danger">*</span></th>
                                        <th style="width: 18%;">Kuantitas <span class="text-danger">*</span></th>
                                        <th style="width: 24%;" class="text-end">Harga Satuan (Rp)</th>
                                        <th style="width: 10%;" class="text-center pe-3">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <!-- Dynamic rows inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <button type="button" class="btn btn-sm btn-light border text-primary" id="addRowBtnBottom">
                            <i class="bi bi-plus-circle me-1"></i> + Tambah Item Barang Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product Options Template -->
<template id="productOptionsTemplate">
    <option value="">-- Pilih Produk Tersedia --</option>
    @foreach($products as $prod)
        <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price > 0 ? $prod->selling_price : $prod->price }}" data-stock="{{ $prod->stock }}">
            {{ $prod->sku }} - {{ $prod->name }} [Stok: {{ $prod->stock }} unit] - Rp {{ number_format($prod->selling_price > 0 ? $prod->selling_price : $prod->price, 0, ',', '.') }}
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

    function addRow(productId = '', qty = 1) {
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="ps-3">
                <select name="items[${rowIndex}][product_id]" class="form-select form-select-sm product-select" required>
                    ${optionsHtml}
                </select>
                <div class="stock-warning text-danger small mt-1" style="font-size: 0.72rem; display: none;"></div>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control form-control-sm qty-input text-center" min="1" value="${qty}" required>
            </td>
            <td class="text-end">
                <div class="price-display fw-semibold text-dark small font-monospace">Rp 0</div>
            </td>
            <td class="text-center pe-3">
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-row-btn" title="Hapus Baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        itemsContainer.appendChild(tr);

        const select = tr.querySelector('.product-select');
        const qtyInput = tr.querySelector('.qty-input');
        const priceDisplay = tr.querySelector('.price-display');
        const warning = tr.querySelector('.stock-warning');

        if (productId) {
            select.value = productId;
        }

        function updateRowPrice() {
            const selectedOpt = select.options[select.selectedIndex];
            const price = parseFloat(selectedOpt?.getAttribute('data-price')) || 0;
            const stock = parseInt(selectedOpt?.getAttribute('data-stock')) || 0;
            const currentQty = parseInt(qtyInput.value) || 0;

            priceDisplay.textContent = 'Rp ' + price.toLocaleString('id-ID');

            if (selectedOpt.value && currentQty > stock) {
                warning.style.display = 'block';
                warning.textContent = `Peringatan: Kuantitas (${currentQty}) melebihi stok gudang (${stock})!`;
            } else {
                warning.style.display = 'none';
            }

            calculateGrandTotal();
        }

        select.addEventListener('change', updateRowPrice);
        qtyInput.addEventListener('input', updateRowPrice);

        tr.querySelector('.remove-row-btn').addEventListener('click', function () {
            if (document.querySelectorAll('.item-row').length > 1) {
                tr.remove();
                calculateGrandTotal();
            } else {
                alert('Transaksi minimal membutuhkan 1 baris produk.');
            }
        });

        rowIndex++;
        updateRowPrice();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const selectedOpt = select.options[select.selectedIndex];
            const price = parseFloat(selectedOpt?.getAttribute('data-price')) || 0;
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            total += (qty * price);
        });
        document.getElementById('grandTotalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addRowBtn').addEventListener('click', () => addRow());
    document.getElementById('addRowBtnBottom').addEventListener('click', () => addRow());

    @if(old('items'))
        @foreach(old('items') as $oldItem)
            addRow("{{ $oldItem['product_id'] ?? '' }}", "{{ $oldItem['quantity'] ?? 1 }}");
        @endforeach
    @else
        addRow();
    @endif
});
</script>
@endpush
