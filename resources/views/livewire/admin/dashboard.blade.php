<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- Absensi kehadiran stats -->
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
                            <h3 class="mb-0">{{ $totalStudents }}</h3>
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
                            <h3 class="mb-0">{{ $totalTeachers }}</h3>
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
                            <h3 class="mb-0">{{ $totalStudyGroups }}</h3>
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
                            <h3 class="mb-0">{{ $totalSubjects }}</h3>
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
                        <span class="badge bg-primary">7 Hari Terakhir</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Jenis Kelamin Siswa</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- More Charts -->
    {{-- <div class="row g-3 mb-4">
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
    </div> --}}

    <!-- Quick Actions & System Info -->
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
                                <h6 class="mb-0">{{ $totalUsers }} Pengguna</h6>
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

    @push('script')
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Color Palette
            const colors = {
                primary: '#0d6efd',
                success: '#198754',
                warning: '#ffc107',
                danger: '#dc3545',
                info: '#0dcaf0',
                purple: '#6f42c1',
                pink: '#d63384',
                orange: '#fd7e14'
            };

            // Data dari Livewire
            const attendanceData = @json($attendanceWeekly);
            const genderData = @json($genderDistribution);
            const classData = @json($studentsPerClass);
            const registrationData = @json($monthlyRegistration);

            // 1. Attendance Line Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: attendanceData.labels,
                    datasets: [{
                        label: 'Hadir',
                        data: attendanceData.hadir,
                        borderColor: colors.success,
                        backgroundColor: colors.success + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Izin',
                        data: attendanceData.izin,
                        borderColor: colors.warning,
                        backgroundColor: colors.warning + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Sakit',
                        data: attendanceData.sakit,
                        borderColor: colors.info,
                        backgroundColor: colors.info + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'dispen',
                        data: attendanceData.dispen,
                        borderColor: colors.purple,
                        backgroundColor: colors.purple + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Alpa',
                        data: attendanceData.alpa,
                        borderColor: colors.danger,
                        backgroundColor: colors.danger + '20',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });

            // 2. Gender Doughnut Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: genderData.labels,
                    datasets: [{
                        data: genderData.data,
                        backgroundColor: [colors.primary, colors.pink],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // const classCtx = document.getElementById('studentsPerClassChart').getContext('2d');
            // new Chart(classCtx, {
            //     type: 'bar',
            //     data: {
            //         labels: classData.labels,
            //         datasets: [{
            //             label: 'Jumlah Siswa',
            //             data: classData.data,
            //             backgroundColor: colors.primary,
            //             borderRadius: 6,
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: true,
            //         plugins: {
            //             legend: {
            //                 display: false
            //             },
            //             tooltip: {
            //                 callbacks: {
            //                     label: function(context) {
            //                         return 'Siswa: ' + context.parsed.y;
            //                     }
            //                 }
            //             }
            //         },
            //         scales: {
            //             y: {
            //                 beginAtZero: true,
            //                 ticks: {
            //                     stepSize: 5
            //                 }
            //             }
            //         }
            //     }
            // });

            // const registrationCtx = document.getElementById('monthlyRegistrationChart').getContext('2d');
            // new Chart(registrationCtx, {
            //     type: 'line',
            //     data: {
            //         labels: registrationData.labels,
            //         datasets: [{
            //             label: 'Pendaftaran Siswa',
            //             data: registrationData.data,
            //             borderColor: colors.success,
            //             backgroundColor: colors.success + '30',
            //             tension: 0.4,
            //             fill: true,
            //             pointRadius: 4,
            //             pointHoverRadius: 6
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: true,
            //         plugins: {
            //             legend: {
            //                 display: false
            //             },
            //             tooltip: {
            //                 callbacks: {
            //                     label: function(context) {
            //                         return 'Siswa Baru: ' + context.parsed.y;
            //                     }
            //                 }
            //             }
            //         },
            //         scales: {
            //             y: {
            //                 beginAtZero: true
            //             }
            //         }
            //     }
            // });
        });
    </script>
    @endpush
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endpush