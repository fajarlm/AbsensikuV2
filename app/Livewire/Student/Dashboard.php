<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student ;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Component
{
    use WithFileUploads;

    public $activeTab = 'schedule'; 

    public $name;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $profile;
    public $photoPreview;
    public $studentData;
    public $verification_code;

    public $selectedDate;
    public $selectedSchedule;

    public $showEdit = False;

    public $activedTab = false;

    public function toggleEdit()
    {
        $this->showEdit = !$this->showEdit;
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'verification_code' => 'nullable|string|max:255',
        'new_password' => 'nullable|min:8|confirmed',
        'profile' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->verification_code = $user->verification_code;

        // Load student data
        $this->studentData = Student::with(['studyGroup', 'user'])
            ->where('user_id', $user->id)
            ->first();

        $this->selectedDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $user = Auth::user();
        $student = Student::with(['studyGroup', 'user'])
            ->where('user_id', $user->id)
            ->first();

        $data = [
            'student' => $student,
            'today' => now()->format('Y-m-d'),
        ];

        switch ($this->activeTab) {
            case 'schedule':
                $data['schedules'] = $this->getSchedules();
                break;

            case 'attendance':
                $data['attendances'] = $this->getAttendances();
                $data['schedules'] = Schedule::where('study_group_id', $student->study_group_id)
                    ->with(['subject', 'teacher'])
                    ->get();

                break;

            case 'profile':
                $data['studyGroups'] = StudyGroup::all();
                break;
        }

        return view('livewire.student.dashboard', $data);
    }

    private function getSchedules()
    {
        $student = $this->studentData;

        if (!$student) {
            return collect();
        }

        return Schedule::with('subject')
            ->where(function ($query) use ($student) {
               $query->where('study_group_id', $student->study_group_id);
            //    dd($student);

            })
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
    }

    private function getAttendances()
    {
        $student = $this->studentData;

        if (!$student) {
            return collect();
        }

        return Attendance::with(['schedule.subject', 'schedule.teacher'])
            ->where('student_id', $student->id)
            ->when($this->selectedDate, function ($query) {
                $query->whereDate('attendance_date', $this->selectedDate);
            })
            ->when($this->selectedSchedule, function ($query) {
                $query->where('schedule_id', $this->selectedSchedule);
            })
            ->orderBy('attendance_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->reset(['current_password', 'new_password','verification_code', 'new_password_confirmation', 'profile']);

        if ($tab === 'profile') {
            $this->photoPreview = Auth::user()->profile ?? '';
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'verification_code' => 'nullable|string|max:255',
            'profile' => 'nullable|image|max:2048',
        ];

        if ($this->new_password) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules);

        if ($this->new_password) {
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Current password is incorrect.');
                return;
            }
        }

        $user->name = $this->name;

        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }

        if ($this->profile) {
            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }

            $path = $this->profile->store('profiles/', 'public');
            $user->profile = $path;
        }

        $user->save();

        $student = Student::where('user_id', $user->id)->first();
        
        // Reset form
        $this->reset(['current_password', 'new_password','verification_code' ,'new_password_confirmation', 'profile']);
        $this->photoPreview = $user->profile ?? '';

        session()->flash('profile_message', 'Profile updated successfully!');
    }

    public function updatedPhoto()
    {
        $this->validate([
            'profile' => 'nullable|image|max:2048',
        ]);

        if ($this->profile) {
            $this->photoPreview = $this->profile->temporaryUrl();
        }
    }

    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->profile) {
            Storage::disk('public')->delete($user->profile);
            $user->profile = null;
            $user->save();

            $this->photoPreview = '';
            $this->profile = null;
        }
    }

    public function getAttendanceStatus($scheduleId, $date)
    {
        if (!$this->studentData) {
            return null;
        }

        $attendance = Attendance::where('student_id', $this->studentData->id)
            ->where('schedule_id', $scheduleId)
            ->whereDate('attendance_date', $date)
            ->first();

        return $attendance ? $attendance->status : null;
    }

    public function getAttendanceStatusClass($status)
    {
        $classes = [
            'present' => 'bg-success',
            'absent' => 'bg-danger',
            'sick' => 'bg-info',
            'permission' => 'bg-warning',
            'dispensed' => 'bg-secondary',
        ];

        return $classes[$status] ?? 'bg-light';
    }

    public function getAttendanceStatusText($status)
    {
        $texts = [
            'present' => 'Present',
            'absent' => 'Absent',
            'sick' => 'Sick',
            'permission' => 'Permission',
            'dispensed' => 'Dispensed',
        ];

        return $texts[$status] ?? 'Not Recorded';
    }
}
