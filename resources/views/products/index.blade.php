@extends('layouts.app')

@section('title', 'Katalog Produk')
@section('page_heading', 'Manajemen & Katalog Produk')

@section('content')
<div class="card glass-card mb-3 p-3">
    <!-- Search, Filter & Sort Form -->
    <form method="GET" action="{{ route('products.index') }}" id="adminProductFilterForm" class="row g-2 align-items-center">
        <!-- Search -->
        <div class="col-12 col-lg-3 col-xl-3">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       style="font-size: 0.86rem;"
                       placeholder="Cari nama atau SKU produk..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-2">
            <select name="category_id" class="form-select text-truncate" style="font-size: 0.84rem;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Status Stok -->
        <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-2">
            <select name="stock_status" class="form-select text-truncate" style="font-size: 0.84rem;">
                <option value="">Semua Status</option>
                <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                <option value="empty" {{ request('stock_status') === 'empty' ? 'selected' : '' }}>Stok Habis</option>
            </select>
        </div>

        <!-- Sorting Dropdown -->
        <div class="col-6 col-sm-6 col-md-3 col-lg-2 col-xl-2">
            <select name="sort" class="form-select text-truncate" style="font-size: 0.84rem;">
                <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Waktu Dibuat</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama Produk</option>
                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Harga Produk</option>
                <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Jumlah Stok</option>
            </select>
        </div>

        <!-- Sort Direction & Action Buttons -->
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 d-flex align-items-center gap-1.5">
            <select name="direction" class="form-select text-center flex-grow-1" style="font-size: 0.84rem; min-width: 90px;" title="Arah Pengurutan">
                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>&uarr; Asc</option>
                <option value="desc" {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>&darr; Desc</option>
            </select>
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; padding: 0;" title="Terapkan Filter">
                <i class="bi bi-funnel-fill"></i>
            </button>
            @if(request()->anyFilled(['search', 'category_id', 'stock_status', 'sort', 'direction']))
                <a href="{{ route('products.index') }}" class="btn btn-glass-outline d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; padding: 0;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Produk Inventaris</h6>
            <span class="badge badge-gradient-accent font-monospace" id="productTotalBadge">{{ $products->total() }} Produk Ditemukan</span>
        </div>

        @if(Auth::user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 font-monospace">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Produk Baru</span>
            </a>
        @endif
    </div>

    <div id="productTableWrapper" class="position-relative">
        <!-- Loading Spinner Overlay -->
        <div id="productTableLoading" class="position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(248, 245, 238, 0.75); z-index: 10; backdrop-filter: blur(2px); border-radius: 14px;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat...</span>
            </div>
        </div>

        <div id="productTableContainer">
            @include('products.partials.table', ['products' => $products])
        </div>
    </div>
</div>

@include('components.delete-product-modal')

@push('scripts')
<script>
    let adminFilterTimeout = null;
    let adminAbortController = null;

    function triggerAdminFilter() {
        clearTimeout(adminFilterTimeout);
        adminFilterTimeout = setTimeout(fetchFilteredProducts, 300);
    }

    function fetchFilteredProducts(pageUrl = null) {
        const form = document.getElementById('adminProductFilterForm');
        const container = document.getElementById('productTableContainer');
        const overlay = document.getElementById('productTableLoading');
        if (!container || !form) return;

        let url;
        if (pageUrl) {
            url = new URL(pageUrl, window.location.origin);
        } else {
            url = new URL(form.action, window.location.origin);
            const formData = new FormData(form);
            for (const [key, value] of formData.entries()) {
                if (value) {
                    url.searchParams.append(key, value);
                }
            }
        }

        url.searchParams.set('ajax', '1');

        if (adminAbortController) {
            adminAbortController.abort();
        }
        adminAbortController = new AbortController();

        if (overlay) {
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        }
        container.style.opacity = '0.5';

        fetch(url.toString(), {
            signal: adminAbortController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            if (overlay) {
                overlay.classList.remove('d-flex');
                overlay.classList.add('d-none');
            }

            const cleanUrl = new URL(url.toString());
            cleanUrl.searchParams.delete('ajax');
            window.history.pushState(null, '', cleanUrl.toString());

            bindAdminPagination();
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                container.style.opacity = '1';
                if (overlay) {
                    overlay.classList.remove('d-flex');
                    overlay.classList.add('d-none');
                }
            }
        });
    }

    function bindAdminPagination() {
        const paginationLinks = document.querySelectorAll('#productTableContainer .ajax-pagination-wrapper a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchFilteredProducts(this.href);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('adminProductFilterForm');
        if (form) {
            form.addEventListener('input', triggerAdminFilter);
            form.addEventListener('change', triggerAdminFilter);
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                triggerAdminFilter();
            });
        }

        bindAdminPagination();
    });
</script>
@endpush
@endsection
