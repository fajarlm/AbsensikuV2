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
                <h2 class="mb-0">Manajemen Siswa</h2>
                <p class="text-muted mb-0">Kelola data siswa dan informasi akademik</p>
            </div>
            <div class="">
                <a wire:navigate href="trahsPage" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Data Sampah
                </a>
                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#studentModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Siswa</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
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
            <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                placeholder="Cari nama, username, atau NIS...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Tingkat</label>
                        <select wire:model.live="filterGrade" class="form-select">
                            <option value="">Semua</option>
                            <option value="X">X (10)</option>
                            <option value="XI">XI (11)</option>
                            <option value="XII">XII (12)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small text-muted">Jurusan</label>
                        <select wire:model.live="filterMajor" class="form-select">
                            <option value="">Semua</option>
                            <option value="PPLG">PPLG</option>
                            <option value="TJKT">TJKT</option>
                            <option value="DKV">DKV</option>
                            <option value="PMN">PMN</option>
                            <option value="MPLB">MPLB</option>
                            <option value="KLN">KLN</option>
                            <option value="HTL">HTL</option>
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

        <!-- Students Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Username</th>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>JK</th>
                                <th>Jurusan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr wire:key="student-{{ $student->id }}">
                                    <td>{{ $students->firstItem() + $index }}</td>
                                    <td>
                                        @if ($student->user->profile)
                                            <img src="{{ asset('storage/' . $student->user->profile) }}"
                                                alt="{{ $student->user->name }}" class="rounded-circle" width="45"
                                                height="45" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px; font-size: 18px;">
                                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $student->user->name }}</strong>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $student->nis }}</span></td>
                                    <td>{{ $student->user->username }}</td>
                                    <td>
                                        @if ($student->studyGroup)
                                            <span class="badge bg-success">
                                                {{ $student->studyGroup->major }}
                                                {{ $student->studyGroup->grade }}-{{ $student->studyGroup->class_number }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->studyGroup->grade }}</td>
                                    <td>
                                        @if ($student->user->gender == 'male')
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
                                        {{ $student->studyGroup->major }}
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="edit({{ $student->id }})" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#studentModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="deleteConfirm({{ $student->id }})"
                                            class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data siswa</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari
                        {{ $students->total() }} data
                    </div>
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="row g-3">
                            <!-- Section: Data Akun -->
                            <div class="col-12">
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
                                <div class="invalid-feedback">{{ $message }}</div @enderror <small
                                    class="text-muted">Maksimal 2MB (JPG, PNG)</small>

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

                            <!-- Section: Data Akademik -->
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-1 mt-2">
                                    <i class="bi bi-journal-text me-2"></i>Data Akademik
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIS <span class="text-danger">*</span></label>
                                <input wire:model="nis" type="text"
                                    class="form-control @error('nis') is-invalid @enderror"
                                    placeholder="Masukkan NIS">
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select wire:model="study_group_id"
                                    class="form-select @error('study_group_id') is-invalid @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($studyGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->major }}
                                            {{ $group->grade }}-{{ $group->class_number }}</option>
                                    @endforeach
                                </select>
                                @error('study_group_id')
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
                    <p class="mb-0">Apakah Anda yakin ingin menghapus siswa <strong>{{ $name }}</strong>?
                    </p>
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
