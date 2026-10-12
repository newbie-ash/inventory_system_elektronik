@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="text-center mb-4">
    <div class="bg-primary text-white rounded-3 p-3 d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 54px; height: 54px;">
        <i class="bi bi-box-seam-fill fs-3"></i>
    </div>
    <h4 class="fw-bold mb-1">Login</h4>
    <p class="text-muted small">Inventory Management System</p>
</div>

@include('components.alert')

<form action="{{ route('login.attempt') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold small">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', 'admin@inventory.com') }}" 
                   placeholder="nama@perusahaan.com" 
                   required 
                   autofocus>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold small">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   value="password"
                   placeholder="••••••••" 
                   required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label small text-muted">Ingat sesi saya</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
    </button>
</form>

<div class="mt-4 pt-3 border-top">
    <div class="text-center mb-2">
        <span class="badge bg-light text-secondary border"></span>
    </div>
    <div class="d-grid gap-2">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="setDemo('admin@inventory.com', 'password')">
            <i class="bi bi-shield-lock me-1"></i> Login Sebagai <strong>Admin</strong>
        </button>
        <button type="button" class="btn btn-sm btn-outline-success" onclick="setDemo('staff@inventory.com', 'password')">
            <i class="bi bi-person me-1"></i> Login Sebagai <strong>Staff Gudang</strong>
        </button>
    </div>
</div>

@push('scripts')
<script>
    function setDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endpush
@endsection
