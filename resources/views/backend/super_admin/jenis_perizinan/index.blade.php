@extends('layouts.backend')

@section('title', 'Manajemen Jenis Perizinan')
@section('breadcrumb', 'Jenis Perizinan')

@section('content')
  <div class="container-fluid text-dark">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
      <div class="col-sm-6 text-center text-sm-left">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-folder-open mr-2 text-primary"></i> Manajemen Jenis
          Perizinan</h1>
        <p class="text-muted small mt-1">Kelola kategori dan pengaturan perizinan dinas Anda secara terpusat.</p>
      </div>
      <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-0 justify-content-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Jenis Perizinan</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Card -->
    <div class="card card-outline card-primary shadow-sm border-0">
      <div class="card-header bg-white py-3">
        <h3 class="card-title font-weight-bold">
          <i class="fas fa-layer-group mr-2 text-primary"></i> Master Data Kategori
        </h3>
        <div class="card-tools">
          <button type="button" onclick="openModal('add')"
            class="btn btn-primary btn-sm shadow-sm font-weight-bold rounded-pill px-3">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori
          </button>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted uppercase small font-weight-bold">
              <tr>
                <th class="text-center py-3" style="width: 50px;">No</th>
                <th class="py-3">Informasi Perizinan</th>
                <th class="py-3 text-center">Durasi Berlaku</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-right pr-4">Opsi Pengaturan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jenisPerizinans as $index => $item)
                <tr class="hover-row">
                  <td class="text-center text-muted">{{ $jenisPerizinans->firstItem() + $index }}</td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="font-weight-bold text-dark mb-0">{{ $item->nama }}</span>
                      <small class="text-muted"><i class="fas fa-barcode mr-1 opacity-50"></i>ID: <span
                          class="text-primary font-weight-bold">{{ $item->kode ?? '-' }}</span></small>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="badge badge-light border rounded-pill px-3 py-2 font-weight-bold text-dark shadow-sm">
                      <i class="far fa-clock mr-1 text-info"></i> {{ $item->masa_berlaku_nilai }}
                      {{ $item->masa_berlaku_unit }}
                    </div>
                  </td>
                  <td class="text-center">
                    @if($item->is_active)
                      <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm" style="font-size: 11px;">
                        <i class="fas fa-check-circle mr-1"></i> Aktif
                      </span>
                    @else
                      <span class="badge badge-secondary px-3 py-2 rounded-pill shadow-sm" style="font-size: 11px;">
                        <i class="fas fa-times-circle mr-1"></i> Nonaktif
                      </span>
                    @endif
                  </td>
                  <td class="text-right pr-4">
                    <div class="d-flex justify-content-end align-items-center">
                      <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                        <a href="{{ route('super_admin.jenis_perizinan.template', $item) }}"
                          class="btn btn-sm btn-outline-primary px-3" title="Desain Sertifikat">
                          <i class="fas fa-certificate mr-1"></i> <span class="d-none d-lg-inline">Template</span>
                        </a>
                        <a href="{{ route('super_admin.jenis_perizinan.syarat.index', $item) }}"
                          class="btn btn-sm btn-outline-info px-3" title="Kelola Persyaratan">
                          <i class="fas fa-file-contract mr-1"></i> <span class="d-none d-lg-inline">Syarat</span>
                        </a>
                        <a href="{{ route('super_admin.jenis_perizinan.form', $item) }}"
                          class="btn btn-sm btn-outline-dark px-3" title="Konfigurasi Form">
                          <i class="fab fa-wpforms mr-1"></i> <span class="d-none d-lg-inline">Form</span>
                        </a>
                      </div>

                      <div class="ml-2">
                        <button onclick="openModal('edit', {{ json_encode($item) }})"
                          class="btn btn-sm btn-light border rounded-circle shadow-sm mx-1" title="Edit Dasar">
                          <i class="fas fa-pencil-alt text-warning"></i>
                        </button>
                        <form action="{{ route('super_admin.jenis_perizinan.destroy', $item) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Hapus jenis perizinan ini secara permanen?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-light border rounded-circle shadow-sm" title="Hapus">
                            <i class="fas fa-trash text-danger"></i>
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center opacity-75">
                      <i class="fas fa-layer-group fa-3x mb-3 text-muted"></i>
                      <p class="text-muted font-italic">Belum ada kategori perizinan yang tersedia.</p>
                      <button type="button" onclick="openModal('add')" class="btn btn-primary btn-sm rounded-pill">Tambah
                        Sekarang</button>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($jenisPerizinans->hasPages())
        <div class="card-footer bg-white border-top py-3">
          <div class="d-flex justify-content-center">
            {{ $jenisPerizinans->links('pagination::bootstrap-4') }}
          </div>
        </div>
      @endif
    </div>
  </div>

  <style>
    .table td {
      vertical-align: middle;
    }

    .hover-row:hover {
      background-color: rgba(0, 123, 255, 0.02);
      transition: background 0.2s ease;
    }

    .btn-group .btn {
      border-width: 1px !important;
    }

    .badge {
      letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
      .d-lg-inline {
        display: none !important;
      }

      .btn-sm i {
        margin-right: 0 !important;
      }

      .btn-group .btn {
        padding: 0.25rem 0.6rem;
      }
    }
  </style>

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