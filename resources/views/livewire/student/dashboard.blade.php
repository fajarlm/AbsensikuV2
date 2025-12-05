<div class="container-fluid py-4">
    <!-- Student Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if (auth()->user()->profile)
                                <img src="{{ asset('storage/' . auth()->user()->profile) }}"
                                    class="rounded-circle border border-primary"
                                    style="width:80px; height:80px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width:80px; height:80px; font-size:2rem;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <h1 class="h3 mb-1">{{ auth()->user()->name }}</h1>
                            <p class="text-muted mb-1">
                                <i class="bi bi-person-badge me-1"></i>
                                {{ $student->nis ?? 'N/A' }} ({{ $student->nisn ?? 'N/A' }})
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-people me-1"></i>
                                {{ $student->studyGroup->name ?? 'N/A' }} Class
                            </p>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-calendar-check me-1"></i>
                                {{ $today }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs nav-tabs-student">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'schedule' ? 'active text-primary' : '' }}"
                        wire:click="selectTab('schedule')">
                        <i class="bi bi-calendar-week me-2"></i>
                        My Schedule
                    </button>
                </li>
                {{-- <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}"
                        wire:click="selectTab('attendance')">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Attendance History
                    </button>
                </li> --}}
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                        wire:click="selectTab('profile')">
                        <i class="bi bi-person-circle me-2"></i>
                        My Profile
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="row">
        <div class="col-12">
            @if ($activeTab === 'schedule')
                <!-- Schedule Tab -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-week text-primary me-2"></i>
                            Weekly Schedule
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (isset($schedules) && $schedules->count() > 0)
                            <div class="row">
                                @foreach ($schedules as $day => $daySchedules)
                                    <div class="col-12 mb-4">
                                        <div class="day-header">
                                            <h6 class="mb-0">{{ $day }}</h6>
                                        </div>
                                        <div class="row">
                                            @foreach ($daySchedules as $schedule)
                                                @php
                                                    $attendanceStatus = $this->getAttendanceStatus(
                                                        $schedule->id,
                                                        $today,
                                                    );
                                                    $statusClass = $this->getAttendanceStatusClass($attendanceStatus);
                                                    $statusText = $this->getAttendanceStatusText($attendanceStatus);
                                                @endphp
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="card schedule-card h-100">
                                                        <div class="card-body">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="card-title mb-0 text-primary">
                                                                    {{ $schedule->subject->name ?? 'N/A' }}
                                                                </h6>
                                                                <span class="attendance-badge {{ $statusClass }}">
                                                                    {{ $statusText }}
                                                                </span>
                                                            </div>
                                                            <div class="mb-2">
                                                                <small class="text-muted d-block">
                                                                    <i class="bi bi-clock me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    <i class="bi bi-geo-alt me-1"></i>
                                                                    {{ $schedule->room ?? 'N/A' }}
                                                                </small>
                                                            </div>
                                                            <div class="d-flex align-items-center mt-3">
                                                                <div class="avatar-circle-sm bg-light text-dark me-2">
                                                                    {{ substr($schedule->teacher->name ?? 'T', 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted d-block">Teacher</small>
                                                                    <small
                                                                        class="fw-medium">{{ $schedule->teacher->name ?? 'N/A' }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x display-4 text-muted"></i>
                                <p class="mt-3 text-muted">No schedule found for your class</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($activeTab === 'attendance')
                <!-- Attendance History Tab -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-clipboard-check text-primary me-2"></i>
                                    Attendance History
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <input type="date" class="form-control form-control-sm"
                                            wire:model.live="selectedDate">
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-select form-select-sm" wire:model.live="selectedSchedule">
                                            <option value="">All Subjects</option>
                                            @foreach ($schedules as $schedule)
                                                <option value="{{ $schedule->id }}">
                                                    {{ $schedule->subject->name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-outline-secondary w-100"
                                            wire:click="$set('selectedDate', '{{ $today }}')">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $attendance->schedule->subject->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $attendance->schedule->teacher->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'present' => 'success',
                                                        'absent' => 'danger',
                                                        'sick' => 'info',
                                                        'permission' => 'warning',
                                                        'dispensed' => 'secondary',
                                                    ];
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusColors[$attendance->status] ?? 'secondary' }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($attendance->note)
                                                    <small class="text-muted">{{ $attendance->note }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="bi bi-clipboard-x display-4 text-muted"></i>
                                                <p class="mt-3 text-muted">No attendance records found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($attendances->hasPages())
                            <div class="mt-3">
                                {{ $attendances->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($activeTab === 'profile')
                <!-- Profile Tab -->
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card profile-card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Profile Photo
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($photoPreview)
                                    <img src="{{ $photoPreview }}" alt="Photo Preview" class="photo-preview mb-3">
                                @elseif(auth()->user()->profile)
                                    <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Current Photo"
                                        class="photo-preview mb-3">
                                @else
                                    <div class="avatar-placeholder mx-auto mb-3"
                                        style="width: 150px; height: 150px; font-size: 3rem;">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="photo" class="photo-upload-area">
                                        <i class="bi bi-cloud-arrow-up display-6 text-muted mb-2"></i>
                                        <p class="mb-1">Click to upload photo</p>
                                        <small class="text-muted">JPG, PNG max 2MB</small>
                                    </label>
                                    <input type="file" id="photo" class="d-none" wire:model="profile"
                                        accept="image/*">
                                    @error('profile')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if (auth()->user()->profile || $profile)
                                    <button class="btn btn-outline-danger btn-sm" wire:click="removePhoto"
                                        wire:loading.attr="disabled">
                                        <i class="bi bi-trash me-1"></i>
                                        Remove Photo
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Student Information -->
                    </div>
                    <div class="card col-md-8 profile-card shadow-sm ">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Student Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">NIS</div>
                                <div class="info-value">{{ $student->nis ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">NISN</div>
                                <div class="info-value">{{ $student->nisn ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Study Group</div>
                                <div class="info-value">{{ $student->studyGroup->name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Verification Code</div>
                                <div class="info-value">
                                    <p>{{ $student->verification_code ?"*******" :'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-lg float-center" wire:click="toggleEdit">
                                <i class="bi bi-pencil me-1"></i>
                                edit
                            </button>
                        </div>
                    </div>

                     <div class="col-lg-12 mt-4 {{ $showEdit ? 'd-block' : 'd-none' }}" >
                        <div class="card profile-card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-gear text-primary me-2"></i>
                                    Account Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                @if (session()->has('profile_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle me-2"></i>
                                        {{ session('profile_message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form wire:submit.prevent="updateProfile">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                wire:model="name" >
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">username</label>
                                            <input type="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                wire:model="username" >
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">verification code</label>
                                            <input type="verification_code"
                                                class="form-control @error('verification_code') is-invalid @enderror"
                                                wire:model="verification_code" >
                                            @error('verification_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h6 class="mb-3">Change Password</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                wire:model="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                wire:model="new_password">
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control"
                                                wire:model="new_password_confirmation">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="updateProfile">
                                                <i class="bi bi-save me-2"></i>
                                                Update Profile
                                            </span>
                                            <span wire:loading wire:target="updateProfile">
                                                <span class="spinner-border spinner-border-sm me-2"></span>
                                                Updating...
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary ms-2"
                                            wire:click="$refresh">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                   
                </div>
            @endif
        </div>
    </div>
</div>
