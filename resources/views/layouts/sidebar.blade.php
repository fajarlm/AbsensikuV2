<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link" wire:navigate>
            <i class="bi bi-calendar-week brand-image opacity-75"></i>
            <span class="brand-text fw-light">ABSENSIKU</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!-- Sidebar Menu -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" 
                aria-label="Main navigation" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Admin Section -->
                <li class="nav-header">ADMIN</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.user.index') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>User</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.teacher.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.user.teacher.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-person-video3"></i>
                        <p>Guru</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.student.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.user.student.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>Murid</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.subject.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.subject.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-book"></i>
                        <p>Mata Pelajaran</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.study_group.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.study_group.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-people"></i>
                        <p>Rombongan Belajar</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.schedule.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-calendar3"></i>    
                        <p>Jadwal Pelajaran</p>
                    </a>
                </li>

                <!-- Guru Section -->
                <li class="nav-header">GURU</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.attendance.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}" wire:navigate>
                        <i class="nav-icon bi bi-check2-square"></i>
                        <p>Absensi</p>
                    </a>
                </li>

              
            </ul>
        </nav>
    </div>
</aside>