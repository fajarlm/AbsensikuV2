<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-trash-alt text-danger mr-2"></i> Trash User
                </h1>
                <p class="mb-0">User yang telah dihapus</p>
            </div>
            <div>
                <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Search and Bulk Actions -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Cari user yang dihapus..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            @if (count($selectedUsers) > 0)
                                <button class="btn btn-success" wire:click="restoreSelected"
                                    wire:confirm="Pulihkan {{ count($selectedUsers) }} user?">
                                    <i class="fas fa-undo mr-2"></i>Pulihkan Terpilih
                                </button>
                                <button class="btn btn-danger" wire:click="deleteSelected"
                                    wire:confirm="Hapus permanen {{ count($selectedUsers) }} user? Tindakan ini tidak dapat dibatalkan!">
                                    <i class="fas fa-trash mr-2"></i>Hapus Permanen
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card shadow">
            <div class="card-body">
                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                                    </th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Gender</th>
                                    <th>Dihapus Pada</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="@if (in_array($user->id, $selectedUsers)) table-active @endif">
                                        <td>
                                            <input type="checkbox" wire:model.live="selectedUsers"
                                                value="{{ $user->id }}" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($user->profile)
                                                    <img src="{{ asset('storage/' . $user->profile) }}"
                                                        alt="{{ $user->name }}" class="rounded-circle mr-3"
                                                        width="40" height="40" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-3"
                                                        style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    <div class="text-muted small">
                                                        @if ($user->student)
                                                            <span class="badge rounded-pill bg-info">Siswa</span>
                                                        @elseif($user->teacher)
                                                            <span class="badge rounded-pill bg-warning">Guru</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                                @if ($user->role == 'admin') bg-danger
                                                @elseif($user->role == 'teacher') bg-warning
                                                @else bg-primary @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($user->gender == 'male')
                                                <span class="badge rounded-pill bg-primary">Laki-laki </span>
                                            @else
                                                <span class="badge rounded-pill bg-danger">Perempuan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-danger">
                                                {{ $user->deleted_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="restore({{ $user->id }})"
                                                    wire:confirm="Pulihkan user {{ $user->name }}?" title="Pulihkan">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="forceDelete({{ $user->id }})"
                                                    wire:confirm="Hapus permanen user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan!"
                                                    title="Hapus Permanen">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari
                            {{ $users->total() }} user
                            @if (count($selectedUsers) > 0)
                                | <span class="text-primary">{{ count($selectedUsers) }} terpilih</span>
                            @endif
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-trash-alt fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Tidak ada data di trash</h5>
                        <p class="text-muted">Semua user telah dipulihkan atau belum ada yang dihapus</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Empty All Trash Button -->
        @if ($users->count() > 0)
            <div class="mt-4 text-center">
                <button class="btn btn-outline-danger" wire:click="deleteSelected"
                    wire:confirm="Hapus permanen SEMUA user di trash ({{ $users->total() }} user)? Tindakan ini tidak dapat dibatalkan!">
                    <i class="fas fa-broom mr-2"></i>Kosongkan Trash
                </button>
                <p class="text-muted small mt-2">
                    <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                    Aksi ini akan menghapus permanen semua user yang ada di trash
                </p>
            </div>
        @endif
    </div>

    <!-- Toast Notification Script -->
    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('notify', (event) => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: event.type,
                        title: event.message
                    });
                });
            });
        </script>
    @endpush
</div>
