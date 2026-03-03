@extends('layouts.public')

@section('title', 'Lacak Status Pengajuan')

@section('content')
  <!-- Header Section -->
  <section class="relative px-6 md:px-20 py-24 bg-slate-50 dark:bg-slate-900/50 overflow-hidden">
    <!-- Premium Background Blobs -->
    <div class="absolute -top-24 -left-24 size-96 bg-primary/10 rounded-full blur-[100px]"></div>
    <div class="absolute top-1/2 -right-24 size-64 bg-blue-400/10 rounded-full blur-[80px]"></div>

    <div class="max-w-7xl mx-auto relative z-10">
      <div class="max-w-3xl" data-aos="fade-right">
        <div
          class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-[0.2em] mb-8 shadow-lg shadow-primary/20">
          <span class="material-symbols-outlined text-sm">analytics</span> Real-time Tracking
        </div>
        <h1 class="text-4xl md:text-7xl font-black text-slate-900 dark:text-white mb-8 tracking-tighter leading-tight">
          Pantau Status <span class="bg-gradient-to-r from-primary to-blue-500 bg-clip-text text-transparent italic">Izin
            Anda</span>
        </h1>
        <p class="text-slate-600 dark:text-slate-400 text-lg md:text-xl leading-relaxed mb-12 max-w-2xl">
          Lacak kemajuan berkas Anda secara transparan. Cukup masukkan nomor <span
            class="text-primary font-bold">NPSN</span> atau <span class="text-primary font-bold text-lg">Nama
            Lembaga</span> resmi Anda.
        </p>

        <!-- Tracking Widget -->
        <div class="relative group max-w-2xl" data-aos="fade-up" data-aos-delay="200">
          <div
            class="absolute -inset-1 bg-gradient-to-r from-primary to-blue-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
          </div>
          <div
            class="relative flex flex-col md:flex-row gap-2 bg-white dark:bg-slate-800 p-3 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-700">
            <div class="flex-1 flex items-center px-4">
              <span class="material-symbols-outlined text-slate-400 mr-3 text-2xl">search</span>
              <input type="text" id="tracking-id"
                class="w-full py-4 bg-transparent outline-none text-slate-900 dark:text-white text-lg font-bold placeholder:text-slate-400/60"
                placeholder="{{ $setting->track_placeholder ?? 'Masukkan NPSN atau Nama Lembaga...' }}">
            </div>
            <button id="track-btn"
              class="bg-primary text-white px-10 py-5 text-lg font-black rounded-3xl hover:bg-primary/95 transition-all flex items-center justify-center gap-3 shadow-xl shadow-primary/30">
              Lacak <span class="material-symbols-outlined font-black">arrow_forward</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Results Section -->
  <section class="max-w-7xl mx-auto px-6 py-24 pb-48">
    <div id="tracking-result-container" class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Initial State -->
      <div id="tracking-initial-msg" class="col-span-full py-32 text-center" data-aos="fade-up">
        <div
          class="size-32 bg-slate-50 dark:bg-slate-800/50 rounded-[3rem] shadow-inner flex items-center justify-center mx-auto mb-8 border border-white dark:border-slate-700">
          <span class="material-symbols-outlined text-6xl text-slate-300">manage_search</span>
        </div>
        <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-4">Siap Membantu Anda</h3>
        <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto text-lg leading-relaxed">
          Data pengajuan yang Anda cari akan muncul di sini setelah Anda memasukkan identitas lembaga.
        </p>
      </div>
      <!-- Inject Results via JS -->
    </div>
  </section>

  <!-- Add Timeline CSS -->
  <style>
    .timeline-step.active .step-icon {
      @apply bg-primary text-white scale-110 shadow-lg shadow-primary/30;
    }

    .timeline-step.active .step-text {
      @apply text-primary font-black;
    }

    .timeline-line {
      height: 2px;
      @apply bg-slate-100 dark:bg-slate-700;
    }

    .timeline-line.active {
      @apply bg-primary;
    }
  </style>

