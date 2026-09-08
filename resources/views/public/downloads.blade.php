@extends('layouts.public')

@section('title', 'Pusat Unduhan')

@section('content')
  <!-- Header Section -->
  <section class="px-6 md:px-20 py-20 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto text-center">
      <h2 class="text-primary font-bold text-sm uppercase tracking-widest mb-4">PUSAT UNDUHAN</h2>
      <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6">
        Format Surat & Regulasi
      </h1>
      <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">
        Temukan dan unduh berbagai format dokumen, panduan resmi, serta regulasi terkait layanan perizinan di portal ini.
      </p>
    </div>
  </section>

  <!-- Download List Section -->
  <section class="max-w-6xl mx-auto px-6 py-12 md:py-24">
    
    <!-- Filter Section -->
    <div class="mb-8">
      <form action="{{ route('landing.unduhan') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex items-center gap-2 w-full md:w-auto">
          <label class="text-sm font-bold text-slate-600 dark:text-slate-400 whitespace-nowrap">Tampilkan:</label>
          <select name="limit" onchange="this.form.submit()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-xl focus:ring-primary focus:border-primary block w-24 p-2.5">
            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
          </select>
          <span class="text-sm font-medium text-slate-500">Data</span>
        </div>
        
        <div class="w-full md:w-96 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <span class="material-symbols-outlined text-slate-400">search</span>
          </div>
          <input type="text" name="search" value="{{ request('search') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full pl-12 p-3 transition-all" placeholder="Cari nama dokumen...">
          @if(request('search'))
            <a href="{{ route('landing.unduhan') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-red-500 transition-colors">
              <span class="material-symbols-outlined">close</span>
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none">
      
      @if($downloads->isEmpty())
        <div class="text-center py-20">
          <div class="inline-flex items-center justify-center size-24 bg-slate-50 dark:bg-slate-700 rounded-full mb-6">
            <span class="material-symbols-outlined text-4xl text-slate-400">folder_off</span>
          </div>
          <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Belum ada file</h4>
          <p class="text-slate-500">Belum ada dokumen yang dapat diunduh saat ini.</p>
        </div>
      @else
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                <th class="py-5 px-8 font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama File</th>
                <th class="py-5 px-8 font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-slate-400 hidden md:table-cell">Keterangan</th>
                <th class="py-5 px-8 font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
              @foreach($downloads as $file)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                  <td class="py-6 px-8">
                    <div class="flex items-center gap-5">
                      <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        @php
                            $ext = pathinfo($file->file_path, PATHINFO_EXTENSION);
                            $icon = 'description'; // default
                            $color = 'text-primary';
                            
                            if (in_array($ext, ['pdf'])) { $icon = 'picture_as_pdf'; $color = 'text-red-500'; }
                            elseif (in_array($ext, ['doc', 'docx'])) { $icon = 'article'; $color = 'text-blue-500'; }
                            elseif (in_array($ext, ['xls', 'xlsx'])) { $icon = 'table_view'; $color = 'text-green-500'; }
                            elseif (in_array($ext, ['zip', 'rar'])) { $icon = 'folder_zip'; $color = 'text-amber-500'; }
                        @endphp
                        <span class="material-symbols-outlined {{ $color }}">{{ $icon }}</span>
                      </div>
                      <div>
                        <h6 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $file->judul }}</h6>
                        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                          <span class="uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700">{{ $ext }}</span>
                          <span>•</span>
                          <span>{{ $file->updated_at->format('d M Y') }}</span>
                        </div>
                        <!-- Keterangan for Mobile -->
                        @if($file->keterangan)
                          <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 md:hidden line-clamp-2">
                            {{ $file->keterangan }}
                          </p>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="py-6 px-8 hidden md:table-cell align-middle">
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-md">
                      {{ $file->keterangan ?? '-' }}
                    </p>
                  </td>
                  <td class="py-6 px-8 text-right align-middle">
                    <a href="{{ route('landing.unduhan.file', $file->id) }}" target="_blank" 
                       class="inline-flex items-center justify-center h-12 px-6 rounded-xl bg-primary/10 text-primary font-bold hover:bg-primary hover:text-white transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                      <span class="material-symbols-outlined mr-2 text-[20px]">download</span>
                      Unduh
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <!-- Pagination -->
    @if($downloads->hasPages())
      <div class="mt-8">
        {{ $downloads->links() }}
      </div>
    @endif
    
    
    <div class="text-center mt-12">
      <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary font-bold transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        Kembali ke Beranda
      </a>
    </div>
  </section>
@endsection
