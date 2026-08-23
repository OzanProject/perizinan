@extends('layouts.backend')

@section('title', 'Konfigurasi Formulir - ' . $jenisPerizinan->nama)
@section('breadcrumb', 'Form Builder')

@push('styles')
<style>
  /* Premium UI Overrides */
  .premium-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
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
  .icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f8f9fa;
  }
  .icon-wrapper.primary { color: #0d6efd; background: #ebf3ff; }
  .field-item {
    border-radius: 10px;
    border: 1px solid #f1f3f5;
    transition: all 0.2s ease;
  }
  .field-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }
  .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
  .border-left-4 { border-left-width: 4px !important; }
  .animate__animated { --animate-duration: 0.4s; }
  .custom-control-label::before { border-radius: 4px; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush

@section('content')
  <div class="content pb-4 text-dark">
    <div class="container-fluid">
      
      <!-- Header -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 mt-2">
        <div>
          <h2 class="h4 font-weight-bold mb-1 text-dark">Konfigurasi Form Dinamis</h2>
          <p class="text-muted mb-0">Tentukan field tambahan yang harus diisi oleh Lembaga untuk izin <strong>{{ $jenisPerizinan->nama }}</strong>.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
          <a href="{{ route('super_admin.jenis_perizinan.index') }}" class="btn btn-light border btn-action mr-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
          </a>
          <button type="button" onclick="addField()" class="btn btn-primary btn-action">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Field
          </button>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <form action="{{ route('super_admin.jenis_perizinan.form.update', $jenisPerizinan) }}" method="POST" id="form-builder">
            @csrf
            
            <div class="card premium-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="icon-wrapper primary mr-3">
                    <i class="fas fa-vials"></i>
                  </div>
                  <div>
                    <h5 class="mb-0 font-weight-bold text-dark">Struktur Input Formulir</h5>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-4 bg-light">
                <div id="fields-container">
                  @if($jenisPerizinan->form_config && count($jenisPerizinan->form_config) > 0)
                    @foreach($jenisPerizinan->form_config as $index => $field)
                      <div class="field-item card shadow-sm mb-4 border-left-4 border-primary animate__animated animate__fadeInDown">
                        <div class="card-header border-0 bg-white py-3 d-flex justify-content-between align-items-center">
                          <h6 class="card-title font-weight-bold text-muted small text-uppercase mb-0">Field #{{ $loop->iteration }}</h6>
                          <div class="card-tools">
                            <button type="button" onclick="removeField(this)" class="btn btn-sm btn-outline-danger btn-action" title="Hapus Field">
                              <i class="fas fa-times mr-1"></i> Hapus
                            </button>
                          </div>
                        </div>
                        <div class="card-body pt-0 pb-4">
                          <div class="row">
                            <div class="col-md-5 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Label Field</label>
                                <input type="text" name="fields[{{ $index }}][label]" value="{{ $field['label'] }}" class="form-control font-weight-bold" placeholder="Contoh: Nama Pimpinan" required>
                              </div>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Key (Unique ID)</label>
                                <input type="text" name="fields[{{ $index }}][name]" value="{{ $field['name'] }}" class="form-control text-monospace bg-light" placeholder="nama_pimpinan" required>
                              </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Tipe Input</label>
                                <select name="fields[{{ $index }}][type]" class="form-control custom-select font-weight-bold">
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
                                <label for="req-{{ $index }}" class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer" style="padding-top: 3px;">Wajib Diisi</label>
                              </div>
                            </div>
                          </div>
                          <div class="mt-4 pt-3 border-top">
                            <div class="text-xs font-italic text-muted d-flex align-items-center">
                              <i class="fas fa-code mr-2 text-primary"></i> 
                              <span>Gunakan format ini di Template Editor:</span> 
                              <code class="bg-info-soft px-2 py-1 rounded text-primary ml-2 font-weight-bold">[DATA:{{ strtoupper($field['name']) }}]</code>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @endif

                  @if(isset($suggestedFields) && count($suggestedFields) > 0)
                    @php 
                      $startIndex = $jenisPerizinan->form_config ? count($jenisPerizinan->form_config) : 0; 
                    @endphp
                    
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4 animate__animated animate__fadeIn">
                      <div class="mr-3 bg-white text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="fas fa-magic fa-lg"></i>
                      </div>
                      <div>
                        <h6 class="font-weight-bold mb-1">Field Otomatis Terdeteksi!</h6>
                        <p class="mb-0 small">Sistem mendeteksi <strong>{{ count($suggestedFields) }} field baru</strong> dari Template Editor. Field-field ini ditambahkan secara otomatis.</p>
                      </div>
                    </div>

                    @foreach($suggestedFields as $sIndex => $field)
                      @php $idx = $startIndex + $sIndex; @endphp
                      <div class="field-item card shadow-sm mb-4 border-left-4 border-info animate__animated animate__fadeInDown">
                        <div class="card-header border-0 bg-info-soft py-3 d-flex justify-content-between align-items-center">
                          <h6 class="card-title font-weight-bold text-info small text-uppercase mb-0">
                            <i class="fas fa-magic mr-1"></i> Auto-Generated Field #{{ $idx + 1 }}
                          </h6>
                          <div class="card-tools">
                            <button type="button" onclick="removeField(this)" class="btn btn-sm btn-outline-danger btn-action bg-white" title="Hapus Field">
                              <i class="fas fa-times mr-1"></i> Hapus
                            </button>
                          </div>
                        </div>
                        <div class="card-body pt-3 pb-4">
                          <div class="row">
                            <div class="col-md-5 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Label Field</label>
                                <input type="text" name="fields[{{ $idx }}][label]" value="{{ $field['label'] }}" class="form-control font-weight-bold border-info" required>
                              </div>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Key (Unique ID)</label>
                                <input type="text" name="fields[{{ $idx }}][name]" value="{{ $field['name'] }}" class="form-control text-monospace bg-light border-info" readonly required>
                              </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                              <div class="form-group mb-0">
                                <label class="small font-weight-bold text-uppercase text-muted mb-2">Tipe Input</label>
                                <select name="fields[{{ $idx }}][type]" class="form-control custom-select font-weight-bold border-info">
                                  <option value="text" selected>Teks Biasa</option>
                                  <option value="number">Angka</option>
                                  <option value="date">Tanggal</option>
                                  <option value="textarea">Paragraf</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                              <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="fields[{{ $idx }}][required]" value="1" checked id="req-{{ $idx }}" class="custom-control-input">
                                <label for="req-{{ $idx }}" class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer" style="padding-top: 3px;">Wajib Diisi</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>

                @php 
                   $hasCurrent = $jenisPerizinan->form_config && count($jenisPerizinan->form_config) > 0;
                   $hasSuggested = isset($suggestedFields) && count($suggestedFields) > 0;
                   $hideEmptyState = $hasCurrent || $hasSuggested;
                @endphp

                <div id="empty-state" class="{{ $hideEmptyState ? 'd-none' : '' }} py-5 text-center bg-white rounded-lg shadow-sm border border-light">
                  <div class="icon-wrapper mx-auto mb-3" style="width: 70px; height: 70px; background: #f8f9fa;">
                    <i class="fas fa-layer-group fa-2x text-muted opacity-50"></i>
                  </div>
                  <h5 class="font-weight-bold text-dark">Belum ada field tambahan</h5>
                  <p class="text-muted small max-w-sm mx-auto mb-0">Klik tombol <strong>"Tambah Field"</strong> untuk mulai mendefinisikan form perizinan ini.</p>
                </div>
              </div>

              <div class="card-footer bg-white py-4 px-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-xs text-muted mb-3 mb-md-0 d-flex align-items-center bg-light px-3 py-2 rounded">
                  <i class="fas fa-lightbulb text-warning mr-2 fa-lg"></i>
                  <span>Gunakan <strong>Key</strong> yang unik (tanpa spasi) untuk menghindari konflik saat generate dokumen.</span>
                </div>
                <button type="submit" class="btn btn-primary shadow-sm px-4 btn-action">
                  <i class="fas fa-save mr-2"></i> Simpan Konfigurasi Form
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      let fieldCount = {{ ($jenisPerizinan->form_config ? count($jenisPerizinan->form_config) : 0) + (isset($suggestedFields) ? count($suggestedFields) : 0) }};

      function addField() {
        document.getElementById('empty-state').classList.add('d-none');

        const container = document.getElementById('fields-container');
        const item = document.createElement('div');
        item.className = 'field-item card shadow-sm mb-4 border-left-4 border-success animate__animated animate__fadeInDown';

        const uid = 'new_' + fieldCount + '_' + Date.now();

        item.innerHTML = `
          <div class="card-header border-0 bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="card-title font-weight-bold text-success small text-uppercase mb-0">Field Baru #${fieldCount + 1}</h6>
            <div class="card-tools">
              <button type="button" onclick="removeField(this)" class="btn btn-sm btn-outline-danger btn-action" title="Hapus Field">
                <i class="fas fa-times mr-1"></i> Hapus
              </button>
            </div>
          </div>
          <div class="card-body pt-0 pb-4">
            <div class="row">
              <div class="col-md-5 mb-3 mb-md-0">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-uppercase text-muted mb-2">Label Field</label>
                  <input type="text" name="fields[${fieldCount}][label]" class="form-control font-weight-bold" placeholder="Contoh: Nama Pimpinan" required>
                </div>
              </div>
              <div class="col-md-3 mb-3 mb-md-0">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-uppercase text-muted mb-2">Key (Unique ID)</label>
                  <input type="text" name="fields[${fieldCount}][name]" oninput="this.value = this.value.toLowerCase().replace(/\\s+/g, '_').replace(/[^a-z0-9_]/g, '')" class="form-control text-monospace bg-light" placeholder="nama_pimpinan" required>
                </div>
              </div>
              <div class="col-md-2 mb-3 mb-md-0">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-uppercase text-muted mb-2">Tipe Input</label>
                  <select name="fields[${fieldCount}][type]" class="form-control custom-select font-weight-bold">
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
                  <label for="req-${uid}" class="custom-control-label small font-weight-bold text-uppercase text-muted cursor-pointer" style="padding-top: 3px;">Wajib Diisi</label>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-3 border-top">
              <div class="text-xs font-italic text-muted d-flex align-items-center">
                <i class="fas fa-info-circle mr-2 text-info"></i> 
                <span>Key akan menjadi placeholder otomatis untuk Template Editor.</span> 
              </div>
            </div>
          </div>
        `;

        container.appendChild(item);
        fieldCount++;
      }

      function removeField(btn) {
        if (confirm('Hapus field ini?')) {
          const item = btn.closest('.field-item');
          item.classList.remove('animate__fadeInDown');
          item.classList.add('animate__fadeOutUp');
          
          setTimeout(() => {
            item.remove();
            if (document.querySelectorAll('.field-item').length === 0) {
              document.getElementById('empty-state').classList.remove('d-none');
            }
          }, 400); // Wait for animation
        }
      }
    </script>
  @endpush
@endsection