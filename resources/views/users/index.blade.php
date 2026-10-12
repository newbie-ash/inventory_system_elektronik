@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_heading', 'Manajemen Pengguna & Hak Akses (RBAC)')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
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
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
            <h6 class="mb-0 fw-bold">Daftar Akun Pengguna</h6>
            <span class="badge bg-secondary-subtle text-secondary">{{ $users->total() }} Pengguna</span>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary small">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Pengguna</th>
                        <th>Role Akses</th>
                        <th class="text-center">Aktivitas Transaksi</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end">Aksi</th>
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
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($user->role === 'admin')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">
                                        <i class="bi bi-shield-check me-1"></i> Administrator
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                                        <i class="bi bi-person me-1"></i> Staff Gudang
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    {{ $user->stock_movements_count }} Transaksi
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
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
        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3 border-top">
            <div class="small text-muted">
                Menampilkan <strong>{{ $users->firstItem() }}</strong> sampai <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
