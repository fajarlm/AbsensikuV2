<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Jadwal Pelajaran Kelas</h3>
        </div>
        {{-- <a href="{{ route('admin.schedule.manage') }}" class="btn btn-outline-secondary">
            Manage Tabel
        </a> --}}
        <div class="">

            <div class="btn-group dropstart">
                <button type="button" class="btn btn-warning dropdown-toggle " data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-print"></i> Export
                </button>
                <div class="dropdown-menu">
                    <button wire:click="exportExcel" style="font-size: 18px" class=" text-success dropdown-item"><i
                            class="fas fa-file-excel"></i>
                        EXCEL</button>
                 
                 <button wire:click="exportPdf" class=" text-danger dropdown-item">
                     <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    
                    {{-- @if ($selectedGroup)
                        <button wire:click="exportPdf" class="text-danger dropdown-item">
                            <i class="fas fa-print"></i> Cetak Jadwal {{ $selectedGroupName }}
                        </button>
                    @endif --}}
                </div>
            </div>
        </div>

    </div>

    <!-- FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="searchClass" class="form-control"
                        placeholder="Cari kelas...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterGrade" class="form-select">
                        <option value="">Semua Tingkat</option>
                        <option>X</option>
                        <option>XI</option>
                        <option>XII</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterMajor" class="form-select">
                        <option value="">Semua Jurusan</option>
                        <option>PPLG</option>
                        <option>TJKT</option>
                        <option>DKV</option>
                        <option>PMN</option>
                        <option>MPLB</option>
                        <option>KLN</option>
                        <option>HTL</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- List Kelas -->
        <div class="col-lg-3">
            <div class="card shadow-sm sticky-top" style="top:1rem">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pilih Kelas</h5>
                </div>
                <div class="list-group list-group-flush" style="max-height:70vh;overflow-y:auto">
                    @foreach ($groups as $group)
                        <button wire:click="$set('selectedGroup', {{ $group->id }})"
                            class="list-group-item list-group-item-action text-start {{ $selectedGroup == $group->id ? 'active bg-primary-subtle text-primary' : '' }}">
                            <strong>{{ $group->grade }} {{ $group->major }} {{ $group->class_number }}</strong>
                            <small class="d-block text-muted">{{ $group->schedules->count() }} jadwal</small>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="col-lg-9">
            @if ($selectedGroup && $selectedGroupName)
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between">
                        <h4 class="mb-0">Jadwal {{ $selectedGroupName }}</h4>
                        <button wire:click="$set('selectedGroup', null)" class="btn btn-sm btn-light">X</button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timeSlots as $index => $start)
                                    @php $end = $timeSlots[$index + 1] ?? 'Selesai'; @endphp
                                    <tr>
                                        <td class="bg-light fw-bold pt-5">
                                            {{ $start }} - {{ $end }}
                                        </td>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                            @php
                                                $jadwal = $selectedSchedules->first(function ($s) use ($day, $start) {
                                                    $dbTime = is_string($s->start_time)
                                                        ? substr($s->start_time, 0, 5)
                                                        : $s->start_time->format('H:i');
                                                    return $s->day === $day && $dbTime === $start;
                                                });
                                            @endphp

                                            <td class="p-3 text-center align-middle"
                                                style="min-height:80px; cursor:pointer; border: 2px dashed #ddd; transition:all .2s"
                                                wire:click="openScheduleModal({{ $selectedGroup }}, '{{ $day }}', '{{ $start }}', {{ $jadwal?->id ?? 'null' }})"
                                                data-bs-toggle="modal" data-bs-target="#scheduleModal">

                                                @if ($jadwal)
                                                    <div class="bg-light text-black rounded-3 p-3 small shadow-sm ">
                                                        <div class="fw-bold fs-6">{{ $jadwal->subject->code }}</div>
                                                        <div class="small">{{ $jadwal->subject->name }}</div>
                                                        <div class="text-primary-50 xsmall">
                                                            {{ $jadwal->subject->teacher->user->name ?? '—' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-muted">
                                                        <i class="bi bi-plus-circle fs-4 d-block mb-2"></i></i>
                                                        <small>Klik untuk tambah jadwal</small>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <h5>Pilih kelas di sebelah kiri untuk melihat jadwal</h5>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-circle' }}"></i>
                        {{ $isEdit ? 'Edit' : 'Tambah' }} Jadwal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Kelas <span class="text-danger">*</span></label>
                                <select wire:model="study_group_id"
                                    class="form-select @error('study_group_id') is-invalid @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->grade }} {{ $g->major }}
                                            {{ $g->class_number }}</option>
                                    @endforeach
                                </select>
                                @error('study_group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Mata Pelajaran <span class="text-danger">*</span></label>
                                <select wire:model="subject_id"
                                    class="form-select @error('subject_id') is-invalid @enderror">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($subjects as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}
                                            ({{ $sub->teacher->user->name ?? '?' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Hari <span class="text-danger">*</span></label>
                                <select wire:model="day" class="form-select @error('day') is-invalid @enderror">
                                    <option value="">Pilih Hari</option>
                                    @foreach ($days as $d)
                                        <option>{{ $d }}</option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Jam Mulai <span class="text-danger">*</span></label>
                                <select wire:model="start_time"
                                    class="form-select @error('start_time') is-invalid @enderror">
                                    <option value="">Pilih Jam Mulai</option>
                                    @foreach ($timeSlots as $ts)
                                        <option value="{{ $ts }}">{{ $ts }}</option>
                                    @endforeach
                                </select>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Jam Selesai <span class="text-danger">*</span></label>
                                <select wire:model="end_time"
                                    class="form-select @error('end_time') is-invalid @enderror">
                                    <option value="">Pilih Jam Selesai</option>

                                    @foreach ($timeSlots as $index => $ts)
                                        @if (isset($timeSlots[$index + 1]))
                                            @php $endTs = $timeSlots[$index + 1]; @endphp
                                            @if ($start_time === $ts)
                                                <option value="{{ $endTs }}">{{ $endTs }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="resetForm">Batal</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
