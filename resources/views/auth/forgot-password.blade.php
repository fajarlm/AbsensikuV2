@extends('layouts.app')

@section('title', 'forgot-password')

@section('content')
    @livewire('auth.ForgotPassword')
{{-- <livewire:Auth.ForgotPassword /> --}}
@include('layouts.footer')
@endsection

