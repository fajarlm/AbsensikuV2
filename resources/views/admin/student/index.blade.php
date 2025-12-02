@extends('layouts.app')

@section('title','Data Siswa')


@section('content')
    @livewire('admin.student.index')
@endsection

@push('script')
    <script>
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