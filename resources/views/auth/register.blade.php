@extends('layouts.guest')

@section('title', 'Sign Up')

@section('content')
<div class="glass-auth-card">
    <div class="auth-heading">CREATE ACCOUNT</div>
    <div class="auth-subtitle">Register to join Ctrl+R system</div>

    @include('components.alert')

    <form action="{{ route('register.attempt') }}" method="POST">
        @csrf

        <!-- Full Name Input -->
        <div class="mb-3">
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control-glass @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" 
                   placeholder="Full Name" 
                   required 
                   autofocus>
            @error('name')
                <div class="small mt-1 text-danger" style="font-size: 0.78rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address Input -->
        <div class="mb-3">
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control-glass @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="Email Address" 
                   required>
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

        <!-- Confirm Password Input -->
        <div class="mb-3">
            <div class="position-relative">
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="form-control-glass pe-5" 
                       placeholder="Confirm Password" 
                       required>
                <button type="button" 
                        class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent pe-3" 
                        style="color: var(--text-secondary);"
                        onclick="togglePassword('password_confirmation', this)" 
                        tabindex="-1"
                        aria-label="Toggle Password Visibility">
                    <i class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>

        <!-- Terms & Conditions Checkbox -->
        <div class="form-check d-flex align-items-center gap-2 mb-4">
            <input type="checkbox" name="terms" id="terms" class="form-check-input m-0" required checked>
            <label for="terms" class="form-check-label small" style="color: var(--text-secondary); font-size: 0.82rem; cursor: pointer;">
                I agree to the <span style="color: #FFFFFF;">Terms & Conditions</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-cream-cta mb-2">
            Create Account
        </button>
    </form>
</div>

<!-- Footer Link -->
<div class="auth-footer">
    Already have an account? <a href="{{ route('login') }}">Sign In</a>
</div>
@endsection
