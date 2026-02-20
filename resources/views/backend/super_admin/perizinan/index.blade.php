@extends('layouts.backend')

@section('title', 'Kelola Perizinan')
@section('breadcrumb', 'Pengajuan Izin')

@section('content')
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Pengajuan Perizinan</h1>
  </div>

  <div class="bg-white rounded-lg shadow-card border-t-[3px] border-t-primary mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-lg">
      <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-gray-500">list_alt</span>
        Semua Data Pengajuan
      </h3>
    </div>
    <div class="p-0 overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Lembaga</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Izin</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($perizinans as $index => $p)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
              <td class="px-5 py-4">
                <div class="flex flex-col">
                  <span class="font-semibold text-gray-800">{{ $p->lembaga->nama_lembaga }}</span>
                  <span class="text-xs text-gray-400">NPSN: {{ $p->lembaga->npsn }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $p->jenisPerizinan->nama }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ \App\Enums\PerizinanStatus::from($p->status)->color() }}-100 text-{{ \App\Enums\PerizinanStatus::from($p->status)->color() }}-800 border border-{{ \App\Enums\PerizinanStatus::from($p->status)->color() }}-200">
                  {{ \App\Enums\PerizinanStatus::from($p->status)->label() }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600 font-mono">{{ $p->nomor_surat ?? '-' }}</td>
              <td class="px-5 py-4 text-right flex justify-end gap-2">
                @if($p->status == \App\Enums\PerizinanStatus::DIAJUKAN->value)
                  <form action="{{ route('super_admin.perizinan.approve', $p) }}" method="POST">
                    @csrf
                    <button
                      class="bg-success hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded shadow-sm transition-colors flex items-center gap-1">
                      <span class="material-symbols-outlined text-sm">check_circle</span> Setujui
                    </button>
                  </form>
                @endif
                @php $canFinalizeIndex = in_array($p->status, [\App\Enums\PerizinanStatus::DISETUJUI->value, \App\Enums\PerizinanStatus::SIAP_DIAMBIL->value, \App\Enums\PerizinanStatus::SELESAI->value]); @endphp
                @if($canFinalizeIndex)
                  <a href="{{ route('super_admin.perizinan.finalisasi', $p) }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded shadow-sm transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    {{ $p->status == \App\Enums\PerizinanStatus::DISETUJUI->value ? 'Finalisasi' : 'Edit Surat' }}
                  </a>
                @endif
                <a href="{{ route('super_admin.perizinan.show', $p) }}"
                  class="bg-primary hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded shadow-sm transition-colors flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">visibility</span> Detail
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection