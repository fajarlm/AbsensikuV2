<div>
    <div class="container-fluid py-4">
        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Manajemen User</h2>
                <p class="text-muted mb-0">Kelola semua pengguna sistem</p>
            </div>
            <div class="">
                <div class="btn-group dropstart">
                    <button type="button" class="btn btn-warning dropdown-toggle " data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-print"></i> Export
                    </button>
                    <div class="dropdown-menu">
                        <button wire:click="exportExcel" href="" style="font-size: 18px"
                            class=" text-success dropdown-item"><i class="fas fa-file-excel"></i>
                            EXCEL</button>
                        <button wire:click="exportPdf" style="font-size: 18px" class=" text-danger dropdown-item"><i
                                class="fas fa-file-pdf"></i>
                            PDF</button>
                    </div>
                </div>
                <a href="{{ route('admin.user.trash') }}" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Data Sampah
                </a>
                {{-- <button class="btn btn-primary" wire:click="create" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah User
                </button> --}}
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 text-danger rounded p-3 me-3">
                                <i class="bi bi-shield-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Admin</h6>
                                <h3 class="mb-0">{{ $adminCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                                <i class="bi bi-person-video3 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Guru</h6>
                                <h3 class="mb-0">{{ $teacherCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Siswa</h6>
                                <h3 class="mb-0">{{ $studentCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total User</h6>
                                <h3 class="mb-0">{{ $userCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                                placeholder="Cari nama atau username...">
                        </div>
                    </div>

                    <!-- Filter Role -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Role</label>
                        <select wire:model.live="filterRole" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Guru</option>
                            <option value="student">Siswa</option>
                        </select>
                    </div>

                    <!-- Filter Gender -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jenis Kelamin</label>
                        <select wire:model.live="filterGender" class="form-select">
                            <option value="">Semua</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Tampilkan</label>
                        <select wire:model.live="perPage" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">Nama</th>
                                <th style="width: 15%">Username</th>
                                <th style="width: 12%">Role</th>
                                <th style="width: 13%">Jenis Kelamin</th>
                                <th style="width: 12%">Tanggal Dibuat</th>
                                <th style="width: 10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $item)
                                <tr wire:key="user-{{ $item->id }}">
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($item->profile)
                                                <img src="{{ asset('storage/' . $item->profile) }}"
                                                    class="rounded-circle me-2" width="32" height="32"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person text-white"></i>
                                                </div>
                                            @endif
                                            {{ $item->name }}
                                        </div>
                                    </td>
                                    <td>{{ $item->username ?? '-' }}</td>
                                    <td>
                                        @if ($item->role == 'admin')
                                            <span class="badge rounded-pill bg-danger">Admin</span>
                                        @elseif ($item->role == 'teacher')
                                            <span class="badge rounded-pill bg-success">Guru</span>
                                        @else
                                            <span class="badge rounded-pill bg-primary">Siswa</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->gender == 'female')
                                            <span class="badge rounded-pill bg-danger">Perempuan</span>
                                        @else
                                            <span class="badge rounded-pill bg-info">Laki-laki</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                            data-bs-target="#userModal" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            wire:click="confirmDelete({{ $item->id }})"
                                            class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada data user</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                        dari {{ $users->total() }} data
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    {{-- <div class="modal fade" id="userModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-md-6">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" wire:model="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Masukkan username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Password
                                    @if (!$isEdit)
                                        <span class="text-danger">*</span>
                                    @endif
                                    @if ($isEdit)
                                        <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                                    @endif
                                </label>
                                <input type="password" wire:model="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" wire:model="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Konfirmasi password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select wire:model="role" class="form-select @error('role') is-invalid @enderror">
                                    <option value="">Pilih Role</option>
                                    <option value="teacher">Guru</option>
                                    <option value="student">Siswa</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select wire:model="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Profile Photo -->
                            <div class="col-12">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" wire:model="profile"
                                    class="form-control @error('profile') is-invalid @enderror" accept="image/*">
                                @error('profile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 2MB (JPG, PNG)</small>

                                <!-- Preview -->
                                <div class="mt-2">
                                    @if ($profile)
                                        <img src="{{ $profile->temporaryUrl() }}" class="img-thumbnail"
                                            width="100">
                                    @elseif($old_profile)
                                        <img src="{{ asset('storage/' . $old_profile) }}" class="img-thumbnail"
                                            width="100">
                                    @endif
                                </div>

                                <div wire:loading wire:target="profile" class="text-primary mt-2">
                                    <i class="spinner-border spinner-border-sm"></i> Uploading...
                                </div>
                            </div>
                        </div>
                        <h4>Isi Data Tambahan User </h4>
                        <hr class="col-12">
                        <div class="">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="resetForm">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-save me-1"></i> Simpan
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus user <strong>{{ $name }}</strong>?</p>
                    <p class="text-danger small mb-0 mt-2">
                        <i class="bi bi-info-circle me-1"></i>Data yang dihapus dapat dikembalikan!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Handle modal open/close
            window.addEventListener('open-modal', event => {
                const modal = new bootstrap.Modal(document.getElementById(event.detail.modal));
                modal.show();
            });

            window.addEventListener('close-modal', event => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(event.detail.modal));
                if (modal) {
                    modal.hide();
                }
            });
        </script>
    @endpush
</div>
