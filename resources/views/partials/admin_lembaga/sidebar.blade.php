<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin_lembaga.dashboard') }}" class="brand-link">
    @if(Auth::user()->lembaga && Auth::user()->lembaga->logo)
      <img src="{{ Storage::url(Auth::user()->lembaga->logo) }}" alt="Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
    @else
      <i class="fas fa-school brand-image img-circle elevation-3 mt-1 ml-2"></i>
    @endif
    <span
      class="brand-text font-weight-light text-truncate">{{ Auth::user()->lembaga->nama_lembaga ?? 'Panel Lembaga' }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        @if(Auth::user()->photo)
          <img src="{{ Storage::url(Auth::user()->photo) }}" class="img-circle elevation-2" alt="User Image">
        @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
            class="img-circle elevation-2" alt="User Image">
        @endif
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MENU UTAMA</li>
        <li class="nav-item">
          <a href="{{ route('admin_lembaga.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin_lembaga.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">MANAJEMEN IZIN</li>
        <li class="nav-item">
          <a href="{{ route('admin_lembaga.perizinan.create') }}"
            class="nav-link {{ request()->routeIs('admin_lembaga.perizinan.create') ? 'active' : '' }}">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>Ajukan Izin Baru</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin_lembaga.perizinan.index') }}"
            class="nav-link {{ request()->routeIs('admin_lembaga.perizinan.index') || request()->routeIs('admin_lembaga.perizinan.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-history"></i>
            <p>Status & Riwayat</p>
          </a>
        </li>

        <li class="nav-header">INFORMASI</li>
        <li class="nav-item">
          <a href="{{ route('admin_lembaga.profile.index') }}"
            class="nav-link {{ request()->routeIs('admin_lembaga.profile.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-university"></i>
            <p>Profil Lembaga</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin_lembaga.guide.index') }}"
            class="nav-link {{ request()->routeIs('admin_lembaga.guide.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-question-circle"></i>
            <p>Panduan Sistem</p>
          </a>
        </li>

        <li class="nav-header">PENGATURAN</li>
        <li class="nav-item">
          <a href="{{ url('/profile') }}" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Akun Saya</p>
          </a>
        </li>
        <li class="nav-item mt-2">
          <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">@csrf</form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>