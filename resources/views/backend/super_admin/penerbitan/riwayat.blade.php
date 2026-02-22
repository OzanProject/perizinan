@extends('layouts.backend')

@section('title', 'Riwayat Penerbitan Sertifikat')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-history mr-2 text-primary"></i> Riwayat Penerbitan
        </h1>
        <p class="text-muted small mt-1">Daftar sertifikat yang telah berhasil diterbitkan dan diserahkan ke lembaga.</p>
      </div>
      <div class="col-sm-6 text-right">
        <button type="button" class="btn btn-default btn-sm shadow-sm font-weight-bold">
          <i class="fas fa-download mr-1 text-primary"></i> Export Riwayat
        </button>
      </div>
    </div>

    <!-- Stats Boxes -->
    <div class="row">
      <div class="col-md-6">
        <div class="info-box shadow-sm border-0">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-double"></i></span>
          <div class="info-box-content">
            <span class="info-box-text font-weight-bold text-uppercase small text-muted">Total Sertifikat Terbit</span>
            <span class="info-box-number text-xl">{{ $perizinans->count() }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info-box shadow-sm border-0">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-calendar-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text font-weight-bold text-uppercase small text-muted">Diterbitkan Bulan Ini</span>
            <span
              class="info-box-number text-xl">{{ $perizinans->where('ready_at', '>=', now()->startOfMonth())->count() }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card card-outline card-primary shadow-sm border-0">
      <div class="card-header bg-white py-3">
        <div class="row align-items-center">
          <div class="col-md-4">
            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-list mr-2 text-primary"></i> Daftar Riwayat
            </h3>
          </div>
          <div class="col-md-8">
            <div class="card-tools float-right">
              <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="table_search" class="form-control float-right"
                  placeholder="Cari Nama/No. Sertifikat...">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
                <th class="px-4 text-center" style="width: 50px;">No</th>
                <th>No. Sertifikat</th>
                <th>Lembaga</th>
                <th>Jenis Perizinan</th>
                <th class="text-center">Tanggal Terbit</th>
                <th class="text-center">Status</th>
                <th class="text-right px-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perizinans as $index => $perizinan)
                <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="font-weight-bold text-primary">{{ $perizinan->nomor_surat ?? '-' }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="bg-light rounded-circle text-center font-weight-bold text-primary mr-2 shadow-sm"
                        style="width: 32px; height: 32px; line-height: 32px; font-size: 11px;">
                        {{ strtoupper(substr($perizinan->lembaga->nama, 0, 2)) }}
                      </div>
                      <span class="font-weight-bold">{{ $perizinan->lembaga->nama }}</span>
                    </div>
                  </td>
                  <td><span class="text-muted small font-weight-bold">{{ $perizinan->jenisPerizinan->nama }}</span></td>
                  <td class="text-center">
                    {{ $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('d M Y') : '-' }}</td>
                  <td class="text-center">
                    @php $status = \App\Enums\PerizinanStatus::from($perizinan->status); @endphp
                    <span class="badge badge-{{ $status->color() }} px-3 py-1 rounded-pill">
                      <i class="fas fa-dot-circle mr-1 small"></i> {{ $status->label() }}
                    </span>
                  </td>
                  <td class="text-right px-4">
                    <a href="{{ route('super_admin.perizinan.show', $perizinan) }}"
                      class="btn btn-default btn-xs shadow-sm font-weight-bold px-2">
                      <i class="fas fa-eye mr-1 text-primary"></i> Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="py-5 text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                    Belum ada riwayat penerbitan sertifikat.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection