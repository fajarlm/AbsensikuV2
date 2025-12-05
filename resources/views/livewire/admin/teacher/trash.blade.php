<div>
    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-trash-alt text-danger mr-2"></i> Trash Guru
                </h1>
                <p class="mb-0">Daftar guru yang sudah dihapus</p>
            </div>
            <a href="{{ route('admin.user.teacher.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Search & Bulk Action -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">

                    <!-- Search -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control"
                                placeholder="Cari guru..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>

                    <!-- Bulk actions -->
                    <div class="col-md-6 d-flex justify-content-end">
                        @if (count($selectedTeachers) > 0)
                            <button class="btn btn-success mr-2"
                                wire:click="restoreSelected"
                                wire:confirm="Pulihkan {{ count($selectedTeachers) }} guru?">
                                <i class="fas fa-undo mr-2"></i>Pulihkan Terpilih
                            </button>

                            <button class="btn btn-danger"
                                wire:click="deleteSelected"
                                wire:confirm="Hapus permanen {{ count($selectedTeachers) }} guru?">
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

                @if ($teachers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" wire:model="selectAll">
                                    </th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>NIP</th>
                                    <th>Status</th>
                                    <th>Dihapus Pada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($teachers as $teacher)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                value="{{ $teacher->id }}"
                                                wire:model="selectedTeachers">
                                        </td>

                                        <td>{{ $teacher->user->name ?? '-' }}</td>
                                        <td>{{ $teacher->user->username ?? '-' }}</td>
                                        <td>{{ $teacher->nip }}</td>
                                        <td>{{ $teacher->status }}</td>
                                        <td>{{ $teacher->deleted_at->format('d M Y H:i') }}</td>

                                        <td>
                                            <button class="btn btn-sm btn-success"
                                                wire:click="restore({{ $teacher->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger"
                                                wire:click="forceDelete({{ $teacher->id }})"
                                                wire:confirm="Hapus permanen guru ini?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $teachers->links() }}
                @else
                    <p class="text-center py-3 mb-0">Tidak ada data.</p>
                @endif

            </div>
        </div>
    </div>
</div>
