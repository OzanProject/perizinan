@extends('layouts.backend')

@section('title', 'Verifikasi Perizinan')
@section('breadcrumb', 'Verifikasi Administratif')

@php
  // AMBIL PENGATURAN PRESET AKTIF UNTUK UKURAN KERTAS & PADDING
  $activePreset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
    ->where('is_active', true)
    ->first();

  $paperSize = strtoupper($activePreset->paper_size ?? 'A4');
  $orientation = strtolower($activePreset->orientation ?? 'portrait');

  $width = '210mm';
  $height = '297mm';
  $isF4 = $paperSize === 'F4';

  if ($isF4) {
    $width = '215mm';
    $height = '330mm';
  }

  if ($orientation === 'landscape') {
    $temp = $width;
    $width = $height;
    $height = $temp;
  }

  // MENGAMBIL PADDING (SAFE AREA) DARI PRESET AGAR PREVIEW IDENTIK DENGAN PDF
  if ($activePreset) {
    $mt = $activePreset->margin_top ?? 2.5;
    $mr = $activePreset->margin_right ?? 3.0;
    $mb = $activePreset->margin_bottom ?? 2.0;
    $ml = $activePreset->margin_left ?? 3.0;
    $padding = "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
  } else {
    $padding = "2.5cm 3cm 2cm 3cm";
  }
@endphp

