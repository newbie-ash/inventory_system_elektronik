@extends('layouts.app')

@section('title', 'Tambah Supplier Baru')
@section('page_heading', 'Form Tambah Supplier')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-truck me-2 text-primary"></i>Pemasok Baru</h6>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small">Nama Supplier / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Contoh: PT Surya Digital Teknologi" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">Alamat Email (Opsional)</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="sales@supplier.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold small">Nomor Telepon / WhatsApp (Opsional)</label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" 
                               placeholder="08123456789 / 021-5551234">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold small">Alamat Kantor / Gudang (Opsional)</label>
                        <textarea name="address" 
                                  id="address" 
                                  rows="3" 
                                  class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Alamat lengkap supplier...">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
