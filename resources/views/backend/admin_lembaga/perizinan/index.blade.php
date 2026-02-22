@extends('layouts.admin_lembaga')

@section('title', 'Manajemen Pengajuan')

@section('content')
  <div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4 align-items-end">
      <div class="col-md-8">
        <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Pengajuan</h1>
        <p class="text-muted mb-0">Kelola dan pantau proses izin operasional lembaga Anda.</p>
      </div>
      <div class="col-md-4 text-md-right mt-3 mt-md-0">
        <a href="{{ route('admin_lembaga.perizinan.create') }}" class="btn btn-primary shadow-sm font-weight-bold">
          <i class="fas fa-plus mr-2"></i> Buat Pengajuan Baru
        </a>
      </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="info-box shadow-sm border">
          <span class="info-box-icon bg-primary"><i class="fas fa-folder-open"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold small">TOTAL PENGAJUAN</span>
            <span class="info-box-number h3 text-dark mb-0 font-weight-bold">{{ $stats['total'] }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box shadow-sm border">
          <span class="info-box-icon bg-warning text-white"><i class="fas fa-hourglass-half"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold small">MENUNGGU REVIEW</span>
            <span class="info-box-number h3 text-dark mb-0 font-weight-bold">{{ $stats['pending'] }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box shadow-sm border">
          <span class="info-box-icon bg-success text-white"><i class="fas fa-check-double"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold small">SELESAI</span>
            <span class="info-box-number h3 text-dark mb-0 font-weight-bold">{{ $stats['selesai'] }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
              <a href="{{ route('admin_lembaga.perizinan.index') }}"
                class="btn btn-outline-primary btn-sm rounded-pill px-3 mr-2 mb-2 {{ !request('status') ? 'active' : '' }}">
                Semua
              </a>
              @foreach(['diajukan' => 'Diproses', 'perbaikan' => 'Perbaikan', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin_lembaga.perizinan.index', ['status' => $val]) }}"
                  class="btn btn-outline-primary btn-sm rounded-pill px-3 mr-2 mb-2 {{ request('status') == $val ? 'active' : '' }}">
                  {{ $label }}
                </a>
              @endforeach
            </div>
          </div>
          <div class="col-lg-4">
            <form action="{{ route('admin_lembaga.perizinan.index') }}" method="GET">
              <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}"
                  class="form-control form-control-sm shadow-none" placeholder="Cari ID atau Jenis Izin...">
                <div class="input-group-append">
                  <button class="btn btn-primary btn-sm" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Application List Grid -->
    <div class="row">
      @forelse($perizinans as $perizinan)
        @php
          $statusEnum = \App\Enums\PerizinanStatus::tryFrom($perizinan->status);
          $statusLabel = $statusEnum ? $statusEnum->label() : ucfirst($perizinan->status);
          $statusColor = $statusEnum ? $statusEnum->color() : 'secondary';

          // Progress mapping
          $progress = 0;
          $progressLabel = 'Draft';
          switch ($perizinan->status) {
            case 'draft':
              $progress = 10;
              $progressLabel = 'Menyiapkan Berkas';
              break;
            case 'diajukan':
              $progress = 40;
              $progressLabel = 'Validasi Berkas';
              break;
            case 'perbaikan':
              $progress = 25;
              $progressLabel = 'Revisi Dokumen';
              break;
            case 'disetujui':
              $progress = 85;
              $progressLabel = 'Penerbitan SK';
              break;
            case 'siap_diambil':
              $progress = 95;
              $progressLabel = 'Siap Diambil';
              break;
            case 'selesai':
              $progress = 100;
              $progressLabel = 'Selesai';
              break;
            case 'ditolak':
              $progress = 100;
              $progressLabel = 'Ditolak';
              break;
          }

          $bsClasses = [
            'warning' => 'badge-warning',
            'success' => 'badge-success text-white',
            'info' => 'badge-info',
            'primary' => 'badge-primary',
            'danger' => 'badge-danger',
            'secondary' => 'badge-secondary',
          ];
          $badgeClass = $bsClasses[$statusColor] ?? 'badge-secondary';
          $progressClass = 'bg-' . ($statusColor == 'info' ? 'info' : $statusColor);
        @endphp
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="card h-100 shadow-sm border-0 border-top border-primary" style="border-top-width: 3px !important;">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                  <div class="bg-light p-2 rounded border mr-3 text-primary font-weight-bold"
                    style="width: 40px; text-align: center;">
                    {{ substr($perizinan->lembaga->nama_lembaga ?? 'L', 0, 2) }}
                  </div>
                  <div>
                    <h6 class="mb-0 font-weight-bold text-dark truncate-2" style="max-width: 150px;">
                      {{ $perizinan->lembaga->nama_lembaga }}
                    </h6>
                    <small class="text-muted">{{ $perizinan->lembaga->kecamatan }}</small>
                  </div>
                </div>
                <span class="badge {{ $badgeClass }} px-2 py-1 text-uppercase"
                  style="font-size: 9px;">{{ $statusLabel }}</span>
              </div>

              <div class="mb-3">
                <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block tracking-wider">Tipe
                  Pengajuan</label>
                <h6 class="font-weight-bold mb-1">{{ $perizinan->jenisPerizinan->nama }}</h6>
                <small class="text-muted font-family-mono">ID: #{{ str_pad($perizinan->id, 5, '0', STR_PAD_LEFT) }}</small>
              </div>

              <div class="progress mb-2" style="height: 6px;">
                <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $progress }}%"
                  aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div class="d-flex justify-content-between small">
                <span class="text-muted">Progress: {{ $progress }}%</span>
                <span class="font-weight-bold text-dark">{{ $progressLabel }}</span>
              </div>
            </div>
            <div class="card-footer bg-light border-top-0 d-flex justify-content-between align-items-center py-2">
              <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>
                {{ $perizinan->created_at->format('d M Y') }}</small>
              <div class="btn-group">
                @if(in_array($perizinan->status, ['draft', 'perbaikan']))
                  <form action="{{ route('admin_lembaga.perizinan.destroy', $perizinan) }}" method="POST"
                    onsubmit="return confirm('Hapus draf ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm text-danger font-weight-bold p-0 mr-3">Hapus</button>
                  </form>
                  <a href="{{ route('admin_lembaga.perizinan.edit', $perizinan) }}"
                    class="btn btn-link btn-sm text-primary font-weight-bold p-0">
                    Lanjutkan <i class="fas fa-edit ml-1"></i>
                  </a>
                @else
                  <a href="{{ route('admin_lembaga.perizinan.show', $perizinan) }}"
                    class="btn btn-link btn-sm text-primary font-weight-bold p-0">
                    Detail <i class="fas fa-arrow-right ml-1"></i>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 py-5 text-center">
          <div class="opacity-25 mb-3">
            <i class="fas fa-folder-open fa-4x"></i>
          </div>
          <p class="text-muted font-italic">Belum ada pengajuan perizinan yang sesuai.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($perizinans, 'hasPages') && $perizinans->hasPages())
      <div class="card shadow-sm border-0">
        <div class="card-body py-3">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="text-muted small mb-3 mb-md-0">
              Menampilkan <strong>{{ $perizinans->firstItem() }}</strong> sampai
              <strong>{{ $perizinans->lastItem() }}</strong> dari <strong>{{ $perizinans->total() }}</strong> hasil
            </p>
            <div>
              {{ $perizinans->links() }}
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection