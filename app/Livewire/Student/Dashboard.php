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
use App\Models\Submission;

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

    // Form Pengajuan Online
    public $submission_type = 'sick';
    public $start_date;
    public $end_date;
    public $reason;
    public $attachment;

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
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');

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

            case 'submission':
                $data['submissions'] = Submission::where('student_id', $student->id)
                    ->orderBy('created_at', 'desc')
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

    public function submitSubmission()
    {
        $this->validate([
            'submission_type' => 'required|in:sick,permission,dispensed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'submission_type.required' => 'Tipe pengajuan wajib dipilih.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'reason.required' => 'Alasan wajib diisi.',
            'reason.min' => 'Alasan minimal 10 karakter.',
            'attachment.mimes' => 'Lampiran harus berupa file PDF, JPG, JPEG, atau PNG.',
            'attachment.max' => 'Ukuran lampiran maksimal 2MB.',
        ]);

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('submissions', 'public');
        }

        Submission::create([
            'student_id' => $this->studentData->id,
            'type' => $this->submission_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        $this->reset(['submission_type', 'reason', 'attachment']);
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');

        session()->flash('submission_message', 'Pengajuan online berhasil dikirim!');
    }

    public function cancelSubmission($id)
    {
        $submission = Submission::where('student_id', $this->studentData->id)
            ->where('id', $id)
            ->first();

        if ($submission && $submission->status === 'pending') {
            if ($submission->attachment && Storage::disk('public')->exists($submission->attachment)) {
                Storage::disk('public')->delete($submission->attachment);
            }
            $submission->delete();
            session()->flash('submission_message', 'Pengajuan berhasil dibatalkan!');
        }
    }

    public function getSubmissionStatusClass($status)
    {
        $classes = [
            'pending' => 'bg-warning text-white',
            'approved' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
        ];
        return $classes[$status] ?? 'bg-secondary text-white';
    }

    public function getSubmissionStatusText($status)
    {
        $texts = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
        return $texts[$status] ?? $status;
    }

    public function getSubmissionTypeClass($type)
    {
        $classes = [
            'sick' => 'bg-info text-white',
            'permission' => 'bg-warning text-dark',
            'dispensed' => 'bg-secondary text-white',
        ];
        return $classes[$type] ?? 'bg-dark text-white';
    }

    public function getSubmissionTypeText($type)
    {
        $texts = [
            'sick' => 'Sakit',
            'permission' => 'Izin',
            'dispensed' => 'Dispensasi',
        ];
        return $texts[$type] ?? $type;
    }
}
