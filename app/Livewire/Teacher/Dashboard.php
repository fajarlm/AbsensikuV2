<?php

namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class Dashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Tab state
    public $activeTab = 'attendance';

    // Modal state
    public $showModal = false;
    public $selectedSchedule = null;
    public $selectedDate = null;
    public $modalStudents = [];
    public $modalAttendances = [];
    public $notes = [];

    // Submissions state
    public $showReviewModal = false;
    public $selectedSubmission = null;
    public $teacherNote = '';
    public $reviewStatus = '';

    // Filter
    public $search = '';
    public $filterDate = '';
    public $filterStatus = '';
    public $filterSchedule = '';

    public function mount()
    {
        $this->filterDate = now()->format('Y-m-d');
    }

    // Open modal untuk isi/edit absensi
    // public function openAttendanceModal($scheduleId)
    // {
    //     $this->selectedSchedule = $scheduleId;
    //     $this->selectedDate = $this->filterDate ?: now()->format('Y-m-d');

    //     $schedule = Schedule::with('studyGroup')->findOrFail($scheduleId);

    //     $this->modalStudents = Student::with('user')
    //         ->where('study_group_id', $schedule->study_group_id)
    //         ->orderBy('nis')
    //         ->get();

    //     $existingAttendances = Attendance::where('schedule_id', $scheduleId)
    //         ->whereDate('attendance_date', $this->selectedDate)
    //         ->get()
    //         ->keyBy('student_id');

    //     $this->modalAttendances = [];
    //     foreach ($this->modalStudents as $student) {
    //         if (isset($existingAttendances[$student->id])) {
    //             $this->modalAttendances[$student->id] = [
    //                 'id' => $existingAttendances[$student->id]->id,
    //                 'status' => $existingAttendances[$student->id]->status,
    //                 'note' => $existingAttendances[$student->id]->note,
    //             ];
    //         } else {
    //             $this->modalAttendances[$student->id] = [
    //                 'id' => null,
    //                 'status' => null,
    //                 'note' => null,
    //             ];
    //         }
    //     }

    //     $this->showModal = true;
    // }

    public function openAttendanceModal($scheduleId)
    {
        try {
            $schedule = Schedule::findOrFail($scheduleId);

            $attendanceDateStr = $this->filterDate ?: now()->format('Y-m-d');
            $attendanceDate = \Carbon\Carbon::parse($attendanceDateStr);
            $attendanceDayOfWeek = $attendanceDate->dayOfWeekIso;

            $dayMap = [
                'Senin'  => 1,
                'Selasa' => 2,
                'Rabu'   => 3,
                'Kamis'  => 4,
                'Jumat'  => 5,
                'Sabtu'  => 6,
                'Minggu' => 7,
            ];

            $scheduleDay = $dayMap[$schedule->day] ?? null;

            if ($attendanceDayOfWeek !== $scheduleDay) {
                $this->dispatch('swal:alert', [
                    'title' => 'Peringatan',
                    'text' => 'Absensi hanya bisa diisi pada hari yang sesuai dengan jadwal (' . $schedule->day . '). Tanggal yang Anda pilih (' . $attendanceDate->format('d-m-Y') . ') adalah hari ' . $this->getDayNameInIndonesian($attendanceDayOfWeek) . '.',
                    'icon' => 'warning'
                ]);
                return;
            }

            // ✅ lanjut normal
            $this->selectedSchedule = $scheduleId;
            $this->selectedDate = $attendanceDateStr;

            $this->modalStudents = Student::with('user')
                ->where('study_group_id', $schedule->study_group_id)
                ->orderBy('nis')
                ->get();

            $existingAttendances = Attendance::where('schedule_id', $scheduleId)
                ->whereDate('attendance_date', $this->selectedDate)
                ->get()
                ->keyBy('student_id');

            $this->modalAttendances = [];

            foreach ($this->modalStudents as $student) {
                $this->modalAttendances[$student->id] = [
                    'id'     => $existingAttendances[$student->id]->id ?? null,
                    'status' => $existingAttendances[$student->id]->status ?? null,
                    'note'   => $existingAttendances[$student->id]->note ?? null,
                ];
            }

            $this->showModal = true;
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'title' => 'Error!',
                'text' => 'Gagal membuka modul absensi: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }


    public function setStatus($studentId, $status)
    {
        if (!isset($this->modalAttendances[$studentId])) {
            return;
        }

        if ($this->modalAttendances[$studentId]['status'] === $status) {
            $this->modalAttendances[$studentId]['status'] = null;
        } else {
            $this->modalAttendances[$studentId]['status'] = $status;
        }
    }

    public function setAllStatus($status)
    {
        foreach ($this->modalStudents as $student) {
            $this->modalAttendances[$student->id]['status'] = $status;
        }
    }

    // Save all attendances
    public function saveAttendances()
    {
        if (!$this->selectedSchedule || !$this->selectedDate) {
            $this->dispatch('swal:alert', [
                'title' => 'Peringatan',
                'text' => 'Jadwal dan tanggal harus dipilih!',
                'icon' => 'warning'
            ]);
            return;
        }

        $user = Auth::user();
        $teacher = $user ? $user->teacher : null;
        if (!$teacher) {
            $this->dispatch('swal:alert', [
                'title' => 'Akses Ditolak',
                'text' => 'Akun Anda tidak memiliki data guru pengampu.',
                'icon' => 'error'
            ]);
            return;
        }

        try {
            foreach ($this->modalAttendances as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'schedule_id' => $this->selectedSchedule,
                        'student_id' => $studentId,
                        'attendance_date' => $this->selectedDate,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'status' => $data['status'],
                        'note' => $data['note'] ?? null
                    ]
                );
            }

            // Clear cache
            $teacherId = $teacher->id;
            cache()->forget('teacher_dashboard_stats_' . $teacherId);

            $this->dispatch('swal:alert', [
                'title' => 'Berhasil!',
                'text' => 'Absensi berhasil disimpan!',
                'icon' => 'success'
            ]);
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'title' => 'Gagal Menyimpan!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedSchedule', 'selectedDate', 'modalStudents', 'modalAttendances']);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterDate', 'filterStatus', 'filterSchedule']);
        $this->resetPage();
    }

    public function render()
    {
        $days = ['Senin'=> 1, 'Selasa'=> 2, 'Rabu'=> 3, 'Kamis'=> 4, 'Jumat'=> 5];

        $user = Auth::user();
        if (!$user || !$user->teacher) {
            $this->dispatch('swal:alert', [
                'title' => 'Akses Ditolak',
                'text' => 'Akun Anda tidak memiliki data guru pengampu.',
                'icon' => 'error'
            ]);
            return view('livewire.teacher.dashboard', [
                'attendances' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'schedules' => collect(),
                'stats' => ['total' => 0, 'present' => 0, 'absent' => 0, 'today' => 0],
                'pendingSubmissionsCount' => 0,
                'submissions' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
            ]);
        }

        $teacherId = $user->teacher->id;

        // Get schedules for the logged in teacher
        $schedules = cache()->remember('teacher_dashboard_schedules_' . $teacherId, 300, function () use ($teacherId) {
            return Schedule::whereHas('subject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->with(['subject.teacher', 'studyGroup'])->get();
        });
        $scheduleIds = $schedules->pluck('id')->toArray();
        $studyGroupIds = $schedules->pluck('study_group_id')->unique()->toArray();

        $pendingSubmissionsCount = Submission::whereHas('student', function ($query) use ($studyGroupIds) {
            $query->whereIn('study_group_id', $studyGroupIds);
        })->where('status', 'pending')->count();
        
        $submissions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);

        if ($this->activeTab === 'submissions') {
            $submissions = Submission::whereHas('student', function ($query) use ($studyGroupIds) {
                $query->whereIn('study_group_id', $studyGroupIds);
            })->with(['student.user', 'student.studyGroup'])
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        try {
            $query = Attendance::with(['student.user', 'schedule.subject', 'schedule.studyGroup'])
                ->whereIn('schedule_id', $scheduleIds)
                ->when($this->search, function ($query) {
                    $query->whereHas('student.user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('student', function ($q) {
                        $q->where('nis', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->filterDate, function ($query) {
                    $query->whereDate('attendance_date', $this->filterDate);
                })
                ->when($this->filterStatus, function ($query) {
                    $query->where('status', $this->filterStatus);
                })
                ->when($this->filterSchedule, function ($query) {
                    $query->where('schedule_id', $this->filterSchedule);
                })
                ->orderBy('attendance_date', 'desc')
                ->orderBy('created_at', 'desc');

            $attendances = $query->paginate(15);

            // Statistics
            $stats = cache()->remember('teacher_dashboard_stats_' . $teacherId, 300, function () use ($scheduleIds) {
                return [
                    'total' => Attendance::whereIn('schedule_id', $scheduleIds)->count(),
                    'present' => Attendance::whereIn('schedule_id', $scheduleIds)->where('status', 'present')->count(),
                    'absent' => Attendance::whereIn('schedule_id', $scheduleIds)
                        ->whereIn('status', ['absent', 'sick', 'permission', 'dispensed'])->count(),
                    'today' => Attendance::whereIn('schedule_id', $scheduleIds)
                        ->whereDate('attendance_date', now()->toDateString())
                        ->where('status', 'present')->count(),
                ];
            });

            $dbNotes = Attendance::whereIn('id', $attendances->pluck('id'))
                ->pluck('note', 'id')
                ->map(function ($note) {
                    return $note ?? '';
                })->toArray();

            foreach ($dbNotes as $id => $note) {
                if (!isset($this->notes[$id])) {
                    $this->notes[$id] = $note;
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'title' => 'Error!',
                'text' => 'Gagal memuat data: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
            $attendances = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $schedules = collect();
            $stats = ['total' => 0, 'present' => 0, 'absent' => 0, 'today' => 0];
        }

        return view('livewire.teacher.dashboard', [
            'attendances' => $attendances,
            'schedules' => $schedules,
            'stats' => $stats,
            'pendingSubmissionsCount' => $pendingSubmissionsCount,
            'submissions' => $submissions,
        ]);
    }

    public function openReviewModal($submissionId, $status)
    {
        $this->selectedSubmission = Submission::with(['student.user', 'student.studyGroup'])->findOrFail($submissionId);
        $this->reviewStatus = $status;
        $this->teacherNote = '';
        $this->showReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->selectedSubmission = null;
        $this->teacherNote = '';
        $this->reviewStatus = '';
    }

    public function processReview()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            $submission = $this->selectedSubmission;
            $submission->status = $this->reviewStatus;
            $submission->teacher_note = $this->teacherNote;
            $submission->save();

            // If approved, sync attendance records
            if ($this->reviewStatus === 'approved') {
                $start = \Carbon\Carbon::parse($submission->start_date);
                $end = \Carbon\Carbon::parse($submission->end_date);
                $student = $submission->student;
                $schedules = Schedule::where('study_group_id', $student->study_group_id)->get();

                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    $dayName = $this->getDayNameInIndonesian($date->dayOfWeekIso);
                    $daySchedules = $schedules->where('day', $dayName);

                    foreach ($daySchedules as $schedule) {
                        Attendance::updateOrCreate(
                            [
                                'schedule_id' => $schedule->id,
                                'student_id' => $student->id,
                                'attendance_date' => $date->format('Y-m-d'),
                            ],
                            [
                                'teacher_id' => Auth::user()->teacher->id,
                                'status' => $submission->type,
                                'note' => 'Izin Online: ' . ($submission->reason ?? '') . ' (' . ($this->teacherNote ?? '') . ')'
                            ]
                        );
                    }
                }
            }

            // Clear cache since attendance records were updated
            $teacherId = Auth::user()->teacher->id;
            cache()->forget('teacher_dashboard_stats_' . $teacherId);

            $this->dispatch('swal:alert', [
                'title' => 'Berhasil!',
                'text' => 'Status pengajuan berhasil diperbarui.',
                'icon' => 'success'
            ]);
            $this->closeReviewModal();
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'title' => 'Gagal Memproses!',
                'text' => $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function refreshData()
    {
        $teacherId = Auth::user()->teacher->id;
        cache()->forget('teacher_dashboard_schedules_' . $teacherId);
        cache()->forget('teacher_dashboard_stats_' . $teacherId);
        $this->dispatch('swal:alert', [
            'title' => 'Berhasil!',
            'text' => 'Data dashboard berhasil diperbarui!',
            'icon' => 'success'
        ]);
    }

    public function updatedNotes($value, $key)
    {
        try {
            $attendance = Attendance::findOrFail($key);
            $attendance->note = $value;
            $attendance->save();
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'title' => 'Error!',
                'text' => 'Gagal memperbarui catatan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    private function getDayNameInIndonesian($dayOfWeekIso)
    {
        $map = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        return $map[$dayOfWeekIso] ?? 'Senin';
    }
}
