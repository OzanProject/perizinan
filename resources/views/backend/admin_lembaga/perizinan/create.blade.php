@extends('layouts.admin_lembaga')

@section('title', 'Buat Pengajuan Izin Baru')

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
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Form Pengajuan Izin Baru
        </h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan lengkapi data lembaga PKBM Anda untuk proses
          verifikasi.</p>
      </div>
      <div class="flex gap-3">
        <a href="{{ route('admin_lembaga.perizinan.index') }}"
          class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
          <span class="material-symbols-outlined mr-2 text-[20px]">close</span>
          Batalkan
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
              class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white ring-4 ring-background-light dark:ring-background-dark ring-offset-2 ring-primary">
              <span class="text-sm font-bold">1</span>
            </span>
            <span class="hidden sm:inline text-slate-900 dark:text-white font-bold">Info Umum</span>
          </li>
          <li class="flex items-center gap-2 bg-background-light dark:bg-background-dark p-2">
            <span
              class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-600 ring-4 ring-background-light dark:ring-slate-700 dark:text-slate-400 dark:ring-background-dark">
              <span class="text-sm font-bold">2</span>
            </span>
            <span class="hidden sm:inline">Upload Berkas</span>
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
      <div class="lg:col-span-2">
        <div
          class="bg-white dark:bg-[#1e293b] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
          <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">info</span>
              Pilih Jenis Izin
            </h2>
          </div>

          <form action="{{ route('admin_lembaga.perizinan.store') }}" method="POST">
            @csrf
            <div class="p-8 space-y-6">
              <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">Jenis
                  Perizinan yang Diajukan</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  @foreach($jenisPerizinans as $jp)
                    <label
                      class="relative flex cursor-pointer rounded-xl border border-slate-200 p-4 shadow-sm focus:outline-none hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-800 transition-all group">
                      <input type="radio" name="jenis_perizinan_id" value="{{ $jp->id }}" class="sr-only" required {{ $loop->first ? 'checked' : '' }}>
                      <span class="flex flex-1">
                        <span class="flex flex-col">
                          <span
                            class="block text-sm font-bold text-slate-900 dark:text-white group-has-[:checked]:text-primary">{{ $jp->nama }}</span>
                          <span class="mt-1 flex items-center text-xs text-slate-500 dark:text-slate-400">
                            {{ $jp->deskripsi ?? 'Pengajuan izin untuk operasional lembaga.' }}
                          </span>
                        </span>
                      </span>
                      <span
                        class="material-symbols-outlined text-primary opacity-0 group-has-[:checked]:opacity-100 transition-opacity">check_circle</span>
                      <span
                        class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent group-has-[:checked]:border-primary"
                        aria-hidden="true"></span>
                    </label>
                  @endforeach
                </div>
              </div>

              <div
                class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/30 flex items-start gap-4">
                <span class="material-symbols-outlined text-primary text-[24px]">contact_support</span>
                <div>
                  <h4 class="text-sm font-bold text-slate-900 dark:text-white">Informasi Lanjutan</h4>
                  <p class="mt-1 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Setelah menekan tombol "Simpan & Lanjut", Anda akan diarahkan ke halaman unggah berkas untuk
                    melengkapi dokumen yang dipersyaratkan sesuai jenis izin yang dipilih.
                  </p>
                </div>
              </div>
            </div>

            <div
              class="bg-slate-50 dark:bg-[#16202e] px-6 py-5 flex items-center justify-end border-t border-slate-200 dark:border-slate-700">
              <button type="submit"
                class="inline-flex items-center rounded-lg bg-primary px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                Simpan & Lanjut Ke Upload
                <span class="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar: Checklist Helpers -->
      <div class="lg:col-span-1 space-y-6">
        <div
          class="bg-white dark:bg-[#1e293b] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sticky top-24">
          <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">tips_and_updates</span>
            Panduan Langkah 1
          </h3>
          <ul class="space-y-4">
            <li class="flex items-start gap-3">
              <div class="flex-none rounded-lg bg-blue-50 dark:bg-blue-900/30 p-2 text-primary font-bold text-xs">1</div>
              <div class="flex-1">
                <p class="text-sm font-bold text-slate-900 dark:text-white">Pilih Jenis Izin</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Pilih salah satu izin operasional yang ingin Anda
                  ajukan.</p>
              </div>
            </li>
            <li class="flex items-start gap-3 opacity-40">
              <div class="flex-none rounded-lg bg-slate-100 dark:bg-slate-700 p-2 text-slate-500 font-bold text-xs">2
              </div>
              <div class="flex-1">
                <p class="text-sm font-bold text-slate-900 dark:text-white">Lengkapi Dokumen</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Unggah berkas PDF/JPG sesuai persyaratan.</p>
              </div>
            </li>
            <li class="flex items-start gap-3 opacity-40">
              <div class="flex-none rounded-lg bg-slate-100 dark:bg-slate-700 p-2 text-slate-500 font-bold text-xs">3
              </div>
              <div class="flex-1">
                <p class="text-sm font-bold text-slate-900 dark:text-white">Final Review</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi seluruh data sebelum disubmit ke dinas.
                </p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </main>
@endsection