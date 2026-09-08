@extends('layouts.backend')

@section('title', 'Pusat Unduhan')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pusat Unduhan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Unduhan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
            </div>
            @endif

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Daftar File Unduhan</h3>
                    <div class="card-tools">
                        <a href="{{ route('super_admin.downloads.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah File
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Judul / Nama File</th>
                                <th>Keterangan</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($downloads as $index => $item)
                            <tr>
                                <td class="text-center">{{ $downloads->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $item->judul }}</strong><br>
                                    <small class="text-muted"><a href="{{ Storage::url($item->file_path) }}" target="_blank"><i class="fas fa-download"></i> Download File Asli</a></small>
                                </td>
                                <td>{{ Str::limit($item->keterangan, 50) }}</td>
                                <td class="text-center">
                                    @if($item->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('super_admin.downloads.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('super_admin.downloads.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data unduhan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $downloads->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
