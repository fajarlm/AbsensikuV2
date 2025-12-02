@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')

@section('content')
    @livewire('admin.subject.index')
@endsection

@push('script')
<script>
    window.addEventListener('closeModal', event => {
        bootstrap.Modal.getInstance(document.getElementById('subjectModal')).hide();
    });

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush