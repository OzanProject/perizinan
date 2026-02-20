<!-- Navbar -->
<header class="bg-white shadow-header h-14 flex items-center justify-between px-4 sm:px-6 z-20 shrink-0">
  <div class="flex items-center gap-4">
    <button class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden" onclick="toggleSidebar()">
      <span class="material-symbols-outlined">menu</span>
    </button>
    <div class="hidden sm:flex text-sm text-gray-500 items-center gap-2">
      <a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Home</a>
      <span class="text-gray-300">/</span>
      <span class="text-gray-800 font-medium">@yield('breadcrumb', 'Dashboard')</span>
    </div>
  </div>
  <div class="flex items-center gap-3 sm:gap-5">
    <!-- User Info -->
    <div class="hidden md:flex flex-col items-end">
      <span class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name }}</span>
      <span
        class="text-[10px] text-gray-400 uppercase tracking-wider text-right">{{ str_replace('_', ' ', Auth::user()->getRoleNames()->first()) }}</span>
    </div>
  </div>
</header>