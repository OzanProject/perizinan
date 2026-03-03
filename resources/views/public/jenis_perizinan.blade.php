@extends('layouts.public')

@section('title', 'Jenis Perizinan Tersedia')

@section('content')
  <!-- Header Section -->
  <section class="px-6 md:px-20 py-20 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto text-center">
      <h2 class="text-primary font-bold text-sm uppercase tracking-widest mb-4">
        {{ $setting->license_title ?? 'JENIS IZIN TERSEDIA' }}</h2>
      <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6">
        {{ $setting->license_subtitle ?? 'Pilih Jenis Perizinan Anda' }}
      </h1>
      <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">
        {{ $setting->license_description ?? 'Kami memfasilitasi berbagai tingkatan sertifikasi institusional tergantung pada tahap operasional lembaga Anda.' }}
      </p>
    </div>
  </section>

  <!-- License Types Grid -->
  <section class="px-6 md:px-20 py-20 bg-white dark:bg-slate-900">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($setting->license_types ?? [] as $license)
          <div
            class="group relative bg-slate-50 dark:bg-slate-800 rounded-3xl p-10 border border-transparent hover:border-primary transition-all duration-300">
            <div class="flex justify-between items-start mb-8">
              <div
                class="size-20 rounded-2xl bg-primary text-white flex items-center justify-center shadow-xl shadow-primary/30">
                <span class="material-symbols-outlined text-4xl">{{ $license['icon'] ?? 'add_card' }}</span>
              </div>
              @if(isset($license['badge']))
                <span
                  class="bg-primary/10 text-primary text-xs font-black uppercase px-4 py-2 rounded-full border border-primary/20">{{ $license['badge'] }}</span>
              @endif
            </div>
            <h4 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">{{ $license['title'] }}</h4>
            <p class="text-slate-600 dark:text-slate-400 mb-8 leading-relaxed text-lg">
              {{ $license['description'] }}
            </p>

            <div class="mb-10">
              <h5
                class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="size-2 bg-primary rounded-full"></span> Persyaratan Utama
              </h5>
              <ul class="space-y-4 text-slate-700 dark:text-slate-300">
                @foreach($license['syarat'] ?? [] as $syarat)
                  <li class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-xl mt-0.5">check_circle</span>
                    <span class="font-medium">{{ $syarat }}</span>
                  </li>
                @endforeach
              </ul>
            </div>

            <a href="{{ route('login') }}"
              class="block w-full text-center py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/25 hover:bg-primary/90 hover:-translate-y-1 transition-all duration-300">
              Daftar & Ajukan Sekarang
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="max-w-7xl mx-auto px-6 mb-24">
    <div
      class="bg-slate-900 dark:bg-slate-800 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
      <div class="absolute -top-40 -left-40 size-80 bg-primary/20 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -right-40 size-80 bg-primary/10 rounded-full blur-3xl"></div>

      <h2 class="text-3xl md:text-5xl font-black text-white mb-6 relative z-10">Punya Pertanyaan Mengenai Izin?</h2>
      <p class="text-slate-400 text-lg md:text-xl mb-12 max-w-2xl mx-auto relative z-10">
        Pusat bantuan kami siap membantu Anda memahami regulasi dan persyaratan terbaru untuk institusi Anda.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
        <a href="{{ route('landing.faq') }}"
          class="px-10 py-5 bg-white text-slate-900 font-black rounded-2xl hover:bg-slate-100 transition-all text-lg">
          Lihat FAQ
        </a>
        <a href="mailto:{{ $setting->contact_email ?? 'support@perizinan.go.id' }}"
          class="px-10 py-5 border-2 border-white/20 text-white font-black rounded-2xl hover:bg-white/10 transition-all text-lg">
          Hubungi Kami
        </a>
      </div>
    </div>
  </section>
@endsection