@extends('layouts.backend')

@section('title', 'Super Admin Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
  <!-- Page Title -->
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard Dinas Pendidikan</h1>
    <div class="flex gap-2">
      <button
        class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded text-sm font-medium hover:bg-gray-50 shadow-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">download</span> Export Laporan
      </button>
    </div>
  </div>

  <!-- Info Boxes (Small Boxes) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Box 1: Total Lembaga -->
    <div class="bg-info rounded-lg shadow-card text-white overflow-hidden relative group">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-bold mb-1">{{ $stats['total_lembaga'] }}</h3>
        <p class="text-sm font-medium opacity-90">Total Lembaga</p>
      </div>
      <div class="absolute right-2 top-2 text-black/10 group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[70px]">school</span>
      </div>
      <a class="block bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors flex items-center justify-center gap-1"
        href="#">
        More info <span class="material-symbols-outlined text-[14px]">arrow_circle_right</span>
      </a>
    </div>

    <!-- Box 2: Pending -->
    <div class="bg-warning rounded-lg shadow-card text-slate-900 overflow-hidden relative group">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-bold mb-1">{{ $stats['pending'] }}</h3>
        <p class="text-sm font-medium opacity-80">Pengajuan Pending</p>
      </div>
      <div class="absolute right-2 top-2 text-black/10 group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[70px]">pending_actions</span>
      </div>
      <a class="block bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors flex items-center justify-center gap-1"
        href="{{ route('super_admin.perizinan.index') }}">
        More info <span class="material-symbols-outlined text-[14px]">arrow_circle_right</span>
      </a>
    </div>

    <!-- Box 3: Disetujui -->
    <div class="bg-success rounded-lg shadow-card text-white overflow-hidden relative group">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-bold mb-1">{{ $stats['disetujui'] }}</h3>
        <p class="text-sm font-medium opacity-90">Disetujui</p>
      </div>
      <div class="absolute right-2 top-2 text-black/10 group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[70px]">check_circle</span>
      </div>
      <a class="block bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors flex items-center justify-center gap-1"
        href="#">
        More info <span class="material-symbols-outlined text-[14px]">arrow_circle_right</span>
      </a>
    </div>

    <!-- Box 4: Need Revision (Perbaikan) -->
    <div class="bg-danger rounded-lg shadow-card text-white overflow-hidden relative group">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-bold mb-1">{{ $stats['perbaikan'] }}</h3>
        <p class="text-sm font-medium opacity-90">Perlu Perbaikan</p>
      </div>
      <div class="absolute right-2 top-2 text-black/10 group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[70px]">cancel</span>
      </div>
      <a class="block bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors flex items-center justify-center gap-1"
        href="#">
        More info <span class="material-symbols-outlined text-[14px]">arrow_circle_right</span>
      </a>
    </div>
  </div>

  <!-- Main Table Section -->
  <div class="bg-white rounded-lg shadow-card border-t-[3px] border-t-primary mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-lg">
      <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-gray-500">list_alt</span>
        Pengajuan Terbaru
      </h3>
    </div>
    <div class="p-0 overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lembaga</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Izin</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-5 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($pengajuanTerbaru as $index => $perizinan)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
              <td class="px-5 py-4">
                <div class="flex flex-col">
                  <span class="font-semibold text-gray-800">{{ $perizinan->lembaga->nama_lembaga }}</span>
                  <span class="text-xs text-gray-400">NPSN: {{ $perizinan->lembaga->npsn }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $perizinan->jenisPerizinan->nama_jenis }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">
                <div class="flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px] text-gray-400">calendar_today</span>
                  {{ $perizinan->created_at->format('d M Y') }}
                </div>
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() }}-100 text-{{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() }}-800 border border-{{ \App\Enums\PerizinanStatus::from($perizinan->status)->color() }}-200">
                  {{ \App\Enums\PerizinanStatus::from($perizinan->status)->label() }}
                </span>
              </td>
              <td class="px-5 py-4 text-right">
                <a href="{{ route('super_admin.perizinan.show', $perizinan->id) }}"
                  class="bg-primary hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded shadow-sm transition-colors">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-gray-500 italic">Belum ada pengajuan terbaru.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection