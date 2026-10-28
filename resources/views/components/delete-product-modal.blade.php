<!-- Modal Konfirmasi Hapus Produk (Modern Warm Paper Crimson Style) -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content" style="background: #FFFFFF; border: 1px solid rgba(220, 38, 38, 0.3); border-radius: 16px; color: #1A1A1C; box-shadow: 0 20px 50px rgba(28, 26, 24, 0.15); overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <!-- Glowing Trash Icon -->
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; border-radius: 50%; background: rgba(220, 38, 38, 0.1); border: 2px solid rgba(220, 38, 38, 0.3); box-shadow: 0 0 20px rgba(220, 38, 38, 0.2);">
                    <i class="bi bi-trash3-fill" style="font-size: 2rem; color: #DC2626;"></i>
                </div>

                <h4 class="fw-bold mb-1 font-monospace" style="color: #1A1A1C;" id="deleteProductModalLabel">Hapus Produk?</h4>
                <p class="text-secondary small mb-3" style="color: var(--colors-muted) !important;">Tindakan ini akan menyembunyikan produk dari sistem.</p>

                <!-- Product Info Card -->
                <div class="p-3 rounded-3 text-start mb-3" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline);">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge font-monospace" id="deleteModalProductSku" style="background: #FFFFFF; color: #1A1A1C; border: 1px solid var(--colors-hairline); font-size: 0.75rem;">SKU</span>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-monospace" style="font-size: 0.7rem;">Soft Delete</span>
                    </div>
                    <div class="fw-semibold fs-6 mt-1 text-truncate" style="color: #1A1A1C;" id="deleteModalProductName">Nama Produk</div>
                </div>

                <div class="p-2.5 rounded-3 mb-4 text-start d-flex align-items-start gap-2" style="background: rgba(220, 38, 38, 0.08); border: 1px solid rgba(220, 38, 38, 0.2); font-size: 0.8rem; color: #DC2626;">
                    <i class="bi bi-shield-check fs-6 text-danger flex-shrink-0 mt-0.5"></i>
                    <div>Riwayat transaksi, mutasi stok, dan penjualan sebelumnya <strong>tetap aman</strong> dan tidak akan hilang.</div>
                </div>

                <!-- Form Action Buttons -->
                <form id="deleteProductForm" action="" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn px-4 py-2.5 fw-semibold font-monospace flex-grow-1" data-bs-dismiss="modal" style="background: var(--colors-card-alt); border: 1px solid var(--colors-hairline); color: #1A1A1C; border-radius: 12px; transition: all 0.2s;">
                            Batal
                        </button>
                        <button type="submit" class="btn px-4 py-2.5 fw-bold font-monospace flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2" style="background: #DC2626; border: none; color: #FFFFFF; border-radius: 12px; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3); transition: all 0.2s;">
                            <i class="bi bi-trash-fill"></i>
                            <span>Ya, Hapus</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDeleteProduct(actionUrl, productName, productSku) {
        const modalEl = document.getElementById('deleteProductModal');
        const form = document.getElementById('deleteProductForm');
        const nameEl = document.getElementById('deleteModalProductName');
        const skuEl = document.getElementById('deleteModalProductSku');

        if (form && nameEl && skuEl && modalEl) {
            form.action = actionUrl;
            nameEl.textContent = productName;
            skuEl.textContent = productSku ? productSku : 'PRODUK';

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }
</script>
