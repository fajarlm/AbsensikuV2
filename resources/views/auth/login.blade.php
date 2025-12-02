@extends('layouts.app')

@section('title', 'Login')

@section('content')
    @livewire('auth.login')

@endsection 

@push('script')
    <script>
        input       = document.getElementById("password");
        icon        = document.getElementById("togglePasswordIcon");
        showPassword = false;

        function togglePassword(){

        if (showPassword) {
            input.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
            showPassword = false;
        } else {
            input.type = "password";
            icon.classList.add("bi-eye-slash");
            icon.classList.remove("bi-eye");
            showPassword = true;
        }
    }
    </script>
@endpush
