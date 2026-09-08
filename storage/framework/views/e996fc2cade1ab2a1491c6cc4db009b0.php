

<?php $__env->startSection('title', 'Edit Lembaga'); ?>
<?php $__env->startSection('breadcrumb', 'Edit Lembaga'); ?>

<?php $__env->startSection('content'); ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mb-3">
        <a href="<?php echo e(route('super_admin.lembaga.index')); ?>" class="btn btn-default btn-sm shadow-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
      </div>

      <div class="col-lg-8">
        <div class="card card-outline card-warning shadow-sm">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-edit mr-2 text-warning"></i> Perbarui Data Lembaga
            </h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="<?php echo e(route('super_admin.lembaga.update', $lembaga->id)); ?>" method="POST"
            enctype="multipart/form-data" id="main-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nama_lembaga">Nama Lembaga <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lembaga" id="nama_lembaga"
                      value="<?php echo e(old('nama_lembaga', strtoupper($lembaga->nama_lembaga))); ?>"
                      class="form-control <?php $__errorArgs = ['nama_lembaga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      placeholder="Contoh: PKBM HARAPAN BANGSA" style="text-transform: uppercase;"
                      oninput="this.value = this.value.toUpperCase();" required>
                    <?php $__errorArgs = ['nama_lembaga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <span class="error invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="npsn">NPSN <span class="text-danger">*</span></label>
                    <input type="text" name="npsn" id="npsn" value="<?php echo e(old('npsn', $lembaga->npsn)); ?>"
                      class="form-control <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="8 digit NPSN" required>
                    <?php $__errorArgs = ['npsn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <span class="error invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="jenjang">Jenjang Pendidikan <span class="text-danger">*</span></label>
                    <select name="jenjang" id="jenjang" class="form-control <?php $__errorArgs = ['jenjang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                      <option value="" disabled>-- Pilih Jenjang --</option>
                      <?php $__currentLoopData = ['TK', 'SD', 'SMP', 'SMA', 'SMK', 'PKBM', 'LKP']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j); ?>" <?php echo e(old('jenjang', $lembaga->jenjang) == $j ? 'selected' : ''); ?>><?php echo e($j); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['jenjang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <span class="error invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                    <input type="text" name="kecamatan" id="kecamatan" value="<?php echo e(old('kecamatan', $lembaga->kecamatan)); ?>"
                      class="form-control <?php $__errorArgs = ['kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: GARUT KOTA" required>
                    <?php $__errorArgs = ['kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <span class="error invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" id="alamat" rows="4" class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  placeholder="Jl. Raya No. 123..." required><?php echo e(old('alamat', $lembaga->alamat)); ?></textarea>
                <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <span class="error invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer bg-light text-right">
              <a href="<?php echo e(route('super_admin.lembaga.index')); ?>" class="btn btn-default mr-2">Batal</a>
              <button type="submit" class="btn btn-warning px-4 shadow-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card card-outline card-info shadow-sm">
          <div class="card-header text-center">
            <h3 class="card-title w-100 font-weight-bold">Logo Lembaga</h3>
          </div>
          <div class="card-body text-center">
            <div id="logo-preview" class="border rounded mx-auto mb-3 d-flex align-items-center justify-content-center"
              style="width: 150px; height: 150px; background-color: #f8f9fa; overflow: hidden;">
              <?php if($lembaga->logo): ?>
                <img src="<?php echo e(asset('storage/' . $lembaga->logo)); ?>" class="img-fluid"
                  style="width: 100%; height: 100%; object-fit: cover;">
              <?php else: ?>
                <i class="fas fa-image fa-3x text-muted"></i>
              <?php endif; ?>
            </div>
            <p class="text-muted small mb-3">Pilih file baru untuk mengganti logo</p>
            <input type="file" name="logo" id="logo-input" form="main-form" class="d-none" accept="image/*">
            <button type="button" onclick="document.getElementById('logo-input').click()"
              class="btn btn-outline-info btn-block">
              <i class="fas fa-sync mr-1"></i> Ganti Foto
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php $__env->startPush('scripts'); ?>
    <script>
      document.getElementById('logo-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
          const preview = document.getElementById('logo-preview');
          preview.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full" style="width: 100%; height: 100%; object-fit: cover;">`;
        }
      };
    </script>
  <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\perizinan\resources\views/backend/super_admin/lembaga/edit.blade.php ENDPATH**/ ?>