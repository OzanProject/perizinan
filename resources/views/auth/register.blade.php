<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Register - {{ isset($globalDinas) && $globalDinas ? $globalDinas->app_name : 'Sistem Perizinan Dinas' }}
    </title>
    @if(isset($globalDinas) && $globalDinas && $globalDinas->logo)
        <link rel="shortcut icon" href="{{ Storage::url($globalDinas->logo) }}" />
        <link rel="icon" type="image/png" href="{{ Storage::url($globalDinas->logo) }}">
    @endif

    <!-- Google Fonts: Public Sans & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0b50da",
                        "primary-dark": "#0941b3",
                        "background-light": "#f4f6f9", // AdminLTE light gray bg
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
                    },
                    boxShadow: {
                        "admin-card": "0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2)",
                    }
                },
            },
        }
    </script>

    <style type="text/css">
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

        /* Prevent FOUC */
        [v-cloak] {
            display: none;
        }

        body {
            opacity: 0;
            transition: opacity 0.2s ease-in;
        }

        body.loaded {
            opacity: 1;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark font-display antialiased min-h-screen flex flex-col items-center justify-center p-4">
    <!-- Register Box Container -->
    <div class="w-full max-w-[500px]">
        <!-- Logo / Brand Header -->
        <div class="text-center mb-6">
            @if(isset($globalDinas) && $globalDinas && $globalDinas->logo)
                <img src="{{ Storage::url($globalDinas->logo) }}" alt="Logo" class="mx-auto mb-3"
                    style="width: 70px; height: 70px; object-fit: contain;">
            @else
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                    <span class="material-symbols-outlined text-4xl">verified</span>
                </div>
            @endif
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">
                {{ isset($globalDinas) && $globalDinas ? $globalDinas->app_name : 'Sistem Perizinan Dinas' }}
            </h1>
        </div>
        <!-- Card -->
        <div class="bg-white dark:bg-[#1a202c] shadow-admin-card rounded-lg overflow-hidden border-t-4 border-primary">
            <div class="p-8">
                <p class="text-center text-slate-600 dark:text-slate-400 mb-6 font-medium">Register a new membership</p>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded">
                        <ul class="list-disc list-inside mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" class="flex flex-col gap-4" method="post">
                    @csrf
                    <!-- Account Type (Read Only) -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <input
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 h-11 pr-10 pl-3 transition-colors cursor-not-allowed"
                                value="Admin Lembaga" readonly type="text" />
                            <div
                                class="absolute right-3 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">badge</span>
                            </div>
                        </div>
                    </div>
                    <!-- Full Name -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <input name="name"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors placeholder:text-slate-400"
                                placeholder="Full name" type="text" value="{{ old('name') }}" required />
                            <div
                                class="absolute right-3 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">person</span>
                            </div>
                        </div>
                    </div>
                    <!-- Jenjang Pendidikan -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <select name="jenjang" id="jenjang-select" required
                                class="form-select w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors">
                                <option value="" disabled selected>Pilih Jenjang Sekolah</option>
                                <option value="TK">TK (Taman Kanak-kanak)</option>
                                <option value="SD">SD (Sekolah Dasar)</option>
                                <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                                <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                                <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
                                <option value="PKBM">PKBM (Pusat Kegiatan Belajar Masyarakat)</option>
                                <option value="LKP">LKP (Lembaga Kursus & Pelatihan)</option>
                            </select>
                            <div
                                class="absolute right-8 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">layers</span>
                            </div>
                        </div>
                    </div>

                    <!-- Institution Selection -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <select name="lembaga_id" id="lembaga-select" required
                                class="form-select w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors">
                                <option value="" disabled selected>Pilih Nama Lembaga</option>
                                @foreach($lembagas as $lembaga)
                                    <option value="{{ $lembaga->id }}" data-jenjang="{{ $lembaga->jenjang }}" {{ old('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                        {{ $lembaga->nama_lembaga }}
                                    </option>
                                @endforeach
                                <option value="new" {{ old('lembaga_id') == 'new' ? 'selected' : '' }}>+ Tambah Lembaga
                                    Baru</option>
                            </select>
                            <div
                                class="absolute right-8 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">account_balance</span>
                            </div>
                        </div>
                    </div>

                    <!-- New Institution Fields (Hidden by default) -->
                    <div id="new-lembaga-fields"
                        class="{{ old('lembaga_id') == 'new' ? 'block' : 'hidden' }} space-y-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="group">
                            <input name="nama_lembaga_baru"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 px-3 transition-colors placeholder:text-slate-400"
                                placeholder="Nama Lengkap Lembaga Baru" type="text"
                                value="{{ old('nama_lembaga_baru') }}" />
                        </div>
                        <div class="group">
                            <input name="npsn"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 px-3 transition-colors placeholder:text-slate-400"
                                placeholder="NPSN (8 Digit)" type="text" maxlength="8" value="{{ old('npsn') }}" />
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <input name="email"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors placeholder:text-slate-400"
                                placeholder="Email" type="email" value="{{ old('email') }}" required />
                            <div
                                class="absolute right-3 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <input name="password"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors placeholder:text-slate-400"
                                placeholder="Password" type="password" required />
                            <div
                                class="absolute right-3 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                        </div>
                    </div>
                    <!-- Retype Password -->
                    <div class="group">
                        <div class="relative flex items-center">
                            <input name="password_confirmation"
                                class="form-input w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-primary focus:ring-primary h-11 pr-10 pl-3 transition-colors placeholder:text-slate-400"
                                placeholder="Retype password" type="password" required />
                            <div
                                class="absolute right-3 h-full flex items-center text-slate-400 pointer-events-none group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">lock_reset</span>
                            </div>
                        </div>
                    </div>
                    <!-- Terms Checkbox & Submit -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                        <div class="flex items-center">
                            <input
                                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary dark:bg-slate-800 dark:border-slate-600"
                                id="terms" type="checkbox" required />
                            <label class="ml-2 block text-sm text-slate-600 dark:text-slate-400" for="terms">
                                I agree to the <a class="text-primary hover:text-primary-dark font-medium"
                                    href="#">terms</a>
                            </label>
                        </div>
                    </div>
                    <button
                        class="mt-4 w-full bg-primary hover:bg-primary-dark text-white font-bold py-2.5 px-4 rounded transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                        type="submit">
                        <span class="material-symbols-outlined text-xl">person_add</span>
                        Register
                    </button>
                </form>
                <div
                    class="mt-8 text-center bg-slate-50 dark:bg-slate-800/50 -mx-8 -mb-8 p-6 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-slate-600 dark:text-slate-400">
                        Already have a membership?
                        <a class="text-primary font-bold hover:text-primary-dark transition-colors inline-flex items-center gap-1"
                            href="{{ route('login') }}">
                            Sign in <span class="material-symbols-outlined text-sm">login</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            document.body.classList.add('loaded');

            const jenjangSelect = document.getElementById('jenjang-select');
            const lembagaSelect = document.getElementById('lembaga-select');
            const newLembagaFields = document.getElementById('new-lembaga-fields');
            const allLembagaOptions = Array.from(lembagaSelect.options);

            function filterLembaga() {
                const selectedJenjang = jenjangSelect.value;

                // Clear current options except first
                lembagaSelect.innerHTML = '';
                lembagaSelect.appendChild(allLembagaOptions[0]); // Pilih Nama Lembaga

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