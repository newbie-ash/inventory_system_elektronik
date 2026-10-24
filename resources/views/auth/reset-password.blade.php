@extends('layouts.guest')

@section('title', 'Perbarui Kata Sandi')

@section('content')
<div class="glass-auth-card">
    <div class="auth-heading">NEW PASSWORD</div>
    <div class="auth-subtitle">Tentukan kata sandi baru untuk akun Anda</div>

    @include('components.alert')

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control-glass @error('email') is-invalid @enderror" 
                   value="{{ old('email', $email) }}" 
                   placeholder="Email Address" 
                   required>
            @error('email')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="position-relative">
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control-glass pe-5 @error('password') is-invalid @enderror" 
                       placeholder="New Password (min. 6 char)" 
                       required 
                       autofocus>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent pe-3" 
                        style="color: var(--text-secondary);"
                        onclick="togglePassword('password', this)" 
                        tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="position-relative">
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="form-control-glass pe-5" 
                       placeholder="Confirm New Password" 
                       required>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent pe-3" 
                        style="color: var(--text-secondary);"
                        onclick="togglePassword('password_confirmation', this)" 
                        tabindex="-1">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-cream-cta mb-2">
            Perbarui Kata Sandi
        </button>
    </form>
</div>

<div class="auth-footer">
    Batal perbarui? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
