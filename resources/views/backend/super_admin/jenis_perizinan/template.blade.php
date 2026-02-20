@extends('layouts.backend')

@section('title', 'Editor Template Sertifikat')

@section('content')
  <div
    class="fixed inset-0 top-0 md:left-64 bg-background-light dark:bg-background-dark z-40 flex flex-col overflow-hidden">
    <!-- Top Navigation (Internal) -->
    <header
      class="flex items-center justify-between border-b border-solid border-border-light bg-white px-6 py-3 shrink-0 z-20 shadow-sm">
      <div class="flex items-center gap-4 text-slate-800">
        <div class="size-8 flex items-center justify-center bg-primary/10 rounded-lg text-primary">
          <span class="material-symbols-outlined">description</span>
        </div>
        <div>
          <h2 class="text-lg font-bold leading-tight tracking-tight">Editor Template: {{ $jenisPerizinan->nama }}</h2>
          <div class="text-xs text-slate-500 flex items-center gap-1">
            <a href="{{ route('super_admin.jenis_perizinan.index') }}" class="hover:text-primary transition-colors">Jenis
              Perizinan</a>
            <span class="material-symbols-outlined text-[10px]">chevron_right</span>
            <span>Professional Editor V2</span>
          </div>
        </div>
      </div>
      <div class="flex flex-1 justify-end items-center gap-6">
        <div
          class="hidden md:flex items-center gap-1 text-sm text-slate-500 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
          <span class="material-symbols-outlined text-base text-primary">verified</span>
          <span class="font-medium">CKEditor 5 Decoupled Engine Active</span>
        </div>
        <div class="flex gap-3">
          <button type="button" onclick="submitTemplate()"
            class="flex items-center justify-center rounded-xl h-10 px-6 bg-primary hover:bg-primary-hover text-white text-sm font-black shadow-lg shadow-primary/20 transition-all active:scale-95">
            <span class="material-symbols-outlined text-lg mr-2">save</span>
            <span>Simpan Template</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Professional Toolbar Container -->
    <div id="editor-toolbar-container"
      class="bg-white border-b border-slate-200 shrink-0 z-20 min-h-[40px] shadow-sm flex items-center px-4"></div>

    <!-- Main Content Area -->
    <main class="flex flex-1 overflow-hidden relative">
      <!-- Left Side: Editor Area -->
      <div class="flex-1 flex flex-col bg-[#eaecf0] relative overflow-hidden">
        <!-- Canvas Area -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-[#eaecf0]">
          <form id="template-form" action="{{ route('super_admin.jenis_perizinan.template.update', $jenisPerizinan) }}"
            method="POST">
            @csrf
            <input type="hidden" name="template_html" id="template-input">

            <!-- Editor will target this DIV -->
            <div id="editor-canvas-wrapper" class="a4-paper-wrapper">
                <div id="editor-canvas" class="a4-paper font-serif leading-relaxed focus:outline-none">
                  {!! $jenisPerizinan->template_html ?? '
        <div class="text-center border-b-4 border-double border-black pb-4 mb-6">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="[LOGO_DINAS]" class="h-16 w-auto object-contain">
                <div class="text-center">
                    <h3 class="font-bold text-lg uppercase tracking-wide italic">Pemerintah Kabupaten [KOTA_DINAS]</h3>
                    <h2 class="font-bold text-xl uppercase tracking-wider">Dinas Pendidikan</h2>
                    <p class="text-xs italic">[ALAMAT_DINAS]</p>
                </div>
            </div>
        </div>
    
        <div class="text-center mb-6">
            <h1 class="font-bold text-xl underline uppercase tracking-widest">SURAT KETERANGAN</h1>
            <p class="mt-1 font-bold">Nomor : 800.1.11.1 / <span class="bg-blue-100 text-primary font-bold px-1 rounded">[NOMOR_SURAT]</span> -Disdik</p>
        </div>
    
        <div class="text-justify mb-6 space-y-2">
            <p class="font-bold">Dasar :</p>
            <ol class="list-decimal ml-6">
                <li>Berdasarkan Permohonan dari Yayasan Nomor : 034/SPPA/YHHN/II/2026, Tanggal: 02 Februari 2026.</li>
                <li>Surat Keterangan Domisili dari Desa : 474.5/048/SKD/I/2026, Tanggal : 25 Januari 2026.</li>
                <li>Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten Garut Nomor : 800/838-Disdik, Tanggal: 25 Mei 2021.</li>
            </ol>
        </div>
    
        <div class="text-justify mb-6">
            <p class="mb-4 font-bold">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
            <table class="w-full mb-6 ml-4 border-collapse">
                <tbody>
                    <tr>
                        <td class="py-1 w-48 align-top">Nama Lembaga</td>
                        <td class="py-1 w-4 align-top">:</td>
                        <td class="py-1 font-bold"><span class="bg-blue-100 text-primary px-1 rounded">[NAMA_LEMBAGA]</span></td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Nama Ketua</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">Desi Yuliani, S.Pd</td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">NPSN</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1"><span class="bg-blue-100 text-primary px-1 rounded">[NPSN]</span></td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Nama Penyelenggara</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">Yayasan Al-Hisam Hasanudin Garut</td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Alamat Lembaga</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1"><span class="bg-blue-100 text-primary px-1 rounded">[ALAMAT_LEMBAGA]</span></td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Desa/Kelurahan</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">Sukatani</td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Kecamatan</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">Cilawu</td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Kabupaten</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">Garut</td>
                    </tr>
                </tbody>
            </table>
    
            <p class="mb-4 font-bold">Bahwa Lembaga tersebut diatas mengalami Perpindahan Alamat :</p>
    
            <div class="ml-6 space-y-4">
                <div>
                    <p class="font-bold">1. Alamat Lama</p>
                    <p class="ml-4">a. Alamat : <span class="bg-blue-100 text-primary px-1 rounded">[DATA:ALAMAT_LAMA]</span></p>
                </div>
                <div>
                    <p class="font-bold">2. Alamat Baru</p>
                    <p class="ml-4">a. Alamat : <span class="bg-blue-100 text-primary px-1 rounded">[DATA:ALAMAT_BARU]</span></p>
                </div>
            </div>
    
            <p class="mt-6">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</p>
        </div>
    
        <div class="flex justify-end mt-12">
            <div class="text-center w-80">
                <p class="mb-1">Dikeluarkan : [KOTA_DINAS]</p>
                <p class="mb-4">Pada Tanggal : <span class="bg-blue-100 text-primary font-bold px-1 rounded">[TANGGAL_TERBIT]</span></p>
                <p class="font-bold mt-8 mb-20 uppercase tracking-widest">KEPALA</p>
                <p class="font-bold underline text-lg"><span class="bg-blue-100 text-primary font-bold px-1 rounded">[PIMPINAN_NAMA]</span></p>
                <p class="text-sm font-bold"><span class="bg-blue-100 text-primary px-1 rounded">[PIMPINAN_PANGKAT]</span></p>
                <p class="mt-1">NIP. <span class="bg-blue-100 text-primary font-bold px-1 rounded">[PIMPINAN_NIP]</span></p>
            </div>
        </div>
        ' !!}
                </div>
            </div>
          </form>
          <div class="h-20"></div>
        </div>
      </div>

      <!-- Right Side: Variables Sidebar -->
      <aside
        class="w-80 bg-white border-l border-border-light flex flex-col shrink-0 z-20 shadow-[-4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="p-4 border-b border-border-light bg-white">
          <h3 class="text-slate-800 font-bold text-base mb-3">Variabel Dinamis</h3>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
              <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
            <input type="text" id="search-var"
              class="block w-full p-2.5 pl-10 text-sm text-slate-900 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-primary transition-colors"
              placeholder="Cari variabel...">
          </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-6" id="var-container">
          <!-- Mapping Data dari Form Pengajuan (NEW) -->
          @if($jenisPerizinan->form_config)
            <div>
              <h4
                class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-3 px-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">dynamic_form</span>
                Mapping Data Ajuan
              </h4>
              <div class="flex flex-wrap gap-2">
                @foreach($jenisPerizinan->form_config as $field)
                  <button type="button" onclick="insertVar('[DATA:{{ strtoupper($field['name']) }}]')"
                    class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-800/40 border border-emerald-100 dark:border-emerald-800/30 rounded-full transition-all cursor-pointer">
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">{{ $field['label'] }}</span>
                    <span class="material-symbols-outlined text-[16px] text-emerald-400">add</span>
                  </button>
                @endforeach
              </div>
            </div>
            <hr class="border-slate-100 dark:border-slate-800" />
          @endif

          <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Identitas Izin</h4>
            <div class="flex flex-wrap gap-2">
              <button onclick="insertVar('[NOMOR_SURAT]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Nomor Surat</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[TANGGAL_TERBIT]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Tanggal Terbit</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[JENIS_IZIN]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Jenis Izin</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
            </div>
          </div>

          <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Data Lembaga</h4>
            <div class="flex flex-wrap gap-2">
              <button onclick="insertVar('[NAMA_LEMBAGA]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Nama Lembaga</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[NPSN]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">NPSN</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[ALAMAT_LEMBAGA]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Alamat Lengkap</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
            </div>
          </div>

          <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Pejabat</h4>
            <div class="flex flex-wrap gap-2">
              <button onclick="insertVar('[PIMPINAN_NAMA]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Nama Pejabat</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[PIMPINAN_JABATAN]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Jabatan</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[PIMPINAN_PANGKAT]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Pangkat/Golongan</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[PIMPINAN_NIP]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">NIP</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
            </div>
          </div>

          <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Atribut Dinas</h4>
            <div class="flex flex-wrap gap-2">
              <button onclick="insertVar('[KOTA_DINAS]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Kota Dinas</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[ALAMAT_DINAS]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Alamat Dinas</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
              <button onclick="insertVar('[LOGO_DINAS]')"
                class="var-btn group flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-primary/10 border border-transparent hover:border-primary/20 rounded-full transition-all cursor-pointer">
                <span class="text-xs font-medium text-slate-700 group-hover:text-primary">Logo Dinas (Img)</span>
                <span class="material-symbols-outlined text-[14px] text-slate-400 group-hover:text-primary">add</span>
              </button>
            </div>
          </div>
        </div>

        <div class="p-4 border-t border-border-light bg-slate-50">
          <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
            <div class="flex gap-2">
              <span class="material-symbols-outlined text-primary text-sm mt-0.5">info</span>
              <div>
                <p class="text-xs text-primary font-bold mb-1">Tips Editor</p>
                <p class="text-[11px] text-slate-600 leading-snug">Klik variabel untuk menyisipkan ke posisi kursor.
                  Editor akan menyimpan struktur HTML lengkap.</p>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </main>
  </div>

  <style>
    /* Hide layout parts for this view */
    header.bg-white.shadow-header,
    footer.bg-white {
      display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 20px;
    }

    .a4-paper {
      width: 210mm;
      min-height: 297mm;
      margin: 0 auto;
      background: white;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      padding: 20mm;
      color: black !important;
    }

    [contenteditable]:focus {
      outline: none;
    }

    .bg-blue-100 {
      background-color: #dbeafe !important;
    }

    @media print {

      header,
      aside,
      .toolbar,
      .bg-white.border-b.border-border-light.px-4.py-2 {
        display: none !important;
      }

      .fixed.inset-0 {
        position: relative !important;
        inset: auto !important;
        margin: 0 !important;
        width: auto !important;
        height: auto !important;
        overflow: visible !important;
      }

      .flex-1.overflow-y-auto.p-8 {
        padding: 0 !important;
        background: white !important;
        overflow: visible !important;
      }

      .a4-paper {
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }

      body {
        background: white !important;
      }
    }
  </style>

  <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/decoupled-document/ckeditor.js"></script>
  <script>
    let editor;

    DecoupledEditor
        .create(document.querySelector('#editor-canvas'), {
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'alignment', '|',
                    'numberedList', 'bulletedList', '|',
                    'outdent', 'indent', '|',
                    'insertTable', '|',
                    'removeFormat'
                ]
            },
            language: 'id',
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            }
        })
        .then(newEditor => {
            editor = newEditor;

            // Set the toolbar to the custom container
            const toolbarContainer = document.querySelector('#editor-toolbar-container');
            toolbarContainer.appendChild(editor.ui.view.toolbar.element);

            window.editor = editor;
            console.log('Editor was initialized');
        })
        .catch(error => {
            console.error(error);
        });

    function insertVar(variable) {
        if (!editor) return;

        const viewFragment = editor.data.processor.toView(`<span class="bg-blue-100 text-primary font-bold px-1 rounded" contenteditable="false">${variable}</span>`);
        const modelFragment = editor.data.toModel(viewFragment);

        editor.model.insertContent(modelFragment);
    }

    function submitTemplate() {
      if (!editor) return;
      const content = editor.getData();
      document.getElementById('template-input').value = content;
      document.getElementById('template-form').submit();
    }

    // Search functionality
    document.getElementById('search-var').addEventListener('input', function (e) {
      const term = e.target.value.toLowerCase();
      document.querySelectorAll('.var-btn').forEach(btn => {
        const text = btn.innerText.toLowerCase();
        btn.style.display = text.includes(term) ? 'flex' : 'none';
      });
    });
  </script>
@endsection