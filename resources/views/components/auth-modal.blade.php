<!-- Clean Ctrl + C Minimalist Auth Modal (Light Mode) -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content bg-white" style="border: 1px solid var(--border-subtle); border-radius: 16px; box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.12);">
            <!-- Modal Header with Tab Navigation -->
            <div class="modal-header border-bottom pb-2.5 pt-3 px-4" style="border-color: var(--border-subtle) !important;">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <ul class="nav nav-pills gap-1.5" id="authTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-1 px-3 rounded-2 small font-monospace" 
                                    id="modal-login-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#modal-login" 
                                    type="button" 
                                    role="tab">
                                Masuk
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-1 px-3 rounded-2 small font-monospace" 
                                    id="modal-register-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#modal-register" 
                                    type="button" 
                                    role="tab">
                                Daftar Baru
                            </button>
                        </li>
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="tab-content" id="authTabContent">
                    <!-- Tab 1: Login Form -->
                    <div class="tab-pane fade show active" id="modal-login" role="tabpanel">
                        <form action="{{ route('login.attempt') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-secondary small font-monospace">EMAIL</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label text-secondary small font-monospace mb-0">PASSWORD</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none" style="font-size: 0.72rem; color: var(--accent-blue);">Lupa kata sandi?</a>
                                </div>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" id="modal-remember" class="form-check-input" style="accent-color: var(--accent-blue);">
                                <label for="modal-remember" class="form-check-label small text-secondary">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-blue-primary w-100 py-2">
                                Masuk ke Sistem
                            </button>
                        </form>
                    </div>

                    <!-- Tab 2: Register Form -->
                    <div class="tab-pane fade" id="modal-register" role="tabpanel">
                        <form action="{{ route('register.attempt') }}" method="POST">
                            @csrf
                            <div class="mb-2.5">
                                <label class="form-label text-secondary small font-monospace">NAMA LENGKAP</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                            </div>
                            <div class="mb-2.5">
                                <label class="form-label text-secondary small font-monospace">EMAIL</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-2.5">
                                <label class="form-label text-secondary small font-monospace">ROLE</label>
                                <select name="role" class="form-select" required>
                                    <option value="pembeli" selected>Pembeli (Customer)</option>
                                    <option value="staff">Staff Gudang & Kasir</option>
                                    <option value="admin">Admin / Owner</option>
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-secondary small font-monospace">PASSWORD</label>
                                    <input type="password" name="password" class="form-control" placeholder="Min. 6 char" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-secondary small font-monospace">KONFIRMASI</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-blue-primary w-100 py-2">
                                Daftar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
