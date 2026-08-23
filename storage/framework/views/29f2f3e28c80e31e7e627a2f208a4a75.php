

<?php $__env->startSection('title', 'Manajemen Jenis Perizinan'); ?>
<?php $__env->startSection('breadcrumb', 'Jenis Perizinan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* Premium UI Overrides */
  .premium-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    background: #ffffff;
  }
  .premium-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f3f5;
    padding: 1.5rem 1.5rem;
  }
  .table-premium th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #87929d;
    border-top: none !important;
    border-bottom: 2px solid #f8f9fa !important;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }
  .table-premium td {
    vertical-align: middle;
    padding: 1.25rem 0.75rem;
    border-bottom: 1px solid #f8f9fa;
    color: #495057;
  }
  .table-premium tbody tr {
    transition: all 0.2s ease;
  }
  .table-premium tbody tr:hover {
    background-color: #fcfcfc;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    transform: translateY(-1px);
    z-index: 1;
    position: relative;
  }
  .badge-soft-success {
    background-color: #d1e7dd;
    color: #0f5132;
    font-weight: 600;
  }
  .badge-soft-secondary {
    background-color: #e9ecef;
    color: #495057;
    font-weight: 600;
  }
  .btn-action {
    border-radius: 8px;
    transition: all 0.2s ease;
    font-weight: 600;
    font-size: 0.85rem;
  }
  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  .icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f8f9fa;
  }
  .icon-wrapper.primary { color: #0d6efd; background: #ebf3ff; }
  .action-group {
    display: flex;
    gap: 8px;
    align-items: center;
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
  <div class="container-fluid text-dark pb-4">

    <!-- Header & Action -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
      <div>
        <p class="text-muted mb-0">Kelola dan atur master kategori perizinan dinas secara terpusat.</p>
      </div>
      <div class="mt-3 mt-md-0 d-flex gap-2">
        <button type="button" id="btnBulkDelete" onclick="submitBulkDelete()" class="btn btn-danger btn-action shadow-sm px-4 mr-2" style="display: none;">
          <i class="fas fa-trash-alt mr-2"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
        </button>
        <button type="button" onclick="openModal('add')" class="btn btn-primary btn-action shadow-sm px-4">
          <i class="fas fa-plus mr-2"></i> Tambah Kategori
        </button>
      </div>
    </div>

    <!-- Main Card -->
    <div class="card premium-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <div class="icon-wrapper primary mr-3">
            <i class="fas fa-layer-group"></i>
          </div>
          <div>
            <h5 class="mb-0 font-weight-bold text-dark">Data Kategori Perizinan</h5>
            <small class="text-muted">Total <?php echo e($jenisPerizinans->total()); ?> kategori terdaftar</small>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <form id="bulkDeleteForm" action="<?php echo e(route('super_admin.jenis_perizinan.bulk_destroy')); ?>" method="POST" style="display: none;">
          <?php echo csrf_field(); ?>
          <?php echo method_field('DELETE'); ?>
        </form>
        
        <div class="table-responsive">
          <table class="table table-premium mb-0">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px; padding-left: 1.5rem;">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkAll">
                    <label class="custom-control-label" for="checkAll"></label>
                  </div>
                </th>
                <th style="width: 50px;">No</th>
                <th style="min-width: 250px;">Informasi Perizinan</th>
                <th class="text-center" style="min-width: 140px;">Durasi Berlaku</th>
                <th class="text-center" style="min-width: 120px;">Status</th>
                <th class="text-right" style="min-width: 320px; padding-right: 1.5rem;">Opsi & Pengaturan</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $jenisPerizinans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="text-center" style="padding-left: 1.5rem;">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input check-item" id="check_<?php echo e($item->id); ?>" value="<?php echo e($item->id); ?>">
                      <label class="custom-control-label" for="check_<?php echo e($item->id); ?>"></label>
                    </div>
                  </td>
                  <td class="text-muted font-weight-bold"><?php echo e($jenisPerizinans->firstItem() + $index); ?></td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="font-weight-bold text-dark mb-1" style="font-size: 1rem;"><?php echo e($item->nama); ?></span>
                      <small class="text-muted"><span class="badge badge-light border text-primary px-2 py-1">ID: <?php echo e($item->kode ?? '-'); ?></span></small>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-light border px-3 py-2 text-dark font-weight-bold rounded" style="font-size: 0.85rem;">
                      <i class="far fa-clock mr-1 text-info"></i> <?php echo e($item->masa_berlaku_nilai); ?> <?php echo e($item->masa_berlaku_unit); ?>

                    </span>
                  </td>
                  <td class="text-center">
                    <?php if($item->is_active): ?>
                      <span class="badge badge-soft-success px-3 py-2 rounded-pill">
                        <i class="fas fa-circle mr-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Aktif
                      </span>
                    <?php else: ?>
                      <span class="badge badge-soft-secondary px-3 py-2 rounded-pill">
                        <i class="fas fa-circle mr-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Nonaktif
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-right" style="padding-right: 1.5rem;">
                    <div class="action-group justify-content-end">
                      <a href="<?php echo e(route('super_admin.jenis_perizinan.template', $item)); ?>" class="btn btn-sm btn-outline-primary btn-action" title="Desain Template">
                        <i class="fas fa-paint-brush mr-1"></i> Template
                      </a>
                      <a href="<?php echo e(route('super_admin.jenis_perizinan.syarat.index', $item)); ?>" class="btn btn-sm btn-outline-info btn-action" title="Kelola Persyaratan">
                        <i class="fas fa-list-check mr-1"></i> Syarat
                      </a>
                      <a href="<?php echo e(route('super_admin.jenis_perizinan.form', $item)); ?>" class="btn btn-sm btn-outline-dark btn-action" title="Konfigurasi Form">
                        <i class="fab fa-wpforms mr-1"></i> Form
                      </a>
                      
                      <div class="border-left ml-2 pl-2 d-flex gap-1">
                        <button onclick="openModal('edit', this)"
                          data-id="<?php echo e($item->id); ?>" data-nama="<?php echo e($item->nama); ?>" data-kode="<?php echo e($item->kode); ?>"
                          data-masa-nilai="<?php echo e($item->masa_berlaku_nilai); ?>" data-masa-unit="<?php echo e($item->masa_berlaku_unit); ?>"
                          data-deskripsi="<?php echo e($item->deskripsi); ?>" data-is-active="<?php echo e($item->is_active); ?>"
                          class="btn btn-sm btn-light border btn-action text-warning" title="Edit">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <form action="<?php echo e(route('super_admin.jenis_perizinan.destroy', $item)); ?>" method="POST" class="d-inline mb-0" onsubmit="return confirm('Hapus jenis perizinan ini secara permanen?')">
                          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                          <button type="submit" class="btn btn-sm btn-light border btn-action text-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center py-4">
                      <div class="icon-wrapper mb-3" style="width: 64px; height: 64px; background: #f8f9fa;">
                        <i class="fas fa-folder-open fa-2x text-muted"></i>
                      </div>
                      <h6 class="font-weight-bold text-dark">Belum ada kategori perizinan</h6>
                      <p class="text-muted font-italic mb-3">Mulai dengan menambahkan kategori perizinan pertama Anda.</p>
                      <button type="button" onclick="openModal('add')" class="btn btn-primary btn-action px-4">
                        <i class="fas fa-plus mr-2"></i> Tambah Kategori
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php if($jenisPerizinans->hasPages()): ?>
        <div class="card-footer bg-white border-top py-3 px-4">
          <div class="d-flex justify-content-center m-0">
            <?php echo e($jenisPerizinans->withQueryString()->links('pagination::bootstrap-4')); ?>

          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>



  <!-- Modal Popup for Add/Edit -->
  <div class="modal fade" id="modalJenisPerizinan" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title font-weight-bold" id="modalTitle">Tambah Jenis Perizinan</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formJenisPerizinan" method="POST">
          <?php echo csrf_field(); ?>
          <input type="hidden" id="formMethod" name="_method" value="POST">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label for="nama" class="font-weight-bold">Nama Perizinan <span class="text-danger">*</span></label>
                  <input required class="form-control" id="nama" name="nama" placeholder="Contoh: Izin Usaha Mikro"
                    type="text" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="kode" class="font-weight-bold">Kode Perizinan</label>
                  <input class="form-control" id="kode" name="kode" placeholder="Contoh: PRM-001" type="text" />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold" for="masa_berlaku_nilai">Masa Berlaku <span
                      class="text-danger">*</span></label>
                  <div class="input-group">
                    <input required class="form-control" id="masa_berlaku_nilai" name="masa_berlaku_nilai" placeholder="5"
                      type="number" />
                    <div class="input-group-append">
                      <select name="masa_berlaku_unit" class="form-control bg-light"
                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <option value="Tahun">Tahun</option>
                        <option value="Bulan">Bulan</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold d-block">Status Aktif</label>
                  <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-2">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" checked value="1">
                    <label class="custom-control-label" for="is_active">Aktifkan jenis perizinan ini</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="font-weight-bold" for="deskripsi">Deskripsi Singkat (Opsional)</label>
              <textarea class="form-control" id="deskripsi" name="deskripsi"
                placeholder="Berikan penjelasan singkat mengenai kategori izin ini..." rows="3"></textarea>
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

  <?php $__env->startPush('scripts'); ?>
    <script>
      function openModal(mode, element = null) {
        const form = document.getElementById('formJenisPerizinan');
        const title = document.getElementById('modalTitle');
        const methodInput = document.getElementById('formMethod');

        if (mode === 'edit') {
          title.innerText = 'Edit Jenis Perizinan';
          
          // Best practice routing (dinamis)
          let baseUrl = "<?php echo e(route('super_admin.jenis_perizinan.update', 'ID_PLACEHOLDER')); ?>";
          form.action = baseUrl.replace('ID_PLACEHOLDER', element.dataset.id);
          
          // Ubah value method input, bukan inject HTML
          methodInput.value = "PUT";

          // Fill data
          document.getElementById('nama').value = element.dataset.nama;
          document.getElementById('kode').value = element.dataset.kode || '';
          document.getElementById('masa_berlaku_nilai').value = element.dataset.masaNilai;
          document.querySelector('select[name="masa_berlaku_unit"]').value = element.dataset.masaUnit;
          document.getElementById('deskripsi').value = element.dataset.deskripsi || '';
          document.getElementById('is_active').checked = element.dataset.isActive == '1';
        } else {
          title.innerText = 'Tambah Jenis Perizinan';
          form.action = "<?php echo e(route('super_admin.jenis_perizinan.store')); ?>";
          methodInput.value = "POST";
          form.reset();
          document.getElementById('is_active').checked = true;
        }

        $('#modalJenisPerizinan').modal('show');
      }

      // Bulk Delete Logic
      const checkAll = document.getElementById('checkAll');
      const checkItems = document.querySelectorAll('.check-item');
      const btnBulkDelete = document.getElementById('btnBulkDelete');
      const selectedCountSpan = document.getElementById('selectedCount');
      const bulkDeleteForm = document.getElementById('bulkDeleteForm');

      function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.check-item:checked').length;
        if (checkedCount > 0) {
          btnBulkDelete.style.display = 'inline-block';
          selectedCountSpan.innerText = checkedCount;
        } else {
          btnBulkDelete.style.display = 'none';
        }
        if(checkAll) {
          checkAll.checked = checkedCount === checkItems.length && checkItems.length > 0;
        }
      }

      if (checkAll) {
        checkAll.addEventListener('change', function() {
          checkItems.forEach(item => {
            item.checked = this.checked;
          });
          updateBulkDeleteButton();
        });
      }

      checkItems.forEach(item => {
        item.addEventListener('change', updateBulkDeleteButton);
      });

      function submitBulkDelete() {
        const checkedItems = document.querySelectorAll('.check-item:checked');
        if (checkedItems.length === 0) return;

        if (confirm(`Yakin ingin menghapus ${checkedItems.length} jenis perizinan terpilih secara permanen?`)) {
          // Add hidden inputs to form
          checkedItems.forEach(item => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = item.value;
            bulkDeleteForm.appendChild(input);
          });
          bulkDeleteForm.submit();
        }
      }
    </script>
  <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.backend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\perizinan\resources\views/backend/super_admin/jenis_perizinan/index.blade.php ENDPATH**/ ?>