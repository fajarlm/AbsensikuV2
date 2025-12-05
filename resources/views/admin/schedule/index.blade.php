@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')
    @livewire('admin.schedule.index')
@endsection

@push('script')
    <script>
    document.addEventListener('closeModal', function (event) {
        let modalId = event.detail.modal;
        let modalEl = document.getElementById(modalId);
        let modal = bootstrap.Modal.getInstance(modalEl);

        if (modal) {
            modal.hide();
        }
    });
</script>

@endpush