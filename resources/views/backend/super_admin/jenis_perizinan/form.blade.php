@extends('layouts.backend')

@section('title', 'Konfigurasi Formulir - ' . $jenisPerizinan->nama)
@section('breadcrumb', 'Form Builder')

@section('content')
  <div class="container-fluid">
    <div class="row mb-4 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark font-weight-bold"><i class="fas fa-edit mr-2 text-primary"></i> Konfigurasi Form Dinamis
        </h1>
        <p class="text-muted small mt-1">Tentukan field tambahan yang harus diisi oleh Lembaga untuk
          <strong>{{ $jenisPerizinan->nama }}</strong>.
        </p>
      </div>
      <div class="col-sm-6 text-right">
        <div class="btn-group">
          <a href="{{ route('super_admin.jenis_perizinan.index') }}"
            class="btn btn-default btn-sm shadow-sm font-weight-bold mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
          <button type="button" onclick="addField()" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Field
          </button>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <form action="{{ route('super_admin.jenis_perizinan.form.update', $jenisPerizinan) }}" method="POST"
          id="form-builder">
          @csrf
          <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header bg-white">
              <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-vials mr-2 text-primary"></i> Struktur
                Input Formulir</h3>
            </div>
            <div class="card-body p-4 bg-light">
              <div id="fields-container">
                @if($jenisPerizinan->form_config && count($jenisPerizinan->form_config) > 0)
                  @foreach($jenisPerizinan->form_config as $index => $field)
                    <div
                      class="field-item card shadow-sm mb-4 border-left-4 border-primary animate__animated animate__fadeInDown">
                      <div class="card-header border-0 bg-white py-3">
                        <h3 class="card-title font-weight-bold text-muted small uppercase">Field #{{ $loop->iteration }}</h3>
                        <div class="card-tools">
                          <button type="button" onclick="removeField(this)"
                            class="btn btn-sm btn-outline-danger" title="Hapus Field">
                            <i class="fas fa-times mr-1"></i> Hapus
                          </button>
                        </div>
                      </div>
                      <div class="card-body pt-0">
                        <div class="row">
                          <div class="col-md-5">
                            <div class="form-group mb-md-0">
                              <label class="small font-weight-bold text-uppercase text-muted">Label Field</label>
                              <input type="text" name="fields[{{ $index }}][label]" value="{{ $field['label'] }}"
                                class="form-control font-weight-bold" placeholder="Contoh: Nama Pemilik PKBM" required>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group mb-md-0">
                              <label class="small font-weight-bold text-uppercase text-muted">Key (Unique ID)</label>
                              <input type="text" name="fields[{{ $index }}][name]" value="{{ $field['name'] }}"
                                class="form-control font-mono" placeholder="nama_pemilik" required>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group mb-md-0">
                              <label class="small font-weight-bold text-uppercase text-muted">Tipe Input</label>
                              <select name="fields[{{ $index }}][type]" class="form-control custom-select">
                                <option value="text" {{ $field['type'] == 'text' ? 'selected' : '' }}>Teks Biasa</option>
                                <option value="number" {{ $field['type'] == 'number' ? 'selected' : '' }}>Angka</option>
                                <option value="date" {{ $field['type'] == 'date' ? 'selected' : '' }}>Tanggal</option>
                                <option value="textarea" {{ $field['type'] == 'textarea' ? 'selected' : '' }}>Paragraf</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2 d-flex align-items-end">
                            <div class="custom-control custom-checkbox mb-2">
                              <input type="checkbox" name="fields[{{ $index }}][required]" value="1" {{ ($field['required'] ?? false) ? 'checked' : '' }} id="req-{{ $index }}" class="custom-control-input">
                              <label for="req-{{ $index }}"
                                class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer">Wajib
                                Diisi</label>
                            </div>
                          </div>
                        </div>
                        <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                          <div class="text-xs text-info font-italic">
                            <i class="fas fa-code mr-1"></i> Placeholder untuk Template: <code
                              class="bg-info-soft px-1 rounded text-primary">[DATA:{{ strtoupper($field['name']) }}]</code>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>

              <div id="empty-state"
                class="{{ ($jenisPerizinan->form_config && count($jenisPerizinan->form_config) > 0) ? 'd-none' : '' }} py-5 text-center bg-white rounded shadow-sm">
                <i class="fas fa-layer-group fa-4x text-light mb-3"></i>
                <h4 class="font-weight-bold text-dark">Belum ada field tambahan</h4>
                <p class="text-muted small max-w-sm mx-auto">Klik tombol <strong>"Tambah Field"</strong> untuk mulai
                  mendefinisikan informasi khusus yang diperlukan untuk perizinan ini.</p>
              </div>
            </div>

            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
              <div class="text-xs text-muted">
                <i class="fas fa-lightbulb text-warning mr-1"></i> Gunakan <strong>Key</strong> yang unik (tanpa spasi)
                untuk setiap field tambahan.
              </div>
              <button type="submit" class="btn btn-primary shadow px-5 font-weight-bold">
                <i class="fas fa-save mr-1"></i> Simpan Konfigurasi
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    .bg-info-soft {
      background-color: rgba(23, 162, 184, 0.1);
    }

    .border-left-4 {
      border-left-width: 4px !important;
    }

    .animate__animated {
      --animate-duration: 0.4s;
    }
  </style>

  @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script>
      let fieldCount = {{ $jenisPerizinan->form_config ? count($jenisPerizinan->form_config) : 0 }};

      function addField() {
        document.getElementById('empty-state').classList.add('d-none');

        const container = document.getElementById('fields-container');
        const item = document.createElement('div');
        item.className = 'field-item card shadow-sm mb-4 border-left-4 border-success animate__animated animate__fadeInDown';

        const uid = 'new_' + fieldCount + '_' + Date.now();

        item.innerHTML = `
                        <div class="card-header border-0 bg-white py-3">
                            <h3 class="card-title font-weight-bold text-success small uppercase">Field Baru #${fieldCount + 1}</h3>
                            <div class="card-tools">
                                <button type="button" onclick="removeField(this)" class="btn btn-sm btn-outline-danger" title="Hapus Field">
                                    <i class="fas fa-times mr-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group mb-md-0">
                                        <label class="small font-weight-bold text-uppercase text-muted">Label Field</label>
                                        <input type="text" name="fields[${fieldCount}][label]" class="form-control font-weight-bold" placeholder="Contoh: Nama Pemilik PKBM" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-md-0">
                                        <label class="small font-weight-bold text-uppercase text-muted">Key (Unique ID)</label>
                                        <input type="text" name="fields[${fieldCount}][name]" oninput="this.value = this.value.toLowerCase().replace(/\\s+/g, '_').replace(/[^a-z0-9_]/g, '')" class="form-control font-mono" placeholder="nama_pimpinan" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label class="small font-weight-bold text-uppercase text-muted">Tipe Input</label>
                                        <select name="fields[${fieldCount}][type]" class="form-control custom-select">
                                            <option value="text">Teks Biasa</option>
                                            <option value="number">Angka</option>
                                            <option value="date">Tanggal</option>
                                            <option value="textarea">Paragraf</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" name="fields[${fieldCount}][required]" value="1" id="req-${uid}" class="custom-control-input">
                                        <label for="req-${uid}" class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer">Wajib Diisi</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

        container.appendChild(item);
        fieldCount++;
      }

      function removeField(btn) {
        if (confirm('Hapus field ini?')) {
          btn.closest('.field-item').remove();
          if (document.querySelectorAll('.field-item').length === 0) {
            document.getElementById('empty-state').classList.remove('d-none');
          }
        }
      }
    </script>
  @endpush
@endsection