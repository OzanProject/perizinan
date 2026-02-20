@extends('layouts.backend')

@section('title', 'Pengaturan Akun')
@section('breadcrumb', 'Setting')

@section('content')
  <div class="mx-auto max-w-6xl space-y-6">
    <!-- Breadcrumbs & Title -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-2">
      <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan Akun</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola profil dan keamanan akun Anda.</p>
      </div>
    </div>

    @if(session('success'))
      <div
        class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="text-sm font-bold">{{ session('success') }}</p>
      </div>
    @endif

    @if($errors->any())
      <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg flex items-start gap-3 mb-6">
        <span class="material-symbols-outlined mt-0.5">error</span>
        <div>
          <p class="text-sm font-bold">Terjadi kesalahan:</p>
          <ul class="text-xs mt-1 list-disc list-inside opacity-90">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
      <!-- Left Column: Profile Summary -->
      <div class="lg:col-span-4 xl:col-span-3 space-y-6">
        <div
          class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-[#1a2234] dark:ring-slate-800">
          <div class="relative h-24 bg-gradient-to-r from-blue-600 to-indigo-700">
            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
              <div id="profile-preview-card"
                class="h-20 w-20 rounded-full border-4 border-white bg-cover bg-center dark:border-[#1a2234] shadow-md overflow-hidden bg-white">
                @if($user->photo)
                  <img src="{{ Storage::url($user->photo) }}" class="h-full w-full object-cover">
                @else
                  <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=EBF4FF&color=7F9CF5"
                    class="h-full w-full object-cover">
                @endif
              </div>
            </div>
          </div>
          <div class="px-6 pb-6 pt-12 text-center">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $user->name }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
              {{ str_replace('_', ' ', $user->getRoleNames()->first()) }}</p>
            <div class="mt-4 flex justify-center">
              <span
                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                <span class="mr-1.5 h-2 w-2 rounded-full bg-green-600"></span>
                Aktif
              </span>
            </div>
            <div class="mt-6 border-t border-slate-100 pt-4 text-left dark:border-slate-800">
              <div class="flex justify-between py-2 text-sm">
                <span class="text-slate-500 dark:text-slate-400">Bergabung</span>
                <span
                  class="font-medium text-slate-900 dark:text-slate-200">{{ $user->created_at->format('d M Y') }}</span>
              </div>
              <div class="flex justify-between py-2 text-sm">
                <span class="text-slate-500 dark:text-slate-400">Dinas</span>
                <span class="font-medium text-slate-900 dark:text-slate-200 truncate max-w-[120px]"
                  title="{{ $user->dinas->nama }}">{{ $user->dinas->nama }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="rounded-xl bg-blue-50 p-4 text-blue-900 dark:bg-blue-900/20 dark:text-blue-200">
          <div class="flex items-start gap-3">
            <span class="material-symbols-outlined mt-0.5 text-blue-600 dark:text-blue-400">security</span>
            <div>
              <h4 class="font-semibold text-sm">Status Keamanan</h4>
              <p class="mt-1 text-xs opacity-80">Akun Anda dilindungi dengan sistem enkripsi standar dan kebijakan dinas.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Settings Content -->
      <div class="lg:col-span-8 xl:col-span-9 space-y-6">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-[#1a2234] dark:ring-slate-800">
          <!-- In-Page Navigation / Tabs -->
          <div class="border-b border-slate-200 px-2 dark:border-slate-700 overflow-x-auto">
            <nav class="-mb-px flex space-x-6">
              <button onclick="switchTab('tab-profile')" id="nav-profile"
                class="tab-link border-b-2 border-primary px-3 py-4 text-sm font-bold text-primary transition-all">
                Profil Saya
              </button>
              <button onclick="switchTab('tab-instansi')" id="nav-instansi"
                class="tab-link border-b-2 border-transparent px-3 py-4 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-all">
                Instansi & Aplikasi
              </button>
              <button onclick="switchTab('tab-security')" id="nav-security"
                class="tab-link border-b-2 border-transparent px-3 py-4 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-all">
                Keamanan
              </button>
              <button onclick="switchTab('tab-maintenance')" id="nav-maintenance"
                class="tab-link border-b-2 border-transparent px-3 py-4 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 transition-all">
                Pemeliharaan
              </button>
            </nav>
          </div>

          <div class="p-6 md:p-8">
            <!-- Tab Content: Profil -->
            <div id="tab-profile" class="tab-content block">
              <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Profil Saya</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Informasi dasar akun pengguna Anda.</p>

              <form action="{{ route('super_admin.settings.profile.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="grid gap-6">
                  <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative group">
                      <div id="profile-preview-container"
                        class="size-24 rounded-full border-2 border-slate-200 overflow-hidden bg-slate-50">
                        @if($user->photo)
                          <img src="{{ Storage::url($user->photo) }}" class="h-full w-full object-cover">
                        @else
                          <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=EBF4FF&color=7F9CF5"
                            class="h-full w-full object-cover">
                        @endif
                      </div>
                      <label
                        class="absolute bottom-0 right-0 p-1.5 bg-primary text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors cursor-pointer border-2 border-white dark:border-[#1a2234]">
                        <span class="material-symbols-outlined text-xs">edit</span>
                        <input type="file" name="photo" class="hidden" accept="image/*">
                      </label>
                    </div>
                    <div class="flex-1 space-y-4 w-full">
                      <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama
                          Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                          class="w-full rounded-lg border-slate-300 bg-white dark:bg-slate-800/50 dark:border-slate-700 dark:text-white focus:border-primary focus:ring-primary text-sm"
                          required>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                          class="w-full rounded-lg border-slate-300 bg-white dark:bg-slate-800/50 dark:border-slate-700 dark:text-white focus:border-primary focus:ring-primary text-sm"
                          required>
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-end mt-4">
                    <button type="submit"
                      class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-colors">
                      <span class="material-symbols-outlined mr-2 text-[18px]">save</span>
                      Simpan Profil
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Tab Content: Instansi -->
            <div id="tab-instansi" class="tab-content hidden">
              <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Instansi & Aplikasi</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Konfigurasi branding untuk seluruh dashboard
                dinas Anda.</p>

              <form action="{{ route('super_admin.settings.app.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                  <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                      <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama
                          Aplikasi</label>
                        <input type="text" name="app_name"
                          value="{{ old('app_name', $dinas->app_name ?? 'Sistem Perizinan') }}"
                          class="w-full rounded-lg border-slate-300 bg-white dark:bg-slate-800/50 dark:border-slate-700 dark:text-white focus:border-primary focus:ring-primary text-sm"
                          required>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Logo
                          Instansi</label>
                        <div class="flex items-center gap-4">
                          <div id="logo-preview-container"
                            class="size-12 rounded border border-slate-200 bg-white flex items-center justify-center p-2">
                            @if($dinas->logo)
                              <img src="{{ Storage::url($dinas->logo) }}" class="max-h-full max-w-full object-contain">
                            @else
                              <span class="material-symbols-outlined text-slate-300">image</span>
                            @endif
                          </div>
                          <label class="cursor-pointer text-primary hover:underline text-sm font-semibold">
                            Ganti Logo
                            <input type="file" name="logo" class="hidden" accept="image/*">
                          </label>
                        </div>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Teks Footer
                        (Copyright)</label>
                      <textarea name="footer_text" rows="4"
                        class="w-full rounded-lg border-slate-300 bg-white dark:bg-slate-800/50 dark:border-slate-700 dark:text-white focus:border-primary focus:ring-primary text-sm">{{ old('footer_text', $dinas->footer_text) }}</textarea>
                    </div>
                  </div>
                  <div class="flex justify-end mt-4">
                    <button type="submit"
                      class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-colors">
                      <span class="material-symbols-outlined mr-2 text-[18px]">save</span>
                      Update Branding
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Tab Content: Keamanan -->
            <div id="tab-security" class="tab-content hidden">
              <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Ubah Password</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Pastikan password Anda kuat dan unik untuk
                keamanan akun.</p>

              <form action="{{ route('super_admin.settings.security.update') }}" method="POST">
                @csrf
                <div class="max-w-xl space-y-5">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5"
                      for="current_password">Password Saat Ini</label>
                    <input type="password" name="current_password"
                      class="block w-full rounded-lg border-slate-300 bg-slate-50 p-2.5 text-sm dark:border-slate-600 dark:bg-slate-800/50 dark:text-white focus:ring-primary focus:border-primary"
                      placeholder="••••••••" required>
                  </div>
                  <div class="grid gap-5 md:grid-cols-2">
                    <div>
                      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5"
                        for="password">Password Baru</label>
                      <input type="password" name="password"
                        class="block w-full rounded-lg border-slate-300 bg-white p-2.5 text-sm dark:border-slate-600 dark:bg-slate-800/50 dark:text-white focus:ring-primary focus:border-primary"
                        placeholder="••••••••" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5"
                        for="password_confirmation">Konfirmasi Password</label>
                      <input type="password" name="password_confirmation"
                        class="block w-full rounded-lg border-slate-300 bg-white p-2.5 text-sm dark:border-slate-600 dark:bg-slate-800/50 dark:text-white focus:ring-primary focus:border-primary"
                        placeholder="••••••••" required>
                    </div>
                  </div>
                  <div class="flex justify-end pt-4">
                    <button type="submit"
                      class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-colors">
                      <span class="material-symbols-outlined mr-2 text-[18px]">lock_reset</span>
                      Ubah Password
                    </button>
                  </div>
                </div>
              </form>
            </div>

                    <!-- Tab Content: Maintenance -->
                    <div id="tab-maintenance" class="tab-content hidden">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Pemeliharaan & Database</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">Peralatan untuk memastikan sistem berjalan dengan optimal dan manajemen basis data.</p>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Cache Management -->
                            <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-700">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg dark:bg-purple-900/20">
                                        <span class="material-symbols-outlined">cleaning_services</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Bersihkan Cache</h4>
                                </div>
                                <p class="text-xs text-slate-500 mb-4">Hapus file cache sistem untuk menyegarkan tampilan atau konfigurasi terbaru.</p>
                                <form action="{{ route('super_admin.settings.cache.clear') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors dark:bg-slate-800 dark:border-slate-600 dark:text-white">Jalankan Clear Cache</button>
                                </form>
                            </div>

                            <!-- Database Backup -->
                            <div class="rounded-xl border border-slate-200 p-5 dark:border-slate-700">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-green-50 text-green-600 rounded-lg dark:bg-green-900/20">
                                        <span class="material-symbols-outlined">database</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Database Backup</h4>
                                </div>
                                <p class="text-xs text-slate-500 mb-4">Unduh salinan cadangan basis data (.sql) Anda untuk keperluan keamanan file.</p>
                                <form action="{{ route('super_admin.settings.backup') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">download</span>
                                        Download Backup (.sql)
                                    </button>
                                </form>
                            </div>

                            <!-- Database Restore (New) -->
                            <div class="rounded-xl border border-dashed border-slate-300 p-5 dark:border-slate-700 col-span-1 md:col-span-2 bg-slate-50/30 dark:bg-transparent">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg dark:bg-blue-900/20">
                                        <span class="material-symbols-outlined">upload_file</span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Restore Database</h4>
                                </div>
                                <p class="text-xs text-slate-500 mb-4">Pulihkan data dari file .sql yang pernah Anda unduh. <span class="text-red-500 font-bold">Peringatan: Data saat ini akan ditimpa!</span></p>
                                <form action="{{ route('super_admin.settings.restore') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                                    @csrf
                                    <input type="file" name="db_file" accept=".sql" class="flex-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700 cursor-pointer border border-slate-200 rounded-lg p-1 dark:border-slate-700 dark:text-slate-400" required>
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memulihkan database? Seluruh data saat ini akan hilang dan digantikan oleh isi file backup.')" class="bg-slate-900 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-800 transition-colors dark:bg-slate-700 dark:hover:bg-slate-600">Restore Sekarang</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function switchTab(tabId) {
      // Hide all contents
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('block');
      });

      // Show target
      document.getElementById(tabId).classList.remove('hidden');
      document.getElementById(tabId).classList.add('block');

      // Update nav styling
      document.querySelectorAll('.tab-link').forEach(link => {
        link.classList.remove('border-primary', 'text-primary', 'font-bold');
        link.classList.add('border-transparent', 'text-slate-500', 'font-medium');
      });

      const activeLink = document.getElementById('nav-' + tabId.replace('tab-', ''));
      activeLink.classList.add('border-primary', 'text-primary', 'font-bold');
      activeLink.classList.remove('border-transparent', 'text-slate-500', 'font-medium');
    }

    // Image Preview Logic
    function setupImagePreview(inputName, previewId, cardId = null) {
      const input = document.querySelector(`input[name="${inputName}"]`);
      const preview = document.getElementById(previewId);

      if (input && preview) {
        input.addEventListener('change', function () {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
              let img = preview.querySelector('img');
              if (!img) {
                preview.innerHTML = '';
                img = document.createElement('img');
                preview.appendChild(img);
              }
              img.src = e.target.result;
              img.className = previewId.includes('logo') ? 'max-h-full max-w-full object-contain' : 'h-full w-full object-cover';

              if (cardId) {
                const cardPreview = document.getElementById(cardId);
                if (cardPreview) {
                  let cardImg = cardPreview.querySelector('img');
                  if (!cardImg) {
                    cardPreview.innerHTML = '';
                    cardImg = document.createElement('img');
                    cardPreview.appendChild(cardImg);
                  }
                  cardImg.src = e.target.result;
                  cardImg.className = 'h-full w-full object-cover';
                }
              }
            }
            reader.readAsDataURL(file);
          }
        });
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      setupImagePreview('photo', 'profile-preview-container', 'profile-preview-card');
      setupImagePreview('logo', 'logo-preview-container');
    });
  </script>
@endpush