<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Dashboard extends Component
{
    use WithFileUploads;

    public $activeTab = 'schedule';

    public $name;
    public $username;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $profile;
    public $photoPreview;
    public $studentData;
    public $verification_code;

    public $showVerifi;

    public $selectedDate;
    public $selectedSchedule;

    public $showEdit = false;

    public function toggleEdit()
    {
        $this->showEdit = !$this->showEdit;
    }

    public function toggleVerifi()
    {
        $this->showVerifi = !$this->showVerifi;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
            'verification_code' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }


    protected $messages = [
        'profile.image' => 'File must be an image',
        'profile.mimes' => 'Only JPEG, PNG, and JPG formats are allowed',
        'profile.max' => 'Image size must not exceed 2MB',
        'new_password.min' => 'Password must be at least 8 characters',
        'new_password.confirmed' => 'Password confirmation does not match',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        // $this->verification_code = decrypt($user->student->verification_code);
        $this->verification_code = $user->student->verification_code;

        $this->studentData = Student::with(['studyGroup', 'user'])->where('user_id', $user->id)->first();

        $this->selectedDate = now()->format('Y-m-d');

        if ($user->profile) {
            $this->photoPreview = asset('storage/' . $user->profile);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $student = Student::with(['studyGroup', 'user'])->where('user_id', $user->id)->first();

        $data = [
            'student' => $student,
            'today' => now()->format('Y-m-d'),
        ];

        switch ($this->activeTab) {
            case 'schedule':
                $data['schedules'] = $this->getSchedules();
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
            ->where('study_group_id', $student->study_group_id)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
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
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->resetValidation();

        if ($tab === 'profile') {
            $user = Auth::user();
            $this->name = $user->name;
            $this->username = $user->username;
            $this->verification_code = $user->student->verification_code ?? '';

            if ($user->profile) {
                $this->photoPreview = asset('storage/' . $user->profile);
            }
        }
    }

    public function updatedProfile()
    {
        $this->validate([
            'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($this->profile) {
            try {
                $this->photoPreview = $this->profile->temporaryUrl();
            } catch (\Exception $e) {
                $this->addError('profile', 'Failed to preview image');
            }
        }
    }

    public function updatePhotoOnly()
    {
        $this->validate([
            'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        try {
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            $path = $this->profile->store('profiles', 'public');
            $user->profile = $path;
            $user->save();

            $this->photoPreview = asset('storage/' . $path);

            $this->profile = null;

            session()->flash('photo_message', 'Profile photo updated successfully!');

            $this->dispatch('profile-updated');
        } catch (\Exception $e) {
            $this->addError('profile', 'Failed to upload photo: ' . $e->getMessage());
        }
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'verification_code' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($this->new_password) {
            if (!$this->current_password) {
                $this->addError('current_password', 'Current password is required to set new password');
                return;
            }

            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Current password is incorrect');
                return;
            }
        }

        try {
            $user->name = $this->name;
            $user->username = $this->username;

            if ($user->student) {
                $user->student->verification_code = $this->verification_code;
                $user->student->save();
            }

            if ($this->new_password) {
                $user->password = Hash::make($this->new_password);
            }

            $user->save();

            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

            session()->flash('profile_message', 'Profile updated successfully!');

            $this->showEdit = false;
        } catch (\Exception $e) {
            $this->addError('name', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function removePhoto()
    {
        $user = Auth::user();

        try {
            if ($user->profile) {
                if (Storage::disk('public')->exists($user->profile)) {
                    Storage::disk('public')->delete($user->profile);
                }

                $user->profile = null;
                $user->save();

                $this->photoPreview = null;
                $this->profile = null;

                session()->flash('photo_message', 'Profile photo removed successfully!');

                $this->dispatch('profile-updated');
            }
        } catch (\Exception $e) {
            $this->addError('profile', 'Failed to remove photo: ' . $e->getMessage());
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
