<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Sistem Perizinan Dinas</title>
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
                    },
                    backgroundImage: {
                        'checkbox-tick': "url(\"data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e\")",
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
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

    <!-- Login Box Container -->
    <div class="w-full max-w-[400px] px-4 z-10 relative">
        <div class="text-center mb-4">
            <h1 class="text-3xl font-bold text-white tracking-tight drop-shadow-md">
                Sistem Perizinan Dinas
            </h1>
        </div>

        <div class="bg-white dark:bg-[#1e2530] rounded-lg shadow-xl overflow-hidden border-t-4 border-primary">
            <div class="p-8">
                <p class="text-slate-500 dark:text-slate-400 text-center mb-6 font-normal">Masuk untuk memulai sesi Anda
                </p>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <!-- Email Input Group -->
                    <div class="mb-4">
                        <div class="relative flex w-full flex-wrap items-stretch">
                            <input name="email" aria-label="Email"
                                class="relative m-0 block w-[1px] min-w-0 flex-auto rounded-l border border-r-0 border-slate-300 bg-transparent bg-clip-padding px-3 py-[0.5rem] text-base font-normal leading-[1.6] text-slate-700 outline-none transition duration-200 ease-in-out placeholder:text-slate-400 focus:z-[3] focus:border-primary focus:text-slate-700 focus:shadow-[inset_0_0_0_1px_rgb(12,72,192)] focus:outline-none dark:border-slate-600 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-primary"
                                placeholder="Email" required="" type="email" value="{{ old('email') }}" />
                            <span
                                class="input-group-text flex items-center whitespace-nowrap rounded-r border border-l-0 border-slate-300 px-3 py-[0.5rem] text-center text-base font-normal text-slate-500 dark:border-slate-600 dark:text-slate-400 bg-white dark:bg-[#1e2530]">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </span>
                        </div>
                    </div>
                    <!-- Password Input Group -->
                    <div class="mb-4">
                        <div class="relative flex w-full flex-wrap items-stretch">
                            <input name="password" aria-label="Password"
                                class="relative m-0 block w-[1px] min-w-0 flex-auto rounded-l border border-r-0 border-slate-300 bg-transparent bg-clip-padding px-3 py-[0.5rem] text-base font-normal leading-[1.6] text-slate-700 outline-none transition duration-200 ease-in-out placeholder:text-slate-400 focus:z-[3] focus:border-primary focus:text-slate-700 focus:shadow-[inset_0_0_0_1px_rgb(12,72,192)] focus:outline-none dark:border-slate-600 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-primary"
                                placeholder="Password" required="" type="password" />
                            <span
                                class="input-group-text flex items-center whitespace-nowrap rounded-r border border-l-0 border-slate-300 px-3 py-[0.5rem] text-center text-base font-normal text-slate-500 dark:border-slate-600 dark:text-slate-400 bg-white dark:bg-[#1e2530]">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </span>
                        </div>
                    </div>
                    <!-- Row: Remember Me & Sign In Button -->
                    <div class="flex items-center justify-between mt-6 mb-4">
                        <div class="flex items-center min-h-[1.5rem] pl-[1.5rem] relative">
                            <input name="remember"
                                class="absolute left-0 top-0 h-5 w-5 rounded border-slate-300 bg-transparent text-primary focus:ring-0 focus:ring-offset-0 transition-all checked:bg-primary checked:border-primary checked:bg-checkbox-tick"
                                id="rememberMe" type="checkbox" />
                            <label
                                class="inline-block pl-2 hover:cursor-pointer text-slate-700 dark:text-slate-300 text-sm font-medium"
                                for="rememberMe">
                                Ingat Saya
                            </label>
                        </div>
                        <div class="w-1/3">
                            <button
                                class="w-full inline-block rounded bg-primary px-6 py-2 text-sm font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-hover hover:shadow-lg focus:bg-primary-hover focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-hover active:shadow-lg"
                                type="submit">
                                Masuk
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-4 border-t border-slate-100 dark:border-slate-700 pt-4">
                    <p class="mb-2">
                        <a class="text-slate-600 hover:text-primary dark:text-slate-400 dark:hover:text-primary transition-colors text-sm"
                            href="#">
                            Lupa kata sandi?
                        </a>
                    </p>
                    <p class="mb-0">
                        <a class="text-slate-600 hover:text-primary dark:text-slate-400 dark:hover:text-primary transition-colors text-sm"
                            href="{{ route('register') }}">
                            Daftar akun baru
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-white/80 text-xs font-light">
                © 2024 Dinas Pemerintahan Kota. All rights reserved.
            </p>
        </div>
    </div>
    @include('partials.sweetalert')
</body>

</html>