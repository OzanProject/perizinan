<footer class="bg-slate-900 text-slate-400 py-20 px-6 md:px-20 relative overflow-hidden">
  <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary/30 via-primary to-primary/30"></div>

  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-20">
      <!-- Brand Section -->
      <div class="lg:col-span-4 flex flex-col gap-8">
        <a href="{{ route('landing') }}" class="flex items-center gap-3 text-white group">
          <div
            class="size-12 rounded-2xl bg-white flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
            @if(isset($dinas->logo))
              <img src="{{ asset('storage/' . $dinas->logo) }}" alt="Logo" class="max-h-8">
            @else
              <span class="material-symbols-outlined text-3xl text-primary">account_balance</span>
            @endif
          </div>
          <div class="flex flex-col">
            <h2 class="text-2xl font-black leading-tight tracking-tighter">{{ $dinas->app_name ?? 'PKBM Licensing' }}
            </h2>
            <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-500">
              {{ $dinas->nama ?? 'Official Portal' }}
            </p>
          </div>
        </a>
        <p class="text-slate-400 leading-relaxed text-sm max-w-sm">
          {{ $setting->footer_description ?? 'Portal Informasi Perizinan PKBM Terpadu. Memastikan keunggulan pendidikan melalui layanan perizinan yang transparan dan mudah diakses.' }}
        </p>
        <div class="flex flex-wrap gap-3">
          @if($setting->contact_email)
            <a href="mailto:{{ $setting->contact_email }}" title="Email"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group">
              <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">mail</span>
            </a>
          @endif
          @if($setting->contact_phone)
            <a href="tel:{{ $setting->contact_phone }}" title="Phone"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 group">
              <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">phone</span>
            </a>
          @endif
          @if($setting->social_facebook)
            <a href="{{ $setting->social_facebook }}" target="_blank" title="Facebook"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all duration-300 group text-slate-400">
              <i class="fab fa-facebook-f text-xl group-hover:scale-110 transition-transform"></i>
            </a>
          @endif
          @if($setting->social_instagram)
            <a href="{{ $setting->social_instagram }}" target="_blank" title="Instagram"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] hover:text-white transition-all duration-300 group text-slate-400">
              <i class="fab fa-instagram text-xl group-hover:scale-110 transition-transform"></i>
            </a>
          @endif
          @if($setting->social_twitter)
            <a href="{{ $setting->social_twitter }}" target="_blank" title="Twitter / X"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-300 group text-slate-400">
              <i class="fab fa-x-twitter text-xl group-hover:scale-110 transition-transform"></i>
            </a>
          @endif
          @if($setting->social_youtube)
            <a href="{{ $setting->social_youtube }}" target="_blank" title="YouTube"
              class="size-11 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center hover:bg-[#FF0000] hover:text-white transition-all duration-300 group text-slate-400">
              <i class="fab fa-youtube text-xl group-hover:scale-110 transition-transform"></i>
            </a>
          @endif
        </div>
      </div>

      <!-- Links Sections -->
      <div class="lg:col-span-4 grid grid-cols-2 gap-8">
        <div>
          <h4 class="text-white font-black text-xs uppercase tracking-widest mb-8">Navigasi Utama</h4>
          <ul class="space-y-4 text-sm font-bold">
            <li><a class="hover:text-primary transition-colors flex items-center gap-2 group"
                href="{{ route('landing') }}"><span
                  class="size-1 bg-slate-700 group-hover:bg-primary transition-colors"></span> Beranda</a></li>
            <li><a class="hover:text-primary transition-colors flex items-center gap-2 group"
                href="{{ route('perizinan.jenis') }}"><span
                  class="size-1 bg-slate-700 group-hover:bg-primary transition-colors"></span> Jenis Izin</a></li>
            <li><a class="hover:text-primary transition-colors flex items-center gap-2 group"
                href="{{ route('landing.faq') }}"><span
                  class="size-1 bg-slate-700 group-hover:bg-primary transition-colors"></span> FAQ</a></li>
            <li><a class="hover:text-primary transition-colors flex items-center gap-2 group"
                href="{{ route('landing.track') }}"><span
                  class="size-1 bg-slate-700 group-hover:bg-primary transition-colors"></span> Lacak Status</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-black text-xs uppercase tracking-widest mb-8">Hubungi Kami</h4>
          <ul class="space-y-6 text-sm">
            <li class="flex items-start gap-4 group">
              <div
                class="size-8 rounded-lg bg-slate-800 flex items-center justify-center text-primary border border-slate-700 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-sm">location_on</span>
              </div>
              <span
                class="flex-1 font-medium group-hover:text-slate-300 transition-colors">{{ $setting->contact_address ?? 'Gedung Dinas Pendidikan' }}</span>
            </li>
            <li class="flex items-start gap-4 group">
              <div
                class="size-8 rounded-lg bg-slate-800 flex items-center justify-center text-primary border border-slate-700 group-hover:bg-primary group-hover:text-white transition-all">
                <span class="material-symbols-outlined text-sm">phone_in_talk</span>
              </div>
              <span
                class="flex-1 font-medium group-hover:text-slate-300 transition-colors">{{ $setting->contact_phone }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Map Section -->
      <div class="lg:col-span-4">
        <h4 class="text-white font-black text-xs uppercase tracking-widest mb-8">Lokasi Kami</h4>
        <div
          class="rounded-3xl overflow-hidden grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-500 border border-slate-800 h-48 lg:h-full min-h-[200px] shadow-2xl bg-slate-800">
          @if($setting->google_maps_embed)
            {!! $setting->google_maps_embed !!}
          @else
            <div class="w-full h-full flex flex-col items-center justify-center gap-4 bg-slate-800 p-8 text-center">
              <span class="material-symbols-outlined text-5xl text-slate-700">map</span>
              <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest leading-loose">Peta lokasi belum
                terintegrasi di admin dinas.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Bottom Section -->
    <div class="border-t border-slate-800/50 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4">
        <p class="text-[10px] uppercase font-black tracking-widest text-slate-500/80">
          © {{ date('Y') }} {{ $dinas->footer_text ?? $dinas->nama }}
        </p>
        <span class="hidden md:block text-slate-800">•</span>
        <p class="text-[10px] uppercase font-black tracking-widest text-slate-600">Official Portal</p>
      </div>
      <div class="flex gap-10">
        <a class="text-[10px] uppercase font-black tracking-[0.2em] hover:text-white transition-colors flex items-center gap-2 group"
          href="https://www.ozanproject.site/" target="_blank">
          <span class="text-slate-600 group-hover:text-primary transition-colors">Developed by</span>
          Ozan Project
        </a>
      </div>
    </div>
  </div>
</footer>

<style>
  footer iframe {
    width: 100% !important;
    height: 100% !important;
    border: 0 !important;
  }
</style>