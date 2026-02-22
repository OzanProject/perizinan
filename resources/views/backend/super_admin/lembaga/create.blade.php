@extends('layouts.backend')

@section('title', 'Tambah Lembaga')
@section('breadcrumb', 'Tambah Lembaga')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mb-3">
        <a href="{{ route('super_admin.lembaga.index') }}" class="btn btn-default btn-sm shadow-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
      </div>

      <div class="col-lg-8">
        <div class="card card-outline card-primary shadow-sm">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">
              <i class="fas fa-plus-circle mr-2 text-primary"></i> Informasi Umum Lembaga
            </h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="{{ route('super_admin.lembaga.store') }}" method="POST" enctype="multipart/form-data"
            id="main-form">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nama_lembaga">Nama Lembaga <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lembaga" id="nama_lembaga" value="{{ old('nama_lembaga') }}"
                      class="form-control @error('nama_lembaga') is-invalid @enderror"
                      placeholder="Contoh: Sekolah Harapan" required>
                    @error('nama_lembaga')
                      <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="npsn">NPSN <span class="text-danger">*</span></label>
                    <input type="text" name="npsn" id="npsn" value="{{ old('npsn') }}"
                      class="form-control @error('npsn') is-invalid @enderror" placeholder="8 digit NPSN" required>
                    @error('npsn')
                      <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="jenjang">Jenjang Pendidikan <span class="text-danger">*</span></label>
                <select name="jenjang" id="jenjang" class="form-control @error('jenjang') is-invalid @enderror" required>
                  <option value="" disabled selected>-- Pilih Jenjang --</option>
                  @foreach(['TK', 'SD', 'SMP', 'SMA', 'SMK', 'PKBM', 'LKP'] as $j)
                    <option value="{{ $j }}" {{ old('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                  @endforeach
                </select>
                @error('jenjang')
                  <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror"
                  placeholder="Jl. Raya No. 123..." required>{{ old('alamat') }}</textarea>
                @error('alamat')
                  <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer bg-light text-right">
              <a href="{{ route('super_admin.lembaga.index') }}" class="btn btn-default mr-2">Batal</a>
              <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="fas fa-save mr-1"></i> Simpan Lembaga
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card card-outline card-info shadow-sm">
          <div class="card-header text-center">
            <h3 class="card-title w-100 font-weight-bold">Logo Lembaga</h3>
          </div>
          <div class="card-body text-center">
            <div id="logo-preview" class="border rounded mx-auto mb-3 d-flex align-items-center justify-content-center"
              style="width: 150px; height: 150px; background-color: #f8f9fa;">
              <i class="fas fa-image fa-3x text-muted"></i>
            </div>
            <p class="text-muted small mb-3">Format: JPG, PNG, GIF. Maks: 2MB</p>
            <input type="file" name="logo" id="logo-input" form="main-form" class="d-none" accept="image/*">
            <button type="button" onclick="document.getElementById('logo-input').click()"
              class="btn btn-outline-primary btn-block">
              <i class="fas fa-cloud-upload-alt mr-1"></i> Pilih Foto
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.getElementById('logo-input').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
          const preview = document.getElementById('logo-preview');
          preview.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">`;
          preview.style.backgroundColor = 'transparent';
        }
      };
    </script>
  @endpush
@endsection