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
          <!-- Text Blocks -->
          <div class="btn-group mr-3 mb-1">
            <select onchange="execCommandWithArg('formatBlock', this.value)"
              class="form-control form-control-sm border-0 bg-white" style="width: 120px;">
              <option value="p">Normal Text</option>
              <option value="h1">Heading 1</option>
              <option value="h2">Heading 2</option>
            </select>
            <select onchange="execCommandWithArg('fontName', this.value)"
              class="form-control form-control-sm border-0 bg-white" style="width: 130px;">
              <option value="Times New Roman">Times New Roman</option>
              <option value="Arial">Arial</option>
              <option value="Public Sans">Public Sans</option>
            </select>
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

          <!-- Lists & Tables -->
          <div class="btn-group mb-1 shadow-sm px-1 bg-white rounded">
            <button type="button" onclick="execCmd('insertUnorderedList')" class="btn btn-link text-dark p-2"
              title="List"><i class="fas fa-list-ul"></i></button>
            <button type="button" onclick="execCmd('insertOrderedList')" class="btn btn-link text-dark p-2"
              title="Ordered List"><i class="fas fa-list-ol"></i></button>
            <button type="button" onclick="insertTable()" class="btn btn-link text-dark p-2" title="Table"><i
                class="fas fa-table"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Workspace -->
    <div class="row no-gutters bg-light" style="min-height: calc(100vh - 180px);">
      <!-- Canvas Side -->
      <div class="col-md-9 p-5 overflow-auto custom-scrollbar" style="max-height: calc(100vh - 180px);">
        <form id="template-form" action="{{ route('super_admin.jenis_perizinan.template.update', $jenisPerizinan) }}"
          method="POST">
          @csrf
          <input type="hidden" name="template_html" id="template-input">
          <div id="editor-canvas" contenteditable="true"
            class="a4-paper font-serif-doc shadow-lg mx-auto p-5 bg-white border">
            {!! $jenisPerizinan->template_html ?? '
                            <div class="text-center" style="border-bottom: 4px double black; padding-bottom: 15px; margin-bottom: 25px;">
                                <h3 style="font-weight: bold; text-transform: uppercase;">Pemerintah Kabupaten Suka Maju</h3>
                                <h2 style="font-weight: bold; text-transform: uppercase;">Dinas Pendidikan dan Kebudayaan</h2>
                                <p style="font-style: italic; font-size: 14px;">Jl. Jendral Sudirman No. 123, Kota Suka Maju, Telp. (021) 12345678</p>
                            </div>
                            <div class="text-center" style="margin-bottom: 30px;">
                                <h1 style="font-weight: bold; text-decoration: underline; text-transform: uppercase;">Surat Izin Operasional</h1>
                                <p>Nomor: [NOMOR_SURAT]</p>
                            </div>
                            <div style="text-align: justify;">
                                <p>Berdasarkan peraturan yang berlaku, memberikan Izin Operasional kepada:</p>
                                <table style="width:100%; margin-top: 20px;">
                                    <tr><td style="width: 150px;">Nama Lembaga</td><td>:</td><td style="font-weight: bold;">[NAMA_LEMBAGA]</td></tr>
                                    <tr><td>NPSN</td><td>:</td><td>[NPSN]</td></tr>
                                    <tr><td>Alamat</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
                                </table>
                            </div>
                        ' !!}
          </div>
        </form>
      </div>

      <!-- Sidebar Variables -->
      <div class="col-md-3 bg-white border-left shadow-sm">
        <div class="p-3 border-bottom bg-light">
          <h5 class="font-weight-bold mb-0 small text-uppercase tracking-wider"><i
              class="fas fa-database mr-2 text-primary"></i> Variabel Dinamis</h5>
        </div>
        <div class="p-3">
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
            </div>
            <input type="text" id="search-var" class="form-control border-left-0" placeholder="Cari variabel...">
          </div>

          <div class="overflow-auto custom-scrollbar" style="max-height: calc(100vh - 300px);">
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
      width: 210mm;
      min-height: 297mm;
      background: white;
      padding: 20mm;
      color: black !important;
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
      function execCmd(command) { document.execCommand(command, false, null); }
      function execCommandWithArg(command, arg) { document.execCommand(command, false, arg); }

      function insertVar(val) {
        const span = `<span class="var-badge" contenteditable="false">${val}</span>&nbsp;`;
        document.execCommand('insertHTML', false, span);
      }

      function insertTable() {
        let table = '<table style="width:100%;"><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table><p>&nbsp;</p>';
        document.execCommand('insertHTML', false, table);
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
        document.getElementById('template-form').submit();
      }

      function applyPreset(key) {
        if (confirm(`Ganti template ini dengan "${presets[key].name}"?`)) {
          document.getElementById('editor-canvas').innerHTML = processTemplate(presets[key].html);
          closePresetModal();
        }
      }

      window.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('editor-canvas');
        if (canvas) {
          canvas.innerHTML = processTemplate(canvas.innerHTML);
          canvas.focus();
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