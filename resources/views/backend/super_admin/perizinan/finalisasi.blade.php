@extends('layouts.backend')

@section('title', 'Review & Finalisasi Sertifikat')

@section('content')
  <div
    class="fixed inset-0 top-0 md:left-64 bg-background-light dark:bg-background-dark z-40 flex flex-col overflow-hidden">
    <!-- Header -->
    <header
      class="flex items-center justify-between border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 py-3 z-30 shrink-0 shadow-sm">
      <div class="flex items-center gap-4">
        <a href="{{ route('super_admin.perizinan.index') }}"
          class="size-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-primary hover:text-white transition-all">
          <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div class="flex items-center gap-3 text-primary">
          <span class="material-symbols-outlined text-2xl">verified_user</span>
          <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">Finalisasi Sertifikat
            Digital</h2>
        </div>
      </div>
      <div class="flex flex-1 justify-end gap-6 items-center">
        <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
          <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
            <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
              {{ $perizinan->lembaga->nama_lembaga }}
            </p>
          </div>
          <div class="size-10 rounded-full border-2 border-primary/20 p-0.5">
            <img
              src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
              class="w-full h-full rounded-full object-cover">
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-1 overflow-hidden relative">
      <!-- Left Panel: Form & Actions -->
      <aside
        class="w-full lg:w-[420px] xl:w-[480px] flex flex-col border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden z-20 shadow-xl shrink-0">
        <!-- Breadcrumbs -->
        <div class="px-6 pt-6 pb-2">
          <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">
            <a class="hover:text-primary transition-colors" href="#">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-slate-900 dark:text-white">Finalisasi</span>
          </div>
          <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic">Review & Finalisasi</h1>
          <p class="text-slate-500 mt-1 text-sm font-medium">Verifikasi data dan terbitkan sertifikat resmi.</p>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
          <!-- Applicant Info Card -->
          <div
            class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-inner">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Informasi Pengajuan</h3>
              <span
                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                Verified Data
              </span>
            </div>
            <div class="space-y-4">
              <div class="flex flex-col gap-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lembaga</span>
                <span
                  class="text-sm font-bold text-slate-900 dark:text-white">{{ $perizinan->lembaga->nama_lembaga }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Izin</span>
                <span class="text-sm font-bold text-primary italic">{{ $perizinan->jenisPerizinan->nama }}</span>
              </div>
              <div class="flex flex-col gap-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Request ID</span>
                <span
                  class="font-mono text-[11px] bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded-md w-fit text-slate-700 dark:text-slate-300">REQ-{{ $perizinan->id }}-{{ date('Ymd') }}</span>
              </div>
            </div>
          </div>

          <!-- Input Form -->
          <form id="finalisasi-form" action="{{ route('super_admin.perizinan.release', $perizinan) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2" for="nomor_surat">
                Nomor Registrasi Surat <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <input
                  class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-mono py-3.5 pl-11"
                  id="input-nomor" name="nomor_surat" placeholder="Ex: 503/001/IUMK/2023" type="text"
                  value="{{ old('nomor_surat', $perizinan->nomor_surat) }}" required />
                <div
                  class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                  <span class="material-symbols-outlined text-[20px]">tag</span>
                </div>
              </div>
              <p class="mt-2 text-[11px] text-slate-500 font-medium">Nomor ini akan dicetak pada header sertifikat.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2"
                  for="tanggal_terbit">
                  Tanggal Terbit
                </label>
                <input
                  class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all py-3 px-4 font-bold"
                  id="input-tanggal" name="tanggal_terbit" type="date"
                  value="{{ old('tanggal_terbit', $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('Y-m-d') : now()->format('Y-m-d')) }}"
                  required />
              </div>
              <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2"
                  for="pimpinan_pangkat">
                  Pangkat/Golongan
                </label>
                <input
                  class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all py-3 px-4 font-bold"
                  id="input-pangkat" name="pimpinan_pangkat" type="text" placeholder="Ex: Pembina Tk.1, IV/b"
                  value="{{ old('pimpinan_pangkat', $perizinan->pimpinan_pangkat ?? Auth::user()->dinas->pimpinan_pangkat) }}" />
              </div>
            </div>

            <div class="space-y-4">
              <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pejabat
                  Penandatangan</label>
                <div class="relative group mb-3">
                  <input
                    class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all py-3 pl-11 font-bold"
                    id="input-nama" name="pimpinan_nama" placeholder="Nama Lengkap Pejabat" type="text"
                    value="{{ old('pimpinan_nama', $perizinan->pimpinan_nama ?? Auth::user()->dinas->pimpinan_nama) }}"
                    required />
                  <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">person</span>
                  </div>
                </div>
                <div class="relative group mb-3">
                  <input
                    class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all py-3 pl-11 font-bold"
                    id="input-jabatan" name="pimpinan_jabatan" placeholder="Jabatan Pejabat" type="text"
                    value="{{ old('pimpinan_jabatan', $perizinan->pimpinan_jabatan ?? Auth::user()->dinas->pimpinan_jabatan) }}"
                    required />
                  <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">work</span>
                  </div>
                </div>
                <div class="relative group">
                  <input
                    class="block w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white shadow-inner focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all py-3 pl-11 font-mono"
                    id="input-nip" name="pimpinan_nip" placeholder="NIP Pejabat" type="text"
                    value="{{ old('pimpinan_nip', $perizinan->pimpinan_nip ?? Auth::user()->dinas->pimpinan_nip) }}"
                    required />
                  <div
                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">fingerprint</span>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Sertifikat
                Validasi</label>
              <div class="relative group">
                <input class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="input-stempel"
                  name="stempel_img" type="file" accept="image/png" />
                <div
                  class="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50 dark:bg-slate-800/30 p-6 flex flex-col items-center justify-center text-center transition-all group-hover:bg-slate-100 dark:group-hover:bg-slate-800 group-hover:border-primary shadow-inner">
                  <div
                    class="size-12 rounded-xl bg-white dark:bg-slate-900 shadow-sm text-primary flex items-center justify-center mb-3 transition-transform group-hover:scale-110">
                    <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                  </div>
                  <p class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider mb-1">Unggah
                    Stempel Basah</p>
                  <p class="text-[10px] text-slate-500 font-bold">Png Transparan (Max 2MB)</p>
                </div>
              </div>
              <div id="stempel-preview-container"
                class="mt-4 p-3 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-100 dark:border-slate-800 animate-in zoom-in duration-300 {{ $perizinan->stempel_img ? '' : 'hidden' }}">
                <div class="flex items-center gap-3">
                  <img src="{{ $perizinan->stempel_img ? Storage::url($perizinan->stempel_img) : '' }}"
                    id="stempel-preview" class="h-14 w-14 object-contain opacity-90" alt="Preview Stempel">
                  <div class="flex-1">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Stempel Aktif</p>
                    <p class="text-xs font-bold text-slate-400">File siap digabungkan.</p>
                  </div>
                  <button type="button" class="text-red-500 hover:text-red-700 transition-colors" title="Remove">
                    <span class="material-symbols-outlined text-lg">delete</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 text-center">Aksi Finalisasi</p>
          <button type="submit" form="finalisasi-form"
            class="flex w-full items-center justify-center rounded-2xl bg-primary px-4 py-4 text-sm font-black text-white shadow-xl shadow-primary/30 hover:bg-primary/90 transition-all active:scale-[0.98] group">
            <span
              class="material-symbols-outlined mr-2 text-[22px] group-hover:rotate-12 transition-transform">verified</span>
            Terbitkan & Set Siap Diambil
          </button>
          <p class="mt-3 text-[10px] text-slate-500 font-bold text-center italic">Lembaga akan menerima notifikasi bahwa
            dokumen sudah siap diambil.</p>
          <div class="mt-4 flex gap-3">
            <button
              class="flex-1 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm"
              type="button" onclick="window.print()">
              <span class="material-symbols-outlined text-[18px] align-middle mr-1">print</span>
              Cetak
            </button>
            <button
              class="flex-1 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-black text-red-700 uppercase tracking-wider hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 transition-colors shadow-sm"
              type="button">
              Tolak
            </button>
          </div>
        </div>
      </aside>

      <!-- Right Panel: Live Preview -->
      <section class="flex-1 bg-slate-100 dark:bg-slate-950/50 relative overflow-hidden flex flex-col h-full">
        <!-- Toolbar -->
        <div
          class="h-14 flex items-center justify-between px-6 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shrink-0 z-10 shadow-sm">
          <div class="flex items-center gap-3 text-xs font-black text-slate-400 uppercase tracking-[0.15em]">
            <span class="material-symbols-outlined text-primary text-[20px]">visibility</span>
            Real-time Live Preview
          </div>
          <div class="flex items-center gap-2">
            <div
              class="flex bg-slate-100 dark:bg-slate-800 rounded-xl p-1 shadow-inner border border-slate-200/50 dark:border-slate-700/50">
              <button onclick="zoomPreview(-0.1)"
                class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm text-slate-500 transition-all">
                <span class="material-symbols-outlined text-[18px]">remove</span>
              </button>
              <span id="zoom-level"
                class="px-2 py-1 text-xs font-black text-slate-800 dark:text-white flex items-center w-12 justify-center">100%</span>
              <button onclick="zoomPreview(0.1)"
                class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm text-slate-500 transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
              </button>
            </div>
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-2"></div>
            <button
              class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:scale-110"
              title="Full Screen">
              <span class="material-symbols-outlined text-[20px]">fullscreen</span>
            </button>
            <button
              class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all hover:scale-110"
              title="Download PDF">
              <span class="material-symbols-outlined text-[20px]">download</span>
            </button>
          </div>
        </div>

        <!-- Preview Canvas -->
        <div
          class="flex-1 overflow-auto p-12 custom-scrollbar bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] flex justify-center items-start">
          <!-- A4 Paper Simulation -->
          <div id="preview-paper"
            class="relative bg-white text-black w-[800px] min-h-[1131px] shadow-[0_25px_60px_rgba(0,0,0,0.18)] flex flex-col p-16 mx-auto origin-top transform transition-all duration-300">

            <!-- Premium Decorative Layout -->
            <div class="absolute inset-0 border-[10px] border-double border-slate-200 pointer-events-none m-5"></div>
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-center bg-no-repeat bg-contain"
              style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB1FEFwlMLvzCuTBTAHez65BrrmNc9-QiX1v-b3GNAbsNlNu9fQuAdYKuIBYaqHumMiYIASllTGcQ96zbK2oHMwd8m9jUs28bar1i-OmP7AFZuLk-aVj5G-GvU3G5dcOD3PX2GGfdHzpxGfw2hUeNafnXF_zhxBQMDI2gGoe6zuyHhriryvhhAl5oNEFg7Hm2HhLzBla20eKH3mJ5wVJRqCgPJErKuEvUIxx_Yq5t-PuYkx9garov0zHIwEdwU_MBCYPqpajLHVbCf2');">
            </div>

            <!-- Certificate Header Logic -->
            <div id="preview-content-area" class="w-full h-full relative z-10">
              @if($perizinan->jenisPerizinan->template_html)
                @php
                  $template = $perizinan->jenisPerizinan->template_html;
                  $replacements = [
                    '[NOMOR_SURAT]' => '<span id="preview-nomor" class="font-bold underline px-1 rounded">' . ($perizinan->nomor_surat ?? '............................') . '</span>',
                    '[TANGGAL_TERBIT]' => '<span id="preview-tanggal" class="font-bold">' . ($perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('d F Y') : date('d F Y')) . '</span>',
                    '[NAMA_LEMBAGA]' => '<span id="preview-nama_lembaga" class="uppercase font-black text-slate-900">' . $perizinan->lembaga->nama_lembaga . '</span>',
                    '[NPSN]' => '<span id="preview-npsn" class="font-mono font-bold">' . $perizinan->lembaga->npsn . '</span>',
                    '[ALAMAT_LEMBAGA]' => '<span id="preview-alamat" class="font-medium">' . $perizinan->lembaga->alamat . '</span>',
                    '[JENIS_IZIN]' => '<span id="preview-jenis" class="font-black text-primary uppercase tracking-widest">' . $perizinan->jenisPerizinan->nama . '</span>',
                    '[PIMPINAN_NAMA]' => '<span id="preview-nama" class="font-bold underline text-xl">' . ($perizinan->pimpinan_nama ?? Auth::user()->dinas->pimpinan_nama) . '</span>',
                    '[PIMPINAN_JABATAN]' => '<span id="preview-jabatan" class="font-bold uppercase tracking-wider">' . ($perizinan->pimpinan_jabatan ?? Auth::user()->dinas->pimpinan_jabatan) . '</span>',
                    '[PIMPINAN_PANGKAT]' => '<span id="preview-pangkat" class="text-sm font-medium">' . ($perizinan->pimpinan_pangkat ?? Auth::user()->dinas->pimpinan_pangkat) . '</span>',
                    '[PIMPINAN_NIP]' => '<span id="preview-nip" class="font-bold">' . ($perizinan->pimpinan_nip ?? Auth::user()->dinas->pimpinan_nip) . '</span>',
                    '[KOTA_DINAS]' => Auth::user()->dinas->kota ?? 'Garut',
                    '[ALAMAT_DINAS]' => Auth::user()->dinas->alamat ?? 'Jl. Pahlawan No. 20, Tarogong Kidul',
                    '[LOGO_DINAS]' => Auth::user()->dinas->logo ? Storage::url(Auth::user()->dinas->logo) : '',
                  ];

                  if ($perizinan->perizinan_data) {
                    foreach ($perizinan->perizinan_data as $key => $value) {
                      $replacements['[DATA:' . strtoupper($key) . ']'] = nl2br(e($value));
                    }
                  }

                  echo str_replace(array_keys($replacements), array_values($replacements), $template);
                @endphp
              @else
                <div class="text-center italic text-slate-400 mt-20">No template defined for this permit type.</div>
              @endif
            </div>

            <!-- Footer / QR -->
            <div class="mt-auto pt-8 border-t border-slate-100 flex justify-between items-end relative z-10">
              <div class="text-[9px] text-slate-400 max-w-[70%] font-medium leading-tight">
                Dokumen ini telah ditandatangani secara elektronik melalui Sistem Informasi Perizinan (SIM-IZIN)
                menggunakan sertifikat elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), BSSN.
                Keaslian dokumen dapat dicek melalui scan QR Code.
              </div>
              <div class="bg-white p-1.5 border border-slate-200 shadow-sm rounded-lg flex items-center justify-center">
                <img class="w-14 h-14 opacity-80" alt="QR Code"
                  src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('perizinan.verification', $perizinan->id)) }}">
              </div>
            </div>

            <!-- Stempel Animation Layer (Absolute) -->
            <div id="preview-stempel-wrap"
              class="absolute bottom-[160px] right-[160px] transform -rotate-12 pointer-events-none z-20 transition-all duration-500 {{ $perizinan->stempel_img ? '' : 'hidden opacity-0 scale-150' }}">
              <img src="{{ $perizinan->stempel_img ? Storage::url($perizinan->stempel_img) : '' }}"
                id="preview-stempel-img" class="w-40 h-40 object-contain mix-blend-multiply opacity-80">
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <style>
    /* Hide specific layout parts */
    header.bg-white.shadow-header,
    footer.bg-white {
      display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.2);
      border-radius: 20px;
    }

    @media print {

      header,
      aside,
      .h-14.bg-white,
      .absolute.bottom-8 {
        display: none !important;
      }

      .fixed.inset-0 {
        position: relative !important;
        inset: auto !important;
        overflow: visible !important;
      }

      main {
        display: block !important;
        overflow: visible !important;
      }

      #preview-paper {
        transform: none !important;
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }
    }
  </style>
