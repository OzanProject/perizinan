@extends('layouts.backend')

@section('title', 'Pengaturan Akun')
@section('breadcrumb', 'Setting')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <!-- Profile Summary Column -->
      <div class="col-md-3">
        <div class="card card-primary card-outline shadow-sm">
          <div class="card-body box-profile">
            <div class="text-center mb-3">
              <div id="profile-preview-card"
                class="profile-user-img img-fluid img-circle shadow-sm overflow-hidden border-2"
                style="width: 100px; height: 100px; padding: 0;">
                @if($user->photo)
                  <img src="{{ Storage::url($user->photo) }}" class="h-100 w-100 object-cover">
                @else
                  <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=EBF4FF&color=7F9CF5"
                    class="h-100 w-100 object-cover">
                @endif
              </div>
            </div>
            <h3 class="profile-username text-center font-weight-bold">{{ $user->name }}</h3>
            <p class="text-muted text-center text-uppercase small font-weight-bold">
              {{ str_replace('_', ' ', $user->getRoleNames()->first()) }}
            </p>

            <div class="text-center mt-2 mb-4">
              <span class="badge badge-success px-3 py-1 shadow-sm"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item px-0 border-top-0">
                <b>Bergabung</b> <a class="float-right text-dark">{{ $user->created_at->format('d M Y') }}</a>
              </li>
              <li class="list-group-item px-0">
                <b>Dinas</b> <a class="float-right text-dark truncate d-inline-block" style="max-width: 150px;"
                  title="{{ $user->dinas->nama }}">{{ $user->dinas->nama }}</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="card card-info shadow-sm bg-light-info border-0">
          <div class="card-body p-3">
            <div class="d-flex align-items-start">
              <i class="fas fa-shield-alt text-info mt-1 mr-3"></i>
              <div>
                <h6 class="font-weight-bold text-info mb-1">Status Keamanan</h6>
                <p class="small text-muted mb-0">Akun Anda dilindungi dengan sistem enkripsi standar dan kebijakan dinas.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Content Column -->
      <div class="col-md-9">
        <div class="card card-primary card-outline card-tabs shadow-sm">
          <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="settingTabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="tab-profile-link" data-toggle="pill" href="#tab-profile"
                  role="tab">
                  <i class="fas fa-user-circle mr-1"></i> Profil Saya
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-instansi-link" data-toggle="pill" href="#tab-instansi"
                  role="tab">
                  <i class="fas fa-building mr-1"></i> Instansi & Aplikasi
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-security-link" data-toggle="pill" href="#tab-security"
                  role="tab">
                  <i class="fas fa-lock mr-1"></i> Keamanan
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link font-weight-bold" id="tab-maintenance-link" data-toggle="pill" href="#tab-maintenance"
                  role="tab">
                  <i class="fas fa-tools mr-1"></i> Pemeliharaan
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="settingTabsContent">

              <!-- Profil Tab -->
              <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                <div class="mb-4">
                  <h4 class="font-weight-bold text-dark mb-1">Profil Saya</h4>
                  <p class="text-muted small">Informasi dasar akun pengguna Anda.</p>
                </div>

                <form action="{{ route('super_admin.settings.profile.update') }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-3 text-center mb-3">
                      <div class="position-relative d-inline-block">
                        <div id="profile-preview-container"
                          class="rounded-circle shadow-sm overflow-hidden border border-secondary bg-light mx-auto"
                          style="width: 120px; height: 120px;">
                          @if($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" class="h-100 w-100 object-cover">
                          @else
                            <img
                              src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=EBF4FF&color=7F9CF5"
                              class="h-100 w-100 object-cover">
                          @endif
                        </div>
                        <label class="btn btn-sm btn-primary btn-circle position-absolute"
                          style="bottom: 5px; right: 5px; width: 32px; height: 32px; padding: 6px 0;">
                          <i class="fas fa-camera fa-sm"></i>
                          <input type="file" name="photo" class="d-none" accept="image/*">
                        </label>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control"
                          required>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Email Dinas</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                          required>
                      </div>
                      <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                          <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Instansi Tab -->
              <div class="tab-pane fade" id="tab-instansi" role="tabpanel">
                <div class="mb-4">
                  <h4 class="font-weight-bold text-dark mb-1">Instansi & Aplikasi</h4>
                  <p class="text-muted small">Konfigurasi branding untuk seluruh dashboard dinas Anda.</p>
                </div>

                <form action="{{ route('super_admin.settings.app.update') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-6 border-right">
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Nama Aplikasi</label>
                        <input type="text" name="app_name"
                          value="{{ old('app_name', $dinas->app_name ?? 'Sistem Perizinan') }}" class="form-control"
                          required>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Logo Instansi</label>
                        <div class="d-flex align-items-center bg-light p-2 rounded">
                          <div id="logo-preview-container"
                            class="bg-white border text-center d-flex align-items-center justify-content-center mr-3 rounded overflow-hidden"
                            style="width: 60px; height: 60px; padding: 5px;">
                            @if($dinas->logo)
                              <img src="{{ Storage::url($dinas->logo) }}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                              <i class="fas fa-image text-light fa-2x"></i>
                            @endif
                          </div>
                          <label class="btn btn-sm btn-link font-weight-bold mb-0 text-primary p-0">
                            Ganti Logo
                            <input type="file" name="logo" class="d-none" accept="image/*">
                          </label>
                        </div>
                      </div>
                      <div class="form-group mb-0">
                        <label class="small font-weight-bold text-muted text-uppercase">Stempel Digital (PNG)</label>
                        <div class="d-flex align-items-center bg-light p-2 rounded">
                          <div id="stempel-preview-container"
                            class="bg-white border text-center d-flex align-items-center justify-content-center mr-3 rounded overflow-hidden"
                            style="width: 60px; height: 60px; padding: 5px;">
                            @if($dinas->stempel_img)
                              <img src="{{ Storage::url($dinas->stempel_img) }}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                              <i class="fas fa-stamp text-light fa-2x"></i>
                            @endif
                          </div>
                          <label class="btn btn-sm btn-link font-weight-bold mb-0 text-primary p-0">
                            Ganti Stempel
                            <input type="file" name="stempel_img" class="d-none" accept="image/png">
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">Kode Surat</label>
                          <input type="text" name="kode_surat" value="{{ old('kode_surat', $dinas->kode_surat) }}"
                            class="form-control" placeholder="Ex: DISDIK" required>
                        </div>
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">Pangkat</label>
                          <input type="text" name="pimpinan_pangkat"
                            value="{{ old('pimpinan_pangkat', $dinas->pimpinan_pangkat) }}" class="form-control"
                            placeholder="Ex: Pembina, IV/a">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Alamat Dinas</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $dinas->alamat) }}" class="form-control"
                          placeholder="Jl. Jenderal Sudirman No. 1">
                        <small class="text-muted">Tampil di Kop Surat sertifikat.</small>
                      </div>
                      <div class="row">
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">Kabupaten/Kota</label>
                          <input type="text" name="kabupaten" value="{{ old('kabupaten', $dinas->kabupaten) }}"
                            class="form-control" placeholder="Ex: Garut">
                        </div>
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">Provinsi</label>
                          <input type="text" name="provinsi" value="{{ old('provinsi', $dinas->provinsi) }}"
                            class="form-control" placeholder="Ex: Jawa Barat">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Nama Pejabat Penandatangan</label>
                        <input type="text" name="pimpinan_nama" value="{{ old('pimpinan_nama', $dinas->pimpinan_nama) }}"
                          class="form-control" placeholder="Nama Lengkap & Gelar" required>
                      </div>
                      <div class="row">
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">Jabatan</label>
                          <input type="text" name="pimpinan_jabatan"
                            value="{{ old('pimpinan_jabatan', $dinas->pimpinan_jabatan) }}" class="form-control"
                            placeholder="Ex: Kepala Dinas" required>
                        </div>
                        <div class="col-6 form-group">
                          <label class="small font-weight-bold text-muted text-uppercase">NIP Pejabat</label>
                          <input type="text" name="pimpinan_nip" value="{{ old('pimpinan_nip', $dinas->pimpinan_nip) }}"
                            class="form-control" placeholder="18 digit NIP">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Teks Footer (Copyright)</label>
                        <textarea name="footer_text" rows="2"
                          class="form-control">{{ old('footer_text', $dinas->footer_text) }}</textarea>
                      </div>

                      <hr class="my-4">
                      <h6 class="font-weight-bold mb-3"><i class="fas fa-certificate text-primary mr-2"></i> Watermark
                        Sertifikat</h6>

                      <div class="bg-light p-3 rounded border">
                        <div class="row">
                          <div class="col-md-5">
                            <div class="form-group mb-0">
                              <label class="small font-weight-bold text-muted text-uppercase">Aset Watermark</label>
                              <div class="d-flex align-items-center bg-white p-2 rounded border">
                                <div id="watermark-preview-container"
                                  class="bg-light border text-center d-flex align-items-center justify-content-center mr-2 roundedoverflow-hidden"
                                  style="width: 50px; height: 50px; padding: 5px;">
                                  @if($dinas->watermark_img)
                                    <img src="{{ Storage::url($dinas->watermark_img) }}"
                                      style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                  @else
                                    <i class="fas fa-tint text-muted"></i>
                                  @endif
                                </div>
                                <label class="btn btn-xs btn-outline-primary mb-0">
                                  Pilih File
                                  <input type="file" name="watermark_img" class="d-none" accept="image/*">
                                </label>
                              </div>
                              <p class="x-small text-muted mt-1 mb-0">Gunakan latar belakang transparan (PNG).</p>
                            </div>
                          </div>
                          <div class="col-md-7">
                            <div class="form-group mb-2">
                              <div class="custom-control custom-switch">
                                <input type="checkbox" name="watermark_enabled" class="custom-control-input"
                                  id="watermarkEnabled" {{ ($dinas->watermark_enabled ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold" for="watermarkEnabled">Aktifkan
                                  Watermark Otomatis</label>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <div class="form-group mb-0">
                                  <label class="x-small font-weight-bold text-muted mb-1">Opacity (%)</label>
                                  <input type="number" name="watermark_opacity" step="0.01" min="0.01" max="1"
                                    value="{{ old('watermark_opacity', $dinas->watermark_opacity ?? 0.05) }}"
                                    class="form-control form-control-sm">
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="form-group mb-0">
                                  <label class="x-small font-weight-bold text-muted mb-1">Size (px)</label>
                                  <input type="number" name="watermark_size" min="50" max="1000"
                                    value="{{ old('watermark_size', $dinas->watermark_size ?? 400) }}"
                                    class="form-control form-control-sm">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                      <i class="fas fa-check-circle mr-1"></i> Update Branding
                    </button>
                  </div>
                </form>
              </div>

              <!-- Keamanan Tab -->
              <div class="tab-pane fade" id="tab-security" role="tabpanel">
                <div class="mb-4">
                  <h4 class="font-weight-bold text-dark mb-1">Ubah Password</h4>
                  <p class="text-muted small">Pastikan password Anda kuat dan unik untuk keamanan akun.</p>
                </div>

                <form action="{{ route('super_admin.settings.security.update') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control bg-light"
                          placeholder="••••••••" required>
                      </div>
                      <hr class="my-4">
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                      </div>
                      <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                          required>
                      </div>
                      <div class="mt-4">
                        <button type="submit" class="btn btn-warning px-4 font-weight-bold">
                          <i class="fas fa-lock mr-2"></i> Ubah Password
                        </button>
                      </div>
                    </div>
                    <div class="col-md-6 offset-md-1 d-none d-md-block pt-4">
                      <div class="p-4 bg-light rounded text-center">
                        <i class="fas fa-user-shield text-muted fa-5x mb-3"></i>
                        <p class="text-muted small font-italic mx-5">Gunakan kombinasi huruf, angka, dan karakter khusus
                          untuk perlindungan maksimal.</p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Pemeliharaan Tab -->
              <div class="tab-pane fade" id="tab-maintenance" role="tabpanel">
                <div class="mb-4">
                  <h4 class="font-weight-bold text-dark mb-1">Pemeliharaan & Database</h4>
                  <p class="text-muted small">Alat untuk memastikan sistem berjalan optimal dan manajemen data.</p>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="card card-outline card-secondary shadow-sm mb-3">
                      <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                          <div class="bg-gray-light p-2 rounded-circle mr-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-broom text-secondary"></i>
                          </div>
                          <h6 class="font-weight-bold mb-0 text-dark">Bersihkan Cache</h6>
                        </div>
                        <p class="small text-muted mb-4">Hapus file cache sistem untuk menyegarkan tampilan atau
                          konfigurasi terbaru.</p>
                        <form action="{{ route('super_admin.settings.cache.clear') }}" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-block btn-outline-secondary btn-sm font-weight-bold">
                            Jalankan Clear Cache
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card card-outline card-success shadow-sm mb-3">
                      <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                          <div class="bg-success-soft p-2 rounded-circle mr-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: rgba(40,167,69,0.1);">
                            <i class="fas fa-database text-success"></i>
                          </div>
                          <h6 class="font-weight-bold mb-0 text-dark">Database Backup</h6>
                        </div>
                        <p class="small text-muted mb-4">Unduh salinan cadangan basis data (.sql) Anda untuk keperluan
                          keamanan data.</p>
                        <form action="{{ route('super_admin.settings.backup') }}" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-block btn-success shadow-sm btn-sm font-weight-bold">
                            <i class="fas fa-download mr-1"></i> Download Backup (.sql)
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card card-outline card-primary shadow-sm border-dashed">
                      <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                          <div class="bg-primary-soft p-2 rounded-circle mr-3"
                            style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background-color: rgba(0,123,255,0.1);">
                            <i class="fas fa-upload text-primary"></i>
                          </div>
                          <h6 class="font-weight-bold mb-0 text-dark">Restore Database</h6>
                        </div>
                        <p class="small text-muted mb-4">Pulihkan data dari file .sql yang pernah Anda unduh. <span
                            class="text-danger font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Peringatan:
                            Data saat ini akan ditimpa!</span></p>
                        <form action="{{ route('super_admin.settings.restore') }}" method="POST"
                          enctype="multipart/form-data">
                          @csrf
                          <div class="row align-items-center">
                            <div class="col-md-9 form-group mb-md-0">
                              <div class="custom-file">
                                <input type="file" name="db_file" class="custom-file-input" id="dbFile" accept=".sql"
                                  required>
                                <label class="custom-file-label" for="dbFile">Pilih file backup .sql</label>
                              </div>
                            </div>
                            <div class="col-md-3 text-right text-md-center">
                              <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin memulihkan database? Seluruh data saat ini akan hilang.')"
                                class="btn btn-dark btn-block font-weight-bold shadow-sm px-4">
                                Restore Sekarang
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      // Image Preview Logic
      function setupImagePreview(inputName, previewId, cardId = null) {
        const input = document.querySelector(`input[name="${inputName}"]`);
        const preview = document.getElementById(previewId);

        if (input && preview) {
          input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = function (e) {
                let img = preview.querySelector('img');
                if (!img) {
                  preview.innerHTML = '';
                  img = document.createElement('img');
                  preview.appendChild(img);
                }
                img.src = e.target.result;
                if (previewId.includes('logo') || previewId.includes('stempel') || previewId.includes('watermark')) {
                  img.style.maxWidth = '100%';
                  img.style.maxHeight = '100%';
                  img.style.objectFit = 'contain';
                } else {
                  img.style.width = '100%';
                  img.style.height = '100%';
                  img.style.objectFit = 'cover';
                }

                if (cardId) {
                  const cardPreview = document.getElementById(cardId);
                  if (cardPreview) {
                    let cardImg = cardPreview.querySelector('img');
                    if (!cardImg) {
                      cardPreview.innerHTML = '';
                      cardImg = document.createElement('img');
                      cardPreview.appendChild(cardImg);
                    }
                    cardImg.src = e.target.result;
                    cardImg.className = 'h-100 w-100 object-cover';
                  }
                }
              }
              reader.readAsDataURL(file);
            }
          });
        }
      }

      $(document).ready(function () {
        setupImagePreview('photo', 'profile-preview-container', 'profile-preview-card');
        setupImagePreview('logo', 'logo-preview-container');
        setupImagePreview('stempel_img', 'stempel-preview-container');
        setupImagePreview('watermark_img', 'watermark-preview-container');

        // BS Custom File Input (for restore database)
        if (typeof bsCustomFileInput !== 'undefined') {
          bsCustomFileInput.init();
        }
      });

      // Auto-switch to tab if error exists or session specific
      @if($errors->hasAny(['current_password', 'password']))
        $('#tab-security-link').tab('show');
      @endif
    </script>
  @endpush
@endsection