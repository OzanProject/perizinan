

<?php $__env->startSection('title', 'Manajemen Lembaga'); ?>
<?php $__env->startSection('breadcrumb', 'Manajemen Lembaga'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Stats Row (Optional, for better AdminLTE feel) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-university mr-2 text-primary"></i> Daftar Lembaga Pendidikan</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('super_admin.lembaga.create')); ?>" class="btn btn-primary btn-sm shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Lembaga
                        </a>
                        <button type="button" class="btn btn-danger btn-sm shadow-sm ml-2 d-none" id="btn-bulk-delete" onclick="confirmBulkDelete()">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Toolbar & Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6 col-lg-4">
                            <form action="<?php echo e(route('super_admin.lembaga.index')); ?>" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Cari nama atau NPSN...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table wrapped in bulk delete form -->
                    <form id="bulk-delete-form" action="<?php echo e(route('super_admin.lembaga.bulk_destroy')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped border">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check-all">
                                                <label class="custom-control-label" for="check-all"></label>
                                            </div>
                                        </th>
                                        <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 80px;">Logo</th>
                                    <th>Identitas Lembaga</th>
                                    <th class="text-center">Jenjang</th>
                                    <th>Admin Utama</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $lembagas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lembaga): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input checkbox-item" name="ids[]" value="<?php echo e($lembaga->id); ?>" id="check-<?php echo e($lembaga->id); ?>">
                                                <label class="custom-control-label" for="check-<?php echo e($lembaga->id); ?>"></label>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle"><?php echo e($lembagas->firstItem() + $index); ?></td>
                                        <td class="align-middle text-center">
                                            <?php if($lembaga->logo): ?>
                                                <img src="<?php echo e(asset('storage/' . $lembaga->logo)); ?>" alt="Logo" class="img-thumbnail shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center mx-auto" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-school text-muted small"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-primary"><?php echo e($lembaga->nama_lembaga); ?></div>
                                            <div class="small text-muted"><i class="fas fa-id-card-alt mr-1"></i> NPSN: <?php echo e($lembaga->npsn); ?></div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary px-2 py-1 shadow-sm"><?php echo e($lembaga->jenjang ?? 'N/A'); ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <?php $admin = $lembaga->users->first(); ?>
                                            <?php if($admin): ?>
                                                <div class="small"><i class="fas fa-user mr-1 text-muted"></i> <?php echo e($admin->name); ?></div>
                                                <div class="extra-small text-muted"><?php echo e($admin->email); ?></div>
                                            <?php else: ?>
                                                <span class="text-danger small font-italic"><i class="fas fa-exclamation-circle mr-1"></i> Belum ada admin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-success px-3 py-1">Aktif</span>
                                        </td>
                                        <td class="text-right align-middle">
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('super_admin.lembaga.show', $lembaga)); ?>" class="btn btn-sm btn-info shadow-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if(!$admin): ?>
                                                    <a href="<?php echo e(route('super_admin.users.index', ['role' => 'admin_lembaga', 'lembaga_id' => $lembaga->id, 'create' => 1])); ?>" class="btn btn-sm btn-success shadow-sm" title="Buat Akun">
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="<?php echo e(route('super_admin.lembaga.edit', $lembaga)); ?>" class="btn btn-sm btn-warning shadow-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('super_admin.lembaga.destroy', $lembaga)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus lembaga ini secara permanen?')">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Tidak ada data lembaga ditemukan.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-top">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <small class="text-muted mb-3 mb-md-0">
                            Menampilkan <b><?php echo e($lembagas->firstItem()); ?></b> - <b><?php echo e($lembagas->lastItem()); ?></b> dari <b><?php echo e($lembagas->total()); ?></b> lembaga
                        </small>
                        <div>
                            <?php echo e($lembagas->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.checkbox-item');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.checkbox-item:checked').length;
            selectedCount.textContent = checkedCount;
            
            if (checkedCount > 0) {
                btnBulkDelete.classList.remove('d-none');
            } else {
                btnBulkDelete.classList.add('d-none');
                checkAll.checked = false;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkDeleteButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkDeleteButton);
        });
    });

    function confirmBulkDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus semua lembaga yang dipilih secara permanen?')) {
            document.getElementById('bulk-delete-form').submit();
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\perizinan\resources\views/backend/super_admin/lembaga/index.blade.php ENDPATH**/ ?>