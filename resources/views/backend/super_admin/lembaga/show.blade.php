@extends('layouts.backend')

@section('title', 'Detail Lembaga')
@section('breadcrumb', 'Profil Lembaga')

@section('content')
  <div class="mb-6 flex items-center justify-between">
    <a href="{{ route('super_admin.lembaga.index') }}"
      class="text-slate-500 hover:text-primary flex items-center gap-1 text-sm transition-colors">
      <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar
    </a>
    <div class="flex gap-2">
      <a href="{{ route('super_admin.lembaga.edit', $lembaga) }}"
        class="bg-warning/10 text-warning hover:bg-warning hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profil
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="h-24 bg-slate-900"></div>
        <div class="px-6 pb-6 -mt-12 text-center">
          <div class="inline-block p-1 bg-white rounded-2xl shadow-lg border border-slate-100 mb-4">
            @if($lembaga->logo)
              <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo" class="w-24 h-24 rounded-xl object-cover">
            @else
              <div
                class="w-24 h-24 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-2xl">
                {{ substr($lembaga->nama_lembaga, 0, 2) }}
              </div>
            @endif
          </div>
          <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $lembaga->nama_lembaga }}</h2>
          <div
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20 mb-4">
            {{ $lembaga->jenjang }}
          </div>
          <div class="grid grid-cols-2 gap-4 text-left border-t border-slate-100 pt-4">
            <div>
              <p class="text-[10px] uppercase font-bold text-slate-400">NPSN</p>
              <p class="text-sm font-semibold text-slate-700">{{ $lembaga->npsn }}</p>
            </div>
            <div>
              <p class="text-[10px] uppercase font-bold text-slate-400">Status</p>
              <p class="text-sm font-semibold text-green-600">Terdaftar</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-[20px]">person</span>
          Administrator Lembaga
        </h4>
        @forelse($lembaga->users as $admin)
          <div class="flex items-center gap-3 mb-4 last:mb-0">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=random"
              class="w-10 h-10 rounded-full">
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ $admin->name }}</p>
              <p class="text-xs text-slate-500">{{ $admin->email }}</p>
            </div>
          </div>
        @empty
          <p class="text-xs text-slate-400 italic">Belum ada admin terdaftar.</p>
        @endforelse
      </div>
    </div>

    <!-- Details Section -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Stats Row -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase">Total Pengajuan</p>
              <h3 class="text-2xl font-bold text-slate-900">{{ $lembaga->perizinans->count() }}</h3>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
              <span class="material-symbols-outlined">description</span>
            </div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase">Aktif</p>
              <h3 class="text-2xl font-bold text-green-600">
                {{ $lembaga->perizinans->where('status', \App\Enums\PerizinanStatus::SELESAI->value)->count() }}
              </h3>
            </div>
            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
              <span class="material-symbols-outlined">check_circle</span>
            </div>
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase">Dalam Proses</p>
              <h3 class="text-2xl font-bold text-warning">
                {{ $lembaga->perizinans->whereNotIn('status', [\App\Enums\PerizinanStatus::SELESAI->value, \App\Enums\PerizinanStatus::DITOLAK->value])->count() }}
              </h3>
            </div>
            <div class="p-2 bg-yellow-50 text-warning rounded-lg">
              <span class="material-symbols-outlined">history</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail Card -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
          <h3 class="font-bold text-slate-800">Informasi Alamat</h3>
        </div>
        <div class="p-6">
          <div class="flex gap-4">
            <div class="p-3 bg-slate-50 rounded-xl h-fit">
              <span class="material-symbols-outlined text-slate-400">location_on</span>
            </div>
            <div>
              <p class="text-slate-600 text-sm leading-relaxed">{{ $lembaga->alamat }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Perizinan -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
          <h3 class="font-bold text-slate-800">Riwayat Pengajuan Izin</h3>
          <span class="text-xs font-semibold text-slate-400 uppercase">5 Terakhir</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Jenis Izin</th>
                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse($lembaga->perizinans->take(5) as $p)
                <tr>
                  <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $p->jenisPerizinan->nama_jenis }}</td>
                  <td class="px-6 py-4 text-sm text-slate-500">{{ $p->created_at->format('d/m/Y') }}</td>
                  <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border 
                                                {{ \App\Enums\PerizinanStatus::from($p->status)->color() == 'success' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                                                {{ \App\Enums\PerizinanStatus::from($p->status)->color() == 'primary' ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}
                                                {{ \App\Enums\PerizinanStatus::from($p->status)->color() == 'warning' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : '' }}
                                                {{ \App\Enums\PerizinanStatus::from($p->status)->color() == 'danger' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                                            ">
                      {{ \App\Enums\PerizinanStatus::from($p->status)->label() }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <a href="{{ route('super_admin.perizinan.show', $p) }}"
                      class="text-primary hover:underline text-xs font-bold">Detail</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic text-sm">Belum ada riwayat pengajuan.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection