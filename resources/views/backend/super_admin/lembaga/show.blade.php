@extends('layouts.backend')

@section('title', 'Detail Lembaga')
@section('breadcrumb', 'Profil Lembaga')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <a href="{{ route('super_admin.lembaga.index') }}" class="btn btn-default btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('super_admin.lembaga.edit', $lembaga) }}" class="btn btn-warning btn-sm shadow-sm font-weight-bold">
                <i class="fas fa-edit mr-1"></i> Edit Profil
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Profile Card -->
        <div class="col-lg-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        @if($lembaga->logo)
                            <img class="profile-user-img img-fluid img-circle shadow-sm" src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #adb5bd;">
                        @else
                            <div class="profile-user-img img-fluid img-circle d-flex align-items-center justify-content-center bg-light shadow-sm" style="width: 100px; height: 100px; margin: 0 auto; border: 3px solid #adb5bd;">
                                <span class="h1 font-weight-bold text-primary mb-0">{{ substr($lembaga->nama_lembaga, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <h3 class="profile-username text-center font-weight-bold">{{ $lembaga->nama_lembaga }}</h3>
                    <p class="text-muted text-center mb-2">{{ $lembaga->jenjang }}</p>

                    <div class="text-center mb-3">
                        <span class="badge badge-success px-3 py-1 shadow-sm">Terdaftar</span>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NPSN</b> <a class="float-right font-weight-bold text-dark">{{ $lembaga->npsn }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Sejak</b> <a class="float-right text-muted">{{ $lembaga->created_at->format('d M Y') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Admin Info -->
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-info"><i class="fas fa-user-shield mr-2"></i> Administrator</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        @forelse($lembaga->users as $admin)
                            <li class="nav-item">
                                <div class="nav-link">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=random" class="img-circle mr-3" style="width: 35px;">
                                        <div>
                                            <div class="font-weight-bold small text-dark">{{ $admin->name }}</div>
                                            <div class="extra-small text-muted" style="font-size: 0.75rem;">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="nav-item">
                                <span class="nav-link text-muted italic small text-center py-4">Belum ada admin terdaftar.</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Details -->
        <div class="col-lg-8">
            <!-- Stats Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-info shadow-sm border-0">
                        <div class="inner">
                            <h3>{{ $lembaga->perizinans->count() }}</h3>
                            <p>Total Pengajuan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success shadow-sm border-0">
                        <div class="inner">
                            <h3>{{ $lembaga->perizinans->where('status', \App\Enums\PerizinanStatus::SELESAI->value)->count() }}</h3>
                            <p>Izin Terbit</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning shadow-sm border-0 text-white">
                        <div class="inner">
                            <h3 class="text-white">{{ $lembaga->perizinans->whereNotIn('status', [\App\Enums\PerizinanStatus::SELESAI->value, \App\Enums\PerizinanStatus::DITOLAK->value])->count() }}</h3>
                            <p class="text-white">Dalam Proses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Card -->
            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-map-marker-alt mr-2 text-danger"></i> Lokasi & Alamat</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-secondary" style="line-height: 1.6;">
                        {{ $lembaga->alamat }}
                    </p>
                </div>
            </div>

            <!-- Recent Submissions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-2 text-muted"></i> Riwayat Pengajuan Izin</h3>
                    <div class="card-tools">
                        <span class="badge badge-light border text-muted px-2 py-1">5 Terakhir</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-top-0 px-4">Jenis Perizinan</th>
                                    <th class="border-top-0">Tanggal</th>
                                    <th class="border-top-0 text-center">Status</th>
                                    <th class="border-top-0 text-right px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lembaga->perizinans->take(5) as $p)
                                    <tr>
                                        <td class="px-4 py-3 align-middle font-weight-bold text-dark">{{ $p->jenisPerizinan->nama_jenis }}</td>
                                        <td class="align-middle text-muted small">{{ $p->created_at->format('d/m/Y') }}</td>
                                        <td class="align-middle text-center">
                                            @php
                                                $status = \App\Enums\PerizinanStatus::from($p->status);
                                                $color = $status->color() == 'success' ? 'success' : ($status->color() == 'primary' ? 'primary' : ($status->color() == 'warning' ? 'warning' : 'danger'));
                                            @endphp
                                            <span class="badge badge-{{ $color }} px-3 py-1 small rounded-pill shadow-sm">
                                                {{ $status->label() }}
                                            </span>
                                        </td>
                                        <td class="text-right align-middle px-4">
                                            <a href="{{ route('super_admin.perizinan.show', $p) }}" class="btn btn-sm btn-outline-primary shadow-sm px-3">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                            <p class="text-muted mb-0 font-italic small">Belum ada riwayat pengajuan perizinan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
