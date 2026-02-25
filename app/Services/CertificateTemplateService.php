<?php

namespace App\Services;

class CertificateTemplateService
{
    public static function getPresets()
    {
        return [
            'izin_memimpin' => [
                'name' => 'Izin Memimpin (Portrait)',
                'description' => 'Format Surat Keterangan Izin Memimpin resmi Garut. A4 Portrait, tanpa bingkai.',
                'html' => self::getIzinMemimpin()
            ],
            'perubahan_ketua' => [
                'name' => 'Izin Perubahan Ketua (Portrait)',
                'description' => 'Format Surat Keterangan Perubahan Ketua dengan data Ketua Lama & Baru.',
                'html' => self::getPerubahanKetua()
            ],
            'perubahan_alamat' => [
                'name' => 'Izin Perubahan Alamat (Portrait)',
                'description' => 'Format Surat Keterangan Perubahan Alamat dengan data Alamat Lama & Baru.',
                'html' => self::getPerubahanAlamat()
            ],
            'heregister' => [
                'name' => 'HER-REGISTRASI (Landscape + Bingkai)',
                'description' => 'Format Sertifikat Daftar Ulang berornamen bingkai. Landscape F4.',
                'paper_size' => 'F4',
                'orientation' => 'landscape',
                'html' => self::getHerregistrasi()
            ],
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

    // =========================================================================
    // FORMAT RESMI GARUT - IZIN MEMIMPIN (PORTRAIT, NO BORDER)
    // =========================================================================
    private static function getIzinMemimpin(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0;">
            ' . self::kopSurat() . '
            <div style="text-align: center; margin-bottom: 15px;">
                <div style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN IZIN MEMIMPIN</div>
                <div style="font-weight: bold; margin-top: 5px;">Nomor : [NOMOR_SURAT]</div>
            </div>
            <div style="margin-bottom: 12px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Dasar :</div>
                <table width="100%" cellpadding="2" cellspacing="0">
                    <tr><td width="20" valign="top">1.</td><td valign="top" style="text-align:justify;">Surat Permohonan dari Yayasan/ Lembaga PKBM nomor : [DATA:NOMOR_PERMOHONAN] tanggal : [DATA:TANGGAL_PERMOHONAN], Perihal : [DATA:PERIHAL_PERMOHONAN]</td></tr>
                    <tr><td width="20" valign="top">2.</td><td valign="top" style="text-align:justify;">Keputusan Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor : [DATA:NOMOR_SK_KADIS], Tanggal [DATA:TANGGAL_SK_KADIS], tentang Izin Pendirian Pusat Kegiatan Belajar Masyarakat ( PKBM ) [UNIT_KERJA].</td></tr>
                    <tr><td width="20" valign="top">3.</td><td valign="top" style="text-align:justify;">Surat Keputusan Ketua Yayasan [DATA:NAMA_YAYASAN], Nomor : [DATA:NOMOR_SK_YAYASAN], tanggal : [DATA:TANGGAL_SK_YAYASAN], Perihal : Pengangkatan Ketua Penyelenggara.</td></tr>
                </table>
            </div>
            <div style="margin-bottom: 10px; text-align: justify;">Berdasarkan hal tersebut diatas, maka dengan ini Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] menerangkan bahwa :</div>
            <table width="100%" cellpadding="3" cellspacing="0" style="margin-left: 20px; margin-bottom: 15px;">
                <tr><td width="170">Nama</td><td width="10">:</td><td><strong>[NAMA]</strong></td></tr>
                <tr><td>Tempat , Tanggal Lahir</td><td>:</td><td>[TTL]</td></tr>
                <tr><td>PendidikanTerakhir</td><td>:</td><td>[DATA:PENDIDIKAN]</td></tr>
                <tr><td>Jabatan</td><td>:</td><td>[JABATAN]</td></tr>
                <tr><td>Unit Kerja</td><td>:</td><td>[UNIT_KERJA]</td></tr>
                <tr><td>NPSN</td><td>:</td><td>[NPSN]</td></tr>
                <tr><td>Alamat</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
            </table>
            <div style="text-align: justify; margin-bottom: 10px;">Adalah benar sebagai [JABATAN] di [UNIT_KERJA] Kecamatan [DATA:KECAMATAN] yang diangkat oleh Yayasan [DATA:NAMA_YAYASAN].</div>
            <div style="text-align: justify; margin-bottom: 10px;">Keterangan ini berlaku selama 2 (dua) tahun dengan ketentuan tidak ada perubahan terhadap penugasan Ketua Penyelenggara yang ditunjuk oleh yayasan.</div>
            <div style="text-align: justify; margin-bottom: 30px;">Demikian surat keterangan memimpin ini dibuat untuk dipergunakan sebagaimana mestinya.</div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"></td>
                    <td style="text-align: center;">
                        <div>Dikeluarkan &nbsp;: [KOTA_DINAS]</div>
                        <div>Pada Tanggal : [TANGGAL_TERBIT]</div>
                        <div style="margin-top: 5px; font-weight: bold; text-transform: uppercase;">KEPALA</div>
                        <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">[PIMPINAN_NAMA]</div>
                        <div>[PIMPINAN_PANGKAT]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
        ';
    }

    // =========================================================================
    // FORMAT RESMI GARUT - PERUBAHAN KETUA (PORTRAIT, NO BORDER)
    // =========================================================================
    private static function getPerubahanKetua(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0;">
            ' . self::kopSurat() . '
            <div style="text-align: center; margin-bottom: 15px;">
                <div style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</div>
                <div style="font-weight: bold; margin-top: 5px;">Nomor : [NOMOR_SURAT]</div>
            </div>
            <div style="margin-bottom: 12px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Dasar :</div>
                <table width="100%" cellpadding="2" cellspacing="0">
                    <tr><td width="30" valign="top">1.</td><td valign="top">Berdasarkan Permohonan dari Yayasan Nomor : [DATA:NOMOR_PERMOHONAN], Tanggal: [DATA:TANGGAL_PERMOHONAN].</td></tr>
                    <tr><td width="30" valign="top">2.</td><td valign="top">Berdasarkan Berita Acara Perubahan Ketua, Nomor : [DATA:NOMOR_BA], Tanggal : [DATA:TANGGAL_BA].</td></tr>
                    <tr><td width="30" valign="top">3.</td><td valign="top">Berdasarkan SK Pengangkatan Ketua dari Yayasan : [DATA:NOMOR_SK_YAYASAN], Tanggal : [DATA:TANGGAL_SK_YAYASAN].</td></tr>
                    <tr><td width="30" valign="top">4.</td><td valign="top">Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor : [DATA:NOMOR_IZIN_OP], Tanggal: [DATA:TANGGAL_IZIN_OP].</td></tr>
                </table>
            </div>
            <div style="margin-bottom: 8px;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</div>
            <table width="100%" cellpadding="3" cellspacing="0" style="margin-bottom: 15px;">
                <tr><td width="170">Nama Lembaga</td><td width="10">:</td><td><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td>Nama Ketua</td><td>:</td><td>[DATA:NAMA_KETUA]</td></tr>
                <tr><td>NPSN</td><td>:</td><td>[DATA:NPSN]</td></tr>
                <tr><td>Nama Penyelenggara</td><td>:</td><td>[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td>Alamat Lembaga</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
                <tr><td>Desa/Kelurahan</td><td>:</td><td>[DATA:DESA]</td></tr>
                <tr><td>Kecamatan</td><td>:</td><td>[DATA:KECAMATAN]</td></tr>
                <tr><td>Kabupaten</td><td>:</td><td>[KOTA_DINAS]</td></tr>
            </table>
            <div style="font-weight: bold; margin-bottom: 10px;">Bahwa Lembaga tersebut di atas mengalami Pergantian Ketua :</div>
            <div style="margin-left: 20px; margin-bottom: 15px;">
                <div style="margin-bottom: 5px;">1. &nbsp;&nbsp;&nbsp; Ketua Lama</div>
                <table width="100%" cellpadding="2" cellspacing="0" style="margin-left: 10px;">
                    <tr><td width="170">a. &nbsp; Nama Ketua</td><td width="10">:</td><td>[DATA:KETUA_LAMA_NAMA]</td></tr>
                    <tr><td>b. &nbsp; TTL</td><td>:</td><td>[DATA:KETUA_LAMA_TTL]</td></tr>
                </table>
                <div style="margin-top: 10px; margin-bottom: 5px;">2. &nbsp;&nbsp;&nbsp; Ketua Baru</div>
                <table width="100%" cellpadding="2" cellspacing="0" style="margin-left: 10px;">
                    <tr><td width="170">a. &nbsp; Nama Ketua</td><td width="10">:</td><td>[DATA:KETUA_BARU_NAMA]</td></tr>
                    <tr><td>b. &nbsp; TTL</td><td>:</td><td>[DATA:KETUA_BARU_TTL]</td></tr>
                </table>
            </div>
            <div style="margin-bottom: 30px; text-align: justify;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"></td>
                    <td style="text-align: center;">
                        <div>Dikeluarkan &nbsp;: [KOTA_DINAS]</div>
                        <div>Pada Tanggal : [TANGGAL_TERBIT]</div>
                        <div style="margin-top: 5px; font-weight: bold; text-transform: uppercase;">KEPALA</div>
                        <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">[PIMPINAN_NAMA]</div>
                        <div>[PIMPINAN_PANGKAT]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
        ';
    }

    // =========================================================================
    // FORMAT RESMI GARUT - PERUBAHAN ALAMAT (PORTRAIT, NO BORDER)
    // =========================================================================
    private static function getPerubahanAlamat(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0;">
            ' . self::kopSurat() . '
            <div style="text-align: center; margin-bottom: 15px;">
                <div style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN</div>
                <div style="font-weight: bold; margin-top: 5px;">Nomor : [NOMOR_SURAT]</div>
            </div>
            <div style="margin-bottom: 12px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Dasar :</div>
                <table width="100%" cellpadding="2" cellspacing="0">
                    <tr><td width="30" valign="top">1.</td><td valign="top">Berdasarkan Permohonan dari Yayasan Nomor : [DATA:NOMOR_PERMOHONAN], Tanggal: [DATA:TANGGAL_PERMOHONAN].</td></tr>
                    <tr><td width="30" valign="top">2.</td><td valign="top">Surat Keterangan Domisili dari Desa : [DATA:NOMOR_DOMISILI], Tanggal : [DATA:TANGGAL_DOMISILI].</td></tr>
                    <tr><td width="30" valign="top">3.</td><td valign="top">Berdasarkan Izin Operasional dari Dinas Pendidikan Kabupaten [KOTA_DINAS] Nomor : [DATA:NOMOR_IZIN_OP], Tanggal: [DATA:TANGGAL_IZIN_OP].</td></tr>
                </table>
            </div>
            <div style="margin-bottom: 8px;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS] Menerangkan :</div>
            <table width="100%" cellpadding="3" cellspacing="0" style="margin-bottom: 15px;">
                <tr><td width="170">Nama Lembaga</td><td width="10">:</td><td><strong>[NAMA_LEMBAGA]</strong></td></tr>
                <tr><td>Nama Ketua</td><td>:</td><td>[DATA:NAMA_KETUA]</td></tr>
                <tr><td>NPSN</td><td>:</td><td>[DATA:NPSN]</td></tr>
                <tr><td>Nama Penyelenggara</td><td>:</td><td>[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td>Alamat Lembaga</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
                <tr><td>Desa/Kelurahan</td><td>:</td><td>[DATA:DESA]</td></tr>
                <tr><td>Kecamatan</td><td>:</td><td>[DATA:KECAMATAN]</td></tr>
                <tr><td>Kabupaten</td><td>:</td><td>[KOTA_DINAS]</td></tr>
            </table>
            <div style="font-weight: bold; margin-bottom: 10px;">Bahwa Lembaga tersebut di atas mengalami Perpindahan Alamat :</div>
            <div style="margin-left: 20px; margin-bottom: 15px;">
                <div style="margin-bottom: 5px;">1. &nbsp;&nbsp;&nbsp; Alamat Lama</div>
                <table width="100%" cellpadding="2" cellspacing="0" style="margin-left: 10px;">
                    <tr><td width="50">a. &nbsp; Alamat</td><td width="10">:</td><td>[DATA:ALAMAT_LAMA]</td></tr>
                </table>
                <div style="margin-top: 10px; margin-bottom: 5px;">2. &nbsp;&nbsp;&nbsp; Alamat Baru</div>
                <table width="100%" cellpadding="2" cellspacing="0" style="margin-left: 10px;">
                    <tr><td width="50">a. &nbsp; Alamat</td><td width="10">:</td><td>[DATA:ALAMAT_BARU]</td></tr>
                </table>
            </div>
            <div style="margin-bottom: 30px; text-align: justify;">Demikian Surat Keterangan ini kami buat agar yang berkepentingan menjadi maklum.</div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="55%"></td>
                    <td style="text-align: center;">
                        <div>Dikeluarkan &nbsp;: [KOTA_DINAS]</div>
                        <div>Pada Tanggal : [TANGGAL_TERBIT]</div>
                        <div style="margin-top: 5px; font-weight: bold; text-transform: uppercase;">KEPALA</div>
                        <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">[PIMPINAN_NAMA]</div>
                        <div>[PIMPINAN_PANGKAT]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
        ';
    }

    // =========================================================================
    // FORMAT RESMI GARUT - HER-REGISTRASI (LANDSCAPE + BINGKAI EMAS)
    // =========================================================================
    private static function getHerregistrasi(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.35; margin: 0; padding: 0mm; background: transparent; position: relative; width: 100%; min-height: 200mm;">
            <div style="text-align: center; margin-bottom: 12px; padding-top: 10px;">
                <div style="margin-bottom: 8px;">
                    <img src="[LOGO_DINAS]" width="75" height="75" style="display:inline-block; margin-bottom: 5px;">
                </div>
                <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase; line-height: 1.1;">PEMERINTAH KABUPATEN [KOTA_DINAS]</div>
                <div style="font-size: 20pt; font-weight: bold; text-transform: uppercase; line-height: 1.1;">DINAS PENDIDIKAN</div>
                <div style="font-size: 10pt; line-height: 1.1; margin-top: 3px;">[ALAMAT_DINAS]</div>
                <div style="border-bottom: 2px solid #000; margin-top: 10px; margin-left: auto; margin-right: auto; width: 100%;"></div>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <div style="font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN DAFTAR ULANG ( HER-REGISTRASI )</div>
                <div style="font-weight: bold; margin-top: 6px;">Nomor : [NOMOR_SURAT] &nbsp;&nbsp; - Disdik</div>
            </div>

            <div style="margin-bottom: 10px; padding-left: 30px;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :</div>
            
            <table width="90%" cellpadding="3" cellspacing="0" style="margin-left: 80px; margin-bottom: 20px;">
                <tr><td width="200">Nama Lembaga</td><td width="15">:</td><td style="font-weight: bold;">[NAMA_LEMBAGA]</td></tr>
                <tr><td>Nama Pimpinan</td><td>:</td><td>[DATA:NAMA_PIMPINAN]</td></tr>
                <tr><td>Nama Penyelenggara</td><td>:</td><td>[DATA:NAMA_PENYELENGGARA]</td></tr>
                <tr><td>NPSN</td><td>:</td><td>[NPSN]</td></tr>
                <tr><td>Kode/ Jenis Pendidikan</td><td>:</td><td>[DATA:JENIS_PENDIDIKAN]</td></tr>
                <tr><td>Alamat Lembaga</td><td>:</td><td>[ALAMAT_LEMBAGA]</td></tr>
                <tr><td>Kecamatan</td><td>:</td><td>[DATA:KECAMATAN]</td></tr>
                <tr><td>Kabupaten</td><td>:</td><td>[KOTA_DINAS]</td></tr>
            </table>

            <div style="margin-bottom: 6px; margin-left: 30px;">Telah memiliki Izin Pendirian Kepala Dinas Kabupaten [KOTA_DINAS] :</div>
            <table width="50%" cellpadding="3" cellspacing="0" style="margin-left: 100px; margin-bottom: 20px;">
                <tr><td width="150">Nomor</td><td width="15">:</td><td>[DATA:SK_NOMOR]</td></tr>
                <tr><td>Tanggal</td><td>:</td><td>[DATA:SK_TANGGAL]</td></tr>
            </table>

            <div style="text-align: justify; margin-bottom: 25px; padding-left: 30px; padding-right: 30px;">
                Berdasarkan kelengkapan proposal permohonan izin operasional Lembaga Kursus dan Pelatihan (LKP), LKP tersebut telah melakukan daftar ulang (her-registrasi) pada Bidang Pendidikan Anak Usia Dini (PAUD) dan DIKMAS Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama 2 ( Dua ) tahun sejak diterbitkan.
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 15px;">
                <tr>
                    <td width="60%"></td>
                    <td style="text-align: center;">
                        <div style="margin-bottom: 4px;">[KOTA_DINAS], &nbsp; [TANGGAL_TERBIT]</div>
                        <div style="font-weight: bold; text-transform: uppercase;">KEPALA</div>
                        <div style="margin-top: 70px; font-weight: bold; text-decoration: underline;">[PIMPINAN_NAMA]</div>
                        <div>[PIMPINAN_PANGKAT]</div>
                        <div>NIP. [PIMPINAN_NIP]</div>
                    </td>
                </tr>
            </table>
        </div>
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