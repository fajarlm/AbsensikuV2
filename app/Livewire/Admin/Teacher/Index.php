<?php

namespace App\Livewire\Admin\Teacher;

use App\Models\User;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Exports\TeacherExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;

    public $search = '';
    public $filterGender = '';
    public $filterStatus = '';


    public $teacher_id;
    public $user_id;
    public $name;
    public $username;
    public $password;
    public $password_confirmation;
    public $gender;
    public $profile;
    public $oldProfile;

    public $nip;
    public $status = 'active';

    public $isEdit = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterGender()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // Validation Rules
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user_id,
            'gender' => 'required',
            'profile' => 'nullable|image|max:2048',

            // Teacher specific
            'nip' => 'required|string|unique:teachers,nip,' . $this->teacher_id,
            'status' => 'required',
        ];

        if ($this->isEdit) {
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama harus diisi',
        'username.required' => 'Username harus diisi',
        'username.unique' => 'Username sudah digunakan',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'gender.required' => 'Jenis kelamin harus dipilih',
        'nip.required' => 'NIP harus diisi',
        'nip.unique' => 'NIP sudah terdaftar',
        'status.required' => 'Status harus dipilih',
    ];

    // Reset Form
    public function resetForm()
    {
        $this->reset([
            'teacher_id',
            'user_id',
            'name',
            'username',
            'password',
            'password_confirmation',
            'gender',
            'profile',
            'oldProfile',
            'nip',
            'status',
            'isEdit'
        ]);
        $this->resetValidation();
    }

    // Create
    public function create()
    {
        $this->resetForm();
        $this->status = 'aktif'; // Set default
        $this->dispatch('openModal');
    }

    // Edit
    public function edit($id)
    {
        $this->resetForm();
        $teacher = Teacher::with('user')->findOrFail($id);

        $this->teacher_id = $teacher->id;
        $this->user_id = $teacher->user_id;
        $this->name = $teacher->user->name;
        $this->username = $teacher->user->username;
        $this->gender = $teacher->user->gender;
        $this->oldProfile = $teacher->user->profile;

        $this->nip = $teacher->nip;
        $this->status = $teacher->status;

        $this->isEdit = true;
        $this->dispatch('openModal');
    }

    // Save
    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'gender' => $this->gender,
            'role' => 'teacher',
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->profile) {
            if ($this->oldProfile) {
                Storage::disk('public')->delete($this->oldProfile);
            }
            $userData['profile'] = $this->profile->store('profiles', 'public');
        }

        $teacherData = [
            'nip' => $this->nip,
            // 'status' => $this->status,
        ];

        if ($this->isEdit) {
            $user = User::findOrFail($this->user_id);
            $user->update($userData);

            Teacher::where('id', $this->teacher_id)->update($teacherData);

            session()->flash('success', 'Data guru berhasil diperbarui!');
        } else {
            $user = User::create($userData);

            $teacherData['user_id'] = $user->id;
            Teacher::create($teacherData);

            session()->flash('success', 'Data guru berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->dispatch('closeModal');
    }

    // Delete (Soft Delete)
    public function deleteConfirm($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $this->teacher_id = $teacher->id;
        $this->user_id = $teacher->user_id;
        $this->name = $teacher->user->name;

        $this->dispatch('openDeleteModal');
    }

    public function delete()
    {
        $teacher = Teacher::with('user')->findOrFail($this->teacher_id);
        $teacher->delete();

        session()->flash('success', 'Data guru berhasil dihapus!');
        $this->resetForm();
    }

    public function exportPdf()
    {
        $query = Teacher::with('user');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            })->orWhere('nip', 'like', '%' . $this->search . '%');
        }

        if ($this->filterGender) {
            $query->whereHas('user', function ($q) {
                $q->where('gender', $this->filterGender);
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $teachers = $query->latest()->get()->toArray();

        $pdf = Pdf::loadView('admin.teacher.print_pdf', [
            'teachers' => $teachers
        ])->setPaper('a4', 'portrait');

        $fileName = 'data-guru-' . now()->format('Y-m-d-His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }
    public function exportExcel()
    {
        return Excel::download(new TeacherExport, 'data-users.xlsx');
    }

    // Restore (kalau mau ada fitur restore)
    public function restore($id)
    {
        $teacher = Teacher::withTrashed()->findOrFail($id);
        $teacher->restore();

        session()->flash('success', 'Data guru berhasil dipulihkan!');
    }

    // Force Delete (hapus permanen)
    public function forceDelete($id)
    {

        $teacher = Teacher::withTrashed()->with('user')->findOrFail($id);

        // Delete profile photo
        if ($teacher->user->profile) {
            Storage::disk('public')->delete($teacher->user->profile);
        }

        // Force delete
        $teacher->forceDelete();
        $teacher->user->delete(); // atau forceDelete() kalau user juga soft delete

        DB::commit();
        session()->flash('success', 'Data guru berhasil dihapus permanen!');
    }

    // Get Statistics
    public function getStatsProperty()
    {
        return [
            'total' => Teacher::count(),
            'aktif' => Teacher::where('status', 'active')->count(),
            'nonaktif' => Teacher::where('status', 'inactive')->count(),
            'male' => Teacher::whereHas('user', function ($q) {
                $q->where('gender', 'male');
            })->count(),
            'female' => Teacher::whereHas('user', function ($q) {
                $q->where('gender', 'female');
            })->count(),
        ];
    }

    public function render()
    {
        $teachers = Teacher::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('nip', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterGender, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('gender', $this->filterGender);
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate($this->perPage);
        // dd($teachers);
        return view('livewire.admin.teacher.index', [
            'teachers' => $teachers,
            'stats' => $this->stats,
        ]);
    }
}