@push('styles')
  <style>
    /* =========================================================
             CSS FIX UNTUK KANVAS PRATINJAU DRAFT (IDENTIK PDF)
             ========================================================= */
    #draft-preview-canvas {
      position: relative;
      background: white;
      width:
        {{ $width }}
      ;
      min-height:
        {{ $height }}
      ;
      padding:
        {{ $padding }}
      ;
      margin: 0 auto;
      box-sizing: border-box;
      /* Agar padding memakan ke dalam, bukan keluar */
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
      transform-origin: top center;
      transition: transform 0.3s ease;

      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.15;
      color: #000;
    }

    /* Elemen fixed (seperti bingkai background) menjadi absolute dalam kanvas */
    #draft-preview-canvas div[style*="position: fixed"] {
      position: absolute !important;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      width: 100% !important;
      height: 100% !important;
    }

    /* Fix Spasi Paragraf */
    #draft-preview-canvas p {
      clear: both;
      margin-top: 2px;
      margin-bottom: 2px;
    }

    #draft-preview-canvas p:last-child {
      margin-bottom: 0 !important;
    }

    /* Fix Tabel */
    #draft-preview-canvas table {
      border-collapse: collapse;
      width: 100%;
    }

    #draft-preview-canvas td {
      vertical-align: top;
      padding: 2px 4px;
      border: none;
    }

    /* Fix Logo CKEditor (Rata Tengah dll) */
    #draft-preview-canvas figure {
      margin: 0;
      padding: 0;
    }

    #draft-preview-canvas figure.image {
      display: block !important;
      width: 100% !important;
      text-align: center !important;
      margin-bottom: 10px !important;
      clear: both !important;
    }

    #draft-preview-canvas figure.image img {
      display: inline-block !important;
      margin: 0 auto !important;
      max-width: 100%;
      height: auto;
    }

    #draft-preview-canvas .image-style-align-left {
      text-align: left !important;
    }

    #draft-preview-canvas .image-style-align-left img {
      float: left !important;
      margin-right: 15px !important;
    }

    #draft-preview-canvas .image-style-align-center {
      text-align: center !important;
    }

    #draft-preview-canvas .image-style-align-center img {
      margin-left: auto !important;
      margin-right: auto !important;
    }

    #draft-preview-canvas .image-style-align-right {
      text-align: right !important;
    }

    #draft-preview-canvas .image-style-align-right img {
      float: right !important;
      margin-left: 15px !important;
    }

    .rotate-45 {
      transform: rotate(-45deg);
    }

    #chat-container::-webkit-scrollbar {
      width: 4px;
    }

    #chat-container::-webkit-scrollbar-track {
      background: transparent;
    }

    #chat-container::-webkit-scrollbar-thumb {
      background: #dee2e6;
      border-radius: 10px;
    }

    @media print {

      .main-header,
      .main-sidebar,
      .card-header,
      .card-footer,
      .btn,
      .modal-header,
      .card-tools {
        display: none !important;
      }

      .content-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
      }

      #draft-preview-canvas {
        transform: scale(1) !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
      }

      .modal {
        position: static !important;
        overflow: visible !important;
      }

      .modal-dialog {
        max-width: 100% !important;
        margin: 0 !important;
      }
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card card-primary card-outline card-tabs">
          <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="perizinan-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="data-lembaga-tab" data-toggle="pill"
                  href="#tab-data-lembaga" role="tab">
                  <i class="fas fa-university mr-1"></i> Data Lembaga & Dokumen
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link font-weight-bold" id="diskusi-tab" data-toggle="pill" href="#tab-diskusi" role="tab">
                  <i class="fas fa-comments mr-1"></i> Diskusi & Catatan
                  @if($perizinan->discussions->count() > 0)
                    <span class="badge badge-danger ml-1">{{ $perizinan->discussions->count() }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-data-lembaga" role="tabpanel">
                <div class="card card-info card-outline">
                  <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-id-card mr-2"></i> Identitas Pengajuan</h3>
                    <div class="card-tools">
                      <a href="{{ route('super_admin.lembaga.show', $perizinan->lembaga) }}"
                        class="btn btn-tool btn-sm bg-light">
                        <i class="fas fa-external-link-alt mr-1"></i> Profil Lembaga
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Nama Lembaga</label>
                        <p class="mb-0 font-weight-bold text-dark h6">{{ $perizinan->lembaga->nama_lembaga }}</p>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">NPSN</label>
                        <p class="mb-0 font-weight-bold text-dark h6">{{ $perizinan->lembaga->npsn }}</p>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Jenis Izin</label>
                        <p class="mb-0 font-weight-bold text-dark h6 text-primary">{{ $perizinan->jenisPerizinan->nama }}
                        </p>
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Nomor Surat</label>
                        <p class="mb-0 font-weight-bold text-dark h6">{{ $perizinan->nomor_surat ?? '-' }}</p>
                      </div>
                      <div class="col-12 mb-3">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Alamat Lengkap</label>
                        <p class="mb-0 text-dark">{{ $perizinan->lembaga->alamat }}</p>
                      </div>
                      <div class="col-sm-6">
                        <label class="text-muted small text-uppercase font-weight-bold mb-1">Jenjang & Tanggal</label>
                        <p class="mb-0 text-dark">
                          <span class="badge badge-secondary">{{ $perizinan->lembaga->jenjang }}</span>
                          <span class="text-muted ml-2 small">{{ $perizinan->created_at->format('d F Y, H:i') }}</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card card-warning card-outline mt-4">
                  <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-file-alt mr-2"></i> Dokumen Persyaratan</h3>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      @forelse($perizinan->dokumens as $dokumen)
                        <div class="col-md-6 mb-3">
                          <div class="info-box shadow-none border bg-light">
                            <span class="info-box-icon bg-danger-gradient"><i class="fas fa-file-pdf"></i></span>
                            <div class="info-box-content">
                              <span
                                class="info-box-text text-truncate font-weight-bold small">{{ $dokumen->nama_file }}</span>
                              <span class="info-box-number mt-1">
                                <a href="{{ asset('storage/' . $dokumen->path) }}" target="_blank"
                                  class="btn btn-xs btn-primary px-3 shadow-sm font-weight-bold">
                                  <i class="fas fa-external-link-alt mr-1"></i> Buka File
                                </a>
                              </span>
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="col-12 text-center py-5 text-muted bg-light rounded"
                          style="border: 2px dashed #dee2e6;">
                          <i class="fas fa-cloud-upload-alt fa-3x mb-3 opacity-50"></i>
                          <p class="mb-0 font-weight-bold">Belum ada dokumen yang diunggah.</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-diskusi" role="tabpanel">
                <div class="card direct-chat direct-chat-primary shadow-none border-0 mb-0">
                  <div class="card-body">
                    <div class="direct-chat-messages" id="chat-container" style="height: 400px !important;">
                      @forelse($perizinan->discussions as $chat)
                        <div class="direct-chat-msg {{ $chat->user_id == Auth::id() ? 'right' : '' }} mb-4">
                          <div class="direct-chat-infos clearfix">
                            <span
                              class="direct-chat-name {{ $chat->user_id == Auth::id() ? 'float-right' : 'float-left' }} font-weight-bold small text-uppercase">{{ $chat->user->name }}</span>
                            <span
                              class="direct-chat-timestamp {{ $chat->user_id == Auth::id() ? 'float-left' : 'float-right' }} small">{{ $chat->created_at->format('H:i, d M') }}</span>
                          </div>
                          <div
                            class="direct-chat-text shadow-sm border-0 py-2 px-3 {{ $chat->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark border' }}"
                            style="border-radius: 12px;">
                            {{ $chat->message }}
                          </div>
                        </div>
                      @empty
                        <div
                          class="h-100 d-flex flex-column align-items-center justify-content-center opacity-25 italic py-5">
                          <i class="fas fa-comments fa-4x mb-3"></i>
                          <p class="font-weight-bold h5">Belum ada diskusi.</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                  <div class="card-footer bg-white border-top px-0 pt-4 pb-0">
                    <form action="{{ route('super_admin.perizinan.discussion.store', $perizinan) }}" method="POST">
                      @csrf
                      <div class="input-group">
                        <input type="text" name="message" id="discussion-input"
                          placeholder="Tulis catatan atau pertanyaan ke lembaga..."
                          class="form-control form-control-lg border-primary" required>
                        <span class="input-group-append">
                          <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-paper-plane mr-1"></i> KIRIM
                          </button>
                        </span>
                      </div>
                    </form>

                    <div class="mt-3 d-flex flex-wrap gap-2">
                      <button type="button" onclick="insertQuickText('Dokumen tidak lengkap, mohon periksa kembali.')"
                        class="btn btn-xs btn-outline-secondary mr-2 mb-2 px-3">Dokumen tidak lengkap</button>
                      <button type="button" onclick="insertQuickText('File tidak terbaca / pecah, mohon unggah ulang.')"
                        class="btn btn-xs btn-outline-secondary mr-2 mb-2 px-3">File tidak terbaca</button>
                      <button type="button" onclick="insertQuickText('Mohon tunggu, proses verifikasi sedang berjalan.')"
                        class="btn btn-xs btn-outline-secondary mr-2 mb-2 px-3">Sedang verifikasi</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-{{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() }} card-outline shadow-sm">
          <div class="card-header">
            <h3 class="card-title font-weight-bold"><i class="fas fa-tasks mr-2"></i> Tindakan Verifikasi</h3>
          </div>
          <div class="card-body">
            <div class="text-center mb-4 py-3 bg-light rounded border">
              <label class="text-muted small text-uppercase font-weight-bold d-block mb-2">Status Saat Ini</label>
              <span
                class="badge badge-{{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() }} px-4 py-2 text-uppercase font-weight-bold"
                style="font-size: 14px;">
                <i class="fas fa-info-circle mr-1"></i>
                {{ \App\Enums\PerizinanStatus::from($perizinan->status)->label() }}
              </span>
            </div>

            @if($perizinan->status === \App\Enums\PerizinanStatus::DIAJUKAN->value)
              <div class="alert alert-info border-0 shadow-none small mb-4">
                <i class="fas fa-info-circle mr-2"></i> Pengajuan ini menanti verifikasi administratif dari Anda.
              </div>
              <div class="space-y-3">
                <form action="{{ route('super_admin.perizinan.approve', $perizinan) }}" method="POST" class="mb-2">
                  @csrf
                  <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm"
                    onclick="return confirm('Apakah Anda yakin dokumen sudah lengkap dan menyetujui pengajuan ini untuk diproses lebih lanjut?')">
                    <i class="fas fa-check-double mr-2"></i> SETUJUI PENGAJUAN
                  </button>
                </form>

                <button type="button" class="btn btn-warning btn-block font-weight-bold text-dark" data-toggle="modal"
                  data-target="#modalRevision">
                  <i class="fas fa-exclamation-triangle mr-2"></i> PERLU PERBAIKAN
                </button>

                <form action="{{ route('super_admin.perizinan.reject', $perizinan) }}" method="POST" class="mt-2">
                  @csrf
                  <button type="submit" class="btn btn-danger btn-block font-weight-bold"
                    onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                    <i class="fas fa-times-circle mr-2"></i> TOLAK PERIZINAN
                  </button>
                </form>
              </div>
            @else
              @php $canFinalize = in_array($perizinan->status, [\App\Enums\PerizinanStatus::DISETUJUI->value, \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, \App\Enums\PerizinanStatus::SELESAI->value]); @endphp
              @if($canFinalize)
                <a href="{{ route('super_admin.perizinan.finalisasi', $perizinan) }}"
                  class="btn btn-primary btn-lg btn-block font-weight-bold shadow-lg py-3">
                  <i class="fas fa-file-signature mr-2"></i>
                  {{ $perizinan->status === \App\Enums\PerizinanStatus::DISETUJUI->value ? 'LANJUT KE FINALISASI' : 'EDIT FINALISASI' }}
                </a>

                <button type="button" class="btn btn-outline-secondary btn-block mt-3" onclick="openPrintDraftModal()">
                  <i class="fas fa-print mr-1"></i> Pratinjau Draft
                </button>
              @endif

              <div class="mt-4 p-3 bg-light rounded small border">
                <p class="mb-1 text-muted font-italic"><i class="fas fa-clock mr-1"></i> Riwayat Verifikasi:</p>
                <span class="text-dark font-weight-bold">Diverifikasi oleh {{ Auth::user()->name }} pada
                  {{ $perizinan->updated_at->format('d/m/y H:i') }}</span>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalRevision" tabindex="-1" role="dialog" aria-labelledby="modalRevisionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
        <div class="modal-header bg-warning py-3">
          <h5 class="modal-title font-weight-bold" id="modalRevisionLabel"><i class="fas fa-pen-nib mr-2"></i> Catatan
            Perbaikan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('super_admin.perizinan.revision', $perizinan) }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="form-group">
              <label class="text-xs font-bold text-muted text-uppercase tracking-wider mb-2 d-block">Pesan untuk
                Lembaga</label>
              <textarea name="catatan" rows="5" class="form-control font-weight-bold"
                placeholder="Contoh: Lampiran Akta Notaris terpotong, mohon unggah ulang versi lengkap..."
                required></textarea>
            </div>
            <div class="alert alert-light border small text-muted px-3 py-2">
              <i class="fas fa-info-circle mr-1"></i> Pesan ini akan langsung tampil di dashboard lembaga pemohon.
            </div>
          </div>
          <div class="modal-footer bg-light px-4">
            <button type="button" class="btn btn-light font-weight-bold px-4" data-dismiss="modal">BATAL</button>
            <button type="submit" class="btn btn-warning font-weight-bold px-4 text-dark shadow-sm">KIRIM CATATAN</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalPrintDraft" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content bg-light">
        <div class="modal-header bg-white shadow-sm py-3 px-4 position-sticky" style="top: 0; z-index: 10;">
          <div>
            <h5 class="modal-title font-weight-bold text-dark text-uppercase">
              <i class="fas fa-print mr-2 text-primary"></i> Pratinjau Draft Sertifikat
            </h5>
            <p class="mb-0 small text-muted font-weight-bold uppercase tracking-widest">
              Dokumen ini belum bersifat resmi (Watermarked) - {{ $paperSize }} {{ strtoupper($orientation) }}
            </p>
          </div>
          <div class="ml-auto d-flex align-items-center">
            <div class="btn-group mr-3 border rounded shadow-sm">
              <button type="button" class="btn btn-light btn-sm" onclick="zoomDraft(-0.1)"><i
                  class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-light btn-sm disabled font-weight-bold px-3"
                id="draft-zoom-level">80%</button>
              <button type="button" class="btn btn-light btn-sm" onclick="zoomDraft(0.1)"><i
                  class="fas fa-plus"></i></button>
            </div>
            <button type="button" class="btn btn-tool" data-dismiss="modal">
              <i class="fas fa-times fa-lg"></i>
            </button>
          </div>
        </div>
        <div class="modal-body p-5 d-flex justify-content-center bg-gray-dark" style="overflow-x: auto;">

          <div id="draft-preview-canvas" style="transform: scale(0.8);">
            <div
              class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center opacity-1 rotate-45 select-none pointer-events-none"
              style="z-index: 99; color: rgba(200,200,200,0.3); font-size: 80px; font-weight: 900; top:0; left:0;">
              WADAH DRAFT
            </div>

            {!! $perizinan->rendered_template !!}
          </div>

        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      let draftZoom = 0.8;

      function insertQuickText(text) {
        document.getElementById('discussion-input').value = text;
        document.getElementById('discussion-input').focus();
      }

      function openPrintDraftModal() {
        $('#modalPrintDraft').modal('show');
      }

      function zoomDraft(delta) {
        draftZoom = Math.min(Math.max(draftZoom + delta, 0.4), 1.5);
        const canvas = document.getElementById('draft-preview-canvas');
        canvas.style.transform = `scale(${draftZoom})`;
        document.getElementById('draft-zoom-level').innerText = `${Math.round(draftZoom * 100)}%`;
      }

      // Auto scroll chat
      $(document).ready(function () {
        var chatBox = document.getElementById('chat-container');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
      });
    </script>
  @endpush
@endsection