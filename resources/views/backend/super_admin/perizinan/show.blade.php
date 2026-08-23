@extends('layouts.backend')

@section('title', 'Verifikasi Perizinan')
@section('breadcrumb', 'Verifikasi Administratif')

@php
  // AMBIL PENGATURAN PRESET AKTIF UNTUK UKURAN KERTAS & PADDING
  $activePreset = \App\Models\CetakPreset::where('dinas_id', $perizinan->dinas_id)
    ->where('is_active', true)
    ->first();

  // Prioritaskan jenis perizinan, jika kosong baru ambil dari preset
  $paperSize = strtoupper($perizinan->jenisPerizinan->paper_size ?: ($activePreset->paper_size ?? 'A4'));
  $orientation = strtolower($perizinan->jenisPerizinan->orientation ?: ($activePreset->orientation ?? 'portrait'));

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
    /* Premium UI Overrides */
    .premium-card {
      border-radius: 12px;
      border: none;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
      background: #ffffff;
    }
    .premium-card .card-header {
      background: #ffffff;
      border-bottom: 1px solid #f1f3f5;
      padding: 1.5rem 1.5rem;
    }
    .btn-action {
      border-radius: 8px;
      transition: all 0.2s ease;
      font-weight: 600;
    }
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .badge-soft-success { background-color: #d1e7dd; color: #0f5132; font-weight: 600; }
    .badge-soft-info { background-color: #cff4fc; color: #055160; font-weight: 600; }
    .badge-soft-warning { background-color: #fff3cd; color: #856404; font-weight: 600; }
    .badge-soft-danger { background-color: #f8d7da; color: #842029; font-weight: 600; }
    .badge-soft-secondary { background-color: #e9ecef; color: #495057; font-weight: 600; }

    /* CSS FIX UNTUK KANVAS PRATINJAU DRAFT */
    #draft-preview-iframe {
      width: {{ $width }};
      height: {{ $height }};
      margin: 0 auto;
      border: none;
      background: white;
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
      transform-origin: top center;
      transition: transform 0.3s ease;
      display: block;
    }
    
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
    
    .nav-tabs .nav-link { font-weight: 600; padding: 1rem 1.5rem; color: #6c757d; border: none; border-bottom: 3px solid transparent; }
    .nav-tabs .nav-link.active { color: #0d6efd; background: transparent; border-bottom: 3px solid #0d6efd; }
    .nav-tabs .nav-link:hover:not(.active) { border-bottom: 3px solid #dee2e6; }
  </style>
@endpush

@section('content')
  <div class="content pb-4 text-dark">
    <div class="container-fluid">
      
      <!-- Header -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 mt-2">
        <div>
          <h2 class="h4 font-weight-bold mb-1 text-dark">Detail Verifikasi Perizinan</h2>
          <p class="text-muted mb-0">Tinjau kelengkapan dokumen dan form pengajuan izin lembaga.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ route('super_admin.perizinan.index') }}" class="btn btn-light border btn-action">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8 mb-4">
          <div class="card premium-card">
            <div class="card-header p-0 bg-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px; overflow: hidden;">
              <ul class="nav nav-tabs px-3 pt-2" id="perizinan-tabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="data-lembaga-tab" data-toggle="pill" href="#tab-data-lembaga" role="tab">
                    <i class="fas fa-file-invoice mr-1"></i> Berkas & Informasi
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="diskusi-tab" data-toggle="pill" href="#tab-diskusi" role="tab">
                    <i class="fas fa-comments mr-1"></i> Diskusi
                    @if($perizinan->discussions->count() > 0)
                      <span class="badge badge-danger ml-1 rounded-pill">{{ $perizinan->discussions->count() }}</span>
                    @endif
                  </a>
                </li>
              </ul>
            </div>
            
            <div class="card-body p-4 bg-light">
              <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-data-lembaga" role="tabpanel">
                  
                  <!-- Identitas Pengajuan -->
                  <div class="card premium-card mb-4 border border-light shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                      <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-id-card mr-2 text-primary"></i> Identitas Pengajuan</h5>
                      <a href="{{ route('super_admin.lembaga.show', $perizinan->lembaga) }}" class="btn btn-sm btn-light border btn-action text-primary">
                        <i class="fas fa-external-link-alt mr-1"></i> Profil Lembaga
                      </a>
                    </div>
                    <div class="card-body p-4">
                      <div class="row">
                        <div class="col-sm-6 mb-4">
                          <label class="text-muted small text-uppercase font-weight-bold mb-1">Nama Lembaga</label>
                          <p class="mb-0 font-weight-bold text-dark h6">{{ $perizinan->lembaga->nama_lembaga }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                          <label class="text-muted small text-uppercase font-weight-bold mb-1">NPSN</label>
                          <p class="mb-0 font-weight-bold text-dark h6">
                            <span class="badge badge-light border text-dark px-2 py-1">{{ $perizinan->lembaga->npsn }}</span>
                          </p>
                        </div>
                        <div class="col-sm-6 mb-4">
                          <label class="text-muted small text-uppercase font-weight-bold mb-1">Jenis Izin</label>
                          <p class="mb-0 font-weight-bold text-primary h6">{{ $perizinan->jenisPerizinan->nama }}</p>
                        </div>
                        <div class="col-sm-6 mb-4">
                          <label class="text-muted small text-uppercase font-weight-bold mb-1">Nomor Surat</label>
                          <p class="mb-0 font-weight-bold text-dark h6">{{ $perizinan->nomor_surat ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-4">
                          <label class="text-muted small text-uppercase font-weight-bold mb-1">Alamat Lengkap</label>
                          <p class="mb-0 text-dark p-3 bg-light rounded border">{{ $perizinan->lembaga->alamat }}</p>
                        </div>
                        <div class="col-sm-12 d-flex align-items-center">
                          <label class="text-muted small text-uppercase font-weight-bold mb-0 mr-3">Jenjang:</label>
                          <span class="badge badge-secondary mr-3">{{ $perizinan->lembaga->jenjang }}</span>
                          <label class="text-muted small text-uppercase font-weight-bold mb-0 mr-3">Diajukan Pada:</label>
                          <span class="text-dark font-weight-bold small"><i class="far fa-calendar-alt mr-1"></i> {{ $perizinan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Data Informasi Tambahan (Formulir Dinamis) -->
                  @if(!empty($perizinan->perizinan_data) && is_array($perizinan->perizinan_data))
                  <div class="card premium-card mb-4 border border-light shadow-sm">
                    <div class="card-header bg-white py-3">
                      <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-list-alt mr-2 text-success"></i> Data Informasi Tambahan (Formulir)</h5>
                    </div>
                    <div class="card-body p-4 bg-light">
                      <div class="row">
                        @foreach($perizinan->perizinan_data as $key => $val)
                          <div class="col-sm-6 mb-3">
                            <label class="text-muted small text-uppercase font-weight-bold mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                            <div class="p-2 bg-white border rounded shadow-sm font-weight-bold text-dark">
                              {{ is_array($val) ? json_encode($val) : ($val ?: '-') }}
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  @endif

                  <!-- Dokumen Persyaratan -->
                  <div class="card premium-card mb-0 border border-light shadow-sm">
                    <div class="card-header bg-white py-3">
                      <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-file-pdf mr-2 text-danger"></i> Dokumen Persyaratan & Berkas</h5>
                    </div>
                    <div class="card-body p-4 bg-light">
                      <div class="row">
                        @forelse($perizinan->dokumens as $dokumen)
                          <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-white border rounded shadow-sm">
                              <div class="mr-3 bg-danger-light rounded p-2 text-danger" style="background: #f8d7da;">
                                <i class="fas fa-file-pdf fa-2x"></i>
                              </div>
                              <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-1 font-weight-bold text-dark text-truncate" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</h6>
                                <a href="{{ asset('storage/' . $dokumen->path) }}" target="_blank" class="btn btn-xs btn-outline-primary font-weight-bold px-3">
                                  <i class="fas fa-external-link-alt mr-1"></i> Buka File
                                </a>
                              </div>
                            </div>
                          </div>
                        @empty
                          <div class="col-12 text-center py-5 bg-white rounded shadow-sm" style="border: 2px dashed #dee2e6;">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted opacity-50"></i>
                            <p class="mb-0 font-weight-bold text-muted">Belum ada dokumen persyaratan yang diunggah.</p>
                          </div>
                        @endforelse
                      </div>
                    </div>
                  </div>

                </div>

                <div class="tab-pane fade" id="tab-diskusi" role="tabpanel">
                  <div class="card premium-card shadow-sm border-0 mb-0">
                    <div class="card-body p-0">
                      <div class="direct-chat-messages p-4 bg-light" id="chat-container" style="height: 500px !important;">
                        @forelse($perizinan->discussions as $chat)
                          <div class="direct-chat-msg {{ $chat->user_id == Auth::id() ? 'right' : '' }} mb-4">
                            <div class="direct-chat-infos clearfix mb-1">
                              <span class="direct-chat-name {{ $chat->user_id == Auth::id() ? 'float-right' : 'float-left' }} font-weight-bold small text-uppercase">{{ $chat->user->name }}</span>
                              <span class="direct-chat-timestamp {{ $chat->user_id == Auth::id() ? 'float-left' : 'float-right' }} small">{{ $chat->created_at->format('H:i, d M') }}</span>
                            </div>
                            <div class="direct-chat-text shadow-sm border-0 py-2 px-3 {{ $chat->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark border' }}" style="border-radius: 12px; font-weight: 500;">
                              {{ $chat->message }}
                            </div>
                          </div>
                        @empty
                          <div class="h-100 d-flex flex-column align-items-center justify-content-center opacity-50 py-5">
                            <i class="fas fa-comments fa-4x mb-3 text-muted"></i>
                            <p class="font-weight-bold h5 text-muted">Belum ada diskusi.</p>
                          </div>
                        @endforelse
                      </div>
                    </div>
                    <div class="card-footer bg-white border-top p-4">
                      <form action="{{ route('super_admin.perizinan.discussion.store', $perizinan) }}" method="POST">
                        @csrf
                        <div class="input-group">
                          <input type="text" name="message" id="discussion-input" placeholder="Tulis catatan atau pertanyaan ke lembaga..." class="form-control form-control-lg border-primary bg-light" required>
                          <span class="input-group-append">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                              <i class="fas fa-paper-plane mr-1"></i> KIRIM
                            </button>
                          </span>
                        </div>
                      </form>

                      <div class="mt-3 d-flex flex-wrap gap-2">
                        <button type="button" onclick="insertQuickText('Dokumen tidak lengkap, mohon periksa kembali.')" class="btn btn-sm btn-light border mr-2 mb-2 font-weight-bold text-muted">Dokumen tidak lengkap</button>
                        <button type="button" onclick="insertQuickText('File tidak terbaca / buram, mohon unggah ulang resolusi yang jelas.')" class="btn btn-sm btn-light border mr-2 mb-2 font-weight-bold text-muted">File tidak terbaca</button>
                        <button type="button" onclick="insertQuickText('Mohon tunggu, proses verifikasi sedang berjalan.')" class="btn btn-sm btn-light border mr-2 mb-2 font-weight-bold text-muted">Sedang verifikasi</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 mb-4">
          @php
            $statusEnum = \App\Enums\PerizinanStatus::from($perizinan->status);
            $badgeColorClass = match ($statusEnum->value) {
              'diajukan' => 'badge-soft-warning',
              'disetujui' => 'badge-soft-success',
              'revisi' => 'badge-soft-danger',
              'siap_diambil' => 'badge-soft-info',
              'selesai' => 'badge-soft-secondary',
              default => 'badge-soft-secondary'
            };
          @endphp
          
          <div class="card premium-card shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-white py-4 text-center border-bottom-0">
              <h5 class="font-weight-bold mb-3 text-dark">Tindakan Verifikasi</h5>
              <div class="py-2 bg-light rounded border">
                <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Status Saat Ini</span>
                <span class="badge {{ $badgeColorClass }} px-4 py-2 text-uppercase rounded-pill" style="font-size: 0.9rem;">
                  <i class="fas fa-info-circle mr-1"></i> {{ $statusEnum->label() }}
                </span>
              </div>
            </div>
            
            <div class="card-body bg-white pt-2 pb-4 px-4">
              @if($perizinan->status === \App\Enums\PerizinanStatus::DIAJUKAN->value)
                <div class="alert alert-light border small text-muted mb-4 p-3 rounded">
                  <i class="fas fa-exclamation-circle text-warning mr-2 fa-lg"></i> Pengajuan ini menanti keputusan verifikasi administratif dari Anda.
                </div>
                
                <div class="d-flex flex-column gap-3">
                  <button type="button" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm btn-action mb-3" data-toggle="modal" data-target="#modalApprove">
                    <i class="fas fa-check-double mr-2"></i> SETUJUI PENGAJUAN
                  </button>

                  <button type="button" class="btn btn-warning btn-block font-weight-bold text-dark btn-action mb-3" data-toggle="modal" data-target="#modalRevision">
                    <i class="fas fa-pen-nib mr-2"></i> PERLU PERBAIKAN (REVISI)
                  </button>

                  <button type="button" class="btn btn-outline-danger btn-block font-weight-bold btn-action" data-toggle="modal" data-target="#modalReject">
                    <i class="fas fa-times-circle mr-2"></i> TOLAK PERIZINAN
                  </button>
                </div>
              @else
                @php 
                  $canFinalize = in_array($perizinan->status, [
                    \App\Enums\PerizinanStatus::DISETUJUI->value, 
                    \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, 
                    \App\Enums\PerizinanStatus::SELESAI->value
                  ]); 
                @endphp
                
                @if($canFinalize)
                  <a href="{{ route('super_admin.perizinan.finalisasi', $perizinan) }}" class="btn btn-primary btn-lg btn-block font-weight-bold shadow-sm py-3 btn-action mb-3">
                    <i class="fas fa-file-signature mr-2"></i>
                    {{ $perizinan->status === \App\Enums\PerizinanStatus::DISETUJUI->value ? 'LANJUT KE FINALISASI' : 'EDIT FINALISASI' }}
                  </a>

                  <button type="button" class="btn btn-light border btn-block font-weight-bold btn-action text-dark" onclick="openPrintDraftModal()">
                    <i class="fas fa-print mr-2 text-primary"></i> Pratinjau Dokumen Draft
                  </button>
                @endif

                <div class="mt-4 p-3 bg-light rounded small border">
                  <p class="mb-1 text-muted font-italic"><i class="fas fa-clock mr-1"></i> Riwayat Verifikasi:</p>
                  <span class="text-dark font-weight-bold d-block">
                    Oleh {{ Auth::user()->name }}
                  </span>
                  <span class="text-muted">{{ $perizinan->updated_at->format('d M Y, H:i') }}</span>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div class="modal fade" id="modalRevision" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
        <div class="modal-header bg-warning py-3">
          <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-pen-nib mr-2"></i> Catatan Perbaikan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('super_admin.perizinan.revision', $perizinan) }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="form-group mb-0">
              <label class="text-xs font-bold text-muted text-uppercase tracking-wider mb-2 d-block">Pesan untuk Lembaga</label>
              <textarea name="catatan" rows="5" class="form-control font-weight-bold bg-light" placeholder="Contoh: Lampiran Akta Notaris terpotong, mohon unggah ulang versi lengkap..." required></textarea>
            </div>
            <div class="alert alert-light border small text-muted mt-3 mb-0 px-3 py-2">
              <i class="fas fa-info-circle mr-1"></i> Pesan ini akan langsung tampil di dashboard lembaga.
            </div>
          </div>
          <div class="modal-footer bg-light px-4 border-0">
            <button type="button" class="btn btn-light font-weight-bold px-4 border" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning font-weight-bold px-4 text-dark shadow-sm">Kirim Catatan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalApprove" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
        <div class="modal-header bg-success text-white py-3">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle mr-2"></i> Setujui Pengajuan</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('super_admin.perizinan.approve', $perizinan) }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="form-group mb-0">
              <label class="font-weight-bold text-muted small text-uppercase mb-2">Catatan Persetujuan (Opsional)</label>
              <textarea name="catatan" class="form-control bg-light" rows="4" placeholder="Contoh: Dokumen lengkap, sedang proses penomoran surat.">Disetujui oleh Dinas</textarea>
            </div>
          </div>
          <div class="modal-footer bg-light px-4 border-0 py-3">
            <button type="button" class="btn btn-light text-muted font-weight-bold px-4 border" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm">Setujui Sekarang</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
        <div class="modal-header bg-danger text-white py-3">
          <h5 class="modal-title font-weight-bold"><i class="fas fa-times-circle mr-2"></i> Tolak Pengajuan</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('super_admin.perizinan.reject', $perizinan) }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="form-group mb-0">
              <label class="font-weight-bold text-muted small text-uppercase mb-2">Alasan Penolakan</label>
              <textarea name="catatan" class="form-control bg-light" rows="4" required placeholder="Jelaskan secara detail alasan pengajuan ditolak..."></textarea>
            </div>
          </div>
          <div class="modal-footer bg-light px-4 border-0 py-3">
            <button type="button" class="btn btn-light text-muted font-weight-bold px-4 border" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger px-4 font-weight-bold shadow-sm">Tolak Secara Permanen</button>
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
              <i class="fas fa-print mr-2 text-primary"></i> Pratinjau Draft Dokumen
            </h5>
            <p class="mb-0 small text-muted font-weight-bold uppercase tracking-widest">
              Layout: {{ $paperSize }} {{ strtoupper($orientation) }} (Dokumen belum memiliki watermark/bingkai resmi)
            </p>
          </div>
          <div class="ml-auto d-flex align-items-center">
            <div class="btn-group mr-3 border rounded shadow-sm">
              <button type="button" class="btn btn-light btn-sm font-weight-bold px-3" onclick="zoomDraft(-0.1)"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-light btn-sm disabled font-weight-bold px-3 text-dark" id="draft-zoom-level">80%</button>
              <button type="button" class="btn btn-light btn-sm font-weight-bold px-3" onclick="zoomDraft(0.1)"><i class="fas fa-plus"></i></button>
            </div>
            <button type="button" class="btn btn-light border rounded shadow-sm" data-dismiss="modal">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="modal-body p-5 d-flex justify-content-center bg-gray-dark" style="overflow-x: auto;">
          <iframe id="draft-preview-iframe" style="transform: scale(0.8);"></iframe>
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
        const iframe = document.getElementById('draft-preview-iframe');
        
        if (!iframe.srcdoc && !iframe.src) {
          iframe.srcdoc = `
            <div style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif; color:#666;">
              <p>Memuat pratinjau dokumen...</p>
            </div>
          `;
          
          fetch("{{ route('super_admin.penerbitan.preview', $perizinan) }}")
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Tambahkan watermark DRAFT ke dalam HTML yang di-render
                let html = data.html;
                const draftWatermark = `<div style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%) rotate(-45deg); font-size:120px; font-weight:900; color:rgba(200,200,200,0.3); z-index:9999; pointer-events:none; text-transform:uppercase;">DRAFT</div>`;
                html = html.replace('</body>', draftWatermark + '</body>');
                iframe.srcdoc = html;
              } else {
                iframe.srcdoc = `<p style="color:red; text-align:center; margin-top:20px;">Gagal memuat pratinjau.</p>`;
              }
            })
            .catch(err => {
              iframe.srcdoc = `<p style="color:red; text-align:center; margin-top:20px;">Error: ${err.message}</p>`;
            });
        }
      }

      function zoomDraft(delta) {
        draftZoom = Math.min(Math.max(draftZoom + delta, 0.4), 1.5);
        const iframe = document.getElementById('draft-preview-iframe');
        iframe.style.transform = `scale(${draftZoom})`;
        document.getElementById('draft-zoom-level').innerText = `${Math.round(draftZoom * 100)}%`;
      }

      // Auto scroll chat
      $(document).ready(function () {
        var chatBox = document.getElementById('chat-container');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
      });

      function insertQuickText(text) {
        const textarea = document.querySelector('input[name="message"]');
        if (textarea) {
          textarea.value = text;
        }
      }
    </script>
  @endpush
@endsection