@extends('layouts.app')

@section('title', 'Master Data Kategori')
@section('page_heading', 'Manajemen Kategori Produk')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('categories.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama atau deskripsi kategori..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
            <h6 class="mb-0 fw-bold">Daftar Kategori Produk</h6>
            <span class="badge bg-secondary-subtle text-secondary">{{ $categories->total() }} Kategori</span>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
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
                                <a href="{{ route('categories.show', $category) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                {{ $category->description ?: '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
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
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
            <div class="small text-muted">
                Menampilkan <strong>{{ $categories->firstItem() }}</strong> sampai <strong>{{ $categories->lastItem() }}</strong> dari <strong>{{ $categories->total() }}</strong> kategori
            </div>
            <div>
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
