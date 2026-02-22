<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('admin_lembaga.dashboard') }}" class="nav-link">
        @if(request()->routeIs('admin_lembaga.dashboard'))
          Dashboard Lembaga
        @else
          {{ $title ?? 'Panel Lembaga' }}
        @endif
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
        <span class="d-none d-md-inline ml-1 text-uppercase">{{ explode(' ', Auth::user()->name)[0] }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ Auth::user()->email }}</span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('profile.edit') }}" class="dropdown-item">
          <i class="fas fa-user-circle mr-2"></i> Akun Saya
        </a>
        <a href="{{ route('admin_lembaga.profile.index') }}" class="dropdown-item">
          <i class="fas fa-university mr-2"></i> Profil Lembaga
        </a>
        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item dropdown-footer text-danger text-left">
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