@extends('layouts.admin_lembaga')

@section('title', 'Dashboard')

@section('content')
  <div class="container-fluid">
    <!-- Dynamic Stats Row -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm mb-4">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-file-invoice"></i></span>
          <div class="info-box-content">
            <span class="info-box-text font-weight-bold text-uppercase small">Total Pengajuan</span>
            <span class="info-box-number h4 mb-0">{{ number_format($stats['total_pengajuan']) }}</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm mb-4">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock text-white"></i></span>
          <div class="info-box-content">
            <span class="info-box-text font-weight-bold text-uppercase small">Sedang Proses</span>
            <span class="info-box-number h4 mb-0 text-warning">{{ number_format($stats['sedang_proses']) }}</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box shadow-sm mb-4">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text font-weight-bold text-uppercase small">Disetujui</span>
            <span class="info-box-number h4 mb-0 text-success">{{ number_format($stats['disetujui']) }}</span>
          </div>
        </div>
      </div>
    </div>

    @if(!$perizinan)
      <!-- Empty State: No Applications Yet -->
      <div class="row">
        <div class="col-md-8 mx-auto py-5">
          <div class="card card-outline card-primary shadow text-center p-5">
            <div class="mb-4 text-primary opacity-50">
              <i class="fas fa-file-signature fa-5x"></i>
            </div>
            <div class="mb-4">
              <h3 class="font-weight-bold">Belum Ada Pengajuan Izin</h3>
              <p class="text-muted">Anda belum memiliki riwayat pengajuan izin operasional. Silakan mulai pengajuan baru
                untuk melegalkan operasional lembaga Anda.</p>
            </div>
            <div class="d-flex justify-content-center">
              <a href="{{ route('admin_lembaga.perizinan.create') }}"
                class="btn btn-primary btn-lg shadow font-weight-bold px-5">
                <i class="fas fa-plus-circle mr-2"></i> Buat Pengajuan Sekarang
              </a>
            </div>
          </div>
        </div>
      </div>
    @else
      @php
        $status = $perizinan->status;
        // Step Mapping: diajukan, verifikasi, perbaikan, disetujui, selesai
        $isDiajukan = in_array($status, ['diajukan', 'perbaikan', 'disetujui', 'siap_diambil', 'selesai']);
        $isVerifikasi = in_array($status, ['diajukan', 'perbaikan', 'disetujui', 'siap_diambil', 'selesai']);
        $isPerbaikan = in_array($status, ['perbaikan']);
        $isDisetujui = in_array($status, ['disetujui', 'siap_diambil', 'selesai']);
        $isSelesai = in_array($status, ['selesai', 'disetujui', 'siap_diambil']);

        $steps = [
          ['id' => 'draft', 'label' => 'Diajukan', 'icon' => 'fa-paper-plane'],
          ['id' => 'diajukan', 'label' => 'Verifikasi', 'icon' => 'fa-search'],
          ['id' => 'perbaikan', 'label' => 'Perbaikan', 'icon' => 'fa-edit'],
          ['id' => 'disetujui', 'label' => 'Disetujui', 'icon' => 'fa-check-double'],
          ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'fa-flag-checkered'],
        ];

        // Map current status index
        $currentIndex = 0;
        if ($status == 'diajukan')
          $currentIndex = 1;
        if ($status == 'perbaikan')
          $currentIndex = 2;
        if (in_array($status, ['disetujui', 'siap_diambil']))
          $currentIndex = 3;
        if ($status == 'selesai')
          $currentIndex = 4;
      @endphp

      <!-- Workflow Stepper Card -->
      <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body py-4">
          <div class="d-flex justify-content-between position-relative pb-2">
            <div class="position-absolute w-100" style="height: 2px; background: #e9ecef; top: 20px; z-index: 0;"></div>
            <div class="position-absolute"
              style="height: 2px; background: #007bff; top: 20px; z-index: 0; transition: width 0.5s; width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%;">
            </div>

            @foreach($steps as $i => $step)
              <div class="text-center position-relative" style="z-index: 1; width: 80px;">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center border"
                  style="width: 40px; height: 40px; background: {{ $i <= $currentIndex ? '#007bff' : '#fff' }}; border-color: {{ $i <= $currentIndex ? '#007bff' : '#dee2e6' }} !important;">
                  <i class="fas {{ $i < $currentIndex ? 'fa-check' : $step['icon'] }} {{ $i <= $currentIndex ? 'text-white' : 'text-muted' }}"
                    style="font-size: 14px;"></i>
                </div>
                <div class="mt-2">
                  <span class="d-block font-weight-bold"
                    style="font-size: 11px; color: {{ $i <= $currentIndex ? '#007bff' : '#adb5bd' }}">{{ $step['label'] }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Main Status Display -->
      <div class="row">
        <div class="col-md-12">
          @if($status == 'perbaikan')
            <div class="card card-warning card-outline shadow">
              <div class="card-header">
                <h3 class="card-title font-weight-bold text-warning"><i class="fas fa-exclamation-triangle mr-2"></i> Dokumen
                  Perlu Perbaikan</h3>
              </div>
              <div class="card-body">
                <p class="mb-4">Yth. Admin <strong>{{ Auth::user()->lembaga->nama_lembaga }}</strong>, berdasarkan hasil
                  verifikasi tim Dinas Pendidikan, terdapat beberapa bagian yang belum memenuhi syarat atau perlu kelengkapan
                  tambahan.</p>

                <div class="callout callout-danger bg-light mb-4">
                  <h6 class="font-weight-bold"><i class="fas fa-sticky-note mr-2"></i> Catatan Verifikator:</h6>
                  <p class="mb-0 font-italic">
                    "{{ $perizinan->catatan_verifikator ?? 'Mohon periksa kembali kelengkapan dokumen sesuai petunjuk teknis.' }}"
                  </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin_lembaga.perizinan.edit', $perizinan) }}"
                    class="btn btn-warning shadow-sm font-weight-bold px-4 mb-2 mr-2">
                    <i class="fas fa-edit mr-2"></i> Perbaiki Sekarang
                  </a>
                  <button class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-headset mr-2"></i> Hubungi Verifikator
                  </button>
                </div>
              </div>
            </div>
          @elseif($status == 'disetujui' || $status == 'siap_diambil' || $status == 'selesai')
            <div class="card card-success card-outline shadow">
              <div class="card-header">
                <h3 class="card-title font-weight-bold text-success"><i class="fas fa-check-circle mr-2"></i> Pengajuan
                  Disetujui</h3>
              </div>
              <div class="card-body">
                <p class="mb-4">Selamat! Pengajuan perizinan Anda telah disetujui oleh Dinas Pendidikan.
                  @if($status == 'siap_diambil')
                    Dokumen fisik SK Izin Operasional sudah siap diambil di kantor dinas pada jam kerja.
                  @else
                    Silakan unduh salinan digital dokumen Anda melalui tombol di bawah ini.
                  @endif
                </p>

                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin_lembaga.perizinan.index') }}" class="btn btn-success shadow-sm px-4 mr-3">
                    <i class="fas fa-list mr-2"></i> Lihat Seluruh Pengajuan
                  </a>
                </div>
              </div>
            </div>
          @else
            <div class="card card-info card-outline shadow">
              <div class="card-header">
                <h3 class="card-title font-weight-bold text-info"><i class="fas fa-info-circle mr-2"></i> Status:
                  {{ \App\Enums\PerizinanStatus::from($status)->label() }}
                </h3>
              </div>
              <div class="card-body">
                <p class="mb-4">Permohonan Anda saat ini sedang dalam tahap
                  <strong>{{ \App\Enums\PerizinanStatus::from($status)->label() }}</strong>. Tim kami sedang meninjau
                  kelengkapan berkas Anda secara mendalam.
                </p>

                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('admin_lembaga.perizinan.show', $perizinan) }}"
                    class="btn btn-info shadow-sm font-weight-bold px-4 mr-3">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail Progres
                  </a>
                  <a href="{{ route('admin_lembaga.perizinan.index') }}" class="btn btn-outline-secondary">
                    Lacak Seluruh Riwayat
                  </a>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- Latest Application Details -->
      <div class="card shadow-sm border mb-4">
        <div class="card-header bg-light py-2">
          <h3 class="card-title font-weight-bold small text-muted text-uppercase mb-0">Detil Pengajuan Terakhir</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0">
              <tr>
                <th width="200" class="pl-3 py-2 border-0">Jenis Perizinan</th>
                <td class="py-2 border-0 text-primary font-weight-bold">{{ $perizinan->jenisPerizinan->nama }}</td>
              </tr>
              <tr>
                <th class="pl-3 py-2">Nomor Ajuan</th>
                <td class="py-2 font-family-mono">{{ $perizinan->nomor_ajuan ?? 'DALAM PROSES' }}</td>
              </tr>
              <tr>
                <th class="pl-3 py-2">Tanggal Masuk</th>
                <td class="py-2">{{ $perizinan->created_at->translatedFormat('d F Y') }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection