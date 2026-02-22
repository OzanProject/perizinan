@extends('layouts.backend')

@section('title', 'Super Admin Dashboard')

@section('content')
  <!-- Content Header (Page header) - Handled by layout, but title here if needed -->
  <div class="row mb-4">
    <div class="col-sm-12">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 font-weight-bold text-dark">Dashboard Dinas Pendidikan</h1>
        <button class="btn btn-primary shadow-sm">
          <i class="fas fa-download mr-1"></i> Export Laporan
        </button>
      </div>
    </div>
  </div>

  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info shadow">
        <div class="inner">
          <h3>{{ $stats['total_lembaga'] }}</h3>
          <p>Total Lembaga</p>
        </div>
        <div class="icon">
          <i class="fas fa-university"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning shadow">
        <div class="inner">
          <h3>{{ $stats['pending'] }}</h3>
          <p>Pengajuan Pending</p>
        </div>
        <div class="icon">
          <i class="fas fa-clock"></i>
        </div>
        <a href="{{ route('super_admin.perizinan.index') }}" class="small-box-footer">More info <i
            class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success shadow">
        <div class="inner">
          <h3>{{ $stats['disetujui'] }}</h3>
          <p>Izin Disetujui</p>
        </div>
        <div class="icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger shadow">
        <div class="inner">
          <h3>{{ $stats['perbaikan'] }}</h3>
          <p>Perlu Perbaikan</p>
        </div>
        <div class="icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->

  <!-- Main row -->
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary shadow">
        <div class="card-header border-transparent">
          <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-2"></i> Pengajuan Terbaru</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0 table-hover">
              <thead class="bg-light">
                <tr>
                  <th width="50">No</th>
                  <th>Nama Lembaga</th>
                  <th>Jenis Izin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th class="text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pengajuanTerbaru as $index => $perizinan)
                  <tr>
                    <td class="align-middle text-center">{{ $index + 1 }}</td>
                    <td class="align-middle">
                      <div class="d-flex flex-column">
                        <span class="font-weight-bold">{{ $perizinan->lembaga->nama_lembaga }}</span>
                        <small class="text-muted">NPSN: {{ $perizinan->lembaga->npsn }}</small>
                      </div>
                    </td>
                    <td class="align-middle text-sm text-secondary">{{ $perizinan->jenisPerizinan->nama_jenis }}</td>
                    <td class="align-middle text-sm">
                      <span class="text-muted"><i class="far fa-calendar-alt mr-1"></i>
                        {{ $perizinan->created_at->format('d M Y') }}</span>
                    </td>
                    <td class="align-middle">
                      @php
                        $statusEnum = \App\Enums\PerizinanStatus::from($perizinan->status);
                        $statusColor = $statusEnum->color();
                        $bsClasses = [
                          'warning' => 'badge-warning',
                          'success' => 'badge-success',
                          'info' => 'badge-info',
                          'primary' => 'badge-primary',
                          'danger' => 'badge-danger',
                          'secondary' => 'badge-secondary',
                          'dark' => 'badge-dark',
                        ];
                        $badgeClass = $bsClasses[$statusColor] ?? 'badge-secondary';
                      @endphp
                      <span class="badge {{ $badgeClass }} p-2">{{ $statusEnum->label() }}</span>
                    </td>
                    <td class="align-middle text-right">
                      <a href="{{ route('super_admin.perizinan.show', $perizinan->id) }}"
                        class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill">
                        Detail <i class="fas fa-chevron-right ml-1" style="font-size: 10px;"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5 text-muted italic">Belum ada pengajuan terbaru.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix bg-white">
          <a href="{{ route('super_admin.perizinan.index') }}"
            class="btn btn-sm btn-outline-primary float-right rounded-pill px-4">Lihat Semua Pengajuan</a>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection
