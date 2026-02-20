@extends('layouts.admin_lembaga')

@section('title', 'Manajemen Pengajuan')

@section('content')
  <div class="space-y-8 max-w-7xl mx-auto w-full">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
      <div>
        <nav class="flex text-sm text-slate-500 mb-2">
          <a class="hover:text-primary transition-colors" href="{{ route('admin_lembaga.dashboard') }}">Dashboard</a>
          <span class="mx-2">/</span>
          <span class="text-slate-800 dark:text-slate-200 font-medium">Pengajuan</span>
        </nav>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Manajemen Pengajuan</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Kelola dan pantau proses izin operasional lembaga Anda.</p>
      </div>
      <div>
        <a href="{{ route('admin_lembaga.perizinan.create') }}"
          class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm shadow-primary/30 transition-all active:scale-95">
          <span class="material-symbols-outlined text-[20px]">add</span>
          Buat Pengajuan Baru
        </a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
      <!-- Card 1: Total -->
      <div
        class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-start justify-between relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-primary"></div>
        <div class="z-10">
          <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Total Pengajuan</p>
          <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</h3>
        </div>
        <div class="bg-primary/10 p-3 rounded-lg text-primary">
          <span class="material-symbols-outlined filled">folder_open</span>
        </div>
      </div>

      <!-- Card 2: Pending -->
      <div
        class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-start justify-between relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-amber-500"></div>
        <div class="z-10">
          <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Menunggu Review</p>
          <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['pending'] }}</h3>
        </div>
        <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg text-amber-600 dark:text-amber-400">
          <span class="material-symbols-outlined filled">pending_actions</span>
        </div>
      </div>

      <!-- Card 3: Completed -->
      <div
        class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-start justify-between relative overflow-hidden group">
        <div class="absolute right-0 top-0 h-full w-1 bg-emerald-500"></div>
        <div class="z-10">
          <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1">Selesai</p>
          <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stats['selesai'] }}</h3>
        </div>
        <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-lg text-emerald-600 dark:text-emerald-400">
          <span class="material-symbols-outlined filled">check_circle</span>
        </div>
      </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div
      class="bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
      <div class="flex flex-wrap gap-2 w-full md:w-auto">
        <button
          class="bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-transparent focus:ring-2 focus:ring-primary/20 flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">filter_list</span>
          Filter
        </button>
        <div class="h-9 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1 hidden md:block"></div>
        <a href="{{ route('admin_lembaga.perizinan.index') }}"
          class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Semua</a>
        @foreach(['diajukan' => 'Diproses', 'perbaikan' => 'Perbaikan', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $val => $label)
          <a href="{{ route('admin_lembaga.perizinan.index', ['status' => $val]) }}"
            class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == $val ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">{{ $label }}</a>
        @endforeach
      </div>
      <form action="{{ route('admin_lembaga.perizinan.index') }}" method="GET" class="relative w-full md:w-80">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
        </div>
        <input name="search" value="{{ request('search') }}"
          class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg leading-5 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm"
          placeholder="Cari ID atau Jenis Izin..." type="text" />
      </form>
    </div>

    <!-- Application Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($perizinans as $perizinan)
        @php
          $statusEnum = \App\Enums\PerizinanStatus::tryFrom($perizinan->status);
          $statusLabel = $statusEnum ? $statusEnum->label() : ucfirst($perizinan->status);
          $statusColor = $statusEnum ? $statusEnum->color() : 'slate';

          // Progress mapping (rough estimate)
          $progress = 0;
          $progressLabel = 'Draft';
          switch ($perizinan->status) {
            case 'draft':
              $progress = 10;
              $progressLabel = 'Menyiapkan Berkas';
              break;
            case 'diajukan':
              $progress = 40;
              $progressLabel = 'Validasi Berkas';
              break;
            case 'perbaikan':
              $progress = 25;
              $progressLabel = 'Revisi Dokumen';
              break;
            case 'disetujui':
              $progress = 85;
              $progressLabel = 'Penerbitan SK';
              break;
            case 'siap_diambil':
              $progress = 95;
              $progressLabel = 'Siap Diambil';
              break;
            case 'selesai':
              $progress = 100;
              $progressLabel = 'Selesai';
              break;
            case 'ditolak':
              $progress = 100;
              $progressLabel = 'Ditolak';
              break;
          }

          // Tailwind color logic fallback
          $badgeBg = [
            'primary' => 'bg-blue-50 text-blue-600 border-blue-100',
            'warning' => 'bg-amber-50 text-amber-600 border-amber-100',
            'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'danger' => 'bg-red-50 text-red-600 border-red-100',
            'info' => 'bg-sky-50 text-sky-600 border-sky-100',
            'secondary' => 'bg-slate-50 text-slate-600 border-slate-100',
            'dark' => 'bg-slate-100 text-slate-800 border-slate-200',
          ];
          $colorKey = $statusColor;
          if ($colorKey == 'info')
            $colorKey = 'info';
          $badgeClass = $badgeBg[$colorKey] ?? 'bg-slate-50 text-slate-600 border-slate-100';

          $progressColor = [
            'primary' => 'bg-blue-500',
            'warning' => 'bg-amber-500',
            'success' => 'bg-emerald-500',
            'danger' => 'bg-red-500',
            'info' => 'bg-sky-500',
            'secondary' => 'bg-slate-500',
          ][$colorKey] ?? 'bg-slate-500';
        @endphp
        <div
          class="group bg-surface-light dark:bg-surface-dark rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden">
          <div class="p-5 flex-1">
            <div class="flex justify-between items-start mb-4">
              <div class="flex items-center gap-3">
                <div
                  class="h-10 w-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                  <span class="font-bold text-sm">{{ substr($perizinan->lembaga->nama_lembaga ?? 'L', 0, 2) }}</span>
                </div>
                <div class="min-w-0">
                  <h4
                    class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors truncate">
                    {{ $perizinan->lembaga->nama_lembaga ?? 'Lembaga' }}
                  </h4>
                  <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $perizinan->lembaga->kecamatan ?? '' }}
                  </p>
                </div>
              </div>
              <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="mb-4">
              <p class="text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium uppercase tracking-wider">Tipe Pengajuan
              </p>
              <h5 class="text-base font-semibold text-slate-800 dark:text-slate-200 leading-snug">
                {{ $perizinan->jenisPerizinan->nama }}
              </h5>
              <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">ID:
                #{{ str_pad($perizinan->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 mb-2">
              <div class="{{ $progressColor }} h-2 rounded-full transition-all duration-500"
                style="width: {{ $progress }}%"></div>
            </div>
            <div class="flex justify-between items-center text-xs">
              <span class="text-slate-500 dark:text-slate-400">Progress: {{ $progress }}%</span>
              <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $progressLabel }}</span>
            </div>
          </div>
          <div
            class="bg-slate-50 dark:bg-slate-800/50 px-5 py-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center gap-2">
            <span class="text-xs text-slate-400">{{ $perizinan->created_at->format('d M Y') }}</span>
            <div class="flex items-center gap-3">
              @if(in_array($perizinan->status, ['draft', 'perbaikan']))
                <form action="{{ route('admin_lembaga.perizinan.destroy', $perizinan) }}" method="POST"
                  onsubmit="return confirm('Hapus draf ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">Hapus</button>
                </form>
                <a href="{{ route('admin_lembaga.perizinan.edit', $perizinan) }}"
                  class="text-sm text-primary font-bold hover:underline flex items-center gap-1">
                  Lanjutkan <span class="material-symbols-outlined text-[16px]">edit</span>
                </a>
              @else
                <a href="{{ route('admin_lembaga.perizinan.show', $perizinan) }}"
                  class="text-sm text-primary font-medium hover:underline flex items-center gap-1">
                  Detail <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div
          class="col-span-full py-20 text-center bg-white dark:bg-surface-dark rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
          <div class="flex flex-col items-center gap-2">
            <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-slate-800">folder_off</span>
            <p class="text-slate-400 dark:text-slate-500 italic font-medium">Belum ada pengajuan perizinan yang sesuai.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($perizinans->hasPages())
      <div class="flex items-center justify-between border-t border-slate-200 dark:border-slate-700 pt-6 pb-2">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-slate-700 dark:text-slate-400">
              Menampilkan <span class="font-medium">{{ $perizinans->firstItem() }}</span> sampai <span
                class="font-medium">{{ $perizinans->lastItem() }}</span> dari <span
                class="font-medium">{{ $perizinans->total() }}</span> hasil
            </p>
          </div>
          <div>
            <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md shadow-sm">
              {{ $perizinans->links() }}
            </nav>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection