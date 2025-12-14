<div>
    <div class="container py-4">
        <!-- Flash Messages -->
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

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 fw-bold text-primary mb-1">Dashboard Guru</h1>
                        <p class="text-muted mb-0">Kelola data kehadiran siswa untuk kelas Anda</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark fs-6 me-3">
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ now()->format('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-primary border-4 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Data</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clipboard-data fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-success border-4 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Hadir Hari Ini</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['today'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-warning border-4 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Hadir</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['present'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-person-check fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-danger border-4 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-danger text-uppercase mb-1">Total Tidak Hadir</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['absent'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-person-x fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light" placeholder="Cari siswa..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control bg-light border-0" wire:model.live="filterDate">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select bg-light border-0" wire:model.live="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="present">Hadir</option>
                            <option value="permission">Izin</option>
                            <option value="sick">Sakit</option>
                            <option value="dispensed">Dispen</option>
                            <option value="absent">Alpa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select bg-light border-0" wire:model.live="filterSchedule">
                            <option value="">Semua Jadwal</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ $schedule->subject->name ?? 'N/A' }}
                                    ({{ $schedule->studyGroup->grade }} {{ $schedule->studyGroup->major }}
                                    {{ $schedule->studyGroup->class_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                            <i class="bi bi-arrow-clockwise me-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 me-2"></i>
                            Jadwal Mengajar
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($schedules as $schedule)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $schedule->subject->name }}</h6>
                                            <small class="text-muted d-block">
                                                {{ $schedule->studyGroup->grade }} {{ $schedule->studyGroup->major }}
                                                {{ $schedule->studyGroup->class_number }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $schedule->day }},
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </small>
                                        </div>
                                        <button wire:click="openAttendanceModal({{ $schedule->id }})"
                                            class="btn btn-sm btn-primary" title="Isi Absensi">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p class="mb-0">Tidak ada jadwal mengajar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Kehadiran</h5>
                        <div class="text-muted small">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Siswa</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    @if ($attendance->student->user->profile)
                                                        <img src="{{ asset('storage/' . $attendance->student->user->profile) }}"
                                                            class="rounded-circle me-2" width="32"
                                                            height="32">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                            style="width: 32px; height: 32px; font-size: 12px;">
                                                            {{ strtoupper(substr($attendance->student->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $attendance->student->user->name }}</div>
                                                        <small
                                                            class="text-muted">{{ $attendance->student->nis }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $attendance->schedule->studyGroup->grade }}
                                                    {{ $attendance->schedule->studyGroup->major }}
                                                    {{ $attendance->schedule->studyGroup->class_number }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->schedule->subject->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                @if ($attendance->status == 'present')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i> Hadir
                                                    </span>
                                                @elseif($attendance->status == 'permission')
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-envelope me-1"></i> Izin
                                                    </span>
                                                @elseif($attendance->status == 'sick')
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-heart-pulse me-1"></i> Sakit
                                                    </span>
                                                @elseif($attendance->status == 'dispensed')
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-heart-pulse me-1"></i>Dispen
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i> Alpa
                                                    </span>
                                                @endif
                                            </td>
                                           <td>
                            <input type="text"
                                   class="form-control form-control-sm border-0 shadow-none"
                                   style="background: transparent; width: 100%;"
                                   wire:model.live.debounce.500ms="notes.{{ $attendance->id }}"
                                   placeholder="Klik untuk tambah catatan..."
                                   wire:keydown.enter="$set('notes.{{ $attendance->id }}', $event.target.value)">
                            
                            @if($attendance->note && empty($notes[$attendance->id]))
                                <small class="text-success fst-italic d-block mt-1">
                                    {{ $attendance->note }}
                                </small>
                            @endif
                        </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="bi bi-calendar-x display-4 text-muted d-block mb-3"></i>
                                                <p class="text-muted">Data kehadiran tidak ditemukan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($attendances->hasPages())
                            <div class="card-footer bg-white">
                                {{-- {{ $attendances->links() }} --}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal fade show d-block mt-5" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-check2-square me-2"></i>
                            Isi/Edit Absensi - {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Quick Actions -->
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Quick Actions:</strong> Atur status semua siswa sekaligus
                        </div>
                        <div class="mb-3 d-flex gap-2 flex-wrap">
                            <button wire:click="setAllStatus('present')" class="btn btn-sm btn-success">
                                <i class="bi bi-check-all me-1"></i> Semua Hadir
                            </button>
                            <button wire:click="setAllStatus('permission')" class="btn btn-sm btn-info">
                                <i class="bi bi-envelope me-1"></i> Semua Izin
                            </button>
                            <button wire:click="setAllStatus('sick')" class="btn btn-sm btn-warning">
                                <i class="bi bi-heart-pulse me-1"></i> Semua Sakit
                            </button>
                            <button wire:click="setAllStatus('absent')" class="btn btn-sm btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Semua Alpa
                            </button>
                            <button wire:click="setAllStatus('dispensed')" class="btn btn-sm btn-primary">
                                <i class="bi bi-x-circle me-1"></i> Semua Dispen
                            </button>
                            <button wire:click="setAllStatus(null)" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua
                            </button>
                        </div>

                        <!-- Summary -->
                        <div class="row mb-3">
                            <div class="col">
                                <small class="text-muted">
                                    Total Siswa: <strong>{{ count($modalStudents) }}</strong> |
                                    Sudah Diisi:
                                    <strong>{{ collect($modalAttendances)->whereNotNull('status')->count() }}</strong>
                                    |
                                    Belum Diisi:
                                    <strong>{{ collect($modalAttendances)->whereNull('status')->count() }}</strong>
                                </small>
                            </div>
                        </div>

                        <!-- Table Absensi -->
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="10%">NIS</th>
                                        <th width="30%">Nama Siswa</th>
                                        <th width="11%" class="text-center">
                                            <i class="bi bi-check-circle text-success me-1"></i>
                                            Hadir
                                        </th>
                                        <th width="11%" class="text-center">
                                            <i class="bi bi-envelope text-info me-1"></i>
                                            Izin
                                        </th>
                                        <th width="11%" class="text-center">
                                            <i class="bi bi-heart-pulse text-warning me-1"></i>
                                            Sakit
                                        </th>
                                        <th width="11%" class="text-center">
                                            <i class="bi bi-envelope text-info me-1"></i>
                                            Dispen
                                        </th>
                                        <th width="11%" class="text-center">
                                            <i class="bi bi-x-circle text-danger me-1"></i>
                                            Alpa
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modalStudents as $index => $student)
                                        <tr wire:key="modal-student-{{ $student->id }}"
                                            class="{{ $modalAttendances[$student->id]['status'] ? '' : 'table-warning' }}">
                                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $student->nis }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($student->user->profile)
                                                        <img src="{{ asset('storage/' . $student->user->profile) }}"
                                                            class="rounded-circle me-2" width="35" height="35"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                            style="width: 35px; height: 35px; font-size: 14px;">
                                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $student->user->name }}</strong>
                                                        @if ($student->user->gender)
                                                            <br>
                                                            <small class="text-muted">
                                                                @if ($student->user->gender == 'male')
                                                                    <i class="bi bi-gender-male text-primary"></i>
                                                                    Laki-laki
                                                                @else
                                                                    <i class="bi bi-gender-female text-danger"></i>
                                                                    Perempuan
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Radio Buttons -->
                                            <td class="text-center ">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input"
                                                        style="width: 22px; height: 22px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="present_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'present')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'present')>
                                                </div>
                                            </td>
                                            <td class="text-center ">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input"
                                                        style="width: 22px; height: 22px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="permission_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'permission')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'permission')>
                                                </div>
                                            </td>
                                            <td class="text-center ">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input"
                                                        style="width: 22px; height: 22px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="sick_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'sick')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'sick')>
                                                </div>
                                            </td>

                                            <td class="text-center ">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input"
                                                        style="width: 22px; height: 22px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="dispensed_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'dispensed')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'dispensed')>
                                                </div>
                                            </td>

                                            <td class="text-center ">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input"
                                                        style="width: 22px; height: 22px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="absent_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'absent')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'absent')>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($modalStudents->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada siswa di kelas ini</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="me-auto">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Klik radio button untuk memilih status kehadiran
                            </small>
                        </div>
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveAttendances"
                            wire:loading.attr="disabled" @disabled(collect($modalAttendances)->whereNull('status')->count() > 0)>
                            <span wire:loading.remove wire:target="saveAttendances">
                                <i class="bi bi-save me-1"></i>
                                Simpan Absensi
                                ({{ collect($modalAttendances)->whereNotNull('status')->count() }}/{{ count($modalStudents) }})
                            </span>
                            <span wire:loading wire:target="saveAttendances">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
  
</div>

@push('script')
    <script>
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
