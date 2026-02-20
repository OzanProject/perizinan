@extends('layouts.admin_lembaga')

@section('title', 'Panduan Sistem')

@section('content')
  <div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="relative overflow-hidden bg-primary rounded-[2.5rem] p-10 text-white shadow-2xl group">
      <div
        class="absolute top-0 right-0 w-1/3 h-full bg-white/10 -skew-x-12 translate-x-1/2 group-hover:translate-x-1/3 transition-transform duration-1000">
      </div>
      <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
        <div
          class="size-24 rounded-3xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
          <span class="material-symbols-outlined text-5xl">help_center</span>
        </div>
        <div class="text-center md:text-left">
          <h1 class="text-3xl font-black italic uppercase tracking-tight mb-2">Panduan Penggunaan Sistem</h1>
          <p class="text-primary-100 font-medium text-lg max-w-2xl">Selamat datang di portal pelayanan perizinan. Ikuti
            langkah-langkah di bawah ini untuk memulai pengajuan izin operasional Anda secara digital.</p>
        </div>
      </div>
    </div>

    <!-- Main Content: Vertical Timeline -->
    <div class="max-w-4xl mx-auto px-4">
      <div class="relative">
        <!-- Vertical Line -->
        <div
          class="absolute left-4 md:left-1/2 top-4 bottom-4 w-1 bg-slate-100 dark:bg-slate-800 -translate-x-1/2 hidden md:block">
        </div>

        <div class="space-y-12">
          <!-- Step 1 -->
          <div class="relative flex flex-col md:flex-row items-center group">
            <div class="flex-1 md:text-right md:pr-12 mb-4 md:mb-0 order-2 md:order-1">
              <div
                class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-soft border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all hover:-translate-y-1 relative group/card">
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-3 italic uppercase tracking-tight">1.
                  Lengkapi Profil Lembaga</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Sebelum melakukan pengajuan, pastikan data
                  institusi Anda (NPSN, Alamat, SK Pendirian) sudah lengkap dan benar pada menu <a
                    href="{{ route('admin_lembaga.profile.index') }}"
                    class="text-primary hover:underline font-bold">Profil Lembaga</a>. Data ini akan ditarik secara
                  otomatis ke dalam berkas sertifikat.</p>
                <!-- Decorative number -->
                <span
                  class="absolute -top-4 -right-4 size-10 bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 rounded-xl flex items-center justify-center font-black italic text-xl border border-slate-100 dark:border-slate-700 pointer-events-none transition-colors group-hover/card:text-primary group-hover/card:bg-primary/5">01</span>
              </div>
            </div>
            <div
              class="relative z-10 size-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 border-4 border-white dark:border-slate-950 order-1 md:order-2 mb-4 md:mb-0">
              <span class="material-symbols-outlined text-[24px]">domain</span>
            </div>
            <div class="flex-1 md:pl-12 order-3 hidden md:block"></div>
          </div>

          <!-- Step 2 -->
          <div class="relative flex flex-col md:flex-row items-center group">
            <div class="flex-1 md:pr-12 order-3 hidden md:block"></div>
            <div
              class="relative z-10 size-12 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 border-4 border-white dark:border-slate-950 order-1 md:order-2 mb-4 md:mb-0">
              <span class="material-symbols-outlined text-[24px]">add_circle</span>
            </div>
            <div class="flex-1 md:pl-12 order-2 md:order-3">
              <div
                class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-soft border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all hover:-translate-y-1 relative group/card">
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-3 italic uppercase tracking-tight">2. Buat
                  Pengajuan Baru</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Klik tombol "Ajukan Izin Baru" pada
                  dashboard. Pilih jenis perizinan yang sesuai dengan kebutuhan lembaga Anda. Sistem akan memandu Anda
                  melalui beberapa tahapan pengisian data.</p>
                <span
                  class="absolute -top-4 -left-4 size-10 bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 rounded-xl flex items-center justify-center font-black italic text-xl border border-slate-100 dark:border-slate-700 pointer-events-none transition-colors group-hover/card:text-indigo-500 group-hover/card:bg-indigo-500/5">02</span>
              </div>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="relative flex flex-col md:flex-row items-center group">
            <div class="flex-1 md:text-right md:pr-12 mb-4 md:mb-0 order-2 md:order-1">
              <div
                class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-soft border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all hover:-translate-y-1 relative group/card">
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-3 italic uppercase tracking-tight">3.
                  Unggah Dokumen Persyaratan</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Persiapkan dokumen fisik dalam format PDF
                  atau Gambar. Unggah setiap dokumen sesuai dengan kategori yang diminta. Pastikan dokumen terbaca dengan
                  jelas untuk mempercepat proses verifikasi oleh petugas.</p>
                <span
                  class="absolute -top-4 -right-4 size-10 bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 rounded-xl flex items-center justify-center font-black italic text-xl border border-slate-100 dark:border-slate-700 pointer-events-none transition-colors group-hover/card:text-amber-500 group-hover/card:bg-amber-500/5">03</span>
              </div>
            </div>
            <div
              class="relative z-10 size-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/30 border-4 border-white dark:border-slate-950 order-1 md:order-2 mb-4 md:mb-0">
              <span class="material-symbols-outlined text-[24px]">upload_file</span>
            </div>
            <div class="flex-1 md:pl-12 order-3 hidden md:block"></div>
          </div>

          <!-- Step 4 -->
          <div class="relative flex flex-col md:flex-row items-center group">
            <div class="flex-1 md:pr-12 order-3 hidden md:block"></div>
            <div
              class="relative z-10 size-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30 border-4 border-white dark:border-slate-950 order-1 md:order-2 mb-4 md:mb-0">
              <span class="material-symbols-outlined text-[24px]">order_approve</span>
            </div>
            <div class="flex-1 md:pl-12 order-2 md:order-3">
              <div
                class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-soft border border-slate-100 dark:border-slate-800 hover:shadow-xl transition-all hover:-translate-y-1 relative group/card">
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-3 italic uppercase tracking-tight">4.
                  Pantau Status & Unduh Izin</h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Setelah dikirim, ajuan Anda akan ditinjau.
                  Anda dapat memantau statusnya secara real-time. Jika disetujui, sertifikat izin resmi dapat diunduh
                  langsung dalam bentuk PDF melalui dashboard atau menu riwayat.</p>
                <span
                  class="absolute -top-4 -left-4 size-10 bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 rounded-xl flex items-center justify-center font-black italic text-xl border border-slate-100 dark:border-slate-700 pointer-events-none transition-colors group-hover/card:text-emerald-500 group-hover/card:bg-emerald-500/5">04</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FAQ / Tip Section -->
    <div
      class="bg-slate-50 dark:bg-slate-800/40 rounded-[2.5rem] p-10 mt-12 border border-slate-100 dark:border-slate-800">
      <h2
        class="text-xl font-black text-slate-900 dark:text-white mb-8 italic uppercase tracking-tight flex items-center gap-3">
        <span class="material-symbols-outlined text-primary text-3xl">info</span>
        Tips Tambahan
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex gap-4">
          <div
            class="size-12 rounded-2xl bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
            <span class="material-symbols-outlined">forum</span>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-1 uppercase tracking-wider text-xs">Diskusi & Komentar
            </h4>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">Gunakan fitur diskusi pada detail pengajuan jika
              ada instruksi perbaikan dari petugas dinas.</p>
          </div>
        </div>
        <div class="flex gap-4">
          <div
            class="size-12 rounded-2xl bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
            <span class="material-symbols-outlined">description</span>
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white mb-1 uppercase tracking-wider text-xs">Format Dokumen</h4>
            <p class="text-xs text-slate-500 font-medium leading-relaxed">Gunakan scannery asli (bukan fotokopi) untuk
              memastikan keaslian data yang Anda kirimkan.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection