 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset('adminLTE/dist/img/user2-160x160.jpg')}}" class="user-image img-circle elevation-2" alt="User Image">
          {{-- <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> --}}
          <span class="d-none d-md-inline">alex</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-light">
            <img src="{{ asset('adminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">

            <p>
              name
            </p>
            <small class="badge badge-success">Admin</small>
          </li>
         
          <!-- Menu Footer-->
          <li class="user-footer d-flex justify-content-center">
            <a href="#" class="btn btn-danger rounded btn-flat "><i class="fas fa-sign-out-alt"></i> Logout </a>
          </li>
        </ul>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->