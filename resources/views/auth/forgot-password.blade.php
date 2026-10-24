@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="glass-auth-card">
    <div class="auth-heading">RESET PASSWORD</div>
    <div class="auth-subtitle">Masukkan email Anda untuk menerima instruksi pemulihan</div>

    @if(session('status'))
        <div class="p-2.5 mb-3 text-start small" style="background: rgba(71, 73, 115, 0.25); border: 1px solid var(--border-glass); border-radius: 8px; color: var(--accent-cream); font-size: 0.8rem;">
            <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
        </div>
    @endif

    @if(session('demo_reset_url'))
        <div class="p-2.5 mb-3 text-start small" style="background: rgba(22, 27, 51, 0.9); border: 1px solid var(--border-glass); border-radius: 8px;">
            <div class="mb-1" style="font-size: 0.75rem; color: var(--text-secondary);">Tautan Reset Langsung:</div>
            <a href="{{ session('demo_reset_url') }}" class="text-break fw-semibold" style="color: var(--accent-cream); font-size: 0.8rem;">
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
                   class="form-control-glass @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="Email Address" 
                   required 
                   autofocus>
            @error('email')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-cream-cta mb-2">
            Kirim Tautan Pemulihan
        </button>
    </form>
</div>

<div class="auth-footer">
    Ingat kata sandi? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
