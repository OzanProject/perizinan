@extends('layouts.backend')

@section('title', 'Edit Lembaga')
@section('breadcrumb', 'Edit Lembaga')

@section('content')
  <div class="mb-6">
    <a href="{{ route('super_admin.lembaga.index') }}"
      class="text-slate-500 hover:text-primary flex items-center gap-1 text-sm transition-colors">
      <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar
    </a>
  </div>

  <div class="flex flex-col lg:flex-row gap-6">
    <!-- Form Section -->
    <div class="flex-1">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
          <h3 class="text-white text-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-xl">edit_note</span>
            Perbarui Data Lembaga
          </h3>
        </div>

        <form action="{{ route('super_admin.lembaga.update', $lembaga->id) }}" method="POST" enctype="multipart/form-data"
          id="main-form">
          @csrf
          @method('PUT')

          <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="nama_lembaga" class="block text-sm font-medium text-slate-700 mb-1">Nama Lembaga</label>
                <input type="text" name="nama_lembaga" id="nama_lembaga"
                  value="{{ old('nama_lembaga', $lembaga->nama_lembaga) }}"
                  class="block w-full border-slate-200 rounded-lg focus:ring-primary focus:border-primary sm:text-sm @error('nama_lembaga') border-danger @enderror"
                  required>
                @error('nama_lembaga')
                  <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="npsn" class="block text-sm font-medium text-slate-700 mb-1">NPSN</label>
                <input type="text" name="npsn" id="npsn" value="{{ old('npsn', $lembaga->npsn) }}"
                  class="block w-full border-slate-200 rounded-lg focus:ring-primary focus:border-primary sm:text-sm @error('npsn') border-danger @enderror"
                  required>
                @error('npsn')
                  <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label for="jenjang" class="block text-sm font-medium text-slate-700 mb-1">Jenjang Pendidikan</label>
              <select name="jenjang" id="jenjang" required
                class="block w-full border-slate-200 rounded-lg focus:ring-primary focus:border-primary sm:text-sm">
                <option value="" disabled>Pilih Jenjang</option>
                @foreach(['TK', 'SD', 'SMP', 'SMA', 'SMK', 'PKBM', 'LKP'] as $j)
                  <option value="{{ $j }}" {{ old('jenjang', $lembaga->jenjang) == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="alamat" class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
              <textarea name="alamat" id="alamat" rows="4"
                class="block w-full border-slate-200 rounded-lg focus:ring-primary focus:border-primary sm:text-sm @error('alamat') border-danger @enderror"
                required>{{ old('alamat', $lembaga->alamat) }}</textarea>
              @error('alamat')
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('super_admin.lembaga.index') }}"
              class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">Batal</a>
            <button type="submit"
              class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all shadow-md">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Sidebar Section: Logo -->
    <div class="w-full lg:w-80">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-6">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 text-center">
          <h4 class="font-semibold text-slate-800">Logo Lembaga</h4>
        </div>
        <div class="p-6 flex flex-col items-center">
          <div id="logo-preview"
            class="w-32 h-32 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mb-4 overflow-hidden shadow-inner">
            @if($lembaga->logo)
              <img src="{{ asset('storage/' . $lembaga->logo) }}" class="w-full h-full object-cover">
            @else
              <span class="material-symbols-outlined text-slate-400 text-4xl">image</span>
            @endif
          </div>
          <p class="text-xs text-slate-500 text-center mb-4">Ubah logo dengan memilih file baru</p>
          <input type="file" name="logo" id="logo-input" form="main-form" class="hidden" accept="image/*">
          <button type="button" onclick="document.getElementById('logo-input').click()"
            class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition-colors border border-slate-200">
            Ganti Foto
          </button>
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
          preview.innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover">`;
        }
      };
    </script>
  @endpush
@endsection