<div>
    <div class="container-fluid py-4">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Manajemen Jadwal Pelajaran</h4>
                <p class="text-muted mb-0">Atur jadwal mengajar per kelas</p>
            </div>
            <div>
                <a href="{{ route('admin.schedule.trash') ?? '#' }}" class="btn btn-danger me-2">
                    <i class="bi bi-trash"></i> Sampah
                </a>
                <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#scheduleModal">
                    <i class="bi bi-plus-circle"></i> Tambah Jadwal
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                            <i class="bi bi-calendar3 fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Jadwal</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Hari Aktif</h6>
                            <h3 class="mb-0">{{ $stats['days'] }} hari</h3>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.schedule.index') }}" class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                            <i class="bi bi-table"></i>
                        </div>
                        <div>
                            <span class="h3 text-decoration-none">
                                Mode Tabel
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Pencarian</label>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                            placeholder="Cari mata pelajaran / kelas...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Hari</label>
                        <select wire:model.live="filterDay" class="form-select">
                            <option value="">Semua Hari</option>
                            @foreach ($days as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Kelas</label>
                        <select wire:model.live="filterGroup" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->grade }} {{ $g->major }}
                                    {{ $g->class_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Tampilkan</label>
                        <select wire:model.live="perPage" class="form-select">
                            <option>15</option>
                            <option>30</option>
                            <option>50</option>
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
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $i => $s)
                                <tr wire:key="schedule-{{ $s->id }}">
                                    <td>{{ $schedules->firstItem() + $i }}</td>
                                    <td><strong>{{ $s->studyGroup->grade }} {{ $s->studyGroup->major }}
                                            {{ $s->studyGroup->class_number }}</strong></td>
                                    <td>{{ $s->subject->name }}</td>
                                    <td>{{ $s->subject->teacher->user->name ?? '—' }}</td>
                                    <td><span class="badge bg-info">{{ $s->day }}</span></td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                                    </td>

                                    <td>
                                        <button wire:click="edit({{ $s->id }})" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="deleteConfirm({{ $s->id }})"
                                            class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Belum ada jadwal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-circle' }}"></i>
                        {{ $isEdit ? 'Edit' : 'Tambah' }} Jadwal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Kelas <span class="text-danger">*</span></label>
                                <select wire:model="study_group_id"
                                    class="form-select @error('study_group_id') is-invalid @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->grade }} {{ $g->major }}
                                            {{ $g->class_number }}</option>
                                    @endforeach
                                </select>
                                @error('study_group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Mata Pelajaran <span class="text-danger">*</span></label>
                                <select wire:model="subject_id"
                                    class="form-select @error('subject_id') is-invalid @enderror">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($subjects as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}
                                            ({{ $sub->teacher->user->name ?? '?' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Hari <span class="text-danger">*</span></label>
                                <select wire:model="day" class="form-select @error('day') is-invalid @enderror">
                                    <option value="">Pilih Hari</option>
                                    @foreach ($days as $d)
                                        <option>{{ $d }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" wire:model="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" wire:model="end_time"
                                    class="form-control @error('end_time') is-invalid @enderror">
                                @error('end_time')
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
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus jadwal ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete"
                        data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
