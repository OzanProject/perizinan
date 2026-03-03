<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="robots" content="noindex, nofollow">
    <title>Registrasi Akun | {{ $globalDinas->app_name ?? 'Sistem Perizinan' }}</title>
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
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.85);
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

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.1) inset !important;
            -webkit-text-fill-color: inherit !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body
    class="mesh-gradient min-h-screen flex items-center justify-center p-6 font-sans antialiased overflow-x-hidden py-20">
    <!-- Back to Home Floating Button -->
    <a href="{{ route('landing') }}"
        class="fixed top-6 left-6 z-50 flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white text-sm font-semibold transition-all hover:-translate-x-1 group">
        <span
            class="material-symbols-outlined text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
        Beranda
    </a>

    <div class="w-full max-w-xl relative">
        <!-- Decorative Blobs -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-purple-600/20 rounded-full blur-3xl animate-pulse"
            style="animation-delay: 1.5s"></div>

        <!-- Card -->
        <div class="glass-card rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Header -->
                <div
                    class="flex flex-col md:flex-row items-center gap-6 mb-12 border-b border-slate-200/50 dark:border-slate-800/50 pb-8">
                    @if(isset($globalDinas) && $globalDinas->logo)
                        <div
                            class="p-3 rounded-2xl bg-white shadow-lg ring-4 ring-slate-100 dark:ring-slate-800 flex-shrink-0">
                            <img src="{{ Storage::url($globalDinas->logo) }}" alt="Logo" class="w-14 h-14 object-contain">
                        </div>
                    @endif
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-1">Daftar
                            Akun Lembaga</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Lengkapi formulir untuk
                            registrasi sistem perizinan</p>
                    </div>
                </div>

                <!-- Errors -->
                @if($errors->any())
                    <div
                        class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 flex flex-col gap-2">
                        @foreach($errors->all() as $error)
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-red-500 text-lg">error_outline</span>
                                <span class="text-red-700 dark:text-red-400 text-sm font-semibold">{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register') }}" method="post" class="space-y-8">
                    @csrf

                    <!-- Section 1: Profil Pendaftar -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2 px-1">
                            <span class="material-symbols-outlined text-blue-500">person</span>
                            <h2
                                class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                Informasi Pendaftar</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="Nama asli sesuai KTP"
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Email
                                    Aktif</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="email@contoh.com"
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Data Lembaga -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2 px-1">
                            <span class="material-symbols-outlined text-purple-500">account_balance</span>
                            <h2
                                class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                Detail Lembaga</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Jenjang
                                    Pendidikan</label>
                                <select name="jenjang" id="jenjang-select" required
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                                    <option value="" disabled selected>Pilih Jenjang</option>
                                    <option value="TK">TK (Taman Kanak-kanak)</option>
                                    <option value="SD">SD (Sekolah Dasar)</option>
                                    <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                                    <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                                    <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                                    <option value="PKBM">PKBM (Pusat Kegiatan Belajar Masyarakat)</option>
                                    <option value="LKP">LKP (Lembaga Kursus & Pelatihan)</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Lembaga
                                    Terdaftar</label>
                                <select name="lembaga_id" id="lembaga-select" required
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                                    <option value="" disabled selected>Pilih Nama Lembaga</option>
                                    @foreach($lembagas as $lembaga)
                                        <option value="{{ $lembaga->id }}" data-jenjang="{{ $lembaga->jenjang }}" {{ old('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                            {{ $lembaga->nama_lembaga }}
                                        </option>
                                    @endforeach
                                    <option value="new" {{ old('lembaga_id') == 'new' ? 'selected' : '' }}>+ Tambah
                                        Lembaga Baru</option>
                                </select>
                            </div>
                        </div>

                        <!-- New Institution Fields -->
                        <div id="new-lembaga-fields"
                            class="{{ old('lembaga_id') == 'new' ? 'block' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-200 dark:border-slate-800 animate-[fadeIn_0.3s_ease-out]">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-blue-600 dark:text-blue-400 ml-1">Nama Lembaga
                                    Baru</label>
                                <input type="text" name="nama_lembaga_baru" value="{{ old('nama_lembaga_baru') }}"
                                    placeholder="Contoh: PKBM Mandiri"
                                    class="block w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-blue-600 dark:text-blue-400 ml-1">NPSN (8
                                    Digit)</label>
                                <input type="text" name="npsn" maxlength="8" value="{{ old('npsn') }}"
                                    placeholder="12345678"
                                    class="block w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Keamanan -->
                    <div class="space-y-6 pt-4">
                        <div class="flex items-center gap-3 mb-2 px-1">
                            <span class="material-symbols-outlined text-green-500">lock_open</span>
                            <h2
                                class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                                Keamanan Akun</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Password
                                    Baru</label>
                                <input type="password" name="password" required placeholder="Minimal 8 karakter"
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-400 ml-1">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" required
                                    placeholder="Ulangi password"
                                    class="block w-full px-4 py-3 bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer group mt-4 px-1">
                            <input type="checkbox" required
                                class="mt-1 w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500/20 transition-all dark:bg-slate-900">
                            <span
                                class="text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200 transition-colors">Saya
                                setuju dengan <a href="#"
                                    class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Syarat &
                                    Ketentuan</a> layanan ini.</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold py-4 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-white/5 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white transition-all transform hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">person_add</span>
                        Selesaikan Pendaftaran
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}"
                            class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Masuk Sekarang</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center animate-pulse">
            <p class="text-white/40 text-[10px] font-bold uppercase tracking-[0.3em]">
                © {{ date('Y') }} {{ $globalDinas->footer_text ?? $globalDinas->nama ?? 'Dinas Pendidikan Kota' }}
            </p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            const jenjangSelect = document.getElementById('jenjang-select');
            const lembagaSelect = document.getElementById('lembaga-select');
            const newLembagaFields = document.getElementById('new-lembaga-fields');
            const allLembagaOptions = Array.from(lembagaSelect.options);

            function filterLembaga() {
                const selectedJenjang = jenjangSelect.value;
                lembagaSelect.innerHTML = '';
                lembagaSelect.appendChild(allLembagaOptions[0]);

                allLembagaOptions.slice(1).forEach(option => {
                    if (option.value === 'new' || option.getAttribute('data-jenjang') === selectedJenjang) {
                        lembagaSelect.appendChild(option);
                    }
                });
            }

            jenjangSelect.addEventListener('change', filterLembaga);
            lembagaSelect.addEventListener('change', function () {
                if (this.value === 'new') {
                    newLembagaFields.classList.remove('hidden');
                } else {
                    newLembagaFields.classList.add('hidden');
                }
            });
        });
    </script>

    @include('partials.sweetalert')
</body>

</html>