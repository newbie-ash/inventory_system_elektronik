@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_heading', 'Manajemen Pengguna & Hak Akses (RBAC)')

@section('content')
<div class="card glass-card mb-3 p-3">
    <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-6">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--colors-muted); pointer-events: none;"></i>
                <input type="text" 
                       name="search" 
                       class="form-control ps-5" 
                       placeholder="Cari nama atau email pengguna..." 
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff Gudang</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1 font-monospace">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('users.index') }}" class="btn btn-glass-outline font-monospace" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card glass-card">
    <div class="card-header d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2.5">
            <h6 class="mb-0 fw-bold text-white">Daftar Akun Pengguna</h6>
            <span class="badge badge-gradient-accent font-monospace">{{ $users->total() }} Pengguna</span>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 font-monospace">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No.</th>
                        <th>Pengguna</th>
                        <th>Role Akses</th>
                        <th class="text-center">Aktivitas Transaksi</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="text-center text-muted small">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($user->role === 'admin')
                                    <span class="badge badge-gradient-accent px-3 py-1 font-monospace">
                                        <i class="bi bi-shield-check me-1"></i> Administrator
                                    </span>
                                @else
                                    <span class="badge badge-gradient-success px-3 py-1 font-monospace">
                                        <i class="bi bi-person me-1"></i> Staff Gudang
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-gradient-info font-monospace">
                                    {{ $user->stock_movements_count }} Transaksi
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="btn-action btn-action-view" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn-action btn-action-edit" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user) }}" 
                                              method="POST" 
                                              class="d-inline m-0 p-0"
                                              data-confirm="Akun pengguna ini akan dihapus dari sistem. Pengguna tidak akan dapat login kembali."
                                              data-confirm-title="Hapus Akun Pengguna?"
                                              data-confirm-item="{{ $user->name }} ({{ $user->email }})"
                                              data-confirm-type="danger"
                                              data-confirm-icon="bi-person-x-fill"
                                              data-confirm-btn="Ya, Hapus Akun">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Belum ada pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($users->hasPages())
        <div class="card-footer py-3 border-top d-flex justify-content-center" style="background: transparent; border-color: var(--colors-hairline) !important;">
            {{ $users->links('vendor.pagination.buyer-slide') }}
        </div>
    @endif
</div>
@endsection
