<?php

namespace App\Services;

class CertificateTemplateService
{
    public static function getPresets()
    {
        return [
            // ==========================================
            // KELOMPOK 1: TEMPLATE PKBM
            // ==========================================
            'pkbm_izin_memimpin' => [
                'name' => '1. [PKBM] Izin Memimpin',
                'description' => 'Format Surat Keterangan Izin Memimpin PKBM. A4/F4 Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getIzinMemimpinPKBM()
            ],
            'pkbm_perubahan_ketua' => [
                'name' => '2. [PKBM] Perubahan Ketua',
                'description' => 'Format Surat Keterangan Perubahan Ketua PKBM. A4/F4 Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanKetuaPKBM()
            ],
            'pkbm_perubahan_alamat' => [
                'name' => '3. [PKBM] Perubahan Alamat',
                'description' => 'Format Surat Keterangan Perubahan Alamat PKBM. A4/F4 Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanAlamatPKBM()
            ],
            'pkbm_heregister' => [
                'name' => '4. [PKBM] HER-REGISTRASI',
                'description' => 'Sertifikat Daftar Ulang PKBM. Landscape F4, pakai Bingkai.',
                'paper_size' => 'F4',
                'orientation' => 'landscape',
                'use_border' => true,
                'html' => self::getHerregistrasiPKBM()
            ],

            // ==========================================
            // KELOMPOK 2: TEMPLATE PAUD (BARU)
            // ==========================================
            'paud_izin_memimpin' => [
                'name' => '5. [PAUD] Izin Memimpin (Foto 4x6)',
                'description' => 'Suket Izin Memimpin PAUD dengan kotak foto 4x6 di kiri bawah. Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getIzinMemimpinPAUD()
            ],
            'paud_perubahan_yayasan' => [
                'name' => '6. [PAUD] Perubahan Pimpinan & Yayasan',
                'description' => 'Suket Perubahan Pimpinan dan Yayasan Baru PAUD. Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanPimpinanYayasanPAUD()
            ],
            'paud_perubahan_alamat' => [
                'name' => '7. [PAUD] Perubahan Alamat',
                'description' => 'Suket Perubahan Alamat (Lama vs Baru) PAUD. Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanAlamatPAUD()
            ],
            'paud_heregister' => [
                'name' => '8. [PAUD] HER-REGISTRASI (Sertifikat)',
                'description' => 'Blangko Her-Registrasi PAUD. Landscape F4, pakai Bingkai Emas-Biru.',
                'paper_size' => 'F4',
                'orientation' => 'landscape',
                'use_border' => true,
                'html' => self::getHerregistrasiPAUD()
            ],
            'paud_sk_jenjang' => [
                'name' => '9. [PAUD] SK Perubahan Jenjang',
                'description' => 'SK KEPUTUSAN KEPALA DINAS (Menimbang, Mengingat, Memutuskan). Portrait.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getSkPerubahanJenjangPAUD()
            ],
        ];
    }

    // =========================================================================
    // KOP SURAT STANDAR (BISA DIPAKAI PKBM & PAUD)
    // =========================================================================
    private static function kopSurat(): string
    {
        return '
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tbody>
                <tr>
                    <td style="width: 15%; vertical-align: middle; text-align: center;">[LOGO_DINAS]</td>
                    <td style="width: 85%; text-align: center; vertical-align: middle;">
                        <span style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH KABUPATEN [KOTA_DINAS]</span><br>
                        <span style="font-size: 18pt; font-weight: bold; text-transform: uppercase;">DINAS PENDIDIKAN</span><br>
                        <span style="font-size: 10pt;">[ALAMAT_DINAS]</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr style="border: none; border-top: 3px solid black; border-bottom: 1px solid black; height: 4px; background: transparent; margin: 0 0 10px 0;">
        ';
    }

