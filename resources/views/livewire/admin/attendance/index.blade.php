<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-journal-check"></i> Data Absensi Siswa</h3>
            <p class="text-muted mb-0">Admin hanya dapat melihat dan export data absensi</p>
        </div>
        <div>
            <button wire:click="exportExcel" class="btn btn-success text-white me-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            {{-- <button wire:click="exportPdf" class="btn btn-danger text-white">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button> --}}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h5>{{ $total }}</h5>
                    <small>Total Absen</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <h5>{{ $present }}</h5>
                    <small>Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body text-center">
                    <h5>{{ $absent }}</h5>
                    <small>Alfa</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <h5>{{ $sick }}</h5>
                    <small>Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body text-center">
                    <h5>{{ $permission }}</h5>
                    <small>Izin</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <h5>{{ $dispensed }}</h5>
                    <small>Dispensasi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari siswa / mata pelajaran...">
                </div>
                <div class="col-md-3">
                    <input type="date" wire:model.live="filterDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterClass" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->grade }} {{ $class->major }} {{ $class->class_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="present">Hadir</option>
                        <option value="absent">Alfa</option>
                        <option value="sick">Sakit</option>
                        <option value="permission">Izin</option>
                        <option value="dispensed">Dispensasi</option>
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
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $a)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($a->schedule->date)->format('d M Y') }}</td>
                                <td><strong>{{ $a->schedule->studyGroup->full_name ?? '-' }}</strong></td>
                                <td>{{ $a->student->user->name }}</td>
                                <td>{{ $a->schedule->subject->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $a->status == 'present' ? 'success' : ($a->status == 'absent' ? 'danger' :($a->status == 'sick' ? 'warning' :($a->status == 'permission' ? 'info' :  'secondary' ))) }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                                <td>{{ $a->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data absensi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>