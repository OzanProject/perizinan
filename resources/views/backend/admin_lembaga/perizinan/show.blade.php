@extends('layouts.admin_lembaga')

@section('title', 'Detail Pengajuan - ' . $perizinan->jenisPerizinan->nama)

@section('content')
    <main class="flex-1 min-w-0 flex flex-col gap-8 max-w-6xl mx-auto w-full">
        <!-- Breadcrumb & Title Area -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <a class="hover:text-primary transition-colors" href="{{ route('admin_lembaga.dashboard') }}">Beranda</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a class="hover:text-primary transition-colors" href="{{ route('admin_lembaga.perizinan.index') }}">Tracking
                    Pengajuan</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-slate-900 dark:text-slate-200 font-medium whitespace-nowrap">Detail
                    #{{ str_pad($perizinan->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Detail
                        Pengajuan Izin</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 flex flex-wrap gap-x-4 gap-y-1">
                        <span class="flex items-center gap-1 font-medium"><span
                                class="material-symbols-outlined text-[18px]">verified_user</span>
                            {{ $perizinan->jenisPerizinan->nama }}</span>
                        <span class="flex items-center gap-1"><span
                                class="material-symbols-outlined text-[18px]">calendar_today</span>
                            {{ $perizinan->created_at->format('d M Y') }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    @php
                        $statusEnum = \App\Enums\PerizinanStatus::from($perizinan->status);
                        $statusColor = $statusEnum->color();
                        $colorMap = [
                            'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border-blue-200',
                            'warning' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 border-orange-200',
                            'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border-emerald-200',
                            'info' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 border-sky-200',
                            'danger' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border-red-200',
                            'dark' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border-slate-300',
                        ];
                        $dotColor = [
                            'primary' => 'bg-blue-500',
                            'warning' => 'bg-orange-500',
                            'success' => 'bg-emerald-500',
                            'info' => 'bg-sky-500',
                            'danger' => 'bg-red-500',
                            'dark' => 'bg-slate-500',
                        ];
                        $badgeClass = $colorMap[$statusColor] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        $dotClass = $dotColor[$statusColor] ?? 'bg-slate-500';
                    @endphp
                    <span
                        class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold border {{ $badgeClass }}">
                        <span class="w-2 h-2 rounded-full {{ $dotClass }} mr-2 animate-pulse"></span>
                        {{ $statusEnum->label() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <section
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">timeline</span>
                    Tracking Timeline
                </h3>
            </div>

            @php
                $steps = [
                    ['id' => 'draft', 'label' => 'Diajukan', 'icon' => 'send', 'desc' => 'Pendaftaran Awal'],
                    ['id' => 'diajukan', 'label' => 'Verifikasi', 'icon' => 'fact_check', 'desc' => 'Cek Administratif'],
                    ['id' => 'disetujui', 'label' => 'Persetujuan', 'icon' => 'gavel', 'desc' => 'Finalisasi SK'],
                    ['id' => 'siap_diambil', 'label' => 'Siap Diambil', 'icon' => 'inventory', 'desc' => 'SK Siap Diambil'],
                    ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'flag', 'desc' => 'Izin Terbit'],
                ];
                $statusValue = $perizinan->status;
                $currentIndex = 0;
                foreach ($steps as $i => $step) {
                    if ($statusValue == $step['id'])
                        $currentIndex = $i;
                }
            @endphp

            <!-- Desktop Timeline -->
            <div class="p-10 hidden md:block">
                <div class="relative flex items-center justify-between w-full">
                    <!-- Progress Line Background -->
                    <div
                        class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-100 dark:bg-slate-700 rounded-full -z-0">
                    </div>

                    @foreach($steps as $i => $step)
                        <div class="relative flex flex-col items-center group z-10 w-full">
                            @if($i < $currentIndex || $statusValue == 'selesai')
                                <div
                                    class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md ring-4 ring-white dark:ring-slate-800">
                                    <span class="material-symbols-outlined text-xl">check</span>
                                </div>
                            @elseif($i == $currentIndex)
                                <div
                                    class="w-10 h-10 rounded-full bg-white border-2 border-primary text-primary flex items-center justify-center shadow-lg shadow-primary/20 ring-4 ring-white dark:ring-slate-800">
                                    <span class="material-symbols-outlined text-xl animate-pulse">{{ $step['icon'] }}</span>
                                </div>
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 dark:border-slate-700 text-slate-300 flex items-center justify-center ring-4 ring-white dark:ring-slate-800">
                                    <span class="material-symbols-outlined text-xl">{{ $step['icon'] }}</span>
                                </div>
                            @endif

                            <div class="absolute top-12 flex flex-col items-center w-32 text-center">
                                <p
                                    class="text-sm {{ $i <= $currentIndex ? 'font-bold text-slate-900 dark:text-white' : 'font-medium text-slate-400' }}">
                                    {{ $step['label'] }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="h-12"></div>
            </div>

            <!-- Mobile Vertical Timeline -->
            <div class="p-6 md:hidden flex flex-col relative pl-4">
                <div class="absolute left-[27px] top-6 bottom-6 w-0.5 bg-slate-100 dark:bg-slate-700"></div>
                @foreach($steps as $i => $step)
                    <div
                        class="flex gap-4 mb-8 relative {{ $i > $currentIndex && $statusValue != 'selesai' ? 'opacity-50' : '' }}">
                        @if($i < $currentIndex || $statusValue == 'selesai')
                            <div
                                class="w-6 h-6 rounded-full bg-emerald-500 shrink-0 ring-4 ring-white dark:ring-slate-800 z-10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[14px]">check</span>
                            </div>
                        @elseif($i == $currentIndex)
                            <div
                                class="w-6 h-6 rounded-full bg-white border-2 border-primary shrink-0 ring-4 ring-white dark:ring-slate-800 z-10 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-primary text-[14px] animate-pulse">{{ $step['icon'] }}</span>
                            </div>
                        @else
                            <div
                                class="w-6 h-6 rounded-full bg-white border-2 border-slate-300 shrink-0 ring-4 ring-white dark:ring-slate-800 z-10">
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $step['label'] }}</p>
                            <p class="text-xs text-slate-500">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Feedback Section -->
        @if($statusValue == 'perbaikan')
            <section
                class="bg-orange-50 dark:bg-orange-900/10 border border-orange-200 dark:border-orange-800/50 rounded-2xl overflow-hidden shadow-sm relative transition-all hover:shadow-md">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-orange-500"></div>
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400">
                                    <span class="material-symbols-outlined">warning</span>
                                </div>
                                <h3 class="text-xl font-black text-orange-800 dark:text-orange-300">Catatan dari Dinas</h3>
                            </div>
                            <p class="text-orange-700 dark:text-orange-200/70 mb-6 leading-relaxed font-medium">
                                Mohon segera perbaiki dokumen berikut sesuai dengan arahan verifikator kami:
                            </p>

                            <!-- Discussion Snippet for context -->
                            <div
                                class="bg-white dark:bg-slate-900/50 p-6 rounded-2xl border border-orange-100 dark:border-orange-800/30 space-y-4 shadow-sm">
                                @php
                                    $latestAdminMsg = $perizinan->discussions->where('user_id', '!=', Auth::id())->last();
                                @endphp
                                @if($latestAdminMsg)
                                    <div class="flex gap-3">
                                        <span class="material-symbols-outlined text-red-500 text-xl shrink-0">info</span>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white mb-1">Arahan Verifikator
                                                ({{ $latestAdminMsg->user->name }}):</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 italic">
                                                "{{ $latestAdminMsg->message }}"</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm italic text-slate-400 text-center">Silakan cek riwayat diskusi untuk detail
                                        lengkap.</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 min-w-[240px] w-full md:w-auto">
                            <a href="{{ route('admin_lembaga.perizinan.edit', $perizinan) }}"
                                class="w-full py-4 px-6 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold shadow-lg shadow-primary/25 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <span class="material-symbols-outlined">upload_file</span>
                                Upload Perbaikan Now
                            </a>
                            <button onclick="document.getElementById('chat-input').focus()"
                                class="w-full py-4 px-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">chat</span>
                                Tanya Balas Admin
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Documents Grid -->
                <section
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div
                        class="px-8 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">folder_open</span>
                            Berkas Terlampir
                        </h3>
                        <span
                            class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-full">{{ $perizinan->dokumens->count() }}
                            Dokumen</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($perizinan->dokumens as $dokumen)
                                <div
                                    class="group flex items-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-primary/50 hover:bg-primary/5 dark:hover:bg-slate-700/50 transition-all cursor-default">
                                    <div
                                        class="h-12 w-12 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center shrink-0 border border-red-100 dark:border-red-900/30">
                                        <span class="material-symbols-outlined">picture_as_pdf</span>
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate">
                                            {{ $dokumen->nama_file }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tight">
                                            {{ $dokumen->created_at->format('d M Y') }}</p>
                                    </div>
                                    <a href="{{ Storage::url($dokumen->path) }}" target="_blank"
                                        class="opacity-0 group-hover:opacity-100 p-2 hover:bg-white dark:hover:bg-slate-600 rounded-full text-slate-400 hover:text-primary transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Bottom Actions -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    @if($statusValue == 'siap_diambil')
                        <div class="flex flex-col sm:flex-row items-center gap-4 w-full justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <span class="material-symbols-outlined">info</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Dokumen Siap Diambil</p>
                                    <p class="text-xs text-slate-500">Silakan konfirmasi jika dokumen fisik telah diterima.</p>
                                </div>
                            </div>
                            <form action="{{ route('admin_lembaga.perizinan.confirm_taken', $perizinan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full sm:w-auto px-10 py-3.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold shadow-lg shadow-primary/25 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5"
                                    onclick="return confirm('Konfirmasi bahwa dokumen fisik telah diterima?')">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Konfirmasi Dokumen Diterima
                                </button>
                            </form>
                        </div>
                    @elseif($statusValue == 'disetujui' || $statusValue == 'selesai')
                        <button
                            class="w-full sm:w-auto px-10 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined">download_done</span>
                            Unduh Sertifikat Izin
                        </button>
                        <button
                            class="w-full sm:w-auto px-6 py-3.5 bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">description</span> Tanda Terima
                        </button>
                    @elseif($statusValue == 'draft')
                        <p class="text-sm text-slate-500 font-medium italic italic">Draft ini tersimpan otomatis.</p>
                        <form action="{{ route('admin_lembaga.perizinan.destroy', $perizinan) }}" method="POST"
                            onsubmit="return confirm('Hapus pengajuan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 font-bold hover:underline py-2 px-4 transition-all">Batalkan
                                Selamanya</button>
                        </form>
                    @else
                        <p class="text-sm text-slate-500 font-medium italic italic">Pengajuan sedang diproses oleh sistem.</p>
                    @endif
                </div>
            </div>

            <!-- Right Column: Discussion -->
            <div class="lg:col-span-1 space-y-8">
                <div
                    class="flex flex-col bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden h-[630px] sticky top-24 transition-all hover:shadow-lg">
                    <div
                        class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">forum</span>
                            </div>
                            <h3 class="font-bold text-slate-800 dark:text-white">Live Discussion</h3>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/30 dark:bg-slate-900/10 custom-scrollbar"
                        id="chat-box">
                        @forelse($perizinan->discussions as $discussion)
                            <div
                                class="flex flex-col {{ $discussion->user_id == Auth::id() ? 'items-end' : 'items-start' }} gap-1.5 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                @if($discussion->user_id != Auth::id())
                                    <p class="text-[10px] font-black text-slate-400 ml-2 uppercase tracking-tight">
                                        {{ $discussion->user->name }}</p>
                                @endif
                                <div
                                    class="max-w-[90%] px-4 py-3 rounded-2xl {{ $discussion->user_id == Auth::id() ? 'bg-primary text-white rounded-br-none shadow-md shadow-primary/20' : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white border border-slate-100 dark:border-slate-600 rounded-tl-none shadow-sm' }}">
                                    <p class="text-sm leading-relaxed">{{ $discussion->message }}</p>
                                </div>
                                <span
                                    class="text-[9px] text-slate-400 font-bold px-2">{{ $discussion->created_at->format('H:i') }}
                                    • {{ $discussion->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full opacity-30 gap-4 text-center px-6">
                                <div
                                    class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl">comments_disabled</span>
                                </div>
                                <p class="text-sm font-bold italic">Belum ada diskusi atau tanya jawab terkait pengajuan ini.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div class="p-5 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
                        <form action="{{ route('admin_lembaga.perizinan.discussion.store', $perizinan) }}" method="POST"
                            class="relative group">
                            @csrf
                            <input type="text" name="message" id="chat-input" placeholder="Tulis pesan ke tim dinas..."
                                class="w-full pl-5 pr-14 py-4 bg-slate-100 dark:bg-slate-900 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary transition-all shadow-inner"
                                required autocomplete="off">
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-primary text-white rounded-xl hover:scale-105 active:scale-95 transition-all flex items-center justify-center shadow-lg shadow-primary/25">
                                <span class="material-symbols-outlined text-[20px]">send</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
@endpush