@extends('layouts.backend')

@section('title', 'Manajemen Jenjang Pendidikan')

@section('content')
<div class="container-fluid">
  <div class="row mb-4 align-items-center">
    <div class="col-sm-6">
      <h1 class="m-0 font-weight-bold text-dark">
        <i class="fas fa-layer-group text-primary mr-2"></i> Manajemen Jenjang
      </h1>
      <p class="text-muted mb-0">Kelola master data jenjang dan alokasi seksinya.</p>
    </div>
    <div class="col-sm-6 text-right">
      <button class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modal-add">
        <i class="fas fa-plus mr-2"></i> Tambah Jenjang
      </button>
    </div>
  </div>

  <div class="card shadow-sm border-0 rounded-lg">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light text-uppercase text-muted" style="font-size: 13px;">
            <tr>
              <th class="px-4 py-3">No</th>
              <th class="py-3">Nama Jenjang</th>
              <th class="py-3">Seksi Alokasi</th>
              <th class="py-3 text-center">Status</th>
              <th class="py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($jenjangs as $j)
              <tr>
                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                <td class="py-3 font-weight-bold text-dark">{{ $j->nama }}</td>
                <td class="py-3">
                  @if($j->seksi == 'dikmas')
                    <span class="badge badge-info px-3 py-2"><i class="fas fa-users mr-1"></i> Seksi Dikmas</span>
                  @else
                    <span class="badge badge-warning px-3 py-2 text-white"><i class="fas fa-child mr-1"></i> Seksi PAUD</span>
                  @endif
                </td>
                <td class="py-3 text-center">
                  @if($j->is_active)
                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                  @else
                    <span class="badge badge-secondary"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>
                  @endif
                </td>
                <td class="py-3 text-center">
                  <button class="btn btn-sm btn-light text-primary hover-shadow" 
                          data-toggle="modal" data-target="#modal-edit-{{ $j->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-light text-danger hover-shadow" 
                          data-toggle="modal" data-target="#modal-delete-{{ $j->id }}">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="modal-edit-{{ $j->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content border-0 shadow">
                    <form action="{{ route('super_admin.jenjang.update', $j->id) }}" method="POST">
                      @csrf @method('PUT')
                      <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold text-dark">Edit Jenjang: {{ $j->nama }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Nama Jenjang <span class="text-danger">*</span></label>
                          <input type="text" name="nama" class="form-control bg-light" value="{{ $j->nama }}" required>
                          <small class="text-muted">Pastikan namanya sama dengan kode di Jenis Perizinan (misal: PKBM).</small>
                        </div>
                        <div class="form-group">
                          <label>Alokasi Seksi <span class="text-danger">*</span></label>
                          <select name="seksi" class="form-control custom-select bg-light" required>
                            <option value="paud" {{ $j->seksi == 'paud' ? 'selected' : '' }}>Seksi PAUD</option>
                            <option value="dikmas" {{ $j->seksi == 'dikmas' ? 'selected' : '' }}>Seksi DIKMAS</option>
                          </select>
                        </div>
                        <div class="form-group text-left">
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active_{{ $j->id }}" name="is_active" {{ $j->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active_{{ $j->id }}">Aktifkan Jenjang Ini</label>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Modal Delete -->
              <div class="modal fade" id="modal-delete-{{ $j->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                  <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                      <div class="text-danger mb-3">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                      </div>
                      <h5 class="font-weight-bold text-dark">Hapus Jenjang?</h5>
                      <p class="text-muted text-sm">Menghapus jenjang mungkin berdampak pada formulir perizinan. Anda yakin?</p>
                      <form action="{{ route('super_admin.jenjang.destroy', $j->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-light font-weight-bold mr-2" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger font-weight-bold shadow-sm">Ya, Hapus</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            @empty
              <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                  <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                  Belum ada data jenjang pendidikan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modal-add" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <form action="{{ route('super_admin.jenjang.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-light">
          <h5 class="modal-title font-weight-bold text-dark">Tambah Jenjang Baru</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Jenjang <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control bg-light" placeholder="Contoh: SD, SMP, KURSUS" required>
            <small class="text-muted">Ketik nama jenjang, pastikan sinkron dengan data lembaga.</small>
          </div>
          <div class="form-group">
            <label>Alokasi Seksi <span class="text-danger">*</span></label>
            <select name="seksi" class="form-control custom-select bg-light" required>
              <option value="paud">Seksi PAUD</option>
              <option value="dikmas">Seksi DIKMAS</option>
            </select>
          </div>
          <div class="form-group text-left">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="is_active_new" name="is_active" checked>
              <label class="custom-control-label" for="is_active_new">Aktifkan Jenjang Ini</label>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
