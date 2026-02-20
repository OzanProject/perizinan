<!-- Top Header -->
<header
  class="h-16 bg-surface-light dark:bg-surface-dark border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-6 sticky top-0 z-20 transition-colors duration-200">
  <div class="flex items-center gap-4">
    <button class="md:hidden text-text-secondary hover:text-primary transition-colors" onclick="toggleSidebar()">
      <span class="material-symbols-outlined">menu</span>
    </button>
    <h2 class="text-lg font-semibold text-text-primary dark:text-white hidden sm:block">
      @if(request()->routeIs('admin_lembaga.dashboard'))
        Status Pengajuan Izin Operasional
      @else
        @yield('title', 'Panel Lembaga')
      @endif
    </h2>
  </div>

  <div class="flex items-center gap-4">
    <!-- Search Bar -->
    <div class="hidden md:flex relative">
      <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary">
        <span class="material-symbols-outlined text-[20px]">search</span>
      </span>
      <input
        class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm text-text-primary dark:text-white w-64 focus:ring-2 focus:ring-primary/50 transition-all shadow-inner"
        placeholder="Cari dokumen..." type="text">
    </div>

    <!-- Notifications & User Dropdown -->
    <div class="flex items-center gap-2 sm:gap-4">
      <button
        class="relative p-2 text-text-secondary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors group">
        <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">notifications</span>
        <span
          class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-surface-dark"></span>
      </button>

      <div class="h-8 w-px bg-slate-200 dark:border-slate-700 mx-1 hidden sm:block"></div>

      <!-- Premium User Dropdown -->
      <div class="relative group/dropdown">
        <button
          class="flex items-center gap-3 p-1.5 pr-3 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-2xl transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
          <div class="size-8 rounded-xl bg-slate-200 dark:bg-slate-700 bg-cover bg-center overflow-hidden shadow-sm"
            style="background-image: url('{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}');">
          </div>
          <div class="hidden md:flex flex-col items-start leading-tight">
            <span
              class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">{{ explode(' ', Auth::user()->name)[0] }}</span>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Personal Profile</span>
          </div>
          <span
            class="material-symbols-outlined text-[18px] text-slate-400 group-hover/dropdown:rotate-180 transition-transform">expand_more</span>
        </button>

        <!-- Dropdown Menu -->
        <div
          class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 py-3 opacity-0 invisible translate-y-2 group-hover/dropdown:opacity-100 group-hover/dropdown:visible group-hover/dropdown:translate-y-0 transition-all duration-300 z-50">
          <div class="px-5 py-3 border-b border-slate-50 dark:border-slate-800 mb-2">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Signed in as</p>
            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
          </div>

          <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-white transition-all">
            <span class="material-symbols-outlined text-[20px]">person_outline</span>
            Akun Saya
          </a>

          <a href="{{ route('admin_lembaga.profile.index') }}"
            class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-white transition-all">
            <span class="material-symbols-outlined text-[20px]">domain</span>
            Profil Lembaga
          </a>

          <div class="my-2 border-t border-slate-50 dark:border-slate-800"></div>

          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
              class="w-full flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
              <span class="material-symbols-outlined text-[20px]">logout</span>
              Keluar
            </button>
          </form>
        </div>
      </div>
    </div>
</header>