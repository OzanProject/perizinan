<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Verifikasi OTP - {{ isset($globalDinas) && $globalDinas ? $globalDinas->app_name : 'Sistem Perizinan Dinas' }}
  </title>
  @if(isset($globalDinas) && $globalDinas && $globalDinas->logo)
    <link rel="shortcut icon" href="{{ Storage::url($globalDinas->logo) }}" />
    <link rel="icon" type="image/png" href="{{ Storage::url($globalDinas->logo) }}">
  @endif
  <!-- Material Symbols -->
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
    rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&amp;display=swap"
    rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <!-- Theme Configuration -->
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0c48c0",
            "primary-hover": "#0a3a9b",
            "background-light": "#f5f6f8",
            "background-dark": "#101622",
          },
          fontFamily: {
            "display": ["Public Sans", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          }
        },
      },
    }
  </script>
  <style>
    body {
      font-family: 'Public Sans', sans-serif;
    }

    .otp-input {
      letter-spacing: 0.5em;
      text-align: center;
      font-weight: 700;
    }
  </style>
</head>

<body
  class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center font-display relative overflow-hidden">
  <!-- Blurred Background Image -->
  <div class="absolute inset-0 bg-cover bg-center z-0 filter blur-[4px] scale-105"
    style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD7UaVMsRGjmeHAHjWLfdIRFtbFV2ADV0B7lJAhznHqZAADO2_xOMyV4MnpT15HU7xYcvx_7sH3JZfhB5yWOsxEvMpdRAO9fjT_wW6hOuknNSAG2G9USWwSw-qB5Erd49kiUZlIk-LxJhoQ6No-nEwfO0nphPbwBVy-yYN0hP0f2Csqah0nm5dS0c2HmIYChPAFZp0LaOyGGNWLhaITJSUlxzxVdG3uNO8bSCGGnOvo_0FDLMJD0E9Ghivw4OObFN3_inWrGxmr-qQH');">
    <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
  </div>

  <!-- Container -->
  <div class="w-full max-w-[400px] px-4 z-10 relative">
    <div class="text-center mb-4">
      @if(isset($globalDinas) && $globalDinas && $globalDinas->logo)
        <img src="{{ Storage::url($globalDinas->logo) }}" alt="Logo" class="mx-auto mb-3 drop-shadow-lg"
          style="width: 70px; height: 70px; object-fit: contain;">
      @endif
      <h1 class="text-3xl font-bold text-white tracking-tight drop-shadow-md">
        Verifikasi OTP
      </h1>
    </div>

    <div class="bg-white dark:bg-[#1e2530] rounded-lg shadow-xl overflow-hidden border-t-4 border-info">
      <div class="p-8">
        <p class="text-slate-500 dark:text-slate-400 text-center mb-6 font-normal">
          Kode OTP telah dikirim ke <br><strong class="text-slate-700 dark:text-slate-200">{{ $email }}</strong>
        </p>

        @if(session('success'))
          <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm">
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('password.otp.submit') }}">
          @csrf
          <input type="hidden" name="email" value="{{ $email }}">

          <!-- OTP Input Group -->
          <div class="mb-6 text-center">
            <input name="otp" aria-label="OTP"
              class="otp-input w-full rounded border border-slate-300 bg-transparent px-3 py-3 text-2xl text-slate-700 outline-none transition duration-200 ease-in-out focus:border-primary focus:shadow-[inset_0_0_0_1px_rgb(12,72,192)] dark:border-slate-600 dark:text-slate-200"
              placeholder="000000" maxlength="6" required="" type="text" autofocus />
          </div>

          <button
            class="w-full inline-block rounded bg-primary px-6 py-2.5 text-sm font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-hover hover:shadow-lg focus:bg-primary-hover focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-hover active:shadow-lg"
            type="submit">
            Verifikasi Kode
          </button>
        </form>

        <div class="mt-6 border-t border-slate-100 dark:border-slate-700 pt-4 text-center">
          <form action="{{ route('password.email') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <p class="text-sm text-slate-600 dark:text-slate-400">
              Tidak menerima kode?
              <button type="submit" class="text-primary font-bold hover:underline">Kirim Ulang</button>
            </p>
          </form>
        </div>
      </div>
    </div>

    <div class="mt-4 text-center">
      <p class="text-white/80 text-xs font-light">
        © {{ date('Y') }} {{ isset($globalDinas) && $globalDinas ? $globalDinas->nama : 'Dinas Pemerintahan' }}.
        All rights reserved.
      </p>
    </div>
  </div>
  @include('partials.sweetalert')
</body>

</html>