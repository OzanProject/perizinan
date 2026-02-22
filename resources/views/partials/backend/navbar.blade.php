<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('super_admin.dashboard') }}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
        <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span
          class="dropdown-item dropdown-header">{{ str_replace('_', ' ', Auth::user()->getRoleNames()->first()) }}</span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('super_admin.settings.index') }}" class="dropdown-item">
          <i class="fas fa-user-cog mr-2"></i> Profil & Pengaturan
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item dropdown-footer text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
          </button>
        </form>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>