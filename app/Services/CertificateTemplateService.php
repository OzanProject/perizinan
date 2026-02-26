<?php

namespace App\Services;

class CertificateTemplateService
{
    public static function getPresets()
    {
        return [
            'izin_memimpin' => [
                'name' => '1. Izin Memimpin (Portrait)',
                'description' => 'Format Surat Keterangan Izin Memimpin resmi. A4/F4 Portrait, tanpa bingkai.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getIzinMemimpin()
            ],
            'perubahan_ketua' => [
                'name' => '2. Izin Perubahan Ketua (Portrait)',
                'description' => 'Format Surat Keterangan Perubahan Ketua dengan data Ketua Lama & Baru.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanKetua()
            ],
            'perubahan_alamat' => [
                'name' => '3. Izin Perubahan Alamat (Portrait)',
                'description' => 'Format Surat Keterangan Perubahan Alamat dengan data Alamat Lama & Baru.',
                'paper_size' => 'F4',
                'orientation' => 'portrait',
                'use_border' => false,
                'html' => self::getPerubahanAlamat()
            ],
            'heregister' => [
                'name' => '4. HER-REGISTRASI (Landscape + Bingkai)',
                'description' => 'Persis PDF Garut 2020: Sertifikat Daftar Ulang berornamen bingkai. Landscape F4.',
                'paper_size' => 'F4',
                'orientation' => 'landscape',
                'use_border' => true,
                'html' => self::getHerregistrasi()
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | KOP SURAT - SPASI DIPADATKAN
    |--------------------------------------------------------------------------
    */
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

    // =========================================================================
    // 1. IZIN MEMIMPIN (Portrait)
    // =========================================================================
    private static function getIzinMemimpin(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN IZIN MEMIMPIN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 5px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Surat Permohonan dari Yayasan/ Lembaga PKBM nomor [DATA:NOMOR_SURAT_YAYASAN] tanggal: [DATA:TGL_SURAT_YAYASAN], Perihal: Permohonan Izin Memimpin PKBM.</li>
            <li>Keputusan Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_SK_PENDIRIAN], Tanggal [DATA:TGL_SK_PENDIRIAN], tentang Izin Pendirian Pusat Kegiatan Belajar Masyarakat (PKBM) [NAMA_LEMBAGA].</li>
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

    // =========================================================================
    // 2. PERUBAHAN KETUA (Portrait)
    // =========================================================================
    private static function getPerubahanKetua(): string
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
            <li>Berdasarkan SK Pengangkatan Ketua dari Yayasan Nomor: [DATA:NOMOR_SK_YAYASAN], Tanggal: [DATA:TANGGAL_SK_YAYASAN].</li>
            <li>Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_IZIN_OP], Tanggal: [DATA:TANGGAL_IZIN_OP].</li>
        </ol>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Ketua</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="padding: 2px 0;">Desa/Kelurahan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:DESA]</td></tr>
                <tr><td style="padding: 2px 0;">Kecamatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:KECAMATAN]</td></tr>
                <tr><td style="padding: 2px 0;">Kabupaten</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[KOTA_DINAS]</td></tr>
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

    // =========================================================================
    // 3. PERUBAHAN ALAMAT (Portrait)
    // =========================================================================
    private static function getPerubahanAlamat(): string
    {
        return self::kopSurat() . '
        <p style="text-align: center; margin: 0 0 15px 0;">
            <span style="font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</span><br>
            <span style="font-size: 11pt;">Nomor: [NOMOR_SURAT]</span>
        </p>
        <p style="margin: 0 0 5px 0;">Dasar:</p>
        <ol style="padding-left: 20px; text-align: justify; margin: 0 0 10px 0;">
            <li>Berdasarkan Permohonan dari Yayasan Nomor: [DATA:NOMOR_PERMOHONAN], Tanggal: [DATA:TANGGAL_PERMOHONAN].</li>
            <li>Surat Keterangan Domisili dari Desa: [DATA:NOMOR_DOMISILI], Tanggal: [DATA:TANGGAL_DOMISILI].</li>
            <li>Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor: [DATA:NOMOR_IZIN_OP], Tanggal: [DATA:TANGGAL_IZIN_OP].</li>
        </ol>
        <p style="margin: 0 0 10px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
            <tbody>
                <tr><td style="width: 30%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 68%; padding: 2px 0;"><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td style="padding: 2px 0;">Nama Ketua</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
                <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td style="vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="padding: 2px 0;">Desa/Kelurahan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:DESA]</td></tr>
                <tr><td style="padding: 2px 0;">Kecamatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:KECAMATAN]</td></tr>
                <tr><td style="padding: 2px 0;">Kabupaten</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[KOTA_DINAS]</td></tr>
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

    // =========================================================================
    // 4. HER-REGISTRASI (Landscape) - SPASI SUPER RAPAT
    // =========================================================================
    private static function getHerregistrasi(): string
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
                <tr><td style="font-weight: bold; padding: 2px 0;">Kode/Jenis Pendidikan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td style="font-weight: bold; vertical-align: top; padding: 2px 0;">Alamat Lembaga</td><td style="vertical-align: top; padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">Kecamatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:KECAMATAN]</td></tr>
                <tr><td style="font-weight: bold; padding: 2px 0;">Kabupaten</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[KOTA_DINAS]</td></tr>
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
            Berdasarkan kelengkapan proposal permohonan izin operasional Lembaga Kursus dan Pelatihan (LKP), LKP tersebut telah melakukan daftar ulang (her-registrasi) pada Bidang Pendidikan Anak Usia Dini (PAUD) dan DIKMAS Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong> sejak diterbitkan.
        </p>
        
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="width: 60%;"></td>
                    <td style="width: 40%; text-align: left;">
                        [KOTA_DINAS], [TANGGAL_TERBIT]<br><br>
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
}