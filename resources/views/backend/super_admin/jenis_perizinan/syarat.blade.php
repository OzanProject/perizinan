@extends('layouts.backend')

@section('title', 'Persyaratan Dokumen')
@section('breadcrumb', 'Persyaratan: ' . $jenisPerizinan->nama)

@section('content')
  <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 -mt-2">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Persyaratan: {{ $jenisPerizinan->nama }}</h1>
      <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Konfigurasi dokumen wajib dan opsional yang harus
        diunggah oleh pemohon.</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('super_admin.jenis_perizinan.index') }}"
        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Daftar Izin
      </a>
      <button onclick="openModal('add')"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors shadow-md">
        <span class="material-symbols-outlined text-lg">add_circle</span>
        Tambah Persyaratan
      </button>
    </div>
  </div>

  <!-- Main Content Card -->
  <div
    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
            <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">No
            </th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama
              Dokumen</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Format
              File</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status
              Wajib</th>
            <th
              class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
              Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
          @forelse($syarats as $index => $syarat)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
              <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $index + 1 }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $syarat->nama_dokumen }}</div>
                <div class="text-xs text-slate-500">{{ $syarat->deskripsi ?? '-' }}</div>
              </td>
              <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                <span
                  class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium uppercase">{{ $syarat->tipe_file }}</span>
              </td>
              <td class="px-6 py-4">
                @if($syarat->is_required)
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Wajib
                  </span>
                @else
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Opsional
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button onclick="openModal('edit', {{ json_encode($syarat) }})"
                    class="p-2 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded transition-colors"
                    title="Edit">
                    <span class="material-symbols-outlined text-lg leading-none">edit</span>
                  </button>
                  <form action="{{ route('super_admin.jenis_perizinan.syarat.destroy', [$jenisPerizinan, $syarat]) }}"
                    method="POST" class="inline" onsubmit="return confirm('Hapus persyaratan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded transition-colors"
                      title="Hapus">
                      <span class="material-symbols-outlined text-lg leading-none">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada persyaratan dokumen.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div
      class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
      <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $syarats->count() }} persyaratan dokumen</span>
    </div>
  </div>

  <!-- Modal Tambah/Edit Persyaratan -->
  <div id="modalSyarat"
    class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div
      class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 animate-in fade-in zoom-in duration-200">
      <div
        class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
        <h3 id="modalTitle" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">add_circle</span>
          Tambah Persyaratan Dokumen
        </h3>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-500">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form id="formSyarat" method="POST" class="p-6 space-y-5">
        @csrf
        <div id="methodContainer"></div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1" for="nama_dokumen">Nama
            Dokumen <span class="text-rose-500">*</span></label>
          <input required
            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-slate-400"
            id="nama_dokumen" name="nama_dokumen" placeholder="Contoh: Sertifikat Tanah / IMB" type="text" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1" for="deskripsi">Deskripsi
            Singkat</label>
          <textarea
            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-slate-400 resize-none"
            id="deskripsi" name="deskripsi" placeholder="Jelaskan detail dokumen untuk membantu pemohon..."
            rows="3"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1" for="tipe_file">Tipe File
              <span class="text-rose-500">*</span></label>
            <select required
              class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary"
              id="tipe_file" name="tipe_file">
              <option value="pdf">PDF Only</option>
              <option value="image">Gambar (JPG, PNG)</option>
              <option value="all">Semua Format</option>
            </select>
          </div>
          <div class="flex flex-col justify-end">
            <label class="relative inline-flex items-center cursor-pointer mb-2">
              <input name="is_required" id="is_required" type="checkbox" checked class="sr-only peer" />
              <div
                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/10 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-primary">
              </div>
              <span class="ml-3 text-sm font-semibold text-slate-700 dark:text-slate-300">Wajib Diunggah</span>
            </label>
          </div>
        </div>
        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex gap-3 justify-end">
          <button type="button" onclick="closeModal()"
            class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            Batal
          </button>
          <button type="submit"
            class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary/90 transition-colors shadow-sm">
            Simpan Persyaratan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(mode, data = null) {
      const modal = document.getElementById('modalSyarat');
      const form = document.getElementById('formSyarat');
      const title = document.getElementById('modalTitle');
      const methodContainer = document.getElementById('methodContainer');

      modal.classList.remove('hidden');

      if (mode === 'edit') {
        title.innerHTML = '<span class="material-symbols-outlined text-amber-500">edit_note</span> Edit Persyaratan Dokumen';
        form.action = `/super-admin/jenis-perizinan/{{ $jenisPerizinan->id }}/syarat/${data.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('nama_dokumen').value = data.nama_dokumen;
        document.getElementById('deskripsi').value = data.deskripsi || '';
        document.getElementById('tipe_file').value = data.tipe_file;
        document.getElementById('is_required').checked = data.is_required;
      } else {
        title.innerHTML = '<span class="material-symbols-outlined text-primary">add_circle</span> Tambah Persyaratan Dokumen';
        form.action = "{{ route('super_admin.jenis_perizinan.syarat.store', $jenisPerizinan) }}";
        methodContainer.innerHTML = '';
        form.reset();
        document.getElementById('is_required').checked = true;
      }
    }

    function closeModal() {
      document.getElementById('modalSyarat').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('modalSyarat').addEventListener('click', function (e) {
      if (e.target === this) closeModal();
    });
  </script>
@endsection