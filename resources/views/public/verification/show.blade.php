<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Sertifikat Digital - SIM-IZIN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .glass {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
  </style>
</head>

<body
  class="bg-slate-50 text-slate-900 min-h-screen flex flex-col items-center justify-center p-6 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:20px_20px]">

  <div
    class="max-w-md w-full glass rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden animate-in fade-in zoom-in duration-700">
    <!-- Status Header -->
    <div class="p-8 pb-4 text-center">
      @if($isValid)
        <div
          class="size-24 bg-emerald-500 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-emerald-500/30 mb-6 group">
          <span
            class="material-symbols-outlined text-white text-5xl group-hover:scale-110 transition-transform">verified</span>
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Sertifikat Valid</h1>
        <p class="text-sm font-medium text-slate-500 uppercase tracking-widest">Digital Certificate Verified</p>
      @else
        <div
          class="size-24 bg-amber-500 rounded-full mx-auto flex items-center justify-center shadow-lg shadow-amber-500/30 mb-6 group">
          <span
            class="material-symbols-outlined text-white text-5xl group-hover:scale-110 transition-transform">info</span>
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Dalam Proses</h1>
        <p class="text-sm font-medium text-slate-500 uppercase tracking-widest">Verification Pending</p>
      @endif
    </div>

    <!-- Details Card -->
    <div class="px-8 pb-8 space-y-6">
      <div class="bg-white/50 rounded-3xl p-6 border border-slate-100 space-y-4 shadow-sm">
        <div class="flex flex-col gap-1">
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor Surat</span>
          <span class="text-sm font-bold text-slate-900 font-mono">{{ $perizinan->nomor_surat ?? 'TBD' }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Perizinan</span>
          <span class="text-sm font-bold text-slate-900">{{ $perizinan->jenisPerizinan->nama }}</span>
        </div>
        <div class="flex flex-col gap-1 border-t border-slate-50 pt-3">
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penerima Izin</span>
          <span class="text-sm font-bold text-primary">{{ $perizinan->lembaga->nama_lembaga }}</span>
        </div>
        <div class="flex flex-col gap-1 border-t border-slate-50 pt-3">
          <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Terbit</span>
          <span
            class="text-sm font-bold text-slate-900">{{ $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('d F Y') : 'Dalam Proses' }}</span>
        </div>
      </div>

      <!-- Authority Info -->
      <div class="flex items-center gap-4 px-4">
        <div
          class="size-12 rounded-2xl bg-white shadow-sm flex items-center justify-center border border-slate-100 p-2">
          <img
            src="{{ $perizinan->dinas->logo ? Storage::url($perizinan->dinas->logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7S9gBqg4Y0_5UfL8Z0f_y99_f_0_f_0&s' }}"
            class="w-full h-full object-contain grayscale opacity-50" alt="Dinas Logo">
        </div>
        <div class="flex-1">
          <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Diterbitkan Oleh</p>
          <p class="text-xs font-bold text-slate-600">{{ $perizinan->dinas->nama_dinas }}</p>
        </div>
      </div>

      <!-- Action -->
      <div class="pt-4">
        <a href="/"
          class="flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-4 text-sm font-black text-white hover:bg-slate-800 transition-all active:scale-95 shadow-xl shadow-slate-900/10">
          <span class="material-symbols-outlined mr-2 text-[20px]">home</span>
          Kembali ke Beranda
        </a>
      </div>
    </div>

    <!-- Footer Decor -->
    <div class="bg-slate-900 py-3 text-center">
      <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] italic">Official Blockchain Verified
        Certificate (Simulation)</p>
    </div>
  </div>

  <!-- Background Decoration -->
  <div class="fixed -bottom-24 -left-24 size-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
  <div class="fixed -top-24 -right-24 size-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

</body>

</html>