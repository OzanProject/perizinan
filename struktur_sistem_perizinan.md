# Struktur dan Alur Sistem Perizinan (Laravel)

Sistem perizinan ini dikembangkan menggunakan framework **Laravel**. Sistem ini memiliki dua peran utama yang mengatur alur perizinan, yaitu **Admin Lembaga** (pemohon) dan **Super Admin / Verifikator** (pemberi izin dari Dinas).

## 1. Arsitektur & Peran (Roles)

- **Admin Lembaga (`admin_lembaga`)**:
  - Mengelola profil lembaga.
  - Mengajukan perizinan baru (membuat draft, mengisi form, upload dokumen syarat).
  - Melakukan perbaikan (revisi) jika dikembalikan oleh dinas.
  - Mengunduh tanda terima (receipt) dan melihat riwayat perizinan.
  - Melakukan konfirmasi pengambilan dokumen fisik (Selesai).

- **Super Admin / Verifikator (`super_admin`)**:
  - Memverifikasi pengajuan perizinan (Menyetujui, Menolak, atau Meminta Revisi).
  - Melakukan finalisasi perizinan (pengisian nomor surat otomatis/manual, tanggal terbit, stempel).
  - Menerbitkan (Release / Auto-Release) sertifikat perizinan.
  - Mengelola Master Data (Jenis Perizinan, Syarat, Form Builder, Template HTML, Preset Cetak, Data Lembaga).
  - Melihat laporan dan pusat cetak antrian.

## 2. Alur Status Perizinan (Workflow)

Alur perizinan diatur secara ketat melalui state machine di `PerizinanWorkflowService` dan enum `PerizinanStatus`.

Merujuk pada kode sistem, berikut adalah siklus hidup (lifecycle) sebuah perizinan:

1. **`DRAFT` (Draft)**
   Admin Lembaga membuat pengajuan baru, mengisi data awal, dan mengunggah dokumen syarat. Pengajuan belum masuk ke antrian Dinas.
2. **`DIAJUKAN` (Diajukan)**
   Admin Lembaga men-submit draft. Pengajuan masuk ke antrian Super Admin untuk dilakukan tinjauan administratif.
3. **`PERBAIKAN` (Perbaikan)**
   Jika dokumen kurang lengkap, Super Admin mengembalikan status ke Perbaikan beserta catatan. Admin Lembaga dapat mengedit kembali dan mengajukan ulang (kembali ke status `DIAJUKAN`).
4. **`DISETUJUI` (Disetujui)**
   Jika dokumen lengkap, Super Admin menyetujui pengajuan. Pada tahap ini, sistem mulai *meng-generate* nomor surat. Dokumen masuk ke tahap finalisasi/pencetakan.
5. **`SIAP_DIAMBIL` (Siap Diambil / Release)**
   Super Admin melakukan 'Release' sertifikat.
   - *Penting*: Sistem akan merender template HTML menjadi PDF.
   - Snapshot HTML dan *Document Hash (SHA256)* akan dikunci (Immutable Freezing) agar tidak bisa dimanipulasi lagi. Data krusial tidak dapat diubah di database setelah status ini.
6. **`SELESAI` (Selesai)**
   Admin Lembaga mengonfirmasi bahwa dokumen fisik sertifikat telah diambil di kantor dinas.
7. **`DITOLAK` (Ditolak)**
   Pengajuan ditolak secara permanen oleh Super Admin.

## 3. Fitur Keamanan & Anti-Pemalsuan Dokumen

Sistem ini memiliki lapisan keamanan yang sangat baik untuk menjaga integritas dokumen perizinan:

- **Immutable Freezing**: Saat perizinan mencapai status `SIAP_DIAMBIL`, model `Perizinan` akan mencegah perubahan data (*boot updating event*) pada kolom `snapshot_html`, `document_hash`, `pdf_path`, dan `nomor_surat`. Ini mencegah perubahan sertifikat secara diam-diam melalui database.
- **QR Code & Public Verification**: Setiap perizinan secara otomatis diberikan `qr_token` (UUID). Saat sertifikat dicetak, QR code akan digenerate dan diarahkan ke rute `/verify/{hash}`. Siapapun yang melakukan scan QR dapat memverifikasi keaslian dokumen tanpa perlu login.

## 4. Sistem Template Dinamis (Engine Cetak)

Sistem ini tidak menggunakan PDF statis (hardcoded). Super Admin dapat mengedit template sertifikat melalui **Template Builder** (berbasis HTML).

- Model `Perizinan` memiliki fungsi `replaceVariables()` yang berfungsi sebagai engine render.
- Fungsi ini mengambil template HTML dari tabel `jenis_perizinans`.
- Secara otomatis melakukan string-replace untuk variabel global seperti `[NOMOR_SURAT]`, `[NAMA_LEMBAGA]`, `[QR_CODE]`, stempel dinas, dll.
- Mendukung pemetaan variabel dinamis dari form builder khusus (`[DATA:... ]`).
- Dilengkapi dengan *wrapper print page* khusus untuk memastikan cetakan rapi dalam ukuran kertas A4.

## 5. Ringkasan Struktur Database Inti

- `perizinans`: Tabel transaksi utama yang menyimpan pengajuan. Memiliki kolom JSON `perizinan_data` untuk menampung isian form yang dinamis (karena setiap jenis izin bisa memiliki form berbeda).
- `jenis_perizinans`: Master tabel kategori izin, menyimpan konfigurasi template HTML, syarat dokumen, dan form config.
- `lembagas`: Tabel profil pengguna (sekolah/yayasan).
- `dinas`: Konfigurasi instansi dinas (logo, kop surat, pimpinan penandatangan).
- `dokumens`: File attachment syarat perizinan yang diunggah lembaga.
- `status_logs`: Tabel riwayat transisi status perizinan.
