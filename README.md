# SIMPER - Sistem Informasi Manajemen Perizinan
### Dinas Pendidikan Kabupaten

SIMPER (Sistem Informasi Manajemen Perizinan) adalah platform berbasis web yang dikembangkan menggunakan **Laravel 12** untuk mengelola proses administrasi perizinan satuan pendidikan (PAUD, TK, PKBM, dll) secara digital, transparan, dan efisien.

---

## 🚀 Fitur Utama

- **Dashboard Multi-Role**: Pemisahan akses antara Super Admin (Dinas) dan Admin Lembaga.
- **Smart Certificate Editor**: Editor visual berbasis CKEditor 4 untuk mendesain template sertifikat dengan variabel dinamis (Drag & Drop variabel).
- **Dynamic Framing (Bingkai Otomatis)**: Sistem pendeteksi bingkai otomatis (PAUD/TK, PKBM, Default) berdasarkan nama perizinan atau pilihan manual.
- **Sistem Verifikasi Publik**: Halaman verifikasi dokumen menggunakan QR Code dan Hash Anti-Tamper untuk memastikan keaslian sertifikat.
- **Manajemen Dokumen & Syarat**: Pengaturan syarat perizinan yang fleksibel untuk setiap jenis izin.
- **Penerbitan Digital**: Alur kerja mulai dari permohonan, revisi, approval, hingga finalisasi sertifikat.
- **Branding Kustom**: Pengaturan Nama Aplikasi, Logo, Stempel Digital, Watermark, dan Opacity Bingkai langsung dari dashboard.
- **Laporan & Ekspor**: Ekspor data ke format Excel dan PDF.
- **Utility System**: Fitur Clear Cache, Database Backup & Restore terintegrasi.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **UI Architecture**: AdminLTE 3 (Bootstrap 4)
- **Database**: MySQL / MariaDB
- **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Excel Engine**: [maatwebsite/excel](https://laravel-excel.com/)
- **QR Code**: [simplesoftwareio/simple-qrcode](https://www.simplesoftware.io/docs/simple-qrcode/)
- **Permission**: [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/)
- **WYSIWYG Editor**: CKEditor 4 (Full Package)

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD Library (untuk pemrosesan gambar/QR Code)

---

## 💻 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/perizinan.git
   cd perizinan
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   *Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.*

4. **Generate Key & Migrate**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses di `http://127.0.0.1:8000`

---

## 📂 Struktur Penting

- `app/Models/Dinas.php`: Pengaturan identitas dinas dan branding.
- `app/Models/Perizinan.php`: Logika utama generate dokumen dan penggantian variabel.
- `app/Http/Controllers/Backend/SuperAdmin/JenisPerizinanController.php`: Manajemen template dan form dinamis.
- `resources/views/backend/super_admin/jenis_perizinan/template.blade.php`: Editor sertifikat visual.

---

## 📝 Lisensi

Proyek ini dibangun untuk kebutuhan internal Dinas Pendidikan. Lisensi di bawah [MIT License](LICENSE).

---

Developed by **[OzanProject](https://www.ozanproject.site/)**
