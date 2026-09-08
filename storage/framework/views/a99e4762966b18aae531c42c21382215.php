<?php $__env->startSection('title', 'Rekap Data Per Kecamatan'); ?>
<?php $__env->startSection('breadcrumb', 'Rekap Kecamatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-map-marked-alt mr-2 text-info"></i> Rekapitulasi Data Lembaga & Perizinan Berdasarkan Kecamatan
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" onclick="window.print()">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered m-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Kecamatan</th>
                                    <th>Jumlah Lembaga</th>
                                    <th>Total Pengajuan Perizinan</th>
                                    <th>Perizinan Disetujui/Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rekapData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center align-middle"><?php echo e($index + 1); ?></td>
                                        <td class="align-middle font-weight-bold text-uppercase text-primary">
                                            <?php echo e($data->kecamatan); ?>

                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-info px-3 py-1" style="font-size: 14px;"><?php echo e($data->total_lembaga); ?></span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary px-3 py-1" style="font-size: 14px;"><?php echo e($data->total_perizinan); ?></span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-success px-3 py-1" style="font-size: 14px;"><?php echo e($data->perizinan_disetujui); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-map-marker-alt fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Data kecamatan pada lembaga belum tersedia atau masih kosong.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if($rekapData->count() > 0): ?>
                                <tfoot class="bg-light font-weight-bold text-center">
                                    <tr>
                                        <td colspan="2" class="text-right">TOTAL KESELURUHAN :</td>
                                        <td><?php echo e($rekapData->sum('total_lembaga')); ?></td>
                                        <td><?php echo e($rekapData->sum('total_perizinan')); ?></td>
                                        <td><?php echo e($rekapData->sum('perizinan_disetujui')); ?></td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\perizinan\resources\views/backend/super_admin/laporan/rekap_kecamatan.blade.php ENDPATH**/ ?>