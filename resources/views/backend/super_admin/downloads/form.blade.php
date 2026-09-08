@extends('layouts.backend')

@section('title', isset($download) ? 'Edit Unduhan' : 'Tambah Unduhan')
@section('breadcrumb', isset($download) ? 'Edit Unduhan' : 'Tambah Unduhan')

@section('content')
            <div class="card card-outline card-primary">
                <form action="{{ isset($download) ? route('super_admin.downloads.update', $download->id) : route('super_admin.downloads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($download))
                        @method('PUT')
                    @endif
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="judul">Judul / Nama File <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $download->judul ?? '') }}" required placeholder="Contoh: Form Pendaftaran PAUD">
                        </div>

                        <div class="form-group">
                            <label for="file">File Unduhan {{ !isset($download) ? '<span class="text-danger">*</span>' : '' }}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar" {{ !isset($download) ? 'required' : '' }}>
                                <label class="custom-file-label" for="file">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, ZIP, RAR. Maksimal 10MB.</small>
                            @if(isset($download) && $download->file_path)
                                <div class="mt-2">
                                    <span class="badge badge-info">File saat ini:</span> 
                                    <a href="{{ Storage::url($download->file_path) }}" target="_blank">Lihat/Download File Lama</a>
                                    <small class="text-warning d-block mt-1">* Kosongkan input file jika tidak ingin mengubah file.</small>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan / Deskripsi</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Opsional...">{{ old('keterangan', $download->keterangan ?? '') }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', isset($download) ? $download->is_active : true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktifkan File Ini (Bisa diunduh publik)</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ route('super_admin.downloads.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@endpush
