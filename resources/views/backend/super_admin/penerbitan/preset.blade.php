@extends('layouts.backend')

@section('title', 'Preset & Layout Cetak')
@section('breadcrumb', 'Manajemen Preset')

@section('content')
  <div class="container-fluid">
    <!-- Page Header & Actions -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-8">
        <h1 class="h3 font-weight-bold text-dark mb-1">Manajemen Preset Cetak</h1>
        <p class="text-muted mb-0">Kelola konfigurasi layout, ukuran kertas, dan margin untuk pencetakan sertifikat.</p>
      </div>
      <div class="col-md-4 text-md-right mt-3 mt-md-0">
        <button type="button" onclick="openPresetModal('add')" class="btn btn-primary shadow-sm font-weight-bold">
          <i class="fas fa-plus-circle mr-2"></i> Buat Preset Baru
        </button>
      </div>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <h5 class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan:</h5>
        <ul class="mb-0 small pl-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Grid Layout for Presets -->
    <div class="row">
      @forelse($presets as $preset)
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="card h-100 shadow-sm border-0 border-top {{ $preset->is_active ? 'border-primary' : 'border-light' }}"
            style="border-top-width: 4px !important;">
            <div class="card-header bg-white py-3">
              <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-truncate h5 mb-0" title="{{ $preset->nama }}">
                  {{ $preset->nama }}
                </h3>
                @if ($preset->is_active)
                  <span class="badge badge-success px-3 py-1 shadow-sm text-uppercase" style="font-size: 10px;">AKTIF</span>
                @else
                  <span class="badge badge-secondary px-3 py-1 text-uppercase" style="font-size: 10px;">DRAFT</span>
                @endif
              </div>
            </div>

            <div class="card-body">
              <div class="d-flex align-items-center mb-4">
                <div class="bg-light rounded d-flex flex-column align-items-center justify-content-center border"
                  style="width: 70px; height: 90px; border-style: dashed !important;">
                  <i class="fas fa-file-alt text-muted mb-1 fa-lg"></i>
                  <span class="text-xs font-weight-bold text-primary">{{ $preset->paper_size }}</span>
                </div>
                <div class="ml-4 flex-grow-1">
                  <div class="d-flex justify-content-between border-bottom pb-1 mb-2">
                    <span class="text-muted small">Orientasi</span>
                    <span class="font-weight-bold small text-dark">
                      <i
                        class="fas {{ strtolower($preset->orientation) === 'portrait' ? 'fa-arrows-alt-v' : 'fa-arrows-alt-h' }} mr-1 text-info"></i>
                      {{ ucfirst($preset->orientation) }}
                    </span>
                  </div>
                  <div class="row text-center no-gutters">
                    <div class="col-3">
                      <label class="text-muted d-block small mb-0" style="font-size: 9px;">TOP</label>
                      <span class="font-weight-bold border rounded px-1">{{ $preset->margin_top }}</span>
                    </div>
                    <div class="col-3">
                      <label class="text-muted d-block small mb-0" style="font-size: 9px;">BTM</label>
                      <span class="font-weight-bold border rounded px-1">{{ $preset->margin_bottom }}</span>
                    </div>
                    <div class="col-3">
                      <label class="text-muted d-block small mb-0" style="font-size: 9px;">LFT</label>
                      <span class="font-weight-bold border rounded px-1">{{ $preset->margin_left }}</span>
                    </div>
                    <div class="col-3">
                      <label class="text-muted d-block small mb-0" style="font-size: 9px;">RGT</label>
                      <span class="font-weight-bold border rounded px-1">{{ $preset->margin_right }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <p class="text-muted small mb-0"><i class="far fa-clock mr-1"></i> Update:
                {{ $preset->updated_at->translatedFormat('d M Y, H:i') }}</p>
            </div>

            <div class="card-footer bg-light py-2 d-flex gap-2">
              @if (!$preset->is_active)
                <form action="{{ route('super_admin.penerbitan.preset.set_active', $preset) }}" method="POST"
                  class="flex-grow-1 mr-2">
                  @csrf
                  <button type="submit" class="btn btn-outline-primary btn-sm btn-block font-weight-bold shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Aktifkan
                  </button>
                </form>
              @endif

              <div class="btn-group ml-auto">
                <button type="button" onclick='openPresetModal("edit", @json($preset))'
                  class="btn btn-sm btn-default border" title="Edit">
                  <i class="fas fa-edit text-primary"></i>
                </button>
                @if (!$preset->is_active)
                  <form action="{{ route('super_admin.penerbitan.preset.destroy', $preset) }}" method="POST"
                    onsubmit="return confirm('Hapus preset ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-default border" title="Hapus">
                      <i class="fas fa-trash text-danger"></i>
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 py-5 text-center bg-white rounded border border-dashed"
          style="border-width: 2px !important; cursor: pointer;" onclick="openPresetModal('add')">
          <i class="fas fa-layer-group fa-4x text-muted mb-3 opacity-25"></i>
          <h5 class="font-weight-bold text-muted">Belum ada Preset Layout</h5>
          <p class="text-muted small">Klik di sini untuk membuat konfigurasi layout kertas pertama Anda.</p>
          <button type="button" class="btn btn-primary mt-2">
            <i class="fas fa-plus mr-1"></i> Buat Sekarang
          </button>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Modal Preset -->
  <div class="modal fade" id="modal-preset" tabindex="-1" role="dialog" aria-labelledby="modal-preset-title"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
        <div class="modal-header border-bottom py-3">
          <h5 class="modal-title font-weight-bold" id="modal-preset-title">Tambah Preset Layout</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-preset" method="POST">
          @csrf
          <input type="hidden" id="form-method" name="_method" value="POST">
          <div class="modal-body p-4">
            <div class="form-group mb-4">
              <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block tracking-wider">Nama
                Preset</label>
              <input type="text" name="nama" id="input-nama" required class="form-control form-control-lg border-primary"
                placeholder="Contoh: Standar Izin Baru (Lanskap)">
            </div>

            <div class="row mb-4">
              <div class="col-6">
                <div class="form-group">
                  <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block">Ukuran Kertas</label>
                  <select name="paper_size" id="input-paper-size" class="form-control custom-select" required>
                    <option value="A4">A4 (210x297mm)</option>
                    <option value="F4">F4 (215x330mm)</option>
                    <option value="LETTER">Letter</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block">Orientasi</label>
                  <select name="orientation" id="input-orientation" class="form-control custom-select" required>
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="bg-light p-4 rounded-xl border">
              <label class="small font-weight-bold text-muted text-uppercase mb-3 d-block tracking-wider"><i
                  class="fas fa-ruler-combined mr-2 text-primary"></i> Margin Dokumen (cm)</label>
              <div class="row">
                <div class="col-3 mb-2">
                  <label class="text-[10px] font-weight-bold text-muted d-block text-center uppercase">Atas</label>
                  <input type="number" step="0.1" name="margin_top" id="input-margin-top" value="2.0"
                    class="form-control text-center font-weight-bold" required>
                </div>
                <div class="col-3 mb-2">
                  <label class="text-[10px] font-weight-bold text-muted d-block text-center uppercase">Bawah</label>
                  <input type="number" step="0.1" name="margin_bottom" id="input-margin-bottom" value="2.0"
                    class="form-control text-center font-weight-bold" required>
                </div>
                <div class="col-3 mb-2">
                  <label class="text-[10px] font-weight-bold text-muted d-block text-center uppercase">Kiri</label>
                  <input type="number" step="0.1" name="margin_left" id="input-margin-left" value="2.5"
                    class="form-control text-center font-weight-bold" required>
                </div>
                <div class="col-3 mb-2">
                  <label class="text-[10px] font-weight-bold text-muted d-block text-center uppercase">Kanan</label>
                  <input type="number" step="0.1" name="margin_right" id="input-margin-right" value="2.0"
                    class="form-control text-center font-weight-bold" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light px-4">
            <button type="button" class="btn btn-link font-weight-bold text-muted" data-dismiss="modal">BATAL</button>
            <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
              <i class="fas fa-save mr-2"></i> SIMPAN PRESET
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function openPresetModal(mode, data = null) {
        const form = document.getElementById('form-preset');
        const methodInput = document.getElementById('form-method');
        const title = document.getElementById('modal-preset-title');

        if (mode === 'add') {
          title.innerText = 'Tambah Preset Layout';
          form.action = "{{ route('super_admin.penerbitan.preset.store') }}";
          methodInput.value = "POST";
          form.reset();

          // Default values
          document.getElementById('input-margin-top').value = '2.0';
          document.getElementById('input-margin-bottom').value = '2.0';
          document.getElementById('input-margin-left').value = '2.5';
          document.getElementById('input-margin-right').value = '2.0';
        } else {
          title.innerText = 'Edit Preset Layout';
          form.action = `/super-admin/penerbitan/preset/${data.id}`;
          methodInput.value = "PUT";

          document.getElementById('input-nama').value = data.nama;
          document.getElementById('input-paper-size').value = data.paper_size;
          document.getElementById('input-orientation').value = data.orientation;
          document.getElementById('input-margin-top').value = data.margin_top;
          document.getElementById('input-margin-bottom').value = data.margin_bottom;
          document.getElementById('input-margin-left').value = data.margin_left;
          document.getElementById('input-margin-right').value = data.margin_right;
        }

        $('#modal-preset').modal('show');
      }
    </script>
  @endpush
@endsection