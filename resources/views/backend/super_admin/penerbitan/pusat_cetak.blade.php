@extends('layouts.backend')

@section('title', 'Pusat Cetak Sertifikat')

@push('styles')
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
  .badge-soft-success { background-color: #d1e7dd; color: #0f5132; font-weight: 600; }
  .badge-soft-info { background-color: #cff4fc; color: #055160; font-weight: 600; }
  .badge-soft-dark { background-color: #e2e3e5; color: #41464b; font-weight: 600; }
  .badge-soft-secondary { background-color: #e9ecef; color: #495057; font-weight: 600; }
  
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
  
  /* Premium Stat Cards */
  .stat-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
  }
  .stat-card:hover { transform: translateY(-3px); }
</style>
@endpush

@section('content')

  <!-- Main Content -->
  <div class="content pb-4 text-dark">
    <div class="container-fluid">
      
      <!-- Header -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 mt-2">
        <div>
          <h2 class="h4 font-weight-bold mb-1 text-dark">Pusat Cetak Dokumen</h2>
          <p class="text-muted mb-0">Cetak dokumen sertifikat dan perizinan yang telah disetujui.</p>
        </div>
      </div>



      <!-- Statistik Cards -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card stat-card bg-danger text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
              <div>
                <h2 class="font-weight-bold mb-0">{{ $totalCount }}</h2>
                <p class="mb-0 opacity-75">Siap Cetak PDF</p>
              </div>
              <i class="fas fa-file-pdf fa-3x opacity-50"></i>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
              <div>
                <h2 class="font-weight-bold mb-0">{{ $totalCount }}</h2>
                <p class="mb-0 opacity-75">Siap Cetak Word</p>
              </div>
              <i class="fas fa-file-word fa-3x opacity-50"></i>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card stat-card bg-success text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
              <div>
                <h2 class="font-weight-bold mb-0">{{ $totalCount }}</h2>
                <p class="mb-0 opacity-75">Siap Cetak Excel</p>
              </div>
              <i class="fas fa-file-excel fa-3x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Data -->
      <div class="card premium-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <div class="icon-wrapper primary mr-3">
              <i class="fas fa-print"></i>
            </div>
            <div>
              <h5 class="mb-0 font-weight-bold text-dark">Daftar Dokumen Siap Cetak</h5>
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <!-- Filter Form -->
          <div class="bg-light p-3 border-bottom">
            <form method="GET" class="row align-items-center m-0">
              <div class="col-md-4 mb-2 mb-md-0">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari ID atau Nama Lembaga">
              </div>

              <div class="col-md-3 mb-2 mb-md-0">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
              </div>

              <div class="col-md-3 mb-2 mb-md-0">
                <select name="status" class="form-control">
                  <option value="">Semua Status</option>
                  @foreach(\App\Enums\PerizinanStatus::cases() as $status)
                    @if(in_array($status->value, ['disetujui', 'siap_diambil', 'selesai']))
                      <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                      </option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <button class="btn btn-primary btn-block btn-action">
                  <i class="fas fa-filter mr-1"></i> Filter
                </button>
              </div>
            </form>
          </div>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-premium mb-0">
              <thead>
                <tr>
                  <th style="width: 60px; padding-left: 1.5rem;">ID</th>
                  <th style="min-width: 250px;">Nama Lembaga</th>
                  <th style="min-width: 200px;">Jenis Izin</th>
                  <th style="min-width: 130px;">Tgl Disetujui</th>
                  <th class="text-center" style="min-width: 120px;">Status</th>
                  <th class="text-right" style="min-width: 350px; padding-right: 1.5rem;">Aksi & Cetak Dokumen</th>
                </tr>
              </thead>
              <tbody>
                @forelse($perizinans as $perizinan)
                  <tr>
                    <td class="text-muted font-weight-bold" style="padding-left: 1.5rem;">#{{ $perizinan->id }}</td>
                    <td>
                      <div class="d-flex flex-column">
                        <strong class="text-dark mb-1">{{ $perizinan->lembaga->nama_lembaga ?? $perizinan->lembaga->nama ?? '-' }}</strong>
                        <small class="text-muted"><span class="badge badge-light border text-primary px-2 py-1">NPSN: {{ $perizinan->lembaga->npsn ?? '-' }}</span></small>
                      </div>
                    </td>
                    <td class="text-dark font-weight-bold">{{ $perizinan->jenisPerizinan->nama ?? '-' }}</td>
                    <td>
                      <span class="badge badge-light border px-3 py-2 text-dark font-weight-bold rounded" style="font-size: 0.85rem;">
                        <i class="far fa-calendar-alt mr-1 text-info"></i> {{ $perizinan->approved_at ? $perizinan->approved_at->format('d M Y') : '-' }}
                      </span>
                    </td>
                    <td class="text-center">
                      @php
                        $status = \App\Enums\PerizinanStatus::from($perizinan->status);
                        $badgeClass = match ($status->value) {
                          'disetujui' => 'badge-soft-success',
                          'siap_diambil' => 'badge-soft-info',
                          'selesai' => 'badge-soft-dark',
                          default => 'badge-soft-secondary'
                        };
                      @endphp
                      <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                        <i class="fas fa-circle mr-1" style="font-size: 0.5rem; vertical-align: middle;"></i> {{ $status->label() }}
                      </span>
                    </td>
                    <td class="text-right" style="padding-right: 1.5rem;">
                      @php
                        // Ukuran & orientasi diambil OTOMATIS dari Jenis Perizinan (sumber kebenaran tunggal)
                        // Fallback ke global preset jika jenis perizinan belum dikonfigurasi
                        $finalSize   = strtoupper($perizinan->jenisPerizinan->paper_size ?? $activePreset->paper_size ?? 'A4');
                        $finalOrient = strtolower($perizinan->jenisPerizinan->orientation ?? $activePreset->orientation ?? 'portrait');
                        $isFromJP    = !empty($perizinan->jenisPerizinan->paper_size);

                        $orientIcon  = $finalOrient === 'landscape' ? 'fa-mobile-alt fa-rotate-90' : 'fa-file-alt';
                        $orientLabel = $finalOrient === 'landscape' ? 'Landscape' : 'Portrait';
                        $sizeColor   = $finalSize === 'F4' ? '#7c3aed' : '#0d6efd';
                      @endphp

                      <div class="d-flex align-items-center justify-content-end" style="gap: 8px;">

                        {{-- Badge ukuran kertas (read-only, dari Jenis Perizinan) --}}
                        <div class="d-flex align-items-center border rounded px-2 py-1"
                             style="background: #f8f9fa; font-size: 0.78rem; gap: 6px;"
                             title="{{ $isFromJP ? 'Otomatis dari pengaturan Jenis Perizinan' : 'Dari Preset Global (Jenis Perizinan belum dikonfigurasi)' }}">
                          <i class="fas {{ $orientIcon }}" style="color: {{ $sizeColor }}; font-size: 0.75rem;"></i>
                          <strong style="color: {{ $sizeColor }};">{{ $finalSize }}</strong>
                          <span class="text-muted">·</span>
                          <span class="text-dark">{{ $orientLabel }}</span>
                          @if($isFromJP)
                            <span class="badge badge-soft-success px-1 py-0" style="font-size: 0.65rem;">Auto</span>
                          @else
                            <span class="badge badge-soft-secondary px-1 py-0" style="font-size: 0.65rem;">Preset</span>
                          @endif
                        </div>

                        {{-- Tombol Cetak Browser --}}
                        <a href="{{ route('super_admin.penerbitan.print_html', $perizinan) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-dark btn-action"
                           title="Cetak via Browser (ukuran: {{ $finalSize }} {{ $orientLabel }})">
                          <i class="fas fa-print mr-1"></i> Cetak
                        </a>

                        {{-- Tombol PDF --}}
                        <a href="{{ route('super_admin.penerbitan.export_pdf', $perizinan) }}"
                           class="btn btn-sm btn-outline-danger btn-action"
                           title="Download PDF (ukuran: {{ $finalSize }} {{ $orientLabel }})">
                          <i class="fas fa-file-pdf mr-1"></i> PDF
                        </a>

                        {{-- Tombol Word --}}
                        <a href="{{ route('super_admin.penerbitan.export_word', $perizinan) }}"
                           class="btn btn-sm btn-outline-primary btn-action"
                           title="Download Word">
                          <i class="fas fa-file-word mr-1"></i> Word
                        </a>

                        {{-- Tombol Excel --}}
                        <a href="{{ route('super_admin.penerbitan.export_excel', $perizinan) }}"
                           class="btn btn-sm btn-outline-success btn-action"
                           title="Download Excel">
                          <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <div class="d-flex flex-column align-items-center py-4">
                        <div class="icon-wrapper mb-3" style="width: 64px; height: 64px; background: #f8f9fa;">
                          <i class="fas fa-inbox fa-2x text-muted"></i>
                        </div>
                        <h6 class="font-weight-bold text-dark">Belum ada dokumen siap cetak</h6>
                        <p class="text-muted font-italic mb-0">Dokumen yang disetujui akan muncul di sini.</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if($perizinans->hasPages())
          <div class="card-footer bg-white border-top py-3 px-4">
            <div class="d-flex justify-content-center m-0">
              {{ $perizinans->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
          </div>
        @endif
      </div>

    </div>
  </div>

@endsection