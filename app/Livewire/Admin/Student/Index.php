<?php
// app/Livewire/Admin/Student/Index.php

namespace App\Livewire\Admin\Student;

use App\Exports\StudentExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Student;
use App\Models\StudyGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterGender = '';
    public $filterGrade = '';
    public $filterMajor = '';
    public $perPage = 10;

    public $student_id;
    public $user_id;
    public $name;
    public $username;
    public $password;
    public $password_confirmation;
    public $gender;
    public $profile;
    public $oldProfile;
    public $nisn;
    
    public $nis;
    public $study_group_id;
    public $isEdit = false;

    // buat drpdown
    public $studyGroups = [];

    public function mount()
    {
        $this->studyGroups = StudyGroup::orderBy('grade')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();
    }



    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
            ],
            'gender' => 'required|in:male,female',
            'profile' => 'nullable|image|max:2048',
            'nis' => [
                'required',
                'string',
                'max:50',
            ],
            'nisn' => 'required|string|max:50',
            'study_group_id' => 'required|exists:study_groups,id',
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
        'nisn.required' => 'NISN wajib diisi',
        'profile.image' => 'File harus berupa gambar',
        'profile.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function updatingSearch()
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

        $this->isEdit = true;
    }

    public function exportPdf()
    {
        $Student = Student::all();
        view()->share('Student', $Student);
        $pdf = Pdf::loadView('admin.user.print_pdf', $Student);
        $fileName = 'data-Siswa' . \Carbon\Carbon::now()->timestamp . '.pdf';
        return $pdf->download($fileName);
    }


    public function exportExcel()
    {
        return Excel::download(new StudentExport, 'data-Student.xlsx');
    }

    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'role' => 'student',
            'gender' => $this->gender,
        ];

        if (!$this->isEdit) {
            $userData['password'] = Hash::make($this->password);
        } elseif ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->profile) {
            if ($this->isEdit && $this->oldProfile) {
                Storage::disk('public')->delete($this->oldProfile);
            }
            $userData['profile'] = $this->profile->store('profiles', 'public');
        }

        if ($this->isEdit) {
            $user = User::find($this->user_id);
            $user->update($userData);
        } else {
            $user = User::create($userData);
        }

        $studentData = [
            'user_id' => $user->id,
            'nis' => $this->nis,
            'study_group_id' => $this->study_group_id,
            'verification_code' => encrypt($this->nis),
            'nisn' => $this->nisn,
            'first_log' => 0
        ];

        if ($this->isEdit) {
            Student::find($this->student_id)->update($studentData);
            session()->flash('success', 'Data siswa berhasil diperbarui!');
        } else {
            Student::create($studentData);
            session()->flash('success', 'Data siswa berhasil ditambahkan!');
        }

        $this->resetForm();
        // $this->dispatch('closeModal');
        $this->dispatch('closeModal');

    }

   

    public function delete()
    {
        $student = Student::findOrFail($this->student_id);
        $user = User::findOrFail($this->user_id);

        if ($user->profile) {
            Storage::disk('public')->delete($user->profile);
        }

        $student->delete();
        $user->delete();

        session()->flash('success', 'Data siswa berhasil dihapus!');
        $this->resetForm();
    }

     public function deleteConfirm($id)
    {
        $student = Student::findOrFail($id);
        $this->student_id = $student->id;
        $this->user_id = $student->user_id;
        $this->name = $student->user->name;
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

        $this->resetValidation();
    }

  public function render()
{
    $students = Student::with(['user', 'studyGroup'])
        ->whereHas('user', function ($q) {
            $q->where('role', 'student') // cuma user role student
              ->when($this->search, function ($query) {
                  $query->where(function ($q2) {
                      $q2->where('name', 'like', '%'.$this->search.'%')
                         ->orWhere('username', 'like', '%'.$this->search.'%');
                  });
              })
              ->when($this->filterGender, fn($query) => 
                    $query->where('gender', $this->filterGender)
              );
        })
        ->when($this->search, fn($q) =>
            $q->where('nis', 'like', '%'.$this->search.'%')
        )
        ->when($this->filterGrade, function ($q) {
            $q->whereHas('studyGroup', fn($sg) =>
                $sg->where('grade', $this->filterGrade)
            );
        })
        ->when($this->filterMajor, function ($q) {
            $q->whereHas('studyGroup', fn($sg) =>
                $sg->where('major', $this->filterMajor)
            );
        })
        ->paginate($this->perPage);
        // dd($students);
    $stats = [
        'total'  => Student::count(),
        'male'   => Student::whereHas('user', fn($q) => $q->where('gender', 'male'))->count(),
        'female' => Student::whereHas('user', fn($q) => $q->where('gender', 'female'))->count(),
    ];

    return view('livewire.admin.student.index', [
        'students' => $students,
        'stats' => $stats,
        'StudyGroups' => StudyGroup::all(),
    ]);
}
}
