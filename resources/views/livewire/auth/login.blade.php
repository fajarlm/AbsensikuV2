@if (Session::get('success'))
    <div class="alert alert-success alert-dismissible absolute fade show shadow-lg rounded-3 mt-4" role="alert"
        style="font-size: 1rem; border-left: 6px solid #28a745;" id="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="d-flex justify-content-start align-items-center"
    style="min-height: 100vh; background:url('{{ asset('bg-login.jpg') }}'); background-size:cover; background-position:center;">

    <div class="border p-3 shadow rounded-3 ms-5 bg-light bg-transparent-50">
        <div class="card-body p-4">
            <h4>📔 Masuk untuk Memulai <span class="text-primary">Absensiku</span></h4>

            <h3 class="text-center mb-4 text-primary-emphasis">Login</h3>

            <form wire:submit.prevent="login">
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" wire:model="username"
                        class="form-control @error('username')
                    is-invalid
                @enderror"
                        placeholder="Masukkan username">
                    {{-- @error('username')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror --}}

                    <label class="form-label" for="username">Username</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4 position">
                    <input id="password" type="password" wire:model="password" class="form-control"
                        placeholder="Masukkan Password">
                    <label class="form-label" for="password">Password</label>

                    <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                        onclick="togglePassword()">
                        <i id="togglePasswordIcon" class="bi bi-eye-slash"></i>
                    </span>


                    {{-- @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror --}}
                </div>

                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            {{-- <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked /> --}}
                            <label class="form-check-label" for="form1Example3"> Remember me </label>
                        </div>
                    </div>

                    <div class="col">
                        <a wire:navigate href="/forgot-password">Forgot password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                @if (session('error'))
                    <div class="alert alert-danger mt-3 mb-0">{{ session('error') }}</div>
                @endif
            </form>
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
    </script>
@endpush
