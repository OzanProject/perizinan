@extends('layouts.backend')

@section('title', 'Antrian Penerbitan Sertifikat')
@section('breadcrumb', 'Antrian Penerbitan')

@section('content')
  <div class="container-fluid">
    <!-- Stats Row -->
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info shadow-sm border-0">
          <div class="inner">
            <h3>{{ $perizinans->count() }}</h3>
            <p>Total Antrian</p>
          </div>
          <div class="icon">
            <i class="fas fa-list-ol"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-warning shadow-sm border-0">
          <div class="inner">
            <h3 class="text-white">{{ $perizinans->count() }}</h3>
            <p class="text-white">Siap Cetak</p>
          </div>
          <div class="icon">
            <i class="fas fa-print"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-success shadow-sm border-0">
          <div class="inner">
            <h3>0</h3>
            <p>Terkirim Hari Ini</p>
          </div>
          <div class="icon">
            <i class="fas fa-paper-plane"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="row mt-2">
      <div class="col-md-12">
        <div class="card card-outline card-primary shadow-sm border-0">
          <div class="card-header bg-white border-0 py-3">
            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-file-signature mr-2 text-primary"></i>
              Daftar Antrian Penerbitan</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-default btn-sm shadow-sm font-weight-bold mr-2">
                <i class="fas fa-download mr-1"></i> Export Data
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="p-3 bg-light border-bottom">
              <div class="row justify-content-end">
                <div class="col-md-4">
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Cari Nama/No. Tiket...">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="text-center" style="width: 50px;">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                      </div>
                    </th>
                    <th>No. Antrian</th>
                    <th>Lembaga & Pemohon</th>
                    <th>Jenis Perizinan</th>
                    <th>Tanggal Disetujui</th>
                    <th class="text-right px-4">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($perizinans as $perizinan)
                    <tr>
                      <td class="text-center align-middle">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="check-{{ $perizinan->id }}">
                          <label class="custom-control-label" for="check-{{ $perizinan->id }}"></label>
                        </div>
                      </td>
                      <td class="align-middle font-weight-bold text-dark">#REQ-{{ $perizinan->id }}</td>
                      <td class="align-middle">
                        <div class="d-flex align-items-center">
                          <div
                            class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-center mr-3 font-weight-bold"
                            style="width: 35px; height: 35px; background-color: rgba(0,123,255,0.1);">
                            {{ strtoupper(substr($perizinan->lembaga->nama_lembaga, 0, 1)) }}
                          </div>
                          <div>
                            <div class="font-weight-bold">{{ $perizinan->lembaga->nama_lembaga }}</div>
                            <div class="small text-muted">{{ $perizinan->lembaga->npsn }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-muted">{{ $perizinan->jenisPerizinan->nama_jenis }}</td>
                      <td class="align-middle">{{ $perizinan->updated_at->format('d M Y') }}</td>
                      <td class="text-right align-middle px-4">
                        <button type="button" onclick="confirmFinalisasi({{ $perizinan->id }})"
                          class="btn btn-sm btn-success shadow-sm shadow-success font-weight-bold px-3">
                          <i class="fas fa-certificate mr-1"></i> Generate Sertifikat
                        </button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-5">
                        <i class="fas fa-hourglass-half fa-3x mb-3 text-light"></i>
                        <p class="text-muted font-italic">Tidak ada antrian penerbitan saat ini.</p>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function confirmFinalisasi(id) {
      Swal.fire({
        title: 'Finalisasi Sertifikat?',
        text: "Sertifikat akan dibuat permanen dan status akan berubah menjadi Siap Diambil.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Finalisasi!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return fetch(`/super-admin/penerbitan/${id}/finalisasi`)
            .then(response => {
              if (!response.ok) {
                if (response.status === 403) {
                  throw new Error("Anda tidak memiliki akses untuk melakukan tindakan ini.");
                }
                throw new Error("Gagal menghubungi server.")
              }
              return response.json()
            })
            .catch(error => {
              Swal.showValidationMessage(
                `Request failed: ${error}`
              )
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value && result.value.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: result.value.message,
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              window.location.reload();
            });
          } else if (result.value) {
            Swal.fire('Gagal', result.value.message || 'Terjadi kesalahan', 'error');
          }
        }
      })
    }
  </script>
@endpush