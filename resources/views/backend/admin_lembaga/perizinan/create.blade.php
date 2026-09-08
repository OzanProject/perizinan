@extends('layouts.admin_lembaga')

@section('title', 'Buat Pengajuan Izin Baru')

@section('content')
  <div class="container-fluid py-4">
    <!-- Breadcrumb & Header -->
    <div class="row mb-4 align-items-end">
      <div class="col-md-9">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-2">
            <li class="breadcrumb-item small"><a href="{{ route('admin_lembaga.dashboard') }}"><i
                  class="fas fa-home mr-1"></i> Home</a></li>
            <li class="breadcrumb-item small"><a href="{{ route('admin_lembaga.perizinan.index') }}">Pengajuan</a></li>
            <li class="breadcrumb-item active small" aria-current="page">Buat Baru</li>
          </ol>
        </nav>
        <h1 class="h3 font-weight-bold text-dark mb-1">Form Pengajuan Pembaruan Izin</h1>
        <p class="text-muted mb-0">Silakan lengkapi data lembaga Anda untuk proses pembaruan izin.</p>
      </div>
      <div class="col-md-3 text-md-right mt-3 mt-md-0">
        <a href="{{ route('admin_lembaga.perizinan.index') }}" class="btn btn-outline-secondary shadow-sm">
          <i class="fas fa-times mr-2"></i> Batalkan
        </a>
      </div>
    </div>

    <!-- Progress Stepper -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body p-3">
        <div class="row text-center px-lg-5">
          <div class="col-4">
            <div class="d-flex flex-column align-items-center">
              <div
                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center border border-white shadow-sm mb-2"
                style="width: 35px; height: 35px; z-index: 2;">
                <span class="font-weight-bold small">1</span>
              </div>
              <span class="font-weight-bold text-primary small d-none d-sm-inline">Info Umum</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center">
              <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center border mb-2"
                style="width: 35px; height: 35px; z-index: 2;">
                <span class="font-weight-bold small">2</span>
              </div>
              <span class="text-muted small d-none d-sm-inline">Upload Berkas</span>
            </div>
          </div>
          <div class="col-4">
            <div class="d-flex flex-column align-items-center">
              <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center border mb-2"
                style="width: 35px; height: 35px; z-index: 2;">
                <span class="font-weight-bold small">3</span>
              </div>
              <span class="text-muted small d-none d-sm-inline">Review & Submit</span>
            </div>
          </div>
          <!-- Progress Line Overlay -->
          <div class="position-absolute"
            style="top: 30px; left: 15%; right: 15%; height: 2px; background: #e9ecef; z-index: 1;"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Main Form Column -->
      <div class="col-lg-8 mb-4">
        <div class="card card-outline card-primary shadow-sm h-100">
          <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-info-circle text-primary mr-2"></i> Pilih Jenis Izin
            </h3>
          </div>

          <form action="{{ route('admin_lembaga.perizinan.store') }}" method="POST">
            @csrf
            <div class="card-body p-4 p-md-5">
              <div class="form-group mb-5">
                <label class="d-block font-weight-bold text-muted text-uppercase small tracking-wider mb-4">Jenis
                  Perizinan yang Diajukan</label>
                <div class="row">
                  @foreach($jenisPerizinans as $jp)
                    <div class="col-md-6 mb-3">
                      <div class="custom-control custom-radio custom-selectable-card h-100">
                        <input type="radio" id="jp_{{ $jp->id }}" name="jenis_perizinan_id" value="{{ $jp->id }}"
                          class="custom-control-input" required {{ $loop->first ? 'checked' : '' }}>
                        <label
                          class="custom-control-label d-block p-3 border rounded h-100 w-100 cursor-pointer transition-all"
                          for="jp_{{ $jp->id }}" style="padding-left: 2.5rem !important;">
                          <span class="d-block font-weight-bold text-dark mb-1">{{ $jp->nama }}</span>
                          <small
                            class="text-muted line-height-sm d-block">{{ $jp->deskripsi ?? 'Pengajuan izin untuk operasional lembaga.' }}</small>
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="alert alert-info border-0 shadow-none bg-light p-4 rounded-lg">
                <div class="d-flex">
                  <i class="fas fa-question-circle text-primary fa-lg mr-3 mt-1"></i>
                  <div>
                    <h6 class="font-weight-bold text-dark mb-1">Informasi Lanjutan</h6>
                    <p class="mb-0 small text-muted leading-relaxed">
                      Setelah menekan tombol "Simpan & Lanjut", Anda akan diarahkan ke halaman unggah berkas untuk
                      melengkapi dokumen yang dipersyaratkan sesuai jenis izin yang dipilih.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer bg-light px-4 py-3 text-right">
              <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm">
                Simpan & Lanjut Ke Upload <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar: Checklist Helpers -->
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
          <div class="card-header bg-white border-bottom-0 pb-0">
            <h3 class="card-title font-weight-bold text-dark">
              <i class="fas fa-lightbulb text-warning mr-2"></i> Panduan Langkah 1
            </h3>
          </div>
          <div class="card-body p-4">
            <div class="d-flex mb-4">
              <div class="bg-primary text-white font-weight-bold rounded p-2 text-center mr-3"
                style="width: 32px; height: 32px; flex-shrink: 0;">1</div>
              <div>
                <h6 class="font-weight-bold text-dark mb-1">Pilih Jenis Izin</h6>
                <p class="text-muted small mb-0">Pilih salah satu izin operasional yang ingin Anda ajukan.</p>
              </div>
            </div>
            <div class="d-flex mb-4 opacity-50">
              <div class="bg-secondary text-white font-weight-bold rounded p-2 text-center mr-3"
                style="width: 32px; height: 32px; flex-shrink: 0;">2</div>
              <div>
                <h6 class="font-weight-bold text-dark mb-1">Lengkapi Dokumen</h6>
                <p class="text-muted small mb-0">Unggah berkas PDF/JPG sesuai persyaratan.</p>
              </div>
            </div>
            <div class="d-flex opacity-50">
              <div class="bg-secondary text-white font-weight-bold rounded p-2 text-center mr-3"
                style="width: 32px; height: 32px; flex-shrink: 0;">3</div>
              <div>
                <h6 class="font-weight-bold text-dark mb-1">Final Review</h6>
                <p class="text-muted small mb-0">Verifikasi seluruh data sebelum disubmit ke dinas.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .custom-selectable-card input:checked+label {
      border-color: #007bff !important;
      background-color: rgba(0, 123, 255, 0.03);
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .custom-selectable-card label:hover {
      border-color: #007bff;
      background-color: rgba(0, 123, 255, 0.01);
    }

    .line-height-sm {
      line-height: 1.4;
    }

    .opacity-50 {
      opacity: 0.5;
    }
  </style>
@endsection