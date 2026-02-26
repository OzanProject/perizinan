@extends('layouts.backend')

@section('title', 'Laporan & Statistik')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-chart-line mr-2 text-primary"></i> Laporan & Statistik
        </h1>
        <p class="text-muted small mt-1">Monitoring data pengajuan izin lintas lembaga secara real-time.</p>
      </div>
      <div class="col-sm-6 text-right">
        <div class="btn btn-white shadow-sm btn-sm font-weight-bold">
          <i class="far fa-calendar-alt mr-2 text-primary"></i> {{ now()->translatedFormat('d F Y') }}
        </div>
      </div>
    </div>

    <!-- Summary Widgets (AdminLTE Small Boxes) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-primary shadow-sm">
          <div class="inner">
            <h3>{{ number_format($stats['total']) }}</h3>
            <p class="font-weight-bold text-uppercase small">Total Pengajuan</p>
          </div>
          <div class="icon">
            <i class="fas fa-file-alt"></i>
          </div>
          <a href="{{ route('super_admin.perizinan.index') }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right ml-1"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm">
          <div class="inner">
            <h3>{{ number_format($stats['approved']) }}</h3>
            <p class="font-weight-bold text-uppercase small">Disetujui</p>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <a href="{{ route('super_admin.perizinan.index', ['status' => 'disetujui']) }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right ml-1"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger shadow-sm">
          <div class="inner">
            <h3>{{ number_format($stats['rejected']) }}</h3>
            <p class="font-weight-bold text-uppercase small">Ditolak</p>
          </div>
          <div class="icon">
            <i class="fas fa-times-circle"></i>
          </div>
          <a href="{{ route('super_admin.perizinan.index', ['status' => 'ditolak']) }}" class="small-box-footer">
            Selengkapnya <i class="fas fa-arrow-circle-right ml-1"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm">
          <div class="inner">
            <h3>{{ number_format($stats['processing']) }}</h3>
            <p class="font-weight-bold text-uppercase small text-dark">Sedang Diproses</p>
          </div>
          <div class="icon">
            <i class="fas fa-sync-alt"></i>
          </div>
          <a href="{{ route('super_admin.perizinan.index', ['status' => 'diajukan']) }}"
            class="small-box-footer text-dark">
            Selengkapnya <i class="fas fa-arrow-circle-right ml-1 text-dark"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Filter Card -->
      <div class="col-lg-4">
        <div class="card card-outline card-primary shadow-sm border-0">
          <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold"><i class="fas fa-filter mr-2 text-primary"></i> Filter Laporan</h3>
          </div>
          <div class="card-body">
            <form action="{{ route('super_admin.laporan.index') }}" method="GET">
              <div class="form-group">
                <label class="small font-weight-bold text-muted text-uppercase">Tanggal Mulai</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar"></i></span>
                  </div>
                  <input name="start_date" value="{{ request('start_date') }}" class="form-control border-left-0"
                    type="date" />
                </div>
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-muted text-uppercase">Tanggal Selesai</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar"></i></span>
                  </div>
                  <input name="end_date" value="{{ request('end_date') }}" class="form-control border-left-0"
                    type="date" />
                </div>
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-muted text-uppercase">Lembaga</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-school"></i></span>
                  </div>
                  <select name="lembaga_id" class="form-control border-left-0 custom-select">
                    <option value="">Semua Lembaga</option>
                    @foreach($listLembaga as $l)
                      <option value="{{ $l->id }}" {{ request('lembaga_id') == $l->id ? 'selected' : '' }}>
                        {{ $l->nama_lembaga }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm mt-4">
                <i class="fas fa-search mr-2"></i> Terapkan Filter
              </button>
              @if(request()->anyFilled(['start_date', 'end_date', 'lembaga_id']))
                <a href="{{ route('super_admin.laporan.index') }}"
                  class="btn btn-default btn-block btn-sm mt-2 font-weight-bold">
                  <i class="fas fa-undo mr-1"></i> Reset Filter
                </a>
              @endif
            </form>
          </div>
        </div>
      </div>

      <!-- Chart Card -->
      <div class="col-lg-8">
        <div class="card card-outline card-primary shadow-sm border-0 h-100">
          <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold"><i class="fas fa-chart-bar mr-2 text-primary"></i> Tren Pengajuan
              Bulanan</h3>
          </div>
          <div class="card-body pb-0">
            <div class="chart-container d-flex align-items-end justify-content-between h-100"
              style="min-height: 250px; padding-bottom: 30px;">
              @php $maxTrend = $monthlyTrend->max() ?: 1; @endphp
              @foreach($monthlyTrend as $month => $total)
                <div class="flex-grow-1 text-center px-1" title="{{ $total }} Izin">
                  <div class="bg-primary rounded-top mx-auto hover-opacity shadow-sm"
                    style="height: {{ ($total / $maxTrend) * 200 }}px; width: 80%; max-width: 40px; transition: height 0.3s ease-in-out;">
                    <span class="small font-weight-bold text-white d-block pt-1">{{ $total > 0 ? $total : '' }}</span>
                  </div>
                  <div class="small font-weight-bold text-muted mt-2 text-uppercase" style="font-size: 10px;">{{ $month }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="card-footer bg-light border-0 py-2">
            <div class="d-flex justify-content-center small font-weight-bold text-muted">
              <span class="mr-3"><i class="fas fa-square text-primary mr-1"></i> Pengajuan Masuk</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Data Table Card -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card card-outline card-primary shadow-sm border-0">
          <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-table mr-2 text-primary"></i> Statistik
              Aktivitas Lembaga</h3>
            <div class="card-tools">
              <a href="{{ route('super_admin.laporan.export_excel', request()->query()) }}"
                class="btn btn-success btn-sm shadow-sm font-weight-bold mr-2">
                <i class="fas fa-file-excel mr-1"></i> Excel
              </a>
              <a href="{{ route('super_admin.laporan.export_pdf', request()->query()) }}"
                class="btn btn-danger btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-file-pdf mr-1"></i> PDF
              </a>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="bg-light">
                  <tr>
                    <th class="border-top-0 px-4 text-center" style="width: 50px;">No</th>
                    <th class="border-top-0">Nama Lembaga</th>
                    <th class="border-top-0 text-center">Total Pengajuan</th>
                    <th class="border-top-0 text-center">Selesai</th>
                    <th class="border-top-0 text-center">Proses</th>
                    <th class="border-top-0" style="width: 250px;">Kelulusan (%)</th>
                    <th class="border-top-0 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lembagaStats as $index => $lembaga)
                    @php
                      $persentase = $lembaga->total_pengajuan > 0 ? round(($lembaga->selesai / $lembaga->total_pengajuan) * 100, 1) : 0;
                    @endphp
                    <tr>
                      <td class="text-center">{{ $lembagaStats->firstItem() + $index }}</td>
                      <td>
                        <div class="font-weight-bold">{{ $lembaga->nama_lembaga }}</div>
                        <div class="text-xs text-muted font-weight-bold uppercase">{{ $lembaga->jenjang }}</div>
                      </td>
                      <td class="text-center font-weight-bold">{{ number_format($lembaga->total_pengajuan) }}</td>
                      <td class="text-center">
                        <span class="badge badge-success px-2 py-1 rounded-pill">{{ $lembaga->selesai }}</span>
                      </td>
                      <td class="text-center">
                        <span class="badge badge-warning px-2 py-1 rounded-pill">{{ $lembaga->proses }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="progress progress-sm shadow-sm" style="height: 10px; border-radius: 10px;">
                          <div
                            class="progress-bar {{ $persentase > 50 ? 'bg-success' : 'bg-danger' }} progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: {{ $persentase }}%" aria-valuenow="{{ $persentase }}"
                            aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                        <div
                          class="text-right small font-weight-bold mt-1 {{ $persentase > 50 ? 'text-success' : 'text-danger' }}">
                          {{ $persentase }}%
                        </div>
                      </td>
                      <td class="text-center">
                        <a href="{{ route('super_admin.perizinan.index', ['search' => $lembaga->nama_lembaga]) }}"
                          class="btn btn-primary btn-sm rounded-circle shadow-sm" title="Lihat Detail">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @if($lembagaStats->hasPages())
            <div class="card-footer bg-white py-2">
              <div class="float-right">
                {{ $lembagaStats->links() }}
              </div>
            </div>
          @endif
        </div>
      </div>
      <!-- Detailed Submissions Table -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center">
              <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-list mr-2 text-info"></i> Daftar
                Pengajuan
                Terperinci</h3>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th class="px-4 text-center" style="width: 50px;">No</th>
                      <th>Nomor Ajuan</th>
                      <th>Lembaga</th>
                      <th>Jenis Perizinan</th>
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">Status</th>
                      <th class="text-right px-4">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($perizinans as $index => $item)
                      <tr>
                        <td class="text-center">{{ $perizinans->firstItem() + $index }}</td>
                        <td class="font-family-mono small font-weight-bold">{{ $item->nomor_ajuan ?? '-' }}</td>
                        <td>{{ $item->lembaga ? $item->lembaga->nama_lembaga : '-' }}</td>
                        <td>{{ $item->jenisPerizinan ? $item->jenisPerizinan->nama : '-' }}</td>
                        <td class="text-center small">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                          <span
                            class="badge badge-{{ \App\Enums\PerizinanStatus::from($item->status)->color() }} px-2 py-1">
                            {{ \App\Enums\PerizinanStatus::from($item->status)->label() }}
                          </span>
                        </td>
                        <td class="text-right px-4">
                          <a href="{{ route('super_admin.perizinan.show', $item) }}" class="btn btn-xs btn-info shadow-sm">
                            <i class="fas fa-eye mr-1"></i> Detail
                          </a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Tidak ada data untuk filter ini.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
            @if($perizinans->hasPages())
              <div class="card-footer bg-white">
                <div class="float-right">
                  {{ $perizinans->appends(request()->query())->links() }}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .hover-opacity:hover {
      opacity: 0.8 !important;
    }

    .progress {
      background-color: #f4f6f9;
    }
  </style>
@endsection