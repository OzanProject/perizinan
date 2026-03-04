@extends('layouts.backend')

@section('title', 'Editor Template Sertifikat')
@section('breadcrumb', 'Desain Template')

@section('content')
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

          <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-success mr-3 mt-1"
            title="Tampilkan bingkai pada PDF">
            <input type="checkbox" class="custom-control-input" id="use-border-checkbox" {{ ($jenisPerizinan->use_border ?? false) ? 'checked' : '' }}>
            <label class="custom-control-label font-weight-bold text-dark small pt-1" for="use-border-checkbox"
              style="cursor: pointer;">Bingkai</label>
          </div>

          <div class="btn-group btn-group-sm mr-3" id="frame-selector-group"
            style="display: {{ ($jenisPerizinan->use_border ?? false) ? 'flex' : 'none' }};">
            <button type="button" class="btn btn-outline-secondary font-weight-bold" onclick="changeFrame('default')"
              id="btn-frame-default">Utama</button>
            <button type="button" class="btn btn-outline-primary font-weight-bold" onclick="changeFrame('paud')"
              id="btn-frame-paud">PAUD</button>
            <button type="button" class="btn btn-outline-info font-weight-bold" onclick="changeFrame('lkp')"
              id="btn-frame-lkp">LKP</button>
          </div>

          <select class="form-control form-control-sm font-weight-bold text-dark mr-2" id="paper-size-selector"
            onchange="updatePaperSize()" style="width: 70px;">
            <option value="A4" {{ ($activePreset->paper_size ?? 'A4') == 'A4' ? 'selected' : '' }}>A4</option>
            <option value="F4" {{ ($activePreset->paper_size ?? 'A4') == 'F4' ? 'selected' : '' }}>F4</option>
          </select>

          <select class="form-control form-control-sm font-weight-bold text-dark mr-3" id="paper-orientation"
            onchange="updatePaperSize()" style="width: 120px;">
            <option value="portrait" {{ ($activePreset->orientation ?? 'portrait') == 'portrait' ? 'selected' : '' }}>
              Portrait</option>
            <option value="landscape" {{ ($activePreset->orientation ?? 'portrait') == 'landscape' ? 'selected' : '' }}>
              Landscape</option>
          </select>

          <button type="button" onclick="openPresetModal()"
            class="btn btn-warning btn-sm shadow-sm font-weight-bold mr-2">
            <i class="fas fa-magic mr-1"></i> Pilih Layout
          </button>
          <button type="button" onclick="submitTemplate()" class="btn btn-primary btn-sm shadow-sm font-weight-bold px-3">
            <i class="fas fa-save mr-1"></i> Simpan
          </button>
        </div>
      </div>

      <div class="card-body p-0 d-flex flex-column overflow-hidden" style="background-color: #e9ecef;">

        <div class="overflow-auto custom-scrollbar d-flex justify-content-center py-5" id="workspace-container"
          style="height: 60vh; transition: all 0.3s ease;">

          <form id="template-form" action="{{ route('super_admin.jenis_perizinan.template.update', $jenisPerizinan) }}"
            method="POST">
            @csrf
            <input type="hidden" name="template_html" id="template-input">
            <input type="hidden" name="use_border" id="use-border-input"
              value="{{ $jenisPerizinan->use_border ? '1' : '0' }}">
            <input type="hidden" name="border_type" id="border-type-input"
              value="{{ old('border_type', $jenisPerizinan->border_type) }}">

            <div id="document-wrapper" class="shadow-lg position-relative mx-3"
              style="background: white; transition: all 0.3s ease;">

              @php
                $namaIzin = strtolower($jenisPerizinan->nama ?? '');
                // Default frame
                $overlayUrl = $frameUrl ?? asset('images/default-border.png');

                if (strpos($namaIzin, 'paud') !== false || strpos($namaIzin, 'tk') !== false) {
                  $overlayUrl = $dinas->watermark_border_paud_img ? asset('storage/' . $dinas->watermark_border_paud_img) : asset('images/bingkai-paud.jpg');
                } elseif (strpos($namaIzin, 'lkp') !== false) {
                  $overlayUrl = asset('images/bingkai-pkbm.jpg');
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

              <div id="editor-canvas" class="document-editor__editable paper-a4-portrait" contenteditable="true">
                {!! $jenisPerizinan->template_html ?? '<div style="text-align:center; padding-top:50px; color:#ccc;"><h2>Kanvas Kosong</h2><p>Klik tombol kuning "Pilih Layout" di atas untuk memulai desain secara otomatis.</p></div>' !!}
              </div>
            </div>
          </form>
        </div>

        <div id="variable-panel" class="bg-white border-top shadow-lg w-100" style="z-index: 10;">
          <div class="p-2 bg-dark text-white text-center font-weight-bold small text-uppercase" style="cursor: pointer;"
            onclick="toggleVariablePanel()">
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

                <button onclick="insertGarisKop()" type="button"
                  class="btn btn-warning btn-sm mb-2 shadow-sm font-weight-bold d-block w-100 mb-3"
                  style="border: 1px solid #333;">
                  <i class="fas fa-grip-lines mr-1"></i> Sisip Garis Kop Surat
                </button>

                <button onclick="insertVar('[LOGO_DINAS]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-image text-info mr-1"></i> Logo Dinas</button>
                <button onclick="insertVar('[KOTA_DINAS]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-city text-info mr-1"></i> Kota</button>
                <button onclick="insertVar('[ALAMAT_DINAS]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-map-marker-alt text-info mr-1"></i> Alamat Dinas</button>
                <button onclick="insertVar('[PIMPINAN_NAMA]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-user-tie text-warning mr-1"></i> Nama Kadis</button>
                <button onclick="insertVar('[PIMPINAN_NIP]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-id-card text-warning mr-1"></i> NIP Kadis</button>
                <button onclick="insertVar('[PIMPINAN_JABATAN]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-briefcase text-warning mr-1"></i> Jabatan</button>
                <button onclick="insertVar('[PIMPINAN_PANGKAT]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-star text-warning mr-1"></i> Pangkat</button>
              </div>

              <div class="col-md-4 mb-3 border-right border-secondary">
                <div class="text-sm font-weight-bold text-dark text-uppercase mb-2 border-bottom border-secondary pb-1">2.
                  Info Surat & Lembaga</div>
                <button onclick="insertVar('[NOMOR_SURAT]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-hashtag text-primary mr-1"></i> No Surat</button>
                <button onclick="insertVar('[TANGGAL_TERBIT]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-calendar-alt text-primary mr-1"></i> Tgl Terbit</button>
                <button onclick="insertVar('[MASA_BERLAKU]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-clock text-primary mr-1"></i> Masa Berlaku</button>
                <button onclick="insertVar('[NAMA_LEMBAGA]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-school text-primary mr-1"></i> Nama Lembaga</button>
                <button onclick="insertVar('[NPSN]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-barcode text-primary mr-1"></i> NPSN</button>
                <button onclick="insertVar('[ALAMAT_LEMBAGA]')" class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i
                    class="fas fa-map text-primary mr-1"></i> Alamat Lembaga</button>
              </div>

              <div class="col-md-4 mb-3">
                <div class="text-sm font-weight-bold text-dark text-uppercase mb-2 border-bottom border-secondary pb-1">3.
                  Data Pemohon (Form)</div>
                <button onclick="insertVar('[DATA:NAMA_PIMPINAN]')"
                  class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i class="fas fa-user text-success mr-1"></i> Nama
                  Pimpinan</button>
                <button onclick="insertVar('[DATA:NAMA_PENYELENGGARA]')"
                  class="btn btn-outline-dark btn-sm mb-1 mr-1 var-btn"><i class="fas fa-users text-success mr-1"></i>
                  Penyelenggara</button>
                @if($jenisPerizinan->form_config)
                  @foreach($jenisPerizinan->form_config as $field)
                    <button onclick="insertVar('[DATA:{{ strtoupper($field['name']) }}]')"
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
                  <div class="card h-100 border-0 shadow-sm transition-all cursor-pointer" onclick="applyPreset('{{ $key }}')"
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

  <style>
    /* TUKAR KERTAS KANVAS TINYMCE */
    .tox-tinymce {
      border: none !important;
      border-radius: 0 !important;
    }

    .document-editor__editable {
      background: white;
      border: 1px solid #d3d3d3;
      padding: 1.5cm 1.5cm;
      /* Adjust padding slightly for TinyMCE */
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.15;
      color: black;
      overflow: visible;
      position: relative;
      z-index: 1;
      transition: all 0.3s ease;
      min-height: auto !important;
      /* TinyMCE uses its own height management */
    }

    .document-editor__editable:focus {
      outline: none;
      box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
      border-color: #80bdff;
    }

    /* UKURAN KERTAS - Menggunakan min-height bukan height */
    .paper-a4-portrait {
      width: 210mm;
      min-height: 297mm;
    }

    .paper-a4-landscape {
      width: 297mm;
      min-height: 210mm;
      padding: 1.5cm 2cm !important;
    }

    .paper-f4-portrait {
      width: 215mm;
      min-height: 330mm;
    }

    .paper-f4-landscape {
      width: 330mm;
      min-height: 215mm;
      padding: 1.5cm 2cm !important;
    }

    /* Spasi Paragraf Rapat */
    .document-editor__editable p {
      margin-top: 0 !important;
      margin-bottom: 4px !important;
      line-height: 1.15 !important;
    }

    .document-editor__editable h1,
    .document-editor__editable h2,
    .document-editor__editable h3,
    .document-editor__editable h4 {
      margin-top: 5px !important;
      margin-bottom: 5px !important;
    }

    .document-editor__editable table {
      margin-bottom: 10px !important;
    }

    .document-editor__editable td,
    .document-editor__editable th {
      padding: 2px 4px !important;
      line-height: 1.15 !important;
      border: 1px dashed #ccc;
      /* Tampilkan border dashed tipis saat di editor agar mudah dilihat */
    }

    .document-editor__editable figure {
      margin: 0 auto 10px auto !important;
      text-align: center;
    }

    /* Variabel & Scrollbar */
    .var-badge {
      display: inline-block;
      padding: 1px 4px;
      font-size: 10pt !important;
      font-weight: bold;
      color: #004085;
      background: #cce5ff;
      border: 1px solid #b8daff;
      border-radius: 3px;
      font-family: monospace;
      cursor: not-allowed;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #adb5bd;
      border-radius: 10px;
    }
  </style>

  {{-- TinyMCE Integration --}}
  @push('scripts')
    <script src="https://cdn.tiny.cloud/1/nuww14eec90ohvwjq67sjn9fcqkn5mmyywap0caie6rk7xhs/tinymce/7/tinymce.min.js"
      referrerpolicy="origin"></script>

    <script>
      let editorInstance;
      const presets = @json($presets ?? []);
      const logoUrl = @json($logoUrl ?? '');

      $(document).ready(function () {
        // Otomatis Tutup Sidebar Admin Lte
        $('body').addClass('sidebar-collapse');

        tinymce.init({
          selector: '#editor-canvas',
          inline: true,
          fixed_toolbar_container: '#toolbar-container',
          toolbar_persist: true, // Toolbar selalu tampil, tidak perlu klik editor dulu

          plugins: 'advlist autolink lists link charmap preview searchreplace visualblocks code fullscreen table help wordcount directionality nonbreaking',

          toolbar: [
            'undo redo | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat',
            'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | charmap code preview',
            'tableprops tablecellprops | addcolbefore addcolafter deleterow deletecol | tableinsertrowbefore tableinsertrowafter'
          ],

          table_column_resizing: 'resizetable',
          table_resize_bars: true,
          table_default_attributes: { border: '0', style: 'width:100%; border-collapse:collapse;' },
          table_default_styles: { 'width': '100%', 'border-collapse': 'collapse' },
          font_family_formats: 'Times New Roman=times new roman,times,serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Tahoma=tahoma,arial,helvetica,sans-serif;',
          font_size_formats: '8pt 10pt 11pt 12pt 14pt 18pt 24pt 36pt 48pt',
          menubar: false,

          content_style: "body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.15; } .var-badge { display: inline-block; padding: 1px 4px; font-weight: bold; color: #004085; background: #cce5ff; border: 1px solid #b8daff; border-radius: 3px; font-family: monospace; }",

          // Pastikan TinyMCE tidak menghapus atribut data-logo pada img tag
          extended_valid_elements: 'img[src|alt|style|width|height|contenteditable|data-logo|data-mce-src]',

          setup: function (editor) {
            editorInstance = editor;
            editor.on('init', function () {
              const currentData = editor.getContent();
              editor.setContent(processVariablesForView(currentData));
              updatePaperSize();
            });
          }
        });
      });

      function openPresetModal() { $('#presetModal').modal('show'); }

      function toggleVariablePanel() {
        $('#variable-content').slideToggle('fast', function () {
          if ($(this).is(':visible')) {
            $('#icon-toggle-var').removeClass('fa-chevron-up').addClass('fa-chevron-down');
          } else {
            $('#icon-toggle-var').removeClass('fa-chevron-down').addClass('fa-chevron-up');
          }
        });
      }

      function updatePaperSize() {
        const size = document.getElementById('paper-size-selector').value.toLowerCase();
        const orient = document.getElementById('paper-orientation').value;
        const canvas = document.getElementById('editor-canvas');

        canvas.classList.remove('paper-a4-portrait', 'paper-a4-landscape', 'paper-f4-portrait', 'paper-f4-landscape');
        canvas.classList.add(`paper-${size}-${orient}`);
      }

      document.getElementById('use-border-checkbox').addEventListener('change', function () {
        const isChecked = this.checked;
        document.getElementById('frame-overlay').style.display = isChecked ? 'block' : 'none';
        document.getElementById('frame-selector-group').style.display = isChecked ? 'flex' : 'none';
        document.getElementById('use-border-input').value = isChecked ? '1' : '0';
      });

      const framePaths = {
        'default': '{{ $frameUrl ?? asset('images/default-border.png') }}',
        'paud': '{{ $dinas->watermark_border_paud_img ? asset('storage/' . $dinas->watermark_border_paud_img) : asset('images/bingkai-paud.jpg') }}',
        'lkp': '{{ asset('images/bingkai-pkbm.jpg') }}'
      };

      function changeFrame(type) {
        const overlay = document.getElementById('frame-overlay');
        const borderTypeInput = document.getElementById('border-type-input');

        if (overlay && framePaths[type]) {
          overlay.style.backgroundImage = `url('${framePaths[type]}')`;
          if (borderTypeInput) borderTypeInput.value = type;

          ['default', 'paud', 'lkp'].forEach(t => {
            const btn = document.getElementById(`btn-frame-${t}`);
            if (btn) {
              if (t === type) {
                btn.classList.add('active');
                if (t === 'default') btn.className = 'btn btn-secondary font-weight-bold active';
                if (t === 'paud') btn.className = 'btn btn-primary font-weight-bold active';
                if (t === 'lkp') btn.className = 'btn btn-info font-weight-bold active';
              } else {
                btn.classList.remove('active');
                if (t === 'default') btn.className = 'btn btn-outline-secondary font-weight-bold';
                if (t === 'paud') btn.className = 'btn btn-outline-primary font-weight-bold';
                if (t === 'lkp') btn.className = 'btn btn-outline-info font-weight-bold';
              }
            }
          });
        }
      }

      $(document).ready(function () {
        const savedType = '{{ $jenisPerizinan->border_type }}';
        if (savedType) {
          changeFrame(savedType);
        } else {
          const namaIzin = '{{ strtolower($jenisPerizinan->nama ?? '') }}';
          if (namaIzin.includes('paud') || namaIzin.includes('tk')) {
            changeFrame('paud');
          } else if (namaIzin.includes('lkp')) {
            changeFrame('lkp');
          } else {
            changeFrame('default');
          }
        }
      });

      function applyPreset(key) {
        if (confirm(`Apakah Anda yakin ingin mengganti desain saat ini dengan preset "${presets[key].name}"? Semua teks manual yang belum disimpan akan terganti.`)) {
          const preset = presets[key];
          editorInstance.setContent(processVariablesForView(preset.html));

          document.getElementById('paper-size-selector').value = preset.paper_size || 'F4';
          document.getElementById('paper-orientation').value = preset.orientation || 'portrait';
          updatePaperSize();

          const borderCb = document.getElementById('use-border-checkbox');
          const borderInput = document.getElementById('use-border-input');
          const frameOl = document.getElementById('frame-overlay');

          if (preset.use_border) {
            borderCb.checked = true;
            borderInput.value = '1';
            if (frameOl) frameOl.style.display = 'block';
          } else {
            borderCb.checked = false;
            borderInput.value = '0';
            if (frameOl) frameOl.style.display = 'none';
          }

          $('#presetModal').modal('hide');
        }
      }

      function insertVar(val) {
        if (!editorInstance) return;
        // Penyesuaian sintaks insert untuk TinyMCE
        editorInstance.execCommand('mceInsertContent', false, `<span class="var-badge" contenteditable="false">${val}</span>&nbsp;`);
      }

      function insertGarisKop() {
        if (!editorInstance) return;
        const htmlLined = '<hr style="border: none; border-top: 3px solid black; border-bottom: 1px solid black; height: 5px; background: transparent; margin: 10px 0;">';
        editorInstance.execCommand('mceInsertContent', false, htmlLined);
      }

      function processVariablesForView(html) {
        if (!html) return '';
        // Add data-logo="1" so we can reliably revert back even if TinyMCE mutates the tag
        if (logoUrl) html = html.replace(/\[LOGO_DINAS\]/g, `<img src="${logoUrl}" data-logo="1" style="width:75px; height:auto; display:inline-block;" contenteditable="false">`);
        return html.replace(/\[([A-Z0-9_:]+)\]/g, function (match, p1) {
          if (p1 === 'LOGO_DINAS') return match;
          return `<span class="var-badge" contenteditable="false">[${p1}]</span>`;
        });
      }

      function revertVariablesForSave(html) {
        if (!html) return '';
        // Use data-logo="1" marker for reliable detection regardless of TinyMCE attribute mutations
        if (logoUrl) {
          html = html.replace(/<img[^>]*data-logo=["|']1["|'][^>]*>/gi, '[LOGO_DINAS]');
          // Fallback: also match by src in case data-logo is stripped
          const escapedUrl = logoUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
          const regex = new RegExp(`<img[^>]*src=["']${escapedUrl}["'][^>]*>`, 'gi');
          html = html.replace(regex, '[LOGO_DINAS]');
        }
        html = html.replace(/<span[^>]*class="[^"]*var-badge[^"]*"[^>]*>\[([A-Z0-9_:]+)\]<\/span>/g, '[$1]');
        return html;
      }

      function submitTemplate() {
        if (!editorInstance) return;
        document.getElementById('template-input').value = revertVariablesForSave(editorInstance.getContent());
        document.getElementById('template-form').submit();
      }

      document.getElementById('search-var').addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.var-btn').forEach(btn => {
          btn.style.display = btn.innerText.toLowerCase().includes(term) ? 'inline-block' : 'none';
        });
      });
    </script>
  @endpush
@endsection