@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      $('#track-btn').click(function (e) {
        e.preventDefault();
        const trackingId = $('#tracking-id').val().trim();

        if (!trackingId) {
          Swal.fire({
            icon: 'warning',
            title: 'Ups!',
            text: 'Silakan masukkan NPSN atau Nama Lembaga terlebih dahulu.',
            confirmButtonColor: '#0A256E'
          });
          return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="material-symbols-outlined animate-spin">sync</span> Memproses...');
        $('#tracking-initial-msg').addClass('hidden');
        $('#tracking-result-container').html('<div class="col-span-full py-20 text-center"><div class="size-20 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-6"></div><p class="font-black text-xl text-slate-400 uppercase tracking-widest">Mencari Berkas...</p></div>');

        $.ajax({
          url: "{{ route('track.check') }}",
          method: "GET",
          data: { id: trackingId },
          success: function (response) {
            $btn.prop('disabled', false).html('Lacak <span class="material-symbols-outlined font-black">arrow_forward</span>');
            $('#tracking-result-container').html('');

            if (response.success && response.data.length > 0) {
              response.data.forEach(function (item, idx) {
                // Determine Timeline Progress
                let step = 1;
                switch (item.status_code) {
                  case 'diajukan': step = 1; break;
                  case 'perbaikan': step = 2; break;
                  case 'disetujui': step = 3; break;
                  case 'siap_diambil':
                  case 'selesai': step = 4; break;
                  case 'ditolaknya': step = 3; break; // Show at step 3 but failed
                }

                const isRejected = item.status_code === 'ditolak';

                let discussionHtml = '';
                if (item.latest_discussion) {
                  discussionHtml = `
                      <div class="mt-8 p-6 bg-slate-50 dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-700 relative">
                          <div class="absolute -top-3 left-8 px-3 py-1 bg-white dark:bg-slate-800 rounded-full border border-slate-100 dark:border-slate-700 text-[8px] font-black uppercase text-primary tracking-widest">Diskusi Terakhir</div>
                          <p class="text-sm text-slate-700 dark:text-slate-300 font-medium mb-3 italic">"${item.latest_discussion.message}"</p>
                          <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">${item.latest_discussion.user} • ${item.latest_discussion.date}</p>
                      </div>
                    `;
                }

                const cardHtml = `
                    <div class="group bg-white dark:bg-slate-800 rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-100 dark:border-slate-700 hover:border-primary transition-all duration-500 animate__animated animate__fadeInUp" style="animation-delay: ${idx * 0.1}s">
                      <div class="flex flex-col sm:flex-row justify-between items-start gap-6 mb-12">
                          <div>
                              <div class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-3">#${item.id} • ${item.tanggal}</div>
                              <h4 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-tight tracking-tighter capitalize mb-2">${item.lembaga}</h4>
                              <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-bold text-sm">
                                  <span class="material-symbols-outlined text-lg">description</span>
                                  ${item.jenis}
                              </div>
                          </div>
                          <div class="px-6 py-2.5 rounded-full text-xs font-black uppercase bg-${item.status_color}/10 text-${item.status_color} border border-${item.status_color}/20 flex items-center gap-2 shadow-sm">
                              <span class="size-2 rounded-full bg-${item.status_color} ${item.status_code === 'diajukan' ? 'animate-pulse' : ''}"></span>
                              ${item.status}
                          </div>
                      </div>

                      <!-- Modern Timeline Stepper -->
                      <div class="mb-12 relative px-4">
                          <div class="absolute top-5 left-8 right-8 timeline-line ${step >= 4 ? 'active' : ''}"></div>
                          <div class="flex justify-between relative z-10 text-center">
                              <!-- Step 1 -->
                              <div class="timeline-step ${step >= 1 ? 'active' : ''}">
                                  <div class="step-icon size-10 rounded-full bg-white dark:bg-slate-700 border-2 border-slate-100 dark:border-slate-600 flex items-center justify-center mx-auto mb-3 transition-all">
                                      <span class="material-symbols-outlined text-sm">send</span>
                                  </div>
                                  <span class="step-text text-[9px] uppercase tracking-widest">Pengajuan</span>
                              </div>
                              <!-- Step 2 -->
                              <div class="timeline-step ${step >= 2 ? 'active' : ''}">
                                  <div class="step-icon size-10 rounded-full bg-white dark:bg-slate-700 border-2 border-slate-100 dark:border-slate-600 flex items-center justify-center mx-auto mb-3 transition-all">
                                      <span class="material-symbols-outlined text-sm">fact_check</span>
                                  </div>
                                  <span class="step-text text-[9px] uppercase tracking-widest">Verifikasi</span>
                              </div>
                              <!-- Step 3 -->
                              <div class="timeline-step ${step >= 3 ? 'active' : ''}">
                                  <div class="step-icon size-10 rounded-full bg-white dark:bg-slate-700 border-2 border-slate-100 dark:border-slate-600 flex items-center justify-center mx-auto mb-3 transition-all">
                                      <span class="material-symbols-outlined text-sm">${isRejected ? 'error' : 'verified'}</span>
                                  </div>
                                  <span class="step-text text-[9px] uppercase tracking-widest">Validasi</span>
                              </div>
                              <!-- Step 4 -->
                              <div class="timeline-step ${step >= 4 ? 'active' : ''} ${item.status_code === 'selesai' ? 'text-emerald-500' : ''}">
                                  <div class="step-icon size-10 rounded-full bg-white dark:bg-slate-700 border-2 border-slate-100 dark:border-slate-600 flex items-center justify-center mx-auto mb-3 transition-all">
                                      <span class="material-symbols-outlined text-sm">inventory_2</span>
                                  </div>
                                  <span class="step-text text-[9px] uppercase tracking-widest">Selesai</span>
                              </div>
                          </div>
                      </div>

                      <div class="p-8 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-inner group-hover:bg-white dark:group-hover:bg-slate-800 transition-colors">
                          <div class="flex items-center gap-3 mb-4">
                              <span class="material-symbols-outlined text-primary text-xl">info</span>
                              <h5 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Ringkasan Status</h5>
                          </div>
                          <p class="text-slate-600 dark:text-slate-400 text-base leading-relaxed italic">"${item.keterangan}"</p>
                      </div>

                      ${discussionHtml}

                      <div class="mt-12 pt-8 border-t border-slate-50 dark:border-slate-700 flex justify-between items-center">
                          <div>
                              <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Nomor Pengajuan</p>
                              <code class="text-sm font-black text-slate-700 dark:text-slate-200">#${item.id}</code>
                          </div>
                          ${item.is_ready ? `
                          <div class="flex items-center gap-3 bg-emerald-500/10 text-emerald-500 px-6 py-3 rounded-2xl animate-bounce">
                              <span class="material-symbols-outlined font-black">check_circle</span>
                              <span class="text-xs font-black uppercase tracking-widest">Izin Siap Diambil</span>
                          </div>
                          ` : ''}
                      </div>
                    </div>
                  `;
                $('#tracking-result-container').append(cardHtml);
              });
            } else {
              $('#tracking-result-container').html(`
                  <div class="col-span-full py-32 text-center bg-red-50/20 dark:bg-red-900/10 rounded-[4rem] border-2 border-dashed border-red-100 dark:border-red-900/30">
                    <div class="size-24 bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl flex items-center justify-center mx-auto mb-8 border border-red-50">
                      <span class="material-symbols-outlined text-5xl text-red-500">search_off</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto text-lg leading-relaxed mb-8">
                      Mohon periksa kembali nomor NPSN atau penulisan nama lembaga Anda.
                    </p>
                    <button onclick="location.reload()" class="inline-flex items-center gap-2 text-primary font-black uppercase tracking-widest text-sm hover:underline">
                      <span class="material-symbols-outlined">restart_alt</span> Coba Lagi
                    </button>
                  </div>
                `);
            }
          },
          error: function (xhr) {
            $btn.prop('disabled', false).html('Lacak <span class="material-symbols-outlined font-black">arrow_forward</span>');
            Swal.fire({
              icon: 'error',
              title: 'Kesalahan Sistem',
              text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mencari data.',
              confirmButtonColor: '#0A256E'
            });
          }
        });
      });

      // Handle Enter Key
      $('#tracking-id').keypress(function (e) {
        if (e.which == 13) {
          $('#track-btn').click();
        }
      });
    });
  </script>
@endpush