@extends('layouts.backend')

@section('title', 'Rekap Data Per Kecamatan')
@section('breadcrumb', 'Rekap Kecamatan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-map-marked-alt mr-2 text-info"></i> Rekapitulasi Data Lembaga & Perizinan Berdasarkan Kecamatan
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" onclick="window.print()">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered m-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Kecamatan</th>
                                    <th>Jumlah Lembaga</th>
                                    <th>Total Pengajuan Perizinan</th>
                                    <th>Perizinan Disetujui/Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapData as $index => $data)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle font-weight-bold text-uppercase text-primary">
                                            {{ $data->kecamatan }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-info px-3 py-1" style="font-size: 14px;">{{ $data->total_lembaga }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary px-3 py-1" style="font-size: 14px;">{{ $data->total_perizinan }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-success px-3 py-1" style="font-size: 14px;">{{ $data->perizinan_disetujui }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-map-marker-alt fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Data kecamatan pada lembaga belum tersedia atau masih kosong.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($rekapData->count() > 0)
                                <tfoot class="bg-light font-weight-bold text-center">
                                    <tr>
                                        <td colspan="2" class="text-right">TOTAL KESELURUHAN :</td>
                                        <td>{{ $rekapData->sum('total_lembaga') }}</td>
                                        <td>{{ $rekapData->sum('total_perizinan') }}</td>
                                        <td>{{ $rekapData->sum('perizinan_disetujui') }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
