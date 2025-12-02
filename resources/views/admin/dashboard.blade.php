@extends('layouts.app')

@section('title', 'Dahsboard')
{{-- @section('menuAdminDashboard', 'active') --}}

@section('content')
    @livewire('admin.dashboard')

@endsection

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

@push('scripts')
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
        let dataAttendance = []; 
            // 1. Attendance Line Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    datasets: [{
                        label: 'Hadir',
                        data: ,
                        borderColor: colors.success,
                        backgroundColor: colors.success + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Izin',
                        data: [15, 18, 12, 20, 22, 25],
                        borderColor: colors.warning,
                        backgroundColor: colors.warning + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Sakit',
                        data: [10, 12, 15, 13, 13, 15],
                        borderColor: colors.info,
                        backgroundColor: colors.info + '20',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Alpa',
                        data: [5, 5, 5, 5, 5, 5],
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
                                stepSize: 50
                            }
                        }
                    }
                }
            });

            // 2. Gender Doughnut Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            const totalStudents = {{ \App\Models\Student::count() }};
            const maleStudents = {{ \App\Models\User::where('role', 'student')->where('gender', 'L')->count() }};
            const femaleStudents = {{ \App\Models\User::where('role', 'student')->where('gender', 'P')->count() }};

            new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [maleStudents, femaleStudents],
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

            // 3. Students per Class Bar Chart
            const classCtx = document.getElementById('studentsPerClassChart').getContext('2d');
            new Chart(classCtx, {
                type: 'bar',
                data: {
                    labels: ['X-1', 'X-2', 'X-3', 'XI-1', 'XI-2', 'XI-3', 'XII-1', 'XII-2', 'XII-3'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: [32, 30, 35, 33, 31, 34, 28, 30, 32],
                        backgroundColor: colors.primary,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Siswa: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });

            // 4. Monthly Registration Line Chart
            const registrationCtx = document.getElementById('monthlyRegistrationChart').getContext('2d');
            new Chart(registrationCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                    datasets: [{
                        label: 'Pendaftaran Siswa',
                        data: [5, 8, 12, 10, 15, 150, 180, 25, 8, 5, 3, 2],
                        borderColor: colors.success,
                        backgroundColor: colors.success + '30',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Siswa Baru: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Period selector for attendance chart
            document.getElementById('attendancePeriod').addEventListener('change', function(e) {
                const period = e.target.value;
                let newLabels, newData;

                if (period === 'week') {
                    newLabels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    attendanceChart.data.datasets[0].data = [450, 445, 448, 442, 440, 435];
                    attendanceChart.data.datasets[1].data = [15, 18, 12, 20, 22, 25];
                    attendanceChart.data.datasets[2].data = [10, 12, 15, 13, 13, 15];
                    attendanceChart.data.datasets[3].data = [5, 5, 5, 5, 5, 5];
                } else if (period === 'month') {
                    newLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                    attendanceChart.data.datasets[0].data = [2240, 2230, 2225, 2220];
                    attendanceChart.data.datasets[1].data = [95, 100, 98, 102];
                    attendanceChart.data.datasets[2].data = [50, 52, 55, 53];
                    attendanceChart.data.datasets[3].data = [15, 18, 22, 25];
                } else {
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                        'Nov', 'Des'
                    ];
                    attendanceChart.data.datasets[0].data = [8900, 8920, 8950, 8940, 8960, 8980, 9000, 9020,
                        9010, 9030, 9050, 9040
                    ];
                    attendanceChart.data.datasets[1].data = [380, 370, 360, 375, 365, 355, 345, 340, 350,
                        345, 340, 350
                    ];
                    attendanceChart.data.datasets[2].data = [200, 210, 205, 215, 220, 225, 230, 220, 225,
                        230, 235, 240
                    ];
                    attendanceChart.data.datasets[3].data = [120, 100, 85, 70, 55, 40, 25, 20, 15, 95, 75,
                        70
                    ];
                }

                attendanceChart.data.labels = newLabels;
                attendanceChart.update();
            });
        });
    </script>
@endpush
