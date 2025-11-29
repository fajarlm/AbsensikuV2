<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Lupa Password</h4>


                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <form wire:submit.prevent="submit" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input wire:model.defer="email" id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="contoh@domain.com" required>


                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="btn btn-link">Kembali ke login</a>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Kirim link reset</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
