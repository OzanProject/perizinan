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
  <section class="max-w-6xl mx-auto px-6 py-24">
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
                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" 
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
    
    <div class="text-center mt-12">
      <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary font-bold transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        Kembali ke Beranda
      </a>
    </div>
  </section>
@endsection
