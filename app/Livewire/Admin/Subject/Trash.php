<?php

namespace App\Livewire\Admin\Subject;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;

class Trash extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedSubjects = [];
    public $selectAll = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $subjects = Subject::onlyTrashed()
            ->with(['teacher.user'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%")
                    ->orWhereHas('teacher.user', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->latest('deleted_at')
            ->paginate(10);

        return view('livewire.admin.subject.trash', compact('subjects'));
    }

    // Restore single
    public function restore($id)
    {
        try {
            $subject = Subject::onlyTrashed()->findOrFail($id);
            $subject->restore();

            session()->flash('success', "Mata pelajaran berhasil dipulihkan!");
        } catch (\Exception $e) {
            session()->flash('error', "Gagal memulihkan: " . $e->getMessage());
        }
    }

    // Force delete single
    public function forceDelete($id)
    {
        try {
            $subject = Subject::onlyTrashed()->findOrFail($id);
            $subject->forceDelete();

            session()->flash('success', "Mata pelajaran dihapus permanen!");
        } catch (\Exception $e) {
            session()->flash('error', "Gagal menghapus: " . $e->getMessage());
        }
    }

    // Restore selected
    public function restoreSelected()
    {
        try {
            $subjects = Subject::onlyTrashed()->whereIn('id', $this->selectedSubjects)->get();

            foreach ($subjects as $subject) {
                $subject->restore();
            }

            session()->flash('success', count($this->selectedSubjects) . " mata pelajaran dipulihkan!");
            $this->selectedSubjects = [];
            $this->selectAll = false;
        } catch (\Exception $e) {
            session()->flash('error', "Gagal memulihkan: " . $e->getMessage());
        }
    }

    // Force delete selected
    public function deleteSelected()
    {
        try {
            $subjects = Subject::onlyTrashed()->whereIn('id', $this->selectedSubjects)->get();

            foreach ($subjects as $subject) {
                $subject->forceDelete();
            }

            session()->flash('success', count($this->selectedSubjects) . " mata pelajaran dihapus permanen!");
            $this->selectedSubjects = [];
            $this->selectAll = false;
        } catch (\Exception $e) {
            session()->flash('error', "Gagal menghapus: " . $e->getMessage());
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedSubjects = Subject::onlyTrashed()->pluck('id')->toArray();
        } else {
            $this->selectedSubjects = [];
        }
    }
}
