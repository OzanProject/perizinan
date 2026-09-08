@extends('layouts.backend')

@section('title', 'Editor Template Sertifikat')
@section('breadcrumb', 'Desain Template')

@section('content')
  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/template-editor.css') }}?v={{ time() }}">
  @endpush
  <div class="container-fluid text-dark">

    <div class="row mb-3 align-items-center">
      <div class="col-sm-6 text-center text-sm-left">
        <h3 class="m-0 font-weight-bold text-dark"><i class="fas fa-file-word text-primary mr-2"></i> Editor Dokumen</h3>
        <p class="text-muted small mt-1">Desain Template: {{ $jenisPerizinan->nama }}</p>
      </div>
    </div>

    <div class="card card-outline card-primary shadow-sm border-0">

      <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap">
        <div id="toolbar-container" class="mb-2 mb-md-0 flex-grow-1" style="min-height: 40px; overflow-x: auto;">
        </div>

        <div class="d-flex align-items-center ml-auto flex-wrap">

          <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success mr-2 mt-1"
            title="Tampilkan logo watermark di tengah PDF">
            <input type="checkbox" class="custom-control-input" id="use-watermark-checkbox" {{ ($jenisPerizinan->use_watermark ?? false) ? 'checked' : '' }}>
            <label class="custom-control-label font-weight-bold text-dark small pt-1" for="use-watermark-checkbox"
              style="cursor: pointer;">Logo Watermark</label>
          </div>

          <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success mr-3 mt-1"
            title="Tampilkan bingkai pada PDF">
            <input type="checkbox" class="custom-control-input" id="use-border-checkbox" {{ ($jenisPerizinan->use_border ?? false) ? 'checked' : '' }}>
            <label class="custom-control-label font-weight-bold text-dark small pt-1" for="use-border-checkbox"
              style="cursor: pointer;">Bingkai</label>
          </div>

          <div class="btn-group btn-group-sm mr-3" id="frame-selector-group"
            style="display: {{ ($jenisPerizinan->use_border ?? false) ? 'flex' : 'none' }};">
            <button type="button" class="btn btn-outline-secondary font-weight-bold btn-change-frame" data-frame-type="default"
              id="btn-frame-default">Utama</button>
            <button type="button" class="btn btn-outline-primary font-weight-bold btn-change-frame" data-frame-type="paud"
              id="btn-frame-paud">PAUD</button>
            <button type="button" class="btn btn-outline-info font-weight-bold btn-change-frame" data-frame-type="lkp"
              id="btn-frame-lkp">LKP</button>
          </div>

          <select class="form-control form-control-sm font-weight-bold text-dark mr-2" id="paper-size-selector"
            style="width: 150px;">
            <option value="a4" {{ strtoupper($jenisPerizinan->paper_size ?? $activePreset->paper_size ?? 'A4') == 'A4' ? 'selected' : '' }}>A4 (21 x 29.7 cm)</option>
            <option value="f4" {{ strtoupper($jenisPerizinan->paper_size ?? $activePreset->paper_size ?? 'A4') == 'F4' ? 'selected' : '' }}>F4 / Folio (21.5 x 33 cm)</option>
          </select>

          <select class="form-control form-control-sm font-weight-bold text-dark mr-3" id="paper-orientation"
            style="width: 120px;">
            <option value="portrait" {{ strtolower($jenisPerizinan->orientation ?? $activePreset->orientation ?? 'portrait') == 'portrait' ? 'selected' : '' }}>Portrait</option>
            <option value="landscape" {{ strtolower($jenisPerizinan->orientation ?? $activePreset->orientation ?? 'portrait') == 'landscape' ? 'selected' : '' }}>Landscape</option>
          </select>

          <button type="button" id="btn-open-preset" onclick="TemplateEditor.openPresetModal()"
            class="btn btn-warning btn-sm shadow-sm font-weight-bold mr-2">
            <i class="fas fa-magic mr-1"></i> Pilih Layout
          </button>
          
          <div class="d-inline-block position-relative mr-2">
            <button type="button" class="btn btn-info btn-sm shadow-sm font-weight-bold" onclick="document.getElementById('template_word').click()">
              <i class="fas fa-file-word mr-1"></i> Upload DOCX Asli
            </button>
            @if($jenisPerizinan->template_word_path)
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="width:10px; height:10px; right:-2px; top:-2px;" title="DOCX Template Tersedia"></span>
            @endif
          </div>

          <button type="button" id="btn-submit-template" class="btn btn-primary btn-sm shadow-sm font-weight-bold px-3">
            <i class="fas fa-save mr-1"></i> Simpan
          </button>
        </div>
      </div>

      <div class="card-body p-0 d-flex flex-column overflow-hidden" style="background-color: #e9ecef;">

        <div class="overflow-auto custom-scrollbar d-flex justify-content-center py-5" id="workspace-container"
          style="height: 60vh; transition: all 0.3s ease;">

          <form id="template-form" action="{{ route('super_admin.jenis_perizinan.template.update', $jenisPerizinan) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="template_html" id="template-input">
            <input type="hidden" name="use_border" id="use-border-input"
              value="{{ $jenisPerizinan->use_border ? '1' : '0' }}">
            <input type="hidden" name="border_type" id="border-type-input"
              value="{{ old('border_type', $jenisPerizinan->border_type) }}">
            <input type="hidden" name="use_watermark" id="use-watermark-input"
              value="{{ $jenisPerizinan->use_watermark ? '1' : '0' }}">
            <input type="hidden" name="paper_size" id="paper-size-input"
              value="{{ old('paper_size', $jenisPerizinan->paper_size ?? $activePreset->paper_size ?? 'A4') }}">
            <input type="hidden" name="orientation" id="orientation-input"
              value="{{ old('orientation', $jenisPerizinan->orientation ?? $activePreset->orientation ?? 'portrait') }}">
            <input type="file" name="template_word" id="template_word" style="display: none;" accept=".docx" onchange="if(this.files.length) alert('File DOCX terpilih: ' + this.files[0].name + '. Jangan lupa klik Simpan!');">

            <div id="document-wrapper" class="shadow-lg position-relative mx-3"
              style="background: white; transition: all 0.3s ease;">

              @php
                $namaIzin = strtolower($jenisPerizinan->nama ?? '');
                // Default frame
                $overlayUrl = $frameUrl ?? asset('images/default-border.png');

                if (strpos($namaIzin, 'paud') !== false || strpos($namaIzin, 'tk') !== false) {
                  $overlayUrl = $dinas->watermark_border_paud_img ? asset('storage/' . $dinas->watermark_border_paud_img) : asset('images/bingkai-paud.jpg');
                } elseif (strpos($namaIzin, 'lkp') !== false || strpos($namaIzin, 'pkbm') !== false) {
                  $overlayUrl = $frameUrl ?? asset('images/default-border.png');
                }
              @endphp

              <div id="frame-overlay" style="
                  position: absolute; top: 0; left: 0; right: 0; bottom: 0;
                  pointer-events: none; z-index: 2;
                  background-image: url('{{ $overlayUrl }}');
                  background-size: 100% 100%; background-repeat: no-repeat;
                  opacity: {{ $dinas->watermark_border_opacity ?? 0.9 }};
                  display: {{ ($jenisPerizinan->use_border ?? false) ? 'block' : 'none' }};
              "></div>

              <div id="watermark-overlay" style="
                  position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                  pointer-events: none; z-index: 11;
                  opacity: {{ $watermarkOpacity }};
                  display: {{ ($jenisPerizinan->use_watermark ?? false) ? 'block' : 'none' }};
              ">
                  <img src="{{ $watermarkUrl }}" style="width: 300px; height: auto; object-fit: contain;">
              </div>

              <div id="editor-canvas" class="document-editor__editable paper-a4-portrait" contenteditable="true">
                {!! $jenisPerizinan->template_html ?? '<div style="text-align:center; padding-top:50px; color:#ccc;"><h2>Kanvas Kosong</h2><p>Klik tombol kuning "Pilih Layout" di atas untuk memulai desain secara otomatis.</p></div>' !!}
              </div>
            </div>
          </form>
        </div>

        <div id="variable-panel" class="bg-white border-top shadow-lg w-100" style="z-index: 10;">
          <div class="p-2 bg-dark text-white text-center font-weight-bold small text-uppercase" style="cursor: pointer;"
            id="toggle-var-panel">
            <i class="fas fa-code mr-1"></i> Panel Data Otomatis & Alat <i class="fas fa-chevron-down ml-2"
              id="icon-toggle-var"></i>
          </div>

          <div id="variable-content" class="p-3 bg-light custom-scrollbar" style="max-height: 25vh; overflow-y: auto;">
            <input type="text" id="search-var" class="form-control form-control-sm rounded-pill text-center mb-3 mx-auto"
              placeholder="Cari data (cth: nama, nip)..." style="max-width: 400px;">

            <div class="row">
              <div class="col-md-4 mb-3 border-right border-secondary">
                <div class="text-sm font-weight-bold text-dark text-uppercase mb-2 border-bottom border-secondary pb-1">1.
                  Kop & Pejabat</div>

                <button data-insert-var="GARIS_KOP" type="button"
                  class="btn btn-warning btn-sm mb-2 shadow-sm font-weight-bold d-block w-100 mb-3 var-btn"
                  style="border: 1px solid #333;">
                  <i class="fas fa-grip-lines mr-1"></i> Sisip Garis Kop Surat
                </button>

                <button data-insert-var="[LOGO_DINAS]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-image text-info mr-1"></i> Logo Dinas</button>
                <button data-insert-var="[KOTA_DINAS]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-city text-info mr-1"></i> Kota</button>
                <button data-insert-var="[ALAMAT_DINAS]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-map-marker-alt text-info mr-1"></i> Alamat Dinas</button>
                <button data-insert-var="[PIMPINAN_NAMA]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-user-tie text-warning mr-1"></i> Nama Kadis</button>
                <button data-insert-var="[PIMPINAN_NIP]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-id-card text-warning mr-1"></i> NIP Kadis</button>
                <button data-insert-var="[PIMPINAN_JABATAN]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-briefcase text-warning mr-1"></i> Jabatan</button>
                <button data-insert-var="[PIMPINAN_PANGKAT]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-star text-warning mr-1"></i> Pangkat</button>
              </div>

              <div class="col-md-4 mb-3 border-right border-secondary">
                <div class="text-sm font-weight-bold text-dark text-uppercase mb-2 border-bottom border-secondary pb-1">2.
                  Info Surat & Lembaga</div>
                <button data-insert-var="[NOMOR_SURAT]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-hashtag text-primary mr-1"></i> No Surat</button>
                <button data-insert-var="[TANGGAL_TERBIT]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-calendar-alt text-primary mr-1"></i> Tgl Terbit</button>
                <button data-insert-var="[MASA_BERLAKU]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-clock text-primary mr-1"></i> Masa Berlaku</button>
                <button data-insert-var="[NAMA_LEMBAGA]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-school text-primary mr-1"></i> Nama Lembaga</button>
                <button data-insert-var="[NPSN]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-barcode text-primary mr-1"></i> NPSN</button>
                <button data-insert-var="[ALAMAT_LEMBAGA]" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-map text-primary mr-1"></i> Alamat Lembaga</button>
              </div>

              <div class="col-md-4 mb-3">
                <div class="text-sm font-weight-bold text-dark text-uppercase mb-2 border-bottom border-secondary pb-1">3.
                  Data Pemohon (Form)</div>
                <button data-insert-var="[DATA:NAMA_PIMPINAN]"
                  class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i class="fas fa-user text-success mr-1"></i> Nama
                  Pimpinan</button>
                <button data-insert-var="[DATA:NAMA_PENYELENGGARA]"
                  class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i class="fas fa-users text-success mr-1"></i>
                  Penyelenggara</button>
                @if($jenisPerizinan->form_config)
                  @foreach($jenisPerizinan->form_config as $field)
                    <button data-insert-var="[DATA:{{ strtoupper($field['name']) }}]"
                      class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn">
                      <i class="fas fa-check-circle text-success mr-1"></i> {{ $field['label'] }}
                    </button>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="presetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-light border-0 py-3">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-magic text-warning mr-2"></i> Pilih Tata Letak
            Otomatis (Preset)</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4 bg-light">
          <div class="row">
            @if(isset($presets) && is_array($presets))
              @foreach($presets as $key => $preset)
                <div class="col-md-4 mb-4">
                  <div class="card h-100 border-0 shadow-sm transition-all cursor-pointer btn-apply-preset" data-preset-key="{{ $key }}" onclick="TemplateEditor.applyPreset('{{ $key }}')"
                    style="cursor: pointer;">
                    <div class="card-body p-0">
                      <div class="bg-white p-3 border-bottom overflow-hidden d-flex justify-content-center align-items-center"
                        style="height: 160px; background-color: #f8f9fa !important;">
                        <i
                          class="fas {{ ($preset['orientation'] ?? 'portrait') == 'landscape' ? 'fa-file-invoice' : 'fa-file-alt' }} fa-4x text-{{ ($preset['use_border'] ?? false) ? 'warning' : 'primary' }} opacity-50"></i>
                      </div>
                      <div class="p-3">
                        <h6 class="font-weight-bold text-dark mb-1">{{ $preset['name'] ?? 'Template' }}</h6>
                        <p class="text-muted small mb-0">{{ $preset['description'] ?? '' }}</p>
                        <div class="mt-2">
                          <span class="badge badge-light border">{{ $preset['paper_size'] ?? 'A4' }}</span>
                          <span class="badge badge-light border">{{ ucfirst($preset['orientation'] ?? 'Portrait') }}</span>
                          @if($preset['use_border'] ?? false)
                            <span class="badge badge-warning text-dark"><i class="fas fa-border-style"></i> Ada Bingkai</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="card-footer bg-primary text-white border-0 text-center py-2">
                      <span class="font-weight-bold small">Gunakan Layout Ini</span>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TinyMCE & Editor Integration --}}
  @push('scripts')
    <script src="https://cdn.tiny.cloud/1/{{ $dinas->tinymce_api_key ?: env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/7/tinymce.min.js"
      referrerpolicy="origin"></script>

    <script>
      window.TemplateEditorConfig = {
        tinymceApiKey: "{{ $dinas->tinymce_api_key ?: env('TINYMCE_API_KEY', 'no-api-key') }}",
        logoUrl: @json($logoUrl ?? ''),
        presets: @json($presets ?? []),
        savedBorderType: '{{ $jenisPerizinan->border_type }}',
        namaIzin: '{{ strtolower($jenisPerizinan->nama ?? '') }}',
        framePaths: {
          'default': '{{ $frameUrl ?? asset('images/default-border.png') }}',
          'paud': '{{ $dinas->watermark_border_paud_img ? asset('storage/' . $dinas->watermark_border_paud_img) : asset('images/bingkai-paud.jpg') }}',
          'lkp': '{{ $frameUrl ?? asset('images/default-border.png') }}'
        }
      };
    </script>
    <script src="{{ asset('js/template-editor.js') }}?v={{ time() }}"></script>

    <script>
      // Sinkronkan dropdown ukuran kertas & orientasi ke hidden input yang dikirim ke server.
      // Tanpa ini, perubahan dropdown TIDAK tersimpan karena yang dikirim form adalah hidden input-nya.
      document.addEventListener('DOMContentLoaded', function () {
        var sizeSelect   = document.getElementById('paper-size-selector');
        var sizeInput    = document.getElementById('paper-size-input');
        var orientSelect = document.getElementById('paper-orientation');
        var orientInput  = document.getElementById('orientation-input');

        if (sizeSelect && sizeInput) {
          // Sinkron saat pertama kali load (pastikan hidden input = dropdown)
          sizeInput.value = sizeSelect.value;

          sizeSelect.addEventListener('change', function () {
            sizeInput.value = this.value;
          });
        }

        if (orientSelect && orientInput) {
          orientInput.value = orientSelect.value;

          orientSelect.addEventListener('change', function () {
            orientInput.value = this.value;
          });
        }
      });
    </script>
  @endpush
@endsection