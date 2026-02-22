@extends('layouts.admin_lembaga')

@section('title', 'Konfigurasi Data Lembaga')

@section('content')
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-university mr-2 text-primary"></i> Data Profil Lembaga
        </h1>
        <p class="text-muted small mt-1">Lengkapi data di bawah ini untuk kebutuhan sinkronisasi otomatis ke sertifikat
          perizinan.</p>
      </div>
      <div class="col-sm-6 text-right">
        <button type="submit" form="profile-form" class="btn btn-primary shadow-sm px-4">
          <i class="fas fa-save mr-1"></i> Simpan Perubahan
        </button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="{{ route('admin_lembaga.profile.update') }}" method="POST" enctype="multipart/form-data"
      id="profile-form">
      @csrf
      <div class="row">
        <!-- Left Column: Branding & Contacts -->
        <div class="col-md-4">
          <!-- Logo Card -->
          <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-body box-profile">
              <p class="text-center font-weight-bold text-muted text-uppercase small mb-3">Logo Institusi Resmi</p>
              <div class="text-center mb-4">
                <div
                  class="profile-user-img img-fluid img-circle shadow-sm overflow-hidden border-2 d-flex align-items-center justify-content-center bg-light"
                  style="width: 150px; height: 150px; padding: 10px; margin: 0 auto; cursor: pointer;"
                  onclick="document.getElementById('logo-input').click()">
                  @if($lembaga->logo)
                    <img src="{{ Storage::url($lembaga->logo) }}" id="logo-preview" class="mw-100 mh-100 object-contain">
                  @else
                    <i class="fas fa-school fa-3x text-muted opacity-50"></i>
                  @endif
                  <div class="position-absolute bg-primary text-white p-2 rounded-circle shadow"
                    style="bottom: 0; right: 25px;">
                    <i class="fas fa-camera"></i>
                  </div>
                </div>
                <input type="file" name="logo" id="logo-input" class="d-none"
                  onchange="previewImage(this, 'logo-preview')">
              </div>
              <p class="text-center text-muted x-small font-italic">Klik gambar untuk mengubah logo. Rekomendasi format
                PNG/JPG dengan latar belakang transparan.</p>
            </div>
          </div>

          <!-- Contact Card -->
          <div class="card card-outline card-success shadow-sm">
            <div class="card-header border-0 bg-transparent">
              <h3 class="card-title font-weight-bold"><i class="fas fa-address-book mr-2 text-success"></i> Kontak &
                Lokasi</h3>
            </div>
            <div class="card-body pt-0">
              <div class="form-group">
                <label class="small font-weight-bold text-uppercase text-muted">Nomor Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $lembaga->telepon) }}" class="form-control"
                  placeholder="02xxx-xxx-xxx">
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-uppercase text-muted">Email Resmi</label>
                <input type="email" name="email" value="{{ old('email', $lembaga->email) }}" class="form-control"
                  placeholder="admin@domain.sch.id">
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-uppercase text-muted">Website</label>
                <input type="url" name="website" value="{{ old('website', $lembaga->website) }}" class="form-control"
                  placeholder="https://pkbm.sch.id">
              </div>
              <div class="form-group">
                <label class="small font-weight-bold text-uppercase text-muted">Alamat Lengkap</label>
                <textarea name="alamat" rows="4" class="form-control"
                  placeholder="Jl. Raya Garut - Tasikmalaya No. 123, Garut"
                  required>{{ old('alamat', $lembaga->alamat) }}</textarea>
                <small class="text-muted font-italic">Alamat ini akan dicetak langsung pada sertifikat izin.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Identity & Legalities -->
        <div class="col-md-8">
          <!-- Main Identity Card -->
          <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header border-bottom-0 bg-transparent">
              <h3 class="card-title font-weight-bold"><i class="fas fa-id-card mr-2 text-primary"></i> Identitas Satuan
                Pendidikan</h3>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-md-8 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">Nama Lembaga</label>
                  <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $lembaga->nama_lembaga) }}"
                    class="form-control font-weight-bold" required placeholder="Contoh: PKBM Harapan Bangsa">
                </div>
                <div class="col-md-4 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">NPSN</label>
                  <input type="text" name="npsn" value="{{ old('npsn', $lembaga->npsn) }}"
                    class="form-control font-weight-bold" required placeholder="8 Digit Angka">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">Status Kepemilikan</label>
                  <select name="status_kepemilikan" class="form-control custom-select">
                    <option value="Yayasan" {{ $lembaga->status_kepemilikan == 'Yayasan' ? 'selected' : '' }}>Yayasan
                    </option>
                    <option value="Negeri" {{ $lembaga->status_kepemilikan == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                    <option value="Swasta" {{ $lembaga->status_kepemilikan == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    <option value="Lainnya" {{ $lembaga->status_kepemilikan == 'Lainnya' ? 'selected' : '' }}>Lainnya
                    </option>
                  </select>
                </div>
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">Akreditasi</label>
                  <select name="akreditasi" class="form-control custom-select">
                    <option value="">Belum Terakreditasi</option>
                    <option value="A" {{ $lembaga->akreditasi == 'A' ? 'selected' : '' }}>Grade A</option>
                    <option value="B" {{ $lembaga->akreditasi == 'B' ? 'selected' : '' }}>Grade B</option>
                    <option value="C" {{ $lembaga->akreditasi == 'C' ? 'selected' : '' }}>Grade C</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Legalities Card -->
          <div class="card card-outline card-indigo shadow-sm mb-4" style="border-top-color: #6610f2;">
            <div class="card-header border-bottom-0 bg-transparent">
              <h3 class="card-title font-weight-bold"><i class="fas fa-file-signature mr-2 text-indigo"></i> Legalitas &
                SK Pendirian</h3>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">SK Pendirian Lembaga</label>
                  <input type="text" name="sk_pendirian" value="{{ old('sk_pendirian', $lembaga->sk_pendirian) }}"
                    class="form-control" placeholder="Nomor SK Pendirian">
                </div>
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">Tanggal SK Pendirian</label>
                  <input type="date" name="tanggal_sk_pendirian"
                    value="{{ old('tanggal_sk_pendirian', $lembaga->tanggal_sk_pendirian ? $lembaga->tanggal_sk_pendirian->format('Y-m-d') : '') }}"
                    class="form-control">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">SK Izin Operasional</label>
                  <input type="text" name="sk_izin_operasional"
                    value="{{ old('sk_izin_operasional', $lembaga->sk_izin_operasional) }}" class="form-control"
                    placeholder="Nomor SK Izin Operasional">
                </div>
                <div class="col-md-6 form-group">
                  <label class="small font-weight-bold text-uppercase text-muted">Masa Berlaku Izin</label>
                  <input type="date" name="masa_berlaku_izin"
                    value="{{ old('masa_berlaku_izin', $lembaga->masa_berlaku_izin ? $lembaga->masa_berlaku_izin->format('Y-m-d') : '') }}"
                    class="form-control">
                </div>
              </div>
            </div>
          </div>

          <!-- Vision Card -->
          <div class="card card-outline card-warning shadow-sm">
            <div class="card-header border-bottom-0 bg-transparent">
              <h3 class="card-title font-weight-bold"><i class="fas fa-lightbulb mr-2 text-warning"></i> Visi Lembaga</h3>
            </div>
            <div class="card-body pt-0">
              <div class="form-group">
                <label class="small font-weight-bold text-uppercase text-muted">Kalimat Visi</label>
                <textarea name="visi" rows="3" class="form-control font-italic font-weight-bold"
                  placeholder="Contoh: Menjadi lembaga pendidikan yang unggul dan inklusif di masa depan.">{{ old('visi', $lembaga->visi) }}</textarea>
                <small class="text-muted">Visi ini mencerminkan orientasi masa depan lembaga Anda.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <style>
    .x-small {
      font-size: 0.75rem;
    }

    .text-indigo {
      color: #6610f2;
    }

    .card-indigo {
      border-top-color: #6610f2 !important;
    }

    .profile-user-img:hover {
      background-color: #f8f9fa !important;
      transform: scale(1.02);
      transition: all 0.2s ease-in-out;
    }
  </style>
@endsection

@push('scripts')
  <script>
    function previewImage(input, previewId) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById(previewId);
          if (preview) {
            preview.src = e.target.result;
          } else {
            // Handle initial display if icon was present
            const container = input.closest('.box-profile').querySelector('.profile-user-img');
            container.innerHTML = `<img src="${e.target.result}" id="${previewId}" class="mw-100 mh-100 object-contain">`;
          }
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endpush