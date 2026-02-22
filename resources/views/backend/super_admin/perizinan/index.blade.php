@extends('layouts.backend')

@section('title', 'Kelola Perizinan')

@section('content')
  <div class="row mb-4">
    <div class="col-sm-12">
      <h1 class="h3 font-weight-bold text-dark">Daftar Pengajuan Perizinan</h1>
    </div>
  </div>

  <div class="card card-outline card-primary shadow">
    <div class="card-header">
      <h3 class="card-title font-weight-bold">
        <i class="fas fa-list mr-2"></i> Semua Data Pengajuan
      </h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover m-0">
          <thead class="bg-light">
            <tr>
              <th width="50">No</th>
              <th>Lembaga</th>
              <th>Jenis Izin</th>
              <th>Status</th>
              <th>Nomor Surat</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($perizinans as $index => $p)
              <tr>
                <td class="align-middle">{{ $index + 1 }}</td>
                <td class="align-middle">
                  <div class="d-flex flex-column">
                    <span class="font-weight-bold">{{ $p->lembaga->nama_lembaga }}</span>
                    <small class="text-muted">NPSN: {{ $p->lembaga->npsn }}</small>
                  </div>
                </td>
                <td class="align-middle text-sm text-secondary">{{ $p->jenisPerizinan->nama }}</td>
                <td class="align-middle">
                  @php
                    $statusEnum = \App\Enums\PerizinanStatus::from($p->status);
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
                <td class="align-middle font-family-mono text-sm">{{ $p->nomor_surat ?? '-' }}</td>
                <td class="align-middle text-right">
                  <div class="d-flex justify-content-end gap-2">
                    @if($p->status == \App\Enums\PerizinanStatus::DIAJUKAN->value)
                      <form action="{{ route('super_admin.perizinan.approve', $p) }}" method="POST" class="mr-1">
                        @csrf
                        <button class="btn btn-success btn-sm shadow-sm">
                          <i class="fas fa-check-circle mr-1"></i> Setujui
                        </button>
                      </form>
                    @endif

                    @php $canFinalizeIndex = in_array($p->status, [\App\Enums\PerizinanStatus::DISETUJUI->value, \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, \App\Enums\PerizinanStatus::SELESAI->value]); @endphp
                    @if($canFinalizeIndex)
                      <a href="{{ route('super_admin.perizinan.finalisasi', $p) }}"
                        class="btn btn-info btn-sm shadow-sm mr-1">
                        <i class="fas fa-certificate mr-1"></i>
                        {{ $p->status == \App\Enums\PerizinanStatus::DISETUJUI->value ? 'Finalisasi' : 'Edit Surat' }}
                      </a>
                    @endif

                    <a href="{{ route('super_admin.perizinan.show', $p) }}" class="btn btn-primary btn-sm shadow-sm">
                      <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix bg-white">
      {{-- Pagination if exists --}}
      @if(method_exists($perizinans, 'links'))
        <div class="float-right">
          {{ $perizinans->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
