@extends('layouts.backend')

@section('title', 'Pusat Cetak Sertifikat')

@section('content')

  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Pusat Cetak Sertifikat</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="{{ route('super_admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Pusat Cetak</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid">

      <!-- Statistik Cards -->
      <div class="row">
        <div class="col-md-4">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $totalCount }}</h3>
              <p>Siap Cetak PDF</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-pdf"></i>
            </div>
            <a href="#" class="small-box-footer">
              Download Semua PDF <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>{{ $totalCount }}</h3>
              <p>Siap Cetak Word</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-word"></i>
            </div>
            <a href="#" class="small-box-footer">
              Download Semua Word <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $totalCount }}</h3>
              <p>Siap Cetak Excel</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-excel"></i>
            </div>
            <a href="#" class="small-box-footer">
              Download Semua Excel <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Card Data -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Dokumen Siap Cetak</h3>
        </div>

        <div class="card-body">

          <!-- Filter Form -->
          <form method="GET" class="form-row mb-4">
            <div class="col-md-4">
              <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Cari ID atau Lembaga">
            </div>

            <div class="col-md-3">
              <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>

            <div class="col-md-3">
              <select name="status" class="form-control">
                <option value="">Semua Status</option>
                @foreach(\App\Enums\PerizinanStatus::cases() as $status)
                  @if(
                      in_array($status->value, [
                        \App\Enums\PerizinanStatus::DISETUJUI->value,
                        \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value,
                        \App\Enums\PerizinanStatus::SELESAI->value
                      ])
                    )
                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                      {{ $status->label() }}
                    </option>
                  @endif
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <button class="btn btn-primary btn-block">
                <i class="fas fa-filter"></i> Filter
              </button>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th width="60">ID</th>
                  <th>Nama Pemohon / Perusahaan</th>
                  <th>Jenis Izin</th>
                  <th>Tgl Disetujui</th>
                  <th width="150">Status</th>
                  <th width="150" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($perizinans as $perizinan)
                            <tr>
                              <td>#{{ $perizinan->id }}</td>

                              <td>
                                <strong>{{ $perizinan->lembaga->nama }}</strong><br>
                                <small>{{ $perizinan->lembaga->npsn }}</small>
                              </td>

                              <td>
                                {{ $perizinan->jenisPerizinan->nama }}
                              </td>

                              <td>
                                {{ $perizinan->approved_at
                  ? $perizinan->approved_at->format('d M Y')
                  : '-' }}
                              </td>

                              <td>
                                @php
                                  $status = \App\Enums\PerizinanStatus::from($perizinan->status);

                                  $badgeClass = match ($status->value) {
                                    'disetujui' => 'badge-success',
                                    'siap_diambil' => 'badge-info',
                                    'selesai' => 'badge-dark',
                                    default => 'badge-secondary'
                                  };
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                  {{ $status->label() }}
                                </span>
                              </td>

                              <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="openExportModal({{ $perizinan->id }})"
                                  title="Export PDF">
                                  <i class="fas fa-file-pdf"></i>
                                </button>

                                <button class="btn btn-primary btn-sm" onclick="alert('Fitur Word segera hadir')" title="Export Word">
                                  <i class="fas fa-file-word"></i>
                                </button>

                                <button class="btn btn-success btn-sm" onclick="alert('Fitur Excel segera hadir')"
                                  title="Export Excel">
                                  <i class="fas fa-file-excel"></i>
                                </button>
                              </td>
                            </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted">
                      Belum ada dokumen siap cetak.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-3">
            {{ $perizinans->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- Modal Export -->
  <div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Preview Sertifikat</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body text-center">
          <div id="modal-content-area">
            <p class="text-muted">Memuat preview...</p>
          </div>
        </div>

        <div class="modal-footer">
          <button id="downloadPdfBtn" class="btn btn-primary">
            <i class="fas fa-download"></i> Download PDF
          </button>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Tutup
          </button>
        </div>

      </div>
    </div>
  </div>

@endsection


@push('scripts')
  <script>
    function openExportModal(id) {
      $('#exportModal').modal('show');

      $('#modal-content-area').html(
        '<p class="text-muted">Memuat preview...</p>'
      );

      fetch(`/super-admin/penerbitan/${id}/preview`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            $('#modal-content-area').html(data.html);
          } else {
            $('#modal-content-area').html(
              '<p class="text-danger">Gagal memuat preview.</p>'
            );
          }
        })
        .catch(() => {
          $('#modal-content-area').html(
            '<p class="text-danger">Terjadi kesalahan sistem.</p>'
          );
        });

      $('#downloadPdfBtn')
        .off('click')
        .on('click', function () {
          window.location.href =
            `/super-admin/penerbitan/${id}/export-pdf`;
        });
    }
  </script>
@endpush