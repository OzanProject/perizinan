@extends('layouts.backend')

@section('title', 'Manajemen Jenis Perizinan')
@section('breadcrumb', 'Jenis Perizinan')

@section('content')
  <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 -mt-2">
    <div>
      <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Manajemen Jenis Perizinan</h2>
      <p class="text-slate-500 text-sm mt-1">Kelola kategori perizinan, masa berlaku, dan persyaratan administratif.</p>
    </div>
    <button onclick="openModal('add')"
      class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-lg font-bold text-sm shadow-sm hover:bg-blue-700 transition-all">
      <span class="material-symbols-outlined text-[20px]">add</span>
      Tambah Jenis Perizinan
    </button>
  </div>

  <!-- Data Table Card -->
  <div
    class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
          <tr>
            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">No</th>
            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Nama Perizinan</th>
            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Masa Berlaku</th>
            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th>
            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @forelse($jenisPerizinans as $index => $item)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
              <td class="px-6 py-4 text-sm text-slate-500">{{ $jenisPerizinans->firstItem() + $index }}</td>
              <td class="px-6 py-4">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 leading-tight">{{ $item->nama }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Kode: {{ $item->kode ?? '-' }}</p>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-400">
                  <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                  {{ $item->masa_berlaku_nilai }} {{ $item->masa_berlaku_unit }}
                </div>
              </td>
              <td class="px-6 py-4">
                @if($item->is_active)
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    <span class="size-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Aktif
                  </span>
                @else
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                    <span class="size-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                    Nonaktif
                  </span>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('super_admin.jenis_perizinan.syarat.index', $item) }}"
                    class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Kelola Syarat">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                  </a>
                  <a href="{{ route('super_admin.jenis_perizinan.template', $item) }}"
                    class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors"
                    title="Edit Template Sertifikat">
                    <span class="material-symbols-outlined text-[20px]">description</span>
                  </a>
                  <button onclick="openModal('edit', {{ json_encode($item) }})"
                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                    title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </button>
                  <form action="{{ route('super_admin.jenis_perizinan.destroy', $item) }}" method="POST" class="inline"
                    onsubmit="return confirm('Hapus jenis perizinan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                      title="Hapus">
                      <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data jenis perizinan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($jenisPerizinans->hasPages())
      <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
        {{ $jenisPerizinans->links() }}
      </div>
    @endif
  </div>

  <!-- Modal Popup for Add/Edit -->
  <div id="modalJenisPerizinan"
    class="fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm hidden flex">
    <div
      class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
      <!-- Modal Header -->
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <h3 id="modalTitle" class="text-lg font-bold text-slate-900 dark:text-slate-100">Tambah Jenis Perizinan</h3>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <!-- Modal Body -->
      <form id="formJenisPerizinan" method="POST" class="p-6 space-y-5">
        @csrf
        <div id="methodContainer"></div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="nama">Nama
            Perizinan</label>
          <input required
            class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm"
            id="nama" name="nama" placeholder="Contoh: Izin Usaha Mikro" type="text" />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="kode">Kode
            Perizinan</label>
          <input
            class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm"
            id="kode" name="kode" placeholder="Contoh: PRM-001" type="text" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
              for="masa_berlaku_nilai">Masa Berlaku</label>
            <div class="flex">
              <input required
                class="w-full px-4 py-2.5 rounded-l-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm"
                id="masa_berlaku_nilai" name="masa_berlaku_nilai" placeholder="5" type="number" />
              <select name="masa_berlaku_unit"
                class="bg-slate-50 dark:bg-slate-800 border-y border-r border-slate-200 dark:border-slate-800 rounded-r-lg px-3 text-xs font-bold outline-none">
                <option value="Tahun">Tahun</option>
                <option value="Bulan">Bulan</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Aktif</label>
            <div class="flex h-[42px] items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input name="is_active" id="is_active" type="checkbox" checked class="sr-only peer" />
                <div
                  class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                </div>
                <span class="ml-3 text-sm font-medium text-slate-600 dark:text-slate-400">Aktif</span>
              </label>
            </div>
          </div>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2" for="deskripsi">Deskripsi
            Singkat (Opsional)</label>
          <textarea
            class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-sm"
            id="deskripsi" name="deskripsi" placeholder="Berikan penjelasan singkat mengenai kategori izin ini..."
            rows="3"></textarea>
        </div>

        <!-- Modal Footer -->
        <div
          class="-mx-6 -mb-6 px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
          <button type="button" onclick="closeModal()"
            class="px-4 py-2 text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
            Batal
          </button>
          <button type="submit"
            class="px-6 py-2 bg-primary text-white rounded-lg font-bold text-sm shadow-sm hover:bg-blue-700 transition-all">
            Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(mode, data = null) {
      const modal = document.getElementById('modalJenisPerizinan');
      const form = document.getElementById('formJenisPerizinan');
      const title = document.getElementById('modalTitle');
      const methodContainer = document.getElementById('methodContainer');

      modal.classList.remove('hidden');

      if (mode === 'edit') {
        title.innerText = 'Edit Jenis Perizinan';
        form.action = `/super-admin/jenis-perizinan/${data.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        // Fill data
        document.getElementById('nama').value = data.nama;
        document.getElementById('kode').value = data.kode || '';
        document.getElementById('masa_berlaku_nilai').value = data.masa_berlaku_nilai;
        document.querySelector('select[name="masa_berlaku_unit"]').value = data.masa_berlaku_unit;
        document.getElementById('deskripsi').value = data.deskripsi || '';
        document.getElementById('is_active').checked = data.is_active;
      } else {
        title.innerText = 'Tambah Jenis Perizinan';
        form.action = "{{ route('super_admin.jenis_perizinan.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        document.getElementById('is_active').checked = true;
      }
    }

    function closeModal() {
      document.getElementById('modalJenisPerizinan').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('modalJenisPerizinan').addEventListener('click', function (e) {
      if (e.target === this) closeModal();
    });
  </script>
@endsection