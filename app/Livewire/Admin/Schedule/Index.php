<?php

namespace App\Livewire\Admin\Schedule;

use App\Exports\ScheduleExport;
use App\Models\Schedule;
use App\Models\StudyGroup;
use App\Models\Subject;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    public $selectedGroup = null;
    public $selectedClassName = ''; // Tambahan untuk display nama kelas

    public $filterGrade = '';
    public $filterMajor = '';
    public $searchClass = '';

    public $schedule_id;
    public $study_group_id;
    public $subject_id;
    public $day;
    public $daySelected;
    public $start_time;
    public $end_time;
    public $isEdit = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterDay()
    {
        $this->resetPage();
    }
    public function updatingFilterGroup()
    {
        $this->resetPage();
    }
    public function updatingFilterSubject()
    {
        $this->resetPage();
    }

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
        $this->reset(['schedule_id', 'study_group_id', 'subject_id', 'day', 'start_time', 'end_time', 'isEdit', 'selectedClassName']);
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

        // Set nama kelas untuk display
        $group = StudyGroup::find($schedule->study_group_id);
        if ($group) {
            $this->selectedClassName = "{$group->grade} {$group->major} {$group->class_number}";
        }

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


        if ($this->isEdit) {
            Schedule::where('id', $this->schedule_id)->update($data);
            session()->flash('success', 'Jadwal berhasil diperbarui!');
        } else {
            Schedule::create($data);
            session()->flash('success', 'Jadwal berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->dispatch('closeModal', modal: 'scheduleModal');
    }

    public function updatedStartTime($value)
    {
        $index = array_search($value, $this->timeSlots);

        if ($index !== false && isset($this->timeSlots[$index + 1])) {
            $this->end_time = $this->timeSlots[$index + 1];
        } else {
            $this->end_time = null;
        }
    }

    public function openScheduleModal($groupId, $day, $start, $scheduleId = null)
    {
        $this->resetForm();

        $this->study_group_id = $groupId;
        $this->day            = $day;
        $this->start_time     = $start;
        
        // Set nama kelas untuk display
        $group = StudyGroup::find($groupId);
        if ($group) {
            $this->selectedClassName = "{$group->grade} {$group->major} {$group->class_number}";
        }
        
        // Set end time
        $index = array_search($start, $this->timeSlots);
        $this->end_time = $this->timeSlots[$index + 1] ?? null;

        if ($scheduleId) {
            $this->isEdit = true;
            $this->schedule_id = $scheduleId;

            $schedule = Schedule::find($scheduleId);
            $this->subject_id = $schedule->subject_id;
        }

        $this->dispatch('openModal');
    }

    public $timeSlots = [
        '07:00',
        '07:45',
        '08:30',
        '09:15',
        '10:00',
        '10:45',
        '11:30',
        '13:00',
        '13:45',
        '14:30'
    ];

    public function exportPdf()
    {
        $group = StudyGroup::with(['schedules.subject.teacher.user'])->get();
        $data = [
            'groups' => $group,
            'timeSlots' => $this->timeSlots,
        ];
        $pdf = Pdf::loadView('admin.schedule.print_pdf', $data)->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'jadwal_Pelajaran.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ScheduleExport, 'jadwal.xlsx');
    }

    public function render()
    {
        $groups = StudyGroup::with(['schedules.subject.teacher.user'])
            ->when($this->filterGrade, fn($q) => $q->where('grade', $this->filterGrade))
            ->when($this->filterMajor, fn($q) => $q->where('major', $this->filterMajor))
            ->when($this->searchClass, fn($q) => $q->where('grade', 'like', "%{$this->searchClass}%")
                ->orWhere('major', 'like', "%{$this->searchClass}%")
                ->orWhere('class_number', 'like', "%{$this->searchClass}%"))
            ->orderBy('grade')->orderBy('major')->orderBy('class_number')
            ->get();

        $selectedSchedules = collect();
        $selectedGroupName = null;

        if ($this->selectedGroup) {
            $group = $groups->where('id', $this->selectedGroup)->first();
            if ($group) {
                $selectedGroupName = "{$group->grade} {$group->major} {$group->class_number}";
                $selectedSchedules = $group->schedules;
            }
        }

        return view('livewire.admin.schedule.index', [
            'allGroups' => StudyGroup::orderBy('grade')->orderBy('major')->get(),
            'groups'    => $groups,
            'selectedGroup' => $this->selectedGroup,
            'selectedGroupName' => $selectedGroupName,
            'selectedSchedules' => $selectedSchedules,
            'subjects'  => Subject::with('teacher.user')->get(),
            'days'      => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'timeSlots' => $this->timeSlots,
        ]);
    }
}