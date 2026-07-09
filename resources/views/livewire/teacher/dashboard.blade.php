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
                    <div class="d-flex align-items-center gap-2">
                        <button wire:click="refreshData" wire:loading.attr="disabled" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2">
                            <i wire:loading.remove class="bi bi-arrow-clockwise"></i>
                            <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Refresh Data
                        </button>
                        <span class="badge bg-light text-dark fs-6">
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
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4 px-1" id="teacherTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold {{ $activeTab === 'attendance' ? 'active text-primary border-bottom border-primary border-3' : 'text-secondary' }}" 
                        wire:click="$set('activeTab', 'attendance')" type="button" style="border: none; background: none;">
                    <i class="bi bi-calendar-check me-2"></i>Kelola Kehadiran & Jadwal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold {{ $activeTab === 'submissions' ? 'active text-primary border-bottom border-primary border-3' : 'text-secondary' }}" 
                        wire:click="$set('activeTab', 'submissions')" type="button" style="border: none; background: none;">
                    <i class="bi bi-file-earmark-text me-2"></i>Pengajuan Izin Siswa
                    @if($pendingSubmissionsCount > 0)
                        <span class="badge bg-danger ms-1 rounded-pill">{{ $pendingSubmissionsCount }}</span>
                    @endif
                </button>
            </li>
        </ul>

        @if ($activeTab === 'attendance')
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
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
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
                                        @php
                                            $today = now()->dayOfWeekIso;
                                            $dayMap = [
                                                'Senin' => 1,
                                                'Selasa' => 2,
                                                'Rabu' => 3,
                                                'Kamis' => 4,
                                                'Jumat' => 5,
                                                'Sabtu' => 6,
                                                'Minggu' => 7,
                                            ];
                                            $isToday = $today === ($dayMap[$schedule->day] ?? null);
                                        @endphp

                                        <button wire:click="openAttendanceModal({{ $schedule->id }})"
                                            class="btn btn-sm {{ $isToday ? 'btn-primary' : 'btn-secondary' }}"
                                            @disabled(!$isToday)
                                            title="{{ $isToday ? 'Isi Absensi' : 'Hanya bisa dilihat' }}">
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
                                            <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
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

                                                @if ($attendance->note && empty($notes[$attendance->id]))
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
        @elseif ($activeTab === 'submissions')
            <!-- Submissions Tab Content -->
            <div class="card shadow border-0 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>Daftar Pengajuan Izin Siswa
                        </h4>
                        <p class="text-muted small mb-0">Tinjau dan setujui permohonan izin (Sakit, Izin, Dispensasi) dari siswa</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Tipe & Alasan</th>
                                <th>Periode Tanggal</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th>Catatan Guru</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($submissions as $sub)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; border-radius: 50%; font-weight: bold; font-size: 1.1rem;">
                                                {{ substr($sub->student->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $sub->student->user->name }}</h6>
                                                <small class="text-muted">NIS: {{ $sub->student->nis }} | Kelas: {{ $sub->student->studyGroup->grade }} {{ $sub->student->studyGroup->major }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @if ($sub->type === 'sick')
                                                <span class="badge bg-info text-dark mb-1"><i class="bi bi-heart-pulse me-1"></i>Sakit</span>
                                            @elseif ($sub->type === 'permission')
                                                <span class="badge bg-warning text-dark mb-1"><i class="bi bi-file-earmark-person me-1"></i>Izin</span>
                                            @else
                                                <span class="badge bg-secondary text-white mb-1"><i class="bi bi-award me-1"></i>Dispensasi</span>
                                            @endif
                                            <p class="mb-0 text-muted small" style="max-width: 250px; white-space: normal; word-break: break-all;">
                                                {{ $sub->reason }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">
                                            <i class="bi bi-calendar-event text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($sub->start_date)->translatedFormat('d M Y') }} 
                                            s.d. 
                                            {{ \Carbon\Carbon::parse($sub->end_date)->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-muted small">
                                            ({{ \Carbon\Carbon::parse($sub->start_date)->diffInDays(\Carbon\Carbon::parse($sub->end_date)) + 1 }} Hari)
                                        </div>
                                    </td>
                                    <td>
                                        @if ($sub->attachment)
                                            <a href="{{ asset('storage/' . $sub->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                                <i class="bi bi-eye me-1"></i>Lihat File
                                            </a>
                                        @else
                                            <span class="text-muted small italic">Tidak ada lampiran</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sub->status === 'pending')
                                            <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-2.5 py-1">
                                                <i class="bi bi-clock-history me-1"></i>Menunggu
                                            </span>
                                        @elseif ($sub->status === 'approved')
                                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2.5 py-1">
                                                <i class="bi bi-check-circle me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-2.5 py-1">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-muted" style="max-width: 150px; display: inline-block; white-space: normal; word-break: break-all;">
                                            {{ $sub->teacher_note ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($sub->status === 'pending')
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button wire:click="openReviewModal({{ $sub->id }}, 'approved')" class="btn btn-sm btn-success px-2.5 py-1 rounded-3">
                                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                                </button>
                                                <button wire:click="openReviewModal({{ $sub->id }}, 'rejected')" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3">
                                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-lock-fill me-1"></i>Telah Ditinjau</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-file-earmark-x display-4 mb-2 d-block"></i>
                                        <p class="mb-0">Tidak ada pengajuan izin siswa saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($submissions->hasPages())
                    <div class="mt-4">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        @endif
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

    <!-- Modal Review Pengajuan -->
    @if ($showReviewModal && $selectedSubmission)
        <div class="modal fade show d-block mt-5" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0 rounded-4">
                    <div class="modal-header border-0 pb-0 {{ $reviewStatus === 'approved' ? 'text-success' : 'text-danger' }}">
                        <h5 class="modal-title fw-bold">
                            @if ($reviewStatus === 'approved')
                                <i class="bi bi-check-circle-fill me-2"></i>Setujui Pengajuan Izin
                            @else
                                <i class="bi bi-x-circle-fill me-2"></i>Tolak Pengajuan Izin
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeReviewModal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                            <div class="row g-2 small">
                                <div class="col-4 text-muted">Siswa:</div>
                                <div class="col-8 fw-semibold text-dark">{{ $selectedSubmission->student->user->name }}</div>
                                
                                <div class="col-4 text-muted">Kelas:</div>
                                <div class="col-8 text-dark">{{ $selectedSubmission->student->studyGroup->grade }} {{ $selectedSubmission->student->studyGroup->major }}</div>
                                
                                <div class="col-4 text-muted">Tipe Izin:</div>
                                <div class="col-8 text-dark">
                                    @if ($selectedSubmission->type === 'sick')
                                        <span class="badge bg-info text-dark">Sakit</span>
                                    @elseif ($selectedSubmission->type === 'permission')
                                        <span class="badge bg-warning text-dark">Izin</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Dispensasi</span>
                                    @endif
                                </div>
                                
                                <div class="col-4 text-muted">Periode:</div>
                                <div class="col-8 text-dark fw-medium">
                                    {{ \Carbon\Carbon::parse($selectedSubmission->start_date)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($selectedSubmission->end_date)->translatedFormat('d M Y') }}
                                </div>
                                
                                <div class="col-4 text-muted">Alasan:</div>
                                <div class="col-8 text-dark italic">"{{ $selectedSubmission->reason }}"</div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">Catatan Guru (Opsional)</label>
                            <textarea class="form-control rounded-3" rows="3" placeholder="Tulis alasan persetujuan atau penolakan..." wire:model="teacherNote"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-4 fw-semibold text-muted" wire:click="closeReviewModal">Batal</button>
                        <button type="button" class="btn {{ $reviewStatus === 'approved' ? 'btn-success' : 'btn-danger' }} rounded-3 px-4 fw-semibold" wire:click="processReview">
                            Konfirmasi {{ $reviewStatus === 'approved' ? 'Setujui' : 'Tolak' }}
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

