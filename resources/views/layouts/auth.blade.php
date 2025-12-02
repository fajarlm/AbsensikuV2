<!-- resources/views/layouts/auth.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AbsensiKu - {{ $title ?? 'Login' }}</title>

    @livewireStyles
    @include('layouts.style')
    @stack('style')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    {{ $slot }}

    @livewireScripts
    @stack('script')
    @include('layouts.script')
</body>

</html>