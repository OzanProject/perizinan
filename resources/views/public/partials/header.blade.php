<header id="main-header"
  class="flex items-center justify-between px-6 md:px-20 py-5 bg-transparent fixed top-0 w-full z-[100] transition-all duration-500">
  <div class="flex items-center gap-10 min-w-0">
    <a href="{{ route('landing') }}" class="flex items-center gap-3 text-primary group min-w-0">
      <div
        class="size-10 min-w-[40px] flex items-center justify-center bg-white dark:bg-slate-800 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300 border border-slate-100 dark:border-slate-700">
        @if(isset($dinas->logo))
          <img src="{{ asset('storage/' . $dinas->logo) }}" alt="Logo" class="max-h-6">
        @else
          <span class="material-symbols-outlined text-2xl">account_balance</span>
        @endif
      </div>
      <div class="flex flex-col min-w-0">
        <h2
          class="text-slate-900 dark:text-slate-100 text-base md:text-lg font-black leading-tight tracking-tighter truncate max-w-[200px] md:max-w-md">
          {{ $dinas->app_name ?? 'PKBM Licensing' }}
        </h2>
        <span
          class="text-[9px] md:text-[10px] uppercase tracking-widest font-black text-slate-500/80 group-hover:text-primary transition-colors truncate max-w-[180px] md:max-w-sm">
          {{ $dinas->footer_text ?? $dinas->nama ?? 'Official Portal' }}
        </span>
      </div>
    </a>

    <!-- Desktop Navigation -->
    <nav class="hidden xl:flex items-center gap-8 whitespace-nowrap">
      <a class="text-slate-600 dark:text-slate-400 text-sm font-bold hover:text-primary transition-all relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-primary hover:after:w-full after:transition-all {{ request()->routeIs('landing') ? 'text-primary after:w-full' : '' }}"
        href="{{ route('landing') }}">Beranda</a>
      <a class="text-slate-600 dark:text-slate-400 text-sm font-bold hover:text-primary transition-all relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-primary hover:after:w-full after:transition-all {{ request()->routeIs('perizinan.jenis') ? 'text-primary after:w-full' : '' }}"
        href="{{ route('perizinan.jenis') }}">Jenis Izin</a>
      <a class="text-slate-600 dark:text-slate-400 text-sm font-bold hover:text-primary transition-all relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-primary hover:after:w-full after:transition-all {{ request()->routeIs('landing.faq') ? 'text-primary after:w-full' : '' }}"
        href="{{ route('landing.faq') }}">FAQ</a>
      <a class="text-slate-600 dark:text-slate-400 text-sm font-bold hover:text-primary transition-all relative after:absolute after:bottom-[-4px] after:left-0 after:w-0 after:h-[2px] after:bg-primary hover:after:w-full after:transition-all {{ request()->routeIs('landing.track') ? 'text-primary after:w-full' : '' }}"
        href="{{ route('landing.track') }}">Lacak Pengajuan</a>
    </nav>
  </div>
  <div class="flex flex-1 justify-end gap-4 items-center">
    @if($setting->show_login_button)
      <a href="{{ route('login') }}"
        class="hidden sm:flex items-center justify-center text-slate-600 dark:text-slate-400 text-sm font-bold hover:text-primary transition-all">
        Masuk
      </a>
      <a href="{{ route('register') }}"
        class="hidden sm:flex min-w-[120px] cursor-pointer items-center justify-center rounded-2xl h-11 px-6 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 text-sm font-black shadow-xl hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white transition-all transform hover:-translate-y-0.5 active:translate-y-0">
        Daftar Akun
      </a>
    @endif
    <button id="mobile-menu-trigger"
      class="xl:hidden size-11 flex items-center justify-center bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 text-slate-700 dark:text-slate-300">
      <span class="material-symbols-outlined">menu</span>
    </button>
  </div>
  </div>
</header>

<!-- Mobile Menu Slide-over (Outside header to escape stacking context) -->
<div id="mobile-menu-overlay"
  class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9998] opacity-0 pointer-events-none transition-all duration-300">
