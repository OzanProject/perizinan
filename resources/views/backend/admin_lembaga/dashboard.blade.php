@extends('layouts.admin_lembaga')

@section('title', 'Dashboard')

@section('content')
  <div class="space-y-8">
    @if(!$perizinan)
      <!-- Empty State: No Applications Yet -->
      <div
        class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center flex flex-col items-center gap-6">
        <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-primary">
          <span class="material-symbols-outlined text-5xl">description</span>
        </div>
        <div class="max-w-md">
          <h3 class="text-xl font-bold text-text-primary dark:text-white mb-2">Belum Ada Pengajuan Izin</h3>
          <p class="text-text-secondary dark:text-slate-400">Anda belum memiliki riwayat pengajuan izin operasional. Silakan
            mulai pengajuan baru untuk melegalkan operasional lembaga Anda.</p>
        </div>
        <a href="{{ route('admin_lembaga.perizinan.create') }}"
          class="bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
          <span class="material-symbols-outlined">add_circle</span>
          Buat Pengajuan Sekarang
        </a>
      </div>
    @else
      @php
        $status = $perizinan->status;
        // Step Mapping: diajukan, verifikasi, perbaikan, disetujui, selesai
        $isDiajukan = in_array($status, ['diajukan', 'perbaikan', 'disetujui', 'siap_diambil', 'selesai']);
        $isVerifikasi = in_array($status, ['diajukan', 'perbaikan', 'disetujui', 'siap_diambil', 'selesai']);
        $isPerbaikan = in_array($status, ['perbaikan']);
        $isDisetujui = in_array($status, ['disetujui', 'siap_diambil', 'selesai']);
        $isSelesai = in_array($status, ['selesai', 'disetujui', 'siap_diambil']);

        // Step Styling Logic
        $step1Class = $isDiajukan ? 'bg-green-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400';
        $step1LabelClass = $isDiajukan ? 'text-green-600 dark:text-green-400' : 'text-slate-500';

        $step2Class = ($status == 'diajukan') ? 'bg-amber-500 text-white ring-2 ring-amber-500 ring-offset-2 ring-offset-surface-light dark:ring-offset-background-dark shadow-lg' : ($isDisetujui || $isPerbaikan || $isSelesai ? 'bg-green-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400');
        $step2LabelClass = ($status == 'diajukan') ? 'text-amber-600 dark:text-amber-400 font-bold' : ($isDisetujui || $isPerbaikan || $isSelesai ? 'text-green-600 dark:text-green-400' : 'text-slate-500');

        $step3Class = ($status == 'perbaikan') ? 'bg-amber-500 text-white ring-2 ring-amber-500 ring-offset-2 ring-offset-surface-light dark:ring-offset-background-dark shadow-lg' : ($isDisetujui || $isSelesai ? 'bg-green-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400');
        $step3LabelClass = ($status == 'perbaikan') ? 'text-amber-600 dark:text-amber-400 font-bold' : ($isDisetujui || $isSelesai ? 'text-green-600 dark:text-green-400' : 'text-slate-500');

        $step4Class = $isDisetujui ? 'bg-green-500 text-white shadow-lg' : 'bg-slate-200 dark:bg-slate-700 text-slate-400';
        $step4LabelClass = $isDisetujui ? 'text-green-600 dark:text-green-400 font-bold' : 'text-slate-500';

        $step5Class = $isSelesai ? 'bg-green-500 text-white shadow-lg' : 'bg-slate-200 dark:bg-slate-700 text-slate-400';
        $step5LabelClass = $isSelesai ? 'text-green-600 dark:text-green-400 font-bold' : 'text-slate-500';
      @endphp

      <!-- Workflow Stepper -->
      <div class="w-full">
        <div class="relative flex items-center justify-between w-full mb-8">
          <div
            class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 dark:bg-slate-700 -z-10 rounded-full">
          </div>

          <!-- Step 1: Diajukan -->
          <div class="flex flex-col items-center gap-2 group cursor-default">
            <div
              class="w-10 h-10 rounded-full {{ $step1Class }} flex items-center justify-center border-4 border-surface-light dark:border-background-dark shadow-sm transition-all duration-300">
              <span class="material-symbols-outlined text-[20px]">{{ $isDiajukan ? 'check' : '1' }}</span>
            </div>
            <span
              class="text-xs font-semibold {{ $step1LabelClass }} bg-surface-light dark:bg-background-dark px-2 rounded">Diajukan</span>
          </div>

          <!-- Step 2: Verifikasi -->
          <div class="flex flex-col items-center gap-2 group cursor-default {{ !$isDiajukan ? 'opacity-50' : '' }}">
            <div
              class="w-10 h-10 rounded-full {{ $step2Class }} flex items-center justify-center border-4 border-surface-light dark:border-background-dark shadow-sm transition-all duration-300">
              @if($status == 'diajukan')
                <span class="material-symbols-outlined text-[20px] animate-pulse">ads_click</span>
              @else
                <span
                  class="material-symbols-outlined text-[20px]">{{ ($isDisetujui || $isPerbaikan || $isSelesai) ? 'check' : '2' }}</span>
              @endif
            </div>
            <span
              class="text-xs font-semibold {{ $step2LabelClass }} bg-surface-light dark:bg-background-dark px-2 rounded">Verifikasi</span>
          </div>

          <!-- Step 3: Perbaikan -->
          <div
            class="flex flex-col items-center gap-2 group cursor-default {{ (!$isVerifikasi || ($status != 'perbaikan' && !$isDisetujui && !$isSelesai)) ? 'opacity-50' : '' }}">
            <div
              class="w-10 h-10 rounded-full {{ $step3Class }} flex items-center justify-center border-4 border-surface-light dark:border-background-dark shadow-sm transition-all duration-300">
              @if($status == 'perbaikan')
                <span class="material-symbols-outlined text-[20px] animate-pulse">edit_document</span>
              @else
                <span class="material-symbols-outlined text-[20px]">{{ ($isDisetujui || $isSelesai) ? 'check' : '3' }}</span>
              @endif
            </div>
            <span
              class="text-xs font-semibold {{ $step3LabelClass }} bg-surface-light dark:bg-background-dark px-2 rounded">Perbaikan</span>
          </div>

          <!-- Step 4: Disetujui -->
          <div
            class="flex flex-col items-center gap-2 group cursor-default {{ !$isDisetujui && !$isSelesai ? 'opacity-50' : '' }}">
            <div
              class="w-10 h-10 rounded-full {{ $step4Class }} flex items-center justify-center border-4 border-surface-light dark:border-background-dark shadow-sm transition-all duration-300">
              @if($status == 'disetujui' || $status == 'siap_diambil')
                <span class="material-symbols-outlined text-[20px] animate-pulse">verified_user</span>
              @else
                <span class="material-symbols-outlined text-[20px]">{{ $isSelesai ? 'check' : '4' }}</span>
              @endif
            </div>
            <span
              class="text-xs font-semibold {{ $step4LabelClass }} bg-surface-light dark:bg-background-dark px-2 rounded">Disetujui</span>
          </div>

          <!-- Step 5: Selesai -->
          <div class="flex flex-col items-center gap-2 group cursor-default {{ !$isSelesai ? 'opacity-50' : '' }}">
            <div
              class="w-10 h-10 rounded-full {{ $step5Class }} flex items-center justify-center border-4 border-surface-light dark:border-background-dark shadow-sm transition-all duration-300">
              <span class="material-symbols-outlined text-[20px]">{{ $isSelesai ? 'check' : '5' }}</span>
            </div>
            <span
              class="text-xs font-semibold {{ $step5LabelClass }} bg-surface-light dark:bg-background-dark px-2 rounded">Selesai</span>
          </div>
        </div>
      </div>

      <!-- Main Status Card -->
      <div
        class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-300">

        @if($status == 'perbaikan')
          <!-- Header: Perbaikan Style -->
          <div
            class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-800/30 p-6 flex items-start gap-5">
            <div class="flex-shrink-0">
              <div
                class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <span class="material-symbols-outlined text-[28px]">warning</span>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-xl font-bold text-amber-800 dark:text-amber-400 mb-2">Dokumen Perlu Perbaikan</h3>
              <p class="text-amber-700 dark:text-amber-200/80 leading-relaxed text-sm md:text-base">
                Yth. Admin {{ Auth::user()->lembaga->nama_lembaga }}, berdasarkan hasil verifikasi tim Dinas Pendidikan,
                terdapat beberapa bagian yang belum memenuhi syarat. Mohon segera diperbaiki agar proses perizinan dapat
                dilanjutkan.
              </p>
            </div>
          </div>

          <!-- Notes -->
          <div class="p-6 md:p-8">
            <div class="mb-6">
              <h4 class="text-sm uppercase tracking-wider font-semibold text-text-secondary mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">sticky_note_2</span>
                Catatan dari Verifikator
              </h4>
              <div
                class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 flex items-start gap-4">
                <span class="material-symbols-outlined text-red-500 mt-0.5 shrink-0">cancel</span>
                <div>
                  <p class="font-medium text-text-primary dark:text-white">Review Dokumen Utama</p>
                  <p class="text-sm text-text-secondary mt-1">
                    "{{ $perizinan->catatan_verifikator ?? 'Mohon periksa kembali kelengkapan dokumen sesuai petunjuk teknis.' }}"
                  </p>
                </div>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 pt-6 border-t border-slate-100 dark:border-slate-700">
              <a href="{{ route('admin_lembaga.perizinan.index') }}"
                class="w-full sm:w-auto bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">edit</span>
                Perbaiki Sekarang
              </a>
              <button
                class="w-full sm:w-auto bg-surface-light dark:bg-slate-800 text-text-primary dark:text-white border border-slate-300 dark:border-slate-600 px-6 py-3 rounded-lg font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">contact_support</span>
                Hubungi Verifikator
              </button>
            </div>
          </div>

        @elseif($status == 'disetujui' || $status == 'siap_diambil' || $status == 'selesai')
          <!-- Header: Success Style -->
          <div
            class="bg-emerald-50 dark:bg-emerald-900/20 border-b border-emerald-100 dark:border-emerald-800/30 p-6 flex items-start gap-5">
            <div class="flex-shrink-0">
              <div
                class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-[28px]">verified</span>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-400 mb-2">Pengajuan Disetujui</h3>
              <p class="text-emerald-700 dark:text-emerald-200/80 leading-relaxed text-sm md:text-base">
                Selamat! Pengajuan perizinan Anda telah disetujui.
                {{ $status == 'siap_diambil' ? 'Dokumen fisik sudah siap diambil di kantor dinas.' : 'Silakan unduh dokumen Anda di panel yang tersedia.' }}
              </p>
            </div>
          </div>
          <div class="p-6 md:p-8">
            <div class="flex flex-col sm:flex-row items-center gap-4">
              <button
                class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-bold transition-all shadow-lg flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">download</span> Unduh Dokumen Izin
              </button>
              <a href="{{ route('admin_lembaga.perizinan.index') }}"
                class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-text-secondary hover:text-primary transition-colors">Lihat
                Riwayat</a>
            </div>
          </div>

        @else
          <!-- Header: Default/Info Style -->
          <div
            class="bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800/30 p-6 flex items-start gap-5">
            <div class="flex-shrink-0">
              <div
                class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">info</span>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-xl font-bold text-blue-800 dark:text-blue-400 mb-2">Status:
                {{ \App\Enums\PerizinanStatus::from($status)->label() }}
              </h3>
              <p class="text-blue-700 dark:text-blue-200/80 leading-relaxed text-sm md:text-base">
                Permohonan Anda sedang dalam tahap <b>{{ \App\Enums\PerizinanStatus::from($status)->label() }}</b>. Tim kami
                sedang meninjau kelengkapan berkas Anda.
              </p>
            </div>
          </div>
          <div class="p-6 md:p-8">
            <div class="flex items-center gap-4">
              <a href="{{ route('admin_lembaga.perizinan.show', $perizinan) }}"
                class="bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-lg font-bold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">visibility</span> Lihat Detail
              </a>
              <a href="{{ route('admin_lembaga.perizinan.index') }}"
                class="text-text-secondary hover:text-primary text-sm font-medium">Lacak Semua Pengajuan</a>
            </div>
          </div>
        @endif
      </div>

      <!-- Summary Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
          <div class="flex items-center gap-4 mb-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-primary">
              <span class="material-symbols-outlined">folder_open</span>
            </div>
            <h5 class="font-semibold text-text-primary dark:text-white">Jenis Izin</h5>
          </div>
          <p class="text-text-secondary dark:text-slate-400 text-sm font-medium">{{ $perizinan->jenisPerizinan->nama }}</p>
        </div>

        <div
          class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
          <div class="flex items-center gap-4 mb-3">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
              <span class="material-symbols-outlined">calendar_today</span>
            </div>
            <h5 class="font-semibold text-text-primary dark:text-white">Tanggal Pengajuan</h5>
          </div>
          <p class="text-text-secondary dark:text-slate-400 text-sm font-medium">
            {{ $perizinan->created_at->format('d F Y') }}
          </p>
        </div>

        <div
          class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
          <div class="flex items-center gap-4 mb-3">
            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
              <span class="material-symbols-outlined">verified_user</span>
            </div>
            <h5 class="font-semibold text-text-primary dark:text-white">Nomor Tiket</h5>
          </div>
          <p class="text-text-secondary dark:text-slate-400 text-sm font-mono font-bold">
            {{ $perizinan->nomor_ajuan ?? 'DALAM ANTRIAN' }}
          </p>
        </div>
      </div>
    @endif
  </div>
@endsection