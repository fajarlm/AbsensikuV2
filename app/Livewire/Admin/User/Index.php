<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use App\Exports\UserExport;
use App\Models\Student;
use App\Models\Teacher;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Filter properties
    public $search = '';
    public $filterRole = '';
    public $filterGender = '';
    public $perPage = 10;

    // Form properties
    public $user_id;
    public $name;
    public $username;
    public $password;
    public $password_confirmation;
    public $role = 'student';
    public $gender;
    public $profile;
    public $old_profile;

    // Modal state
    public $isEdit = false;

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
            'role' => 'required|in:admin,teacher,student',
            'gender' => 'required|in:male,female',
            'profile' => 'nullable|image|max:2048',
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
        'role.required' => 'Role wajib dipilih',
        'gender.required' => 'Jenis kelamin wajib dipilih',
        'profile.image' => 'File harus berupa gambar',
        'profile.max' => 'Ukuran gambar maksimal 2MB',
    ];

    // Reset page saat filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterGender()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->dispatch('open-modal', modal: 'userModal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->gender = $user->gender;
        $this->old_profile = $user->profile;

        // Reset password fields saat edit
        $this->password = '';
        $this->password_confirmation = '';

        $this->isEdit = true;
        $this->dispatch('open-modal', modal: 'userModal');
    }

    // public function save()
    // {
    //     $this->validate();

    //     $data = [
    //         'name' => $this->name,
    //         'username' => $this->username,
    //         'role' => $this->role,
    //         'gender' => $this->gender,
    //     ];

    //     if (!$this->isEdit && $this->password) {
    //         $data['password'] = Hash::make($this->password);
    //     } elseif ($this->isEdit && $this->password) {
    //         $data['password'] = Hash::make($this->password);
    //     }

    //     if ($this->profile) {
    //         if ($this->isEdit && $this->old_profile) {
    //             Storage::disk('public')->delete($this->old_profile);
    //         }
    //         $data['profile'] = $this->profile->store('profiles', 'public');
    //     }

    //     if ($this->isEdit) {
    //         $user = User::findOrFail($this->user_id);
    //         $user->update($data);
    //         session()->flash('success', 'User berhasil diperbarui!');
    //     } else {
    //         $userCreate = User::create($data);
    //         if ($data['role'] == 'student') {
    //             Student::create([
    //                 'user_id' => $userCreate->id,
    //                 'nis' => NULL,
    //                 'nisn' => NULL,
    //                 'study_group_id' => NULL,
    //                 'verification_code' => NULL,
    //             ]);
    //         } else {
    //             Teacher::create([
    //                 'user_id' => $userCreate->id,
    //                 'nip' => NULL,
    //                 'status' => NULL,
    //             ]);
    //         }
    //         session()->flash('success', 'User berhasil ditambahkan!');
    //     }

    //     $this->resetForm();
    //     $this->dispatch('close-modal', modal: 'userModal');
    // }

    public function confirmDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->dispatch('open-modal', modal: 'deleteModal');
    }

    public function delete()
    {
        $user = User::findOrFail($this->user_id);

        // Soft delete user (akan otomatis soft delete related data jika ada cascade)
        $user->delete();

        //session adalah sesi (php bult in)
        // flash mirip seperti with tetapi lebih coock untuk mengirim mesaage
        session()->flash('success', 'User berhasil dihapus dan dipindahkan ke sampah!');
        $this->resetForm();
        $this->dispatch('close-modal', modal: 'deleteModal');
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->gender = null;
        $this->profile = null;
        $this->old_profile = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function exportPdf()
    {
        $query = User::with(['student.studyGroup', 'teacher.subjects']);

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        $users = $query->latest()->get()->toArray();
        $role = $this->filterRole ?: null;

        $pdf = Pdf::loadView('admin.user.print_pdf', [
            'users' => $users,
            'role' => $role
        ])->setPaper('a4', 'landscape');

        $fileName = 'data-users-' . ($role ?: 'all') . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function exportExcel()
    {
        return Excel::download(new UserExport, 'data-users.xlsx');
    }

    public function render()
    {
        // Query dengan filter (hanya data yang tidak dihapus)
        $query = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterRole, function ($q) {
                $q->where('role', $this->filterRole);
            })  
            ->when($this->filterGender, function ($q) {
                $q->where('gender', $this->filterGender);
            })
            ->latest();

        $users = $query->paginate($this->perPage);

        // Hitung statistik dari semua user aktif (tidak termasuk yang dihapus)
        $allUsers = User::all();

        return view('livewire.admin.user.index', [
            'users' => $users,
            'userCount' => $allUsers->count(),
            'studentCount' => $allUsers->where('role', 'student')->count(),
            'teacherCount' => $allUsers->where('role', 'teacher')->count(),
            'adminCount' => $allUsers->where('role', 'admin')->count(),
        ]);
    }
}
