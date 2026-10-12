@extends('layouts.app')

@section('title', 'Detail Pengguna - ' . $user->name)
@section('page_heading', 'Profil Pengguna & Log Aktivitas')

@section('content')
<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Informasi Akun</h6>
            </div>
            <div class="card-body p-4 text-center">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-3 mb-3 shadow-sm" style="width: 72px; height: 72px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>

                <div class="mb-4">
                    @if ($user->role === 'admin')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i> Administrator
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            <i class="bi bi-person me-1"></i> Staff Gudang
                        </span>
                    @endif
                </div>

                <div class="p-3 bg-light rounded-3 text-start mb-4">
                    <small class="text-muted d-block">Terdaftar Pada</small>
                    <span class="fw-semibold text-dark">{{ $user->created_at->translatedFormat('d F Y H:i') }}</span>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i> Ubah
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-light border">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold">10 Aktivitas Transaksi Stok Terakhir Oleh Pengguna Ini</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase text-secondary small">
                            <tr>
                                <th>Jenis</th>
                                <th>Produk</th>
                                <th class="text-center">Kuantitas</th>
                                <th>Catatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->stockMovements as $mov)
                                <tr>
                                    <td>
                                        @if ($mov->type === 'in')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-arrow-down-left"></i> Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-arrow-up-right"></i> Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $mov->product->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $mov->product->sku ?? '-' }}</small>
                                    </td>
                                    <td class="text-center fw-bold {{ $mov->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }}
                                    </td>
                                    <td class="small text-muted">{{ $mov->notes ?: '-' }}</td>
                                    <td class="small text-muted">{{ $mov->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Pengguna ini belum pernah mencatat transaksi stok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
