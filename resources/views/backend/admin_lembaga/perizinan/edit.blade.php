@extends('layouts.admin_lembaga')

@section('title', 'Upload Berkas Pengajuan')

@section('content')
  <main class="flex-1 w-full max-w-[1400px] mx-auto py-8">
    <!-- Breadcrumbs & Header -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <nav aria-label="Breadcrumb" class="flex mb-2">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400"
                href="{{ route('admin_lembaga.dashboard') }}">
                <span class="material-symbols-outlined text-[18px] mr-2">home</span>
                Home
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">chevron_right</span>
                <a class="ml-1 text-sm font-medium text-slate-500 hover:text-primary dark:text-slate-400 md:ml-2"
                  href="{{ route('admin_lembaga.perizinan.index') }}">Lembaga</a>
              </div>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">chevron_right</span>
                <span class="ml-1 text-sm font-medium text-slate-900 dark:text-white md:ml-2">Pengajuan Izin</span>
              </div>
            </li>
          </ol>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Lengkapi Berkas Pengajuan
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan unggah dokumen persyaratan untuk
          {{ $perizinan->jenisPerizinan->nama }}.
        </p>
      </div>
      <div class="flex gap-3">
        <a href="{{ route('admin_lembaga.perizinan.index') }}"
          class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
          <span class="material-symbols-outlined mr-2 text-[20px]">save</span>
          Simpan Draft
        </a>
      </div>
    </div>

    <!-- Progress Stepper -->
    <div class="mb-12">
      <div
        class="relative after:absolute after:inset-x-0 after:top-1/2 after:block after:h-0.5 after:-translate-y-1/2 after:rounded-lg after:bg-slate-200 dark:after:bg-slate-700">
        <ol class="relative z-10 flex justify-between text-sm font-medium text-slate-500 dark:text-slate-400">
          <li class="flex items-center gap-2 bg-background-light dark:bg-background-dark p-2">
            <span
              class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white ring-4 ring-background-light dark:ring-background-dark">
              <span class="material-symbols-outlined text-[18px]">check</span>
            </span>
            <span class="hidden sm:inline text-primary font-bold">Info Umum</span>
          </li>
          <li class="flex items-center gap-2 bg-background-light dark:bg-background-dark p-2">
            <span
              class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white ring-4 ring-background-light dark:ring-background-dark ring-offset-2 ring-primary">
              <span class="text-sm font-bold">2</span>
            </span>
            <span class="hidden sm:inline text-slate-900 dark:text-white font-bold">Upload Berkas</span>
          </li>
          <li class="flex items-center gap-2 bg-background-light dark:bg-background-dark p-2">
            <span
              class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-600 ring-4 ring-background-light dark:ring-slate-700 dark:text-slate-400 dark:ring-background-dark">
              <span class="text-sm font-bold">3</span>
            </span>
            <span class="hidden sm:inline">Review & Submit</span>
          </li>
        </ol>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Main Form Column -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Section: Document Uploads -->
        <div
          class="bg-white dark:bg-[#1e293b] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">folder_open</span>
              Dokumen Persyaratan
            </h2>
            <span
              class="bg-blue-100 text-primary text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-900/30 dark:text-blue-300">Wajib
              Diisi</span>
          </div>
          <div class="p-6 space-y-8">
          <!-- Dynamic Form Fields from Super Admin config -->
          @if($perizinan->jenisPerizinan->form_config)
            <div
              class="bg-blue-50/50 dark:bg-blue-900/10 rounded-xl p-6 border border-blue-100 dark:border-blue-900/30 mb-8">
              <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">edit_note</span>
                Lengkapi Informasi Detail Pengajuan
              </h3>
              <form id="form-detail-pengajuan" action="{{ route('admin_lembaga.perizinan.update_data', $perizinan) }}"
                method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @foreach($perizinan->jenisPerizinan->form_config as $field)
                  <div
                    class="flex flex-col gap-2 group {{ ($field['type'] ?? 'text') == 'textarea' ? 'md:col-span-2' : '' }}">
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest">
                      {{ $field['label'] }} @if($field['required'] ?? false) <span class="text-red-500">*</span> @endif
                    </label>
                    <div class="relative">
                      @if(($field['type'] ?? 'text') == 'textarea')
                        <textarea name="data[{{ $field['name'] }}]"
                          class="w-full rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white px-4 py-3 text-sm font-bold transition-all focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none placeholder:text-slate-400/60 shadow-sm min-h-[100px]"
                          placeholder="Masukkan {{ strtolower($field['label']) }}..." {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ $perizinan->perizinan_data[$field['name']] ?? '' }}</textarea>
                      @else
                        <input type="{{ $field['type'] ?? 'text' }}" name="data[{{ $field['name'] }}]"
                          class="w-full rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-white px-4 py-3 text-sm font-bold transition-all focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none placeholder:text-slate-400/60 shadow-sm"
                          placeholder="Masukkan {{ strtolower($field['label']) }}..."
                          value="{{ $perizinan->perizinan_data[$field['name']] ?? '' }}" {{ ($field['required'] ?? false) ? 'required' : '' }}>
                      @endif
                    </div>
                  </div>
                @endforeach
                <div class="md:col-span-2 flex justify-end">
                  <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    Simpan Perubahan Detail
                  </button>
                </div>
              </form>
            </div>
            <hr class="border-slate-100 dark:border-slate-700/50 mb-8" />
          @endif

          @foreach($syarats as $syarat)
            @php $dokumen = $uploadedDokumens->get($syarat->id); @endphp
            <div class="grid sm:grid-cols-12 gap-6 items-center">
              <div class="sm:col-span-4">
                <label class="block text-sm font-bold text-slate-900 dark:text-white mb-1">
                  {{ $syarat->nama_dokumen }}
                  @if($syarat->is_required) <span class="text-red-500">*</span> @endif
                </label>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                  {{ $syarat->deskripsi ?? 'Format PDF/JPG, Max 5MB.' }}
                </p>

                <div onclick="document.getElementById('file-input-{{ $syarat->id }}').click()"
                  class="relative flex items-center justify-center rounded-lg border-2 border-dashed border-slate-300 px-4 py-8 text-center hover:border-primary hover:bg-slate-50 dark:border-slate-600 dark:hover:border-primary dark:hover:bg-slate-800/50 transition-all cursor-pointer group">
                  <div class="space-y-2">
                    <span
                      class="material-symbols-outlined text-4xl text-slate-400 group-hover:text-primary transition-colors">
                      {{ $dokumen ? 'check_circle' : 'cloud_upload' }}
                    </span>
                    <div class="text-xs text-slate-500 dark:text-slate-400">
                      <span class="font-semibold text-primary">{{ $dokumen ? 'Ganti File' : 'Klik untuk upload' }}</span>
                    </div>
                  </div>
                  <input id="file-input-{{ $syarat->id }}" type="file" class="hidden"
                    onchange="handleFileUpload(event, {{ $syarat->id }})" accept=".pdf,.jpg,.jpeg,.png">
                </div>
              </div>

              <div class="sm:col-span-8">
                <div id="preview-{{ $syarat->id }}" class="{{ $dokumen ? '' : 'hidden' }}">
                  <label class="block text-sm font-medium text-slate-900 dark:text-white mb-1">Preview File</label>
                  <div
                    class="mt-1 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50 flex items-start gap-4">
                    <div
                      class="flex h-12 w-12 flex-none items-center justify-center rounded bg-white shadow-sm dark:bg-slate-700">
                      <span class="material-symbols-outlined text-3xl text-primary">
                        @if($dokumen && str_ends_with($dokumen->path, '.pdf')) picture_as_pdf @else image @endif
                      </span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-slate-900 truncate dark:text-white filename">
                        {{ $dokumen->nama_file ?? '' }}
                      </p>
                      <p class="text-xs text-slate-500">Terunggah pada
                        {{ $dokumen ? $dokumen->created_at->format('d M Y') : '' }}
                      </p>
                      <div class="mt-2 h-1.5 w-full rounded-full bg-slate-200 dark:bg-slate-700">
                        <div class="h-1.5 rounded-full bg-green-500" style="width: 100%"></div>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <button
                        onclick="openFilePreview('{{ $dokumen ? Storage::url($dokumen->path) : '#' }}', '{{ $dokumen->nama_file ?? 'Dokumen' }}')"
                        class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                        title="Lihat File">
                        <span class="material-symbols-outlined">visibility</span>
                      </button>
                      <button id="btn-delete-{{ $syarat->id }}"
                        onclick="deleteFile({{ $perizinan->id }}, {{ $dokumen->id ?? 'null' }}, {{ $syarat->id }})"
                        class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                        title="Hapus File">
                        <span class="material-symbols-outlined">delete</span>
                      </button>
                    </div>
                  </div>
                </div>

                <div id="empty-{{ $syarat->id }}"
                  class="{{ $dokumen ? 'hidden' : '' }} flex items-center justify-center border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800/30 text-slate-400 border-dashed min-h-[120px]">
                  <span class="text-sm italic">Belum ada file dipilih</span>
                </div>

                <div id="loading-{{ $syarat->id }}"
                  class="hidden flex flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800/30 min-h-[120px] gap-2">
                  <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                  <span class="text-xs font-bold text-primary">Sedang Mengunggah...</span>
                </div>
              </div>
            </div>
            @if(!$loop->last)
            <hr class="border-slate-100 dark:border-slate-700/50" /> @endif
          @endforeach
        </div>

        <!-- Form Actions -->
        <div
          class="bg-slate-50 dark:bg-[#16202e] px-6 py-5 flex items-center justify-between border-t border-slate-200 dark:border-slate-700">
          <a href="{{ route('admin_lembaga.perizinan.index') }}"
            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
            <span class="material-symbols-outlined mr-2 text-[18px]">arrow_back</span>
            Batal
          </a>
          <button onclick="checkRequirementsAndProceed()"
            class="inline-flex items-center rounded-lg bg-primary px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
            Lanjut ke Review
            <span class="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar: Checklist & Helpers -->
    <div class="lg:col-span-1 space-y-6">
      <div
        class="bg-white dark:bg-[#1e293b] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sticky top-24">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">fact_check</span>
          Kelengkapan Berkas
        </h3>

        @php
          $requiredCount = $syarats->where('is_required', true)->count();
          $uploadedCount = $uploadedDokumens->whereIn('syarat_perizinan_id', $syarats->where('is_required', true)->pluck('id'))->count();
          $percentage = $requiredCount > 0 ? round(($uploadedCount / $requiredCount) * 100) : 100;
        @endphp

        <div class="mb-6">
          <div class="flex justify-between mb-2">
            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Progress Wajib</span>
            <span id="overall-percentage" class="text-sm font-black text-primary">{{ $percentage }}%</span>
          </div>
          <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
            <div id="progress-bar" class="bg-primary h-2.5 rounded-full transition-all duration-700"
              style="width: {{ $percentage }}%"></div>
          </div>
        </div>

        <ul class="space-y-4" id="document-checklist">
          @foreach($syarats as $syarat)
            @php $isUploaded = $uploadedDokumens->has($syarat->id); @endphp
            <li class="flex items-start gap-3 {{ $isUploaded ? '' : 'opacity-60' }}" id="checklist-item-{{ $syarat->id }}">
              <div
                class="flex-none rounded-full {{ $isUploaded ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'border-2 border-slate-300 dark:border-slate-600' }} p-1 transition-colors duration-500">
                <span
                  class="material-symbols-outlined text-[16px] block font-bold {{ $isUploaded ? '' : 'invisible' }}">check</span>
              </div>
              <div class="flex-1">
                <p
                  class="text-sm font-bold text-slate-900 dark:text-white {{ $isUploaded ? 'line-through decoration-slate-400 decoration-2' : '' }} transition-all duration-500 name-label">
                  {{ $syarat->nama_dokumen }}
                </p>
                <p class="text-[10px] uppercase font-black tracking-widest text-slate-500 status-label">
                  {{ $isUploaded ? 'Terunggah' : 'Menunggu upload...' }}
                </p>
              </div>
            </li>
          @endforeach
        </ul>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
          <div
            class="flex gap-3 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
            <span class="material-symbols-outlined text-primary text-[24px]">info</span>
            <div>
              <h4 class="text-sm font-bold text-slate-900 dark:text-white">Butuh Bantuan?</h4>
              <p class="mt-1 text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                Pastikan semua dokumen hasil scan terbaca dengan jelas. Jika file PDF > 5MB, silakan kompres terlebih
                dahulu.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Review Modal / Step 3 Simulation -->
    <div id="review-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
      role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
          onclick="closeReview()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
          class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
          <div class="px-8 pt-8 pb-6">
            <div class="flex items-center gap-4 mb-6">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-3xl">task_alt</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Review & Konfirmasi Akhir</h3>
                <p class="text-sm text-slate-500">Pastikan data yang Anda masukkan sudah benar.</p>
              </div>
            </div>

            <div
              class="space-y-6 bg-slate-50 dark:bg-slate-900/40 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Perizinan</p>
                  <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $perizinan->jenisPerizinan->nama }}</p>
                </div>
              </div>
              <div class="space-y-3">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Dokumen</p>
                <div id="modal-checklist" class="space-y-2">
                  <!-- Filled by JS -->
                </div>
              </div>
            </div>

            <div
              class="mt-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 flex items-start gap-3">
              <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">shield_check</span>
              <p class="text-xs text-emerald-800 dark:text-emerald-400 font-medium">
                Dengan menekan tombol "Ajukan Sekarang", Anda menyatakan bahwa data dan dokumen yang diunggah adalah sah
                dan benar.
              </p>
            </div>
          </div>
          <div
            class="px-8 py-6 bg-slate-50 dark:bg-slate-800 flex flex-col sm:flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700">
            <form action="{{ route('admin_lembaga.perizinan.submit', $perizinan) }}" method="POST">
              @csrf
              <button type="submit"
                class="w-full inline-flex justify-center items-center rounded-xl bg-primary px-8 py-3.5 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-blue-700 focus:outline-none transition-all transform hover:-translate-y-0.5">
                Ajukan Sekarang <span class="material-symbols-outlined ml-2">rocket_launch</span>
              </button>
            </form>
            <button onclick="closeReview()"
              class="w-full inline-flex justify-center rounded-xl border border-slate-300 bg-white px-8 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors">
              Kembali Periksa
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Floating Chat Button -->
  <button onclick="toggleChat()"
    class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all z-[60] group">
    <span class="material-symbols-outlined text-2xl group-hover:rotate-12">forum</span>
    <span
      class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white dark:border-slate-900 {{ $perizinan->discussions->count() > 0 ? '' : 'hidden' }}"></span>
  </button>

  <!-- Slide-over Chat Panel -->
  <div id="chat-panel"
    class="fixed inset-y-0 right-0 w-full sm:w-[400px] bg-white dark:bg-slate-800 shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 ease-in-out border-l border-slate-200 dark:border-slate-700">
    <div class="flex flex-col h-full">
      <div
        class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined text-primary">forum</span>
          <h3 class="font-bold text-slate-800 dark:text-white">Diskusi 2 Arah</h3>
        </div>
        <button onclick="toggleChat()" class="text-slate-400 hover:text-slate-600 transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-900/20" id="chat-box">
        @forelse($perizinan->discussions as $discussion)
          <div class="flex flex-col {{ $discussion->user_id == Auth::id() ? 'items-end' : 'items-start' }} gap-1">
            <div
              class="max-w-[85%] px-4 py-2.5 rounded-2xl {{ $discussion->user_id == Auth::id() ? 'bg-primary text-white rounded-br-none' : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-100 dark:border-slate-600 rounded-bl-none shadow-sm' }}">
              <p class="text-[10px] font-black mb-1 opacity-70">{{ $discussion->user->name }}</p>
              <p class="text-sm leading-relaxed">{{ $discussion->message }}</p>
            </div>
            <span class="text-[10px] text-slate-400 font-medium px-1">{{ $discussion->created_at->format('H:i') }}</span>
          </div>
        @empty
          <div class="flex flex-col items-center justify-center h-full opacity-30 gap-2">
            <span class="material-symbols-outlined text-5xl">chat_bubble</span>
            <p class="text-sm font-bold italic">Belum ada diskusi.</p>
          </div>
        @endforelse
      </div>

      <div class="p-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
        <form action="{{ route('admin_lembaga.perizinan.discussion.store', $perizinan) }}" method="POST" class="relative">
          @csrf
          <input type="text" name="message" placeholder="Tulis pesan ke dinas..."
            class="w-full pl-4 pr-12 py-3 bg-slate-100 dark:bg-slate-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary transition-all shadow-inner"
            required>
          <button type="submit"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-primary hover:scale-110 transition-transform">
            <span class="material-symbols-outlined">send</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- File Preview Modal -->
  <div id="file-preview-modal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
        onclick="closeFilePreview()"></div>
      <div
        class="relative bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-2xl transform transition-all w-full max-w-5xl h-[85vh] flex flex-col border border-slate-200 dark:border-slate-700">
        <div
          class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-white dark:bg-slate-800">
          <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">visibility</span>
            <span id="preview-filename">Pratinjau Dokumen</span>
          </h3>
          <div class="flex items-center gap-2">
            <a id="preview-download-link" href="#" target="_blank"
              class="p-2 text-slate-500 hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-all">
              <span class="material-symbols-outlined">download</span>
            </a>
            <button onclick="closeFilePreview()"
              class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
        </div>
        <div class="flex-1 bg-slate-100 dark:bg-slate-900 overflow-hidden relative">
          <iframe id="preview-iframe" src="" class="w-full h-full border-none"></iframe>
          <div id="preview-image-container" class="hidden w-full h-full flex items-center justify-center p-4">
            <img id="preview-image" src="" class="max-w-full max-h-full object-contain shadow-lg">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    const syarats = @json($syarats);
    let uploadedCount = {{ $uploadedCount }};
    const requiredCount = {{ $requiredCount }};

    function handleFileUpload(event, syaratId) {
      const file = event.target.files[0];
      if (!file) return;

      // UI State: Loading
      document.getElementById(`empty-${syaratId}`).classList.add('hidden');
      document.getElementById(`preview-${syaratId}`).classList.add('hidden');
      document.getElementById(`loading-${syaratId}`).classList.remove('hidden');

      const formData = new FormData();
      formData.append('file', file);
      formData.append('syarat_id', syaratId);
      formData.append('_token', '{{ csrf_token() }}');

      fetch(`{{ route('admin_lembaga.perizinan.upload_dokumen', $perizinan) }}`, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          document.getElementById(`loading-${syaratId}`).classList.add('hidden');

          if (data.success) {
            // Update Preview Card
            const preview = document.getElementById(`preview-${syaratId}`);
            preview.classList.remove('hidden');
            preview.querySelector('.filename').textContent = file.name;

            // Update Preview & Delete Buttons
            const viewBtn = preview.querySelector('button[title="Lihat File"]');
            viewBtn.setAttribute('onclick', `openFilePreview('${data.path}', '${file.name}')`);

            const deleteBtn = document.getElementById(`btn-delete-${syaratId}`);
            deleteBtn.setAttribute('onclick', `deleteFile({{ $perizinan->id }}, ${data.id}, ${syaratId})`);

            // Update Sidebar Checklist
            const item = document.getElementById(`checklist-item-${syaratId}`);
            item.classList.remove('opacity-60');
            const badge = item.querySelector('.flex-none');
            badge.classList.remove('border-2', 'border-slate-300', 'dark:border-slate-600');
            badge.classList.add('bg-green-100', 'text-green-600', 'dark:bg-green-900/30', 'dark:text-green-400');
            badge.querySelector('.material-symbols-outlined').classList.remove('invisible');
            item.querySelector('.name-label').classList.add('line-through', 'decoration-slate-400', 'decoration-2');
            item.querySelector('.status-label').textContent = 'Terunggah';

            // Update Progress Calculation if it was required and not yet counted
            // This is a simple client-side update, true state is in DB
            updateProgress();

            // Show toast or snackbar
            alert('Berhasil mengunggah dokumen.');
          } else {
            document.getElementById(`empty-${syaratId}`).classList.remove('hidden');
            alert(data.message || 'Gagal mengunggah dokumen.');
          }
        })
        .catch(error => {
          document.getElementById(`loading-${syaratId}`).classList.add('hidden');
          document.getElementById(`empty-${syaratId}`).classList.remove('hidden');
          console.error('Error:', error);
          alert('Terjadi kesalahan saat mengunggah.');
        });
    }

    function updateProgress() {
      // Simple client-side progress estimation
      const items = document.querySelectorAll('#document-checklist li');
      let currentUploaded = 0;
      let currentRequired = 0;

      syarats.forEach(s => {
        if (s.is_required) {
          currentRequired++;
          const item = document.getElementById(`checklist-item-${s.id}`);
          if (item.querySelector('.material-symbols-outlined').classList.contains('invisible') === false) {
            currentUploaded++;
          }
        }
      });

      const percentage = currentRequired > 0 ? Math.round((currentUploaded / currentRequired) * 100) : 100;
      document.getElementById('overall-percentage').textContent = percentage + '%';
      document.getElementById('progress-bar').style.width = percentage + '%';
    }

    function checkRequirementsAndProceed() {
      // Validate required documents
      let missing = [];
      syarats.forEach(s => {
        if (s.is_required) {
          const item = document.getElementById(`checklist-item-${s.id}`);
          if (item.querySelector('.material-symbols-outlined').classList.contains('invisible')) {
            missing.push(s.nama_dokumen);
          }
        }
      });

      if (missing.length > 0) {
        alert('Mohon lengkapi dokumen wajib berikut:\n- ' + missing.join('\n- '));
        return;
      }

      openReview();
    }

    function openReview() {
      const modal = document.getElementById('review-modal');
      const list = document.getElementById('modal-checklist');
      list.innerHTML = '';

      syarats.forEach(s => {
        const item = document.getElementById(`checklist-item-${s.id}`);
        const isUploaded = !item.querySelector('.material-symbols-outlined').classList.contains('invisible');

        const div = document.createElement('div');
        div.className = 'flex items-center justify-between text-sm py-1';
        div.innerHTML = `
                        <span class="text-slate-600 dark:text-slate-300 font-medium">${s.nama_dokumen}</span>
                        <span class="flex items-center gap-1 font-bold ${isUploaded ? 'text-emerald-500' : 'text-slate-400'}">
                            <span class="material-symbols-outlined text-[18px]">${isUploaded ? 'check_circle' : 'pending'}</span>
                            ${isUploaded ? 'Siap' : 'Belum Ada'}
                        </span>
                    `;
        list.appendChild(div);
      });

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeReview() {
      document.getElementById('review-modal').classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    function toggleChat() {
      const panel = document.getElementById('chat-panel');
      const isHidden = panel.classList.contains('translate-x-full');

      if (isHidden) {
        panel.classList.remove('translate-x-full');
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
      } else {
        panel.classList.add('translate-x-full');
      }
    }

    function openFilePreview(url, filename) {
      const modal = document.getElementById('file-preview-modal');
      const iframe = document.getElementById('preview-iframe');
      const imgContainer = document.getElementById('preview-image-container');
      const img = document.getElementById('preview-image');
      const filenameDisplay = document.getElementById('preview-filename');
      const downloadLink = document.getElementById('preview-download-link');

      filenameDisplay.textContent = filename;
      downloadLink.href = url;

      if (url.toLowerCase().endsWith('.pdf')) {
        iframe.classList.remove('hidden');
        imgContainer.classList.add('hidden');
        iframe.src = url;
      } else {
        iframe.classList.add('hidden');
        imgContainer.classList.remove('hidden');
        img.src = url;
        iframe.src = '';
      }

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeFilePreview() {
      const modal = document.getElementById('file-preview-modal');
      const iframe = document.getElementById('preview-iframe');
      iframe.src = '';
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    function deleteFile(perizinanId, dokumenId, syaratId) {
      if (!dokumenId) return;
      if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) return;

      fetch(`/admin-lembaga/perizinan/${perizinanId}/dokumen/${dokumenId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // UI Update
            document.getElementById(`preview-${syaratId}`).classList.add('hidden');
            document.getElementById(`empty-${syaratId}`).classList.remove('hidden');

            // Update Badge Checkmark
            const item = document.getElementById(`checklist-item-${syaratId}`);
            item.classList.add('opacity-60');
            const badge = item.querySelector('.flex-none');
            badge.classList.add('border-2', 'border-slate-300', 'dark:border-slate-600');
            badge.classList.remove('bg-green-100', 'text-green-600', 'dark:bg-green-900/30', 'dark:text-green-400');
            badge.querySelector('.material-symbols-outlined').classList.add('invisible');
            item.querySelector('.name-label').classList.remove('line-through', 'decoration-slate-400', 'decoration-2');
            item.querySelector('.status-label').textContent = 'Menunggu upload...';

            updateProgress();
            alert('Dokumen berhasil dihapus.');
          } else {
            alert(data.message || 'Gagal menghapus dokumen.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menghapus.');
        });
    }
  </script>
@endpush