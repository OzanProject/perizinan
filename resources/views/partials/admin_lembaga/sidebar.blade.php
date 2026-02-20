<aside
  class="w-64 bg-surface-light dark:bg-surface-dark border-r border-slate-200 dark:border-slate-700 hidden md:flex flex-col fixed h-full z-30 transition-colors duration-200"
  id="admin-sidebar">
  <!-- Brand Logo -->
  <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-slate-700">
    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center overflow-hidden mr-3 shadow-inner">
      @if(Auth::user()->lembaga && Auth::user()->lembaga->logo)
        <img src="{{ Storage::url(Auth::user()->lembaga->logo) }}" class="w-full h-full object-contain">
      @else
        <span class="material-symbols-outlined text-primary text-[24px]">school</span>
      @endif
    </div>
    <div class="flex-1 min-w-0">
      <h1 class="font-bold text-sm text-text-primary dark:text-white leading-tight truncate">
        {{ Auth::user()->lembaga->nama_lembaga ?? (Auth::user()->dinas->app_name ?? 'Admin PKBM') }}
      </h1>
      <p class="text-[10px] text-text-secondary dark:text-slate-400 font-bold uppercase tracking-widest italic">Panel Lembaga</p>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
    <!-- Main Menu -->
    <div>
      <p class="px-3 text-[10px] font-black text-text-secondary dark:text-slate-500 uppercase tracking-[2px] mb-3">Menu
        Utama</p>
      <div class="space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin_lembaga.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ route('admin_lembaga.dashboard') }}">
          <span
            class="material-symbols-outlined {{ request()->routeIs('admin_lembaga.dashboard') ? 'fill-1' : '' }}">dashboard</span>
          <span class="text-sm">Dashboard</span>
        </a>
      </div>
    </div>

    <!-- Licensing -->
    <div>
      <p class="px-3 text-[10px] font-black text-text-secondary dark:text-slate-500 uppercase tracking-[2px] mb-3">
        Manajemen Izin</p>
      <div class="space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin_lembaga.perizinan.create') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ route('admin_lembaga.perizinan.create') }}">
          <span
            class="material-symbols-outlined {{ request()->routeIs('admin_lembaga.perizinan.create') ? 'fill-1' : '' }}">add_circle</span>
          <span class="text-sm">Ajukan Izin Baru</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin_lembaga.perizinan.index') || request()->routeIs('admin_lembaga.perizinan.show') || request()->routeIs('admin_lembaga.perizinan.edit') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ route('admin_lembaga.perizinan.index') }}">
          <span
            class="material-symbols-outlined {{ request()->routeIs('admin_lembaga.perizinan.index') ? 'fill-1' : '' }}">history</span>
          <span class="text-sm">Status & Riwayat</span>
        </a>
      </div>
    </div>

    <!-- Organization -->
    <div>
      <p class="px-3 text-[10px] font-black text-text-secondary dark:text-slate-500 uppercase tracking-[2px] mb-3">
        Informasi</p>
      <div class="space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin_lembaga.profile.*') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ route('admin_lembaga.profile.index') }}">
          <span
            class="material-symbols-outlined {{ request()->routeIs('admin_lembaga.profile.index') ? 'fill-1' : '' }}">domain</span>
          <span class="text-sm">Profil Lembaga</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin_lembaga.guide.*') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ route('admin_lembaga.guide.index') }}">
          <span
            class="material-symbols-outlined {{ request()->routeIs('admin_lembaga.guide.*') ? 'fill-1' : '' }}">help_center</span>
          <span class="text-sm">Panduan Sistem</span>
        </a>
      </div>
    </div>

    <!-- Account -->
    <div>
      <p class="px-3 text-[10px] font-black text-text-secondary dark:text-slate-500 uppercase tracking-[2px] mb-3">
        Pengaturan</p>
      <div class="space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->is('profile*') ? 'bg-primary text-white shadow-lg shadow-primary/25 font-bold text-white' : 'text-text-secondary dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-text-primary dark:hover:text-white' }} group"
          href="{{ url('/profile') }}">
          <span class="material-symbols-outlined {{ request()->is('profile*') ? 'fill-1' : '' }}">manage_accounts</span>
          <span class="text-sm">Akun Saya</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
            <span class="material-symbols-outlined">logout</span>
            <span class="text-sm font-bold">Keluar</span>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- User Mini Profile -->
  <div class="p-4 border-t border-slate-100 dark:border-slate-700">
    <div class="flex items-center gap-3">
      <div class="relative">
        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-600 bg-cover bg-center"
          style="background-image: url('{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}');">
        </div>
        <div
          class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-surface-dark rounded-full">
        </div>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-text-primary dark:text-white truncate">{{ Auth::user()->name }}</p>
        <p class="text-xs text-text-secondary dark:text-slate-400 truncate">
          {{ Auth::user()->lembaga->nama_lembaga ?? 'Kepala Lembaga' }}
        </p>
      </div>
    </div>
  </div>
</aside>

<!-- Backdrop Overlay for Mobile -->
<div id="sidebar-backdrop"
  class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-20 hidden opacity-0 transition-opacity duration-300 md:hidden"
  onclick="toggleSidebar()"></div>