<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title', $setting->hero_title ?? $dinas->app_name ?? 'Portal Perizinan') |
    {{ $dinas->footer_text ?? $dinas->nama ?? 'Official Portal' }}
  </title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon"
    href="{{ isset($dinas->logo) ? asset('storage/' . $dinas->logo) : asset('favicon.ico') }}">

  <!-- SEO Meta Tags -->
  <meta name="description"
    content="@yield('meta_description', 'Portal Resmi Perizinan PKBM. Layanan terpadu untuk pengajuan dan pemantauan izin operasional lembaga pendidikan.')">
  <meta name="keywords" content="@yield('meta_keywords', 'perizinan, pkbm, dinas pendidikan, izin operasional')">
  <meta name="author" content="{{ $dinas->nama ?? 'Dinas Pendidikan' }}">
  <meta property="og:title" content="@yield('title', 'PKBM Licensing Portal')">
  <meta property="og:description" content="@yield('meta_description', 'Portal Resmi Perizinan PKBM.')">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  @if(isset($dinas->logo))
    <meta property="og:image" content="{{ asset('storage/' . $dinas->logo) }}">
  @endif

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0f49bd",
            "background-light": "#f6f6f8",
            "background-dark": "#101622",
          },
          fontFamily: {
            "display": ["Public Sans"]
          },
          borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
        },
      },
    }
  </script>
  <style>
    body {
      font-family: "Public Sans", sans-serif;
      scroll-behavior: smooth;
    }

    .material-symbols-outlined {
      font-family: 'Material Symbols Outlined';
      font-weight: normal;
      font-style: normal;
      display: inline-block;
      line-height: 1;
      text-transform: none;
      letter-spacing: normal;
      word-wrap: normal;
      white-space: nowrap;
      direction: ltr;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
      -moz-osx-font-smoothing: grayscale;
      font-feature-settings: 'liga';
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    [x-cloak] {
      display: none !important;
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
    }

    .faq-item.active .faq-answer {
      max-height: 500px;
      transition: max-height 0.5s ease-in;
    }

    .faq-item.active .expand-icon {
      transform: rotate(180deg);
    }

    .glassmorphism {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }

    .dark .glassmorphism {
      background: rgba(15, 22, 34, 0.7);
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #e2e8f0;
      border-radius: 10px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #334155;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
      }

      to {
        transform: translateX(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes floatHorizontal {

      0%,
      100% {
        transform: translateX(0);
      }

      50% {
        transform: translateX(8px);
      }
    }

    .animate-float-horizontal {
      animation: floatHorizontal 4s ease-in-out infinite;
    }

    @keyframes flowLine {
      from {
        stroke-dashoffset: 16;
      }

      to {
        stroke-dashoffset: 0;
      }
    }

    .animate-path-flow {
      stroke-dasharray: 8 8;
      animation: flowLine 1s linear infinite;
    }

    .marquee-container {
      overflow: hidden;
      width: 100%;
      mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }

    .marquee-content {
      display: flex;
      gap: 2rem;
      width: max-content;
      animation: marqueeRight 30s linear infinite;
    }

    @keyframes marqueeRight {
      0% {
        transform: translateX(-50%);
      }

      100% {
        transform: translateX(0);
      }
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">

      @include('public.partials.header')

      <main class="flex flex-col flex-1 pt-24 md:pt-28 lg:pt-32 overflow-x-hidden">
        @yield('content')
      </main>

      @include('public.partials.footer')

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: 50
      });
    });
  </script>
  @stack('scripts')
</body>

</html>