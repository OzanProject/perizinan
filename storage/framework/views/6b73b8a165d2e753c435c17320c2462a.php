<aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow-x: hidden !important;">
  <a href="<?php echo e(Auth::user()->hasRole('super_admin') ? route('super_admin.dashboard') : route('admin_lembaga.dashboard')); ?>" class="brand-link d-flex align-items-center pb-3 pt-3">
    
    <?php if(Auth::user()->hasRole('super_admin') && Auth::user()->dinas && Auth::user()->dinas->logo): ?>
      <img src="<?php echo e(Storage::url(Auth::user()->dinas->logo)); ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <?php elseif(Auth::user()->hasRole('admin_lembaga')): ?>
      <i class="fas fa-school brand-image img-circle elevation-3 mt-1 ml-2 text-white bg-primary p-2" style="font-size: 14px;"></i>
    <?php else: ?>
      <i class="fas fa-certificate brand-image img-circle elevation-3 mt-1 ml-2"></i>
    <?php endif; ?>
    
    <span class="brand-text font-weight-bold" style="white-space: normal; font-size: 13px; line-height: 1.3; max-width: 170px;">
      <?php if(Auth::user()->hasRole('super_admin')): ?>
        <?php echo e(Auth::user()->dinas->app_name ?? 'Sistem Izin'); ?>

      <?php else: ?>
        <?php echo e(Auth::user()->lembaga->nama_lembaga ?? 'Sistem Perizinan'); ?>

      <?php endif; ?>
    </span>
  </a>

  <div class="sidebar" style="overflow-x: hidden !important;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <?php if(Auth::user()->photo): ?>
          <img src="<?php echo e(Storage::url(Auth::user()->photo)); ?>" class="img-circle elevation-2" alt="User Image">
        <?php else: ?>
          <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&color=7F9CF5&background=EBF4FF" class="img-circle elevation-2" alt="User Image">
        <?php endif; ?>
      </div>
      
      <div class="info" style="white-space: normal; line-height: 1.2; padding-left: 10px;">
        <a href="#" class="d-block font-weight-bold" style="font-size: 14px;"><?php echo e(Auth::user()->name); ?></a>
      </div>
    </div>

    <nav class="mt-2 pb-5">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MAIN NAVIGATION</li>
        <li class="nav-item">
          <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super_admin')): ?>
            <a href="<?php echo e(route('super_admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.dashboard') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          <?php else: ?>
            <a href="<?php echo e(route('admin_lembaga.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin_lembaga.dashboard') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          <?php endif; ?>
        </li>

        <?php if(Auth::user()->hasRole('super_admin')): ?>
          <li class="nav-header">DATA UTAMA</li>
          <li class="nav-item">
            <a href="<?php echo e(route('super_admin.lembaga.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.lembaga.index') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-university"></i>
              <p>Manajemen Lembaga</p>
            </a>
          </li>

          <li class="nav-header">MODUL PERIZINAN</li>
          
          <?php
            $isPerizinanActive = request()->routeIs('super_admin.jenis_perizinan.*') || 
                                 request()->routeIs('super_admin.perizinan.*') || 
                                 request()->routeIs('super_admin.penerbitan.*');
          ?>

          <li class="nav-item <?php echo e($isPerizinanActive ? 'menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e($isPerizinanActive ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>
                Siklus Perizinan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('super_admin.jenis_perizinan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.jenis_perizinan.*') ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>1. Jenis Perizinan</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo e(route('super_admin.perizinan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.perizinan.*') ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>2. Daftar Pengajuan</p>
                </a>
              </li>

              <li class="nav-item <?php echo e(request()->routeIs('super_admin.penerbitan.*') ? 'menu-open' : ''); ?>">
                <a href="#" class="nav-link <?php echo e(request()->routeIs('super_admin.penerbitan.*') ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>
                    3. Pusat Penerbitan
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" style="padding-left: 15px;">
                  <li class="nav-item">
                    <a href="<?php echo e(route('super_admin.penerbitan.antrian')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.penerbitan.antrian') || request()->routeIs('super_admin.penerbitan.finalisasi') ? 'active' : ''); ?>">
                      <i class="fas fa-hourglass-half nav-icon" style="font-size: 12px;"></i>
                      <p>Antrian Cetak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo e(route('super_admin.penerbitan.pusat_cetak')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.penerbitan.pusat_cetak') ? 'active' : ''); ?>">
                      <i class="fas fa-print nav-icon" style="font-size: 12px;"></i>
                      <p>Pusat Cetak</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?php echo e(route('super_admin.penerbitan.riwayat')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.penerbitan.riwayat') ? 'active' : ''); ?>">
                      <i class="fas fa-history nav-icon" style="font-size: 12px;"></i>
                      <p>Riwayat Penerbitan</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <li class="nav-item border-bottom pb-2 mb-2">
            <a href="<?php echo e(route('super_admin.laporan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.laporan.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-chart-line text-info"></i>
              <p>Laporan & Statistik</p>
            </a>
          </li>

          <li class="nav-header">SYSTEM</li>
          <li class="nav-item">
            <a href="<?php echo e(route('super_admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.users.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('super_admin.landing_page.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.landing_page.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-desktop"></i>
              <p>Landing Page</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('super_admin.settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('super_admin.settings.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>Konfigurasi</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if(Auth::user()->hasRole('admin_lembaga')): ?>
          <li class="nav-header">MENU LEMBAGA</li>
          <li class="nav-item">
            <a href="<?php echo e(route('admin_lembaga.perizinan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin_lembaga.perizinan.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Pengajuan Saya</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('admin_lembaga.profile.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin_lembaga.profile.*') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-school"></i>
              <p>Profil Lembaga</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('profile.edit')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.edit') ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Akun Saya</p>
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item mt-4 border-top pt-2">
          <form action="<?php echo e(route('logout')); ?>" method="POST" id="logout-form" class="d-none"><?php echo csrf_field(); ?></form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p class="font-weight-bold">Keluar</p>
          </a>
        </li>
      </ul>
    </nav>
    </div>
  </aside><?php /**PATH D:\laragon\www\perizinan\resources\views/partials/backend/sidebar.blade.php ENDPATH**/ ?>