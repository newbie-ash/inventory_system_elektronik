@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="glass-auth-card">
    <div class="auth-heading">WELCOME BACK</div>
    <div class="auth-subtitle">Sign in to manage your electronics inventory</div>

    @if(session('info'))
        <div class="p-2.5 mb-3 text-start small" style="background: rgba(71, 73, 115, 0.25); border: 1px solid var(--border-glass); border-radius: 8px; color: var(--accent-cream); font-size: 0.8rem;">
            <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
        </div>
    @endif

    @include('components.alert')

    <form action="{{ route('login.attempt') }}" method="POST">
        @csrf

        <!-- Email Input -->
        <div class="mb-3">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control-glass @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="Email" 
                   required 
                   autofocus>
            @error('email')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Input -->
        <div class="mb-3">
            <div class="position-relative">
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control-glass pe-5 @error('password') is-invalid @enderror" 
                       placeholder="Password" 
                       required>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent pe-3" 
                        style="color: var(--text-secondary);"
                        onclick="togglePassword('password', this)" 
                        tabindex="-1"
                        aria-label="Toggle Password Visibility">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
            @error('password')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check d-flex align-items-center gap-2 mb-0">
                <input type="checkbox" name="remember" id="remember" class="form-check-input m-0">
                <label for="remember" class="form-check-label small" style="color: var(--text-secondary); font-size: 0.82rem; cursor: pointer;">
                    Remember Me
                </label>
            </div>
            <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: var(--text-secondary); font-size: 0.82rem;">
                Forgot Password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-cream-cta mb-2">
            Sign In
        </button>
    </form>

    <!-- Divider -->
    <div class="auth-divider">
        <span>or sign in with</span>
    </div>

    <!-- Social Login: Google -->
    <a href="{{ route('auth.social', 'google') }}" class="btn-social-glass">
        <svg width="18" height="18" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.66v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.15z"/>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.26v3.15C3.27 21.36 7.34 24 12 24z"/>
            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.26C.46 8.16 0 9.98 0 12s.46 3.84 1.26 5.42l4.02-3.15z"/>
            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.27 2.64 1.26 6.58l4.02 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
        </svg>
    </a>
</div>

<!-- Footer Link -->
<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
</div>
@endsection
