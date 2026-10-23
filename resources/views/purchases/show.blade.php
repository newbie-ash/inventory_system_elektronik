@extends('layouts.app')

@section('title', 'Detail Pembelian #' . $purchase->purchase_number)
@section('page_heading', 'Detail Pembelian')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('purchases.index') }}" class="text-decoration-none small text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pembelian
            </a>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0 text-dark font-monospace">{{ $purchase->purchase_number }}</h4>
                @if($purchase->status === 'completed')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Cancelled</span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Dokumen
            </button>

            @if($purchase->status === 'completed' && Auth::user()->isAdmin())
                <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('PERINGATAN: Membatalkan transaksi ini akan otomatis mengurangi (mengembalikan) stok produk di inventaris. Lanjutkan pembatalan?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi (Admin)
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($purchase->status === 'cancelled')
        <div class="alert alert-secondary border d-flex align-items-center gap-3 py-3 mb-4">
            <i class="bi bi-info-circle-fill text-muted fs-4"></i>
            <div>
                <div class="fw-bold">Transaksi Ini Telah Dibatalkan</div>
                <div class="small text-muted">Stok barang yang sebelumnya masuk dari pembelian ini telah otomatis dikembalikan (dikurangi) dari sistem.</div>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Header Info Card -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Pemasok / Vendor</div>
                    <div class="fw-bold text-dark fs-6">{{ $purchase->supplier->name ?? '-' }}</div>
                    <div class="small text-muted mt-1"><i class="bi bi-envelope me-1"></i>{{ $purchase->supplier->email ?? '-' }}</div>
                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $purchase->supplier->phone ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Informasi Pencatatan</div>
                    <div class="fw-bold text-dark fs-6">{{ $purchase->purchased_at ? $purchase->purchased_at->format('d F Y - H:i') : '-' }}</div>
                    <div class="small text-muted mt-1">Dicatat oleh: <span class="fw-medium text-dark">{{ $purchase->user->name ?? '-' }}</span></div>
                    <div class="small text-muted">Role: <span class="badge bg-light text-dark border">{{ $purchase->user->role ?? '-' }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small">Catatan Pembelian</div>
                    <div class="small text-dark fst-italic">{{ $purchase->notes ?: 'Tidak ada catatan khusus.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 fw-bold">
            <i class="bi bi-boxes text-primary me-1"></i> Rincian Produk Masuk
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Kuantitas Masuk</th>
                        <th class="text-end">Harga Beli Satuan</th>
                        <th class="text-end pe-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @foreach($purchase->items as $idx => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                            <td class="font-monospace text-muted">{{ $item->product->sku ?? '-' }}</td>
                            <td class="fw-semibold text-dark">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                            <td class="text-center fw-bold">{{ $item->quantity }} unit</td>
                            <td class="text-end">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                            <td class="text-end pe-3 fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light border-top">
                    <tr>
                        <td colspan="5" class="text-end fw-bold py-3 fs-6">Grand Total Pembelian:</td>
                        <td class="text-end pe-3 fw-bold text-primary font-monospace fs-5 py-3">
                            Rp {{ number_format($purchase->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
