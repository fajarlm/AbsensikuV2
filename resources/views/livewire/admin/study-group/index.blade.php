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
                <h4 class="mb-1">Manajemen Kelas</h4>
                <p class="text-muted mb-0">Kelola data kelas dan rombongan belajar</p>
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
                        <button href="exportPdf" style="font-size: 18px" class=" text-danger dropdown-item"><i
                            class="fas fa-file-pdf"></i>
                            PDF</button>
                        </div>
                    </div>
                    <a href="{{ route('admin.study_group.trash') }}" class="btn btn-danger me-2">
                        <i class="bi bi-trash"></i> Data Sampah
                    </a>

                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#groupModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Kelas
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
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Kelas</h6>
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
                                <i class="bi bi-diagram-3 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Jumlah Tingkat</h6>
                                <h3 class="mb-0">{{ $stats['totalGrades'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                placeholder="Cari kelas, jurusan, atau nomor...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Tingkat</label>
                        <select wire:model.live="filterGrade" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Jurusan</label>
                        <select wire:model.live="filterMajor" class="form-select">
                            <option value="">Semua Jurusan</option>
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
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Nomor Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th>Tgl Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $index => $group)
                                <tr wire:key="group-{{ $group->id }}">
                                    <td>{{ $groups->firstItem() + $index }}</td>
                                    <td><strong>{{ $group->grade }}</strong></td>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $group->major }}</span>
                                    </td>
                                    <td>{{ $group->class_number }}</td>
                                    <td>{{ $group->students_count ?? $group->students()->count() }} siswa</td>
                                    <td>{{ $group->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <button wire:click="edit({{ $group->id }})" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#groupModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="deleteConfirm({{ $group->id }})"
                                            class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada data kelas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $groups->firstItem() ?? 0 }} - {{ $groups->lastItem() ?? 0 }} dari
                        {{ $groups->total() }} data
                    </div>
                    {{ $groups->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    <div class="modal fade" id="groupModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Edit Kelas' : 'Tambah Kelas' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select wire:model="grade" class="form-select @error('grade') is-invalid @enderror">
                                    <option value="">Pilih Tingkat</option>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                                <select wire:model="major" class="form-select @error('major') is-invalid @enderror">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="PPLG">PPLG</option>
                                    <option value="TJKT">TJKT</option>
                                    <option value="DKV">DKV</option>
                                    <option value="PMN">PMN</option>
                                    <option value="MPLB">MPLB</option>
                                    <option value="KLN">KLN</option>
                                    <option value="HTL">HTL</option>
                                </select>
                                @error('major')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nomor Kelas <span class="text-danger">*</span></label>
                                <select wire:model="class_number"
                                    class="form-select @error('class_number') is-invalid @enderror">
                                    <option value="">Pilih Nomor</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('class_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="resetForm">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus kelas ini? Data akan masuk ke trash.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete" data-bs-dismiss="modal">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
