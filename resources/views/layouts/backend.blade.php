<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title') - {{ Auth::user()->dinas->app_name ?? 'Sistem Perizinan Dinas' }}</title>

  @if(Auth::user()->dinas && Auth::user()->dinas->logo)
    <link rel="icon" type="image/x-icon" href="{{ Storage::url(Auth::user()->dinas->logo) }}">
  @endif

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <!-- Tailwind Configuration -->
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0b50da",
            "secondary": "#6c757d",
            "success": "#28a745",
            "info": "#17a2b8",
            "warning": "#ffc107",
            "danger": "#dc3545",
            "sidebar-dark": "#343a40",
            "sidebar-light": "#f4f6f9",
            "body-bg": "#f4f6f9",
          },
          fontFamily: {
            "display": ["Public Sans", "sans-serif"],
            "sans": ["Public Sans", "sans-serif"],
          },
          boxShadow: {
            'card': '0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)',
            'header': '0 .125rem .25rem rgba(0,0,0,.075)',
          }
        },
      },
    }
  </script>
  <style>
    body {
      font-family: 'Public Sans', sans-serif;
      background-color: #f4f6f9;
    }

    .sidebar-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
      background: #343a40;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: #6c757d;
      border-radius: 3px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
      background: #adb5bd;
    }

    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined';
      font-weight: normal;
      font-style: normal;
      font-size: 24px;
      line-height: 1;
      letter-spacing: normal;
      text-transform: none;
      display: inline-block;
      white-space: nowrap;
      word-wrap: normal;
      direction: ltr;
      -webkit-font-feature-settings: 'liga';
      -webkit-font-smoothing: antialiased;
    }
  </style>
</head>

<body
  class="bg-body-bg text-slate-800 antialiased min-h-screen flex flex-col md:flex-row overflow-x-hidden md:overflow-hidden">

  <!-- Mobile Overlay -->
  <div id="sidebar-overlay"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-20 hidden transition-opacity duration-300 opacity-0 md:hidden"
    onclick="toggleSidebar()"></div>

  @include('partials.backend.sidebar')

  <!-- Main Content Wrapper -->
  <div class="flex-1 flex flex-col h-screen overflow-hidden relative">

    @include('partials.backend.navbar')

    <!-- Main Content Scroll Area -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-body-bg">
      <div class="max-w-7xl mx-auto min-h-full flex flex-col">
        <div class="flex-grow">
          @yield('content')
        </div>

        @include('partials.backend.footer')
      </div>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('aside');
      const overlay = document.getElementById('sidebar-overlay');

      const isHidden = sidebar.classList.contains('-translate-x-full');

      if (isHidden) {
        // Open
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => {
          overlay.classList.remove('opacity-0');
        }, 10);
        document.body.classList.add('overflow-hidden');
      } else {
        // Close
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => {
          overlay.classList.add('hidden');
        }, 300);
        document.body.classList.remove('overflow-hidden');
      }
    }
  </script>

  @include('partials.sweetalert')
  @stack('scripts')
</body>

</html>