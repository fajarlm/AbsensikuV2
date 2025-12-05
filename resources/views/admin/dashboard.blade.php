@extends('layouts.app')

@section('title', 'Dahsboard')
{{-- @section('menuAdminDashboard', 'active') --}}

@section('content')
    @livewire('admin.dashboard')
@endsection

@push('style')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }
    </style>
@endpush
