@extends('layouts.backend')

@section('title', 'Editor Template Sertifikat')

@section('content')
  <div class="container-fluid p-0" style="margin-top: -15px;">
    <!-- Editor Header Toolbar -->
    <div class="card card-outline card-primary shadow-sm mb-0 rounded-0 border-left-0 border-right-0">
      <div class="card-header bg-white py-2">
        <div class="row align-items-center">
          <div class="col-md-4">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-certificate mr-2 text-primary"></i>
              Editor: <span class="text-muted small">{{ $jenisPerizinan->nama }}</span>
            </h3>
          </div>
          <div class="col-md-8 text-right">
            <div class="btn-group mr-2">
              <div class="custom-control custom-checkbox d-inline-flex align-items-center mr-3 bg-white px-2 rounded border shadow-sm" style="height: 31px;">
                <input type="checkbox" class="custom-control-input" id="use-border-checkbox"
                  {{ ($jenisPerizinan->use_border ?? false) ? 'checked' : '' }}>
                <label class="custom-control-label small font-weight-bold text-dark pt-1" for="use-border-checkbox" style="cursor: pointer;">
                  Gunakan Bingkai
                </label>
              </div>
              <button type="button" onclick="toggleWatermark()" id="btn-toggle-watermark"
                class="btn btn-outline-secondary btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-eye-slash mr-1"></i> Sembunyikan Bingkai
              </button>
              <button type="button" onclick="openPresetModal()" class="btn btn-warning btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-magic mr-1"></i> Gunakan Preset
              </button>
            </div>
            <button type="button" onclick="submitTemplate()"
              class="btn btn-primary btn-sm shadow-sm px-4 font-weight-bold">
              <i class="fas fa-save mr-1"></i> Simpan Template
            </button>
          </div>
        </div>
      </div>

      <!-- Native Text Editor Toolbar -->
      <div class="card-body py-2 px-3 bg-light border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-center">
          <!-- Font & Size -->
          <div class="btn-group mr-3 mb-1 shadow-sm px-1 bg-white rounded">
            <select onchange="applyFontSize(this.value)" class="form-control form-control-sm border-0 font-weight-bold"
              style="width: 70px;" title="Font Size">
              @foreach([8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72] as $size)
                <option value="{{ $size }}pt" {{ $size == 11 ? 'selected' : '' }}>{{ $size }}</option>
              @endforeach
            </select>
            <div class="border-right my-1 mx-1"></div>
            <button type="button" onclick="document.getElementById('color-picker').click()"
              class="btn btn-link text-dark p-2" title="Text Color">
              <i class="fas fa-font" style="border-bottom: 3px solid #000;" id="color-indicator"></i>
              <input type="color" id="color-picker" onchange="applyColor(this.value)" style="display: none;">
            </button>
          </div>

          <!-- Formatting -->
          <div class="btn-group mr-3 mb-1 shadow-sm px-1 bg-white rounded">
            <button type="button" onclick="execCmd('bold')" class="btn btn-link text-dark p-2" title="Bold"><i
                class="fas fa-bold"></i></button>
            <button type="button" onclick="execCmd('italic')" class="btn btn-link text-dark p-2" title="Italic"><i
                class="fas fa-italic"></i></button>
            <button type="button" onclick="execCmd('underline')" class="btn btn-link text-dark p-2" title="Underline"><i
                class="fas fa-underline"></i></button>
          </div>

          <!-- Paragraph Settings (Line Height) -->
          <div class="btn-group mr-3 mb-1 shadow-sm px-1 bg-white rounded align-items-center">
            <span class="small text-muted px-2"><i class="fas fa-arrows-alt-v mr-1"></i> Line Height</span>
            <select onchange="applyLineHeight(this.value)" class="form-control form-control-sm border-0 bg-transparent"
              style="width: 65px;">
              <option value="1">1.0</option>
              <option value="1.15">1.15</option>
              <option value="1.3" selected>1.3</option>
              <option value="1.5">1.5</option>
              <option value="2">2.0</option>
              <option value="3">3.0</option>
            </select>
          </div>

          <!-- Alignment -->
          <div class="btn-group mr-3 mb-1 shadow-sm px-1 bg-white rounded">
            <button type="button" onclick="execCmd('justifyLeft')" class="btn btn-link text-dark p-2"
              title="Align Left"><i class="fas fa-align-left"></i></button>
            <button type="button" onclick="execCmd('justifyCenter')" class="btn btn-link text-dark p-2"
              title="Align Center"><i class="fas fa-align-center"></i></button>
            <button type="button" onclick="execCmd('justifyRight')" class="btn btn-link text-dark p-2"
              title="Align Right"><i class="fas fa-align-right"></i></button>
            <button type="button" onclick="execCmd('justifyFull')" class="btn btn-link text-dark p-2" title="Justify"><i
                class="fas fa-align-justify"></i></button>
          </div>

          <!-- Lists & Indentation -->
          <div class="btn-group mb-1 shadow-sm px-1 bg-white rounded">
            <button type="button" onclick="execCmd('insertUnorderedList')" class="btn btn-link text-dark p-2"
              title="Bullets"><i class="fas fa-list-ul"></i></button>
            <button type="button" onclick="execCmd('insertOrderedList')" class="btn btn-link text-dark p-2"
              title="Numbering"><i class="fas fa-list-ol"></i></button>
            <div class="border-right my-1 mx-1"></div>
            <button type="button" onclick="execCmd('outdent')" class="btn btn-link text-dark p-2"
              title="Decrease Indent"><i class="fas fa-outdent"></i></button>
            <button type="button" onclick="execCmd('indent')" class="btn btn-link text-dark p-2"
              title="Increase Indent"><i class="fas fa-indent"></i></button>
            <div class="border-right my-1 mx-1"></div>
            <button type="button" onclick="insertTable()" class="btn btn-link text-dark p-2" title="Table"><i
                class="fas fa-table"></i></button>
            <button type="button" onclick="document.getElementById('image-upload').click()"
              class="btn btn-link text-dark p-2" title="Sisipkan Gambar">
              <i class="fas fa-image"></i>
            </button>
            <input type="file" id="image-upload" style="display: none;" accept="image/*" onchange="insertImage(this)">
          </div>
        </div>
      </div>
    </div>

    <!-- Main Workspace -->
    <div class="d-flex bg-light" style="height: calc(100vh - 180px); overflow: hidden;">
      <!-- Canvas Side (Flexible) -->
      <div class="flex-grow-1 p-5 overflow-auto custom-scrollbar bg-gray-dark border-right shadow-inner"
        onclick="focusCanvas(event)">
        <form id="template-form" action="{{ route('super_admin.jenis_perizinan.template.update', $jenisPerizinan) }}"
          method="POST">
          @csrf
          <input type="hidden" name="template_html" id="template-input">
          <input type="hidden" name="use_border" id="use-border-input"
            value="{{ $jenisPerizinan->use_border ? '1' : '0' }}">

          @php
            $paper = $activePreset->paper_size ?? 'A4';
            $orientation = $activePreset->orientation ?? 'portrait';
            $isLandscape = $orientation == 'landscape';

            $dims = [
              'A4' => ['portrait' => ['w' => '210mm', 'h' => '297mm'], 'landscape' => ['w' => '297mm', 'h' => '210mm']],
              'F4' => ['portrait' => ['w' => '215mm', 'h' => '330mm'], 'landscape' => ['w' => '330mm', 'h' => '215mm']],
            ];

            $w = $dims[$paper][$orientation]['w'] ?? '210mm';
            $minH = $dims[$paper][$orientation]['h'] ?? '297mm';

            $mt = $activePreset->margin_top ?? 20;
            $mr = $activePreset->margin_right ?? 20;
            $mb = $activePreset->margin_bottom ?? 20;
            $ml = $activePreset->margin_left ?? 20;
            $padding = "{$mt}mm {$mr}mm {$mb}mm {$ml}mm";

            $isHerreg = $jenisPerizinan->use_border;
          @endphp

          <div id="canvas-wrapper" class="canvas-container mx-auto shadow-lg mb-5"
            style="width: {{ $w }}; position: relative; transition: width 0.3s ease;">
            @if($frameUrl)
              <div id="frame-overlay" class="frame-overlay" style="
                                                                position: absolute;
                                                                top: 0; left: 0; width: 100%; height: 100%;
                                                                pointer-events: none;
                                                                z-index: 2;
                                                                background-image: url('{{ $frameUrl }}');
                                                                background-size: 100% 100%;
                                                                opacity: {{ $dinas->watermark_border_opacity ?? 0.2 }};
                                                                display: {{ ($watermarkEnabled && $isHerreg) ? 'block' : 'none' }};
                                                              "></div>
            @endif

            <div id="editor-canvas" contenteditable="true" class="a4-paper font-serif-doc bg-white border-0"
              style="padding: {{ $padding }}; width: 100%; min-height: {{ $minH }}; position: relative; z-index: 1;">
              {!! $jenisPerizinan->template_html ?? '
                                                          <div class="text-center" style="border-bottom: 4px double black; padding-bottom: 15px; margin-bottom: 25px;">
                                                              <h3 style="font-weight: bold; text-transform: uppercase;">Pemerintah Kabupaten Suka Maju</h3>
                                                              <h2 style="font-weight: bold; text-transform: uppercase;">Dinas Pendidikan dan Kebudayaan</h2>
                                                              <p style="margin: 0;">Jl. Contoh Alamat No. 123, Telp: (0262) 123456</p>
                                                          </div>
                                                      ' !!}
            </div>
          </div>
        </form>
      </div>

      <!-- Sidebar Variables (Fixed Width) -->
      <div class="bg-white border-left shadow-sm d-flex flex-column" style="width: 350px; min-width: 350px;">
        <div class="p-3 border-bottom bg-light">
          <h5 class="font-weight-bold mb-0 small text-uppercase tracking-wider"><i
              class="fas fa-database mr-2 text-primary"></i> Variabel Dinamis</h5>
        </div>
        <div class="p-3 flex-grow-1 overflow-auto custom-scrollbar">
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
            </div>
            <input type="text" id="search-var" class="form-control border-left-0" placeholder="Cari variabel...">
          </div>

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-danger text-uppercase mb-2 d-block">Mapping Premium Garut</label>
            <div class="d-flex flex-wrap">
              <button type="button" onclick="insertVar('[DATA:NAMA_PIMPINAN]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">Nama Pimpinan</button>
              <button type="button" onclick="insertVar('[DATA:NAMA_PENYELENGGARA]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">Nama Penyelenggara</button>
              <button type="button" onclick="insertVar('[DATA:JENIS_PENDIDIKAN]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">Jenis Pendidikan</button>
              <button type="button" onclick="insertVar('[DATA:KECAMATAN]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">Kecamatan</button>
              <button type="button" onclick="insertVar('[DATA:NOMOR_IZIN_PENDIRIAN]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">No. Izin Pendirian</button>
              <button type="button" onclick="insertVar('[DATA:TANGGAL_IZIN_PENDIRIAN]')"
                class="btn btn-outline-danger btn-xs m-1 var-btn">Tgl Izin Pendirian</button>
            </div>
          </div>

          @if($jenisPerizinan->form_config)
            <div class="mb-4">
              <label class="text-xs font-weight-bold text-success text-uppercase mb-2 d-block">Mapping Data Ajuan</label>
              <div class="d-flex flex-wrap">
                @foreach($jenisPerizinan->form_config as $field)
                  <button type="button" onclick="insertVar('[DATA:{{ strtoupper($field['name']) }}]')"
                    class="btn btn-outline-success btn-xs m-1 var-btn">
                    {{ $field['label'] }} <i class="fas fa-plus small ml-1"></i>
                  </button>
                @endforeach
              </div>
            </div>
          @endif

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Identitas Lembaga</label>
            <div class="d-flex flex-wrap">
              <button onclick="insertVar('[NAMA_LEMBAGA]')" class="btn btn-outline-primary btn-xs m-1 var-btn">Nama
                Lembaga</button>
              <button onclick="insertVar('[NPSN]')" class="btn btn-outline-primary btn-xs m-1 var-btn">NPSN</button>
              <button onclick="insertVar('[ALAMAT_LEMBAGA]')" class="btn btn-outline-primary btn-xs m-1 var-btn">Alamat
                Lembaga</button>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Identitas Izin</label>
            <div class="d-flex flex-wrap">
              <button onclick="insertVar('[NOMOR_SURAT]')" class="btn btn-outline-primary btn-xs m-1 var-btn">Nomor
                Surat</button>
              <button onclick="insertVar('[TANGGAL_TERBIT]')" class="btn btn-outline-primary btn-xs m-1 var-btn">Tanggal
                Terbit</button>
              <button onclick="insertVar('[MASA_BERLAKU]')" class="btn btn-outline-primary btn-xs m-1 var-btn">Masa
                Berlaku</button>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Kop Surat & Dinas</label>
            <div class="d-flex flex-wrap">
              <button onclick="insertVar('[LOGO_DINAS]')" class="btn btn-outline-info btn-xs m-1 var-btn">Logo
                Kop</button>
              <button onclick="insertVar('[KOTA_DINAS]')"
                class="btn btn-outline-info btn-xs m-1 var-btn">Kota/Kabupaten</button>
              <button onclick="insertVar('[ALAMAT_DINAS]')" class="btn btn-outline-info btn-xs m-1 var-btn">Alamat
                Dinas</button>
              <button onclick="insertVar('[PROVINSI_DINAS]')"
                class="btn btn-outline-info btn-xs m-1 var-btn">Provinsi</button>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Pejabat Penandatangan</label>
            <div class="d-flex flex-wrap">
              <button onclick="insertVar('[PIMPINAN_NAMA]')" class="btn btn-outline-warning btn-xs m-1 var-btn">Nama
                Pejabat</button>
              <button onclick="insertVar('[PIMPINAN_JABATAN]')"
                class="btn btn-outline-warning btn-xs m-1 var-btn">Jabatan</button>
              <button onclick="insertVar('[PIMPINAN_NIP]')" class="btn btn-outline-warning btn-xs m-1 var-btn">NIP
                Pejabat</button>
              <button onclick="insertVar('[PIMPINAN_PANGKAT]')"
                class="btn btn-outline-warning btn-xs m-1 var-btn">Pangkat</button>
              <button type="button" onclick="insertSignatureBlock()" class="btn btn-warning btn-xs m-1 font-weight-bold">
                <i class="fas fa-file-signature mr-1"></i> Sisipkan Blok Tanda Tangan
              </button>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Watermark</label>
            <div class="d-flex flex-wrap">
              <button onclick="insertVar('[WATERMARK_LOGO]')"
                class="btn btn-outline-secondary btn-xs m-1 var-btn">Watermark Logo</button>
            </div>
          </div>
        </div>

        <div class="mt-3 p-2 bg-info rounded small opacity-75">
          <i class="fas fa-info-circle mr-1"></i> Klik variabel untuk menyisipkan ke posisi kursor di canvas.
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Preset Modal (Bootstrap 4) -->
  <div class="modal fade shadow-lg" id="presetModal" tabindex="-1" role="dialog" aria-hidden="true border-0">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
        <div class="modal-header bg-light border-0 py-3">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-magic text-warning mr-2"></i> Pilih Preset Template
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4 bg-light">
          <div class="row">
            @foreach($presets as $key => $preset)
              <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all cursor-pointer"
                  onclick="applyPreset('{{ $key }}')">
                  <div class="card-body p-0">
                    <div class="preset-preview bg-white p-3 border-bottom overflow-hidden" style="height: 180px;">
                      <div class="scale-preview" style="transform: scale(0.2); transform-origin: top left; width: 500%;">
                        {!! $preset['html'] !!}
                      </div>
                    </div>
                    <div class="p-3">
                      <h6 class="font-weight-bold text-dark mb-1">{{ $preset['name'] }}</h6>
                      <p class="text-muted small mb-0">{{ $preset['description'] }}</p>
                    </div>
                  </div>
                  <div class="card-footer bg-white border-0 text-center pb-3">
                    <button class="btn btn-primary btn-sm btn-block shadow-sm font-weight-bold">Gunakan Layout</button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .a4-paper {
      background: white;
      color: black !important;
      position: relative;
      z-index: 1;
      margin: 0;
    }

    .canvas-container {
      background-color: white;
    }

    .bg-gray-dark {
      background-color: #343a40 !important;
    }

    .font-serif-doc {
      font-family: 'Times New Roman', serif;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 10px;
    }

    .var-btn:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
    }

    .hover-shadow:hover {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
      transform: translateY(-3px);
    }

    .btn-xs {
      padding: .125rem .25rem;
      font-size: .75rem;
      line-height: 1.5;
      border-radius: .15rem;
    }

    #editor-canvas:focus {
      outline: none;
      border: 1px solid #007bff !important;
    }

    #editor-canvas table {
      border-collapse: collapse;
      margin-top: 1rem;
      width: 100%;
      border: 1px dotted #ccc;
    }

    #editor-canvas td {
      border: 1px dotted #ccc;
      padding: 5px;
    }

    /* Variable badges in editor */
    .var-badge {
      display: inline;
      padding: 1px 6px;
      font-size: 11px;
      font-weight: bold;
      color: #007bff;
      background: #e8f0fe;
      border: 1px solid #b3d4fc;
      border-radius: 3px;
      font-family: monospace;
      white-space: nowrap;
    }
  </style>

  @push('scripts')
    <script>
      function applyColor(val) {
        document.execCommand('foreColor', false, val);
        document.getElementById('color-indicator').style.borderBottomColor = val;
      }

      function applyFontSize(val) {
        // execCommand 'fontSize' only supports 1-7. For precision, we use style injection.
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
          const range = selection.getRangeAt(0);
          const span = document.createElement('span');
          span.style.fontSize = val;
          range.surroundContents(span);
        }
      }

      function applyLineHeight(val) {
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
          let node = selection.anchorNode;
          // Find closest block element
          while (node && node.id !== 'editor-canvas') {
            if (node.nodeType === 1 && (['P', 'DIV', 'H1', 'H2', 'H3', 'TD'].includes(node.nodeName))) {
              node.style.lineHeight = val;
              break;
            }
            node = node.parentNode;
          }
        }
      }

      function execCmd(command) { document.execCommand(command, false, null); }
      function execCommandWithArg(command, arg) { document.execCommand(command, false, arg); }

      function insertVar(val) {
        const span = `<span class="var-badge" contenteditable="false">${val}</span>&nbsp;`;
        document.execCommand('insertHTML', false, span);
      }

      function insertSignatureBlock() {
        const block = `
                                          <div class="signature-block" style="page-break-inside: avoid; margin-top: 5px;">
                                            <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
                                              <tr>
                                                <td width="55%"></td>
                                                <td width="45%" style="text-align:center; font-size: 10pt; line-height: 1.1;">
                                                  <div>[KOTA_DINAS], [TANGGAL_TERBIT]</div>
                                                  <div style="margin-top:2px; font-weight:bold; text-transform:uppercase;">KEPALA</div>
                                                  <div style="margin-top:35px; font-weight:bold;">[PIMPINAN_NAMA]</div>
                                                  <div style="font-weight:bold;">[PIMPINAN_PANGKAT]</div>
                                              <div style="margin-top: 1px;">NIP. [PIMPINAN_NIP]</div>
                                                </td>
                                              </tr>
                                            </table>
                                          </div>
                                        `;
        document.execCommand('insertHTML', false, block);
        // Trigger processing to convert new variables into badges
        const canvas = document.getElementById('editor-canvas');
        canvas.innerHTML = processTemplate(canvas.innerHTML);
      }

      function insertTable() {
        let table = '<table style="width:100%;"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table><p>&nbsp;</p>';
        document.execCommand('insertHTML', false, table);
      }

      function insertImage(input) {
        if (input.files && input.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            const img = `<img src="${e.target.result}" style="max-width: 100%; height: auto; display: block; margin: 10px 0;" alt="Uploaded Image">`;
            document.execCommand('insertHTML', false, img);
          }
          reader.readAsDataURL(input.files[0]);
          input.value = ''; // Reset input
        }
      }

      function toggleWatermark() {
        const frame = document.getElementById('frame-overlay');
        const btn = document.getElementById('btn-toggle-watermark');
        if (frame) {
          if (frame.style.display === 'none') {
            frame.style.display = 'block';
            btn.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Sembunyikan Bingkai';
            btn.className = 'btn btn-outline-secondary btn-sm shadow-sm font-weight-bold';
          } else {
            frame.style.display = 'none';
            btn.innerHTML = '<i class="fas fa-eye mr-1"></i> Tampilkan Bingkai';
            btn.className = 'btn btn-secondary btn-sm shadow-sm font-weight-bold';
          }
        }
      }

      function focusCanvas(e) {
        // If user clicks on the grey area (not the canvas itself), focus the canvas
        if (e.target.classList.contains('bg-gray-dark') || e.target.classList.contains('flex-grow-1')) {
          const canvas = document.getElementById('editor-canvas');
          canvas.focus();
        }
      }

      const presets = @json($presets);
      const logoUrl = @json($logoUrl);

      // Known variable patterns
      const varPatterns = [
        'NOMOR_SURAT', 'TANGGAL_TERBIT', 'MASA_BERLAKU',
        'NAMA_LEMBAGA', 'NPSN', 'ALAMAT_LEMBAGA',
        'LOGO_DINAS', 'KOTA_DINAS', 'ALAMAT_DINAS', 'PROVINSI_DINAS',
        'PIMPINAN_NAMA', 'PIMPINAN_JABATAN', 'PIMPINAN_NIP', 'PIMPINAN_PANGKAT',
        'WATERMARK_LOGO'
      ];

      function openPresetModal() { $('#presetModal').modal('show'); }
      function closePresetModal() { $('#presetModal').modal('hide'); }

      /**
       * processTemplate: Convert raw template HTML into editor-friendly HTML
       * - Replace [LOGO_DINAS] in <img src="[LOGO_DINAS]"> with actual URL
       * - Replace [VAR] text with styled badges for visibility
       */
      function processTemplate(html) {
        // 1. Fix logo: replace <img src="[LOGO_DINAS]"...> with actual logo preview
        if (logoUrl) {
          // Handle <img> tags that have [LOGO_DINAS] as src
          html = html.replace(/<img[^>]*src=["'][^"']*\[LOGO_DINAS\][^"']*["'][^>]*>/gi,
            `<img src="${logoUrl}" style="width:70px; height:70px; display:block;" contenteditable="false">`
          );
          // Handle standalone [LOGO_DINAS] text
          html = html.replace(/\[LOGO_DINAS\]/g,
            `<img src="${logoUrl}" style="width:70px; height:70px; display:block;" contenteditable="false">`
          );
        }

        // 2. Convert all known [VARIABLE] placeholders into styled badges
        varPatterns.forEach(v => {
          if (v === 'LOGO_DINAS') return; // Already handled above
          const regex = new RegExp(`\\[${v}\\]`, 'g');
          html = html.replace(regex, `<span class="var-badge" contenteditable="false">[${v}]</span>`);
        });

        // 3. Handle [DATA:FIELD_NAME] custom fields
        html = html.replace(/\[DATA:([A-Z_]+)\]/g,
          '<span class="var-badge" style="color:#28a745; border-color:#28a745; background:#e6f9ed;" contenteditable="false">[DATA:$1]</span>'
        );

        return html;
      }

      /**
       * revertTemplate: Convert editor HTML back to raw template for storage
       * - Replace logo <img> back to [LOGO_DINAS]
       * - Strip badge wrappers, keep [VAR] text only
       */
      function revertTemplate(html) {
        // 1. Revert logo images back to [LOGO_DINAS]
        if (logoUrl) {
          const escapedUrl = logoUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
          const regex = new RegExp(`<img[^>]*src=["']${escapedUrl}["'][^>]*>`, 'gi');
          html = html.replace(regex, '[LOGO_DINAS]');
        }

        // 2. Strip badge wrappers: <span class="var-badge"...>[VAR]</span> → [VAR]
        html = html.replace(/<span[^>]*class="var-badge"[^>]*>\[([A-Z_:]+)\]<\/span>/g, '[$1]');

        // 3. Strip old badge wrappers (from previous editor version)
        html = html.replace(/<span[^>]*class="badge[^"]*"[^>]*>\[([A-Z_:]+)\]<\/span>/g, '[$1]');

        return html;
      }

      function submitTemplate() {
        const canvas = document.getElementById('editor-canvas');
        const content = revertTemplate(canvas.innerHTML);
        document.getElementById('template-input').value = content;

        // Sync border checkbox to hidden input before submit
        const useBorderCheckbox = document.getElementById('use-border-checkbox');
        document.getElementById('use-border-input').value = useBorderCheckbox.checked ? '1' : '0';

        document.getElementById('template-form').submit();
      }

      function applyPreset(key) {
        if (confirm(`Ganti template ini dengan "${presets[key].name}"?`)) {
          const preset = presets[key];
          const canvas = document.getElementById('editor-canvas');
          const wrapper = document.getElementById('canvas-wrapper');

          // 1. Apply HTML
          canvas.innerHTML = processTemplate(preset.html);

          // 2. Adjust paper size and orientation
          const paper = preset.paper_size || 'A4';
          const orientation = preset.orientation || 'portrait';

          const dims = {
            'A4': { 'portrait': { 'w': '210mm', 'h': '297mm' }, 'landscape': { 'w': '297mm', 'h': '210mm' } },
            'F4': { 'portrait': { 'w': '215mm', 'h': '330mm' }, 'landscape': { 'w': '330mm', 'h': '215mm' } }
          };

          const config = dims[paper][orientation];
          if (config) {
            wrapper.style.width = config.w;
            canvas.style.minHeight = config.h;
            // Update internal padding if needed (resets to 20mm usually for presets)
            canvas.style.padding = "20mm 20mm 20mm 20mm";
          }

          // 3. Auto-enable border for HER-REGISTRASI (if use_border is not already on)
          if (key === 'heregister') {
            const cb = document.getElementById('use-border-checkbox');
            if (cb) {
              cb.checked = true;
              const frame = document.getElementById('frame-overlay');
              if (frame) frame.style.display = 'block';
            }
          }

          closePresetModal();
        }
      }

      window.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('editor-canvas');
        if (canvas) {
          canvas.innerHTML = processTemplate(canvas.innerHTML);
          canvas.focus();
        }

        // Add Listener for Use Border Checkbox
        const useBorderCheckbox = document.getElementById('use-border-checkbox');
        if (useBorderCheckbox) {
          useBorderCheckbox.addEventListener('change', function () {
            const frame = document.getElementById('frame-overlay');
            const btn = document.getElementById('btn-toggle-watermark');
            if (frame) {
              if (this.checked) {
                frame.style.display = 'block';
                if (btn) btn.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Sembunyikan Bingkai';
              } else {
                frame.style.display = 'none';
                if (btn) btn.innerHTML = '<i class="fas fa-eye mr-1"></i> Tampilkan Bingkai';
              }
            }
          });
        }
      });

      document.getElementById('search-var').addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.var-btn').forEach(btn => {
          btn.style.display = btn.innerText.toLowerCase().includes(term) ? 'inline-block' : 'none';
        });
      });
    </script>
  @endpush
@endsection