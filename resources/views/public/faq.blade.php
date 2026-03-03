@extends('layouts.public')

@section('title', 'Pertanyaan yang Sering Diajukan')

@section('content')
  <!-- Header Section -->
  <section class="px-6 md:px-20 py-20 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto text-center">
      <h2 class="text-primary font-bold text-sm uppercase tracking-widest mb-4">
        {{ $setting->faq_title ?? 'PUSAT PENGETAHUAN' }}</h2>
      <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6">
        {{ $setting->faq_subtitle ?? 'Frequently Asked Questions' }}
      </h1>
      <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">
        Temukan jawaban cepat untuk pertanyaan umum mengenai proses perizinan, dokumen, dan penggunaan sistem.
      </p>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="max-w-4xl mx-auto px-6 py-24" id="faq">
    <div class="space-y-6">
      @forelse($setting->faq ?? [] as $index => $item)
        <div
          class="faq-item bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
          <div class="p-8 flex items-center justify-between cursor-pointer group"
            onclick="this.parentElement.classList.toggle('active')">
            <h5 class="text-xl font-bold text-slate-900 dark:text-white pr-4 group-hover:text-primary transition-colors">
              {{ $item['question'] }}
            </h5>
            <div
              class="size-10 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center group-hover:bg-primary/10 transition-colors">
              <span
                class="material-symbols-outlined text-slate-400 group-hover:text-primary expand-icon transition-transform">expand_more</span>
            </div>
          </div>
          <div
            class="faq-answer px-8 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-50 dark:border-slate-700">
            <div class="py-8 text-lg">
              {{ $item['answer'] }}
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-20 bg-slate-50 dark:bg-slate-800 rounded-[3rem]">
          <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">help_outline</span>
          <p class="text-slate-500 font-medium text-lg">Belum ada pertanyaan yang ditambahkan.</p>
        </div>
      @endforelse
    </div>
  </section>

  <!-- Help Section -->
  <section class="max-w-7xl mx-auto px-6 mb-24">
    <div class="bg-primary rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-primary/20">
      <div class="absolute -top-40 -left-40 size-80 bg-white/10 rounded-full blur-3xl"></div>

      <h2 class="text-3xl md:text-5xl font-black text-white mb-6 relative z-10">Masih Butuh Bantuan?</h2>
      <p class="text-white/80 text-lg md:text-xl mb-12 max-w-2xl mx-auto relative z-10">
        Jika Anda tidak menemukan jawaban yang Anda cari, jangan ragu untuk menghubungi tim teknis kami.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->contact_phone) }}"
          class="px-10 py-5 bg-white text-primary font-black rounded-2xl hover:bg-slate-100 transition-all text-lg flex items-center justify-center gap-3">
          <span class="material-symbols-outlined font-bold">chat</span> Chat via WhatsApp
        </a>
        <a href="mailto:{{ $setting->contact_email ?? 'support@perizinan.go.id' }}"
          class="px-10 py-5 border-2 border-white/30 text-white font-black rounded-2xl hover:bg-white/10 transition-all text-lg flex items-center justify-center gap-3">
          <span class="material-symbols-outlined font-bold">mail</span> Kirim Email
        </a>
      </div>
    </div>
  </section>
@endsection