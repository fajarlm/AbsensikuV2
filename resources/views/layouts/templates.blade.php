<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Absensiku</title>

    <!-- Styles -->
    @include('layouts.style')

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            border-radius: 8px;
            margin: 0 3px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-link:hover {
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: rgb(25, 63, 145) !important;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, blue, rgb(41, 74, 165));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }

        .main-container {
            min-height: calc(100vh - 76px - 60px);
        }

        .footer {
            background: white;
            border-top: 1px solid #e3e6f0;
            padding: 1.5rem 0;
            margin-top: auto;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container-fluid mx-3">
            <a class="navbar-brand d-flex align-items-center"
                href="{{ Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}" wire:navigate>
                <i class="bi bi-mortarboard-fill me-2"></i>
                Absensiku
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @if (Auth::user()->role === 'teacher')
                        <!-- Teacher Navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                                href="{{ route('teacher.dashboard') }}" wire:navigate>
                                <i class="bi bi-speedometer2 me-1"></i>
                                Dashboard
                            </a>
                        </li>
                    @elseif(Auth::user()->role === 'student')
                        <!-- Student Navigation -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                                href="{{ route('student.dashboard') }}" wire:navigate>
                                <i class="bi bi-speedometer2 me-1"></i>
                                Dashboard
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Right Side -->
                <div class="d-flex align-items-center">
                    <!-- Notification -->
                    <div class="dropdown me-3">
                        <button class="btn btn-link text-dark p-0 position-relative" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="notification-badge badge bg-danger rounded-pill"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 300px;">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>
                            @if (Auth::user()->role === 'teacher')
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'student')
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                            </div>
                                            <div class="flex-grow-1 ms-3">

                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center text-primary" href="#">View all</a></li>
                        </ul>
                    </div>

                    <!-- User Profile -->
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <div
                                class="user-avatar me-2 {{ Auth::user()->role === 'teacher' ? 'bg-success' : 'bg-primary' }}">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="d-none d-md-block">
                                <div class="fw-medium">{{ auth()->user()->name }}</div>
                                <small
                                    class="text-muted {{ Auth::user()->role === 'teacher' ? 'text-success' : 'text-primary' }}">
                                    {{ Auth::user()->role === 'teacher' ? 'Teacher' : 'Student' }}
                                </small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Auth::user()->role === 'teacher' ? route('teacher.profile') : route('student.profile') }}" wire:navigate>
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                           
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" class="btn ms-4 btn-danger rounded">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} <strong>Absensiku</strong>. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        {{ Auth::user()->role === 'teacher' ? 'Teacher' : 'Student' }} Dashboard v1.0.0
                        <span class="mx-2">|</span>
                        Last login:
                        {{ auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() : 'Recently' }}
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @include('layouts.script')

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Custom Scripts -->
    @stack('scripts')
</body>

</html>
