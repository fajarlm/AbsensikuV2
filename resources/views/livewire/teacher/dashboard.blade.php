<div>
    <!-- Custom CSS styles specific to teacher dashboard -->
    <style>
        :root {
            --teacher-primary: #10b981;
            --teacher-primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --teacher-bg-soft: #f0fdf4;
            --teacher-card-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            --teacher-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .dashboard-banner::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 80%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        .dashboard-banner::before {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            bottom: -50px;
            left: -50px;
            border-radius: 50%;
            pointer-events: none;
        }

        .hover-bg-opacity-20:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            overflow: hidden;
            border-left: 5px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.07);
        }

        .stat-card.primary { border-left-color: #3b82f6; }
        .stat-card.success { border-left-color: #10b981; }
        .stat-card.warning { border-left-color: #f59e0b; }
        .stat-card.danger { border-left-color: #ef4444; }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.primary { background-color: #eff6ff; color: #3b82f6; }
        .stat-icon.success { background-color: #ecfdf5; color: #10b981; }
        .stat-icon.warning { background-color: #fffbeb; color: #f59e0b; }
        .stat-icon.danger { background-color: #fef2f2; color: #ef4444; }

        /* Navigation tabs */
        .nav-tabs-premium {
            border-bottom: 2px solid #e2e8f0;
            gap: 0.5rem;
        }

        .nav-tabs-premium .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            transition: all 0.2s ease;
            position: relative;
            background: transparent;
        }

        .nav-tabs-premium .nav-link:hover {
            color: #10b981;
            background: rgba(16, 185, 129, 0.05);
        }

        .nav-tabs-premium .nav-link.active {
            color: #10b981 !important;
            background: transparent;
        }

        .nav-tabs-premium .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: #10b981;
            border-radius: 3px 3px 0 0;
        }

        /* List group schedules */
        .schedule-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.04);
            border-color: #10b981;
        }

        /* Table styling */
        .premium-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .premium-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem;
        }

        .premium-table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .premium-table tbody tr {
            transition: all 0.2s ease;
        }

        .premium-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* filter card */
        .filter-card {
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
    </style>

    <div class="container py-4">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Modern Banner Header -->
        <div class="dashboard-banner mb-4 text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; backdrop-filter: blur(5px);">
                        <i class="bi bi-mortarboard fs-3 text-white"></i>
                    </div>
                    <div>
                        <h1 class="h3 fw-bold mb-1 text-white">Dashboard Guru</h1>
                        <p class="mb-0 text-white-50 small">Kelola data kehadiran siswa untuk kelas yang Anda ampu</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button wire:click="refreshData" wire:loading.attr="disabled" class="btn btn-light bg-opacity-10 text-white border-white border-opacity-25 d-flex align-items-center gap-2 hover-bg-opacity-20 py-2 px-3" style="backdrop-filter: blur(5px); border-radius: 10px;">
                        <i wire:loading.remove class="bi bi-arrow-clockwise"></i>
                        <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Refresh Data
                    </button>
                    <span class="badge bg-white bg-opacity-10 text-white fs-6 border border-white border-opacity-15 py-2 px-3" style="border-radius: 10px;">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card primary p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Data Absensi</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card success p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Hadir Hari Ini</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['today'] }}</h3>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card warning p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Hadir</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['present'] }}</h3>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card danger p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Absen</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['absent'] }}</h3>
                        </div>
                        <div class="stat-icon danger">
                            <i class="bi bi-person-x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs-premium mb-4 px-1" id="teacherTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}" 
                        wire:click="$set('activeTab', 'attendance')" type="button">
                    <i class="bi bi-calendar-check me-2"></i>Kelola Kehadiran & Jadwal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'submissions' ? 'active' : '' }}" 
                        wire:click="$set('activeTab', 'submissions')" type="button">
                    <i class="bi bi-file-earmark-text me-2"></i>Pengajuan Izin Siswa
                    @if($pendingSubmissionsCount > 0)
                        <span class="badge bg-danger ms-1 rounded-pill" style="font-size: 0.75rem; padding: 0.25em 0.6em;">{{ $pendingSubmissionsCount }}</span>
                    @endif
                </button>
            </li>
        </ul>

        @if ($activeTab === 'attendance')
        <!-- Filters -->
        <div class="card filter-card shadow-sm mb-4 border-0">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light py-2" placeholder="Cari siswa..."
                                wire:model.live.debounce.300ms="search" style="font-size: 0.9rem;">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control bg-light border-0 py-2" wire:model.live="filterDate" style="font-size: 0.9rem;">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select bg-light border-0 py-2" wire:model.live="filterStatus" style="font-size: 0.9rem;">
                            <option value="">Semua Status</option>
                            <option value="present">Hadir</option>
                            <option value="permission">Izin</option>
                            <option value="sick">Sakit</option>
                            <option value="dispensed">Dispen</option>
                            <option value="absent">Alpa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select bg-light border-0 py-2" wire:model.live="filterSchedule" style="font-size: 0.9rem;">
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
                        <button class="btn btn-outline-secondary w-100 py-2" wire:click="resetFilters" style="font-size: 0.9rem; border-radius: 10px;">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Schedules -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white border-0 py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold" style="font-size: 1.1rem;">
                            <i class="bi bi-calendar3 me-2 text-success"></i>
                            Jadwal Mengajar
                        </h5>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25" style="font-size: 0.75rem;">Aktif</span>
                    </div>
                    <div class="card-body p-2 bg-light bg-opacity-50">
                        <div class="d-flex flex-column gap-2">
                            @forelse($schedules as $schedule)
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
                                <div class="schedule-card p-3 d-flex justify-content-between align-items-center bg-white border border-opacity-50">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $schedule->subject->name }}</h6>
                                        <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.75rem;">
                                                {{ $schedule->studyGroup->grade }} {{ $schedule->studyGroup->major }} {{ $schedule->studyGroup->class_number }}
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            Hari: <strong class="{{ $isToday ? 'text-success' : '' }}">{{ $schedule->day }}</strong>
                                        </small>
                                    </div>
                                    <button wire:click="openAttendanceModal({{ $schedule->id }})"
                                        class="btn btn-sm p-2 rounded-circle d-flex align-items-center justify-content-center hover-scale {{ $isToday ? 'btn-success text-white' : 'btn-outline-secondary' }}"
                                        style="width: 38px; height: 38px;"
                                        title="{{ $isToday ? 'Isi Absensi' : 'Hari tidak sesuai' }}">
                                        <i class="bi {{ $isToday ? 'bi-pencil-square' : 'bi-eye' }}"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-opacity-50"></i>
                                    <p class="mb-0">Tidak ada jadwal mengajar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Attendances Grid -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom border-light">
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.1rem;">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                            Data Kehadiran Siswa
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table premium-table align-middle mb-0">
                                <thead>
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
                                                            class="rounded-circle me-2 border border-2 border-light" width="36"
                                                            height="36" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2 fw-bold"
                                                            style="width: 36px; height: 36px; font-size: 13px;">
                                                            {{ strtoupper(substr($attendance->student->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $attendance->student->user->name }}</div>
                                                        <small class="text-muted">NIS: {{ $attendance->student->nis }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.8rem;">
                                                    {{ $attendance->schedule->studyGroup->grade }}
                                                    {{ $attendance->schedule->studyGroup->major }}
                                                    {{ $attendance->schedule->studyGroup->class_number }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark">{{ $attendance->schedule->subject->name }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-dark">{{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td class="text-center">
                                                @if ($attendance->status == 'present')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Hadir
                                                    </span>
                                                @elseif($attendance->status == 'permission')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill">
                                                        <i class="bi bi-envelope-fill me-1"></i> Izin
                                                    </span>
                                                @elseif($attendance->status == 'sick')
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 rounded-pill">
                                                        <i class="bi bi-heart-pulse-fill me-1"></i> Sakit
                                                    </span>
                                                @elseif($attendance->status == 'dispensed')
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill">
                                                        <i class="bi bi-award-fill me-1"></i> Dispen
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill">
                                                        <i class="bi bi-x-circle-fill me-1"></i> Alpa
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm" style="max-width: 200px;">
                                                    <input type="text"
                                                        class="form-control border-0 bg-light rounded-3 px-2 py-1"
                                                        style="font-size: 0.85rem;"
                                                        wire:model.live.debounce.500ms="notes.{{ $attendance->id }}"
                                                        placeholder="Tambah catatan..."
                                                        wire:keydown.enter="$set('notes.{{ $attendance->id }}', $event.target.value)">
                                                </div>
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
                                                <i class="bi bi-calendar-x display-4 text-muted d-block mb-3 opacity-50"></i>
                                                <p class="text-muted mb-0">Data kehadiran tidak ditemukan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($attendances->hasPages())
                            <div class="card-footer bg-white border-top border-light py-3">
                                {{ $attendances->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @elseif ($activeTab === 'submissions')
            <!-- Submissions Tab Content -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden p-0 mb-4">
                <div class="card-header bg-white border-bottom border-light py-3">
                    <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>Daftar Pengajuan Izin Siswa
                    </h5>
                    <p class="text-muted small mb-0">Tinjau dan setujui permohonan izin (Sakit, Izin, Dispensasi) dari siswa</p>
                </div>

                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
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
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 fw-bold" 
                                                 style="width: 40px; height: 40px; font-size: 1.1rem;">
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
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 rounded-pill mb-1">
                                                    <i class="bi bi-heart-pulse-fill me-1"></i>Sakit
                                                </span>
                                            @elseif ($sub->type === 'permission')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill mb-1">
                                                    <i class="bi bi-file-earmark-person-fill me-1"></i>Izin
                                                </span>
                                            @else
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill mb-1">
                                                    <i class="bi bi-award-fill me-1"></i>Dispensasi
                                                </span>
                                            @endif
                                            <p class="mb-0 text-muted small" style="max-width: 250px; white-space: normal; word-break: break-all;">
                                                {{ $sub->reason }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark mb-1">
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
                                            <a href="{{ asset('storage/' . $sub->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 text-decoration-none d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-eye"></i> Lihat File
                                            </a>
                                        @else
                                            <span class="text-muted small italic">Tidak ada lampiran</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sub->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill">
                                                <i class="bi bi-clock-history me-1"></i>Menunggu
                                            </span>
                                        @elseif ($sub->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill">
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
                                                <button wire:click="openReviewModal({{ $sub->id }}, 'approved')" class="btn btn-sm btn-success px-3 py-1 rounded-3">
                                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                                </button>
                                                <button wire:click="openReviewModal({{ $sub->id }}, 'rejected')" class="btn btn-sm btn-outline-danger px-3 py-1 rounded-3">
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
                                        <i class="bi bi-file-earmark-x display-4 mb-2 d-block opacity-50"></i>
                                        <p class="mb-0">Tidak ada pengajuan izin siswa saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($submissions->hasPages())
                    <div class="card-footer bg-white border-top border-light py-3">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Modal Isi Absensi -->
    @if ($showModal)
        <div class="modal fade show d-block mt-5" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1040;">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content shadow border-0 rounded-4">
                    <div class="modal-header bg-dark text-white border-0 py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-check2-square me-2 text-success"></i>
                            Isi/Edit Absensi - Kehadiran Kelas
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
                            <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1" style="font-size: 0.95rem;">Quick Actions: Atur status semua siswa</h6>
                                <p class="mb-0 small text-muted-50">Gunakan pintasan di bawah ini untuk mengisi status kehadiran seluruh siswa secara serentak.</p>
                            </div>
                        </div>
                        
                        <div class="mb-4 d-flex gap-2 flex-wrap">
                            <button wire:click="setAllStatus('present')" class="btn btn-sm btn-success px-3 py-2 rounded-3">
                                <i class="bi bi-check-all me-1"></i> Semua Hadir
                            </button>
                            <button wire:click="setAllStatus('permission')" class="btn btn-sm btn-warning px-3 py-2 rounded-3 text-dark">
                                <i class="bi bi-envelope me-1"></i> Semua Izin
                            </button>
                            <button wire:click="setAllStatus('sick')" class="btn btn-sm btn-info px-3 py-2 rounded-3 text-white">
                                <i class="bi bi-heart-pulse me-1"></i> Semua Sakit
                            </button>
                            <button wire:click="setAllStatus('dispensed')" class="btn btn-sm btn-primary px-3 py-2 rounded-3">
                                <i class="bi bi-award me-1"></i> Semua Dispen
                            </button>
                            <button wire:click="setAllStatus('absent')" class="btn btn-sm btn-danger px-3 py-2 rounded-3">
                                <i class="bi bi-x-circle me-1"></i> Semua Alpa
                            </button>
                            <button wire:click="setAllStatus(null)" class="btn btn-sm btn-secondary px-3 py-2 rounded-3">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pilihan
                            </button>
                        </div>

                        <!-- Summary -->
                        <div class="row mb-3 bg-light rounded-3 p-3 mx-0 border border-light">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-4 text-muted small">
                                    <span>Total Siswa: <strong class="text-dark">{{ count($modalStudents) }}</strong></span>
                                    <span>Sudah Diisi: <strong class="text-success">{{ collect($modalAttendances)->whereNotNull('status')->count() }}</strong></span>
                                    <span>Belum Diisi: <strong class="text-danger">{{ collect($modalAttendances)->whereNull('status')->count() }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Table Absensi inside Modal -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 border">
                                <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                    <tr>
                                        <th width="5%" class="text-center">#</th>
                                        <th width="12%">NIS</th>
                                        <th width="28%">Nama Siswa</th>
                                        <th width="11%" class="text-center text-success"><i class="bi bi-check-circle-fill me-1"></i> Hadir</th>
                                        <th width="11%" class="text-center text-warning"><i class="bi bi-envelope-fill me-1"></i> Izin</th>
                                        <th width="11%" class="text-center text-info"><i class="bi bi-heart-pulse-fill me-1"></i> Sakit</th>
                                        <th width="11%" class="text-center text-primary"><i class="bi bi-award-fill me-1"></i> Dispen</th>
                                        <th width="11%" class="text-center text-danger"><i class="bi bi-x-circle-fill me-1"></i> Alpa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modalStudents as $index => $student)
                                        <tr wire:key="modal-student-{{ $student->id }}" class="{{ $modalAttendances[$student->id]['status'] ? '' : 'table-warning bg-opacity-25' }}">
                                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $student->nis }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($student->user->profile)
                                                        <img src="{{ asset('storage/' . $student->user->profile) }}"
                                                            class="rounded-circle me-2 border" width="32" height="32"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2 fw-bold"
                                                            style="width: 32px; height: 32px; font-size: 12px;">
                                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong class="text-dark">{{ $student->user->name }}</strong>
                                                        @if ($student->user->gender)
                                                            <br>
                                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                                @if ($student->user->gender == 'male')
                                                                    <i class="bi bi-gender-male text-primary"></i> Laki-laki
                                                                @else
                                                                    <i class="bi bi-gender-female text-danger"></i> Perempuan
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Radio Buttons -->
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input border-secondary"
                                                        style="width: 20px; height: 20px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="present_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'present')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'present')>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input border-secondary"
                                                        style="width: 20px; height: 20px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="permission_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'permission')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'permission')>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input border-secondary"
                                                        style="width: 20px; height: 20px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="sick_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'sick')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'sick')>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input border-secondary"
                                                        style="width: 20px; height: 20px; cursor: pointer;"
                                                        name="status_{{ $student->id }}"
                                                        id="dispensed_{{ $student->id }}"
                                                        wire:click="setStatus({{ $student->id }}, 'dispensed')"
                                                        @checked($modalAttendances[$student->id]['status'] === 'dispensed')>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input type="radio" class="form-check-input border-secondary"
                                                        style="width: 20px; height: 20px; cursor: pointer;"
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
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                <p class="mb-0">Tidak ada siswa terdaftar di kelas ini</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <div class="me-auto text-muted small">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            Pilih salah satu status kehadiran untuk masing-masing siswa
                        </div>
                        <button type="button" class="btn btn-light rounded-3 px-4 fw-semibold text-muted" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-success rounded-3 px-4 fw-semibold text-white" wire:click="saveAttendances"
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
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 rounded-pill">Sakit</span>
                                    @elseif ($selectedSubmission->type === 'permission')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill">Izin</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill">Dispensasi</span>
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
