<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 50px;" class="text-center">No.</th>
                <th style="min-width: 250px;">SKU & Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Status</th>
                <th class="text-end" style="min-width: 160px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $index => $product)
                <tr>
                    <td class="text-center font-monospace small" style="color: var(--colors-muted);">
                        {{ $products->firstItem() + $index }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center" style="gap: 14px;">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="object-fit-cover flex-shrink-0" style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid var(--colors-hairline); background: var(--colors-card-alt);">
                            @else
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; border-radius: 8px; background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: var(--colors-muted);">
                                    <i class="bi bi-box-seam fs-5"></i>
                                </div>
                            @endif
                            <div class="d-flex flex-column justify-content-center" style="min-width: 0;">
                                <span class="fw-semibold text-white" style="line-height: 1.35;">{{ $product->name }}</span>
                                <span class="badge bg-transparent border font-monospace text-muted align-self-start mt-1" style="border-color: var(--colors-hairline) !important; font-size: 0.72rem;">
                                    {{ $product->sku }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-gradient-info font-monospace">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @forelse ($product->suppliers as $sup)
                            <span class="badge badge-gradient-accent font-monospace me-1 mb-1">{{ $sup->name }}</span>
                        @empty
                            <span class="small" style="color: var(--colors-muted);">-</span>
                        @endforelse
                    </td>
                    <td class="text-end font-monospace text-white fw-semibold">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="fw-bold font-monospace text-white fs-6">{{ $product->stock }}</span>
                        <div class="small font-monospace" style="color: var(--colors-muted); font-size: 0.72rem;">Min: {{ $product->min_stock }}</div>
                    </td>
                    <td class="text-center">
                        @if ($product->stock <= 0)
                            <span class="badge badge-gradient-danger font-monospace">Habis</span>
                        @elseif ($product->isLowStock())
                            <span class="badge badge-gradient-warning font-monospace">Rendah</span>
                        @else
                            <span class="badge badge-gradient-success font-monospace">Aman</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn-action btn-action-view" title="Detail Produk">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn-action btn-action-move" title="Catat Transaksi Stok">
                                <i class="bi bi-arrow-left-right"></i>
                            </a>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('products.edit', $product) }}" class="btn-action btn-action-edit" title="Ubah Produk">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" 
                                        class="btn-action btn-action-delete" 
                                        title="Hapus Produk"
                                        onclick="confirmDeleteProduct('{{ route('products.destroy', $product) }}', '{{ addslashes($product->name) }}', '{{ $product->sku }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color: var(--colors-muted);">
                        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--colors-muted);"></i>
                        Tidak ada produk yang sesuai dengan kriteria pencarian atau filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($products->hasPages())
    <div class="card-footer py-3 border-top d-flex justify-content-center ajax-pagination-wrapper" style="background: transparent; border-color: var(--colors-hairline) !important;">
        {{ $products->links('vendor.pagination.buyer-slide') }}
    </div>
@endif
