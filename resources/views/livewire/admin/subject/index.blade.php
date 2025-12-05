<!-- resources/views/livewire/admin/subject/index.blade.php -->
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
                <h4 class="mb-1">Manajemen Mata Pelajaran</h4>
                <p class="text-muted mb-0">Kelola mata pelajaran dan guru pengampu</p>
            </div>
            <div class="">
                <div class="btn-group dropstart">
                    <button type="button" class="btn btn-warning dropdown-toggle " data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-print"></i> Export
                    </button>
                    <div class="dropdown-menu">
                        <button wire:click="exportExcel" style="font-size: 18px"
                            class=" text-success dropdown-item"><i class="fas fa-file-excel"></i>
                            EXCEL</button>
                        <button href="exportPdf" style="font-size: 18px" class=" text-danger dropdown-item"><i
                                class="fas fa-file-pdf"></i>
                            PDF</button>
                    </div>
                </div>
                <a href="{{ route('admin.subject.trash') }}" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Data Sampah
                </a>
                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#subjectModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mata Pelajaran
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                                <i class="bi bi-book fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Mata Pelajaran</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                                <i class="bi bi-person-video3 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Guru Pengampu</h6>
                                <h3 class="mb-0">{{ $stats['totalTeachers'] }}</h3>
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
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                placeholder="Cari nama atau kode mata pelajaran...">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Guru Pengampu</label>
                        <select wire:model.live="filterTeacher" class="form-select">
                            <option value="">Semua Guru</option>

                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
                            @endforeach
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

        <!-- Subjects Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Mata Pelajaran</th>
                                <th>Guru Pengampu</th>
                                <th>Deskripsi</th>
                                <th>Tgl Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $index => $subject)
                                <tr wire:key="subject-{{ $subject->id }}">
                                    <td>{{ $subjects->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $subject->code }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $subject->name }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($subject->teacher->user->profile)
                                                <img src="{{ asset('storage/' . $subject->teacher->user->profile) }}"
                                                    alt="{{ $subject->teacher->user->name }}"
                                                    class="rounded-circle me-2" width="35" height="35"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px; font-size: 14px;">
                                                    {{ strtoupper(substr($subject->teacher->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $subject->teacher->user->name }}</div>
                                                <small class="text-muted">NIP: {{ $subject->teacher->nip }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($subject->description)
                                            <small
                                                class="text-muted">{{ Str::limit($subject->description, 50) }}</small>
                                        @else
                                            <small class="text-muted fst-italic">Tidak ada deskripsi</small>
                                        @endif
                                    </td>
                                    <td>{{ $subject->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button wire:click="edit({{ $subject->id }})" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#subjectModal" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="deleteConfirm({{ $subject->id }})"
                                            class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data mata pelajaran</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $subjects->firstItem() ?? 0 }} - {{ $subjects->lastItem() ?? 0 }} dari
                        {{ $subjects->total() }} data
                    </div>
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="subjectModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Mata Pelajaran <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Matematika">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kode Mata Pelajaran <span
                                        class="text-danger">*</span></label>
                                <input wire:model="code" type="text"
                                    class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: MTK"
                                    style="text-transform: uppercase;">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format kode harus 3 huruf kapital diikuti tanda minus dan 3
                                    angka. Contoh: MTK-011</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Guru Pengampu <span class="text-danger">*</span></label>
                                <select wire:model="teacher_id"
                                    class="form-select @error('teacher_id') is-invalid @enderror">
                                    <option value="">Pilih Guru Pengampu</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi <small
                                        class="text-muted">(Opsional)</small></label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                    placeholder="Masukkan deskripsi mata pelajaran (opsional)"></textarea>
                                @error('description')
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
                    <p class="mb-0">Apakah Anda yakin ingin menghapus mata pelajaran
                        <strong>{{ $name }}</strong>?</p>
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
