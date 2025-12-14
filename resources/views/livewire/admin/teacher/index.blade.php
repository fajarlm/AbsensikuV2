<div>
    <div class="container-fluid py-4">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Manajemen Guru</h2>
                <p class="text-muted mb-0">Kelola data guru dan informasi kepegawaian</p>
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
                <a href="{{ route('admin.user.teacher.trash') }}" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Data Sampah
                </a>
                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#teacherModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Guru
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                                <i class="bi bi-person-video3 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Guru</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
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
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Guru Aktif</h6>
                                <h3 class="mb-0">{{ $stats['aktif'] }}</h3>
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
                                <i class="bi bi-gender-male fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Laki-laki</h6>
                                <h3 class="mb-0">{{ $stats['male'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 text-danger rounded p-3 me-3">
                                <i class="bi bi-gender-female fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Perempuan</h6>
                                <h3 class="mb-0">{{ $stats['female'] }}</h3>
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
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input wire:model.live.debounce.300ms="search" type="number" class="form-control"
                                placeholder="Cari nama, username, atau NIP...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Status</label>
                        <select wire:model.live="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted">Jenis Kelamin</label>
                        <select wire:model.live="filterGender" class="form-select">
                            <option value="">Semua</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>

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

        <!-- Teachers Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Username</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Tgl Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $index => $teacher)
                                <tr wire:key="teacher-{{ $teacher->id }}">
                                    <td>{{ $teachers->firstItem() + $index }}</td>
                                    <td>
                                        @if ($teacher->user->profile)
                                            <img src="{{ asset('storage/' . $teacher->user->profile) }}"
                                                alt="{{ $teacher->user->name }}" class="rounded-circle"
                                                width="45" height="45" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px; font-size: 18px;">
                                                {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $teacher->user->name }}</strong>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $teacher->nip }}</span></td>
                                    <td>{{ $teacher->user->username }}</td>
                                    <td>
                                        @if ($teacher->user->gender == 'male')
                                            <span class="badge bg-primary">
                                                <i class="bi bi-gender-male"></i> L
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-gender-female"></i> P
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($teacher->status == 'active')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-x-circle"></i> Non-Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $teacher->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button wire:click="edit({{ $teacher->id }})" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#teacherModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="deleteConfirm({{ $teacher->id }})"
                                            class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data guru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $teachers->firstItem() ?? 0 }} - {{ $teachers->lastItem() ?? 0 }} dari
                        {{ $teachers->total() }} data
                    </div>
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade " id="teacherModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Edit Data Guru' : 'Tambah Guru Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Section: Data Akun -->
                            <div class="col-12 ">
                                <h6 class="border-bottom pb-2 mb-1">
                                    <i class="bi bi-person-circle me-2"></i>Data Akun
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input wire:model="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Masukkan username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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
                                <input wire:model="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input wire:model="password_confirmation" type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Konfirmasi password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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

                            <div class="col-md-6">
                                <label class="form-label">Foto Profil</label>
                                <input wire:model="profile" type="file"
                                    class="form-control @error('profile') is-invalid @enderror" accept="image/*">
                                @error('profile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 2MB (JPG, PNG)</small>

                                <div class="mt-2">
                                    @if ($profile)
                                        <img src="{{ $profile->temporaryUrl() }}" class="img-thumbnail"
                                            width="100">
                                    @elseif($oldProfile)
                                        <img src="{{ asset('storage/' . $oldProfile) }}" class="img-thumbnail"
                                            width="100">
                                    @endif
                                </div>

                                <div wire:loading wire:target="profile" class="text-primary mt-2">
                                    <i class="spinner-border spinner-border-sm"></i> Uploading...
                                </div>
                            </div>

                            <!-- Section: Data Kepegawaian -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-1 mt-2">
                                    <i class="bi bi-person-badge me-2"></i>Data Kepegawaian
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                <input wire:model="nip" type="text"
                                    class="form-control @error('nip') is-invalid @enderror"
                                    placeholder="Masukkan NIP">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non-Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="resetForm">Batal</button>
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
    </div>

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
                    <p class="mb-0">Apakah Anda yakin ingin menghapus guru <strong>{{ $name }}</strong>?</p>
                    <p class="text-danger small mb-0 mt-2">
                        <i class="bi bi-info-circle me-1"></i>Data akan masuk ke trash (soft delete)
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete" data-bs-dismiss="modal">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        window.addEventListener('closeModal', event => {
            bootstrap.Modal.getInstance(document.getElementById('teacherModal')).hide();
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endpush
