<div class="container py-4">
    <!-- Student Profile Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-banner shadow">
                <div class="row align-items-center">
                    <div class="col-auto text-center text-md-start mb-3 mb-md-0">
                        @if (auth()->user()->profile)
                            <img src="{{ asset('storage/' . auth()->user()->profile) }}"
                                 class="student-avatar" alt="{{ auth()->user()->name }}">
                        @else
                            <div class="student-avatar bg-white text-primary d-flex align-items-center justify-content-center"
                                 style="font-size: 2.5rem; font-weight: 700;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="col text-center text-md-start">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 mb-2">
                            <h2 class="h3 mb-0 fw-bold">{{ auth()->user()->name }}</h2>
                            <span class="badge bg-white text-primary fw-bold px-2.5 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                                Siswa Aktif
                            </span>
                        </div>
                        <p class="mb-1 opacity-90 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                            <i class="bi bi-person-badge"></i>
                            <span>NIS: {{ $student?->nis ?? 'N/A' }}</span>
                            <span class="opacity-50">|</span>
                            
                            <span>NISN: {{ $student?->nisn ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-0 opacity-90 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                            <i class="bi bi-mortarboard"></i>
                            <span>{{ $student?->studyGroup?->name ?? 'N/A' }} ({{ $student?->studyGroup?->major ?? 'N/A' }})</span>
                        </p>
                    </div>
                    <div class="col-12 col-md-auto text-center text-md-end mt-3 mt-md-0">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 bg-white bg-opacity-20 border border-white border-opacity-20 rounded-3">
                            <i class="bi bi-calendar3"></i>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills / Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-pills-student justify-content-center justify-content-md-start gap-2">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'schedule' ? 'active' : '' }}"
                            wire:click="selectTab('schedule')">
                        <i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'submission' ? 'active' : '' }}"
                            wire:click="selectTab('submission')">
                        <i class="bi bi-file-earmark-text me-2"></i>Pengajuan Izin
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                            wire:click="selectTab('profile')">
                        <i class="bi bi-gear me-2"></i>Pengaturan Akun
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <div class="col-12">
            <!-- TAB 1: SCHEDULE -->
            @if ($activeTab === 'schedule')
                <div class="fade-in-up">
                    <div class="card premium-card border-0 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Mingguan Anda
                            </h4>
                            <span class="text-muted small">Menampilkan semua jadwal pelajaran berdasarkan kelas</span>
                        </div>

                        @if (isset($schedules) && $schedules->count() > 0)
                            <div class="row">
                                @foreach ($schedules as $day => $daySchedules)
                                    <div class="col-12 mb-4">
                                        <div class="day-badge">
                                            <i class="bi bi-calendar-day text-primary"></i>
                                            <span>Hari {{ $day }}</span>
                                        </div>
                                        <div class="row g-3">
                                            @foreach ($daySchedules as $schedule)
                                                @php
                                                    $attendanceStatus = $this->getAttendanceStatus($schedule->id, $today);
                                                    $statusClass = $this->getAttendanceStatusClass($attendanceStatus);
                                                    $statusText = $this->getAttendanceStatusText($attendanceStatus);
                                                @endphp
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="card schedule-item shadow-sm p-3 h-100">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                                                                {{ $schedule->subject->name ?? 'N/A' }}
                                                            </h5>
                                                            @if($attendanceStatus)
                                                                <span class="badge attendance-badge-pill {{ $statusClass }}">
                                                                    {{ $statusText }}
                                                                </span>
                                                            @else
                                                                <span class="badge attendance-badge-pill bg-light text-muted border">
                                                                    Belum Absen
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="mb-3 d-flex align-items-center gap-1 text-muted small">
                                                            <i class="bi bi-clock"></i>
                                                            <span class="time-badge">
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                            </span>
                                                        </div>

                                                        <div class="d-flex align-items-center gap-2 mt-auto pt-2 border-top border-light">
                                                            <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 32px; height: 32px; font-weight: bold; font-size: 0.8rem;">
                                                                {{ substr($schedule->subject->teacher->user->name ?? 'G', 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <small class="text-muted d-block" style="font-size: 0.75rem;">Guru Pengampu</small>
                                                                <span class="fw-semibold text-dark small">{{ $schedule->subject->teacher->user->name ?? 'N/A' }}</span>
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
                                <i class="bi bi-calendar-x display-1 text-muted opacity-50 mb-3"></i>
                                <h5 class="fw-bold text-muted">Jadwal Kelas Belum Tersedia</h5>
                                <p class="text-muted">Silakan hubungi admin sekolah untuk pengaturan jadwal pelajaran kelas Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- TAB 2: ONLINE SUBMISSIONS -->
            @elseif($activeTab === 'submission')
                <div class="fade-in-up">
                    <div class="row">
                        <!-- Left Side: Form Pengajuan -->
                        <div class="col-lg-5 mb-4">
                            <div class="card premium-card border-0 p-4">
                                <h4 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-plus-circle text-primary me-2"></i>Buat Pengajuan Izin
                                </h4>
                                <p class="text-muted small mb-4">Kirim formulir ini untuk mengajukan permohonan izin belajar jika berhalangan hadir.</p>

                                @if (session()->has('submission_message'))
                                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #f0fdf4; color: #15803d; border-radius: 12px;">
                                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('submission_message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <form wire:submit.prevent="submitSubmission">
                                    <!-- Jenis Pengajuan -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-dark">Tipe Pengajuan *</label>
                                        <div class="d-flex gap-2">
                                            <input type="radio" class="btn-check" name="sub_type" id="type_sick" value="sick" wire:model="submission_type">
                                            <label class="btn btn-outline-info flex-fill" for="type_sick">
                                                <i class="bi bi-heart-pulse me-1"></i>Sakit
                                            </label>

                                            <input type="radio" class="btn-check" name="sub_type" id="type_permission" value="permission" wire:model="submission_type">
                                            <label class="btn btn-outline-warning flex-fill" for="type_permission">
                                                <i class="bi bi-file-earmark-person me-1"></i>Izin
                                            </label>

                                            <input type="radio" class="btn-check" name="sub_type" id="type_dispensed" value="dispensed" wire:model="submission_type">
                                            <label class="btn btn-outline-secondary flex-fill" for="type_dispensed">
                                                <i class="bi bi-award me-1"></i>Dispensasi
                                            </label>
                                        </div>
                                        @error('submission_type')
                                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Range -->
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label class="form-label fw-semibold text-dark">Tanggal Mulai *</label>
                                            <input type="date" class="form-control rounded-3 @error('start_date') is-invalid @enderror" wire:model="start_date">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">Tanggal Selesai *</label>
                                            <input type="date" class="form-control rounded-3 @error('end_date') is-invalid @enderror" wire:model="end_date">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Alasan -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark">Alasan Pengajuan *</label>
                                        <textarea class="form-control rounded-3 @error('reason') is-invalid @enderror" rows="4" placeholder="Tulis alasan detail pengajuan izin..." wire:model="reason"></textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Lampiran -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-dark">Lampiran Bukti (Opsional)</label>
                                        <label class="custom-file-upload d-block position-relative">
                                            <i class="bi bi-cloud-arrow-up display-5 text-muted mb-2 d-block"></i>
                                            @if ($attachment)
                                                <span class="text-success fw-bold d-block mb-1">
                                                    <i class="bi bi-file-earmark-check me-1"></i>File dipilih: {{ $attachment->getClientOriginalName() }}
                                                </span>
                                                <span class="text-muted small">Klik untuk mengganti berkas</span>
                                            @else
                                                <span class="fw-semibold text-dark d-block mb-1">Pilih berkas bukti izin</span>
                                                <span class="text-muted small">PDF, JPG, JPEG, PNG (Maks 2MB)</span>
                                            @endif
                                            <input type="file" class="d-none" wire:model="attachment" accept="image/*,application/pdf">
                                        </label>
                                        <div wire:loading wire:target="attachment" class="mt-2">
                                            <span class="spinner-border spinner-border-sm text-primary me-2"></span>
                                            <small class="text-muted">Mengunggah file...</small>
                                        </div>
                                        @error('attachment')
                                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold" wire:loading.attr="disabled" wire:target="submitSubmission">
                                        <span wire:loading.remove wire:target="submitSubmission">
                                            <i class="bi bi-send me-1"></i> Kirim Pengajuan
                                        </span>
                                        <span wire:loading wire:target="submitSubmission">
                                            <span class="spinner-border spinner-border-sm me-2"></span> Mengirim...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Right Side: Riwayat Pengajuan -->
                        <div class="col-lg-7">
                            <div class="card premium-card border-0 p-4">
                                <h4 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Pengajuan Online
                                </h4>
                                <p class="text-muted small mb-4">Daftar semua izin yang pernah Anda ajukan beserta status verifikasinya.</p>

                                @if (isset($submissions) && $submissions->count() > 0)
                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($submissions as $sub)
                                            <div class="card border border-light shadow-none p-3 rounded-4 bg-light bg-opacity-50">
                                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $this->getSubmissionTypeClass($sub->type) }} px-2.5 py-1.5" style="border-radius: 6px;">
                                                            {{ $this->getSubmissionTypeText($sub->type) }}
                                                        </span>
                                                        <span class="text-muted small">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            {{ \Carbon\Carbon::parse($sub->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($sub->end_date)->translatedFormat('d M Y') }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $this->getSubmissionStatusClass($sub->status) }} px-2.5 py-1.5" style="border-radius: 6px;">
                                                            {{ $this->getSubmissionStatusText($sub->status) }}
                                                        </span>
                                                        @if ($sub->status === 'pending')
                                                            <button class="btn btn-sm btn-outline-danger border-0 p-1"
                                                                    wire:click="cancelSubmission({{ $sub->id }})"
                                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')"
                                                                    title="Batalkan pengajuan">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Alasan -->
                                                <div class="mb-2">
                                                    <small class="text-muted d-block mb-1 fw-bold" style="font-size: 0.75rem;">Alasan Pengajuan</small>
                                                    <p class="text-dark mb-0 small" style="line-height: 1.5;">{{ $sub->reason }}</p>
                                                </div>

                                                <!-- Detail Lampiran & Catatan Guru -->
                                                <div class="row mt-2 g-2 pt-2 border-top border-white">
                                                    @if ($sub->attachment)
                                                        <div class="col-sm-6">
                                                            <small class="text-muted d-block fw-bold" style="font-size: 0.75rem; margin-bottom: 3px;">Berkas Bukti</small>
                                                            <a href="{{ asset('storage/' . $sub->attachment) }}" target="_blank" class="btn btn-xs btn-outline-primary d-inline-flex align-items-center gap-1 py-1 px-2.5 rounded-2" style="font-size: 0.75rem;">
                                                                <i class="bi bi-file-earmark-arrow-down"></i> Lihat Lampiran
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if ($sub->teacher_note)
                                                        <div class="col-12">
                                                            <div class="detail-item bg-white border border-light mt-1">
                                                                <small class="text-primary d-block fw-bold mb-1" style="font-size: 0.75rem;">Catatan Guru</small>
                                                                <p class="text-muted mb-0 small italic">"{{ $sub->teacher_note }}"</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-chat-left-dots display-2 text-muted opacity-30 mb-3"></i>
                                        <h5 class="fw-bold text-muted">Belum Ada Pengajuan</h5>
                                        <p class="text-muted small">Semua pengajuan izin Anda akan terdaftar di sini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            <!-- TAB 3: ACCOUNT & PROFILE -->
            @elseif($activeTab === 'profile')
                <div class="fade-in-up">
                    <div class="row">
                        <!-- Left Side: Profile Photo Editor -->
                        <div class="col-lg-4 mb-4">
                            <div class="card premium-card border-0 p-4 text-center">
                                <h4 class="fw-bold mb-4 text-dark text-start">
                                    <i class="bi bi-camera text-primary me-2"></i>Foto Profil
                                </h4>

                                <!-- circular image preview -->
                                <div class="mb-4 position-relative d-inline-block mx-auto">
                                    @if ($photoPreview)
                                        <img src="{{ $photoPreview }}" alt="Preview" class="photo-preview shadow-sm"
                                             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid var(--student-primary);">
                                    @elseif(auth()->user()->profile)
                                        <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Foto Sekarang" class="photo-preview shadow-sm"
                                             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid var(--student-primary);">
                                    @else
                                        <div class="avatar-placeholder mx-auto shadow-sm bg-primary text-white d-flex align-items-center justify-content-center"
                                             style="width: 150px; height: 150px; border-radius: 50%; font-size: 3.5rem; font-weight: bold;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Photo Upload Input Area -->
                                <div class="mb-4">
                                    <label for="photo" class="custom-file-upload d-block py-3">
                                        <i class="bi bi-cloud-arrow-up text-primary fs-3 mb-1 d-block"></i>
                                        <span class="fw-semibold text-dark small d-block">Unggah Foto Baru</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">JPEG, PNG (Maks 2MB)</span>
                                    </label>
                                    <input type="file" id="photo" class="d-none" wire:model.live="profile" accept="image/jpeg,image/png,image/jpg">

                                    <div wire:loading wire:target="profile" class="mt-2">
                                        <span class="spinner-border spinner-border-sm text-primary me-1"></span>
                                        <small class="text-muted">Memuat gambar...</small>
                                    </div>

                                    @error('profile')
                                        <small class="text-danger d-block mt-2">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    @if ($profile)
                                        <button class="btn btn-success rounded-3 fw-semibold py-2" wire:click="updatePhotoOnly"
                                                wire:loading.attr="disabled" wire:target="updatePhotoOnly">
                                            <span wire:loading.remove wire:target="updatePhotoOnly">
                                                <i class="bi bi-check2-circle me-1"></i>Simpan Foto
                                            </span>
                                            <span wire:loading wire:target="updatePhotoOnly">
                                                <span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...
                                            </span>
                                        </button>
                                    @endif

                                    @if (auth()->user()->profile)
                                        <button class="btn btn-outline-danger rounded-3 fw-semibold py-2" wire:click="removePhoto"
                                                wire:loading.attr="disabled" wire:target="removePhoto"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                                            <span wire:loading.remove wire:target="removePhoto">
                                                <i class="bi bi-trash me-1"></i>Hapus Foto
                                            </span>
                                            <span wire:loading wire:target="removePhoto">
                                                <span class="spinner-border spinner-border-sm me-2"></span>Menghapus...
                                            </span>
                                        </button>
                                    @endif
                                </div>

                                @if (session()->has('photo_message'))
                                    <div class="alert alert-success border-0 small mt-3 py-2 px-3 text-start" role="alert" style="background-color: #f0fdf4; color: #15803d; border-radius: 8px;">
                                        <i class="bi bi-check-circle-fill me-1.5"></i>{{ session('photo_message') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Side: Account and Student Information Details -->
                        <div class="col-lg-8">
                            <!-- Student Details Card -->
                            <div class="card premium-card border-0 p-4 mb-4">
                                <h4 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-info-circle text-primary me-2"></i>Informasi Akademik
                                </h4>
                                <div class="info-row-item">
                                    <div class="info-row-label">Nomor Induk Siswa (NIS)</div>
                                    <div class="info-row-value">{{ $student?->nis ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row-item">
                                    <div class="info-row-label">Nomor Induk Siswa Nasional (NISN)</div>
                                    <div class="info-row-value">{{ $student?->nisn ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row-item">
                                    <div class="info-row-label">Rombongan Belajar (Kelas)</div>
                                    <div class="info-row-value">{{ $student?->studyGroup?->name ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row-item">
                                    <div class="info-row-label">Jurusan</div>
                                    <div class="info-row-value">{{ $student?->studyGroup?->major ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row-item">
                                    <div class="info-row-label">Status Akun</div>
                                    <div class="info-row-value text-success fw-bold">Terverifikasi</div>
                                </div>
                            </div>

                            <!-- Account Settings Form Card -->
                            <div class="card premium-card border-0 p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h4 class="fw-bold mb-0 text-dark">
                                        <i class="bi bi-sliders text-primary me-2"></i>Pengaturan Akun & Keamanan
                                    </h4>
                                    <button class="btn btn-sm {{ $showEdit ? 'btn-outline-secondary' : 'btn-primary' }}" wire:click="toggleEdit">
                                        <i class="bi {{ $showEdit ? 'bi-eye' : 'bi-pencil' }} me-1"></i>
                                        {{ $showEdit ? 'Tutup Pengeditan' : 'Edit Akun' }}
                                    </button>
                                </div>

                                @if (session()->has('profile_message'))
                                    <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="background-color: #f0fdf4; color: #15803d; border-radius: 12px;">
                                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('profile_message') }}
                                    </div>
                                @endif

                                @if (!$showEdit)
                                    <!-- Read-only view -->
                                    <div class="info-row-item">
                                        <div class="info-row-label">Nama Lengkap</div>
                                        <div class="info-row-value fw-bold">{{ auth()->user()->name }}</div>
                                    </div>
                                    <div class="info-row-item">
                                        <div class="info-row-label">Username</div>
                                        <div class="info-row-value">{{ auth()->user()->username }}</div>
                                    </div>
                                    <div class="info-row-item">
                                        <div class="info-row-label">Kode Verifikasi</div>
                                        <div class="info-row-value text-muted">••••••••</div>
                                    </div>
                                @else
                                    <!-- Edit form -->
                                    <form wire:submit.prevent="updateProfile">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Nama Lengkap *</label>
                                                <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" wire:model="name">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Username *</label>
                                                <input type="text" class="form-control rounded-3 @error('username') is-invalid @enderror" wire:model="username">
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Kode Verifikasi (NIS)</label>
                                                <div class="position-relative">
                                                    <input type="{{ $showVerifi ? 'text' : 'password' }}"
                                                           class="form-control rounded-3 pe-5 @error('verification_code') is-invalid @enderror"
                                                           wire:model="verification_code">
                                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                                          style="cursor: pointer; z-index: 10;" wire:click="toggleVerifi">
                                                        <i class="bi {{ $showVerifi ? 'bi-eye' : 'bi-eye-slash' }} text-muted"></i>
                                                    </span>
                                                </div>
                                                @error('verification_code')
                                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr class="my-4 border-light">

                                        <h5 class="fw-bold mb-3 text-dark">Ganti Kata Sandi</h5>
                                        <p class="text-muted small mb-3">Kosongkan kolom sandi baru jika Anda tidak ingin mengubahnya.</p>

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-dark">Kata Sandi Saat Ini</label>
                                                <input type="password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" wire:model="current_password">
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Kata Sandi Baru</label>
                                                <input type="password" class="form-control rounded-3 @error('new_password') is-invalid @enderror" wire:model="new_password">
                                                @error('new_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-dark">Konfirmasi Kata Sandi Baru</label>
                                                <input type="password" class="form-control rounded-3" wire:model="new_password_confirmation">
                                            </div>
                                        </div>

                                        <div class="mt-4 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold" wire:loading.attr="disabled" wire:target="updateProfile">
                                                <span wire:loading.remove wire:target="updateProfile">
                                                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                                                </span>
                                                <span wire:loading wire:target="updateProfile">
                                                    <span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...
                                                </span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3" wire:click="toggleEdit">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
