<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AbsensiKu - @yield('title','Login')</title>

    @include('layouts.style')
    @livewireStyles()
    @stack('style')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    @if (request()->is('/') || request()->is('/login') || request()->is('forgot-password'))
        @yield('content')
    @else
        <div class="app-wrapper">

            @include('layouts.navbar')

            @include('layouts.sidebar')

            <!-- Main Content -->
            <main class="app-main">
                <!-- Content Header -->
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="mb-0 ms-3 mt-3 fw-semibold">@yield('title')</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="#" class="text-reset">Home</a></li>
                                    <li class="breadcrumb-item"><a href="#" class="text-reset">admin</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="app-content">
                    <div class="container-fluid">
                        @yield('content')
                        {{-- {{ $slot ?? '' }} --}}

                    </div>
                </div>
            </main>

            @include('layouts.footer')
        </div>
    @endif
    <!-- App Wrapper -->
    @stack('script')
    @livewireScripts()
    @include('layouts.script')

</body>

</html>
