<?php

namespace App\Livewire\Admin\StudyGroup;

use App\Models\StudyGroup;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $search = '';
    public $filterGrade = '';
    public $filterMajor = '';

    // Form
    public $study_group_id;
    public $grade;
    public $major;
    public $class_number;
    public $isEdit = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterGrade() { $this->resetPage(); }
    public function updatingFilterMajor() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    protected function rules()
    {
        return [
            'grade'        => 'required|in:X,XI,XII',
            'major'        => 'required|in:PPLG,TJKT,DKV,PMN,MPLB,KLN,HTL',
            'class_number' => 'required|in:1,2,3,4,5,6',
        ];
    }

    protected $messages = [
        'grade.required'   => 'Tingkat kelas wajib diisi',
        'major.required'   => 'Jurusan wajib diisi',
        'class_number.required' => 'Nomor kelas wajib diisi',
    ];

    public function resetForm()
    {
        $this->reset([
            'study_group_id', 'grade', 'major', 'class_number', 'isEdit'
        ]);
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('openModal');
    }

    public function edit($id)
    {
        $this->resetForm();
        $group = StudyGroup::findOrFail($id);

        $this->study_group_id = $group->id;
        $this->grade         = $group->grade;
        $this->major         = $group->major;
        $this->class_number  = $group->class_number;
        $this->isEdit        = true;

        $this->dispatch('openModal');
    }

   public function save()
{
    $this->validate();

    try {
        if ($this->isEdit) {
            // Cek apakah kombinasi baru sudah ada (kecuali data ini sendiri)
            $exists = StudyGroup::where('grade', $this->grade)
                ->where('major', $this->major)
                ->where('class_number', $this->class_number)
                ->where('id', '!=', $this->study_group_id)
                ->exists();

            if ($exists) {
                $this->addError('grade', 'Kelas sudah ada! Kombinasi tingkat, jurusan, dan nomor kelas harus unik.');
                return;
            }

            StudyGroup::where('id', $this->study_group_id)->update([
                'grade'        => $this->grade,
                'major'        => $this->major,
                'class_number' => $this->class_number,
            ]);

            session()->flash('success', 'Kelas berhasil diperbarui!');
        } else {
            // Saat tambah baru → langsung create, kalau duplikat akan error karena unique di DB
            StudyGroup::create([
                'grade'        => $this->grade,
                'major'        => $this->major,
                'class_number' => $this->class_number,
            ]);

            session()->flash('success', 'Kelas berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->dispatch('closeModal');

    } catch (\Illuminate\Database\QueryException $e) {
        // Error 23000 = unique constraint violation
        if (str_contains($e->getMessage(), 'study_groups_unique')) {
            session()->flash('error', 'Gagal! Kelas dengan kombinasi tersebut sudah ada.');
        } else {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function deleteConfirm($id)
    {
        $group = StudyGroup::findOrFail($id);
        $this->study_group_id = $group->id;
        $this->dispatch('openDeleteModal');
    }

    public function delete()
    {
        StudyGroup::findOrFail($this->study_group_id)->delete();
        session()->flash('success', 'Kelas berhasil dihapus (soft delete)');
    }

    public function getStatsProperty()
    {
        return [
            'total'       => StudyGroup::count(),
            'totalGrades' => StudyGroup::distinct('grade')->count('grade'),
        ];
    }

    public function render()
    {
        $groups = StudyGroup::query()
            ->when($this->search, function ($q) {
                $q->where('grade', 'like', "%{$this->search}%")
                  ->orWhere('major', 'like', "%{$this->search}%")
                  ->orWhere('class_number', 'like', "%{$this->search}%");
            })
            ->when($this->filterGrade, fn($q) => $q->where('grade', $this->filterGrade))
            ->when($this->filterMajor, fn($q) => $q->where('major', $this->filterMajor))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.study-group.index', [
            'groups' => $groups,
            'stats'  => $this->stats,
        ]);
    }
}