@extends('layouts.admin_lembaga')

@section('title', 'Profil Saya')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6 font-bold">
            <a class="hover:text-primary flex items-center gap-1 transition-colors"
                href="{{ route('admin_lembaga.dashboard') }}">
                <span class="material-symbols-outlined text-[18px]">home</span> Dashboard
            </a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="font-bold text-slate-900 dark:text-white uppercase tracking-widest text-[11px]">Akun Saya</span>
        </div>

        <!-- Personal Information -->
        <div
            class="p-6 sm:p-10 bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-[2rem]">
            <div class="max-w-2xl">
                <h2 class="text-xl font-black text-slate-900 dark:text-white mb-6 italic uppercase tracking-tight">Informasi
                    Personal</h2>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Security (Update Password) -->
        <div
            class="p-6 sm:p-10 bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-[2rem]">
            <div class="max-w-2xl">
                <h2 class="text-xl font-black text-slate-900 dark:text-white mb-6 italic uppercase tracking-tight">Keamanan
                    & Password</h2>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Danger Zone (Delete Account) -->
        <div
            class="p-6 sm:p-10 bg-red-50/30 dark:bg-red-950/10 shadow-sm border border-red-100 dark:border-red-900/20 rounded-[2rem]">
            <div class="max-w-2xl">
                <h2 class="text-xl font-black text-red-900 dark:text-red-400 mb-6 italic uppercase tracking-tight">Zona
                    Berbahaya</h2>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    <style>
        /* Clean form styling for the partials */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            @apply w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-sm font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all p-4 shadow-inner !important;
        }

        label {
            @apply block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 !important;
        }

        button[type="submit"],
        .inline-flex.items-center.px-4.py-2.bg-gray-800 {
            @apply bg-primary text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all active:scale-95 !important;
        }

        .text-sm.text-gray-600 {
            @apply text-xs italic font-semibold text-slate-500 !important;
        }
    </style>
@endsection