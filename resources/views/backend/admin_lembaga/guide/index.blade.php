@extends('layouts.admin_lembaga')

@section('title', 'Panduan Sistem')

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-5">
      <div class="col-12">
        <div class="card bg-gradient-primary shadow-lg border-0" style="border-radius: 1.5rem;">
          <div class="card-body p-5">
            <div class="row align-items-center">
              <div class="col-md-2 text-center mb-4 mb-md-0">
                <div
                  class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg"
                  style="width: 100px; height: 100px;">
                  <i class="fas fa-question-circle fa-4x"></i>
                </div>
              </div>
              <div class="col-md-10 text-center text-md-left">
                <h1 class="font-weight-bold text-uppercase italic tracking-tight mb-2">Panduan Penggunaan Sistem</h1>
                <p class="lead mb-0 opacity-75">Selamat datang di portal pelayanan perizinan. Ikuti langkah-langkah di
                  bawah ini untuk memulai pengajuan izin operasional Anda secara digital.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Timeline Content -->
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="timeline">
          <!-- Step 1 -->
          <div class="time-label">
            <span class="bg-primary px-3 shadow-sm">LANGKAH 1</span>
          </div>
          <div>
            <i class="fas fa-university bg-primary shadow-sm"></i>
            <div class="timeline-item shadow-sm border-0 rounded-lg">
              <h3 class="timeline-header font-weight-bold text-primary border-0">Lengkapi Profil Lembaga</h3>
              <div class="timeline-body py-3">
                <p class="mb-0 text-muted">Sebelum melakukan pengajuan, pastikan data institusi Anda (NPSN, Alamat, SK
                  Pendirian) sudah lengkap dan benar pada menu
                  <a href="{{ route('admin_lembaga.profile.index') }}" class="font-weight-bold text-primary">Profil
                    Lembaga</a>.
                  Data ini akan ditarik secara otomatis ke dalam berkas sertifikat untuk memastikan keakuratan informasi.
                </p>
              </div>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="time-label">
            <span class="bg-indigo px-3 shadow-sm" style="background-color: #6610f2 !important;">LANGKAH 2</span>
          </div>
          <div>
            <i class="fas fa-plus-circle bg-indigo shadow-sm" style="background-color: #6610f2 !important;"></i>
            <div class="timeline-item shadow-sm border-0 rounded-lg">
              <h3 class="timeline-header font-weight-bold text-indigo border-0" style="color: #6610f2;">Buat Pengajuan
                Baru</h3>
              <div class="timeline-body py-3">
                <p class="mb-0 text-muted">Klik tombol <span class="badge badge-primary">Ajukan Izin Baru</span> pada
                  dashboard. Pilih jenis perizinan yang sesuai dengan kebutuhan lembaga Anda. Sistem akan memandu Anda
                  melalui beberapa tahapan pengisian data yang intuitif.</p>
              </div>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="time-label">
            <span class="bg-warning px-3 shadow-sm">LANGKAH 3</span>
          </div>
          <div>
            <i class="fas fa-file-upload bg-warning shadow-sm"></i>
            <div class="timeline-item shadow-sm border-0 rounded-lg">
              <h3 class="timeline-header font-weight-bold text-warning border-0">Unggah Dokumen Persyaratan</h3>
              <div class="timeline-body py-3">
                <p class="mb-0 text-muted">Persiapkan dokumen fisik dalam format PDF atau Gambar. Unggah setiap dokumen
                  sesuai dengan kategori yang diminta. Pastikan dokumen terbaca dengan jelas untuk mempercepat proses
                  verifikasi oleh petugas dinas pendidikan.</p>
              </div>
            </div>
          </div>

          <!-- Step 4 -->
          <div class="time-label">
            <span class="bg-success px-3 shadow-sm">LANGKAH 4</span>
          </div>
          <div>
            <i class="fas fa-check-double bg-success shadow-sm"></i>
            <div class="timeline-item shadow-sm border-0 rounded-lg">
              <h3 class="timeline-header font-weight-bold text-success border-0">Pantau Status & Unduh Izin</h3>
              <div class="timeline-body py-3">
                <p class="mb-0 text-muted">Setelah dikirim, ajuan Anda akan ditinjau. Anda dapat memantau statusnya secara
                  real-time. Jika disetujui, sertifikat izin resmi dapat diunduh langsung dalam bentuk PDF melalui
                  dashboard atau menu riwayat pengajuan.</p>
              </div>
            </div>
          </div>

          <div>
            <i class="fas fa-flag-checkered bg-gray shadow-sm"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- FAQ / Tip Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card card-outline card-primary shadow-sm border-0" style="border-radius: 1.25rem;">
          <div class="card-header bg-transparent py-4">
            <h3 class="card-title font-weight-bold text-dark h5 mb-0">
              <i class="fas fa-lightbulb text-warning mr-2"></i> Tips Tambahan & Informasi Penting
            </h3>
          </div>
          <div class="card-body px-4 pb-5">
            <div class="row">
              <div class="col-md-6 mb-4 mb-md-0">
                <div class="d-flex align-items-start p-3 bg-light rounded-lg h-100">
                  <div class="bg-white p-3 rounded-circle shadow-sm mr-3">
                    <i class="fas fa-comments text-primary h4 mb-0"></i>
                  </div>
                  <div>
                    <h4 class="font-weight-bold text-xs text-uppercase tracking-wider mb-2">Diskusi & Komentar</h4>
                    <p class="small text-muted mb-0">Gunakan fitur diskusi pada detail pengajuan jika ada instruksi
                      perbaikan dari petugas dinas. Komunikasi yang aktif akan mempercepat penyelesaian berkas Anda.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex align-items-start p-3 bg-light rounded-lg h-100">
                  <div class="bg-white p-3 rounded-circle shadow-sm mr-3">
                    <i class="fas fa-file-alt text-primary h4 mb-0"></i>
                  </div>
                  <div>
                    <h4 class="font-weight-bold text-xs text-uppercase tracking-wider mb-2">Format Berkas</h4>
                    <p class="small text-muted mb-0">Gunakan hasil scan dokumen asli (bukan fotokopi) untuk memastikan
                      keaslian data. Pastikan ukuran file tidak melebihi batas yang ditentukan sistem.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .bg-gradient-primary {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }

    .timeline::before {
      border-radius: 0.25rem;
      background-color: #dee2e6;
      bottom: 0;
      content: "";
      left: 31px;
      margin: 0;
      position: absolute;
      top: 0;
      width: 4px;
    }

    .timeline>div>i {
      left: 18px;
      width: 30px;
      height: 30px;
      font-size: 14px;
      line-height: 30px;
    }

    .timeline .time-label {
      margin-left: 10px;
    }

    @media (max-width: 767.98px) {
      .timeline::before {
        left: 18px;
      }

      .timeline>div>i {
        left: 5px;
      }

      .timeline .time-label {
        margin-left: 0;
      }
    }

    .timeline-item {
      margin-left: 60px !important;
    }
  </style>
@endsection