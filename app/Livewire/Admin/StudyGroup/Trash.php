<?php

namespace App\Livewire\Admin\StudyGroup;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudyGroup;

class Trash extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $selectedGroups = [];
    public $selectAll = false;

    public function render()
    {
        $studyGroups = StudyGroup::onlyTrashed()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('grade', 'like', '%' . $this->search . '%')
                      ->orWhere('major', 'like', '%' . $this->search . '%')
                      ->orWhere('class_number', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('deleted_at')
            ->paginate(10);

        return view('livewire.admin.study-group.trash' ,['studyGroups' => $studyGroups]);
    }

    public function restore($id)
    {
        try {
            $studyGroup = StudyGroup::onlyTrashed()->findOrFail($id);
            $studyGroup->restore();

            session()->flash('success', 'Kelas berhasil dipulihkan!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memulihkan kelas: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $studyGroup = StudyGroup::onlyTrashed()->findOrFail($id);
            $studyGroup->forceDelete();

            session()->flash('success', 'Kelas berhasil dihapus permanen!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }
}