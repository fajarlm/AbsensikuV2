<?php

namespace App\Livewire\Admin\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Trash extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedTeachers = [];
    public $selectAll = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Render
    public function render()
    {
        $teachers = Teacher::onlyTrashed()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('username', 'like', '%'.$this->search.'%');
                })
                ->orWhere('nip', 'like', '%'.$this->search.'%');
            })
            ->latest('deleted_at')
            ->paginate(10);

        return view('livewire.admin.teacher.trash', compact('teachers'));
    }

    // Restore Single
    public function restore($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $teacher = Teacher::onlyTrashed()->with('user')->findOrFail($id);
                $teacher->restore();

                if ($teacher->user && $teacher->user->trashed()) {
                    $teacher->user->restore();
                }
            });

            session()->flash('success', 'Guru berhasil dipulihkan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulihkan guru: '.$e->getMessage());
        }
    }

    // Force Delete Single
    public function forceDelete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $teacher = Teacher::onlyTrashed()->with('user')->findOrFail($id);

                // Delete profile image
                if ($teacher->user && $teacher->user->profile) {
                    Storage::disk('public')->delete($teacher->user->profile);
                }

                if ($teacher->user) {
                    $teacher->user->forceDelete();
                }

                $teacher->forceDelete();
            });

            session()->flash('success', 'Guru berhasil dihapus permanen!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus guru: '.$e->getMessage());
        }
    }

    // Restore selected
    public function restoreSelected()
    {
        try {
            DB::transaction(function () {
                $teachers = Teacher::onlyTrashed()->whereIn('id', $this->selectedTeachers)->get();

                foreach ($teachers as $teacher) {
                    $teacher->restore();

                    if ($teacher->user && $teacher->user->trashed()) {
                        $teacher->user->restore();
                    }
                }
            });

            session()->flash('success', count($this->selectedTeachers).' guru berhasil dipulihkan!');
            $this->selectedTeachers = [];
            $this->selectAll = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulihkan: '.$e->getMessage());
        }
    }

    // Delete selected permanently
    public function deleteSelected()
    {
        try {
            DB::transaction(function () {
                $teachers = Teacher::onlyTrashed()->whereIn('id', $this->selectedTeachers)->get();

                foreach ($teachers as $teacher) {
                    if ($teacher->user && $teacher->user->profile) {
                        Storage::disk('public')->delete($teacher->user->profile);
                    }

                    if ($teacher->user) {
                        $teacher->user->forceDelete();
                    }

                    $teacher->forceDelete();
                }
            });

            session()->flash('success', count($this->selectedTeachers).' guru berhasil dihapus permanen!');
            $this->selectedTeachers = [];
            $this->selectAll = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus: '.$e->getMessage());
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTeachers = Teacher::onlyTrashed()->pluck('id')->toArray();
        } else {
            $this->selectedTeachers = [];
        }
    }
}
