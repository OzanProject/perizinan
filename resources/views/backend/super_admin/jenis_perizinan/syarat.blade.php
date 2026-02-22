@extends('layouts.backend')

@section('title', 'Persyaratan Dokumen')
@section('breadcrumb', 'Persyaratan: ' . $jenisPerizinan->nama)

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
      <div class="col-sm-7">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-clipboard-list mr-2 text-primary"></i> Persyaratan
          Dokumen</h1>
        <p class="text-muted small mt-1">Konfigurasi dokumen wajib dan opsional yang harus diunggah oleh pemohon untuk
          <strong>{{ $jenisPerizinan->nama }}</strong>.</p>
      </div>
      <div class="col-sm-5 text-right">
        <div class="btn-group">
          <a href="{{ route('super_admin.jenis_perizinan.index') }}"
            class="btn btn-default btn-sm shadow-sm font-weight-bold mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
          <button type="button" onclick="openModal('add')" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Persyaratan
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="card card-outline card-primary shadow-sm border-0">
      <div class="card-header bg-white py-3">
        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-th-list mr-2 text-primary"></i> Daftar Dokumen
          Persyaratan</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
                <th class="px-4 text-center" style="width: 60px;">No</th>
                <th>Nama Dokumen</th>
                <th class="text-center">Format File</th>
                <th class="text-center">Sifat</th>
                <th class="text-right px-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($syarats as $index => $syarat)
                <tr>
                  <td class="text-center align-middle">{{ $index + 1 }}</td>
                  <td class="align-middle">
                    <div class="font-weight-bold text-dark">{{ $syarat->nama_dokumen }}</div>
                    <div class="text-xs text-muted">{{ $syarat->deskripsi ?? '-' }}</div>
                  </td>
                  <td class="text-center align-middle">
                    <span
                      class="badge badge-info px-2 py-1 uppercase small font-weight-bold">{{ $syarat->tipe_file }}</span>
                  </td>
                  <td class="text-center align-middle">
                    @if($syarat->is_required)
                      <span class="badge badge-success px-3 py-1 rounded-pill small">
                        <i class="fas fa-check-circle mr-1"></i> Wajib
                      </span>
                    @else
                      <span class="badge badge-secondary px-3 py-1 rounded-pill small">
                        <i class="fas fa-minus-circle mr-1"></i> Opsional
                      </span>
                    @endif
                  </td>
                  <td class="text-right align-middle px-4">
                    <div class="btn-group">
                      <button onclick="openModal('edit', {{ json_encode($syarat) }})"
                        class="btn btn-warning btn-sm shadow-sm rounded-circle mr-2" title="Edit">
                        <i class="fas fa-edit text-white"></i>
                      </button>
                      <form action="{{ route('super_admin.jenis_perizinan.syarat.destroy', [$jenisPerizinan, $syarat]) }}"
                        method="POST" class="d-inline" onsubmit="return confirm('Hapus persyaratan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm shadow-sm rounded-circle" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="py-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                    Belum ada persyaratan dokumen yang dikonfigurasi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer bg-white py-2">
        <span class="text-xs text-muted font-weight-bold text-uppercase">Total: {{ $syarats->count() }} Persyaratan</span>
      </div>
    </div>
  </div>

  <!-- Modal Tambah/Edit (Bootstrap 4) -->
  <div class="modal fade" id="modalSyarat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 12px;">
        <div class="modal-header bg-light border-bottom-0 py-3">
          <h5 class="modal-title font-weight-bold" id="modalTitle">
            <i class="fas fa-plus-circle mr-2 text-primary"></i> Tambah Persyaratan
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formSyarat" method="POST">
          @csrf
          <div id="methodContainer"></div>
          <div class="modal-body p-4">
            <div class="form-group">
              <label class="small font-weight-bold text-uppercase text-muted">Nama Dokumen <span
                  class="text-danger">*</span></label>
              <input type="text" id="nama_dokumen" name="nama_dokumen" class="form-control font-weight-bold"
                placeholder="Contoh: Sertifikat Tanah / IMB" required>
            </div>
            <div class="form-group">
              <label class="small font-weight-bold text-uppercase text-muted">Deskripsi Singkat</label>
              <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                placeholder="Jelaskan detail dokumen untuk membantu pemohon..."></textarea>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-uppercase text-muted">Tipe File <span
                      class="text-danger">*</span></label>
                  <select id="tipe_file" name="tipe_file" class="form-control custom-select" required>
                    <option value="pdf">PDF Only</option>
                    <option value="image">Gambar (JPG, PNG)</option>
                    <option value="all">Semua Format</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="custom-control custom-switch mb-2">
                  <input type="checkbox" name="is_required" id="is_required" value="1" class="custom-control-input"
                    checked>
                  <label class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer"
                    for="is_required">Wajib Diunggah</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light border-top-0 py-3 mt-2">
            <button type="button" class="btn btn-default font-weight-bold px-4 shadow-sm"
              data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">Simpan Persyaratan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function openModal(mode, data = null) {
        const form = document.getElementById('formSyarat');
        const title = document.getElementById('modalTitle');
        const methodContainer = document.getElementById('methodContainer');

        if (mode === 'edit') {
          title.innerHTML = '<i class="fas fa-edit mr-2 text-warning"></i> Edit Persyaratan Dokumen';
          form.action = `/super-admin/jenis-perizinan/{{ $jenisPerizinan->id }}/syarat/${data.id}`;
          methodContainer.innerHTML = '@method("PUT")';

          document.getElementById('nama_dokumen').value = data.nama_dokumen;
          document.getElementById('deskripsi').value = data.deskripsi || '';
          document.getElementById('tipe_file').value = data.tipe_file;
          document.getElementById('is_required').checked = data.is_required == 1;
        } else {
          title.innerHTML = '<i class="fas fa-plus-circle mr-2 text-primary"></i> Tambah Persyaratan Dokumen';
          form.action = "{{ route('super_admin.jenis_perizinan.syarat.store', $jenisPerizinan) }}";
          methodContainer.innerHTML = '';
          form.reset();
          document.getElementById('is_required').checked = true;
        }

        $('#modalSyarat').modal('show');
      }
    </script>
  @endpush
@endsection