    private static function kopSuratLandscape(): string
    {
        return '
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
            <tbody>
                <tr>
                    <td style="width: 15%; vertical-align: middle; text-align: center;">[LOGO_DINAS]</td>
                    <td style="width: 85%; text-align: center; vertical-align: middle; padding-right: 15%;">
                        <span style="font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">PEMERINTAH KABUPATEN [KOTA_DINAS]</span><br>
                        <span style="font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">DINAS PENDIDIKAN</span><br>
                        <span style="font-size: 10pt;">[ALAMAT_DINAS]</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr style="border: none; border-top: 3px solid black; border-bottom: 1px solid black; height: 4px; background: transparent; margin: 0 0 10px 0;">
        ';
    }


    /*
    |--------------------------------------------------------------------------
    | BAGIAN 1: TEMPLATE PKBM
    |--------------------------------------------------------------------------
    */
    private static function getIzinMemimpinPKBM(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN IZIN MEMIMPIN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 5px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Surat Permohonan dari Yayasan/ Lembaga PKBM nomor [DATA:NOMOR_SURAT_YAYASAN] tanggal: [DATA:TGL_SURAT_YAYASAN], Perihal: Permohonan Izin Memimpin PKBM.</li>
            <li>Keputusan Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_SK_PENDIRIAN], Tanggal [DATA:TGL_SK_PENDIRIAN], tentang Izin Pendirian PKBM.</li>
            <li>Surat Keputusan Ketua Yayasan [DATA:NAMA_YAYASAN], Nomor: [DATA:NOMOR_SK_KETUA], tanggal: [DATA:TGL_SK_KETUA], Perihal: Pengangkatan Ketua Penyelenggara.</li>
        </ol>
        <p style="text-align: justify; margin: 0 0 10px 0;">Berdasarkan hal tersebut diatas, maka dengan ini Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] menerangkan bahwa :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[DATA:NAMA_PIMPINAN]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Tempat, Tanggal Lahir</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:TTL]</td></tr>
                <tr><td style="padding: 2px 0;">Pendidikan Terakhir</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:PENDIDIKAN]</td></tr>
                <tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:JABATAN]</td></tr>
                <tr><td style="padding: 2px 0;">Unit Kerja</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NAMA_LEMBAGA]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
            </tbody>
        </table>
        
        <p style="text-align: justify; margin: 0 0 10px 0;">Adalah benar sebagai Ketua Penyelenggara di [NAMA_LEMBAGA] Kecamatan [DATA:KECAMATAN] yang diangkat oleh Yayasan [DATA:NAMA_YAYASAN].</p>
        <p style="text-align: justify; margin: 0 0 10px 0;">Keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong> dengan ketentuan tidak ada perubahan terhadap penugasan Ketua Penyelenggara yang ditunjuk oleh yayasan.</p>
        <p style="text-align: justify; margin: 0 0 15px 0;">Demikian surat keterangan memimpin ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 55%;"></td>
                    <td style="width: 45%; text-align: left;">
                        Dikeluarkan di : [KOTA_DINAS]<br>
                        Pada Tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getPerubahanKetuaPKBM(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 5px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Berdasarkan Permohonan dari Yayasan Nomor: [DATA:NOMOR_PERMOHONAN], Tanggal: [DATA:TANGGAL_PERMOHONAN].</li>
            <li>Berdasarkan Berita Acara Perubahan Ketua, Nomor: [DATA:NOMOR_BA], Tanggal: [DATA:TANGGAL_BA].</li>
            <li>Berdasarkan SK Pengangkatan Ketua dari Yayasan Nomor: [DATA:NOMOR_SK_YAYASAN].</li>
            <li>Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_IZIN_OP].</li>
        </ol>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Ketua</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 5px 0;">Bahwa Lembaga tersebut diatas mengalami Pergantian Ketua :</p>
        <div style="margin-left: 20px;">
            <strong>1. Ketua Lama</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 5px;">
                <tbody>
                    <tr><td style="width: 25%; padding: 1px 0;">a. Nama Ketua</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 73%; padding: 1px 0;">[DATA:KETUA_LAMA_NAMA]</td></tr>
                    <tr><td style="padding: 1px 0;">b. TTL</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:KETUA_LAMA_TTL]</td></tr>
                </tbody>
            </table>
            <strong>2. Ketua Baru</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 10px;">
                <tbody>
                    <tr><td style="width: 25%; padding: 1px 0;">a. Nama Ketua</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 73%; padding: 1px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                    <tr><td style="padding: 1px 0;">b. TTL</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:TTL]</td></tr>
                </tbody>
            </table>
        </div>
        
        <p style="text-align: justify; margin: 0 0 15px 0;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 55%;"></td>
                    <td style="width: 45%; text-align: left;">
                        Dikeluarkan di : [KOTA_DINAS]<br>
                        Pada Tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getPerubahanAlamatPKBM(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 5px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Berdasarkan Permohonan dari Yayasan Nomor: [DATA:NOMOR_PERMOHONAN], Tanggal: [DATA:TANGGAL_PERMOHONAN].</li>
            <li>Surat Keterangan Domisili dari Desa: [DATA:NOMOR_DOMISILI].</li>
            <li>Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_IZIN_OP].</li>
        </ol>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Ketua</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 5px 0;">Bahwa Lembaga tersebut diatas mengalami Perpindahan Alamat :</p>
        <div style="margin-left: 20px;">
            <strong>1. Alamat Lama</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 5px;">
                <tbody><tr><td style="width: 5%; padding: 1px 0;">a.</td><td style="width: 15%; padding: 1px 0;">Alamat</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 78%; padding: 1px 0;">[DATA:ALAMAT_LAMA]</td></tr></tbody>
            </table>
            <strong>2. Alamat Baru</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 10px;">
                <tbody><tr><td style="width: 5%; padding: 1px 0;">a.</td><td style="width: 15%; padding: 1px 0;">Alamat</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 78%; padding: 1px 0;">[ALAMAT_LEMBAGA]</td></tr></tbody>
            </table>
        </div>
        
        <p style="text-align: justify; margin: 0 0 15px 0;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 55%;"></td>
                    <td style="width: 45%; text-align: left;">
                        Dikeluarkan di : [KOTA_DINAS]<br>
                        Pada Tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getHerregistrasiPKBM(): string
    {
        return self::kopSuratLandscape() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN DAFTAR ULANG (HER-REGISTRASI)</span><br>
            <span style="font-size: 11pt; font-weight: bold;">Nomor: [NOMOR_SURAT]</span>
        </p>
        
        <p style="margin: 0 0 8px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :</p>
        
        <table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 28%; font-weight: bold; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 70%; font-weight: bold; padding: 2px 0;">[NAMA_LEMBAGA]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">Nama Pimpinan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="font-weight: bold; vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 8px 0;">Telah memiliki Izin Pendirian Kepala Dinas Kabupaten [KOTA_DINAS]:</p>
        
        <table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 28%; font-weight: bold; padding: 2px 0;">Nomor</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 70%; padding: 2px 0;">[DATA:NOMOR_IZIN_PENDIRIAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">Tanggal</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:TANGGAL_IZIN_PENDIRIAN]</td></tr>
            </tbody>
        </table>
        
        <p style="text-align: justify; margin: 0 0 15px 0; line-height: 1.15;">
            Berdasarkan kelengkapan proposal permohonan izin operasional PKBM, Lembaga tersebut telah melakukan daftar ulang (her-registrasi) pada Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong>.
        </p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 60%;"></td>
                    <td style="width: 40%; text-align: left;">
                        [KOTA_DINAS], [TANGGAL_TERBIT]<br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong>
                        <div style="height: 55px;"></div>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    /*
    |--------------------------------------------------------------------------
    | BAGIAN 2: TEMPLATE PAUD (BARU)
    |--------------------------------------------------------------------------
    */
    private static function getIzinMemimpinPAUD(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 10px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN IZIN MEMIMPIN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 2px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Surat Permohonan dari [NAMA_LEMBAGA], perihal Permohonan Izin Memimpin, nomor: [DATA:NOMOR_SURAT_LEMBAGA], tanggal [DATA:TGL_SURAT_LEMBAGA].</li>
            <li>Surat Keputusan Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] tentang Pemberian Ijin Operasional nomor: [DATA:NOMOR_SK_OPRASIONAL], tanggal [DATA:TGL_SK_OPRASIONAL].</li>
            <li>Surat Keputusan Pengangkatan Kepala Sekolah dari Ketua Yayasan [DATA:NAMA_YAYASAN], nomor: [DATA:NOMOR_SK_YAYASAN], tanggal [DATA:TGL_SK_YAYASAN].</li>
        </ol>
        <p style="text-align: justify; margin: 0 0 5px 0;">Berdasarkan hal tersebut di atas maka dengan ini Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] menerangkan bahwa:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[DATA:NAMA_PIMPINAN]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Tempat, tanggal lahir</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:TTL]</td></tr>
                <tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Kepala Sekolah / Pimpinan</td></tr>
                <tr><td style="padding: 2px 0;">Pendidikan Terakhir</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:PENDIDIKAN]</td></tr>
                <tr><td style="padding: 2px 0;">NUPTK</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NUPTK]</td></tr>
                <tr><td style="padding: 2px 0;">Unit Kerja</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NAMA_LEMBAGA]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
            </tbody>
        </table>
        
        <p style="text-align: justify; margin: 0 0 5px 0;">adalah benar sebagai Kepala Sekolah yang diangkat oleh [DATA:NAMA_YAYASAN] ditempatkan di lembaga [NAMA_LEMBAGA].</p>
        <p style="text-align: justify; margin: 0 0 5px 0;">Keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong> dengan ketentuan tidak ada perubahan terhadap penugasan Kepala Satuan Pendidikan yang ditunjuk oleh yayasan.</p>
        <p style="text-align: justify; margin: 0 0 15px 0;">Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 30%; text-align: center; vertical-align: bottom;">
                        <div style="width: 3cm; height: 4cm; border: 1px solid #000; margin: 0 auto; display: table;">
                            <span style="display: table-cell; vertical-align: middle; font-size: 10pt; color: #555;">FOTO<br>4x6</span>
                        </div>
                    </td>
                    <td style="width: 20%;"></td>
                    <td style="width: 50%; text-align: left; vertical-align: bottom;">
                        [KOTA_DINAS], [TANGGAL_TERBIT]<br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getPerubahanPimpinanYayasanPAUD(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 2px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Surat Permohonan dari Ketua Penyelenggara Satuan Pendidikan Nomor: [DATA:NOMOR_SURAT_PERMOHONAN], tanggal [DATA:TGL_SURAT_PERMOHONAN]. Perihal Penggantian Nama Penyelenggara Baru Satuan Pendidikan.</li>
            <li>Berita Acara Pergantian Nama Penyelenggara Nomor: [DATA:NOMOR_BA], tanggal [DATA:TGL_BA].</li>
        </ol>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 35%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 63%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara Baru</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:YAYASAN_BARU]</td></tr>
                <tr><td style="padding: 2px 0;">Ketua Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">Jenis Layanan Pendidikan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 5px 0;">Bahwa Lembaga tersebut terdapat Pergantian Nama Penyelenggara Satuan Pendidikan :</p>
        <div style="margin-left: 20px;">
            <strong>1. Nama Penyelenggara Lama</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 5px;">
                <tbody>
                    <tr><td style="width: 30%; padding: 1px 0;">Nama Penyelenggara</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 68%; padding: 1px 0;">[DATA:YAYASAN_LAMA]</td></tr>
                    <tr><td style="padding: 1px 0;">Nama Ketua Penyelenggara</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:KETUA_LAMA]</td></tr>
                    <tr><td style="padding: 1px 0;">No. SK Kemenkumham</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:SK_KEMENKUMHAM_LAMA]</td></tr>
                </tbody>
            </table>
            <strong>2. Nama Penyelenggara Baru</strong>
            <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 10px;">
                <tbody>
                    <tr><td style="width: 30%; padding: 1px 0;">Nama Penyelenggara</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 68%; padding: 1px 0;">[DATA:YAYASAN_BARU]</td></tr>
                    <tr><td style="padding: 1px 0;">Nama Ketua Penyelenggara</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                    <tr><td style="padding: 1px 0;">No. SK Kemenkumham</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:SK_KEMENKUMHAM_BARU]</td></tr>
                </tbody>
            </table>
        </div>
        
        <p style="text-align: justify; margin: 0 0 15px 0;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 55%;"></td>
                    <td style="width: 45%; text-align: left;">
                        Ditetapkan di : [KOTA_DINAS]<br>
                        Pada tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getPerubahanAlamatPAUD(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Pimpinan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="padding: 2px 0;">Jenis Layanan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 5px 0;">Bahwa Lembaga tersebut ada perubahan Berdasarkan :</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Surat Permohonan dari Pimpinan Yayasan / Pimpinan Lembaga Nomor: [DATA:NOMOR_SURAT_PERMOHONAN], tanggal [DATA:TGL_SURAT_PERMOHONAN]. Perihal Perubahan Alamat.</li>
            <li>Berita Acara Perubahan Alamat Nomor: [DATA:NOMOR_BA_ALAMAT], tanggal [DATA:TGL_BA_ALAMAT].</li>
        </ol>

        <p style="margin: 0 0 5px 0;">Mengalami Perubahan Alamat Lembaga Satuan Pendidikan :</p>
        <div style="margin-left: 20px;">
            <table style="width: 100%; border-collapse: collapse; margin-left: 5px; margin-bottom: 15px;">
                <tbody>
                    <tr>
                        <td style="width: 30%; font-weight: bold; padding: 2px 0;">Alamat Satuan Pendidikan Lama</td>
                        <td style="width: 2%; padding: 2px 0;">:</td>
                        <td style="width: 68%; padding: 2px 0;">[DATA:ALAMAT_LAMA]</td>
                    </tr>
                    <tr>
                        <td style="width: 30%; font-weight: bold; padding: 2px 0;">Alamat Satuan Pendidikan Baru</td>
                        <td style="width: 2%; padding: 2px 0;">:</td>
                        <td style="width: 68%; font-weight: bold; padding: 2px 0;">[ALAMAT_LEMBAGA]</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p style="text-align: justify; margin: 0 0 20px 0;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 55%;"></td>
                    <td style="width: 45%; text-align: left;">
                        Ditetapkan di : [KOTA_DINAS]<br>
                        Pada tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong><br><br><br><br>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getHerregistrasiPAUD(): string
    {
        return self::kopSuratLandscape() . '
        <p style="text-align: center; margin: 0 0 10px 0;">
            <span style="font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN IZIN OPERASIONAL (HER-REGISTRASI)</span><br>
            <span style="font-size: 11pt; font-weight: bold;">Nomor: [NOMOR_SURAT]</span>
        </p>
        
        <p style="margin: 0 0 5px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :</p>
        
        <table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 5px;">
            <tbody>
                <tr><td style="width: 28%; font-weight: bold; padding: 1px 0;">Nama Lembaga</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 70%; font-weight: bold; padding: 1px 0;">[NAMA_LEMBAGA]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Kepala Satuan Pendidikan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Nama Penyelenggara</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Jenis Layanan PAUD/DIKMAS</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td style="font-weight: bold; vertical-align: top; padding: 1px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 1px 0;">:</td><td style="padding: 1px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Kecamatan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:KECAMATAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Kabupaten</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[KOTA_DINAS]</td></tr>
            </tbody>
        </table>
        
        <p style="margin: 0 0 5px 0;">Telah memiliki Izin Pendirian Lembaga dari Dinas Pendidikan Kabupaten [KOTA_DINAS]:</p>
        
        <table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 5px;">
            <tbody>
                <tr><td style="width: 28%; font-weight: bold; padding: 1px 0;">Nomor</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 70%; padding: 1px 0;">[DATA:NOMOR_IZIN_PENDIRIAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">Tanggal</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:TANGGAL_IZIN_PENDIRIAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 1px 0;">NPSN</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[NPSN]</td></tr>
            </tbody>
        </table>
        
        <p style="text-align: justify; margin: 0 0 10px 0; line-height: 1.1;">
            Lembaga tersebut telah melakukan daftar ulang (her-registrasi) Pada Bidang PAUD dan Dikmas Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat Keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong> sejak diterbitkan.
        </p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 60%;"></td>
                    <td style="width: 40%; text-align: left;">
                        [KOTA_DINAS], [TANGGAL_TERBIT]<br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong>
                        <div style="height: 55px;"></div>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }

    private static function getSkPerubahanJenjangPAUD(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">
                KEPUTUSAN KEPALA DINAS PENDIDIKAN KABUPATEN [KOTA_DINAS]<br>
                NOMOR : [NOMOR_SURAT]
            </span><br><br>
            <span style="font-size: 11pt; text-transform: uppercase; font-weight: bold;">
                TENTANG PERUBAHAN JENIS LAYANAN PAUD<br>
                DARI [DATA:JENJANG_LAMA] MENJADI [DATA:JENJANG_BARU]<br>
                KABUPATEN [KOTA_DINAS]
            </span>
        </p>

        <p style="text-align: center; font-weight: bold; margin: 0 0 10px 0;">KEPALA DINAS PENDIDIKAN KABUPATEN [KOTA_DINAS]</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tbody>
                <tr>
                    <td style="width: 18%; vertical-align: top;">Menimbang</td>
                    <td style="width: 2%; vertical-align: top;">:</td>
                    <td style="width: 4%; vertical-align: top;">a.</td>
                    <td style="width: 76%; text-align: justify; vertical-align: top; padding-bottom: 5px;">bahwa untuk legalitas atau pengakuan dan persetujuan resmi atas status pendidikan anak usia dini dalam melaksanakan programnya perlu izin perubahan jenjang yang ditetapkan dengan keputusan Kepala Dinas Pendidikan;</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="vertical-align: top;">b.</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 5px;">bahwa untuk maksud butir a di atas, maka perlu ditetapkan dengan Keputusan Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS].</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tbody>
                <tr>
                    <td style="width: 18%; vertical-align: top;">Mengingat</td>
                    <td style="width: 2%; vertical-align: top;">:</td>
                    <td style="width: 4%; vertical-align: top;">1.</td>
                    <td style="width: 76%; text-align: justify; vertical-align: top; padding-bottom: 3px;">Undang-Undang Republik Indonesia Nomor 20 tahun 2003 tentang Sistem Pendidikan Nasional;</td>
                </tr>
                <tr>
                    <td colspan="2"></td><td style="vertical-align: top;">2.</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 3px;">Peraturan Pemerintah Nomor 17 Tahun 2010 tentang Pengelolaan dan Penyelenggaraan Pendidikan;</td>
                </tr>
                <tr>
                    <td colspan="2"></td><td style="vertical-align: top;">3.</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 3px;">Peraturan Menteri Pendidikan dan Kebudayaan Nomor 84 Tahun 2014 Tentang Pendirian Satuan Pendidikan Anak Usia Dini;</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tbody>
                <tr>
                    <td style="width: 18%; vertical-align: top;">Memperhatikan</td>
                    <td style="width: 2%; vertical-align: top;">:</td>
                    <td style="width: 4%; vertical-align: top;">1.</td>
                    <td style="width: 76%; text-align: justify; vertical-align: top; padding-bottom: 3px;">Surat Permohonan dari Pimpinan Yayasan Nomor : [DATA:NOMOR_SURAT_YAYASAN], tanggal [DATA:TGL_SURAT_YAYASAN] Perihal Perubahan Jenis Layanan Izin Oprasional;</td>
                </tr>
            </tbody>
        </table>

        <p style="text-align: center; font-weight: bold; margin: 15px 0; letter-spacing: 2px;">MEMUTUSKAN</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tbody>
                <tr>
                    <td style="width: 18%; vertical-align: top;">Menetapkan</td>
                    <td style="width: 2%; vertical-align: top;">:</td>
                    <td style="width: 80%; text-align: justify; vertical-align: top; font-weight: bold; padding-bottom: 5px;">KEPUTUSAN KEPALA DINAS PENDIDIKAN KABUPATEN [KOTA_DINAS] TENTANG PERUBAHAN JENIS LAYANAN PAUD DARI [DATA:JENJANG_LAMA] MENJADI [DATA:JENJANG_BARU]</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Pertama</td>
                    <td style="vertical-align: top;">:</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 5px;">Memberikan Izin Perubahan Jenis Layanan Pendidikan Anak Usia Dini (PAUD), Kepada :</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-left: 20%; width: 80%; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 40%; padding: 1px 0;">Nama Lembaga Baru</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 58%; padding: 1px 0; font-weight: bold;">[NAMA_LEMBAGA]</td></tr>
                <tr><td style="padding: 1px 0;">Nama Kepala Sekolah</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 1px 0;">Nama Penyelenggara</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="padding: 1px 0;">Jenis PAUD</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:JENJANG_BARU]</td></tr>
                <tr><td style="vertical-align: top; padding: 1px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 1px 0;">:</td><td style="padding: 1px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="padding: 1px 0;">NPSN</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[NPSN]</td></tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tbody>
                <tr>
                    <td style="width: 18%; vertical-align: top;">Kedua</td>
                    <td style="width: 2%; vertical-align: top;">:</td>
                    <td style="width: 80%; text-align: justify; vertical-align: top; padding-bottom: 5px;">Pemberian izin ini dimaksudkan agar lembaga [NAMA_LEMBAGA] dapat melaksanakan kegiatannya secara maksimal dengan baik dan terencana.</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Ketiga</td>
                    <td style="vertical-align: top;">:</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 5px;">Izin pada diktum pertama berlaku sepanjang memenuhi ketentuan dan apabila terjadi pelanggaran, maka izin ini akan dicabut kembali.</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Keempat</td>
                    <td style="vertical-align: top;">:</td>
                    <td style="text-align: justify; vertical-align: top; padding-bottom: 5px;">Keputusan ini mulai berlaku sejak tanggal ditetapkan dengan ketentuan akan diubah dan diperbaiki apabila dipandang perlu.</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid; margin-top: 10px;">
            <tbody>
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <p style="margin:0; font-size: 9pt; text-decoration: underline;">Tembusan :</p>
                        <ol style="margin: 0; padding-left: 15px; font-size: 9pt;">
                            <li>Yth. PJ Bupati Kab. [KOTA_DINAS]</li>
                            <li>Yth. Sekretaris Daerah Kab. [KOTA_DINAS]</li>
                            <li>Yth. Camat [DATA:KECAMATAN]</li>
                        </ol>
                    </td>
                    <td style="width: 50%; text-align: left; vertical-align: top;">
                        Ditetapkan di : [KOTA_DINAS]<br>
                        Pada tanggal : [TANGGAL_TERBIT]<br><br>
                        <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong>
                        <div style="height: 50px;"></div>
                        <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                        [PIMPINAN_PANGKAT]<br>
                        NIP. [PIMPINAN_NIP]
                    </td>
                </tr>
            </tbody>
        </table>
        ';
    }
}