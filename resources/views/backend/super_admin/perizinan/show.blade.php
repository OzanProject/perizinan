@extends('layouts.backend')

@section('title', 'Verifikasi Perizinan')
@section('breadcrumb', 'Verifikasi Administratif')

@section('content')
  <div class="px-6 py-4 flex flex-col gap-1 shrink-0 bg-background-light dark:bg-background-dark -mt-4 mb-6">
    <div class="flex justify-between items-start">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Verifikasi Administratif</h1>
        <p class="text-sm text-slate-500">Tinjau kelengkapan berkas untuk melanjutkan ke tahap verifikasi lapangan.</p>
      </div>
      @php
        $statusEnum = \App\Enums\PerizinanStatus::from($perizinan->status);
        $statusColor = $statusEnum->color();
        $bgClasses = [
          'warning' => 'bg-amber-100 text-amber-800 border-amber-200',
          'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
          'info' => 'bg-sky-100 text-sky-800 border-sky-200',
          'primary' => 'bg-blue-100 text-blue-800 border-blue-200',
          'danger' => 'bg-red-100 text-red-800 border-red-200',
          'secondary' => 'bg-slate-100 text-slate-800 border-slate-200',
          'dark' => 'bg-slate-800 text-white border-slate-900',
        ];
        $badgeClass = $bgClasses[$statusColor] ?? 'bg-slate-100 text-slate-800 border-slate-200';
      @endphp
      <div class="flex items-center gap-2 {{ $badgeClass }} px-3 py-1.5 rounded-full border">
        <span class="material-symbols-outlined text-sm">
          @if($statusColor == 'success' || $statusColor == 'dark') check_circle @elseif($statusColor == 'warning') history
          @else schedule @endif
        </span>
        <span class="text-xs font-bold uppercase tracking-wide">{{ $statusEnum->label() }}</span>
      </div>
    </div>
  </div>

  <div class="flex flex-col xl:flex-row gap-6 pb-24">
    <!-- Left Column: Applicant Data -->
    <div class="flex-1 flex flex-col gap-6 min-w-0">
      <!-- Tabs -->
      <div class="flex border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
        <button class="px-4 py-2 text-sm font-medium text-primary border-b-2 border-primary whitespace-nowrap active-tab"
          onclick="switchTab('data-lembaga')">
          Data Lembaga
        </button>
        <button
          class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent hover:border-slate-300 transition-colors whitespace-nowrap"
          onclick="switchTab('diskusi')">
          Diskusi & Catatan
        </button>
      </div>

      <!-- Tab Content: Data Lembaga -->
      <div id="tab-data-lembaga" class="tab-content">
        <div class="space-y-6">
          <!-- Card: Identity -->
          <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-t-4 border-t-primary overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
              <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">domain</span>
                Identitas Lembaga
              </h3>
              <a href="{{ route('super_admin.lembaga.show', $perizinan->lembaga) }}"
                class="text-xs text-primary hover:underline">Lihat Profil Lengkap</a>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Nama
                  Lembaga</label>
                <p class="font-medium text-slate-800">{{ $perizinan->lembaga->nama_lembaga }}</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">NPSN</label>
                <p class="font-medium text-slate-800">{{ $perizinan->lembaga->npsn }}</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Jenis Izin</label>
                <p class="font-medium text-slate-800">{{ $perizinan->jenisPerizinan->nama }}</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Nomor
                  Surat</label>
                <p class="font-medium text-slate-800 font-mono">{{ $perizinan->nomor_surat ?? '-' }}</p>
              </div>
              <div class="md:col-span-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Alamat
                  Lengkap</label>
                <p class="font-medium text-slate-800">{{ $perizinan->lembaga->alamat }}</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Jenjang
                  Pendidikan</label>
                <p class="font-medium text-slate-800">{{ $perizinan->lembaga->jenjang }}</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Tanggal
                  Pengajuan</label>
                <p class="font-medium text-slate-800">{{ $perizinan->created_at->format('d F Y, H:i') }}</p>
              </div>
            </div>
          </div>

          <!-- Card: Documents Preview -->
          <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-t-4 border-t-info overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
              <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">attach_file</span>
                Dokumen Pendukung
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                @forelse($perizinan->dokumens as $dokumen)
                  <a href="{{ asset('storage/' . $dokumen->path) }}" target="_blank"
                    class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors group">
                    <div class="flex items-start gap-3">
                      <div class="h-10 w-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate group-hover:text-primary">
                          {{ $dokumen->nama_file }}
                        </p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold mt-0.5">Dokumen Digital</p>
                      </div>
                      <span
                        class="material-symbols-outlined text-slate-300 group-hover:text-primary text-[20px]">open_in_new</span>
                    </div>
                  </a>
                @empty
                  <div
                    class="col-span-full py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300 text-slate-400 italic text-sm">
                    Belum ada dokumen yang diunggah.
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Content: Diskusi -->
      <div id="tab-diskusi" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
              <span class="material-symbols-outlined text-primary">chat</span>
              Diskusi Perizinan
            </h3>
          </div>
          <div class="p-6 h-[500px] overflow-y-auto custom-scrollbar space-y-4 bg-slate-50/50" id="chat-container">
            @forelse($perizinan->discussions as $chat)
              <div class="flex {{ $chat->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div
                  class="max-w-[80%] {{ $chat->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border border-slate-200' }} p-3 rounded-2xl shadow-sm">
                  <p
                    class="text-[10px] font-bold uppercase {{ $chat->user_id == auth()->id() ? 'text-blue-100 text-right' : 'text-slate-400' }} mb-1">
                    {{ $chat->user->name }} • {{ $chat->created_at->diffForHumans() }}
                  </p>
                  <p class="text-sm">{{ $chat->message }}</p>
                </div>
              </div>
            @empty
              <div class="text-center py-10 text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-2">forum</span>
                <p class="italic">Belum ada diskusi untuk pengajuan ini.</p>
              </div>
            @endforelse
          </div>
          <div class="p-4 bg-white border-t border-slate-100">
            <form action="{{ route('super_admin.perizinan.discussion.store', $perizinan) }}" method="POST">
              @csrf
              <div class="relative">
                <input type="text" name="message"
                  class="w-full pl-4 pr-12 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-primary text-sm"
                  placeholder="Ketik pesan perbaikan atau pertanyaan...">
                <button type="submit"
                  class="absolute right-2 top-2 p-1.5 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors">
                  <span class="material-symbols-outlined">send</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Verification Checklist Panel -->
    <div class="w-full xl:w-96 shrink-0 flex flex-col gap-4">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 sticky top-24 overflow-hidden">
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
          <h3 class="font-bold text-sm">Checklist Administratif</h3>
          <span class="text-[10px] font-bold bg-white/20 px-2 py-1 rounded uppercase tracking-wider">Verifikator</span>
        </div>
        <div class="divide-y divide-slate-100">
          @php $isApproved = $perizinan->status === \App\Enums\PerizinanStatus::DISETUJUI->value || $perizinan->status === \App\Enums\PerizinanStatus::SELESAI->value; @endphp
          <div class="p-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-start gap-3">
              <input type="checkbox" {{ $isApproved ? 'checked' : '' }}
                class="h-5 w-5 text-success rounded border-slate-300 focus:ring-success cursor-pointer mt-0.5">
              <div class="flex-1">
                <label class="text-sm font-semibold text-slate-700 block">Validitas Identitas</label>
                <p class="text-[11px] text-slate-500">Nama lembaga dan NPSN sesuai database referensi.</p>
              </div>
            </div>
          </div>
          <div class="p-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-start gap-3">
              <input type="checkbox" {{ $isApproved ? 'checked' : '' }}
                class="h-5 w-5 text-success rounded border-slate-300 focus:ring-success cursor-pointer mt-0.5">
              <div class="flex-1">
                <label class="text-sm font-semibold text-slate-700 block">Kesesuaian Lokasi</label>
                <p class="text-[11px] text-slate-500">Alamat domisili lembaga jelas dan dapat diverifikasi.</p>
              </div>
            </div>
          </div>
          <div class="p-4 hover:bg-slate-50 transition-colors">
            <div class="flex items-start gap-3">
              <input type="checkbox" {{ $isApproved ? 'checked' : '' }}
                class="h-5 w-5 text-success rounded border-slate-300 focus:ring-success cursor-pointer mt-0.5">
              <div class="flex-1">
                <label class="text-sm font-semibold text-slate-700 block">Kualitas Berkas</label>
                <p class="text-[11px] text-slate-500">Scan dokumen asli, terbaca jelas, dan tidak manipulatif.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
          <p class="text-[11px] text-slate-500 italic">Gunakan panel ini sebagai alat bantu pemeriksaan berkas digital.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Bottom Action Bar -->
  <div
    class="fixed bottom-0 left-0 right-0 md:left-64 bg-white border-t border-slate-200 p-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.1)] z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <button
          class="text-slate-500 hover:text-primary text-xs font-bold flex items-center gap-2 uppercase tracking-tight"
          onclick="window.print()">
          <span class="material-symbols-outlined text-[18px]">print</span>
          Cetak Draft
        </button>
      </div>
      <div class="flex items-center gap-3 w-full sm:w-auto">
        @if($perizinan->status === \App\Enums\PerizinanStatus::DIAJUKAN->value)
          <button onclick="document.getElementById('modalRevision').classList.remove('hidden')"
            class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl border-2 border-warning text-warning hover:bg-warning hover:text-white transition-all text-xs font-bold flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">assignment_return</span>
            KEMBALIKAN PERBAIKAN
          </button>
          <form action="{{ route('super_admin.perizinan.approve', $perizinan) }}" method="POST" class="flex-1 sm:flex-none">
            @csrf
            <button type="submit" onclick="return confirm('Apakah Anda yakin dokumen sudah lengkap dan valid?')"
              class="w-full px-8 py-2.5 rounded-xl bg-success text-white hover:bg-green-600 transition-all text-sm font-bold flex items-center justify-center gap-2 shadow-lg shadow-green-500/30">
              <span class="material-symbols-outlined text-[18px]">check_circle</span>
              SETUJUI & LANJUTKAN
            </button>
          </form>
        @else
          <div class="flex items-center gap-3">
            <div
              class="flex items-center gap-3 px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
              <span
                class="material-symbols-outlined text-[20px] {{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() == 'success' ? 'text-green-500' : 'text-slate-400' }}">info</span>
              <span class="text-xs font-bold uppercase tracking-widest">Status
                {{ \App\Enums\PerizinanStatus::from($perizinan->status)->label() }}</span>
            </div>

            @php $canFinalize = in_array($perizinan->status, [\App\Enums\PerizinanStatus::DISETUJUI->value, \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, \App\Enums\PerizinanStatus::SELESAI->value]); @endphp
            @if($canFinalize)
              <a href="{{ route('super_admin.perizinan.finalisasi', $perizinan) }}"
                class="px-8 py-2.5 rounded-xl bg-primary text-white hover:bg-primary-hover transition-all text-sm font-bold flex items-center justify-center gap-2 shadow-lg shadow-primary/30">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                {{ $perizinan->status === \App\Enums\PerizinanStatus::DISETUJUI->value ? 'LANJUT KE FINALISASI' : 'EDIT FINALISASI' }}
              </a>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Traditional Modal for Revision Catatan -->
  <div id="modalRevision"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
      <div class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold text-slate-800">Catatan Perbaikan</h3>
          <button onclick="document.getElementById('modalRevision').classList.add('hidden')"
            class="text-slate-400 hover:text-slate-600">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <form action="{{ route('super_admin.perizinan.revision', $perizinan) }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Pesan untuk
              Lembaga</label>
            <textarea name="catatan" rows="5" required
              class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm"
              placeholder="Contoh: Lampiran Akta Notaris terpotong, mohon unggah ulang versi lengkap..."></textarea>
          </div>
          <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('modalRevision').classList.add('hidden')"
              class="flex-1 py-3 text-sm font-bold text-slate-500 hover:bg-slate-50 rounded-xl transition-colors">BATAL</button>
            <button type="submit"
              class="flex-1 py-3 bg-warning text-white rounded-xl shadow-lg shadow-yellow-500/20 font-bold text-sm">KIRIM
              CATATAN</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    @media print {

      .fixed,
      nav,
      aside,
      .sticky,
      button,
      form {
        display: none !important;
      }

      main {
        padding: 0 !important;
      }

      .bg-white {
        border: none !important;
      }
    }
  </style>

  <script>
    function switchTab(tabId) {
      // Content switch
      document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
      document.getElementById('tab-' + tabId).classList.remove('hidden');

      // Button switch
      document.querySelectorAll('button[onclick^="switchTab"]').forEach(btn => {
        btn.classList.remove('text-primary', 'border-primary');
        btn.classList.add('text-slate-500', 'border-transparent');
      });
      const activeBtn = event.currentTarget;
      activeBtn.classList.add('text-primary', 'border-primary');
      activeBtn.classList.remove('text-slate-500', 'border-transparent');
    }

    // Scroll chat to bottom
    const chatContainer = document.getElementById('chat-container');
    if (chatContainer) {
      chatContainer.scrollTop = chatContainer.scrollHeight;
    }
  </script>
@endsection