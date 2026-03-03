@extends('layouts.backend')

@section('title', 'Manajemen Landing Page')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Manajemen Landing Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Landing Page</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <form action="{{ route('super_admin.landing_page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="landing-page-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tabs-hero-tab" data-toggle="pill" href="#tabs-hero" role="tab"
                  aria-controls="tabs-hero" aria-selected="true">
                  <i class="fas fa-rocket mr-1"></i> Hero & Utama
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tabs-license-tab" data-toggle="pill" href="#tabs-license" role="tab"
                  aria-controls="tabs-license" aria-selected="false">
                  <i class="fas fa-id-card mr-1"></i> Layanan (Izin)
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tabs-faq-tab" data-toggle="pill" href="#tabs-faq" role="tab"
                  aria-controls="tabs-faq" aria-selected="false">
                  <i class="fas fa-question-circle mr-1"></i> FAQ
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tabs-contact-tab" data-toggle="pill" href="#tabs-contact" role="tab"
                  aria-controls="tabs-contact" aria-selected="false">
                  <i class="fas fa-address-book mr-1"></i> Kontak & SEO
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="landing-page-tabsContent">
              <!-- Tab 1: Hero & Utama -->
              <div class="tab-pane fade show active" id="tabs-hero" role="tabpanel" aria-labelledby="tabs-hero-tab">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label>Judul Hero (HTML diperbolehkan)</label>
                      <input type="text" name="hero_title" class="form-control form-control-lg"
                        value="{{ $setting->hero_title }}" required>
                      <small class="text-muted">Gunakan <code>&lt;span class="text-primary"&gt;teks&lt;/span&gt;</code>
                        untuk memberi warna biru.</small>
                    </div>
                    <div class="form-group">
                      <label>Sub-judul Hero</label>
                      <textarea name="hero_subtitle" class="form-control"
                        rows="3">{{ $setting->hero_subtitle }}</textarea>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Teks Support (Bawah Hero)</label>
                          <input type="text" name="support_text" class="form-control"
                            value="{{ $setting->support_text }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Jumlah Agen/Institusi</label>
                          <input type="number" name="support_agents_count" class="form-control"
                            value="{{ $setting->support_agents_count }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Gambar Hero</label>
                      <div class="mb-3">
                        @if($setting->hero_image)
                          <img src="{{ asset('storage/' . $setting->hero_image) }}"
                            class="img-fluid rounded shadow-sm border"
                            style="max-height: 200px; width: 100%; object-fit: cover;">
                        @else
                          <div class="bg-light d-flex align-items-center justify-center rounded border"
                            style="height: 200px;">
                            <span class="text-muted">Tidak ada gambar</span>
                          </div>
                        @endif
                      </div>
                      <div class="custom-file">
                        <input type="file" name="hero_image" class="custom-file-input" id="hero_image">
                        <label class="custom-file-label" for="hero_image">Pilih gambar baru...</label>
                      </div>
                      <small class="text-muted">Format: JPG/PNG/WebP. Maks 2MB.</small>
                    </div>

                    <div class="card card-outline card-success mt-4">
                      <div class="card-header">
                        <h3 class="card-title text-sm"><i class="fas fa-chart-line mr-1"></i> Quick Stats</h3>
                      </div>
                      <div class="card-body p-3">
                        <div class="form-group mb-2">
                          <label class="text-xs">Izin Aktif</label>
                          <input type="number" name="stats_izin_aktif" class="form-control form-control-sm"
                            value="{{ $setting->stats_izin_aktif }}">
                        </div>
                        <div class="form-group mb-2">
                          <label class="text-xs">Proses (MTD)</label>
                          <input type="number" name="stats_proses_bulan_ini" class="form-control form-control-sm"
                            value="{{ $setting->stats_proses_bulan_ini }}">
                        </div>
                        <div class="form-group mb-0">
                          <label class="text-xs">Rata-rata Hari Proses</label>
                          <input type="number" name="stats_rata_hari" class="form-control form-control-sm"
                            value="{{ $setting->stats_rata_hari }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tab 2: Layanan (Izin) -->
              <div class="tab-pane fade" id="tabs-license" role="tabpanel" aria-labelledby="tabs-license-tab">
                <div class="row">
                  <div class="col-md-4">
                    <div class="callout callout-info">
                      <h5><i class="fas fa-info-circle"></i> Info Section</h5>
                      <div class="form-group">
                        <label>Judul Section</label>
                        <input type="text" name="license_title" class="form-control"
                          value="{{ $setting->license_title }}">
                      </div>
                      <div class="form-group">
                        <label>Sub-judul</label>
                        <input type="text" name="license_subtitle" class="form-control"
                          value="{{ $setting->license_subtitle }}">
                      </div>
                      <div class="form-group">
                        <label>Deskripsi Pengantar</label>
                        <textarea name="license_description" class="form-control"
                          rows="4">{{ $setting->license_description }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h5 class="mb-0">Daftar Jenis Izin</h5>
                      <button type="button" class="btn btn-primary btn-sm" id="add-license">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                      </button>
                    </div>
                    <div id="license-container">
                      @foreach($setting->license_types ?? [] as $index => $license)
                        <div class="license-row card card-light border mb-3">
                          <div class="card-body p-3 position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute"
                              style="top: 10px; right: 10px;" onclick="this.closest('.license-row').remove()">
                              <i class="fas fa-times"></i>
                            </button>
                            <div class="row">
                              <div class="col-md-8">
                                <div class="form-group mb-2">
                                  <label class="text-xs">Nama Izin</label>
                                  <input type="text" name="license_types[{{ $index }}][title]" class="form-control"
                                    value="{{ $license['title'] }}">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group mb-2">
                                  <label class="text-xs">Ikon (Material)</label>
                                  <input type="text" name="license_types[{{ $index }}][icon]" class="form-control"
                                    value="{{ $license['icon'] ?? 'add_card' }}">
                                </div>
                              </div>
                              <div class="col-12">
                                <div class="form-group mb-0">
                                  <label class="text-xs">Deskripsi Singkat</label>
                                  <textarea name="license_types[{{ $index }}][description]" class="form-control"
                                    rows="2">{{ $license['description'] }}</textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tab 3: FAQ -->
              <div class="tab-pane fade" id="tabs-faq" role="tabpanel" aria-labelledby="tabs-faq-tab">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Judul Section FAQ</label>
                      <input type="text" name="faq_title" class="form-control" value="{{ $setting->faq_title }}">
                    </div>
                    <div class="form-group">
                      <label>Sub-judul</label>
                      <input type="text" name="faq_subtitle" class="form-control" value="{{ $setting->faq_subtitle }}">
                    </div>
                    <div class="alert alert-info py-2 shadow-sm border-0 mt-4">
                      <i class="fas fa-lightbulb mr-1"></i> Tips: Masukkan pertanyaan yang paling sering diajukan oleh
                      lembaga untuk mengurangi beban support.
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h5 class="mb-0">Daftar Tanya Jawab</h5>
                      <button type="button" class="btn btn-primary btn-sm" id="add-faq">
                        <i class="fas fa-plus mr-1"></i> Tambah FAQ
                      </button>
                    </div>
                    <div id="faq-container">
                      @foreach($setting->faq ?? [] as $index => $faq)
                        <div class="faq-row card border-left-info mb-3 shadow-sm">
                          <div class="card-body p-3 position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute"
                              style="top: 10px; right: 10px;" onclick="this.closest('.faq-row').remove()">
                              <i class="fas fa-times"></i>
                            </button>
                            <div class="form-group mb-2">
                              <label class="text-xs font-bold uppercase tracking-wider text-primary">Pertanyaan</label>
                              <input type="text" name="faq[{{ $index }}][question]" class="form-control"
                                value="{{ $faq['question'] }}">
                            </div>
                            <div class="form-group mb-0">
                              <label class="text-xs font-bold uppercase tracking-wider text-muted">Jawaban</label>
                              <textarea name="faq[{{ $index }}][answer]" class="form-control"
                                rows="2">{{ $faq['answer'] }}</textarea>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tab 4: Kontak & SEO -->
              <div class="tab-pane fade" id="tabs-contact" role="tabpanel" aria-labelledby="tabs-contact-tab">
                <div class="row">
                  <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-headset mr-2"></i>Informasi Kontak</h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Nomor Telepon</label>
                          <input type="text" name="contact_phone" class="form-control"
                            value="{{ $setting->contact_phone }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Email Support</label>
                          <input type="email" name="contact_email" class="form-control"
                            value="{{ $setting->contact_email }}">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Alamat Kantor</label>
                      <textarea name="contact_address" class="form-control"
                        rows="2">{{ $setting->contact_address }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Deskripsi Footer</label>
                      <textarea name="footer_description" class="form-control"
                        rows="3">{{ $setting->footer_description }}</textarea>
                    </div>

                    <h5 class="border-bottom pb-2 mt-4 mb-3"><i class="fas fa-share-alt mr-2"></i>Media Sosial</h5>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fab fa-facebook-f mr-1 text-primary"></i> Facebook</label>
                          <input type="url" name="social_facebook" class="form-control"
                            value="{{ $setting->social_facebook }}" placeholder="https://...">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fab fa-instagram mr-1 text-danger"></i> Instagram</label>
                          <input type="url" name="social_instagram" class="form-control"
                            value="{{ $setting->social_instagram }}" placeholder="https://...">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fab fa-twitter mr-1 text-info"></i> Twitter / X</label>
                          <input type="url" name="social_twitter" class="form-control"
                            value="{{ $setting->social_twitter }}" placeholder="https://...">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fab fa-youtube mr-1 text-danger"></i> YouTube</label>
                          <input type="url" name="social_youtube" class="form-control"
                            value="{{ $setting->social_youtube }}" placeholder="https://...">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-search mr-2"></i>SEO & Integrasi</h5>
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea name="meta_description" class="form-control"
                        rows="2">{{ $setting->meta_description }}</textarea>
                      <small class="text-muted">Deskripsi ini muncul di hasil pencarian Google.</small>
                    </div>
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <input type="text" name="meta_keywords" class="form-control" value="{{ $setting->meta_keywords }}"
                        placeholder="perizinan, pkbm, dinas">
                    </div>
                    <div class="form-group">
                      <label>Google Maps Embed Code</label>
                      <textarea name="google_maps_embed" class="form-control"
                        rows="4">{{ $setting->google_maps_embed }}</textarea>
                      <small class="text-muted">Tempel kode <code>&lt;iframe&gt;</code> dari fitur Bagikan di Google
                        Maps.</small>
                    </div>

                    <div class="card bg-light border mt-4">
                      <div class="card-body p-3">
                        <div class="custom-control custom-switch">
                          <input type="checkbox" name="show_login_button" class="custom-control-input"
                            id="show_login_button_tab" value="1" {{ $setting->show_login_button ? 'checked' : '' }}>
                          <label class="custom-control-label" for="show_login_button_tab">Tampilkan Tombol Login di
                            Landing Page</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer bg-white text-right py-3 border-top">
            <a href="{{ route('landing') }}" target="_blank" class="btn btn-outline-secondary btn-lg mr-2">
              <i class="fas fa-eye mr-1"></i> Preview
            </a>
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
              <i class="fas fa-save mr-1"></i> SIMPAN PERUBAHAN
            </button>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
      });

      let faqIndex = $('#faq-container .faq-row').length;
      $('#add-faq').click(function () {
        let html = `
                              <div class="faq-row border p-3 mb-3 rounded position-relative">
                                  <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:5px; right:5px;" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                  <div class="form-group">
                                      <label>Pertanyaan</label>
                                      <input type="text" name="faq[${faqIndex}][question]" class="form-control">
                                  </div>
                                  <div class="form-group mb-0">
                                      <label>Jawaban</label>
                                      <textarea name="faq[${faqIndex}][answer]" class="form-control" rows="2"></textarea>
                                  </div>
                              </div>
                          `;
        $('#faq-container').append(html);
        faqIndex++;
      });

      let licenseIndex = $('#license-container .license-row').length;
      $('#add-license').click(function () {
        let html = `
                              <div class="license-row border p-3 mb-3 rounded position-relative">
                                  <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:5px; right:5px;" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                  <div class="row">
                                      <div class="col-8">
                                          <div class="form-group">
                                              <label>Nama Izin</label>
                                              <input type="text" name="license_types[${licenseIndex}][title]" class="form-control">
                                          </div>
                                      </div>
                                      <div class="col-4">
                                          <div class="form-group">
                                              <label>Ikon (Material)</label>
                                              <input type="text" name="license_types[${licenseIndex}][icon]" class="form-control" value="add_card">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label>Deskripsi Singkat</label>
                                      <textarea name="license_types[${licenseIndex}][description]" class="form-control" rows="2"></textarea>
                                  </div>
                              </div>
                          `;
        $('#license-container').append(html);
        licenseIndex++;
      });
    });
  </script>
@endpush