</div>
<div id="mobile-menu-content"
  class="fixed top-0 right-0 w-[85%] max-w-sm h-full bg-white dark:bg-slate-900 z-[9999] translate-x-full transition-transform duration-500 ease-out shadow-2xl p-8 flex flex-col">
  <div class="flex justify-between items-center mb-12">
    <div class="flex flex-col">
      <h2 class="text-slate-900 dark:text-slate-100 text-xl font-black leading-tight tracking-tighter">Menu Utama</h2>
      <p class="text-[10px] uppercase tracking-widest font-black text-slate-400">Navigasi Portal</p>
    </div>
    <button id="mobile-menu-close"
      class="size-10 flex items-center justify-center bg-slate-50 dark:bg-slate-800 rounded-xl">
      <span class="material-symbols-outlined text-slate-500">close</span>
    </button>
  </div>
  <nav class="flex flex-col gap-6 flex-1 overflow-y-auto custom-scrollbar pr-2">
    <a class="flex items-center justify-between text-lg font-black {{ request()->routeIs('landing') ? 'text-primary' : 'text-slate-900 dark:text-white' }}"
      href="{{ route('landing') }}">
      Beranda <span class="material-symbols-outlined text-slate-400">chevron_right</span>
    </a>
    <a class="flex items-center justify-between text-lg font-black {{ request()->routeIs('perizinan.jenis') ? 'text-primary' : 'text-slate-900 dark:text-white' }}"
      href="{{ route('perizinan.jenis') }}">
      Jenis Izin <span class="material-symbols-outlined text-slate-400">chevron_right</span>
    </a>
    <a class="flex items-center justify-between text-lg font-black {{ request()->routeIs('landing.faq') ? 'text-primary' : 'text-slate-900 dark:text-white' }}"
      href="{{ route('landing.faq') }}">
      FAQ <span class="material-symbols-outlined text-slate-400">chevron_right</span>
    </a>
    <a class="flex items-center justify-between text-lg font-black {{ request()->routeIs('landing.track') ? 'text-primary' : 'text-slate-900 dark:text-white' }}"
      href="{{ route('landing.track') }}">
      Lacak Status <span class="material-symbols-outlined text-slate-400">chevron_right</span>
    </a>
  </nav>
  <div class="mt-auto pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-4">
    @if($setting->show_login_button)
      <a href="{{ route('login') }}"
        class="w-full flex items-center justify-center h-14 border-2 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-black rounded-2xl">
        Masuk
      </a>
      <a href="{{ route('register') }}"
        class="w-full flex items-center justify-center h-14 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20">
        Daftar Akun
      </a>
    @endif
    <p class="text-center text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-2">© {{ date('Y') }}
      {{ $dinas->nama }}
    </p>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const header = document.getElementById('main-header');
      const trigger = document.getElementById('mobile-menu-trigger');
      const close = document.getElementById('mobile-menu-close');
      const overlay = document.getElementById('mobile-menu-overlay');
      const content = document.getElementById('mobile-menu-content');

      // Scroll Effect
      if (header) {
        window.addEventListener('scroll', () => {
          if (window.scrollY > 20) {
            header.classList.add('bg-white/80', 'dark:bg-slate-900/80', 'backdrop-blur-xl', 'py-3', 'shadow-lg', 'border-b', 'border-slate-100', 'dark:border-slate-800');
            header.classList.remove('bg-transparent', 'py-5');
          } else {
            header.classList.remove('bg-white/80', 'dark:bg-slate-900/80', 'backdrop-blur-xl', 'py-3', 'shadow-lg', 'border-b', 'border-slate-100', 'dark:border-slate-800');
            header.classList.add('bg-transparent', 'py-5');
          }
        });
      }

      // Mobile Menu Logic
      const openMenu = () => {
        if (overlay && content) {
          overlay.classList.remove('opacity-0', 'pointer-events-none');
          content.classList.remove('translate-x-full');
          document.body.classList.add('overflow-hidden');
        }
      };

      const closeMenu = () => {
        if (overlay && content) {
          overlay.classList.add('opacity-0', 'pointer-events-none');
          content.classList.add('translate-x-full');
          document.body.classList.remove('overflow-hidden');
        }
      };

      if (trigger) trigger.addEventListener('click', openMenu);
      if (close) close.addEventListener('click', closeMenu);
      if (overlay) overlay.addEventListener('click', closeMenu);
    });
  </script>
@endpush