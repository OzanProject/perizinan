@extends('layouts.admin_lembaga')

@section('title', 'Konfigurasi Data Lembaga')

@section('content')
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tight uppercase">Data Profil Lembaga
        </h1>
        <p class="text-sm text-slate-500 font-medium">Lengkapi data di bawah ini untuk kebutuhan sinkronisasi otomatis ke
          sertifikat perizinan.</p>
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" form="profile-form"
          class="bg-primary text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all active:scale-95 flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">save</span>
          Simpan Perubahan
        </button>
      </div>
    </div>

    <form action="{{ route('admin_lembaga.profile.update') }}" method="POST" enctype="multipart/form-data"
      id="profile-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      @csrf

      <!-- Left Column: Main Form -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Section: Identitas Utama (Main Identity) -->
        <div
          class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
          <div class="flex items-center gap-3 border-b border-slate-50 dark:border-slate-800 pb-4 mb-2">
            <span class="material-symbols-outlined text-primary">domain</span>
            <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">Identitas Satuan
              Pendidikan</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Nama Lembaga</label>
              <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $lembaga->nama_lembaga) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                required placeholder="Contoh: PKBM Harapan Bangsa">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">NPSN</label>
              <input type="text" name="npsn" value="{{ old('npsn', $lembaga->npsn) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner font-mono"
                required placeholder="8 Digit Angka">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Status
                Kepemilikan</label>
              <select name="status_kepemilikan"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner">
                <option value="Yayasan" {{ $lembaga->status_kepemilikan == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                <option value="Negeri" {{ $lembaga->status_kepemilikan == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                <option value="Swasta" {{ $lembaga->status_kepemilikan == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                <option value="Lainnya" {{ $lembaga->status_kepemilikan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Akreditasi</label>
              <select name="akreditasi"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner">
                <option value="">Belum Terakreditasi</option>
                <option value="A" {{ $lembaga->akreditasi == 'A' ? 'selected' : '' }}>Grade A</option>
                <option value="B" {{ $lembaga->akreditasi == 'B' ? 'selected' : '' }}>Grade B</option>
                <option value="C" {{ $lembaga->akreditasi == 'C' ? 'selected' : '' }}>Grade C</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Section: Pimpinan & Legalitas -->
        <div
          class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
          <div class="flex items-center gap-3 border-b border-slate-50 dark:border-slate-800 pb-4 mb-2">
            <span class="material-symbols-outlined text-indigo-500">description</span>
            <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">Legalitas & SK
              Pendirian</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">SK Pendirian
                Lembaga</label>
              <input type="text" name="sk_pendirian" value="{{ old('sk_pendirian', $lembaga->sk_pendirian) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                placeholder="Nomor SK Pendirian">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Tanggal SK
                Pendirian</label>
              <input type="date" name="tanggal_sk_pendirian"
                value="{{ old('tanggal_sk_pendirian', $lembaga->tanggal_sk_pendirian ? $lembaga->tanggal_sk_pendirian->format('Y-m-d') : '') }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">SK Izin
                Operasional</label>
              <input type="text" name="sk_izin_operasional"
                value="{{ old('sk_izin_operasional', $lembaga->sk_izin_operasional) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                placeholder="Nomor SK Izin Operasional">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Masa Berlaku Izin</label>
              <input type="date" name="masa_berlaku_izin"
                value="{{ old('masa_berlaku_izin', $lembaga->masa_berlaku_izin ? $lembaga->masa_berlaku_izin->format('Y-m-d') : '') }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner">
            </div>
          </div>
        </div>

        <!-- Section: Visi & Misi -->
        <div
          class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
          <div class="flex items-center gap-3 border-b border-slate-50 dark:border-slate-800 pb-4 mb-2">
            <span class="material-symbols-outlined text-amber-500">lightbulb</span>
            <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">Visi Lembaga</h2>
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Kalimat Visi</label>
            <textarea name="visi" rows="3"
              class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm font-medium italic focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
              placeholder="Contoh: Menjadi lembaga pendidikan yang unggul dan inklusif di masa depan.">{{ old('visi', $lembaga->visi) }}</textarea>
          </div>
        </div>
      </div>

      <!-- Right Column: Contacts & Media -->
      <div class="space-y-8">
        <!-- Section: Branding (Logo) -->
        <div
          class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 p-8 text-center">
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-6">Logo Institusi Resmi</p>
          <div class="relative inline-block group mb-6">
            <div
              class="size-32 rounded-3xl bg-slate-100 dark:bg-slate-800 border-2 border-slate-50 dark:border-slate-700 shadow-xl overflow-hidden flex items-center justify-center p-2 transition-transform group-hover:scale-105 duration-500">
              @if($lembaga->logo)
                <img src="{{ Storage::url($lembaga->logo) }}" id="logo-preview"
                  class="max-w-full max-h-full object-contain">
              @else
                <span class="material-symbols-outlined text-4xl text-slate-300 italic">school</span>
              @endif
            </div>
            <label
              class="absolute -bottom-2 -right-2 bg-primary text-white size-10 rounded-xl shadow-xl flex items-center justify-center cursor-pointer hover:bg-primary-hover transition-all active:scale-95 group/upload">
              <span
                class="material-symbols-outlined text-[18px] group-hover/upload:rotate-12 transition-transform">photo_camera</span>
              <input type="file" name="logo" class="hidden" onchange="previewImage(this, 'logo-preview')">
            </label>
          </div>
          <p class="text-[9px] font-bold text-slate-400 leading-tight uppercase tracking-wider">Rekomendasi format
            PNG/JPG<br>dengan latar belakang transparan.</p>
        </div>

        <!-- Section: Kontak & Lokasi -->
        <div
          class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-6">
          <div class="flex items-center gap-3 border-b border-slate-50 dark:border-slate-800 pb-4 mb-2">
            <span class="material-symbols-outlined text-emerald-500">contact_page</span>
            <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">Kontak & Lokasi</h2>
          </div>

          <div class="space-y-4">
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Nomor Telepon</label>
              <input type="text" name="telepon" value="{{ old('telepon', $lembaga->telepon) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                placeholder="02xxx-xxx-xxx">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Email Resmi</label>
              <input type="email" name="email" value="{{ old('email', $lembaga->email) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                placeholder="admin@domain.sch.id">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Website</label>
              <input type="url" name="website" value="{{ old('website', $lembaga->website) }}"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                placeholder="https://pkbm.sch.id">
            </div>
            <div class="space-y-2">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[2px] ml-1">Alamat Lengkap</label>
              <textarea name="alamat" rows="4"
                class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all shadow-inner"
                required
                placeholder="Jl. Raya Garut - Tasikmalaya No. 123, Garut">{{ old('alamat', $lembaga->alamat) }}</textarea>
              <p class="text-[9px] font-bold text-slate-400 px-2 italic uppercase tracking-wider">Alamat ini akan dicetak
                langsung pada sertifikat izin.</p>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    function previewImage(input, previewId) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          const img = document.getElementById(previewId);
          if (img) {
            img.src = e.target.result;
            img.classList.remove('hidden');
          } else {
            // If no img tag (first time), create it
            const container = input.closest('.relative').querySelector('.size-32');
            container.innerHTML = `<img src="${e.target.result}" id="${previewId}" class="max-w-full max-h-full object-contain">`;
          }
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endpush