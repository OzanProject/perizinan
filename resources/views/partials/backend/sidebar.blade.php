<!-- Sidebar -->
<aside
  class="w-[280px] md:w-64 bg-sidebar-dark text-white flex flex-col h-screen fixed md:relative z-30 transition-all duration-300 shadow-xl -translate-x-full md:translate-x-0">
  <!-- Brand Logo -->
  <div class="h-14 flex items-center px-4 border-b border-gray-600 bg-sidebar-dark shrink-0">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-lg bg-primary/20 backdrop-blur-sm flex items-center justify-center text-primary font-bold shadow-sm overflow-hidden p-1">
        @if(Auth::user()->dinas && Auth::user()->dinas->logo)
          <img src="{{ Storage::url(Auth::user()->dinas->logo) }}" class="h-full w-full object-contain">
        @else
          <span class="material-symbols-outlined text-[24px]">verified</span>
        @endif
      </div>
      <div class="flex flex-col">
        <span class="font-bold text-sm tracking-tight text-white leading-tight">{{ Auth::user()->dinas->app_name ?? 'Sistem Izin' }}</span>
        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ Auth::user()->dinas->nama ?? 'Dinas Pendidikan' }}</span>
      </div>
    </div>
  </div>
  <!-- Sidebar User -->
  <div class="p-4 border-b border-gray-600 text-ellipsis overflow-hidden">
    <div class="flex items-center gap-3">
      <div class="h-9 w-9 rounded-full bg-gray-500 overflow-hidden ring-2 ring-gray-400/50">
        @if(Auth::user()->photo)
          <img alt="User Profile" class="h-full w-full object-cover" src="{{ Storage::url(Auth::user()->photo) }}" />
        @else
          <img alt="User Profile" class="h-full w-full object-cover"
            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" />
        @endif
      </div>
      <div class="flex flex-col min-w-0">
        <span class="text-sm font-medium truncate">{{ Auth::user()->name }}</span>
        <span class="text-xs text-gray-400 flex items-center gap-1">
          <span class="w-2 h-2 rounded-full bg-success inline-block"></span> Online
        </span>
      </div>
    </div>
  </div>
  <!-- Sidebar Menu -->
  <nav class="flex-1 overflow-y-auto sidebar-scroll py-4">
    <ul class="flex flex-col gap-1 px-2">
      <li class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Main Navigation</li>
      <li>
        @role('super_admin')
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.dashboard') }}">
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            <p class="text-sm">Dashboard</p>
          </a>
        @else
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('admin_lembaga.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('admin_lembaga.dashboard') }}">
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            <p class="text-sm">Dashboard</p>
          </a>
        @endrole
      </li>
      @if(Auth::user()->hasRole('super_admin'))
        <li class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Manajemen Data</li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.lembaga.index') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.lembaga.index') }}">
            <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('super_admin.lembaga.index') ? 'text-white' : 'group-hover:text-white' }} transition-colors">domain</span>
            <p class="text-sm">Manajemen Lembaga</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.jenis_perizinan.index') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.jenis_perizinan.index') }}">
            <span class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">list_alt</span>
            <p class="text-sm">Jenis Perizinan</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.jenis_perizinan.template') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.jenis_perizinan.index') }}">
            <span class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">description</span>
            <p class="text-sm">Template Sertifikat</p>
          </a>
        </li>

        <li class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Layanan Perizinan</li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ (request()->routeIs('super_admin.perizinan.index') && request('status') !== 'disetujui') || request()->routeIs('super_admin.perizinan.show') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.perizinan.index') }}">
            <span
              class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">description</span>
            <p class="text-sm">Daftar Pengajuan</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request('status') === 'disetujui' || request()->routeIs('super_admin.perizinan.finalisasi') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.perizinan.index', ['status' => 'disetujui']) }}">
            <span
              class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">verified_user</span>
            <p class="text-sm">Manajemen Surat</p>
          </a>
        </li>

        <li class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Pengguna & Laporan</li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.users.*') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.users.index') }}">
            <span class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">group</span>
            <p class="text-sm">Manajemen Pengguna</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.laporan.*') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.laporan.index') }}">
            <span class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">analytics</span>
            <p class="text-sm">Laporan & Statistik</p>
          </a>
        </li>

        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('super_admin.settings.*') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('super_admin.settings.index') }}">
            <span class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">settings</span>
            <p class="text-sm">Konfigurasi Sistem</p>
          </a>
        </li>
      @endif

      @if(Auth::user()->hasRole('admin_lembaga'))
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('admin_lembaga.perizinan.*') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('admin_lembaga.perizinan.index') }}">
            <span
              class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">description</span>
            <p class="text-sm">Pengajuan Saya</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('admin_lembaga.profile.*') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('admin_lembaga.profile.index') }}">
            <span
              class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">domain</span>
            <p class="text-sm">Profil Lembaga</p>
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('profile.edit') ? 'bg-primary text-white shadow-md' : 'text-gray-300 hover:bg-[#494e53] hover:text-white' }} transition-all group"
            href="{{ route('profile.edit') }}">
            <span
              class="material-symbols-outlined text-[20px] group-hover:text-white transition-colors">manage_accounts</span>
            <p class="text-sm">Akun Saya</p>
          </a>
        </li>
      @endif

      <li class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">System</li>
      <li>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-md text-gray-300 hover:bg-[#494e53] hover:text-white transition-all group mt-auto cursor-pointer"
          onclick="document.getElementById('logout-form').submit()">
          <span class="material-symbols-outlined text-[20px] group-hover:text-danger transition-colors">logout</span>
          <p class="text-sm">Log Out</p>
        </a>
      </li>
    </ul>
  </nav>
</aside>