<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudyGroup;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalUsers;
    public $totalStudents;
    public $totalTeachers;
    public $totalStudyGroups;
    public $totalSubjects;
    public $todayAttendance;

    // Data untuk chart
    public $attendanceWeekly;
    public $genderDistribution;
    public $studentsPerClass;
    public $monthlyRegistration;

    public function mount()
    {
        $this->loadStatistics();
        $this->loadChartData();
    }

    public function loadStatistics()
    {
        $this->totalUsers = User::count();
        $this->totalStudents = Student::count();
        $this->totalTeachers = Teacher::count();
        $this->totalStudyGroups = StudyGroup::count();
        $this->totalSubjects = Subject::count();
        $this->todayAttendance = Attendance::whereDate('created_at', today())->count();
    }

    public function loadChartData()
    {
        // 1. Data Kehadiran Mingguan (7 hari terakhir)
        $weeklyAttendance = Attendance::select(
                DB::raw('DATE(created_at) as date'),
                'status',
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        $dates = [];
        $hadir = [];
        $izin = [];
        $sakit = [];
        $alpa = [];
        $dispen = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->locale('id')->isoFormat('dddd');
            $dates[] = $dayName;

            $hadir[] = $weeklyAttendance->where('date', $date)->where('status', 'present')->first()->total ?? 0;
            $izin[] = $weeklyAttendance->where('date', $date)->where('status', 'permission')->first()->total ?? 0;
            $sakit[] = $weeklyAttendance->where('date', $date)->where('status', 'sick')->first()->total ?? 0;
            $alpa[] = $weeklyAttendance->where('date', $date)->where('status', 'absent')->first()->total ?? 0;
            $dispen[] = $weeklyAttendance->where('date', $date)->where('status', 'dispensed')->first()->total ?? 0;
        }

        $this->attendanceWeekly = [
            'labels' => $dates,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'dispen' => $dispen,
        ];

        // 2. Distribusi Gender
        $maleStudents = User::where('role', 'student')->where('gender', 'male')->count();
        $femaleStudents = User::where('role', 'student')->where('gender', 'female')->count();

        $this->genderDistribution = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'data' => [$maleStudents, $femaleStudents],
        ];

        $studentsPerClass = StudyGroup::whereHas('students')->withCount('students')->get();

        $this->studentsPerClass = [
            'labels' => $studentsPerClass->pluck('name')->toArray(),
            'data' => $studentsPerClass->pluck('students_count')->toArray(),
        ];

        // 4. Pendaftaran Bulanan (12 bulan terakhir)
        $registrations = Student::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->locale('id')->isoFormat('MMM');
            
            $count = $registrations
                ->where('month', $date->month)
                ->where('year', $date->year)
                ->first();
            
            $data[] = $count ? $count->total : 0;
        }

        $this->monthlyRegistration = [
            'labels' => $months,
            'data' => $data,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}