<?php

namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Modal state
    public $showModal = false;
    public $selectedSchedule = null;
    public $selectedDate = null;
    public $modalStudents = [];
    public $modalAttendances = [];
    public $notes = [];

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

            $today = now()->dayOfWeekIso;

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

            if ($today !== $scheduleDay) {
                $this->dispatch('swal:alert', [
                    'title' => 'Peringatan',
                    'text' => 'Absensi hanya bisa diisi di hari jadwal.',
                    'icon' => 'warning'
                ]);
                return;
            }

            // ✅ lanjut normal
            $this->selectedSchedule = $scheduleId;
            $this->selectedDate = now()->format('Y-m-d');

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
            ]);
        }

        try {
            $schedules = Schedule::with(['subject.teacher', 'studyGroup'])->get();
            $scheduleIds = $schedules->pluck('id')->toArray();

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
            $stats = [
                'total' => Attendance::whereIn('schedule_id', $scheduleIds)->count(),
                'present' => Attendance::whereIn('schedule_id', $scheduleIds)->where('status', 'hadir')->count(),
                'absent' => Attendance::whereIn('schedule_id', $scheduleIds)
                    ->whereIn('status', ['izin', 'sakit', 'alpa'])->count(),
                'today' => Attendance::whereIn('schedule_id', $scheduleIds)
                    ->whereDate('attendance_date', now())->count(),
            ];

            $this->notes = Attendance::whereIn('id', $attendances->pluck('id'))
                ->pluck('note', 'id')
                ->map(function ($note) {
                    return $note ?? '';
                })->toArray();
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
        ]);
    }
}
