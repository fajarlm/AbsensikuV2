<?php
// app/Livewire/Admin/Student/Index.php

namespace App\Livewire\Admin\Student;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Student;
use App\Models\StudyGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Filter properties
    public $search = '';
    public $filterStatus = '';
    public $filterGender = '';
    public $filterGrade = '';
    public $filterMajor = '';
    public $perPage = 10;

    // Form properties - User
    public $student_id;
    public $user_id;
    public $name;
    public $username;
    public $password;
    public $password_confirmation;
    public $gender;
    public $profile;
    public $oldProfile;

    // Form properties - Student
    public $nis;
    public $study_group_id;
    public $status = 'active';
    public $entry_year;

    // Modal state
    public $isEdit = false;

    // Study groups for dropdown
    public $studyGroups = [];

    public function mount()
    {
        $this->studyGroups = StudyGroup::orderBy('grade')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();
        $this->entry_year = date('Y');
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user_id)
            ],
            'gender' => 'required|in:male,female',
            'profile' => 'nullable|image|max:2048',
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students')->ignore($this->student_id)
            ],
            'study_group_id' => 'nullable|exists:study_groups,id',
            'status' => 'required|in:active,inactive,graduated,dropped_out',
            'entry_year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi',
        'username.required' => 'Username wajib diisi',
        'username.unique' => 'Username sudah digunakan',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'gender.required' => 'Jenis kelamin wajib dipilih',
        'nis.required' => 'NIS wajib diisi',
        'nis.unique' => 'NIS sudah digunakan',
        'status.required' => 'Status wajib dipilih',
        'entry_year.required' => 'Tahun masuk wajib diisi',
        'entry_year.digits' => 'Tahun masuk harus 4 digit',
        'profile.image' => 'File harus berupa gambar',
        'profile.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterGender()
    {
        $this->resetPage();
    }

    public function updatingFilterGrade()
    {
        $this->resetPage();
    }

    public function updatingFilterMajor()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
    }

    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);

        $this->student_id = $student->id;
        $this->user_id = $student->user_id;
        $this->name = $student->user->name;
        $this->username = $student->user->username;
        $this->gender = $student->user->gender;
        $this->oldProfile = $student->user->profile;

        $this->nis = $student->nis;
        $this->study_group_id = $student->study_group_id;
        $this->status = $student->status;
        $this->entry_year = $student->entry_year;

        $this->isEdit = true;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Data User
            $userData = [
                'name' => $this->name,
                'username' => $this->username,
                'role' => 'student',
                'gender' => $this->gender,
            ];

            // Handle password
            if (!$this->isEdit) {
                $userData['password'] = Hash::make($this->password);
            } elseif ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            // Handle file upload
            if ($this->profile) {
                if ($this->isEdit && $this->oldProfile) {
                    Storage::disk('public')->delete($this->oldProfile);
                }
                $userData['profile'] = $this->profile->store('profiles', 'public');
            }

            // Save or Update User
            if ($this->isEdit) {
                $user = User::find($this->user_id);
                $user->update($userData);
            } else {
                $user = User::create($userData);
            }

            // Data Student
            $studentData = [
                'user_id' => $user->id,
                'nis' => $this->nis,
                'study_group_id' => $this->study_group_id,
                'status' => $this->status,
                'entry_year' => $this->entry_year,
            ];

            // Save or Update Student
            if ($this->isEdit) {
                Student::find($this->student_id)->update($studentData);
                session()->flash('success', 'Data siswa berhasil diperbarui!');
            } else {
                Student::create($studentData);
                session()->flash('success', 'Data siswa berhasil ditambahkan!');
            }

            DB::commit();
            $this->resetForm();
            $this->dispatch('close-modal');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteConfirm($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $this->student_id = $student->id;
        $this->user_id = $student->user_id;
        $this->name = $student->user->name;
    }

    public function delete()
    {
        try {
            $student = Student::findOrFail($this->student_id);
            $user = User::findOrFail($this->user_id);

            // Delete profile image if exists
            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }

            // Soft delete student and user
            $student->delete();
            $user->delete();

            session()->flash('success', 'Data siswa berhasil dihapus!');
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->student_id = null;
        $this->user_id = null;
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->gender = null;
        $this->profile = null;
        $this->oldProfile = null;

        $this->nis = '';
        $this->study_group_id = null;
        $this->status = 'active';
        $this->entry_year = date('Y');

        $this->resetValidation();
    }

    public function render()
    {
        $query = Student::with(['user', 'studyGroup'])
            ->whereHas('user', function ($q) {
                $q->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%');
                })
                ->when($this->filterGender, function ($query) {
                    $query->where('gender', $this->filterGender);
                });
            })
            ->when($this->search, function ($query) {
                $query->orWhere('nis', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterGrade, function ($query) {
                $query->whereHas('studyGroup', function ($q) {
                    $q->where('grade', $this->filterGrade);
                });
            })
            ->when($this->filterMajor, function ($query) {
                $query->whereHas('studyGroup', function ($q) {
                    $q->where('major', $this->filterMajor);
                });
            })
            ->latest();

        $students = $query->paginate($this->perPage);

        // Statistics
        $allStudents = Student::with('user')->get();
        $stats = [
            'total' => $allStudents->count(),
            'active' => $allStudents->where('status', 'active')->count(),
            'male' => $allStudents->filter(function ($student) {
                return $student->user->gender == 'male';
            })->count(),
            'female' => $allStudents->filter(function ($student) {
                return $student->user->gender == 'female';
            })->count(),
        ];

        return view('livewire.admin.student.index', [
            'students' => $students,
            'stats' => $stats,
        ]);
    }
}