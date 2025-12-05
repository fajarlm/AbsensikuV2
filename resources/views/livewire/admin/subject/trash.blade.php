<div>
    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="fas fa-trash-alt text-danger mr-2"></i> Trash Mata Pelajaran</h3>
                <p class="text-muted mb-0">Daftar matpel yang sudah dihapus</p>
            </div>
            <a href="{{ route('admin.subject.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Search & Bulk Actions -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari matpel..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">
                        @if (count($selectedSubjects) > 0)
                            <button class="btn btn-success mr-2"
                                wire:click="restoreSelected"
                                wire:confirm="Pulihkan {{ count($selectedSubjects) }} matpel?">
                                <i class="fas fa-undo mr-2"></i>Pulihkan Terpilih
                            </button>

                            <button class="btn btn-danger"
                                wire:click="deleteSelected"
                                wire:confirm="Hapus permanen {{ count($selectedSubjects) }} matpel?">
                                <i class="fas fa-trash mr-2"></i>Hapus Permanen
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow">
            <div class="card-body">

                @if ($subjects->count() > 0)
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" wire:model="selectAll">
                                    </th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Guru Pengampu</th>
                                    <th>Dihapus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                value="{{ $subject->id }}"
                                                wire:model="selectedSubjects">
                                        </td>

                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->code }}</td>
                                        <td>
                                            {{ $subject->teacher->user->name ?? '-' }}
                                        </td>
                                        <td>{{ $subject->deleted_at->format('d M Y H:i') }}</td>

                                        <td>
                                            <button class="btn btn-sm btn-success"
                                                wire:click="restore({{ $subject->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger"
                                                wire:click="forceDelete({{ $subject->id }})"
                                                wire:confirm="Hapus matpel ini permanen?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    {{ $subjects->links() }}

                @else
                    <p class="text-center py-3">Tidak ada data dalam trash.</p>
                @endif

            </div>
        </div>

    </div>
</div>
