<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('super_admin.dashboard') }}" class="brand-link">
    @if(Auth::user()->dinas && Auth::user()->dinas->logo)
      <img src="{{ Storage::url(Auth::user()->dinas->logo) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    @else
      <i class="fas fa-certificate brand-image img-circle elevation-3 mt-1 ml-2"></i>
    @endif
    <span class="brand-text font-weight-light" style="white-space: normal; font-size: 14px; line-height: 1.3;">{{ Auth::user()->dinas->app_name ?? 'Sistem Izin' }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        @if(Auth::user()->photo)
          <img src="{{ Storage::url(Auth::user()->photo) }}" class="img-circle elevation-2" alt="User Image">
        @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="img-circle elevation-2" alt="User Image">
        @endif
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MAIN NAVIGATION</li>
        <li class="nav-item">
          @role('super_admin')
            <a href="{{ route('super_admin.dashboard') }}" class="nav-link {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          @else
            <a href="{{ route('admin_lembaga.dashboard') }}" class="nav-link {{ request()->routeIs('admin_lembaga.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          @endrole
        </li>

        @if(Auth::user()->hasRole('super_admin'))
          <li class="nav-header">MANAJEMEN DATA</li>
          <li class="nav-item">
            <a href="{{ route('super_admin.lembaga.index') }}" class="nav-link {{ request()->routeIs('super_admin.lembaga.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-university"></i>
              <p>Manajemen Lembaga</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('super_admin.jenis_perizinan.index') }}" class="nav-link {{ request()->routeIs('super_admin.jenis_perizinan.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-list"></i>
              <p>Jenis Perizinan</p>
            </a>
          </li>

          <li class="nav-header">LAYANAN PERIZINAN</li>
          <li class="nav-item">
            <a href="{{ route('super_admin.perizinan.index') }}" class="nav-link {{ request()->routeIs('super_admin.perizinan.index') && request('status') !== 'disetujui' ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Daftar Pengajuan</p>
            </a>
          </li>

          <li class="nav-header">PENERBITAN</li>
          <li class="nav-item">
            <a href="{{ route('super_admin.penerbitan.antrian') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.antrian') || request()->routeIs('super_admin.penerbitan.finalisasi') ? 'active' : '' }}">
              <i class="nav-icon fas fa-hourglass-half"></i>
              <p>Antrian Cetak</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('super_admin.penerbitan.pusat_cetak') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.pusat_cetak') ? 'active' : '' }}">
              <i class="nav-icon fas fa-print"></i>
              <p>Pusat Cetak</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('super_admin.penerbitan.preset.index') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.preset.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-sliders-h"></i>
              <p>Preset & Layout</p>
            </a>
          </li>

          <li class="nav-header">SYSTEM</li>
          <li class="nav-item">
            <a href="{{ route('super_admin.users.index') }}" class="nav-link {{ request()->routeIs('super_admin.users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('super_admin.settings.index') }}" class="nav-link {{ request()->routeIs('super_admin.settings.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>Konfigurasi</p>
            </a>
          </li>
        @endif

        @if(Auth::user()->hasRole('admin_lembaga'))
          <li class="nav-header">MENU LEMBAGA</li>
          <li class="nav-item">
            <a href="{{ route('admin_lembaga.perizinan.index') }}" class="nav-link {{ request()->routeIs('admin_lembaga.perizinan.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Pengajuan Saya</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin_lembaga.profile.index') }}" class="nav-link {{ request()->routeIs('admin_lembaga.profile.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>Profil Lembaga</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Akun Saya</p>
            </a>
          </li>
        @endif

        <li class="nav-item mt-4">
          <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">@csrf</form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger">
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
