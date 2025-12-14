<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Trash extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedUsers = [];
    public $selectAll = false;
    public $bulkDisabled = true;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::onlyTrashed()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('role', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('deleted_at')
            ->paginate(10);

        $this->bulkDisabled = count($this->selectedUsers) < 1;

        return view('livewire.admin.user.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        if ($user->student) {
            $user->student()->onlyTrashed()->restore();
        }

        if ($user->teacher) {
            $user->teacher()->onlyTrashed()->restore();
        }


        $this->reset(['selectedUsers', 'selectAll']);
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->student) {
            // Hapus attendance dulu
            $user->student->attendances()->forceDelete();

            // Hapus student
            $user->student()->forceDelete();
        }

        if ($user->teacher) {
            $user->teacher()->forceDelete();
        }

        // Hapus foto
        if ($user->profile) {
            $path = public_path('storage/' . $user->profile);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $user->forceDelete();
    }

    public function restoreSelected()
    {
        $users = User::onlyTrashed()->whereIn('id', $this->selectedUsers)->get();

        foreach ($users as $user) {
            $user->restore();

            if ($user->student) {
                $user->student()->onlyTrashed()->restore();
            }

            if ($user->teacher) {
                $user->teacher()->onlyTrashed()->restore();
            }
        }

        $this->dispatch('notify', type: 'success', message: 'Semua user terpilih berhasil direstore!');
        $this->reset(['selectedUsers', 'selectAll']);
    }


    public function deleteSelected()
    {
        DB::transaction(function () {
            $users = User::onlyTrashed()->whereIn('id', $this->selectedUsers)->get();

            foreach ($users as $user) {
                // Force delete related records
                if ($user->student) {
                    $user->student()->forceDelete();
                }

                if ($user->teacher) {
                    $user->teacher()->forceDelete();
                }

                // Delete profile image
                if ($user->profile) {
                    $path = public_path('storage/' . $user->profile);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $user->forceDelete();
            }
        });

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => count($this->selectedUsers) . ' user berhasil dihapus permanen!'
        ]);

        $this->reset(['selectedUsers', 'selectAll']);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = User::onlyTrashed()
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('username', 'like', '%' . $this->search . '%')
                            ->orWhere('role', 'like', '%' . $this->search . '%');
                    });
                })
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }
}
