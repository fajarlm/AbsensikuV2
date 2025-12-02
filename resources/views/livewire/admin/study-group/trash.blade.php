<div>
    <div class="container-fluid py-4">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-trash text-danger me-2"></i>Trash Kelas
                </h4>
                <p class="text-muted mb-0">Kelas yang telah dihapus</p>
            </div>
            <div>
                <a href="{{ route('admin.study_group.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Search Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                        placeholder="Cari kelas yang dihapus...">
                </div>
            </div>
        </div>

        <!-- Trash Table Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Dihapus Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($studyGroups as $studyGroup)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary bg-opacity-10 text-secondary rounded p-2 me-3">
                                                <i class="bi bi-door-closed fs-5"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $studyGroup->class_number }}</strong>
                                                <small class="text-muted">Nomor Kelas</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary fs-6">
                                            {{ $studyGroup->grade }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $studyGroup->major }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            {{ $studyGroup->deleted_at->format('d M Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="restore({{ $studyGroup->id }})" 
                                            class="btn btn-sm btn-success me-2"
                                            wire:confirm="Pulihkan kelas {{ $studyGroup->grade }} {{ $studyGroup->major }} {{ $studyGroup->class_number }}?">
                                            <i class="bi bi-arrow-clockwise"></i> Pulihkan
                                        </button>
                                        <button wire:click="forceDelete({{ $studyGroup->id }})"
                                            class="btn btn-sm btn-danger"
                                            wire:confirm="Hapus permanen kelas {{ $studyGroup->grade }} {{ $studyGroup->major }} {{ $studyGroup->class_number }}? Tindakan ini tidak dapat dibatalkan!">
                                            <i class="bi bi-trash"></i> Hapus Permanen
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-trash fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data di trash</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Menampilkan {{ $studyGroups->firstItem() ?? 0 }} - {{ $studyGroups->lastItem() ?? 0 }} dari
                        {{ $studyGroups->total() }} data
                    </div>
                    {{ $studyGroups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>