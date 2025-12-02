<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ now()->format('l, d F Y') }}
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Students -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Siswa</h6>
                            <h3 class="mb-0">{{ \App\Models\Student::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.user.student.index') }}" class="text-decoration-none small">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-person-badge-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Guru</h6>
                            <h3 class="mb-0">{{ \App\Models\Teacher::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.user.teacher.index') }}" class="text-decoration-none small">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Study Groups -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-collection-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Kelas</h6>
                            <h3 class="mb-0">{{ \App\Models\StudyGroup::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.study_group.index') }}" class="text-decoration-none small">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Subjects -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-book-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Mata Pelajaran</h6>
                            <h3 class="mb-0">{{ \App\Models\Subject::count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.subject.index') }}" class="text-decoration-none small">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-3 mb-4">
        <!-- Student Attendance Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Statistik Kehadiran Siswa</h5>
                        <select class="form-select form-select-sm" style="width: auto;" id="attendancePeriod">
                            <option value="week">Minggu Ini</option>
                            <option value="month" selected>Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Distribusi Jenis Kelamin</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- More Charts -->
    <div class="row g-3 mb-4">
        <!-- Students per Class -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Jumlah Siswa per Kelas</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentsPerClassChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Registration -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Pendaftaran Siswa Bulanan</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRegistrationChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row g-3">
        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('admin.user.student.index') }}" class="text-decoration-none">
                                <div class="card border text-center h-100 hover-shadow">
                                    <div class="card-body py-4">
                                        <i class="bi bi-person-plus-fill fs-2 text-primary mb-2"></i>
                                        <p class="mb-0 small">Kelola Siswa</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.user.teacher.index') }}" class="text-decoration-none">
                                <div class="card border text-center h-100 hover-shadow">
                                    <div class="card-body py-4">
                                        <i class="bi bi-person-badge fs-2 text-success mb-2"></i>
                                        <p class="mb-0 small">Kelola Guru</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.schedule.index') }}" class="text-decoration-none">
                                <div class="card border text-center h-100 hover-shadow">
                                    <div class="card-body py-4">
                                        <i class="bi bi-calendar-check fs-2 text-warning mb-2"></i>
                                        <p class="mb-0 small">Jadwal Pelajaran</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.attendance.index') }}" class="text-decoration-none">
                                <div class="card border text-center h-100 hover-shadow">
                                    <div class="card-body py-4">
                                        <i class="bi bi-clipboard-check fs-2 text-info mb-2"></i>
                                        <p class="mb-0 small">Absensi</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 small text-muted">Total User Aktif</p>
                                <h6 class="mb-0">{{ \App\Models\User::count() }} Pengguna</h6>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 small text-muted">Tahun Ajaran</p>
                                <h6 class="mb-0">2024/2025</h6>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 small text-muted">Semester</p>
                                <h6 class="mb-0">Genap</h6>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 small text-muted">Status Sistem</p>
                                <h6 class="mb-0">Berjalan Normal</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
