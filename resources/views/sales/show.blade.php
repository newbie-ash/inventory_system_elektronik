@extends('layouts.app')

@section('title', 'Invoice Penjualan #' . $sale->sale_number)
@section('page_heading', 'Detail Penjualan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('sales.index') }}" class="text-decoration-none small text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penjualan
            </a>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0 text-dark font-monospace">{{ $sale->sale_number }}</h4>
                @if($sale->status === 'completed')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                @endif

                @if($sale->payment_status === 'paid')
                    <span class="badge bg-success text-white px-2 py-1">Lunas</span>
                @elseif($sale->payment_status === 'pending')
                    <span class="badge bg-warning text-dark px-2 py-1">Menunggu Pembayaran</span>
                @elseif($sale->payment_status === 'refunded')
                    <span class="badge bg-info text-dark px-2 py-1">Dana Dikembalikan</span>
                @else
                    <span class="badge bg-danger text-white px-2 py-1">Gagal</span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Invoice
            </button>

            @if($sale->status === 'completed' && Auth::user()->isAdmin())
                <form action="{{ route('sales.cancel', $sale) }}" 
                      method="POST"
                      data-confirm="Membatalkan transaksi ini akan otomatis mengembalikan (menambah kembali) stok produk ke inventaris."
                      data-confirm-title="Batalkan Transaksi Penjualan?"
                      data-confirm-item="Transaksi #{{ $sale->sale_number }}"
                      data-confirm-type="warning"
                      data-confirm-icon="bi-arrow-counterclockwise"
                      data-confirm-btn="Ya, Batalkan Transaksi">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi (Admin)
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($sale->status !== 'cancelled')
        <!-- Operational Fulfillment Control Bar (Staff Gudang & Admin) -->
        <div class="card mb-4 p-3.5 border-0 shadow-sm" style="background: rgba(22, 27, 51, 0.95); border: 1px solid var(--colors-hairline) !important; border-radius: 14px;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(71, 73, 115, 0.35); color: var(--colors-accent);">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-white fs-6 font-monospace">Operasional Gudang & Logistik Pengiriman</div>
                        <div class="small text-secondary font-monospace mt-0.5">
                            Status saat ini:
                            @if($sale->shipping_status === 'shipped')
                                <span class="badge bg-info text-white font-monospace"><i class="bi bi-truck me-1"></i> Sedang Dalam Perjalanan Kurir</span>
                            @elseif($sale->shipping_status === 'delivered')
                                <span class="badge bg-success text-white font-monospace"><i class="bi bi-check2-circle me-1"></i> Pesanan Selesai / Telah Diterima</span>
                            @elseif($sale->shipping_status === 'packing')
                                <span class="badge font-monospace" style="background: rgba(241, 218, 196, 0.2); color: var(--colors-accent); border: 1px solid rgba(241, 218, 196, 0.35);"><i class="bi bi-box-seam me-1"></i> Sedang Dikemas di Gudang</span>
                            @else
                                <span class="badge bg-warning text-dark font-monospace">Menunggu Pembayaran</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Operational Action Buttons for Staff & Admin -->
                <div class="d-flex align-items-center gap-2">
                    @if($sale->shipping_status === 'packing' || ($sale->shipping_status === 'pending' && $sale->payment_status === 'paid'))
                        <form action="{{ route('sales.update-shipping-status', $sale) }}" 
                              method="POST"
                              data-confirm="Konfirmasi pengiriman ke kurir? Status di akun pembeli otomatis berubah menjadi Sedang Dikirim."
                              data-confirm-title="Serahkan Pesanan ke Kurir?"
                              data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                              data-confirm-type="success"
                              data-confirm-icon="bi-truck"
                              data-confirm-btn="Ya, Kirim Pesanan">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="shipping_status" value="shipped">
                            <button type="submit" class="btn btn-primary btn-sm font-monospace d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-truck"></i>
                                <span>Kirim Pesanan (Serahkan ke Kurir)</span>
                            </button>
                        </form>
                    @elseif($sale->shipping_status === 'shipped')
                        <form action="{{ route('sales.update-shipping-status', $sale) }}" 
                              method="POST"
                              data-confirm="Tandai pesanan telah sampai di tangan pembeli secara fisik dan status pengiriman diselesaikan."
                              data-confirm-title="Konfirmasi Pesanan Selesai?"
                              data-confirm-item="Pesanan #{{ $sale->sale_number }}"
                              data-confirm-type="success"
                              data-confirm-icon="bi-check2-circle"
                              data-confirm-btn="Ya, Selesaikan Pesanan">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="shipping_status" value="delivered">
                            <button type="submit" class="btn btn-success btn-sm font-monospace d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-check2-circle"></i>
                                <span>Konfirmasi Pesanan Selesai (Diterima)</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($sale->payment_status === 'pending')
        <div class="alert alert-warning border d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 mb-4 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-wallet2 fs-4 text-warning"></i>
                <div>
                    <div class="fw-bold text-dark">Transaksi ini menunggu pembayaran via Midtrans Snap</div>
                    <div class="small text-muted">Stok produk telah diamankan dalam database transaksi.</div>
                </div>
            </div>
            @if(!empty($snapToken))
                <button id="pay-button" class="btn btn-warning btn-sm fw-bold px-3 py-2 shadow-sm">
                    <i class="bi bi-credit-card me-1"></i> Bayar Sekarang (Midtrans Snap)
                </button>
            @endif
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Header Info Card -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Informasi Pelanggan</div>
                    <div class="fw-bold text-dark fs-6">{{ $sale->buyer->name ?? 'Walk-In Customer (Umum)' }}</div>
                    @if($sale->buyer)
                        <div class="small text-muted mt-1"><i class="bi bi-envelope me-1"></i>{{ $sale->buyer->email }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Waktu & Petugas</div>
                    <div class="fw-bold text-dark fs-6">{{ $sale->sold_at ? $sale->sold_at->format('d F Y - H:i') : '-' }}</div>
                    <div class="small text-muted mt-1">Dicatat oleh: <span class="fw-medium text-dark">{{ $sale->user->name ?? '-' }}</span></div>
                    <div class="small text-muted">Metode: <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Catatan Penjualan</div>
                    <div class="small text-dark fst-italic">{{ $sale->notes ?: 'Tidak ada catatan.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-basket text-primary me-1"></i> Rincian Produk Terjual
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($sale->items as $idx => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td class="font-monospace text-muted">{{ $item->product->sku ?? '-' }}</td>
                            <td class="fw-semibold text-dark">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                            <td class="text-center fw-bold">{{ $item->quantity }} unit</td>
                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-end pe-3 fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light border-top">
                    <tr>
                        <td colspan="5" class="text-end fw-bold py-3 fs-6">Grand Total Penjualan:</td>
                        <td class="text-end pe-3 fw-bold text-success font-monospace fs-5 py-3">
                            Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@if(!empty($snapToken) && $sale->payment_status === 'pending')
@push('scripts')
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.getElementById('pay-button')?.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                const trxStatus = (result && result.transaction_status) ? result.transaction_status : 'settlement';
                window.location.href = "{{ route('sales.show', $sale) }}?transaction_status=" + encodeURIComponent(trxStatus);
            },
            onPending: function(result){
                const trxStatus = (result && result.transaction_status) ? result.transaction_status : 'pending';
                window.location.href = "{{ route('sales.show', $sale) }}?transaction_status=" + encodeURIComponent(trxStatus);
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                location.reload();
            },
            onClose: function(){
                console.log('Customer menutup popup Snap tanpa menyelesaikan pembayaran');
            }
        });
    });
</script>
@endpush
@endif
