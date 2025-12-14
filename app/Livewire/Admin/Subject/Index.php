<?php

namespace App\Livewire\Admin\Subject;

use App\Exports\SubjectExport;
use App\Models\Subject;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;

    // Filter
    public $search = '';
    public $filterTeacher = '';

    // Form Fields
    public $subject_id;
    public $teacher_id;
    public $name;
    public $code;
    public $description;

    // Modal State
    public $isEdit = false;

    // Reset pagination when filter changes
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterTeacher()
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
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'name' => 'required|string',
            'code' => [
                'required',
                // 'regex:/^[A-Z]{3}-[0-9]{3}$/',
            ],

            'description' => 'nullable|string',
        ];
    }

    protected $messages = [
        'teacher_id.required' => 'Guru pengampu harus dipilih',
        'teacher_id.exists' => 'Guru tidak valid',
        'name.required' => 'Nama mata pelajaran harus diisi',
        'code.required' => 'Kode mata pelajaran harus diisi',
        'code.regex' => 'Format kode mata pelajaran tidak valid',
        'code.unique' => 'Kode mata pelajaran sudah digunakan',
    ];

    // Reset Form
    public function resetForm()
    {
        $this->reset([  
            'subject_id',
            'teacher_id',
            'name',
            'code',
            'description',
            'isEdit'
        ]);
        $this->resetValidation();
    }

    // Create
    public function create()
    {
        $this->resetForm();
        $this->dispatch('openModal');
    }

    // Edit
    public function edit($id)
    {
        $this->resetForm();
        $subject = Subject::findOrFail($id);

        $this->subject_id = $subject->id;
        $this->teacher_id = $subject->teacher_id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->description = $subject->description;

        $this->isEdit = true;
        $this->dispatch('openModal');
    }

    // Save
    public function save()
    {
        $this->validate();

            $data = [
                'teacher_id' => $this->teacher_id,
                'name' => $this->name,
                'code' => strtoupper($this->code), // Uppercase kode
                'description' => $this->description,
            ];

            if ($this->isEdit) {
                Subject::where('id', $this->subject_id)->update($data);
                session()->flash('success', 'Mata pelajaran berhasil diperbarui!');
            } else {
                Subject::create($data);
                session()->flash('success', 'Mata pelajaran berhasil ditambahkan!');
            }

            $this->resetForm();
            $this->dispatch('closeModal');
    }

    // Delete (Soft Delete)
    public function deleteConfirm($id)
    {
        $subject = Subject::findOrFail($id);
        $this->subject_id = $subject->id;
        $this->name = $subject->name;

        $this->dispatch('openDeleteModal');
    }

    public function delete()
    {
            $subject = Subject::findOrFail($this->subject_id);
            $subject->delete(); // Soft delete

            session()->flash('success', 'Mata pelajaran berhasil dihapus!');
            $this->resetForm();
    }

    // Get Statistics
    public function getStatsProperty()
    {
        return [
            'total' => Subject::count(),
            'totalTeachers' => Subject::distinct('teacher_id')->count('teacher_id'),
        ];
    }

    // Get Teachers for Dropdown
    public function getTeachersProperty()
    {
        return Teacher::with('user')
            ->whereHas('user')
            ->where('status', 'active')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->user->name . ' (' . $teacher->nip . ')',
                ];
            });
    }

     public function exportPdf(){
        $subject = Subject::all();
        view()->share('subject',$subject);
        $pdf = Pdf::loadView('admin.subject.print_pdf',$subject);
        $fileName = 'data-subject'.\Carbon\Carbon::now()->timestamp . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportExcel(){
        return Excel::download(new SubjectExport, 'data-Mata-pelajaran.xlsx');
    }

    public function render()
    {
        $subjects = Subject::with(['teacher.user'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('teacher.user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterTeacher, function ($query) {
                $query->where('teacher_id', $this->filterTeacher);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.subject.index', [
            'subjects' => $subjects,
            'stats' => $this->stats,
            'teachers' => $this->teachers,
        ]);
    }
}
