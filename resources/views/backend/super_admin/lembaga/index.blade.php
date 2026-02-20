@extends('layouts.backend')

@section('title', 'Manajemen Lembaga')
@section('breadcrumb', 'Lembaga')

@section('content')
  <!-- Page Header & Breadcrumbs -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight mb-1">Manajemen Lembaga</h1>
      <p class="text-slate-500 text-sm">Kelola data lembaga pendidikan (TK, SD, SMP, SMA, SMK, PKBM, LKP) di wilayah dinas
        Anda.</p>
    </div>
  </div>

  <!-- Main Card -->
  <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Card Header -->
    <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex flex-wrap justify-between items-center gap-4">
      <h3 class="text-white text-lg font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-xl">corporate_fare</span>
        Daftar Lembaga Pendidikan
      </h3>
      <div class="flex items-center gap-3">
        <a href="{{ route('super_admin.lembaga.create') }}"
          class="flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all shadow-sm">
          <span class="material-symbols-outlined text-[20px]">add</span>
          <span>Tambah Lembaga Baru</span>
        </a>
        <div class="h-6 w-px bg-slate-700 mx-1"></div>
        <a href="{{ route('super_admin.lembaga.index') }}"
          class="text-slate-300 hover:text-white transition-colors p-1 rounded" title="Refresh">
          <span class="material-symbols-outlined text-xl">refresh</span>
        </a>
      </div>
    </div>

    <!-- Card Body: Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white">
      <!-- Search & Filter -->
      <form action="{{ route('super_admin.lembaga.index') }}" method="GET"
        class="flex w-full sm:w-auto gap-3 flex-1 max-w-lg">
        <div class="relative w-full">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
          </div>
          <input name="search" value="{{ request('search') }}"
            class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-lg leading-5 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Cari nama lembaga atau NPSN..." type="text" />
        </div>
        <button type="submit"
          class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Cari</button>
      </form>
    </div>

    <!-- Card Body: Table -->
    <div class="overflow-x-auto custom-scrollbar">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-20">Logo</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lembaga
            </th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenjang</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Admin</th>
            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Status
            </th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-40">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-200">
          @forelse($lembagas as $index => $lembaga)
            <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                {{ $lembagas->firstItem() + $index }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($lembaga->logo)
                  <img src="{{ asset('storage/' . $lembaga->logo) }}" alt="Logo {{ $lembaga->nama_lembaga }}"
                    class="h-10 w-10 rounded-lg object-cover border border-slate-200 shadow-sm">
                @else
                  <div
                    class="h-10 w-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm border border-primary/20">
                    {{ substr($lembaga->nama_lembaga, 0, 2) }}
                  </div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col">
                  <span class="text-sm font-semibold text-slate-900">{{ $lembaga->nama_lembaga }}</span>
                  <span class="text-xs text-slate-500">NPSN: {{ $lembaga->npsn }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-bold rounded bg-slate-100 text-slate-700 border border-slate-200">
                  {{ $lembaga->jenjang ?? 'N/A' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                @php
                  $admin = $lembaga->users->first();
                @endphp
                {{ $admin ? $admin->name : 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <span
                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Active</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('super_admin.lembaga.show', $lembaga) }}"
                    class="text-primary hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded-lg" title="Detail">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                  </a>

                  @if(!$admin)
                    <a href="{{ route('super_admin.users.index', ['role' => 'admin_lembaga', 'lembaga_id' => $lembaga->id, 'create' => 1]) }}"
                      class="text-emerald-600 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded-lg"
                      title="Buat Akun Login">
                      <span class="material-symbols-outlined text-[20px]">person_add</span>
                    </a>
                  @endif

                  <a href="{{ route('super_admin.lembaga.edit', $lembaga) }}"
                    class="text-warning hover:text-yellow-600 p-1.5 hover:bg-yellow-50 rounded-lg" title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </a>
                  <form action="{{ route('super_admin.lembaga.destroy', $lembaga) }}" method="POST" class="inline"
                    onsubmit="return confirm('Hapus lembaga ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-danger hover:text-red-900 p-1.5 hover:bg-red-50 rounded-lg"
                      title="Hapus">
                      <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-slate-500 italic">
                Tidak ada data lembaga ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Card Footer: Pagination -->
    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
      <div class="flex-1 flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-700">
            Menampilkan <span class="font-medium">{{ $lembagas->firstItem() }}</span> sampai <span
              class="font-medium">{{ $lembagas->lastItem() }}</span> dari <span
              class="font-medium">{{ $lembagas->total() }}</span> data
          </p>
        </div>
        <div>
          {{ $lembagas->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection