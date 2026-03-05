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

          <form action="{{ route('admin_lembaga.perizinan.store') }}" method="POST" id="perizinanForm">
            @csrf
            <div class="card-body p-4 p-md-5">

              {{-- FLOW STEP 1: Tombol Utama --}}
              <div id="step-1-container" class="text-center py-4 transition-all duration-300">
                <button type="button" id="btn-mulai-pengajuan" class="btn btn-primary btn-lg px-5 py-4 font-weight-bold shadow rounded-lg" style="font-size: 1.25rem;">
                  <i class="fas fa-file-signature fa-2x mb-3 d-block"></i>
                  Ajukan pembaharuan Izin
                </button>
              </div>

              {{-- FLOW STEP 2: Pilih Bidang (Sembunyi by default) --}}
              <div id="step-2-container" class="d-none transition-all duration-300">
                <div class="text-center mb-4">
                  <h4 class="font-weight-bold text-dark">Pilih Bidang Pengajuan</h4>
                  <p class="text-muted">Pilih bidang yang sesuai dengan lembaga Anda.</p>
                </div>

                <div class="row justify-content-center">
                  <div class="col-md-5 mb-3">
                    <div class="card border border-primary h-100 cursor-pointer hover-shadow transition-all text-center p-4 bidang-card" data-bidang="dikmas" onclick="selectBidang('dikmas')">
                      <i class="fas fa-users fa-3x text-primary mb-3"></i>
                      <h5 class="font-weight-bold text-primary mb-0">PENGAJUAN<br>BIDANG DIKMAS</h5>
                    </div>
                  </div>
                  <div class="col-md-auto d-flex align-items-center justify-content-center mb-3">
                    <i class="fas fa-exchange-alt fa-2x text-muted d-none d-md-block px-3"></i>
                  </div>
                  <div class="col-md-5 mb-3">
                    <div class="card border border-info h-100 cursor-pointer hover-shadow transition-all text-center p-4 bidang-card" data-bidang="paud" onclick="selectBidang('paud')">
                      <i class="fas fa-child fa-3x text-info mb-3"></i>
                      <h5 class="font-weight-bold text-info mb-0">PENGAJUAN<br>BIDANG PAUD</h5>
                    </div>
                  </div>
                </div>

                <div class="text-center mt-3">
                  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetToStep1()">
                    <i class="fas fa-undo mr-1"></i> Batal
                  </button>
                </div>
              </div>

              {{-- FLOW STEP 3: Pilih Jenis Izin Spesifik (Sembunyi by default) --}}
              <div id="step-3-container" class="d-none transition-all duration-300 mt-5">
                <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                  <button type="button" class="btn btn-light btn-sm mr-3" onclick="backToStep2()">
                    <i class="fas fa-arrow-left"></i> Kembali
                  </button>
                  <h5 class="font-weight-bold text-dark mb-0 m-0" id="step-3-title">Pilih Jenis Perizinan</h5>
                </div>

                <div class="row" id="perizinan-list">
                  @foreach($jenisPerizinans as $jp)
                    @php
                      // Kategorisasi berdasarkan nama
                      $nama = strtolower($jp->nama);
                      $bidang = 'lainnya';
                      if (str_contains($nama, 'pkbm') || str_contains($nama, 'lkp')) {
                        $bidang = 'dikmas';
                      } elseif (str_contains($nama, 'paud') || str_contains($nama, 'tk') || str_contains($nama, 'sps') || str_contains($nama, 'tpa') || str_contains($nama, 'kober')) {
                        $bidang = 'paud';
                      }
                    @endphp
                    <div class="col-md-6 mb-3 perizinan-item" data-bidang="{{ $bidang }}">
                      <div class="custom-control custom-radio custom-selectable-card h-100">
                        <input type="radio" id="jp_{{ $jp->id }}" name="jenis_perizinan_id" value="{{ $jp->id }}" class="custom-control-input">
                        <label class="custom-control-label d-block p-3 border rounded h-100 w-100 cursor-pointer transition-all" for="jp_{{ $jp->id }}" style="padding-left: 2.5rem !important;">
                          <span class="d-block font-weight-bold text-dark mb-1">{{ $jp->nama }}</span>
                          <small class="text-muted line-height-sm d-block">{{ $jp->deskripsi ?? 'Pengajuan izin untuk operasional lembaga.' }}</small>
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>

                {{-- Alert jika kosong --}}
                <div id="empty-alert" class="alert alert-warning d-none">
                  <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada jenis perizinan yang disetting untuk bidang ini.
                </div>
              </div>

            </div>

            <div class="card-footer bg-light px-4 py-3 text-right" id="form-footer" style="display: none;">
              <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm" id="btn-submit" disabled>
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

    /* Animations & Cards for Steps */
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .duration-300 {
        transition-duration: 300ms;
    }
    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const step1 = document.getElementById('step-1-container');
        const step2 = document.getElementById('step-2-container');
        const step3 = document.getElementById('step-3-container');
        const footer = document.getElementById('form-footer');
        const btnSubmit = document.getElementById('btn-submit');
        
        // Start Step 1 -> Step 2
        document.getElementById('btn-mulai-pengajuan').addEventListener('click', function() {
            step1.classList.add('d-none');
            step2.classList.remove('d-none');
            step2.classList.add('fade-in');
        });

        // Radios behavior
        const radios = document.querySelectorAll('input[name="jenis_perizinan_id"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    btnSubmit.removeAttribute('disabled');
                }
            });
        });

        // Step 2 -> Step 3
        window.selectBidang = function(bidangId) {
            // Update Title
            const title = document.getElementById('step-3-title');
            title.textContent = bidangId === 'dikmas' ? 'Pilih Jenis Perizinan DIKMAS' : 'Pilih Jenis Perizinan PAUD';

            // Filter items
            const items = document.querySelectorAll('.perizinan-item');
            let hasVisibleItems = false;
            items.forEach(item => {
                // Remove required and checked state
                const radio = item.querySelector('input[type="radio"]');
                radio.required = false;
                radio.checked = false;

                if (item.dataset.bidang === bidangId || item.dataset.bidang === 'lainnya') {
                    item.style.display = 'block';
                    radio.required = true;
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Handle empty state
            const emptyAlert = document.getElementById('empty-alert');
            if(hasVisibleItems) {
                emptyAlert.classList.add('d-none');
            } else {
                emptyAlert.classList.remove('d-none');
            }

            // Disable submit initially
            btnSubmit.setAttribute('disabled', 'true');
            
            // Transition
            step2.classList.add('d-none');
            step3.classList.remove('d-none');
            footer.style.display = 'block';
            step3.classList.add('fade-in');
        };

        // Back buttons
        window.resetToStep1 = function() {
            step2.classList.add('d-none');
            step1.classList.remove('d-none');
            step1.classList.add('fade-in');
        };

        window.backToStep2 = function() {
            step3.classList.add('d-none');
            footer.style.display = 'none';
            step2.classList.remove('d-none');
            step2.classList.add('fade-in');
            
            // Uncheck all radios
            radios.forEach(r => r.checked = false);
            btnSubmit.setAttribute('disabled', 'true');
        };
    });
  </script>
  @endpush
@endsection