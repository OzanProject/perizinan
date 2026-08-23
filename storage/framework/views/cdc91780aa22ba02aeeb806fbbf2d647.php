<?php if(session('success') || session('error') || session('info') || session('warning')): ?>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      <?php if(session('success')): ?>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: "<?php echo e(session('success')); ?>",
          timer: 3000,
          showConfirmButton: false
        });
      <?php endif; ?>

      <?php if(session('error')): ?>
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: "<?php echo e(session('error')); ?>",
        });
      <?php endif; ?>

      <?php if(session('info')): ?>
        Swal.fire({
          icon: 'info',
          title: 'Informasi',
          text: "<?php echo e(session('info')); ?>",
        });
      <?php endif; ?>

      <?php if(session('warning')): ?>
        Swal.fire({
          icon: 'warning',
          title: 'Peringatan',
          text: "<?php echo e(session('warning')); ?>",
        });
      <?php endif; ?>
      });
  </script>
<?php endif; ?><?php /**PATH D:\laragon\www\perizinan\resources\views/partials/sweetalert.blade.php ENDPATH**/ ?>