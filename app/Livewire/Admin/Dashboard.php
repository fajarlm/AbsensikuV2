<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalUsers;
    public $totalStudents;
    public $totalTeachers;
    public $todayAttendance;

    // Data untuk chart
    public $usersByRole;
    public $attendanceByMonth;
    public $attendanceByStatus;

    public function mount()
    {
        $this->loadStatistics();
        $this->loadChartData();
    }

    public function loadStatistics()
    {
        $this->totalUsers = User::count();
        $this->totalStudents = User::where('role', 'student')->count();
        $this->totalTeachers = User::where('role', 'teacher')->count();
        $this->todayAttendance = Attendance::whereDate('created_at', today())->count();
    }

    public function loadChartData()
    {
        // Data untuk Pie Chart - Users by Role
        $this->usersByRole = [
            'labels' => ['Admin', 'Guru', 'Siswa'],
            'data' => [
                User::where('role', 'admin')->count(),
                User::where('role', 'teacher')->count(),
                User::where('role', 'student')->count(),
            ],
        ];

        // Data untuk Line Chart - Attendance by Month (6 bulan terakhir)
        $attendanceData = Attendance::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $this->attendanceByMonth = [
            'labels' => $attendanceData->pluck('month')->map(function($month) {
                return \Carbon\Carbon::parse($month)->format('M Y');
            })->toArray(),
            'data' => $attendanceData->pluck('total')->toArray(),
        ];

        // Data untuk Doughnut Chart - Attendance by Status
        $this->attendanceByStatus = [
            'labels' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            'data' => [
                Attendance::where('status', 'hadir')->count(),
                Attendance::where('status', 'izin')->count(),
                Attendance::where('status', 'sakit')->count(),
                Attendance::where('status', 'alpa')->count(),
            ],
        ];
    }
    public function render()
    {
        // return view('livewire.admin.dashboard')->layout('layouts.app');
        return view('livewire.admin.dashboard');
    }
}
