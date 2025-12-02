<nav class="app-header navbar navbar-expand bg-body navbar-light">
    <div class="container-fluid">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto">
            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile ? asset('adminLTE/dist/img/user2-160x160.jpg') : asset('profile.jpeg') }}"
                        class="user-image rounded-circle shadow" alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User image -->
                    <li class="user-header bg-light">
                          <img src="{{ Auth::user()->profile ? asset('adminLTE/dist/img/user2-160x160.jpg') : asset('profile.jpeg') }}"
                            class="rounded-circle shadow" alt="User Image">
                        <p class="mt-2">
                            {{ Auth::user()->name }}
                        </p>
                        <small class="badge bg-success">Admin</small>
                    </li>
                    
                    <!-- Menu Footer -->
                    <li class="user-footer d-flex justify-content-center">
                      <form action="{{ route('logout') }}" method="POST" > 
                           
                        <button type="submit" class="btn btn-danger rounded">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                      </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>