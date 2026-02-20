<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title') - {{ Auth::user()->lembaga->nama_lembaga ?? 'Dashboard Lembaga' }}</title>

  @if(Auth::user()->dinas && Auth::user()->dinas->logo)
    <link rel="icon" type="image/x-icon" href="{{ Storage::url(Auth::user()->dinas->logo) }}">
  @endif

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0b50da",
            "primary-hover": "#0941b3",
            "background-light": "#f5f6f8",
            "background-dark": "#101622",
            "surface-light": "#ffffff",
            "surface-dark": "#1e293b",
            "text-primary": "#111318",
            "text-secondary": "#606e8a",
          },
          fontFamily: {
            "display": ["Public Sans", "sans-serif"],
            "sans": ["Public Sans", "sans-serif"],
          },
          borderRadius: {
            "DEFAULT": "0.375rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "2xl": "1rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>

  <style>
    body {
      font-family: 'Public Sans', sans-serif;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    .sidebar-active {
      background-color: rgba(11, 80, 218, 0.1);
      color: #0b50da;
      font-weight: 600;
    }
  </style>
  @stack('styles')
</head>

<body
  class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased min-h-screen flex flex-col overflow-x-hidden">

  @include('partials.admin_lembaga.sidebar')

  <!-- Main Content Wrapper -->
  <main class="flex-1 flex flex-col md:ml-64 transition-all duration-300">
    @include('partials.admin_lembaga.header')

    <div class="flex flex-1 w-full max-w-[1600px] mx-auto p-4 md:p-6 lg:p-8 gap-6 lg:gap-8 flex-col">
      @yield('content')
    </div>

    <!-- Footer -->
    <footer
      class="mt-auto border-t border-slate-200 dark:border-slate-800 p-8 text-center text-xs text-slate-400 bg-white dark:bg-[#1e2330]">
      <div class="flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto gap-4">
        <p>© {{ date('Y') }} <span class="font-black text-primary uppercase tracking-widest">Dinas Pendidikan</span>.
          Seluruh Hak Cipta Dilindungi.</p>
        <p class="opacity-70 font-mono tracking-tighter">Premium Portal v{{ config('app.version', '3.8.2') }}</p>
      </div>
    </footer>
  </main>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('admin-sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      const isHidden = !sidebar.classList.contains('flex');

      if (isHidden) {
        // Open
        sidebar.classList.remove('hidden');
        sidebar.classList.add('flex', '-translate-x-full');
        backdrop.classList.remove('hidden');
        setTimeout(() => {
          sidebar.classList.remove('-translate-x-full');
          backdrop.classList.add('opacity-100');
        }, 10);
      } else {
        // Close
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.remove('opacity-100');
        setTimeout(() => {
          sidebar.classList.add('hidden');
          sidebar.classList.remove('flex', '-translate-x-full');
          backdrop.classList.add('hidden');
        }, 300);
      }
    }
  </script>

  @include('partials.sweetalert')
  @stack('scripts')
</body>

</html>