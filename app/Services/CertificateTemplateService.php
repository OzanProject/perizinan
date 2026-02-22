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
               style="border-collapse:collapse; border-bottom:4px double #000; margin-bottom:15px;">
            <tr>
                <td width="90" style="vertical-align:middle; padding-bottom:10px;">
                    <img src="[LOGO_DINAS]"
                         width="70"
                         height="70"
                         style="display:block;"
                         onerror="this.style.display=\'none\'">
                </td>
                <td style="text-align:center; vertical-align:middle; padding-bottom:10px;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase; line-height:1.2;">
                        PEMERINTAH KABUPATEN [KOTA_DINAS]
                    </div>
                    <div style="font-size:20px; font-weight:bold; text-transform:uppercase; line-height:1.2;">
                        DINAS PENDIDIKAN
                    </div>
                    <div style="font-size:12px; line-height:1.2;">
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
}