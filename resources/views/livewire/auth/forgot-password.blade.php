<!-- resources/views/livewire/auth/forgot-password.blade.php -->
<div style="background:url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZ87JumWASQDZf45b-8X9_DNvnoLgBv7XnbA&s');absolute;background-size:cover;height:100%;width:100%">

    <div class="container pt-5" style="min-height: 91vh;">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="row align-items-center">
                            <!-- Form Section -->
                            <h2 class="card-title fs-3 text-center mb-1 text-primary-emphasis">Reset Password</h2>
                            <div class="col-md-7 order-2 order-md-1">

                                {{-- Alert Messages --}}
                                @if (session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session()->has('verified'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        {{ session('verified') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($step === 1)
                                    {{-- Step 1: Verifikasi Username & Code --}}
                                    <form wire:submit.prevent="verifyCode">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input wire:model="username" id="username" type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                placeholder="Masukkan username Anda">
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="verification_code" class="form-label">Kode Verifikasi</label>
                                            <input wire:model="verification_code" id="verification_code" type="text"
                                                class="form-control @error('verification_code') is-invalid @enderror"
                                                placeholder="Masukkan kode verifikasi">
                                            @error('verification_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Masukkan kode verifikasi yang Anda terima
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none">
                                                <i class="bi bi-arrow-left me-1"></i> Kembali ke login
                                            </a>
                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                                <span wire:loading.remove wire:target="verifyCode">
                                                    Verifikasi Kode <i class="bi bi-arrow-right ms-1"></i>
                                                </span>
                                                <span wire:loading wire:target="verifyCode">
                                                    <span class="spinner-border spinner-border-sm me-2"
                                                        role="status"></span>
                                                    Memverifikasi...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    {{-- Step 2: Input Password Baru --}}
                                    <form wire:submit.prevent="resetPassword">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password Baru</label>
                                            <div class="input-group">
                                                <input wire:model.defer="password" id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Masukkan password baru">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePassword()">
                                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Minimal 6 karakter</small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="password_confirmation" class="form-label">Konfirmasi
                                                Password</label>
                                            <div class="input-group">
                                                <input wire:model.defer="password_confirmation"
                                                    id="password_confirmation" type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    placeholder="Masukkan ulang password baru">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePasswordConfirmation()">
                                                    <i class="bi bi-eye-slash" id="togglePasswordConfIcon"></i>
                                                </button>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" wire:click="backToStep1"
                                                class="btn btn-link text-decoration-none">
                                                <i class="bi bi-arrow-left me-1"></i> Kembali
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                wire:loading.attr="disabled">
                                                <span wire:loading.remove wire:target="resetPassword">
                                                    Reset Password <i class="bi bi-check-circle ms-1"></i>
                                                </span>
                                                <span wire:loading wire:target="resetPassword">
                                                    <span class="spinner-border spinner-border-sm me-2"
                                                        role="status"></span>
                                                    Memproses...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>

                            <!-- Image Section -->
                            <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
                                <img src="{{ asset('forget-password-bg.avif') }}" alt="Reset Password Illustration"
                                    class="img-fluid rounded" style="max-height: 300px;">
                                <!-- Kalau gambar belum ada, pakai placeholder -->
                                {{-- <div class="p-5 bg-light rounded">
                                <i class="bi bi-shield-lock text-primary" style="font-size: 120px;"></i>
                                <p class="mt-3 text-muted">Reset Password</p>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
          function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
          function togglePasswordConfirmation() {
            const passwordInput = document.getElementById('password_confirmation');
            const icon = document.getElementById('togglePasswordConfIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endpush