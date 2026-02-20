@extends('layouts.backend')

@section('title', 'Laporan & Statistik')
@section('breadcrumb', 'Laporan')

@section('content')
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h2 class="text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Laporan & Statistik Perizinan</h2>
      <p class="text-slate-500 text-sm mt-1">Monitoring data pengajuan izin lintas lembaga secara real-time.</p>
    </div>
    <div class="flex items-center gap-2">
      <button
        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm transition-all">
        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
        {{ now()->translatedFormat('d F Y') }}
      </button>
    </div>
  </div>

  <!-- Summary Widgets (AdminLTE Style Boxes) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <!-- Total Pengajuan -->
    <div class="bg-primary text-white rounded-lg overflow-hidden relative shadow-md min-h-[140px]">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-black">{{ number_format($stats['total']) }}</h3>
        <p class="text-sm font-medium opacity-90 mt-1 uppercase tracking-wide">Total Pengajuan</p>
      </div>
      <span
        class="material-symbols-outlined absolute right-4 top-4 text-7xl text-black/15 pointer-events-none">description</span>
      <a class="absolute bottom-0 left-0 right-0 bg-black/10 py-1.5 text-center text-xs font-semibold hover:bg-black/20 transition-colors flex items-center justify-center gap-1"
        href="{{ route('super_admin.perizinan.index') }}">
        Selengkapnya <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>

    <!-- Pengajuan Disetujui -->
    <div class="bg-emerald-600 text-white rounded-lg overflow-hidden relative shadow-md min-h-[140px]">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-black">{{ number_format($stats['approved']) }}</h3>
        <p class="text-sm font-medium opacity-90 mt-1 uppercase tracking-wide">Pengajuan Disetujui</p>
      </div>
      <span
        class="material-symbols-outlined absolute right-4 top-4 text-7xl text-black/15 pointer-events-none">check_circle</span>
      <a class="absolute bottom-0 left-0 right-0 bg-black/10 py-1.5 text-center text-xs font-semibold hover:bg-black/20 transition-colors flex items-center justify-center gap-1"
        href="{{ route('super_admin.perizinan.index', ['status' => 'disetujui']) }}">
        Selengkapnya <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>

    <!-- Pengajuan Ditolak -->
    <div class="bg-rose-600 text-white rounded-lg overflow-hidden relative shadow-md min-h-[140px]">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-black">{{ number_format($stats['rejected']) }}</h3>
        <p class="text-sm font-medium opacity-90 mt-1 uppercase tracking-wide">Pengajuan Ditolak</p>
      </div>
      <span
        class="material-symbols-outlined absolute right-4 top-4 text-7xl text-black/15 pointer-events-none">cancel</span>
      <a class="absolute bottom-0 left-0 right-0 bg-black/10 py-1.5 text-center text-xs font-semibold hover:bg-black/20 transition-colors flex items-center justify-center gap-1"
        href="{{ route('super_admin.perizinan.index', ['status' => 'ditolak']) }}">
        Selengkapnya <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>

    <!-- Sedang Diproses -->
    <div class="bg-amber-500 text-white rounded-lg overflow-hidden relative shadow-md min-h-[140px]">
      <div class="p-4 relative z-10">
        <h3 class="text-3xl font-black">{{ number_format($stats['processing']) }}</h3>
        <p class="text-sm font-medium opacity-90 mt-1 uppercase tracking-wide">Sedang Diproses</p>
      </div>
      <span
        class="material-symbols-outlined absolute right-4 top-4 text-7xl text-black/15 pointer-events-none">sync</span>
      <a class="absolute bottom-0 left-0 right-0 bg-black/10 py-1.5 text-center text-xs font-semibold hover:bg-black/20 transition-colors flex items-center justify-center gap-1"
        href="{{ route('super_admin.perizinan.index', ['status' => 'diajukan']) }}">
        Selengkapnya <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Filter Card -->
    <div
      class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden h-fit">
      <div
        class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-[20px]">filter_alt</span>
          <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider">Filter Laporan</h3>
        </div>
      </div>
      <div class="p-5">
        <form action="{{ route('super_admin.laporan.index') }}" method="GET" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
            <div class="relative">
              <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">event</span>
              <input name="start_date" value="{{ request('start_date') }}"
                class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary"
                type="date" />
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Selesai</label>
            <div class="relative">
              <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">event</span>
              <input name="end_date" value="{{ request('end_date') }}"
                class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary"
                type="date" />
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lembaga</label>
            <div class="relative">
              <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">school</span>
              <select name="lembaga_id"
                class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary appearance-none">
                <option value="">Semua Lembaga</option>
                @foreach($listLembaga as $l)
                  <option value="{{ $l->id }}" {{ request('lembaga_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_lembaga }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="flex flex-col gap-2 pt-2">
            <button type="submit"
              class="w-full bg-primary text-white font-bold py-2.5 rounded-lg text-sm hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-[18px]">search</span>
              Terapkan Filter
            </button>
            @if(request()->anyFilled(['start_date', 'end_date', 'lembaga_id']))
              <a href="{{ route('super_admin.laporan.index') }}"
                class="w-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold py-2 rounded-lg text-xs hover:bg-slate-200 flex items-center justify-center gap-1 transition-all">
                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                Reset Filter
              </a>
            @endif
          </div>
        </form>
      </div>
    </div>

    <!-- Chart Card -->
    <div
      class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col min-h-[400px]">
      <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-[20px]">show_chart</span>
          <h3 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider">Tren Pengajuan Bulanan
          </h3>
        </div>
      </div>
      <div class="p-6 flex-1 relative flex items-end gap-3 min-h-[300px]">
        @php
          $maxTrend = $monthlyTrend->max() ?: 1;
        @endphp
        @foreach($monthlyTrend as $month => $total)
          <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
            <div
              class="w-full bg-primary/10 hover:bg-primary/30 rounded-t-lg transition-all relative border-t border-transparent group-hover:border-primary"
              style="height: {{ ($total / $maxTrend) * 90 }}%">
              <div
                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap z-20">
                {{ $total }} Izin
              </div>
            </div>
            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $month }}</span>
          </div>
        @endforeach

        <!-- Grid Lines Layer -->
        <div
          class="absolute left-0 right-0 top-0 bottom-[20px] pointer-events-none flex flex-col justify-between opacity-5 px-6">
          <div class="border-t border-slate-900 dark:border-slate-100 w-full"></div>
          <div class="border-t border-slate-900 dark:border-slate-100 w-full"></div>
          <div class="border-t border-slate-900 dark:border-slate-100 w-full"></div>
          <div class="border-t border-slate-900 dark:border-slate-100 w-full"></div>
        </div>
      </div>
      <div
        class="p-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 rounded-b-xl flex items-center justify-center gap-6">
        <div class="flex items-center gap-2">
          <span class="size-3 rounded-sm bg-primary/20"></span>
          <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pengajuan
            Masuk</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="size-3 rounded-sm bg-primary h-0.5 mt-0.5"></span>
          <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Aktivitas
            Periode Ini</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Data Table Card -->
  <div
    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden border-t-4 border-t-primary">
    <div
      class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-[24px]">analytics</span>
        <h3 class="font-bold text-slate-800 dark:text-slate-200 text-lg">Statistik Aktivitas Lembaga</h3>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('super_admin.laporan.export_excel') }}"
          class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all">
          <span class="material-symbols-outlined text-[18px]">table_view</span>
          Export Excel
        </a>
        <a href="{{ route('super_admin.laporan.export_pdf') }}"
          class="flex items-center gap-2 px-4 py-2 bg-rose-600 text-white rounded-lg text-sm font-bold hover:bg-rose-700 shadow-lg shadow-rose-600/20 transition-all">
          <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
          Cetak PDF
        </a>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 dark:bg-slate-800/50">
            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest w-12">
              No</th>
            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nama
              Lembaga</th>
            <th
              class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-center">
              Total Pengajuan</th>
            <th
              class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-center">
              Selesai</th>
            <th
              class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-center">
              Dalam Proses</th>
            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
              Persentase Kelulusan</th>
            <th
              class="px-6 py-4 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-center">
              Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($lembagaStats as $index => $lembaga)
            @php
              $persentase = $lembaga->total_pengajuan > 0
                ? round(($lembaga->selesai / $lembaga->total_pengajuan) * 100, 1)
                : 0;
            @endphp
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
              <td class="px-6 py-4 text-sm font-medium text-slate-500">{{ $lembagaStats->firstItem() + $index }}</td>
              <td class="px-6 py-4">
                <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $lembaga->nama_lembaga }}</div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">{{ $lembaga->jenjang }}</div>
              </td>
              <td class="px-6 py-4 text-center font-semibold text-slate-700 dark:text-slate-300">
                {{ number_format($lembaga->total_pengajuan) }}
              </td>
              <td class="px-6 py-4 text-center">
                <span
                  class="inline-flex items-center justify-center size-8 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-xs rounded-full">{{ $lembaga->selesai }}</span>
              </td>
              <td class="px-6 py-4 text-center">
                <span
                  class="inline-flex items-center justify-center size-8 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold text-xs rounded-full">{{ $lembaga->proses }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="w-full space-y-1.5">
                  <div class="flex items-center justify-between">
                    <span
                      class="text-xs font-bold {{ $persentase > 50 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $persentase }}%</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full {{ $persentase > 50 ? 'bg-emerald-500' : 'bg-rose-500' }}"
                      style="width: {{ $persentase }}%"></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <a href="{{ route('super_admin.perizinan.index', ['search' => $lembaga->nama_lembaga]) }}"
                  class="bg-primary/10 hover:bg-primary hover:text-white text-primary p-2 rounded-lg transition-all inline-block">
                  <span class="material-symbols-outlined text-[18px]">visibility</span>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($lembagaStats->hasPages())
      <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        {{ $lembagaStats->links() }}
      </div>
    @endif
  </div>
@endsection