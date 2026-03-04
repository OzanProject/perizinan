@extends('layouts.public')

@section('title', 'Jenis Perizinan Tersedia')

@section('content')
  {{-- Header Section --}}
  <section class="px-6 md:px-20 py-20 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto text-center">
      <h2 class="text-primary font-bold text-sm uppercase tracking-widest mb-4">
        {{ $setting->license_title ?? 'JENIS IZIN TERSEDIA' }}
      </h2>
      <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6">
        {{ $setting->license_subtitle ?? 'Pilih Jenis Perizinan Anda' }}
      </h1>
      <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">
        {{ $setting->license_description ?? 'Kami memfasilitasi berbagai tingkatan sertifikasi institusional tergantung pada tahap operasional lembaga Anda.' }}
      </p>
    </div>
  </section>

  {{-- Dynamic License Types from Database --}}
  <section class="px-6 md:px-20 py-20 bg-white dark:bg-slate-900">
    <div class="max-w-7xl mx-auto">

      @if($jenisPerizinanList->isEmpty())
        <div class="text-center py-20 text-slate-400">
          <span class="material-symbols-outlined text-6xl mb-4 block">folder_off</span>
          <p class="text-xl font-semibold">Belum ada jenis perizinan yang tersedia.</p>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          @foreach($jenisPerizinanList as $jenis)
            @php
              $iconMap = ['PKBM' => 'school', 'PAUD' => 'child_care', 'TK' => 'child_care', 'LKP' => 'workspace_premium'];
              $icon = 'add_card';
              foreach ($iconMap as $keyword => $ic) {
                if (str_contains(strtoupper($jenis->nama), $keyword)) {
                  $icon = $ic;
                  break;
                }
              }
              $masaBerlaku = $jenis->masa_berlaku_nilai . ' ' . $jenis->masa_berlaku_unit;
              $wajibCount = $jenis->syarats->where('is_required', true)->count();
              $opsiCount = $jenis->syarats->where('is_required', false)->count();
            @endphp
            <div
              class="group relative bg-slate-50 dark:bg-slate-800 rounded-3xl p-10 border border-transparent hover:border-primary transition-all duration-300 flex flex-col">

              {{-- Ikon & Badge masa berlaku --}}
              <div class="flex justify-between items-start mb-6">
                <div
                  class="size-20 rounded-2xl bg-primary text-white flex items-center justify-center shadow-xl shadow-primary/30">
                  <span class="material-symbols-outlined text-4xl">{{ $icon }}</span>
                </div>
                <span
                  class="bg-primary/10 text-primary text-xs font-black uppercase px-4 py-2 rounded-full border border-primary/20 self-start">
                  {{ $masaBerlaku }}
                </span>
              </div>

              {{-- Nama & Deskripsi --}}
              <h4 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ $jenis->nama }}</h4>
              @if($jenis->deskripsi)
                <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">{{ $jenis->deskripsi }}</p>
              @endif

              {{-- Persyaratan dari database --}}
              @if($jenis->syarats->isNotEmpty())
                <div class="mb-6 flex-1">
                  <h5
                    class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="size-2 bg-primary rounded-full"></span>
                    Persyaratan ({{ $jenis->syarats->count() }} dokumen)
                  </h5>
                  <ul class="space-y-3 text-slate-700 dark:text-slate-300">
                    @foreach($jenis->syarats as $syarat)
                      <li class="flex items-start gap-3">
                        <span
                          class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0 {{ $syarat->is_required ? 'text-primary' : 'text-slate-400' }}">
                          {{ $syarat->is_required ? 'check_circle' : 'radio_button_unchecked' }}
                        </span>
                        <div>
                          <span class="font-semibold text-sm">{{ $syarat->nama_dokumen }}</span>
                          @if($syarat->deskripsi)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $syarat->deskripsi }}</p>
                          @endif
                          @if($syarat->tipe_file)
                            <span
                              class="inline-block mt-1 text-[10px] font-bold uppercase bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded">
                              {{ $syarat->tipe_file }}
                            </span>
                          @endif
                        </div>
                        @if(!$syarat->is_required)
                          <span
                            class="ml-auto text-[10px] font-bold text-slate-400 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full flex-shrink-0 self-start">Opsional</span>
                        @endif
                      </li>
                    @endforeach
                  </ul>

                  @if($wajibCount > 0)
                    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                      <span class="material-symbols-outlined text-sm text-primary">info</span>
                      <span>{{ $wajibCount }} wajib{{ $opsiCount > 0 ? ", $opsiCount opsional" : '' }}</span>
                    </p>
                  @endif
                </div>
              @else
                <div class="mb-6 flex-1 text-slate-400 text-sm italic flex items-center gap-2">
                  <span class="material-symbols-outlined text-lg">info</span>
                  Persyaratan belum dikonfigurasi.
                </div>
              @endif

              {{-- CTA Button --}}
              <a href="{{ route('login') }}"
                class="block w-full text-center py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/25 hover:bg-primary/90 hover:-translate-y-1 transition-all duration-300 mt-auto">
                Daftar &amp; Ajukan Sekarang
              </a>
            </div>
          @endforeach
        </div>
      @endif

    </div>
  </section>

  {{-- Call to Action --}}
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