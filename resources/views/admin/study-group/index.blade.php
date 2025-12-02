@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
    @livewire('admin.studyGroup.index')
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // Open modal
            Livewire.on('open-modal', (modalId) => {
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
            });

            // Close modal
            Livewire.on('close-modal', (modalId) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                modal.hide();
            });

            // Notify
            Livewire.on('notify', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: event.type,
                    title: event.message
                });
            });
        });
    </script>
@endpush