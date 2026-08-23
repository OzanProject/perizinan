<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(Auth::user()->dinas->app_name ?? 'Sistem Perizinan'); ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon"
    href="<?php echo e(Auth::user()->dinas->logo ? asset('storage/' . Auth::user()->dinas->logo) : asset('favicon.ico')); ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte3/adminlte3/plugins/fontawesome-free/css/all.min.css')); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('adminlte3/adminlte3/dist/css/adminlte.min.css')); ?>">
  <!-- Material Symbols -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <style>
    .material-symbols-outlined {
      vertical-align: middle;
      font-size: 20px;
    }
  </style>

  <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php echo $__env->make('partials.backend.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php echo $__env->make('partials.backend.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?php echo $__env->yieldContent('title'); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?php echo e(route('super_admin.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active"><?php echo $__env->yieldContent('breadcrumb', 'Page'); ?></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <?php echo $__env->yieldContent('content'); ?>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php echo $__env->make('partials.backend.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="<?php echo e(asset('adminlte3/adminlte3/plugins/jquery/jquery.min.js')); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo e(asset('adminlte3/adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo e(asset('adminlte3/adminlte3/dist/js/adminlte.min.js')); ?>"></script>

  <?php echo $__env->make('partials.sweetalert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH D:\laragon\www\perizinan\resources\views/layouts/backend.blade.php ENDPATH**/ ?>