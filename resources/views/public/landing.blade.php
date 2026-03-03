@extends('layouts.public')

@section('title', $setting->hero_title)
@section('meta_description', $setting->meta_description)
@section('meta_keywords', $setting->meta_keywords)

@section('content')
    <!-- Hero Section -->
    <section
        class="px-6 md:px-20 py-12 md:py-24 bg-gradient-to-b from-white to-background-light dark:from-slate-900 dark:to-background-dark">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-12 items-center">
            <div class="flex flex-col gap-8 flex-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit"
                    data-aos="fade-down" data-aos-delay="100">
                    <span class="material-symbols-outlined text-sm">verified</span> Verified Educational Services
                </div>
                <h1 class="text-slate-900 dark:text-slate-100 text-4xl md:text-6xl font-black leading-tight tracking-tighter"
                    data-aos="fade-up" data-aos-delay="200">
                    {!! $setting->hero_title !!}
                </h1>
                <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl leading-relaxed max-w-2xl"
                    data-aos="fade-up" data-aos-delay="300">
                    {{ $setting->hero_subtitle }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mt-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('perizinan.jenis') }}"
                        class="px-8 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/25 hover:bg-primary/90 hover:-translate-y-1 transition-all text-center">
                        Mulai Pengajuan
                    </a>
                    <a href="{{ route('landing.track') }}"
                        class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-black rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all text-center">
                        Lacak Status
                    </a>
                </div>

                <div class="flex flex-col gap-4 mt-8" data-aos="fade-up" data-aos-delay="500">
                    <p class="text-xs text-slate-400 uppercase font-black tracking-widest">Lembaga Terdaftar</p>
                    <div class="marquee-container">
                        <div class="marquee-content">
                            {{-- Double the loop for seamless animation --}}
                            @php $combinedLogos = $lembagaLogos->concat($lembagaLogos)->concat($lembagaLogos); @endphp
                            @foreach($combinedLogos as $l)
                                <div
                                    class="flex items-center gap-3 bg-white dark:bg-slate-800 px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                    <div
                                        class="size-8 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $l->logo) }}"
                                            alt="{{ $l->nama_lembaga }}">
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $l->nama_lembaga }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full relative" data-aos="fade-left" data-aos-duration="1200">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/20">
                    @if($setting->hero_image)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $setting->hero_image) }}" alt="Hero">
                    @else
                        <img class="w-full h-full object-cover"
                            src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1000"
                            alt="Office">
                    @endif
                    <div class="absolute inset-0 bg-primary/5 mix-blend-multiply"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brief Info Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 bg-white dark:bg-slate-900">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="flex flex-col gap-4" data-aos="fade-up" data-aos-delay="100">
                <div
                    class="size-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">speed</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">Proses Cepat</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Pengajuan divalidasi dalam waktu singkat untuk mendukung
                    operasional lembaga Anda.</p>
            </div>
            <div class="flex flex-col gap-4" data-aos="fade-up" data-aos-delay="200">
                <div
                    class="size-14 rounded-2xl bg-green-50 dark:bg-green-900/30 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">security</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">Aman & Terpercaya</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Data Anda tersimpan dengan enkripsi standar industri dan
                    proses yang transparan.</p>
            </div>
            <div class="flex flex-col gap-4" data-aos="fade-up" data-aos-delay="300">
                <div
                    class="size-14 rounded-2xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">visibility</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 dark:text-white">Transparansi Penuh</h4>
                <p class="text-slate-500 text-sm leading-relaxed">Pantau setiap tahapan verifikasi dokumen Anda secara
                    real-time dari mana saja.</p>
            </div>
        </div>
    </section>

    <!-- License Types Section -->
    <section class="max-w-7xl mx-auto px-6 py-24 bg-white dark:bg-slate-900" id="licenses">
        <div class="text-center mb-16" data-aos="fade-up">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-6">
                <span class="size-1.5 rounded-full bg-primary"></span>
                {{ $setting->license_title ?? 'JENIS IZIN TERSEDIA' }}
            </div>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 tracking-tighter">
                {{ $setting->license_subtitle ?? 'Pilih Jenis Perizinan Anda' }}
            </h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed">
                {{ $setting->license_description ?? 'Sistem kami mendukung berbagai kategori perizinan sesuai dengan kebutuhan operasional lembaga Anda.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($setting->license_types ?? [] as $index => $license)
                <div class="group relative bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 hover:border-primary/30 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/5"
                    data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
                    <div class="flex justify-between items-start mb-8">
                        <div
                            class="size-16 rounded-2xl bg-white dark:bg-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-500">
                            <span class="material-symbols-outlined text-3xl">{{ $license['icon'] ?? 'add_card' }}</span>
                        </div>
                        @if(isset($license['badge']))
                            <span
                                class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider">
                                {{ $license['badge'] }}
                            </span>
                        @endif
                    </div>

                    <h4
                        class="text-xl font-black text-slate-900 dark:text-white mb-4 group-hover:text-primary transition-colors">
                        {{ $license['title'] }}
                    </h4>

                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8 line-clamp-3">
                        {{ $license['description'] }}
                    </p>

                    <a href="{{ route('perizinan.jenis') }}"
                        class="inline-flex items-center gap-2 text-primary font-black text-xs uppercase tracking-widest group/link">
                        Detail Layanan
                        <span
                            class="material-symbols-outlined text-lg group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- User Guide Section -->
    <section class="max-w-7xl mx-auto px-6 py-32 relative overflow-hidden" id="guide">
        <!-- Background Decorative Elements -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-400/5 rounded-full blur-[120px] -z-10"></div>

        <div class="text-center mb-24 relative" data-aos="fade-up">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-6">
                <span class="size-1.5 rounded-full bg-primary animate-pulse"></span> Alur Pengajuan
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white mb-8 tracking-tighter">
                Proses Perizinan <span class="text-primary italic">Tanpa Ribet</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed">
                Kami mendesain sistem yang intuitif untuk memastikan perjalanan perizinan lembaga Anda berjalan mulus dari
                awal hingga akhir.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 relative z-10">
            <!-- Animated Path (Desktop Only) -->
            <svg class="absolute top-1/2 left-0 w-full h-1 hidden md:block -translate-y-1/2 -z-10 overflow-visible"
                viewBox="0 0 1000 20">
                <path d="M 0 10 Q 250 10 500 10 T 1000 10" fill="none" stroke="currentColor"
                    class="text-slate-200 dark:text-slate-800 animate-path-flow" stroke-width="2" />
            </svg>

            <!-- Step 1 -->
            <div class="group relative flex flex-col items-center" data-aos="fade-up" data-aos-delay="100">
                <div class="relative mb-8 animate-float-horizontal" style="animation-delay: 0s;">
                    <div
                        class="absolute inset-0 bg-primary/20 rounded-[2rem] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="size-24 rounded-[2rem] bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center justify-center relative z-10 group-hover:-translate-y-2 transition-transform duration-500">
                        <span class="text-4xl">🔐</span>
                        <div
                            class="absolute -top-3 -right-3 size-8 rounded-full bg-primary text-white text-xs font-black flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-900">
                            1</div>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-3 text-center">Registrasi Akun</h4>
                <p class="text-slate-500 text-sm text-center leading-relaxed">Mulai dengan mendaftarkan NPSN resmi lembaga
                    Anda.</p>
            </div>

            <!-- Step 2 -->
            <div class="group relative flex flex-col items-center" data-aos="fade-up" data-aos-delay="200">
                <div class="relative mb-8 pt-6 md:pt-0 animate-float-horizontal" style="animation-delay: 0.5s;">
                    <div
                        class="absolute inset-0 bg-blue-500/20 rounded-[2rem] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="size-24 rounded-[2rem] bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center justify-center relative z-10 group-hover:-translate-y-2 transition-transform duration-500">
                        <span class="text-4xl">📝</span>
                        <div
                            class="absolute -top-3 -right-3 size-8 rounded-full bg-blue-500 text-white text-xs font-black flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-900">
                            2</div>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-3 text-center">Lengkapi Berkas</h4>
                <p class="text-slate-500 text-sm text-center leading-relaxed">Unggah dokumen persyaratan sesuai jenis izin
                    yang dipilih.</p>
            </div>

            <!-- Step 3 -->
            <div class="group relative flex flex-col items-center" data-aos="fade-up" data-aos-delay="300">
                <div class="relative mb-8 pt-6 md:pt-0 animate-float-horizontal" style="animation-delay: 1s;">
                    <div
                        class="absolute inset-0 bg-indigo-500/20 rounded-[2rem] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="size-24 rounded-[2rem] bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center justify-center relative z-10 group-hover:-translate-y-2 transition-transform duration-500">
                        <span class="text-4xl">🛡️</span>
                        <div
                            class="absolute -top-3 -right-3 size-8 rounded-full bg-indigo-500 text-white text-xs font-black flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-900">
                            3</div>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-3 text-center">Audit & Verifikasi</h4>
                <p class="text-slate-500 text-sm text-center leading-relaxed">Tim ahli kami akan melakukan verifikasi
                    keaslian dokumen.</p>
            </div>

            <!-- Step 4 -->
            <div class="group relative flex flex-col items-center" data-aos="fade-up" data-aos-delay="400">
                <div class="relative mb-8 pt-6 md:pt-0 animate-float-horizontal" style="animation-delay: 1.5s;">
                    <div
                        class="absolute inset-0 bg-violet-500/20 rounded-[2rem] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="size-24 rounded-[2rem] bg-white dark:bg-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center justify-center relative z-10 group-hover:-translate-y-2 transition-transform duration-500">
                        <span class="text-4xl">📡</span>
                        <div
                            class="absolute -top-3 -right-3 size-8 rounded-full bg-violet-500 text-white text-xs font-black flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-900">
                            4</div>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-3 text-center">Pantau berkala</h4>
                <p class="text-slate-500 text-sm text-center leading-relaxed">Gunakan dashboard untuk melihat update status
                    setiap jam.</p>
            </div>

            <!-- Step 5 -->
            <div class="group relative flex flex-col items-center" data-aos="fade-up" data-aos-delay="500">
                <div class="relative mb-8 pt-6 md:pt-0 animate-float-horizontal" style="animation-delay: 2s;">
                    <div
                        class="absolute inset-0 bg-emerald-500/20 rounded-[2rem] blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="size-24 rounded-[2rem] bg-emerald-50 dark:bg-emerald-900/20 shadow-2xl shadow-emerald-200/50 dark:shadow-none border border-emerald-100 dark:border-emerald-800 flex items-center justify-center relative z-10 group-hover:-translate-y-2 transition-transform duration-500">
                        <span class="text-4xl">🎓</span>
                        <div
                            class="absolute -top-3 -right-3 size-8 rounded-full bg-emerald-500 text-white text-xs font-black flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-900">
                            5</div>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-900 dark:text-white mb-3 text-center">Izin Terbit</h4>
                <p class="text-slate-500 text-sm text-center leading-relaxed">Selamat! Ambil sertifikat fisik Anda di kantor
                    Dinas terkait.</p>
            </div>
        </div>

        <!-- Footer Section of Guide (CTA) -->
        <div class="mt-32 p-10 md:p-20 bg-[#0A256E] dark:bg-slate-800 rounded-[3.5rem] text-white flex flex-col lg:flex-row items-center justify-between gap-12 shadow-[0_40px_100px_-20px_rgba(15,73,189,0.4)] overflow-hidden relative"
            data-aos="fade-up">
            <!-- Premium Background Mesh -->
            <div
                class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_100%_0%,rgba(15,73,189,0.4)_0%,transparent_50%),radial-gradient(circle_at_0%_100%,rgba(59,130,246,0.3)_0%,transparent_50%)]">
            </div>
            <div class="absolute -top-32 -right-32 size-96 bg-primary/20 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-32 -left-32 size-96 bg-blue-400/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex-1 text-center lg:text-left">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-white/90 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                    ⚡ Akses Real-time
                </div>
                <h4 class="text-4xl md:text-5xl font-black mb-6 tracking-tighter leading-tight max-w-xl">
                    Siap Mengajukan <span
                        class="bg-gradient-to-r from-blue-200 to-white bg-clip-text text-transparent italic">Perizinan?</span>
                </h4>
                <p class="text-white/70 max-w-lg leading-relaxed text-lg mb-4">
                    Ayo mulai langkah pertama Anda untuk memodernisasi institusi. Sistem kami siap melayani Anda 24/7.
                </p>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row gap-6 w-full lg:w-auto">
                <a href="{{ route('login') }}"
                    class="group px-10 py-5 bg-white text-[#0A256E] font-black rounded-3xl hover:-translate-y-1 hover:shadow-2xl hover:shadow-white/20 transition-all duration-300 text-center flex items-center justify-center gap-3">
                    Masuk Ke Dashboard
                    <span
                        class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="{{ route('landing.track') }}"
                    class="px-10 py-5 border-2 border-white/20 text-white font-bold rounded-3xl hover:bg-white/10 hover:border-white/40 transition-all duration-300 text-center flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-xl">manage_search</span>
                    Lacak Status
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="max-w-4xl mx-auto px-4 py-20" id="faq">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-primary font-bold tracking-widest text-sm mb-4">{{ $setting->faq_title ?? 'PUSAT PENGETAHUAN' }}
            </h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
                {{ $setting->faq_subtitle ?? 'Pertanyaan yang Sering Diajukan' }}
            </h3>
        </div>
        <div class="space-y-4">
            @foreach($setting->faq ?? [] as $index => $item)
                <div class="faq-item bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm"
                    data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
                    <div class="p-6 flex items-center justify-between cursor-pointer"
                        onclick="this.parentElement.classList.toggle('active')">
                        <h5 class="font-bold text-slate-900 dark:text-white pr-4">{{ $item['question'] }}</h5>
                        <span
                            class="material-symbols-outlined text-slate-400 expand-icon transition-transform">expand_more</span>
                    </div>
                    <div
                        class="faq-answer px-6 text-slate-600 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-700">
                        <div class="py-4">
                            {{ $item['answer'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#track-btn').click(function (e) {
                e.preventDefault();
                const trackingId = $('#tracking-id').val();

                if (!trackingId) {
                    alert('Silakan masukkan NPSN atau Nama Lembaga.');
                    return;
                }

                $(this).prop('disabled', true).html('<span class="material-symbols-outlined animate-spin">sync</span> Loading...');
                $('#tracking-result-container').html('');

                $.ajax({
                    url: "{{ route('track.check') }}",
                    method: "GET",
                    data: { id: trackingId },
                    success: function (response) {
                        $('#track-btn').prop('disabled', false).html('Lacak <span class="material-symbols-outlined">arrow_forward</span>');

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(function (item) {
                                let discussionHtml = '';
                                if (item.latest_discussion) {
                                    discussionHtml = `
                                                                    <div class="mt-3 p-3 bg-primary/5 rounded-lg border border-primary/10">
                                                                        <p class="text-[10px] text-primary uppercase font-black mb-1">Catatan Terbaru</p>
                                                                        <p class="text-sm text-slate-700 dark:text-slate-300 font-medium mb-1">"${item.latest_discussion.message}"</p>
                                                                        <p class="text-[10px] text-slate-400 font-bold">${item.latest_discussion.user} • ${item.latest_discussion.date}</p>
                                                                    </div>
                                                                `;
                                }

                                const card = `
                                                                <div class="p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border-l-4 border-primary animate-in fade-in slide-in-from-top-4 duration-500">
                                                                    <div class="flex justify-between items-start mb-4">
                                                                        <div>
                                                                            <h5 class="text-xs font-black uppercase tracking-widest text-primary mb-1">Status Pengajuan</h5>
                                                                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">${item.lembaga}</h4>
                                                                        </div>
                                                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-${item.status_color}/10 text-${item.status_color}">${item.status}</span>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                                                        <div>
                                                                            <p class="text-[10px] text-slate-400 uppercase font-bold">Jenis Izin</p>
                                                                            <p class="text-sm font-semibold dark:text-slate-200">${item.jenis}</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-[10px] text-slate-400 uppercase font-bold">Tanggal</p>
                                                                            <p class="text-sm font-semibold dark:text-slate-200">${item.tanggal}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-100 dark:border-slate-700 line-clamp-2">
                                                                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Keterangan</p>
                                                                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">"${item.keterangan}"</p>
                                                                    </div>
                                                                    ${discussionHtml}
                                                                </div>
                                                            `;
                                $('#tracking-result-container').append(card);
                            });
                        } else {
                            alert(response.message || 'Data tidak ditemukan.');
                        }
                    },
                    error: function (xhr) {
                        $('#track-btn').prop('disabled', false).html('Lacak <span class="material-symbols-outlined">arrow_forward</span>');
                        alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat mencari data.');
                    }
                });
            });
        });
    </script>
@endpush