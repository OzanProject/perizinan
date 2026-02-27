<aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow-x: hidden !important;">
  <a href="{{ Auth::user()->hasRole('super_admin') ? route('super_admin.dashboard') : route('admin_lembaga.dashboard') }}" class="brand-link d-flex align-items-center pb-3 pt-3">
    
    @if(Auth::user()->hasRole('super_admin') && Auth::user()->dinas && Auth::user()->dinas->logo)
      <img src="{{ Storage::url(Auth::user()->dinas->logo) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    @elseif(Auth::user()->hasRole('admin_lembaga'))
      <i class="fas fa-school brand-image img-circle elevation-3 mt-1 ml-2 text-white bg-primary p-2" style="font-size: 14px;"></i>
    @else
      <i class="fas fa-certificate brand-image img-circle elevation-3 mt-1 ml-2"></i>
    @endif
    
    <span class="brand-text font-weight-bold" style="white-space: normal; font-size: 13px; line-height: 1.3; max-width: 170px;">
      @if(Auth::user()->hasRole('super_admin'))
        {{ Auth::user()->dinas->app_name ?? 'Sistem Izin' }}
      @else
        {{ Auth::user()->lembaga->nama_lembaga ?? 'Sistem Perizinan' }}
      @endif
    </span>
  </a>

  <div class="sidebar" style="overflow-x: hidden !important;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        @if(Auth::user()->photo)
          <img src="{{ Storage::url(Auth::user()->photo) }}" class="img-circle elevation-2" alt="User Image">
        @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="img-circle elevation-2" alt="User Image">
        @endif
      </div>
      
      <div class="info" style="white-space: normal; line-height: 1.2; padding-left: 10px;">
        <a href="#" class="d-block font-weight-bold" style="font-size: 14px;">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <nav class="mt-2 pb-5">
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
          <li class="nav-header">DATA UTAMA</li>
          <li class="nav-item">
            <a href="{{ route('super_admin.lembaga.index') }}" class="nav-link {{ request()->routeIs('super_admin.lembaga.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-university"></i>
              <p>Manajemen Lembaga</p>
            </a>
          </li>

          <li class="nav-header">MODUL PERIZINAN</li>
          
          @php
            $isPerizinanActive = request()->routeIs('super_admin.jenis_perizinan.*') || 
                                 request()->routeIs('super_admin.perizinan.*') || 
                                 request()->routeIs('super_admin.penerbitan.*');
          @endphp

          <li class="nav-item {{ $isPerizinanActive ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $isPerizinanActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>
                Siklus Perizinan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('super_admin.jenis_perizinan.index') }}" class="nav-link {{ request()->routeIs('super_admin.jenis_perizinan.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>1. Jenis Perizinan</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('super_admin.perizinan.index') }}" class="nav-link {{ request()->routeIs('super_admin.perizinan.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>2. Daftar Pengajuan</p>
                </a>
              </li>

              <li class="nav-item {{ request()->routeIs('super_admin.penerbitan.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('super_admin.penerbitan.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>
                    3. Pusat Penerbitan
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" style="padding-left: 15px;">
                  <li class="nav-item">
                    <a href="{{ route('super_admin.penerbitan.antrian') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.antrian') || request()->routeIs('super_admin.penerbitan.finalisasi') ? 'active' : '' }}">
                      <i class="fas fa-hourglass-half nav-icon" style="font-size: 12px;"></i>
                      <p>Antrian Cetak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('super_admin.penerbitan.pusat_cetak') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.pusat_cetak') ? 'active' : '' }}">
                      <i class="fas fa-print nav-icon" style="font-size: 12px;"></i>
                      <p>Pusat Cetak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('super_admin.penerbitan.preset.index') }}" class="nav-link {{ request()->routeIs('super_admin.penerbitan.preset.*') ? 'active' : '' }}">
                      <i class="fas fa-sliders-h nav-icon" style="font-size: 12px;"></i>
                      <p>Preset & Layout</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <li class="nav-item border-bottom pb-2 mb-2">
            <a href="{{ route('super_admin.laporan.index') }}" class="nav-link {{ request()->routeIs('super_admin.laporan.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line text-info"></i>
              <p>Laporan & Statistik</p>
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

        <li class="nav-item mt-4 border-top pt-2">
          <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">@csrf</form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p class="font-weight-bold">Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
    </div>
  </aside>