@extends('layouts.backend')

@section('title', 'Finalisasi Sertifikat')

@php
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
@endphp

@push('styles')
    <style>
        .finalisasi-wrapper {
            display: flex;
            flex-direction: row;
            height: calc(100vh - 57px - 57px);
            /* Adjust based on header/footer height */
            overflow: hidden;
        }

        .left-panel {
            width: 400px;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid #dee2e6;
            padding: 24px;
            z-index: 10;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }

        .right-panel {
            flex: 1;
            overflow-y: auto;
            background: #f4f6f9;
            display: flex;
            flex-direction: column;
        }

        .preview-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px;
            background-color: #e9ecef;
            background-image: radial-gradient(#dee2e6 1px, transparent 1px);
            background-size: 20px 20px;
            overflow: auto;
        }

        #preview-paper {
            background: white;
            width: {{ $width }};
            min-height: {{ $height }};
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            transform-origin: top center;
            transition: transform 0.3s ease;
            position: relative;
            margin: 0 auto;
        }

        #certificate-canvas {
            position: relative;
            width: 100%;
            min-height: {{ $height }};
            background: white;
            padding: 0;
            margin: 0;
        }

        /* Force fixed elements in template (watermarks/borders) to be absolute within the preview canvas */
        #certificate-canvas div[style*="position: fixed"] {
            position: absolute !important;
        }

        @media (max-width: 991.98px) {
            .finalisasi-wrapper {
                flex-direction: column;
                height: auto;
                overflow: visible;
            }

            .left-panel {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
        }
    </style>
@endpush

@section('content')
    <form id="penerbitan-form" action="{{ route('super_admin.perizinan.release', $perizinan) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="finalisasi-wrapper">
            <!-- Left Panel: Form & Actions -->
            <div class="left-panel">
                <div class="mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb p-0 bg-transparent mb-2">
                            <li class="breadcrumb-item small"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item small"><a href="{{ route('super_admin.perizinan.index') }}">Antrian</a></li>
                            <li class="breadcrumb-item active small" aria-current="page">Finalisasi</li>
                        </ol>
                    </nav>
                    <h4 class="font-weight-bold mb-1">Review & Finalisasi</h4>
                    <p class="text-muted small">Verifikasi data dan terbitkan sertifikat resmi.</p>
                </div>

                <!-- Applicant Info Section -->
                <div class="card card-sm mb-4 shadow-sm border">
                    <div class="card-header bg-light py-2 px-3">
                        <h3 class="card-title text-uppercase font-weight-bold small mb-0">Informasi Pemohon</h3>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted py-1" width="100">Pemohon</td>
                                <td class="font-weight-bold py-1">{{ $perizinan->lembaga->nama_lembaga }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Jenis Izin</td>
                                <td class="font-weight-bold py-1">{{ $perizinan->jenisPerizinan->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">ID Req</td>
                                <td class="py-1"><span class="badge badge-light font-family-mono">#{{ $perizinan->id }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Custom Input Fields -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold small text-muted text-uppercase mb-2">
                        Nomor Registrasi SK <span class="text-danger">*</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-tag text-muted"></i></span>
                        </div>
                        <input type="text" id="nomor_surat" name="nomor_surat"
                            class="form-control border-left-0 font-weight-bold"
                            value="{{ old('nomor_surat', $perizinan->nomor_surat ?? $perizinan->nomor_surat_auto) }}"
                            placeholder="Ex: 503/001/IUMK/2023" required onkeyup="updatePreview()">
                    </div>
                    <small class="form-text text-muted mt-2">Nomor ini akan dicetak pada header sertifikat.</small>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted text-uppercase mb-2">Tanggal Terbit</label>
                            <input type="date" id="tanggal_terbit" name="tanggal_terbit" class="form-control"
                                value="{{ old('tanggal_terbit', $perizinan->tanggal_terbit ? $perizinan->tanggal_terbit->format('Y-m-d') : date('Y-m-d')) }}"
                                onchange="updatePreview()">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted text-uppercase mb-2">Masa Berlaku</label>
                            <select id="masa_berlaku" name="masa_berlaku" class="form-control custom-select"
                                onchange="updatePreview()">
                                @php 
                                    $defaultMasa = $perizinan->jenisPerizinan->masa_berlaku_nilai . ' ' . $perizinan->jenisPerizinan->masa_berlaku_unit;
                                @endphp
                                <option value="{{ $defaultMasa }}" selected>{{ $defaultMasa }} (Default)</option>
                                <option value="5 Tahun">5 Tahun</option>
                                <option value="Selamanya">Selamanya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold small text-muted text-uppercase mb-2">Pejabat Penandatangan</label>
                    <div class="input-group shadow-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-pen-nib text-muted"></i></span>
                        </div>
                        <select class="form-control border-left-0 custom-select" id="penandatangan" name="penandatangan_id" onchange="updateSigner(this)">
                            <option value="{{ Auth::user()->dinas_id }}" 
                                    data-nama="{{ Auth::user()->dinas->pimpinan_nama }}"
                                    data-jabatan="{{ Auth::user()->dinas->pimpinan_jabatan }}"
                                    data-nip="{{ Auth::user()->dinas->pimpinan_nip }}"
                                    data-pangkat="{{ Auth::user()->dinas->pimpinan_pangkat }}">
                                {{ Auth::user()->dinas->pimpinan_nama }} ({{ Auth::user()->dinas->pimpinan_jabatan }})
                            </option>
                        </select>
                    </div>
                    <input type="hidden" name="pimpinan_nama" id="pimpinan_nama" value="{{ old('pimpinan_nama', $perizinan->pimpinan_nama ?? Auth::user()->dinas->pimpinan_nama) }}">
                    <input type="hidden" name="pimpinan_jabatan" id="pimpinan_jabatan" value="{{ old('pimpinan_jabatan', $perizinan->pimpinan_jabatan ?? Auth::user()->dinas->pimpinan_jabatan) }}">
                    <input type="hidden" name="pimpinan_nip" id="pimpinan_nip" value="{{ old('pimpinan_nip', $perizinan->pimpinan_nip ?? Auth::user()->dinas->pimpinan_nip) }}">
                    <input type="hidden" name="pimpinan_pangkat" id="pimpinan_pangkat" value="{{ old('pimpinan_pangkat', $perizinan->pimpinan_pangkat ?? Auth::user()->dinas->pimpinan_pangkat) }}">
                </div>

                <div class="card mb-4 bg-light shadow-none border">
                    <div class="card-body p-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="generate_qr" name="generate_qr" value="1" checked>
                            <label class="custom-control-label font-weight-bold" for="generate_qr">Generate QR Code Validasi</label>
                            <small class="d-block text-muted">Menambahkan QR Code untuk verifikasi keaslian dokumen secara digital.</small>
                        </div>
                    </div>
                </div>

                <div class="sticky-bottom mt-auto pt-4 bg-white">
                    <button type="submit" class="btn btn-primary btn-lg btn-block shadow-lg font-weight-bold mb-3">
                        <i class="fas fa-file-export mr-2"></i> Konfirmasi & Terbitkan
                    </button>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary btn-block">
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Live Preview -->
            <div class="right-panel">
                <div class="bg-white border-bottom py-2 px-4 d-flex justify-content-between align-items-center shadow-sm" style="z-index: 5;">
                    <div class="text-muted font-weight-bold small">
                        <i class="fas fa-eye text-primary mr-2"></i> LIVE PREVIEW: {{ $paperSize }} {{ strtoupper($orientation) }}
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light btn-sm border" onclick="zoomPreview(-0.1)"><i class="fas fa-minus"></i></button>
                        <span class="btn btn-light btn-sm border-top border-bottom px-4 font-weight-bold" id="zoom-level">85%</span>
                        <button type="button" class="btn btn-light btn-sm border" onclick="zoomPreview(0.1)"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <div class="preview-container">
                    <div id="preview-paper" style="transform: scale(0.85);">
                        <div id="certificate-canvas">
                            {!! $perizinan->rendered_template !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let currentZoom = 0.85;

        function zoomPreview(delta) {
            currentZoom = Math.min(Math.max(currentZoom + delta, 0.4), 1.5);
            const paper = document.getElementById('preview-paper');
            paper.style.transform = `scale(${currentZoom})`;
            document.getElementById('zoom-level').innerText = `${Math.round(currentZoom * 100)}%`;
        }

        function updateSigner(select) {
            const option = select.options[select.selectedIndex];
            document.getElementById('pimpinan_nama').value = option.dataset.nama || '';
            document.getElementById('pimpinan_jabatan').value = option.dataset.jabatan || '';
            document.getElementById('pimpinan_nip').value = option.dataset.nip || '';
            document.getElementById('pimpinan_pangkat').value = option.dataset.pangkat || '';
            updatePreview();
        }

        function updatePreview() {
            // Re-render would normally happen via AJAX, but for now we rely on the initial render
            // and the user can re-open/submit to see final.
        }

        // Initial load adjustment
        window.addEventListener('load', () => {
            const paper = document.getElementById('preview-paper');
            paper.style.transform = `scale(${currentZoom})`;

            if (window.innerWidth < 1024) {
                 zoomPreview(-0.25);
            }
        });
    </script>
@endpush