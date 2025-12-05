<?php

namespace App\Livewire\Admin\Attendance;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\StudyGroup;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 25;
    public $search = '';
    public $filterClass = '';
    public $filterDate = '';
    public $filterStatus = '';

    // Tambahan untuk export user PDF
    public $role;

    public function mount($role = null)
    {
        $this->role = $role; // inisialisasi role kalau ada
    }

    public function render()
    {
        $attendances = Attendance::with(['student.user', 'schedule.studyGroup', 'schedule.subject'])
            ->select('attendances.*')
            ->join('schedules', 'attendances.schedule_id', '=', 'schedules.id')
            ->join('study_groups', 'schedules.study_group_id', '=', 'study_groups.id')
            ->when($this->search, function ($q) {
                $q->whereHas('student.user', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('schedule.subject', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->filterClass, fn($q) => $q->where('schedules.study_group_id', $this->filterClass))
            ->when($this->filterDate, fn($q) => $q->whereDate('attendances.attendance_date', $this->filterDate))
            ->when($this->filterStatus, fn($q) => $q->where('attendances.status', $this->filterStatus))
            ->orderBy('attendances.attendance_date', 'desc')
            ->orderBy('study_groups.grade')
            ->orderBy('study_groups.class_number')
            ->paginate($this->perPage);

        $classes = StudyGroup::orderBy('grade')->orderBy('major')->orderBy('class_number')->get();

        return view('livewire.admin.attendance.index', [
            'attendances' => $attendances,
            'classes' => $classes,
            'total' => Attendance::count(),
            'present' => Attendance::where('status', 'present')->count(),
            'absent' => Attendance::where('status', 'absent')->count(),
            'sick' => Attendance::where('status', 'sick')->count(),
            'permission' => Attendance::where('status', 'permission')->count(),
            'dispensed' => Attendance::where('status', 'dispensed')->count(),
        ]);
    }

    // EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new AttendanceExport, 'Laporan_Absensi_' . now()->format('Y-m-d') . '.xlsx');
    }

    private function getUsersQuery($role)
    {
        if ($role === 'student') {
            return User::whereHas('student')->with(['student.studyGroup.homeroomTeacher.user']);
        } elseif ($role === 'teacher') {
            return User::whereHas('teacher')->with(['teacher.subjects']);
        } else {
            return User::where('role', 'admin');
        }
    }

    protected function getQuery()
    {
        return Attendance::with(['student.user', 'schedule.studyGroup', 'schedule.subject'])
            ->join('schedules', 'attendances.schedule_id', '=', 'schedules.id')
            ->join('study_groups', 'schedules.study_group_id', '=', 'study_groups.id')
            ->when($this->search, fn($q) => $q->whereHas('student.user', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterClass, fn($q) => $q->where('schedules.study_group_id', $this->filterClass))
            ->when($this->filterDate, fn($q) => $q->whereDate('attendances.attendance_date', $this->filterDate))
            ->when($this->filterStatus, fn($q) => $q->where('attendances.status', $this->filterStatus))
            ->select('attendances.*')
            ->orderBy('attendances.attendance_date', 'desc');
    }
}
