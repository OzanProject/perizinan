<?php

namespace App\Services;

class CertificateTemplateService
{
    public static function getPresets()
    {
        return [
            'standar_izin' => [
                'name' => 'Template Resmi (Government Stable)',
                'description' => 'DomPDF safe. Single page. Deterministic layout.',
                'html' => self::getStandarIzin()
            ],
            'formal_perizinan' => [
                'name' => 'Formal Perizinan (Government Stable)',
                'description' => 'Layout resmi 1 halaman tanpa overflow.',
                'html' => self::getFormalPerizinan()
            ],
            'garut_premium' => [
                'name' => 'Garut Premium Style (Centered)',
                'description' => 'Layout premium dengan kop tengah dan tabel data lengkap.',
                'html' => self::getGarutPremium()
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | KOP SURAT - STABIL
    |--------------------------------------------------------------------------
    | - Gunakan TABLE
    | - Logo dikunci 70x70
    | - Tidak ada object-fit
    | - Tidak ada height:auto
    | - Tidak ada flex
    |--------------------------------------------------------------------------
    */
    private static function kopSurat(): string
    {
        return '
        <table width="100%" cellpadding="0" cellspacing="0"
               style="border-collapse:collapse; border-bottom:3px double #000; margin-bottom:10px;">
            <tr>
                <td width="80" style="vertical-align:middle; padding-bottom:5px;">
                    <img src="[LOGO_DINAS]"
                         width="60"
                         height="60"
                         style="display:block;"
                         onerror="this.style.display=\'none\'">
                </td>
                <td style="text-align:center; vertical-align:middle; padding-bottom:5px;">
                    <div style="font-size:14px; font-weight:bold; text-transform:uppercase; line-height:1.1;">
                        PEMERINTAH KABUPATEN [KOTA_DINAS]
                    </div>
                    <div style="font-size:18px; font-weight:bold; text-transform:uppercase; line-height:1.1;">
                        DINAS PENDIDIKAN
                    </div>
                    <div style="font-size:10px; line-height:1.1;">
                        [ALAMAT_DINAS]
                    </div>
                </td>
            </tr>
        </table>
        ';
    }

    private static function getStandarIzin()
    {
        return '
        <div style="font-family:\'Times New Roman\', serif; font-size:12pt; line-height:1.5; margin:0; padding:0;">
            ' . self::kopSurat() . '

            <div style="text-align:center; margin-bottom:15px;">
                <div style="font-size:16pt; font-weight:bold; text-decoration:underline; text-transform:uppercase;">
                    SURAT IZIN OPERASIONAL
                </div>
                <div style="font-weight:bold; margin-top:5px;">
                    Nomor : [NOMOR_SURAT]
                </div>
            </div>

            <div style="text-align:justify; margin-bottom:10px;">
                Berdasarkan hasil verifikasi administrasi, Pemerintah Kabupaten [KOTA_DINAS]
                melalui Dinas Pendidikan memberikan izin operasional kepada:
            </div>

            <table width="100%" cellpadding="0" cellspacing="0"
                   style="border-collapse:collapse; margin-bottom:15px;">
                <tr>
                    <td width="170">Nama Lembaga</td>
                    <td width="15">:</td>
                    <td><strong>[NAMA_LEMBAGA]</strong></td>
                </tr>
                <tr>
                    <td>NPSN</td>
                    <td>:</td>
                    <td>[NPSN]</td>
                </tr>
                <tr>
                    <td>Alamat Lembaga</td>
                    <td>:</td>
                    <td>[ALAMAT_LEMBAGA]</td>
                </tr>
                <tr>
                    <td>Masa Berlaku</td>
                    <td>:</td>
                    <td>[MASA_BERLAKU]</td>
                </tr>
            </table>

            <div style="text-align:justify; margin-bottom:25px;">
                Surat izin ini berlaku sesuai ketentuan peraturan perundang-undangan yang berlaku.
            </div>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"></td>
                    <td width="45%" style="text-align:center;">
                        <div>[KOTA_DINAS], [TANGGAL_TERBIT]</div>
                        <div style="margin-top:5px; font-weight:bold;">
                            Kepala Dinas Pendidikan
                        </div>
                        <div style="margin-top:60px; font-weight:bold; text-decoration:underline;">
                            [PIMPINAN_NAMA]
                        </div>
                        <div>[PIMPINAN_JABATAN]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
        ';
    }

    private static function getFormalPerizinan()
    {
        return '
        <div style="font-family:\'Times New Roman\', serif; font-size:12pt; line-height:1.5; margin:0; padding:0;">
            ' . self::kopSurat() . '

            <div style="text-align:center; margin-bottom:15px;">
                <div style="font-size:15pt; font-weight:bold; text-decoration:underline; text-transform:uppercase;">
                    SERTIFIKAT IZIN PENDIRIAN SATUAN PENDIDIKAN
                </div>
                <div style="font-weight:bold; margin-top:5px;">
                    Nomor : [NOMOR_SURAT]
                </div>
            </div>

            <div style="text-align:justify; margin-bottom:10px;">
                Memberikan izin pendirian kepada satuan pendidikan sebagai berikut:
            </div>

            <table width="100%" cellpadding="0" cellspacing="0"
                   style="border-collapse:collapse; margin-bottom:15px;">
                <tr>
                    <td width="200">1. Nama Satuan Pendidikan</td>
                    <td width="15">:</td>
                    <td><strong>[NAMA_LEMBAGA]</strong></td>
                </tr>
                <tr>
                    <td>2. NPSN</td>
                    <td>:</td>
                    <td>[NPSN]</td>
                </tr>
                <tr>
                    <td>3. Alamat</td>
                    <td>:</td>
                    <td>[ALAMAT_LEMBAGA]</td>
                </tr>
                <tr>
                    <td>4. Tanggal Terbit</td>
                    <td>:</td>
                    <td>[TANGGAL_TERBIT]</td>
                </tr>
                <tr>
                    <td>5. Masa Berlaku</td>
                    <td>:</td>
                    <td>[MASA_BERLAKU]</td>
                </tr>
            </table>

            <div style="text-align:justify; margin-bottom:25px;">
                Keputusan ini berlaku sejak tanggal ditetapkan dan dapat diperbaiki apabila terdapat kekeliruan.
            </div>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"></td>
                    <td width="45%" style="text-align:center;">
                        <div>Ditetapkan di [KOTA_DINAS]</div>
                        <div>Pada tanggal [TANGGAL_TERBIT]</div>
                        <div style="margin-top:5px; font-weight:bold;">
                            Kepala Dinas Pendidikan
                        </div>
                        <div style="margin-top:60px; font-weight:bold; text-decoration:underline;">
                            [PIMPINAN_NAMA]
                        </div>
                        <div>[PIMPINAN_JABATAN]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
        ';
    }
    private static function getGarutPremium()
    {
        return '
        <div style="font-family:\'Times New Roman\', serif; font-size:10.5pt; line-height:1.1; margin:0; padding:5px;">
            <div style="text-align:center; margin-bottom:5px;">
                <div style="display:inline-block; margin-bottom:2px;">[LOGO_DINAS]</div>
                <div style="font-size:13pt; font-weight:bold; text-transform:uppercase; line-height:1.1;">PEMERINTAH KABUPATEN [KOTA_DINAS]</div>
                <div style="font-size:16pt; font-weight:bold; text-transform:uppercase; line-height:1.1;">DINAS PENDIDIKAN</div>
                <div style="font-size:9pt; font-style:normal;">[ALAMAT_DINAS]</div>
            </div>
 
            <div style="border-bottom:3px double #000; margin-bottom:10px;"></div>
 
            <div style="text-align:center; margin-bottom:10px;">
                <div style="font-size:12pt; font-weight:bold; text-transform:uppercase;">SURAT KETERANGAN DAFTAR ULANG ( HER-REGISTRASI )</div>
                <div style="font-weight:bold;">Nomor : [NOMOR_SURAT]</div>
            </div>
 
            <div style="margin-bottom:5px;">
                Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :
            </div>
 
            <table width="100%" cellpadding="1" cellspacing="0" style="border-collapse:collapse; margin-bottom:10px; margin-left:40px;">
                <tr><td width="160">Nama Lembaga</td><td width="10">:</td><td style="font-weight:bold;">[NAMA_LEMBAGA]</td></tr>
                <tr><td>Nama Pimpinan</td><td>:</td><td>[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td>Nama Penyelenggara</td><td>:</td><td>[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td>NPSN</td><td>:</td><td>[NPSN]</td></tr>
                <tr><td>Kode/ Jenis Pendidikan</td><td>:</td><td>[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td>Alamat Lembaga</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
                <tr><td>Kecamatan</td><td>:</td><td>[DATA:KECAMATAN]</td></tr>
                <tr><td>Kabupaten</td><td>:</td><td>[KOTA_DINAS]</td></tr>
            </table>
 
            <div style="margin-bottom:5px;">
                Telah memiliki Izin Pendirian Kepala Dinas Kabupaten [KOTA_DINAS] :
            </div>
 
            <table width="100%" cellpadding="1" cellspacing="0" style="border-collapse:collapse; margin-bottom:10px; margin-left:40px;">
                <tr><td width="160">Nomor</td><td width="10">:</td><td>[DATA:NOMOR_IZIN_PENDIRIAN]</td></tr>
                <tr><td>Tanggal</td><td>:</td><td>[DATA:TANGGAL_IZIN_PENDIRIAN]</td></tr>
            </table>
 
            <div style="text-align:justify; margin-bottom:10px;">
                Berdasarkan kelengkapan proposal permohonan izin operasional Lembaga Kursus dan Pelatihan (LKP), LKP tersebut telah melakukan daftar ulang (her-registrasi) pada Bidang Pendidikan Anak Usia Dini (PAUD) dan DIKMAS Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama 2 ( Dua ) tahun sejak diterbitkan.
            </div>
 
            <div class="signature-block" style="page-break-inside: avoid; margin-top: 5px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
                    <tr>
                        <td width="55%"></td>
                        <td width="45%" style="text-align:center; font-size: 10pt; line-height: 1.1;">
                            <div>[KOTA_DINAS], [TANGGAL_TERBIT]</div>
                            <div style="margin-top:2px; font-weight:bold; text-transform:uppercase;">KEPALA</div>
                            <div style="margin-top:40px; font-weight:bold; text-decoration:none;">[PIMPINAN_NAMA]</div>
                            <div style="font-weight:bold;">[PIMPINAN_PANGKAT]</div>
                            <div style="margin-top: 1px;">NIP. [PIMPINAN_NIP]</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        ';
    }
}