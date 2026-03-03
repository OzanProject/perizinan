<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="robots" content="noindex, nofollow">
    <title>Login | {{ $globalDinas->app_name ?? 'Sistem Perizinan' }}</title>
    @if(isset($globalDinas) && $globalDinas->logo)
        <link rel="shortcut icon" href="{{ Storage::url($globalDinas->logo) }}" />
    @endif

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { primary: { DEFAULT: '#0f172a', hover: '#1e293b' } }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mesh-gradient {
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.5) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(124, 58, 237, 0.4) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.5) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(124, 58, 237, 0.4) 0px, transparent 50%);
        }
    </style>
</head>

<body class="mesh-gradient min-h-screen flex items-center justify-center p-6 font-sans antialiased overflow-x-hidden">
    <!-- Back to Home Floating Button -->
    <a href="{{ route('landing') }}"
        class="fixed top-6 left-6 z-50 flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white text-sm font-semibold transition-all hover:-translate-x-1 group">
        <span
            class="material-symbols-outlined text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
        Kembali ke Beranda
    </a>

    <div class="w-full max-w-md relative">
        <!-- Decorative Blobs -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-purple-600/20 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1s"></div>

        <!-- Login Card -->
        <div
            class="glass-card rounded-3xl shadow-2xl overflow-hidden relative z-10 transition-all duration-500 hover:shadow-primary/20">
            <div class="p-8 md:p-10">
                <!-- Header -->
                <div class="text-center mb-10">
                    @if(isset($globalDinas) && $globalDinas->logo)
                        <div
                            class="inline-flex p-3 rounded-2xl bg-white shadow-lg mb-6 ring-4 ring-slate-100 dark:ring-slate-800">
                            <img src="{{ Storage::url($globalDinas->logo) }}" alt="Logo" class="w-16 h-16 object-contain">
                        </div>
                    @endif
                    <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">
                        Selamat Datang
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                        Silakan masuk ke akun {{ $globalDinas->app_name ?? 'Portal Perizinan' }} Anda
                    </p>
                </div>

                <!-- Alert Error -->
                @if($errors->any())
                    <div
                        class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-500 mt-0.5">error</span>
                        <span class="text-red-700 dark:text-red-400 text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="post" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label
                            class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 ml-1">Email
                            Address</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-slate-400">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
                                placeholder="nama@lembaga.com">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label
                                class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Password</label>
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Lupa
                                Password?</a>
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-slate-400">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input type="password" name="password" required
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer group w-fit">
                        <input type="checkbox" name="remember"
                            class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500/20 transition-all dark:bg-slate-900">
                        <span
                            class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Ingat
                            saya untuk sesi berikutnya</span>
                    </label>

                    <button type="submit"
                        class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold py-4 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-white/5 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all transform hover:-translate-y-1 active:translate-y-0">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                        Belum punya akun lembaga?
                        <a href="{{ route('register') }}"
                            class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Daftar Akun Baru</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="mt-8 text-center animate-pulse">
            <p class="text-white/40 text-[10px] font-bold uppercase tracking-[0.3em]">
                © {{ date('Y') }} {{ $globalDinas->footer_text ?? $globalDinas->nama ?? 'Dinas Pendidikan Kota' }}
            </p>
        </div>
    </div>

    @include('partials.sweetalert')
</body>

</html>