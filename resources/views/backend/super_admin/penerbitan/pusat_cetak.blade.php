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

      <!-- Preset Info Banner -->
      @if($activePreset)
        <div class="callout callout-info">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1"><i class="fas fa-sliders-h mr-2"></i> Preset Aktif:
                <strong>{{ $activePreset->nama }}</strong></h5>
              <p class="mb-0 text-muted">
                Kertas: <strong>{{ strtoupper($activePreset->paper_size) }}</strong> |
                Orientasi: <strong>{{ ucfirst($activePreset->orientation) }}</strong> |
                Margin: <strong>{{ $activePreset->margin_top }}mm / {{ $activePreset->margin_right }}mm /
                  {{ $activePreset->margin_bottom }}mm / {{ $activePreset->margin_left }}mm</strong>
                <small class="text-muted">(atas / kanan / bawah / kiri)</small>
              </p>
            </div>
            <a href="{{ route('super_admin.penerbitan.preset.index') }}" class="btn btn-sm btn-outline-info">
              <i class="fas fa-cog mr-1"></i> Kelola Preset
            </a>
          </div>
        </div>
      @else
        <div class="callout callout-warning">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Belum Ada Preset Aktif</h5>
              <p class="mb-0 text-muted">Cetak akan menggunakan pengaturan default (A4, Portrait). Buat preset untuk
                kustomisasi.</p>
            </div>
            <a href="{{ route('super_admin.penerbitan.preset.index') }}" class="btn btn-sm btn-warning">
              <i class="fas fa-plus mr-1"></i> Buat Preset
            </a>
          </div>
        </div>
      @endif

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
            <a href="#" class="small-box-footer">&nbsp;</a>
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
            <a href="#" class="small-box-footer">&nbsp;</a>
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
            <a href="#" class="small-box-footer">&nbsp;</a>
          </div>
        </div>
      </div>

      <!-- Card Data -->
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-print mr-2"></i> Daftar Dokumen Siap Cetak</h3>
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
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead class="bg-light">
                <tr>
                  <th width="60">ID</th>
                  <th>Nama Lembaga</th>
                  <th>Jenis Izin</th>
                  <th>Tgl Disetujui</th>
                  <th width="120" class="text-center">Status</th>
                  <th width="200" class="text-center">Aksi Cetak</th>
                </tr>
              </thead>
              <tbody>
                @forelse($perizinans as $perizinan)
                            <tr>
                              <td>#{{ $perizinan->id }}</td>
                              <td>
                                <strong>{{ $perizinan->lembaga->nama_lembaga ?? $perizinan->lembaga->nama ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $perizinan->lembaga->npsn ?? '-' }}</small>
                              </td>
                              <td>{{ $perizinan->jenisPerizinan->nama ?? '-' }}</td>
                              <td>
                                {{ $perizinan->approved_at
                  ? $perizinan->approved_at->format('d M Y')
                  : '-' }}
                              </td>
                              <td class="text-center">
                                @php
                                  $status = \App\Enums\PerizinanStatus::from($perizinan->status);
                                  $badgeClass = match ($status->value) {
                                    'disetujui' => 'badge-success',
                                    'siap_diambil' => 'badge-info',
                                    'selesai' => 'badge-dark',
                                    default => 'badge-secondary'
                                  };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $status->label() }}</span>
                              </td>
                              <td class="text-center" style="white-space: nowrap;">
                                <a href="{{ route('super_admin.penerbitan.export_pdf', $perizinan) }}"
                                  class="btn btn-xs btn-danger mr-1" title="Download PDF">
                                  <i class="fas fa-file-pdf mr-1"></i> PDF
                                </a>
                                <a href="{{ route('super_admin.penerbitan.export_word', $perizinan) }}"
                                  class="btn btn-xs btn-primary mr-1" title="Download Word">
                                  <i class="fas fa-file-word mr-1"></i> Word
                                </a>
                                <a href="{{ route('super_admin.penerbitan.export_excel', $perizinan) }}"
                                  class="btn btn-xs btn-success" title="Download Excel">
                                  <i class="fas fa-file-excel mr-1"></i> Excel
                                </a>
                              </td>
                            </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                      <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
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

@endsection