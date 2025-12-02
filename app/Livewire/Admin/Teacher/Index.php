<?php

namespace App\Livewire\Admin\Teacher;

use App\Models\User;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;

    // Filter
    public $search = '';
    public $filterGender = '';
    public $filterStatus = '';

    // Form Fields - User
    public $teacher_id;
    public $user_id;
    public $name;
    public $username;
    public $password;
    public $password_confirmation;
    public $gender;
    public $profile;
    public $oldProfile;

    // Form Fields - Teacher Specific
    public $nip;
    public $status = 'active'; // Default aktif

    // Modal State
    public $isEdit = false;

    // Reset pagination when filter changes
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterGender() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

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

        try {
            DB::beginTransaction();

            // Data User
            $userData = [
                'name' => $this->name,
                'username' => $this->username,
                'gender' => $this->gender,
                'role' => 'teacher',
            ];

            if ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            // Handle profile photo
            if ($this->profile) {
                if ($this->oldProfile) {
                    Storage::disk('public')->delete($this->oldProfile);
                }
                $userData['profile'] = $this->profile->store('profiles', 'public');
            }

            // Teacher Data
            $teacherData = [
                'nip' => $this->nip,
                'status' => $this->status,
            ];

            if ($this->isEdit) {
                // Update User
                $user = User::findOrFail($this->user_id);
                $user->update($userData);

                // Update Teacher
                Teacher::where('id', $this->teacher_id)->update($teacherData);

                session()->flash('success', 'Data guru berhasil diperbarui!');
            } else {
                // Create User
                $user = User::create($userData);

                // Create Teacher
                $teacherData['user_id'] = $user->id;
                Teacher::create($teacherData);

                session()->flash('success', 'Data guru berhasil ditambahkan!');
            }

            DB::commit();
            $this->resetForm();
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        try {
            DB::beginTransaction();

            $teacher = Teacher::with('user')->findOrFail($this->teacher_id);

            // Soft delete teacher (SoftDeletes trait)
            $teacher->delete();

            // Optional: Soft delete user juga (kalau User model punya SoftDeletes)
            // $teacher->user->delete();

            DB::commit();
            session()->flash('success', 'Data guru berhasil dihapus!');
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Restore (kalau mau ada fitur restore)
    public function restore($id)
    {
        try {
            $teacher = Teacher::withTrashed()->findOrFail($id);
            $teacher->restore();
            
            session()->flash('success', 'Data guru berhasil dipulihkan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Force Delete (hapus permanen)
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

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
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Get Statistics
    public function getStatsProperty()
    {
        return [
            'total' => Teacher::count(),
            'aktif' => Teacher::where('status', 'active')->count(),
            'nonaktif' => Teacher::where('status', 'inactive')->count(),
            'male' => Teacher::whereHas('user', function($q) {
                $q->where('gender', 'male');
            })->count(),
            'female' => Teacher::whereHas('user', function($q) {
                $q->where('gender', 'female');
            })->count(),
        ];
    }

    public function render()
    {
        $teachers = Teacher::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%');
                })
                ->orWhere('nip', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterGender, function ($query) {
                $query->whereHas('user', function($q) {
                    $q->where('gender', $this->filterGender);
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.teacher.index', [
            'teachers' => $teachers,
            'stats' => $this->stats,
        ]);
    }
}