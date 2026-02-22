@extends('layouts.admin_lembaga')

@section('title', 'Lengkapi Pengajuan - ' . $perizinan->jenisPerizinan->nama)

@push('styles')
<style>
    .step-indicator {
        position: relative;
        z-index: 1;
    }
    .step-line {
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }
    .step-line-progress {
        position: absolute;
        top: 20px;
        left: 0;
        height: 2px;
        background: #007bff;
        z-index: 0;
        transition: width 0.5s ease;
    }
    .step-item {
        position: relative;
        z-index: 1;
        cursor: pointer;
        background: transparent;
        border: none;
        outline: none !important;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .active .step-circle {
        background: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    .completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: #fff;
    }
    [x-cloak] { display: none !important; }
    
    /* Direct Chat Extensions */
    #chat-panel {
        position: fixed;
        right: -450px;
        top: 0;
        bottom: 0;
        width: 450px;
        z-index: 1050;
        transition: right 0.4s ease;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }
    #chat-panel.open {
        right: 0;
    }
    @media (max-width: 575.98px) {
        #chat-panel { width: 100%; right: -100%; }
        #chat-panel.open { right: 0; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" x-data="wizard({{ $initialStep }})">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-9">
            <h1 class="h3 font-weight-bold text-dark mb-1">Proses Pengajuan Izin</h1>
            <p class="text-muted small mb-0">Silakan ikuti langkah-langkah di bawah untuk melengkapi berkas pengajuan <strong>{{ $perizinan->jenisPerizinan->nama }}</strong>.</p>
        </div>
        <div class="col-md-3 text-md-right mt-3 mt-md-0">
            <a href="{{ route('admin_lembaga.perizinan.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                <i class="fas fa-sign-out-alt mr-2"></i> Keluar Editor
            </a>
        </div>
    </div>

    <!-- Stepper -->
    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row position-relative px-lg-5">
                <div class="step-line"></div>
                <div class="step-line-progress" :style="'width: ' + progressWidth + '%'"></div>
                
                <!-- Step 1 -->
                <div class="col-4 text-center">
                    <button @click="goToStep(1)" class="step-item d-inline-flex flex-column align-items-center mx-auto" :class="step >= 1 ? (step > 1 ? 'completed' : 'active') : ''">
                        <div class="step-circle mb-2">
                           <template x-if="step > 1"><i class="fas fa-check"></i></template>
                           <template x-if="step <= 1"><span>1</span></template>
                        </div>
                        <span class="small font-weight-bold text-uppercase d-none d-sm-block" :class="step >= 1 ? 'text-dark' : 'text-muted'">Info Detail</span>
                    </button>
                </div>

                <!-- Step 2 -->
                <div class="col-4 text-center">
                    <button @click="goToStep(2)" class="step-item d-inline-flex flex-column align-items-center mx-auto" :class="step >= 2 ? (step > 2 ? 'completed' : 'active') : ''">
                        <div class="step-circle mb-2">
                            <template x-if="step > 2"><i class="fas fa-check"></i></template>
                            <template x-if="step <= 2"><span>2</span></template>
                        </div>
                        <span class="small font-weight-bold text-uppercase d-none d-sm-block" :class="step >= 2 ? 'text-dark' : 'text-muted'">Upload Berkas</span>
                    </button>
                </div>

                <!-- Step 3 -->
                <div class="col-4 text-center">
                    <button @click="goToStep(3)" class="step-item d-inline-flex flex-column align-items-center mx-auto" :class="step >= 3 ? 'active' : ''">
                        <div class="step-circle mb-2">
                            <span>3</span>
                        </div>
                        <span class="small font-weight-bold text-uppercase d-none d-sm-block" :class="step >= 3 ? 'text-dark' : 'text-muted'">Review & Kirim</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            
            <!-- STEP 1: INFORMASI DETAIL -->
            <div x-show="step === 1" class="mb-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-edit text-primary mr-2"></i> Lengkapi Informasi Pengajuan
                        </h3>
                    </div>
                    <form id="form-data-perizinan" action="{{ route('admin_lembaga.perizinan.update_data', $perizinan) }}" method="POST">
                        @csrf
                        <div class="card-body p-4">
                            <p class="text-muted small mb-4">Informasi di bawah ini akan digunakan sebagai data identitas pada sertifikat izin Anda.</p>
                            
                            <div class="row">
                                @if($perizinan->jenisPerizinan->form_config)
                                    @foreach($perizinan->jenisPerizinan->form_config as $field)
                                        <div class="col-md-{{ ($field['type'] ?? 'text') == 'textarea' ? '12' : '6' }} mb-3">
                                            <div class="form-group">
                                                <label class="text-uppercase small font-weight-bold text-muted mb-2 tracking-wider">
                                                    {{ $field['label'] }} @if($field['required'] ?? false) <span class="text-danger">*</span> @endif
                                                </label>
                                                @if(($field['type'] ?? 'text') == 'textarea')
                                                    <textarea name="data[{{ $field['name'] }}]" class="form-control form-control-lg font-weight-bold" rows="3" required>{{ $perizinan->perizinan_data[$field['name']] ?? '' }}</textarea>
                                                @else
                                                    <input type="{{ $field['type'] ?? 'text' }}" name="data[{{ $field['name'] }}]" value="{{ $perizinan->perizinan_data[$field['name']] ?? '' }}" class="form-control form-control-lg font-weight-bold" required>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-info-circle text-muted fa-3x mb-3"></i>
                                        <p class="text-muted font-italic">Tidak ada data tambahan yang diperlukan untuk jenis izin ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-light px-4 py-3 text-right">
                            <button type="button" @click="submitStep1()" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm">
                                Simpan & Lanjut <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- STEP 2: UPLOAD BERKAS -->
            <div x-show="step === 2" class="mb-4" x-cloak>
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-cloud-upload-alt text-primary mr-2"></i> Unggah Dokumen Persyaratan
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Pastikan dokumen hasil scan jelas dan terbaca. Format: PDF, JPG, PNG.</p>
                        
                        <div class="list-group list-group-flush border-top border-bottom">
                            @foreach($syarats as $syarat)
                                @php $dokumen = $uploadedDokumens->get($syarat->id); @endphp
                                <div class="list-group-item px-0 py-4 border-light">
                                    <div class="row align-items-start">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="font-weight-bold mb-0 mr-2">{{ $syarat->nama_dokumen }}</h6>
                                                @if($syarat->is_required)
                                                    <span class="badge badge-danger text-uppercase p-1 px-2" style="font-size: 9px;">Wajib</span>
                                                @endif
                                            </div>
                                            <p class="text-muted small mb-3">{{ $syarat->deskripsi ?? 'Silakan unggah dokumen yang sah untuk persyaratan ini.' }}</p>

                                            @if($dokumen)
                                                <div class="d-flex align-items-center p-3 rounded bg-light border border-success-light">
                                                    <div class="bg-white rounded p-2 mr-3 border shadow-sm">
                                                        <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                                    </div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <span class="d-block font-weight-bold text-truncate small">{{ $dokumen->nama_file }}</span>
                                                        <span class="text-success small font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Terunggah</span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <button @click="openFilePreview('{{ Storage::url($dokumen->path) }}', '{{ $dokumen->nama_file }}')" class="btn btn-sm btn-light border" title="Lihat">
                                                            <i class="fas fa-eye text-muted"></i>
                                                        </button>
                                                        <button @click="deleteFile({{ $perizinan->id }}, {{ $dokumen->id }}, {{ $syarat->id }})" class="btn btn-sm btn-light border ml-1" title="Hapus">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                            <input type="file" id="file-{{ $syarat->id }}" class="d-none" @change="uploadFile($event, {{ $syarat->id }})">
                                            <button type="button" @click="document.getElementById('file-{{ $syarat->id }}').click()" 
                                                class="btn btn-sm font-weight-bold text-uppercase px-3 py-2 shadow-sm"
                                                :class="{{ $dokumen ? 'btn-outline-secondary' : 'btn-primary' }}">
                                                <i class="fas {{ $dokumen ? 'fa-sync-alt' : 'fa-upload' }} mr-2"></i> {{ $dokumen ? 'Ganti' : 'Unggah' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-light px-4 py-3 d-flex justify-content-between">
                        <button type="button" @click="goToStep(1)" class="btn btn-outline-secondary font-weight-bold px-4">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                        <button type="button" @click="validateStep2()" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm">
                            Lanjut ke Review <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: REVIEW & SUBMIT -->
            <div x-show="step === 3" class="mb-4" x-cloak>
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-check-circle text-success mr-2"></i> Review & Konfirmasi Akhir
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Pastikan seluruh data dan berkas yang Anda unggah sudah benar dan sesuai asli.</p>
                        
                        <!-- Summary Detail -->
                        <div class="card bg-light border shadow-none mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    @if($perizinan->jenisPerizinan->form_config)
                                        @foreach($perizinan->jenisPerizinan->form_config as $field)
                                            <div class="col-md-6 mb-3">
                                                <label class="text-uppercase small font-weight-bold text-muted mb-1">{{ $field['label'] }}</label>
                                                <div class="font-weight-bold text-dark">{{ $perizinan->perizinan_data[$field['name']] ?? '-' }}</div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Document Status -->
                        <div class="mb-4">
                            <h6 class="text-uppercase small font-weight-bold text-muted mb-3">Ringkasan Dokumen</h6>
                            <div class="list-group list-group-sm rounded border">
                                @foreach($syarats as $syarat)
                                    @php $dokumen = $uploadedDokumens->get($syarat->id); @endphp
                                    <div class="list-group-item d-flex align-items-center justify-content-between py-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas {{ $dokumen ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} mr-2"></i>
                                            <span class="small font-weight-bold">{{ $syarat->nama_dokumen }}</span>
                                        </div>
                                        <span class="badge font-weight-bold text-uppercase {{ $dokumen ? 'text-success' : 'text-danger' }}" style="font-size: 8px;">
                                            {{ $dokumen ? 'Terlampir' : 'Kosong' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="alert alert-warning border shadow-none small d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle mt-1 mr-3"></i>
                            <div>
                                Dengan menekan tombol <strong>Ajukan Sekarang</strong>, data akan dikirim ke sistem verifikasi Super Admin dan tidak dapat diubah hingga ada catatan perbaikan.
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light px-4 py-4 d-flex flex-column flex-sm-row justify-content-between">
                        <button type="button" @click="goToStep(2)" class="btn btn-outline-secondary font-weight-bold px-4 mb-3 mb-sm-0">
                            Periksa Ulang Berkas
                        </button>
                        <form action="{{ route('admin_lembaga.perizinan.submit', $perizinan) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i> AJUKAN SEKARANG
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Components -->
        <div class="col-lg-4">
            <!-- Progress Circle Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold small text-uppercase tracking-wider">
                        <i class="fas fa-chart-pie text-primary mr-2"></i> Progres Persiapan
                    </h3>
                </div>
                <div class="card-body p-4 text-center">
                    @php
                        $requiredCount = $syarats->where('is_required', true)->count();
                        $uploadedCount = $uploadedDokumens->whereIn('syarat_perizinan_id', $syarats->where('is_required', true)->pluck('id'))->count();
                        $percentage = $requiredCount > 0 ? round(($uploadedCount / $requiredCount) * 100) : 100;
                    @endphp

                    <div class="position-relative d-inline-block mb-3">
                        <div class="progress-circle shadow-sm" style="width: 120px; height: 120px;">
                            <svg viewBox="0 0 36 36" class="w-100 h-100 rotate-n90">
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-light" stroke-width="3"></circle>
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-primary" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $percentage }}" style="transition: stroke-dashoffset 1s ease;"></circle>
                            </svg>
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top:0; left:0;">
                                <span class="h4 font-weight-bold mb-0">{{ $percentage }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row text-center mt-3">
                        <div class="col-6 border-right">
                            <div class="text-muted small uppercase font-weight-bold mb-1">Terunggah</div>
                            <div class="h6 font-weight-bold mb-0">{{ $uploadedCount }} Berkas</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small uppercase font-weight-bold mb-1">Kekurangan</div>
                            <div class="h6 font-weight-bold text-danger mb-0">{{ $requiredCount - $uploadedCount }} Berkas</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button @click="document.getElementById('chat-panel').classList.toggle('open')" class="btn btn-info btn-block py-2 font-weight-bold shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-comments mr-2"></i> BUKA DISKUSI</span>
                            @if($perizinan->discussions->count() > 0)
                                <span class="badge badge-light text-info">{{ $perizinan->discussions->count() }}</span>
                            @else
                                <i class="fas fa-chevron-right small"></i>
                            @endif
                        </div>
                    </button>
                </div>
            </div>

            <!-- Guidelines Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <h3 class="card-title font-weight-bold small text-uppercase">Tips Pengajuan</h3>
                </div>
                <div class="card-body p-4 pt-2">
                    <ul class="list-unstyled small space-y-3">
                        <li class="d-flex align-items-start border-bottom pb-2 mb-2">
                            <i class="fas fa-lightbulb text-warning mr-3 mt-1"></i>
                            <span>Gunakan file PDF hasil scan asli, bukan foto kamera HP jika memungkinkan agar lebih profesional.</span>
                        </li>
                        <li class="d-flex align-items-start border-bottom pb-2 mb-2">
                            <i class="fas fa-file-contract text-info mr-3 mt-1"></i>
                            <span>Satu jenis berkas harus digabung ke dalam satu file PDF jika terdiri dari beberapa halaman.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Chat Panel -->
    <div id="chat-panel" class="card direct-chat direct-chat-primary h-100 rounded-0 border-0">
        <div class="card-header rounded-0 bg-primary d-flex align-items-center py-3">
            <h3 class="card-title font-weight-bold text-white"><i class="fas fa-comments mr-2"></i> Pusat Diskusi</h3>
            <div class="card-tools ml-auto">
                <button type="button" class="btn btn-tool text-white" @click="document.getElementById('chat-panel').classList.remove('open')">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
        </div>
        <div class="card-body bg-light p-0">
            <div class="direct-chat-messages h-100 p-4" id="chat-box" style="height: calc(100vh - 150px) !important;">
                @forelse($perizinan->discussions as $chat)
                    <div class="direct-chat-msg {{ $chat->user_id == Auth::id() ? 'right' : '' }} mb-4">
                        <div class="direct-chat-infos clearfix mb-1">
                            <span class="direct-chat-name {{ $chat->user_id == Auth::id() ? 'float-right' : 'float-left' }} font-weight-bold small uppercase">{{ $chat->user->name }}</span>
                            <span class="direct-chat-timestamp {{ $chat->user_id == Auth::id() ? 'float-left' : 'float-right' }} small">{{ $chat->created_at->format('H:i, d M') }}</span>
                        </div>
                        <div class="direct-chat-text shadow-sm border-0 py-2 px-3 {{ $chat->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark' }}" style="border-radius: 12px; font-size: 13px;">
                            {{ $chat->message }}
                        </div>
                    </div>
                @empty
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center opacity-25 italic py-5">
                        <i class="fas fa-comment-dots fa-4x mb-3"></i>
                        <p class="font-weight-bold h5">Belum ada diskusi.</p>
                        <p class="small">Tanyakan kendala pengajuan Anda di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white border-top p-4">
            <form action="{{ route('admin_lembaga.perizinan.discussion.store', $perizinan) }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" placeholder="Tanyakan kendala Anda..." class="form-control form-control-lg border-light bg-light" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane px-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rotate-n90 { transform: rotate(-90deg); }
    .stroke-light { stroke: #f4f6f9; }
    .stroke-primary { stroke: #007bff; }
    .progress-circle svg { width: 100%; height: 100%; }
    .direct-chat-text:after, .direct-chat-text:before { border-right-color: transparent !important; border-left-color: transparent !important; }
    body.chat-open { overflow: hidden; }
</style>

<!-- Add AlpineJS -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function wizard(initialStep) {
        return {
            step: initialStep,
            progressWidth: 0,
            init() {
                this.updateProgress();
                // Scroll to bottom of chat if open or after load
                const chatBox = document.getElementById('chat-box');
                chatBox.scrollTop = chatBox.scrollHeight;
            },
            goToStep(n) {
                if (n < this.step) {
                    this.step = n;
                    this.updateProgress();
                    const url = new URL(window.location);
                    url.searchParams.set('step', n);
                    window.history.pushState({}, '', url);
                } else if (n === 2) {
                    this.step = 2;
                    this.updateProgress();
                } else if (n === 3) {
                    this.step = 3;
                    this.updateProgress();
                }
            },
            updateProgress() {
                this.progressWidth = ((this.step - 1) / 2) * 100;
            },
            submitStep1() {
                const form = document.getElementById('form-data-perizinan');
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(() => {
                    window.location.href = `{{ route('admin_lembaga.perizinan.edit', $perizinan) }}?step=2`;
                });
            },
            validateStep2() {
                this.step = 3;
                this.updateProgress();
                const url = new URL(window.location);
                url.searchParams.set('step', 3);
                window.history.pushState({}, '', url);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            uploadFile(event, syaratId) {
                const file = event.target.files[0];
                if (!file) return;
                const formData = new FormData();
                formData.append('file', file);
                formData.append('syarat_id', syaratId);
                formData.append('_token', '{{ csrf_token() }}');
                const btn = event.target.nextElementSibling;
                const originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> ...';
                fetch(`{{ route('admin_lembaga.perizinan.upload_dokumen', $perizinan) }}`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(async res => {
                    if (!res.ok) throw new Error('Gagal mengunggah file.');
                    return res.json();
                }).then(data => {
                    if (data.success) window.location.reload();
                    else { alert(data.message); btn.disabled = false; btn.innerHTML = originalContent; }
                }).catch(err => {
                    alert('Error: ' + err.message);
                    btn.disabled = false; btn.innerHTML = originalContent;
                });
            },
            deleteFile(pId, dId, sId) {
                if (!confirm('Hapus dokumen ini?')) return;
                fetch(`/admin-lembaga/perizinan/${pId}/dokumen/${dId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(async res => {
                    if (res.ok) window.location.reload();
                    else alert('Gagal menghapus dokumen.');
                });
            },
            openFilePreview(url, filename) {
                window.open(url, '_blank');
            }
        }
    }
</script>
@endsection

  <!-- Add AlpineJS for wizard logic -->
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
    function wizard(initialStep) {
      return {
        step: initialStep,
        progressWidth: 0,
        init() {
          this.updateProgress();
        },
        goToStep(n) {
          if (n < this.step) {
            this.step = n;
            this.updateProgress();
            // Update URL without reload for manual step clicks
            const url = new URL(window.location);
            url.searchParams.set('step', n);
            window.history.pushState({}, '', url);
          } else if (n === 2) {
            this.step = 2;
            this.updateProgress();
          } else if (n === 3) {
            this.step = 3;
            this.updateProgress();
          }
        },
        updateProgress() {
          this.progressWidth = ((this.step - 1) / 2) * 100;
        },
        submitStep1() {
          const form = document.getElementById('form-data-perizinan');
          const formData = new FormData(form);

          fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          }).then(() => {
            // Navigate to step 2 WITH reload to refresh information
            window.location.href = `{{ route('admin_lembaga.perizinan.edit', $perizinan) }}?step=2`;
          });
        },
        validateStep2() {
          // Navigate to step 3
          this.step = 3;
          this.updateProgress();
          const url = new URL(window.location);
          url.searchParams.set('step', 3);
          window.history.pushState({}, '', url);
          window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        uploadFile(event, syaratId) {
          const file = event.target.files[0];
          if (!file) return;

          const formData = new FormData();
          formData.append('file', file);
          formData.append('syarat_id', syaratId);
          formData.append('_token', '{{ csrf_token() }}');

          // Simple UX feedback - find the button which is the next sibling
          const btn = event.target.nextElementSibling;
          const originalContent = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '<span class="animate-spin material-symbols-outlined text-[18px]">sync</span> Uploading...';

          fetch(`{{ route('admin_lembaga.perizinan.upload_dokumen', $perizinan) }}`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
            .then(async res => {
              if (!res.ok) {
                const errData = await res.json();
                throw new Error(errData.message || 'Gagal mengunggah file.');
              }
              return res.json();
            })
            .then(data => {
              if (data.success) {
                // Reload current step to refresh file list
                window.location.reload();
              } else {
                alert(data.message);
                btn.disabled = false;
                btn.innerHTML = originalContent;
              }
            })
            .catch(err => {
              alert('Error: ' + err.message);
              btn.disabled = false;
              btn.innerHTML = originalContent;
            });
        },
        deleteFile(pId, dId, sId) {
          if (!confirm('Hapus dokumen ini?')) return;
          fetch(`/admin-lembaga/perizinan/${pId}/dokumen/${dId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
            .then(async res => {
              if (res.ok) {
                window.location.reload();
              } else {
                const data = await res.json();
                alert(data.message || 'Gagal menghapus dokumen.');
              }
            });
        },
        toggleChat() {
          const panel = document.getElementById('chat-panel');
          panel.classList.toggle('translate-x-full');
        },
        openFilePreview(url, filename) {
          // Use a simple window open for preview for now or custom modal if needed
          window.open(url, '_blank');
        }
      }
    }
  </script>

  <style>
    [x-cloak] {
      display: none !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 5px;
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
@endsection