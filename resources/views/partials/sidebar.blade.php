<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a wire:navigate href="/admin/dashboard" class="brand-link ">
        <i class="bi-calendar-week ps-3"></i> 
        <span class="brand-text font-weight-light ms-5"> Absensiku</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a wire:navigate href="/admin/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>    
                </li>

                <li class="nav-header">Admin</li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/user" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            User
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/teacher" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Guru
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/student" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Murid
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/subject" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Mata Pelajaran
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/study-group" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Rombongan Belajar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/schedule" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Jadwal Pelajaran
                        </p>
                    </a>
                </li>
                <li class="nav-header">Guru</li>
                <li class="nav-item">
                    <a wire:navigate href="/admin/attendance" class="nav-link">
                        <i class="nav-icon fas fa-"></i>
                        <p>
                            Absensi
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
