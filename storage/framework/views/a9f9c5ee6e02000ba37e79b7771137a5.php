

<?php $__env->startSection('title', 'Manajemen User'); ?>
<?php $__env->startSection('breadcrumb', 'User'); ?>

<?php $__env->startSection('content'); ?>
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h1 class="h3 font-weight-bold text-dark mb-0">Manajemen User</h1>
        <button onclick="openModal('add')" class="btn btn-primary shadow-sm font-weight-bold">
          <i class="fas fa-user-plus mr-1"></i> Tambah User Baru
        </button>
      </div>
    </div>

    <!-- Filters Card -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
      <div class="card-body">
        <form action="<?php echo e(route('super_admin.users.index')); ?>" method="GET">
          <div class="row align-items-end">
            <div class="col-md-3 form-group mb-md-0">
              <label class="small font-weight-bold text-muted text-uppercase">Cari User</label>
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control border-left-0"
                  placeholder="Nama, Email, atau NIP...">
              </div>
            </div>
            <div class="col-md-3 form-group mb-md-0">
              <label class="small font-weight-bold text-muted text-uppercase">Filter Role</label>
              <select name="role" class="form-control form-control-sm">
                <option value="">Semua Role</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($role->name); ?>" <?php echo e(request('role') == $role->name ? 'selected' : ''); ?>>
                    <?php echo e(strtoupper(str_replace('_', ' ', $role->name))); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-md-3 form-group mb-md-0">
              <label class="small font-weight-bold text-muted text-uppercase">Status Akun</label>
              <select name="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="Aktif" <?php echo e(request('status') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                <option value="Non-Aktif" <?php echo e(request('status') == 'Non-Aktif' ? 'selected' : ''); ?>>Non-Aktif</option>
              </select>
            </div>
            <div class="col-md-3 d-flex">
              <a href="<?php echo e(route('super_admin.users.index')); ?>"
                class="btn btn-default btn-sm shadow-sm flex-fill mr-2">Reset</a>
              <button type="submit" class="btn btn-primary btn-sm shadow-sm flex-fill font-weight-bold">Terapkan</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card card-outline card-primary shadow-sm overflow-hidden border-top-4">
      <div class="card-header bg-white border-0 py-3">
        <h3 class="card-title font-weight-bold"><i class="fas fa-users-cog mr-2 text-primary"></i> Daftar Pegawai & Akses
        </h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped mb-0">
            <thead class="bg-light">
              <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th>Nama Lengkap</th>
                <th>Email Dinas</th>
                <th class="text-center">Role</th>
                <th class="text-center">Status</th>
                <th class="text-right px-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="text-center align-middle"><?php echo e($users->firstItem() + $index); ?></td>
                  <td class="align-middle">
                    <div class="d-flex align-items-center">
                      <div
                        class="bg-primary-soft text-primary rounded d-flex align-items-center justify-content-center mr-3 font-weight-bold"
                        style="width: 35px; height: 35px; background-color: rgba(0,123,255,0.1);">
                        <?php echo e(collect(explode(' ', $user->name))->take(2)->map(fn($n) => substr($n, 0, 1))->join('')); ?>

                      </div>
                      <div>
                        <div class="font-weight-bold text-dark"><?php echo e($user->name); ?></div>
                        <div class="extra-small text-muted font-italic">NIP: <?php echo e($user->nip ?? '-'); ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="align-middle text-muted small"><?php echo e($user->email); ?></td>
                  <td class="text-center align-middle">
                    <span class="badge badge-info px-3 py-1 shadow-sm text-uppercase" style="font-size: 0.65rem;">
                      <?php echo e(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'No Role')); ?>

                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <?php if($user->is_active): ?>
                      <span class="badge badge-success px-3 py-1 shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Aktif
                      </span>
                    <?php else: ?>
                      <span class="badge badge-secondary px-3 py-1 shadow-sm">
                        <i class="fas fa-times-circle mr-1"></i> Non-Aktif
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-right align-middle px-4">
                    <div class="btn-group">
                      <button onclick="openResetModal(<?php echo e(json_encode($user)); ?>)" class="btn btn-sm btn-default shadow-sm"
                        title="Reset Password">
                        <i class="fas fa-key text-warning"></i>
                      </button>
                      <button onclick="openModal('edit', <?php echo e(json_encode($user->load('roles'))); ?>)"
                        class="btn btn-sm btn-default shadow-sm" title="Edit">
                        <i class="fas fa-pencil-alt text-primary"></i>
                      </button>
                      <form action="<?php echo e(route('super_admin.users.destroy', $user)); ?>" method="POST" class="d-inline"
                        onsubmit="return confirm('Hapus user ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-default shadow-sm" title="Delete">
                          <i class="fas fa-trash text-danger"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <i class="fas fa-user-slash fa-3x mb-3 text-light"></i>
                    <p class="text-muted font-italic">Data user tidak ditemukan.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php if($users->hasPages()): ?>
        <div class="card-footer bg-white border-top">
          <?php echo e($users->links()); ?>

        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Modal User -->
  <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-primary text-white font-weight-bold">
          <h5 class="modal-title" id="modalTitle">Tambah User Baru</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formUser" method="POST">
          <?php echo csrf_field(); ?>
          <div id="methodContainer"></div>
          <input type="hidden" name="id" id="userId">
          <div class="modal-body">
            <div class="form-group">
              <label class="small font-weight-bold text-muted text-uppercase">Nama Lengkap</label>
              <input required name="name" id="userName" class="form-control"
                placeholder="Masukkan nama lengkap dengan gelar" type="text" />
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label class="small font-weight-bold text-muted text-uppercase">NIP</label>
                <input name="nip" id="userNip" class="form-control" placeholder="Contoh: 1982..." type="text" />
              </div>
              <div class="col-md-6 form-group">
                <label class="small font-weight-bold text-muted text-uppercase">Role Akses</label>
                <select required name="role" id="userRole" class="form-control">
                  <option value="">Pilih Role</option>
                  <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->name); ?>"><?php echo e(strtoupper(str_replace('_', ' ', $role->name))); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="small font-weight-bold text-muted text-uppercase">Email Dinas</label>
              <input required name="email" id="userEmail" class="form-control" placeholder="example@disdik.go.id"
                type="email" />
            </div>
            <div id="passwordField" class="form-group">
              <label class="small font-weight-bold text-muted text-uppercase">Password Awal</label>
              <input name="password" id="userPassword" class="form-control" type="password"
                placeholder="Minimal 8 karakter" />
              <small class="text-muted font-italic">User diwajibkan mengganti password pada login pertama.</small>
            </div>
            <div id="lembagaSelectContainer" class="form-group d-none">
              <label class="small font-weight-bold text-muted text-uppercase text-primary">Pilih Lembaga</label>
              <select name="lembaga_id" id="userLembaga" class="form-control border-primary">
                <option value="">Pilih Lembaga (Hanya yang belum punya akun)</option>
                <?php $__empty_1 = true; $__currentLoopData = $lembagas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lembaga): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($lembaga->id); ?>"><?php echo e($lembaga->nama_lembaga); ?> (<?php echo e($lembaga->jenjang); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <option value="" disabled>-- Semua lembaga sudah memiliki akun --</option>
                <?php endif; ?>
              </select>
              <small class="text-muted font-italic">Pilihan lembaga hanya muncul untuk Role "Admin Lembaga".</small>
            </div>

            <div id="statusToggle" class="form-group d-none">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" name="is_active" class="custom-control-input" id="userStatus" value="1">
                <label class="custom-control-label font-weight-bold" for="userStatus">Status Akun Aktif</label>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-default shadow-sm px-4" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary shadow-sm px-4 font-weight-bold">
              <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Reset Password -->
  <div class="modal fade" id="modalReset" tabindex="-1" role="dialog" aria-labelledby="resetTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-warning text-dark font-weight-bold">
          <h5 class="modal-title" id="resetTitle"><i class="fas fa-key mr-2"></i> Reset Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formReset" method="POST">
          <?php echo csrf_field(); ?>
          <div class="modal-body">
            <p class="small text-muted mb-3 text-center">Reset password untuk:<br><strong id="resetTarget"
                class="text-dark"></strong></p>
            <div class="form-group mb-0">
              <label class="small font-weight-bold text-muted text-uppercase">Password Baru</label>
              <input required name="password" class="form-control form-control-sm" type="password"
                placeholder="Minimal 8 karakter" />
            </div>
          </div>
          <div class="modal-footer bg-light p-2">
            <button type="button" class="btn btn-link btn-sm text-muted" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning btn-sm font-weight-bold shadow-sm">Reset Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php $__env->startPush('scripts'); ?>
    <script>
      function openModal(mode, data = null) {
        const form = document.getElementById('formUser');
        const title = document.getElementById('modalTitle');
        const methodContainer = document.getElementById('methodContainer');
        const passwordField = document.getElementById('passwordField');
        const statusToggle = document.getElementById('statusToggle');
        const lembagaContainer = document.getElementById('lembagaSelectContainer');

        form.reset();

        if (mode === 'edit' && data) {
          title.innerText = 'Edit User Pegawai';
          form.action = `/super-admin/users/${data.id}`;
          methodContainer.innerHTML = '<?php echo method_field("PUT"); ?>';
          passwordField.classList.add('d-none');
          document.getElementById('userPassword').required = false;
          statusToggle.classList.remove('d-none');

          document.getElementById('userId').value = data.id;
          document.getElementById('userName').value = data.name;
          document.getElementById('userNip').value = data.nip || '';
          document.getElementById('userEmail').value = data.email;

          const roleName = data.roles && data.roles.length > 0 ? data.roles[0].name : '';
          document.getElementById('userRole').value = roleName;
          document.getElementById('userStatus').checked = data.is_active == 1;

          if (roleName === 'admin_lembaga') {
            lembagaContainer.classList.remove('d-none');
            if (data.lembaga_id) {
              const select = document.getElementById('userLembaga');
              let exists = Array.from(select.options).some(opt => opt.value == data.lembaga_id);
              if (!exists && data.lembaga) {
                const opt = document.createElement('option');
                opt.value = data.lembaga_id;
                opt.text = data.lembaga.nama_lembaga + ' (Current)';
                select.add(opt);
              }
              select.value = data.lembaga_id;
            }
          } else {
            lembagaContainer.classList.add('d-none');
          }
        } else {
          title.innerText = 'Tambah User Baru';
          form.action = "<?php echo e(route('super_admin.users.store')); ?>";
          methodContainer.innerHTML = '';
          passwordField.classList.remove('d-none');
          document.getElementById('userPassword').required = true;
          statusToggle.classList.add('d-none');
          lembagaContainer.classList.add('d-none');

          if (data) {
            if (data.name) document.getElementById('userName').value = data.name;
            if (data.nip) document.getElementById('userNip').value = data.nip;
            if (data.email) document.getElementById('userEmail').value = data.email;
            if (data.roles && data.roles[0].name) {
              const roleName = data.roles[0].name;
              document.getElementById('userRole').value = roleName;
              if (roleName === 'admin_lembaga') {
                lembagaContainer.classList.remove('d-none');
                if (data.lembaga_id) document.getElementById('userLembaga').value = data.lembaga_id;
              }
            }
          }
        }
        $('#modalUser').modal('show');
      }

      document.getElementById('userRole').addEventListener('change', function () {
        if (this.value === 'admin_lembaga') {
          document.getElementById('lembagaSelectContainer').classList.remove('d-none');
          document.getElementById('userLembaga').required = true;
        } else {
          document.getElementById('lembagaSelectContainer').classList.add('d-none');
          document.getElementById('userLembaga').required = false;
        }
      });

      function openResetModal(user) {
        const form = document.getElementById('formReset');
        document.getElementById('resetTarget').innerText = user.name;
        form.action = `/super-admin/users/${user.id}/reset-password`;
        $('#modalReset').modal('show');
      }

      <?php if($errors->any()): ?>
        $(document).ready(function () {
          openModal("<?php echo e(old('_method') == 'PUT' ? 'edit' : 'add'); ?>", {
            id: "<?php echo e(old('id')); ?>",
            name: "<?php echo e(old('name')); ?>",
            email: "<?php echo e(old('email')); ?>",
            nip: "<?php echo e(old('nip')); ?>",
            roles: [{
              name: "<?php echo e(old('role')); ?>"
            }],
            is_active: <?php echo e(old('is_active') ? '1' : '0'); ?>,
            lembaga_id: "<?php echo e(old('lembaga_id')); ?>"
          });
        });
      <?php elseif(request('create') == 1): ?>
        $(document).ready(function () {
          openModal('add');
          document.getElementById('userRole').value = 'admin_lembaga';
          document.getElementById('lembagaSelectContainer').classList.remove('d-none');

          const lembagaId = "<?php echo e(request('lembaga_id')); ?>";
          if (lembagaId) {
            const select = document.getElementById('userLembaga');
            let exists = Array.from(select.options).some(opt => opt.value == lembagaId);
            if (!exists) {
              <?php if(request('lembaga_id')): ?>
                <?php $l = \App\Models\Lembaga::find(request('lembaga_id')); ?>
                <?php if($l): ?>
                  const opt = document.createElement('option');
                  opt.value = "<?php echo e($l->id); ?>";
                  opt.text = "<?php echo e($l->nama_lembaga); ?> (Target)";
                  select.add(opt);
                <?php endif; ?>
              <?php endif; ?>
                      }
            select.value = lembagaId;
          }
        });
      <?php endif; ?>
    </script>
  <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\perizinan\resources\views/backend/super_admin/users/index.blade.php ENDPATH**/ ?>