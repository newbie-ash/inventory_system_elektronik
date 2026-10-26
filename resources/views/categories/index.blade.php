@extends('layouts.app')

@section('title', 'Master Data Kategori')
@section('page_heading', 'Manajemen Kategori Produk')

@section('content')
<div class="card glass-card mb-3 p-3">
    <form method="GET" action="{{ route('categories.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-8">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       placeholder="Cari nama atau deskripsi kategori..." 
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1 font-monospace">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="btn btn-glass-outline font-monospace" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Kategori Produk</h6>
            <span class="badge badge-gradient-accent font-monospace">{{ $categories->total() }} Kategori</span>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 font-monospace">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td>
                                <a href="{{ route('categories.show', $category) }}" class="fw-bold text-white text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                {{ $category->description ?: '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-gradient-info font-monospace">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('categories.show', $category) }}" class="btn-action btn-action-view" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn-action btn-action-edit" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline m-0 p-0"
                                          data-confirm="Kategori ini akan dihapus dari sistem."
                                          data-confirm-title="Hapus Kategori?"
                                          data-confirm-item="{{ $category->name }}"
                                          data-confirm-type="danger"
                                          data-confirm-icon="bi-folder-x"
                                          data-confirm-btn="Ya, Hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-tags fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada kategori yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($categories->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $categories->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
