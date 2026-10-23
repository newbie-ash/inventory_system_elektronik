@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="auth-centered-card">
    <div class="mb-4 text-center">
        <a href="{{ route('landing') }}" class="auth-brand-logo mb-3 justify-content-center text-dark">
            <span class="auth-brand-dot"></span>
            <span>Ctrl + C</span>
        </a>
        <h3 class="fw-bold text-dark mb-1">Pemulihan Kata Sandi</h3>
        <p class="text-secondary small mb-0 font-monospace">Masukkan email Anda untuk instruksi reset</p>
    </div>

    @if(session('status'))
        <div class="p-3 mb-3 rounded-3 text-start small font-monospace" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.35); color: #15803d;">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5 mt-n1"></i>
                <div>
                    <div class="fw-bold text-dark mb-0.5">STATUS</div>
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif

    @if(session('demo_reset_url'))
        <div class="p-3 mb-4 rounded-3 text-start small font-monospace" style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.35);">
            <div class="text-secondary mb-1" style="font-size: 0.72rem;">Klik tautan untuk langsung reset:</div>
            <a href="{{ session('demo_reset_url') }}" class="text-break fw-bold text-decoration-underline" style="color: var(--accent-blue);">
                {{ session('demo_reset_url') }}
            </a>
        </div>
    @endif

    @include('components.alert')

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-4">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control-minimal @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="Enter your email" 
                   required 
                   autofocus>
            @error('email')
                <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-auth-blue mb-3">
            Kirim Tautan Pemulihan
        </button>
    </form>

    <div class="pt-3 border-top border-secondary border-opacity-25 text-center">
        <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">
            &larr; Kembali ke Log in
        </a>
    </div>
</div>
@endsection
