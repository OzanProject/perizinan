@extends('layouts.public')

@section('title', 'Pusat Unduhan')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-primary text-white pt-5 pb-4">
    <div class="container pt-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 font-weight-bold mb-2">Pusat Unduhan</h1>
                <p class="lead mb-0 opacity-8">Download format surat, panduan, dan regulasi terkait perizinan.</p>
            </div>
            <div class="col-md-4 text-md-right mt-4 mt-md-0 d-none d-md-block">
                <i class="fas fa-file-download fa-5x opacity-4"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body p-4 p-md-5">
                    
                    @if($downloads->isEmpty())
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="No Files" width="120" class="mb-4 opacity-5">
                            <h4 class="text-muted">Belum ada file yang dapat diunduh.</h4>
                            <p class="text-muted mb-0">Silakan kembali lagi nanti.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="border-0 py-3 rounded-left">Nama File</th>
                                        <th scope="col" class="border-0 py-3">Keterangan</th>
                                        <th scope="col" class="border-0 py-3 rounded-right text-center" width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($downloads as $file)
                                        <tr>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-primary-light text-primary mr-3">
                                                        @php
                                                            $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                                            $icon = 'fa-file-alt';
                                                            if(in_array($ext, ['pdf'])) $icon = 'fa-file-pdf text-danger';
                                                            elseif(in_array($ext, ['doc','docx'])) $icon = 'fa-file-word text-primary';
                                                            elseif(in_array($ext, ['xls','xlsx'])) $icon = 'fa-file-excel text-success';
                                                            elseif(in_array($ext, ['zip','rar'])) $icon = 'fa-file-archive text-warning';
                                                        @endphp
                                                        <i class="fas {{ $icon }} fa-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 font-weight-bold text-dark">{{ $file->judul }}</h6>
                                                        <small class="text-muted">Diperbarui: {{ $file->updated_at->format('d M Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-muted">
                                                {{ $file->keterangan ?? '-' }}
                                            </td>
                                            <td class="py-3 text-center">
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-primary btn-sm btn-block rounded-pill shadow-sm">
                                                    <i class="fas fa-download mr-1"></i> Unduh
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('landing') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f8f9fa;
    }
    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1) !important;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>
@endsection
