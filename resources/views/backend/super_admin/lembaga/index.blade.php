@extends('layouts.backend')

@section('title', 'Manajemen Lembaga')
@section('breadcrumb', 'Manajemen Lembaga')

@section('content')
<div class="container-fluid">
    <!-- Stats Row (Optional, for better AdminLTE feel) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-university mr-2 text-primary"></i> Daftar Lembaga Pendidikan</h3>
                    <div class="card-tools">
                        <a href="{{ route('super_admin.lembaga.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Lembaga
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Toolbar & Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6 col-lg-4">
                            <form action="{{ route('super_admin.lembaga.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama atau NPSN...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped border">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th style="width: 80px;">Logo</th>
                                    <th>Identitas Lembaga</th>
                                    <th class="text-center">Jenjang</th>
                                    <th>Admin Utama</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lembagas as $index => $lembaga)
                                    <tr>
                                        <td class="text-center align-middle">{{ $lembagas->firstItem() + $index }}</td>
                                        <td class="align-middle text-center">
                                            @if($lembaga->logo)
                                                <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo" class="img-thumbnail shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center mx-auto" style="width: 45px; height: 45px;">
                                                    <i class="fas fa-school text-muted small"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-primary">{{ $lembaga->nama_lembaga }}</div>
                                            <div class="small text-muted"><i class="fas fa-id-card-alt mr-1"></i> NPSN: {{ $lembaga->npsn }}</div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary px-2 py-1 shadow-sm">{{ $lembaga->jenjang ?? 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @php $admin = $lembaga->users->first(); @endphp
                                            @if($admin)
                                                <div class="small"><i class="fas fa-user mr-1 text-muted"></i> {{ $admin->name }}</div>
                                                <div class="extra-small text-muted">{{ $admin->email }}</div>
                                            @else
                                                <span class="text-danger small font-italic"><i class="fas fa-exclamation-circle mr-1"></i> Belum ada admin</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-success px-3 py-1">Aktif</span>
                                        </td>
                                        <td class="text-right align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('super_admin.lembaga.show', $lembaga) }}" class="btn btn-sm btn-info shadow-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if(!$admin)
                                                    <a href="{{ route('super_admin.users.index', ['role' => 'admin_lembaga', 'lembaga_id' => $lembaga->id, 'create' => 1]) }}" class="btn btn-sm btn-success shadow-sm" title="Buat Akun">
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('super_admin.lembaga.edit', $lembaga) }}" class="btn btn-sm btn-warning shadow-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('super_admin.lembaga.destroy', $lembaga) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lembaga ini secara permanen?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Tidak ada data lembaga ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <small class="text-muted mb-3 mb-md-0">
                            Menampilkan <b>{{ $lembagas->firstItem() }}</b> - <b>{{ $lembagas->lastItem() }}</b> dari <b>{{ $lembagas->total() }}</b> lembaga
                        </small>
                        <div>
                            {{ $lembagas->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
