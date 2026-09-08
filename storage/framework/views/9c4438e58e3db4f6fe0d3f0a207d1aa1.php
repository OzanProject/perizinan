<style>
  /* Hilangkan batas tinggi dan paksa teks turun */
  .main-sidebar .brand-link {
    height: auto !important;
    padding: 12px 10px !important;
    white-space: normal !important;
    display: flex !important;
    align-items: center !important;
  }

  .main-sidebar .brand-text {
    white-space: normal !important;
    line-height: 1.3 !important;
    font-size: 14px !important;
    max-width: 170px;
  }

  .main-sidebar .user-panel {
    white-space: normal !important;
    height: auto !important;
    align-items: center !important;
    padding-bottom: 12px !important;
  }

  .main-sidebar .user-panel .info {
    white-space: normal !important;
    line-height: 1.2 !important;
    padding-left: 10px !important;
  }

  /* Anti scrollbar bawah */
  aside.main-sidebar,
  .main-sidebar .sidebar {
    overflow-x: hidden !important;
  }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?php echo e(route('admin_lembaga.dashboard')); ?>" class="brand-link border-bottom border-secondary">
    <?php if(Auth::user()->lembaga && Auth::user()->lembaga->logo): ?>
      <img src="<?php echo e(Storage::url(Auth::user()->lembaga->logo)); ?>" alt="Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
    <?php else: ?>
      <i class="fas fa-school brand-image img-circle elevation-3 mt-1 ml-2 text-white bg-primary p-2"
        style="font-size: 14px; width: 33px; height: 33px; display: flex; align-items: center; justify-content: center;"></i>
    <?php endif; ?>

    <span class="brand-text font-weight-bold">
      <?php echo e(Auth::user()->lembaga->nama_lembaga ?? 'Panel Lembaga'); ?>

    </span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex border-bottom border-secondary">
      <div class="image">
        <?php if(Auth::user()->photo): ?>
          <img src="<?php echo e(Storage::url(Auth::user()->photo)); ?>" class="img-circle elevation-2" alt="User Image">
        <?php else: ?>
          <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&color=7F9CF5&background=EBF4FF"
            class="img-circle elevation-2" alt="User Image">
        <?php endif; ?>
      </div>
      <div class="info">
        <a href="#" class="d-block font-weight-bold" style="font-size: 14px;"><?php echo e(Auth::user()->name); ?></a>
      </div>
    </div>

    <nav class="mt-2 pb-5">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header font-weight-bold">MENU UTAMA</li>
        <li class="nav-item">
          <a href="<?php echo e(route('admin_lembaga.dashboard')); ?>"
            class="nav-link <?php echo e(request()->routeIs('admin_lembaga.dashboard') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header font-weight-bold">MANAJEMEN IZIN</li>
        <li class="nav-item">
          <a href="<?php echo e(route('admin_lembaga.perizinan.create')); ?>"
            class="nav-link <?php echo e(request()->routeIs('admin_lembaga.perizinan.create') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-plus-circle text-success"></i>
            <p>Ajuan Pembaruan Izin</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo e(route('admin_lembaga.perizinan.index')); ?>"
            class="nav-link <?php echo e(request()->routeIs('admin_lembaga.perizinan.index') || request()->routeIs('admin_lembaga.perizinan.show') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-history text-info"></i>
            <p>Status & Riwayat</p>
          </a>
        </li>

        <li class="nav-header font-weight-bold">INFORMASI</li>
        <li class="nav-item">
          <a href="<?php echo e(route('admin_lembaga.profile.index')); ?>"
            class="nav-link <?php echo e(request()->routeIs('admin_lembaga.profile.*') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-university text-warning"></i>
            <p>Profil Lembaga</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo e(route('admin_lembaga.guide.index')); ?>"
            class="nav-link <?php echo e(request()->routeIs('admin_lembaga.guide.*') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-question-circle text-primary"></i>
            <p>Panduan Sistem</p>
          </a>
        </li>

        <li class="nav-header font-weight-bold">PENGATURAN</li>
        <li class="nav-item border-bottom pb-2 mb-2">
          <a href="<?php echo e(url('/profile')); ?>" class="nav-link <?php echo e(request()->is('profile*') ? 'active' : ''); ?>">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Akun Saya</p>
          </a>
        </li>

        <li class="nav-item mt-3">
          <form action="<?php echo e(route('logout')); ?>" method="POST" id="logout-form" class="d-none"><?php echo csrf_field(); ?></form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="nav-link text-white bg-danger" style="border-radius: 5px;">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p class="font-weight-bold">Keluar Sistem</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside><?php /**PATH D:\laragon\www\perizinan\resources\views/partials/admin_lembaga/sidebar.blade.php ENDPATH**/ ?>