@extends('layouts.backend')

@section('title', 'Manajemen Jenis Perizinan')
@section('breadcrumb', 'Jenis Perizinan')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary shadow-sm">
          <div class="card-header">
            <h3 class="card-title font-weight-bold"><i class="fas fa-list-alt mr-2 text-primary"></i> Daftar Jenis
              Perizinan</h3>
            <div class="card-tools">
              <button type="button" onclick="openModal('add')" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Jenis
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Perizinan</th>
                    <th>Masa Berlaku</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($jenisPerizinans as $index => $item)
                    <tr>
                      <td class="text-center align-middle">{{ $jenisPerizinans->firstItem() + $index }}</td>
                      <td class="align-middle">
                        <div class="font-weight-bold text-dark">{{ $item->nama }}</div>
                        <div class="small text-muted font-italic">Kode: {{ $item->kode ?? '-' }}</div>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-info"></i> {{ $item->masa_berlaku_nilai }}
                        {{ $item->masa_berlaku_unit }}
                      </td>
                      <td class="text-center align-middle">
                        @if($item->is_active)
                          <span class="badge badge-success px-3 py-1 shadow-sm">Aktif</span>
                        @else
                          <span class="badge badge-secondary px-3 py-1 shadow-sm">Nonaktif</span>
                        @endif
                      </td>
                      <td class="text-right align-middle" style="white-space: nowrap;">
                        <a href="{{ route('super_admin.jenis_perizinan.template', $item) }}"
                          class="btn btn-xs btn-primary mr-1" title="Template Sertifikat">
                          <i class="fas fa-certificate mr-1"></i> Template
                        </a>
                        <a href="{{ route('super_admin.jenis_perizinan.syarat.index', $item) }}"
                          class="btn btn-xs btn-info mr-1" title="Kelola Syarat">
                          <i class="fas fa-tasks mr-1"></i> Syarat
                        </a>
                        <a href="{{ route('super_admin.jenis_perizinan.form', $item) }}" class="btn btn-xs btn-dark mr-1"
                          title="Kelola Form">
                          <i class="fas fa-wpforms mr-1"></i> Form
                        </a>
                        <button onclick="openModal('edit', {{ json_encode($item) }})" class="btn btn-xs btn-warning mr-1"
                          title="Edit">
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <form action="{{ route('super_admin.jenis_perizinan.destroy', $item) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Hapus jenis perizinan ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-xs btn-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                        <p class="text-muted font-italic">Belum ada data jenis perizinan.</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          @if($jenisPerizinans->hasPages())
            <div class="card-footer bg-white border-top">
              {{ $jenisPerizinans->links() }}
            </div>
          @endif
        </div>
      </div>
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
          @csrf
          <div id="methodContainer"></div>
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

  @push('scripts')
    <script>
      function openModal(mode, data = null) {
        const form = document.getElementById('formJenisPerizinan');
        const title = document.getElementById('modalTitle');
        const methodContainer = document.getElementById('methodContainer');

        if (mode === 'edit') {
          title.innerText = 'Edit Jenis Perizinan';
          form.action = `/super-admin/jenis-perizinan/${data.id}`;
          methodContainer.innerHTML = '@method("PUT")';

          // Fill data
          document.getElementById('nama').value = data.nama;
          document.getElementById('kode').value = data.kode || '';
          document.getElementById('masa_berlaku_nilai').value = data.masa_berlaku_nilai;
          document.querySelector('select[name="masa_berlaku_unit"]').value = data.masa_berlaku_unit;
          document.getElementById('deskripsi').value = data.deskripsi || '';
          document.getElementById('is_active').checked = data.is_active == 1;
        } else {
          title.innerText = 'Tambah Jenis Perizinan';
          form.action = "{{ route('super_admin.jenis_perizinan.store') }}";
          methodContainer.innerHTML = '';
          form.reset();
          document.getElementById('is_active').checked = true;
        }

        $('#modalJenisPerizinan').modal('show');
      }
    </script>
  @endpush
@endsection