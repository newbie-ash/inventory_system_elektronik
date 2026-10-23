@extends('layouts.guest')

@section('title', 'Perbarui Kata Sandi')

@section('content')
<div class="auth-centered-card">
    <div class="mb-4 text-center">
        <a href="{{ route('landing') }}" class="auth-brand-logo mb-3 justify-content-center text-dark">
            <span class="auth-brand-dot"></span>
            <span>Ctrl + C</span>
        </a>
        <h3 class="fw-bold text-dark mb-1">Buat Kata Sandi Baru</h3>
        <p class="text-secondary small mb-0 font-monospace">Tentukan kata sandi baru untuk akun Anda</p>
    </div>

    @include('components.alert')

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control-minimal @error('email') is-invalid @enderror" 
                   value="{{ old('email', $email) }}" 
                   placeholder="Email" 
                   required>
            @error('email')
                <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="position-relative">
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control-minimal pe-5 @error('password') is-invalid @enderror" 
                       placeholder="Kata sandi baru (min. 6 karakter)" 
                       required 
                       autofocus>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y text-secondary border-0 bg-transparent pe-3" 
                        onclick="togglePassword('password', this)" 
                        tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="position-relative">
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="form-control-minimal pe-5" 
                       placeholder="Ulangi kata sandi baru" 
                       required>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y text-secondary border-0 bg-transparent pe-3" 
                        onclick="togglePassword('password_confirmation', this)" 
                        tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-auth-blue mb-3">
            Simpan Kata Sandi Baru
        </button>
    </form>

    <div class="pt-3 border-top border-secondary border-opacity-25 text-center">
        <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">
            &larr; Batal & Kembali ke Log in
        </a>
    </div>
</div>
@endsection
