<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Schedule;
use App\Models\StudyGroup;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $perPage = 15;
    public $search = '';
    public $filterDay = '';
    public $filterGroup = '';
    public $filterSubject = '';

    // Form
    public $schedule_id;
    public $study_group_id;
    public $subject_id;
    public $day;
    public $start_time;
    public $end_time;
    public $isEdit = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterDay() { $this->resetPage(); }
    public function updatingFilterGroup() { $this->resetPage(); }
    public function updatingFilterSubject() { $this->resetPage(); }

    protected function rules()
    {
        return [
            'study_group_id' => 'required|exists:study_groups,id',
            'subject_id'     => 'required|exists:subjects,id',
            'day'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
        ];
    }

    protected $messages = [
        'study_group_id.required' => 'Kelas wajib dipilih',
        'subject_id.required'     => 'Mata pelajaran wajib dipilih',
        'day.required'            => 'Hari wajib diisi',
        'start_time.required'     => 'Jam mulai wajib diisi',
        'end_time.required'       => 'Jam selesai wajib diisi',
        'end_time.after'          => 'Jam selesai harus setelah jam mulai',
    ];

    public function resetForm()
    {
        $this->reset(['schedule_id', 'study_group_id', 'subject_id', 'day', 'start_time', 'end_time', 'isEdit']);
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
        $schedule = Schedule::findOrFail($id);

        $this->schedule_id    = $schedule->id;
        $this->study_group_id = $schedule->study_group_id;
        $this->subject_id     = $schedule->subject_id;
        $this->day            = $schedule->day;
        $this->start_time     = $schedule->start_time->format('H:i');
        $this->end_time       = $schedule->end_time->format('H:i');
        $this->isEdit         = true;

        $this->dispatch('openModal');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'study_group_id' => $this->study_group_id,
            'subject_id'     => $this->subject_id,
            'day'            => $this->day,
            'start_time'     => $this->start_time,
            'end_time'       => $this->end_time,
        ];

        try {
            if ($this->isEdit) {
                Schedule::where('id', $this->schedule_id)->update($data);
                session()->flash('success', 'Jadwal berhasil diperbarui!');
            } else {
                Schedule::create($data);
                session()->flash('success', 'Jadwal berhasil ditambahkan!');
            }

            $this->resetForm();
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function deleteConfirm($id)
    {
        $this->schedule_id = Schedule::findOrFail($id)->id;
        $this->dispatch('openDeleteModal');
    }

    public function delete()
    {
        Schedule::findOrFail($this->schedule_id)->delete();
        session()->flash('success', 'Jadwal berhasil dihapus (soft delete)');
    }

    public function getStatsProperty()
    {
        return [
            'total' => Schedule::count(),
            'days'  => Schedule::distinct('day')->count('day'),
        ];
    }

    public function render()
    {
        $schedules = Schedule::with(['studyGroup', 'subject.teacher.user'])
            ->when($this->search, fn($q) => $q->whereHas('subject', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
                ->orWhereHas('studyGroup', fn($sq) => $sq->where('grade', 'like', "%{$this->search}%")))
            ->when($this->filterDay, fn($q) => $q->where('day', $this->filterDay))
            ->when($this->filterGroup, fn($q) => $q->where('study_group_id', $this->filterGroup))
            ->when($this->filterSubject, fn($q) => $q->where('subject_id', $this->filterSubject))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.schedule.manage', [
            'schedules' => $schedules,
            'stats'     => $this->stats,
            'groups'    => StudyGroup::orderBy('grade')->orderBy('major')->get(),
            'subjects'  => Subject::with('teacher.user')->get(),
            'days'      => ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'],
        ]);
    }
}