@endsection

@push('scripts')
  <script>
    let currentZoom = 1;
    const paper = document.getElementById('preview-paper');
    const zoomText = document.getElementById('zoom-level');

    function zoomPreview(delta) {
      currentZoom = Math.max(0.6, Math.min(1.4, currentZoom + delta));
      paper.style.transform = `scale(${currentZoom})`;
      zoomText.innerText = `${Math.round(currentZoom * 100)}%`;
    }

    // Live Sync Editor to Preview
    function updateText(inputId, previewId, defaultValue = '............................') {
      const input = document.getElementById(inputId);
      const preview = document.getElementById(previewId);
      if (input && preview) {
        input.addEventListener('input', e => {
          let val = e.target.value;
          if (inputId === 'input-tanggal') {
            const date = new Date(val);
            val = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
          }
          preview.innerHTML = val || defaultValue;
        });
      }
    }

    updateText('input-nomor', 'preview-nomor');
    updateText('input-nama', 'preview-nama');
    updateText('input-jabatan', 'preview-jabatan');
    updateText('input-pangkat', 'preview-pangkat', '');
    updateText('input-nip', 'preview-nip');
    updateText('input-tanggal', 'preview-tanggal');

    // Stempel Preview
    document.getElementById('input-stempel').addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          document.getElementById('stempel-preview').src = event.target.result;
          document.getElementById('stempel-preview-container').classList.remove('hidden');

          document.getElementById('preview-stempel-img').src = event.target.result;
          const wrap = document.getElementById('preview-stempel-wrap');
          wrap.classList.remove('hidden');
          setTimeout(() => {
            wrap.style.opacity = '0.8';
            wrap.style.transform = 'rotate(-12deg) scale(1)';
          }, 10);
        };
        reader.readAsDataURL(file);
      }
    });

    // Dark Mode Toggle Support if needed
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
@endpush