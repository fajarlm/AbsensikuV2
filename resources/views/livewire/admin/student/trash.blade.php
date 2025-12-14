<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Trash Data Siswa</h3>

        <a href="{{ route('admin.user.student.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" id="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" id="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Cari nama / username...">
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Dihapus Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $index => $item)
                            <tr>
                                <td>{{ $students->firstItem() + $index }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->user->username ?? '-' }}</td>
                                <td>{{ $item->nis ?? '-' }}</td>
                                <td>
                                    {{ $item->studyGroup->grade ?? '-' }}
                                    {{ $item->studyGroup->major ?? '' }}
                                    {{ $item->studyGroup->class_number ?? '' }}
                                </td>
                                <td>{{ $item->deleted_at->format('d M Y H:i') }}</td>

                                <td>
                                    <button wire:click="restore({{ $item->id }})"
                                        class="btn btn-success btn-sm">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>

                                    <button wire:click="deletePermanent({{ $item->id }})"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus Permanen
                                    </button>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada data di trash.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>

</div>

