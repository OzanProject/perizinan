@extends('layouts.admin_lembaga')

@section('title', 'Detail Pengajuan - ' . $perizinan->jenisPerizinan->nama)

@section('content')
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ route('admin_lembaga.perizinan.index') }}" class="btn btn-default shadow-sm border">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
            </a>
        </div>

        <!-- Timeline Section -->
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-route mr-2 text-primary"></i> Tracking Status Pengajuan
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $steps = [
                            ['id' => 'draft', 'label' => 'Diajukan', 'icon' => 'fa-paper-plane', 'desc' => 'Pendaftaran Awal'],
                            ['id' => 'diajukan', 'label' => 'Verifikasi', 'icon' => 'fa-search', 'desc' => 'Cek Administratif'],
                            ['id' => 'disetujui', 'label' => 'Persetujuan', 'icon' => 'fa-check-double', 'desc' => 'Finalisasi SK'],
                            ['id' => 'siap_diambil', 'label' => 'Siap Diambil', 'icon' => 'fa-box-open', 'desc' => 'SK Siap Diambil'],
                            ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'fa-flag-checkered', 'desc' => 'Izin Terbit'],
                        ];
                        $statusValue = $perizinan->status;
                        $currentIndex = 0;
                        foreach ($steps as $i => $step) {
                            if ($statusValue == $step['id'])
                                $currentIndex = $i;
                        }
                    @endphp

                    <!-- Professional Step Tracker -->
                    <div class="d-flex justify-content-between position-relative pb-4 mt-2">
                        <div class="position-absolute w-100"
                            style="height: 2px; background: #e9ecef; top: 20px; z-index: 0;"></div>
                        <div class="position-absolute"
                            style="height: 2px; background: #007bff; top: 20px; z-index: 0; transition: width 0.5s; width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%;">
                        </div>

                        @foreach($steps as $i => $step)
                            <div class="text-center position-relative" style="z-index: 1; width: 100px;">
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center border"
                                    style="width: 40px; height: 40px; background: {{ $i <= $currentIndex ? '#007bff' : '#fff' }}; border-color: {{ $i <= $currentIndex ? '#007bff' : '#dee2e6' }} !important;">
                                    <i class="fas {{ $step['icon'] }} {{ $i <= $currentIndex ? 'text-white' : 'text-muted' }}"
                                        style="font-size: 14px;"></i>
                                </div>
                                <div class="mt-2">
                                    <p
                                        class="mb-0 font-weight-bold small {{ $i <= $currentIndex ? 'text-primary' : 'text-muted' }}">
                                        {{ $step['label'] }}</p>
                                    <small class="text-muted d-none d-md-block"
                                        style="font-size: 10px;">{{ $step['desc'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @if($statusValue == 'perbaikan')
            <div class="col-md-12">
                <div class="callout callout-warning bg-light border-warning shadow-sm mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3"></i>
                        <h5 class="mb-0 font-weight-bold text-warning">Catatan Perbaikan dari Dinas</h5>
                    </div>
                    <p class="mb-3">Mohon segera lengkapi atau perbaiki berkas sesuai instruksi di bawah ini agar proses
                        verifikasi dapat dilanjutkan.</p>

                    <div class="bg-white p-3 rounded border mb-3">
                        @php
                            $latestAdminMsg = $perizinan->discussions->where('user_id', '!=', Auth::id())->last();
                        @endphp
                        @if($latestAdminMsg)
                            <div class="media">
                                <i class="fas fa-info-circle text-info mt-1 mr-3"></i>
                                <div class="media-body">
                                    <p class="mb-1 font-weight-bold">Arahan Verifikator ({{ $latestAdminMsg->user->name }}):</p>
                                    <p class="mb-0 text-muted font-italic font-weight-bold">"{{ $latestAdminMsg->message }}"</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted small mb-0 font-italic text-center">Cek detail instruksi pada panel diskusi di
                                bawah.</p>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin_lembaga.perizinan.edit', $perizinan) }}"
                            class="btn btn-warning shadow-sm font-weight-bold">
                            <i class="fas fa-upload mr-2"></i> Upload Berkas Perbaikan
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Left Column: Berkas -->
        <div class="col-lg-7">
            <div class="card card-outline card-info shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-folder-open mr-2 text-info"></i> Berkas
                        Terlampir</h3>
                    <div class="card-tools">
                        <span class="badge badge-info">{{ $perizinan->dokumens->count() }} Dokumen</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($perizinan->dokumens as $dokumen)
                            <div class="col-md-6 mb-3">
                                <div
                                    class="p-3 border rounded h-100 d-flex align-items-center hover-shadow transition-all bg-light">
                                    <i class="fas fa-file-pdf fa-2x text-danger mr-3"></i>
                                    <div class="flex-grow-1 min-width-0">
                                        <p class="mb-0 font-weight-bold text-sm text-truncate"
                                            title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                                        <small class="text-muted text-uppercase"
                                            style="font-size: 10px;">{{ $dokumen->created_at->translatedFormat('d M Y') }}</small>
                                    </div>
                                    <a href="{{ Storage::url($dokumen->path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary ml-2 rounded-circle" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions Footer Card -->
            <div class="card shadow">
                <div class="card-body p-4">
                    @if($statusValue == 'siap_diambil')
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="media mb-3 mb-md-0">
                                <div class="bg-blue shadow-sm rounded-circle d-flex align-items-center justify-content-center mr-3"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-info text-white"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="mb-0 font-weight-bold">SK Izin Siap Diambil</h6>
                                    <p class="mb-0 text-muted small">Konfirmasi di sini jika Anda sudah menerima dokumen fisik.
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin_lembaga.perizinan.confirm_taken', $perizinan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm font-weight-bold"
                                    onclick="return confirm('Apakah Anda yakin sudah menerima dokumen fisik SK?')">
                                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Diterima
                                </button>
                            </form>
                        </div>
                    @elseif($statusValue == 'disetujui' || $statusValue == 'selesai')
                        <div class="text-center py-2">
                            <button class="btn btn-success btn-lg px-5 shadow-sm font-weight-bold mr-2 mb-2">
                                <i class="fas fa-download mr-2"></i> Unduh Sertifikat
                            </button>
                            <button class="btn btn-outline-secondary btn-lg px-4 mb-2">
                                <i class="fas fa-print mr-2"></i> Print Tanda Terima
                            </button>
                        </div>
                    @elseif($statusValue == 'draft')
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 text-muted font-italic small">Pengajuan masih berupa draft.</p>
                            <form action="{{ route('admin_lembaga.perizinan.destroy', $perizinan) }}" method="POST"
                                onsubmit="return confirm('Hapus draft pengajuan ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash-alt mr-2"></i> Batalkan Pengajuan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-clock mr-2"></i> <span class="small font-italic">Pengajuan sedang diproses oleh tim
                                verifikasi Dinas.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Discussion -->
        <div class="col-lg-5">
            <div class="card direct-chat direct-chat-primary shadow">
                <div class="card-header border-bottom">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-comments mr-2 text-primary"></i> Diskusi
                        Layanan</h3>
                    <div class="card-tools">
                        <span class="badge badge-success"><i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                            Online</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="direct-chat-messages" style="height: 480px;" id="chat-messages">
                        @forelse($perizinan->discussions->sortBy('created_at') as $chat)
                            @php $isMe = $chat->user_id == Auth::id(); @endphp
                            <div class="direct-chat-msg @if($isMe) right @endif">
                                <div class="direct-chat-infos clearfix">
                                    <span
                                        class="direct-chat-name @if($isMe) float-right @else float-left @endif">{{ $isMe ? 'Anda' : $chat->user->name }}</span>
                                    <span
                                        class="direct-chat-timestamp @if($isMe) float-left @else float-right @endif">{{ $chat->created_at->translatedFormat('H:i, d M') }}</span>
                                </div>
                                <img class="direct-chat-img"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($chat->user->name) }}&color=7F9CF5&background=EBF4FF"
                                    alt="User Image">
                                <div class="direct-chat-text shadow-sm border-0">
                                    {!! nl2br(e($chat->message)) !!}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted opacity-50">
                                <i class="fas fa-comments fa-4x mb-3"></i>
                                <p>Belum ada diskusi. Kirim pertanyaan atau konfirmasi jika diperlukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin_lembaga.perizinan.discussion.store', $perizinan) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="message" id="chat-input" placeholder="Tulis pesan..."
                                class="form-control" autocomplete="off" required>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-2px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            var chatMessages = $('#chat-messages');
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        });
    </script>
@endpush