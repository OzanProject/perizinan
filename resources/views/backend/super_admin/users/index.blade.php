@extends('layouts.backend')

@section('title', 'Manajemen User')
@section('breadcrumb', 'User')

@section('content')
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 -mt-2">
    <div>
      <h1 class="text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Manajemen User</h1>
      <p class="text-slate-500 text-sm mt-1">Kelola data pegawai, hak akses (role), dan status akun dinas.</p>
    </div>
    <button onclick="openModal('add')"
      class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 shadow-lg shadow-primary/20 transition-all">
      <span class="material-symbols-outlined">person_add</span>
      Tambah User Baru
    </button>
  </div>

  <!-- Filters Card -->
  <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 mb-6 shadow-sm">
    <form action="{{ route('super_admin.users.index') }}" method="GET"
      class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
      <div class="space-y-1.5">
        <label class="text-xs font-bold text-slate-500 uppercase">Cari User</label>
        <div class="relative">
          <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-lg">search</span>
          <input type="text" name="search" value="{{ request('search') }}"
            class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary"
            placeholder="Nama, Email, atau NIP...">
        </div>
      </div>
      <div class="space-y-1.5">
        <label class="text-xs font-bold text-slate-500 uppercase">Filter Role</label>
        <select name="role"
          class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
          <option>Semua Role</option>
          @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
              {{ strtoupper(str_replace('_', ' ', $role->name)) }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="space-y-1.5">
        <label class="text-xs font-bold text-slate-500 uppercase">Status Akun</label>
        <select name="status"
          class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
          <option>Semua Status</option>
          <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="Non-Aktif" {{ request('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
        </select>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('super_admin.users.index') }}"
          class="flex-1 text-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
          Reset
        </a>
        <button type="submit"
          class="flex-1 bg-primary text-white hover:bg-primary/90 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
          Terapkan
        </button>
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div
    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-800 border-t-4 border-t-primary">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
      <h3 class="font-bold text-slate-800 dark:text-slate-200">Daftar Pegawai & Akses</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 dark:bg-slate-800/50">
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12 text-center">No</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email Dinas</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Role</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
          @forelse($users as $index => $user)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
              <td class="px-6 py-4 text-sm text-slate-500 text-center font-medium">{{ $users->firstItem() + $index }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold">
                    {{ collect(explode(' ', $user->name))->take(2)->map(fn($n) => substr($n, 0, 1))->join('') }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</p>
                    <p class="text-[10px] text-slate-400">NIP: {{ $user->nip ?? '-' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
              <td class="px-6 py-4 text-center">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 uppercase">
                  {{ str_replace('_', ' ', $user->getRoleNames()->first() ?? 'No Role') }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                @if($user->is_active)
                  <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                    Non-Aktif
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button onclick="openResetModal({{ json_encode($user) }})"
                    class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all"
                    title="Reset Password">
                    <span class="material-symbols-outlined text-xl">key</span>
                  </button>
                  <button onclick="openModal('edit', {{ json_encode($user->load('roles')) }})"
                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all"
                    title="Edit">
                    <span class="material-symbols-outlined text-xl">edit</span>
                  </button>
                  <form action="{{ route('super_admin.users.destroy', $user) }}" method="POST" class="inline"
                    onsubmit="return confirm('Hapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all"
                      title="Delete">
                      <span class="material-symbols-outlined text-xl">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Data user tidak ditemukan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($users->hasPages())
      <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800">
        {{ $users->links() }}
      </div>
    @endif
  </div>

  <!-- Modal User -->
  <div id="modalUser"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div
      class="bg-white dark:bg-slate-900 rounded-xl shadow-2xl w-full max-w-lg border border-slate-200 dark:border-slate-800 overflow-hidden animate-in fade-in zoom-in duration-200">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <h3 id="modalTitle" class="text-xl font-black text-slate-900 dark:text-slate-100">Tambah User Baru</h3>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form id="formUser" method="POST" class="p-6 space-y-4">
        @csrf
        <div id="methodContainer"></div>
        <input type="hidden" name="id" id="userId">
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label>
          <input required name="name" id="userName"
            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary"
            placeholder="Masukkan nama lengkap dengan gelar" type="text" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-slate-500 uppercase">NIP</label>
            <input name="nip" id="userNip"
              class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary"
              placeholder="Contoh: 1982..." type="text" />
          </div>
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-slate-500 uppercase">Role Akses</label>
            <select required name="role" id="userRole"
              class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
              <option value="">Pilih Role</option>
              @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ strtoupper(str_replace('_', ' ', $role->name)) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-slate-500 uppercase">Email Dinas</label>
          <input required name="email" id="userEmail"
            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary"
            placeholder="example@disdik.go.id" type="email" />
        </div>
        <div id="passwordField" class="space-y-1.5">
          <label class="text-xs font-bold text-slate-500 uppercase">Password Awal</label>
          <div class="relative">
            <input name="password" id="userPassword"
              class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary"
              type="password" placeholder="Minimal 8 karakter" />
          </div>
          <p class="text-[10px] text-slate-400 italic">User diwajibkan mengganti password pada login pertama.</p>
        </div>
        <div id="lembagaSelectContainer" class="space-y-1.5 hidden">
          <label class="text-xs font-bold text-slate-500 uppercase">Pilih Lembaga</label>
          <select name="lembaga_id" id="userLembaga"
            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <option value="">Pilih Lembaga (Hanya yang belum punya akun)</option>
            @forelse($lembagas as $lembaga)
              <option value="{{ $lembaga->id }}">{{ $lembaga->nama_lembaga }} ({{ $lembaga->jenjang }})</option>
            @empty
              <option value="" disabled>-- Semua lembaga sudah memiliki akun --</option>
            @endforelse
          </select>
          <p class="text-[10px] text-slate-400 italic">Pilihan lembaga hanya muncul untuk Role "Admin Lembaga".</p>
        </div>

        <div id="statusToggle" class="hidden h-[42px] items-center">
          <label class="relative inline-flex items-center cursor-pointer">
            <input name="is_active" id="userStatus" type="checkbox" value="1" class="sr-only peer" />
            <div
              class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
            </div>
            <span class="ml-3 text-sm font-medium text-slate-600 dark:text-slate-400">Akun Aktif</span>
          </label>
        </div>

        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
          <button type="button" onclick="closeModal()"
            class="px-4 py-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">Batal</button>
          <button type="submit"
            class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-primary/20">Simpan
            Data</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Reset Password -->
  <div id="modalReset"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div
      class="bg-white dark:bg-slate-900 rounded-xl shadow-2xl w-full max-w-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <h3 class="text-lg font-bold">Reset Password</h3>
        <button onclick="closeResetModal()" class="text-slate-400 hover:text-slate-600">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <form id="formReset" method="POST" class="p-6 space-y-4">
        @csrf
        <div>
          <p class="text-sm text-slate-500 mb-4">Reset password untuk: <span id="resetTarget"
              class="font-bold text-slate-800 dark:text-slate-200"></span></p>
          <label class="text-xs font-bold text-slate-500 uppercase">Password Baru</label>
          <input required name="password"
            class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary mt-1.5"
            type="password" placeholder="Minimal 8 karakter" />
        </div>
        <div class="flex justify-end gap-3">
          <button type="button" onclick="closeResetModal()"
            class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
          <button type="submit"
            class="bg-amber-500 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-amber-500/20">Reset
            Password</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(mode, data = null) {
      const modal = document.getElementById('modalUser');
      const form = document.getElementById('formUser');
      const title = document.getElementById('modalTitle');
      const methodContainer = document.getElementById('methodContainer');
      const passwordField = document.getElementById('passwordField');
      const statusToggle = document.getElementById('statusToggle');
      const lembagaContainer = document.getElementById('lembagaSelectContainer');

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      form.reset(); // Reset first to clear previous states

      if (mode === 'edit' && data) {
        title.innerText = 'Edit User Pegawai';
        form.action = `/super-admin/users/${data.id}`;
        methodContainer.innerHTML = '@method("PUT")';
        passwordField.classList.add('hidden');
        document.getElementById('userPassword').required = false;
        statusToggle.classList.remove('hidden');
        statusToggle.classList.add('flex');

        document.getElementById('userId').value = data.id;
        document.getElementById('userName').value = data.name;
        document.getElementById('userNip').value = data.nip || '';
        document.getElementById('userEmail').value = data.email;

        const roleName = data.roles && data.roles.length > 0 ? data.roles[0].name : '';
        document.getElementById('userRole').value = roleName;
        document.getElementById('userStatus').checked = data.is_active;

        if (roleName === 'admin_lembaga') {
          lembagaContainer.classList.remove('hidden');
          if (data.lembaga_id) {
            const select = document.getElementById('userLembaga');
            let exists = Array.from(select.options).some(opt => opt.value == data.lembaga_id);
            if (!exists && data.lembaga) {
              const opt = document.createElement('option');
              opt.value = data.lembaga_id;
              opt.text = data.lembaga.nama_lembaga + ' (Current)';
              select.add(opt);
            }
            select.value = data.lembaga_id;
          }
        } else {
          lembagaContainer.classList.add('hidden');
        }
      } else {
        title.innerText = 'Tambah User Baru';
        form.action = "{{ route('super_admin.users.store') }}";
        methodContainer.innerHTML = '';
        passwordField.classList.remove('hidden');
        document.getElementById('userPassword').required = true;
        statusToggle.classList.remove('flex');
        statusToggle.classList.add('hidden');
        lembagaContainer.classList.add('hidden');

        // If we have data (from old input / auto-open), populate it
        if (data) {
          if (data.name) document.getElementById('userName').value = data.name;
          if (data.nip) document.getElementById('userNip').value = data.nip;
          if (data.email) document.getElementById('userEmail').value = data.email;
          if (data.roles && data.roles[0].name) {
            const roleName = data.roles[0].name;
            document.getElementById('userRole').value = roleName;
            if (roleName === 'admin_lembaga') {
              lembagaContainer.classList.remove('hidden');
              if (data.lembaga_id) document.getElementById('userLembaga').value = data.lembaga_id;
            }
          }
        }
      }
    }

    document.getElementById('userRole').addEventListener('change', function () {
      if (this.value === 'admin_lembaga') {
        document.getElementById('lembagaSelectContainer').classList.remove('hidden');
        document.getElementById('userLembaga').required = true;
      } else {
        document.getElementById('lembagaSelectContainer').classList.add('hidden');
        document.getElementById('userLembaga').required = false;
      }
    });

    function closeModal() {
      const modal = document.getElementById('modalUser');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function openResetModal(user) {
      const modal = document.getElementById('modalReset');
      const form = document.getElementById('formReset');
      document.getElementById('resetTarget').innerText = user.name;
      form.action = `/super-admin/users/${user.id}/reset-password`;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeResetModal() {
      const modal = document.getElementById('modalReset');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Auto-open modal if validation errors exist
    @if ($errors->any())
      document.addEventListener('DOMContentLoaded', function () {
        openModal("{{ old('_method') == 'PUT' ? 'edit' : 'add' }}", {
          id: "{{ old('id') }}",
          name: "{{ old('name') }}",
          email: "{{ old('email') }}",
          nip: "{{ old('nip') }}",
          roles: [{
            name: "{{ old('role') }}"
          }],
          is_active: {{ old('is_active') ? 'true' : 'false' }},
          lembaga_id: "{{ old('lembaga_id') }}"
        });
      });
    @elseif(request('create') == 1)
      document.addEventListener('DOMContentLoaded', function () {
        openModal('add');
        document.getElementById('userRole').value = 'admin_lembaga';
        document.getElementById('lembagaSelectContainer').classList.remove('hidden');

        const lembagaId = "{{ request('lembaga_id') }}";
        if (lembagaId) {
          // Check if option exists (it might not if filter is strict)
          const select = document.getElementById('userLembaga');
          let exists = Array.from(select.options).some(opt => opt.value == lembagaId);
          if (!exists) {
            // If not exists (e.g. filtered out), we need to fetch it or just show it
            @if(request('lembaga_id'))
              @php 
                $l = \App\Models\Lembaga::find(request('lembaga_id'));
              @endphp
              @if($l)
                const opt = document.createElement('option');
                opt.value = "{{ $l->id }}";
                opt.text = "{{ $l->nama_lembaga }} (Target)";
                select.add(opt);
              @endif
            @endif
                    }
          select.value = lembagaId;
        }
      });
    @endif
  </script>
@endsection