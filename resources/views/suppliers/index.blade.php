@extends('layouts.app')

@section('title', 'Master Data Supplier')
@section('page_heading', 'Manajemen Supplier / Pemasok')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama supplier, email, nomor telepon, alamat..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0 fw-bold">Daftar Rekanan Pemasok / Supplier</h6>
            <span class="badge bg-secondary-subtle text-secondary">{{ $suppliers->total() }} Supplier</span>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Supplier
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th>Nama Supplier</th>
                        <th>Kontak & Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Produk Disuplai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $index => $supplier)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $suppliers->firstItem() + $index }}
                            </td>
                            <td>
                                <a href="{{ route('suppliers.show', $supplier) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $supplier->name }}
                                </a>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope me-1 text-muted"></i> {{ $supplier->email ?: '-' }}</div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1 text-muted"></i> {{ $supplier->phone ?: '-' }}</div>
                            </td>
                            <td class="text-muted small text-truncate" style="max-width: 250px;">
                                {{ $supplier->address ?: '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                    {{ $supplier->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-truck fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada supplier yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($suppliers->hasPages())
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
            <div class="small text-muted">
                Menampilkan <strong>{{ $suppliers->firstItem() }}</strong> sampai <strong>{{ $suppliers->lastItem() }}</strong> dari <strong>{{ $suppliers->total() }}</strong> supplier
            </div>
            <div>
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
