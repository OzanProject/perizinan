@extends('layouts.backend')

@section('title', 'Kelola Perizinan')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-file-signature mr-2 text-primary"></i> Kelola
          Perizinan</h1>
        <p class="text-muted small mt-1">Pantau dan kelola semua pengajuan perizinan lembaga secara efisien.</p>
      </div>
      <div class="col-sm-6 text-sm-right mt-2 mt-sm-0">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-0 justify-content-sm-end">
            <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perizinan</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Card -->
    <div class="card card-outline card-primary shadow-sm border-0">
      <div class="card-header bg-white py-3">
        <h3 class="card-title font-weight-bold text-dark">
          <i class="fas fa-list-ul mr-2 text-primary"></i> Daftar Pengajuan
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <!-- Advanced Filter Form -->
        <form method="GET" action="{{ route('super_admin.perizinan.index') }}" class="mb-4">
          <div class="row align-items-end">
            <div class="col-lg-4 col-md-6 mb-3">
              <label class="small font-weight-bold text-muted uppercase">Cari Data</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-light border-right-0"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-left-0"
                  placeholder="Cari ID, Lembaga, atau NPSN...">
              </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
              <label class="small font-weight-bold text-muted uppercase">Rentang Tanggal</label>
              <div class="input-group">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                  class="form-control form-control-sm pr-1" title="Mulai">
                <div class="input-group-append input-group-prepend">
                  <span class="input-group-text px-1 border-0 bg-transparent text-muted"><i
                      class="fas fa-arrow-right"></i></span>
                </div>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                  class="form-control form-control-sm pl-1" title="Selesai">
              </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
              <label class="small font-weight-bold text-muted uppercase">Status</label>
              <select name="status" class="form-control custom-select shadow-none">
                <option value="">Semua Status</option>
                @foreach(\App\Enums\PerizinanStatus::cases() as $status)
                  <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-2 col-md-6 mb-3">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                <i class="fas fa-search mr-1"></i> Telusuri
              </button>
            </div>
          </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted uppercase small font-weight-bold">
              <tr>
                <th width="50" class="text-center py-3">No</th>
                <th class="py-3">Lembaga</th>
                <th class="py-3">Jenis Izin</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3">Nomor Surat</th>
                <th width="200" class="text-right py-3 pr-4">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($perizinans as $index => $p)
                <tr>
                  <td class="text-center text-muted">{{ $perizinans->firstItem() + $index }}</td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="font-weight-bold text-dark">{{ $p->lembaga->nama_lembaga }}</span>
                      <small class="text-muted"><i class="fas fa-fingerprint mr-1"></i>NPSN: {{ $p->lembaga->npsn }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="text-sm text-secondary">{{ $p->jenisPerizinan->nama }}</span>
                  </td>
                  <td class="text-center">
                    @php
                      $statusEnum = \App\Enums\PerizinanStatus::from($p->status);
                      $statusColor = $statusEnum->color();
                      $badgeClass = match ($statusColor) {
                        'warning' => 'badge-warning',
                        'success' => 'badge-success',
                        'info' => 'badge-info',
                        'primary' => 'badge-primary',
                        'danger' => 'badge-danger',
                        'dark' => 'badge-dark',
                        default => 'badge-secondary',
                      };
                    @endphp
                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill font-weight-bold shadow-sm"
                      style="font-size: 11px;">
                      {{ $statusEnum->label() }}
                    </span>
                  </td>
                  <td class="font-family-mono text-sm">
                    @if($p->nomor_surat)
                      <span class="text-dark"><i class="fas fa-hashtag mr-1 text-muted"></i>{{ $p->nomor_surat }}</span>
                    @else
                      <span class="text-muted italic small">Belum terbit</span>
                    @endif
                  </td>
                  <td class="text-right pr-4">
                    <div class="d-flex justify-content-end align-items-center">
                      @if($p->status == \App\Enums\PerizinanStatus::DIAJUKAN->value)
                        <form action="{{ route('super_admin.perizinan.approve', $p) }}" method="POST" class="mr-1">
                          @csrf
                          <button class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm" title="Setujui">
                            <i class="fas fa-check mr-1"></i> Setujui
                          </button>
                        </form>
                      @endif

                      @php $canFinalize = in_array($p->status, [\App\Enums\PerizinanStatus::DISETUJUI->value, \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, \App\Enums\PerizinanStatus::SELESAI->value]); @endphp
                      @if($canFinalize)
                        <a href="{{ route('super_admin.perizinan.finalisasi', $p) }}"
                          class="btn btn-sm btn-info rounded-pill px-3 shadow-sm mr-1" title="Finalisasi Template">
                          <i class="fas fa-edit mr-1"></i> Surat
                        </a>
                      @endif

                      <a href="{{ route('super_admin.perizinan.show', $p) }}"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm mr-1" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center">
                      <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                      <p class="text-muted">Tidak ada data pengajuan yang ditemukan.</p>
                      @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('super_admin.perizinan.index') }}" class="btn btn-link btn-sm">Reset Filter</a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Card Footer with Smart Pagination -->
      <div class="card-footer bg-white border-top-0 py-3">
        <div class="row align-items-center">
          <div class="col-md-6 mb-3 mb-md-0 text-center text-md-left">
            <span class="small text-muted font-weight-bold">
              Menampilkan {{ $perizinans->firstItem() ?? 0 }} - {{ $perizinans->lastItem() ?? 0 }} dari
              {{ $perizinans->total() }} data
            </span>
          </div>
          <div class="col-md-6">
            <div class="float-md-right d-flex justify-content-center">
              {{ $perizinans->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .table td {
      vertical-align: middle;
    }

    .badge {
      letter-spacing: 0.5px;
    }

    .hover-row:hover {
      background-color: rgba(0, 123, 255, 0.02);
    }

    @media (max-width: 768px) {
      .btn-sm {
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
      }

      .badge {
        padding: 0.3rem 0.6rem !important;
      }
    }
  </style>
@endsection