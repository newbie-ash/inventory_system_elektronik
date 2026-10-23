@extends('layouts.guest')

@section('title', 'Create an account')

@section('content')
<div class="auth-split-card">
    <div class="row g-0 align-items-stretch">
        <!-- Left Visual Showcase Panel (Matches UI Reference) -->
        <div class="col-lg-6 d-flex flex-column">
            <div class="auth-visual-panel h-100">
                <!-- Top Row: Brand & Back to Website Pill -->
                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="{{ route('landing') }}" class="auth-brand-logo">
                        <span class="auth-brand-dot"></span>
                        <span>Ctrl + C</span>
                    </a>
                    <a href="{{ route('landing') }}" class="auth-back-pill">
                        <span>Back to website</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <!-- Bottom Row: Slogan & Carousel Dash Indicators -->
                <div class="mt-auto pt-5">
                    <h2 class="text-white fw-medium fs-3 lh-sm mb-3">
                        Capturing Moments,<br>
                        Creating Memories
                    </h2>
                    <div class="dash-indicators">
                        <span class="dash-indicator"></span>
                        <span class="dash-indicator"></span>
                        <span class="dash-indicator active"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="col-lg-6 d-flex align-items-center">
            <div class="auth-form-panel w-100">
                <div class="mb-4">
                    <h2 class="text-dark fw-bold fs-3 mb-1">Create an account</h2>
                    <p class="text-secondary small mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none ms-1" style="color: var(--accent-blue);">
                            Log in
                        </a>
                    </p>
                </div>

                @include('components.alert')

                <form action="{{ route('register.attempt') }}" method="POST">
                    @csrf

                    <!-- Name and Role Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control-minimal @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Full name" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-5">
                            <select name="role" id="role" class="form-select-minimal @error('role') is-invalid @enderror" required>
                                <option value="pembeli" {{ old('role', 'pembeli') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3">
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control-minimal @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="Email" 
                               required>
                        @error('email')
                            <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input with Toggle Eye Icon -->
                    <div class="mb-3">
                        <div class="position-relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control-minimal pe-5 @error('password') is-invalid @enderror" 
                                   placeholder="Enter your password" 
                                   required>
                            <button type="button" 
                                    class="btn position-absolute top-50 end-0 translate-middle-y text-secondary border-0 bg-transparent pe-3" 
                                    onclick="togglePassword('password', this)" 
                                    tabindex="-1"
                                    aria-label="Toggle Password Visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1 font-monospace">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation Input -->
                    <div class="mb-3">
                        <div class="position-relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="form-control-minimal pe-5" 
                                   placeholder="Confirm your password" 
                                   required>
                            <button type="button" 
                                    class="btn position-absolute top-50 end-0 translate-middle-y text-secondary border-0 bg-transparent pe-3" 
                                    onclick="togglePassword('password_confirmation', this)" 
                                    tabindex="-1"
                                    aria-label="Toggle Confirm Password Visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Terms & Conditions Checkbox -->
                    <div class="form-check d-flex align-items-center gap-2 mb-4">
                        <input type="checkbox" name="terms" id="terms" class="form-check-input border-secondary m-0" style="accent-color: var(--accent-blue);" required checked>
                        <label for="terms" class="form-check-label small text-secondary">
                            I agree to the <a href="#" class="text-dark text-decoration-underline">Terms & Conditions</a>
                        </label>
                    </div>

                    <!-- 10% Accent CTA Button -->
                    <button type="submit" class="btn-auth-blue mb-4">
                        Create account
                    </button>
                </form>

                <!-- Divider -->
                <div class="position-relative text-center mb-4">
                    <hr class="opacity-25 m-0" style="border-color: var(--border-subtle);">
                    <span class="position-absolute top-50 start-50 translate-middle px-3 small text-muted font-monospace" style="background: #FFFFFF;">
                        Or register with
                    </span>
                </div>

                <!-- Social Login Buttons: Google & Apple -->
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('auth.social', 'google') }}" class="btn-auth-social">
                            <svg width="18" height="18" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.66v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.15z"/>
                                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.26v3.15C3.27 21.36 7.34 24 12 24z"/>
                                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.26C.46 8.16 0 9.98 0 12s.46 3.84 1.26 5.42l4.02-3.15z"/>
                                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.27 2.64 1.26 6.58l4.02 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                            </svg>
                            <span>Google</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('auth.social', 'apple') }}" class="btn-auth-social">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516s1.52.087 2.475-1.258.762-2.391.728-2.43m3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422s1.675-2.789 1.698-2.854-.597-.79-1.254-1.157a3.7 3.7 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.937-.133-2.062.247-2.955 1.127a5.7 5.7 0 0 0-1.516 3.284c-.2 1.345.14 3.743 1.353 5.518.736 1.077 1.624 2.14 2.766 2.148.988.007 1.341-.607 2.531-.607 1.189 0 1.515.607 2.532.592 1.127-.016 1.967-1.077 2.704-2.148.514-.746.85-1.427.971-1.748l-.027-.008z"/>
                            </svg>
                            <span>Apple</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
