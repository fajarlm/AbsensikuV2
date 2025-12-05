<?php

namespace App\Livewire\Admin\Student;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Trash extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $student_id;
    public $user_id;
    public $name;

    public function updatingSearch()
    {
        $this->resetPage();
    }

   public function restore($id)
{
    try {
        DB::transaction(function () use ($id) {
            $student = Student::onlyTrashed()->findOrFail($id);
            $user = User::onlyTrashed()->findOrFail($student->user_id);

            $student->restore();
            $user->restore();
        });

        session()->flash('success', 'Data siswa berhasil dikembalikan!');
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function deletePermanent($id)
{
    try {
        DB::transaction(function () use ($id) {

            $student = Student::onlyTrashed()->with('user')->findOrFail($id);
            $user = User::onlyTrashed()->findOrFail($student->user_id);

            // Hapus attendance dulu kalau ada relationnya
            if (method_exists($student, 'attendances')) {
                $student->attendances()->forceDelete();
            }

            // Hapus file user
            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }

            $student->forceDelete();
            $user->forceDelete();
        });

        session()->flash('success', 'Data siswa berhasil dihapus permanen!');
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function render()
    {
        $students = Student::onlyTrashed()
            ->with(['user' => fn ($q) => $q->onlyTrashed()])
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($u) {
                    $u->where('name', 'like', "%{$this->search}%")
                      ->orWhere('username', 'like', "%{$this->search}%");
                });
            })
            ->paginate($this->perPage);

        return view('livewire.admin.student.trash', [
            'students' => $students
        ]);
    }
}
