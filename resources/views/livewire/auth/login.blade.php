<div>

    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg rounded-3" role="alert" id="alert"
            style="
        position: fixed;
        top: 20px;
        right: 20px;
        font-size: 1rem;
        border-left: 6px solid #28a745;
        z-index: 9999;
        min-width: 280px; ">

            <i class="fas fa-check-circle me-2"></i>
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-start align-items-center"
        style="min-height: 100vh; background:url('{{ asset('https://wallpapers.com/images/featured/school-background-fvzmkdcjswmjz5y7.jpg') }}'); background-size:cover; background-position:center;">

        <div class="border p-3 shadow rounded-3 ms-5 bg-light bg-transparent-50">
            <div class="card-body p-4">
                <h4>📔 Masuk untuk Memulai <span class="text-primary">Absensiku</span></h4>

                <h3 class="text-center mb-4 text-primary-emphasis">Login</h3>

                <form wire:submit.prevent="login">
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" wire:model="username"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan username">
                        <label class="form-label" for="username">Username</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4 position-relative">
                        <input id="password" type="password" wire:model="password" class="form-control"
                            placeholder="Masukkan Password">
                        <label class="form-label" for="password">Password</label>

                        <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                            onclick="togglePassword()">
                            <i id="togglePasswordIcon" class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>

                    @if (session('error'))
                        <div class="alert alert-danger mt-3 mb-0">{{ session('error') }}</div>
                    @endif
                </form>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <a href="{{ route('forgot-password') }}" class="text-primary me-2" wire:navigate>
                        Lupa Password?
                    </a>
                    <span class="text-muted">atau hubungi admin</span>
                </div>
            </div>
        </div>
    </div>
</div>
