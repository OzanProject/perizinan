<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\JenisPerizinan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $html = <<<HTML
<table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
    <tbody>
        <tr>
            <td style="width: 15%; vertical-align: middle; text-align: center;">[LOGO_DINAS]</td>
            <td style="width: 85%; text-align: center; vertical-align: middle; padding-right: 15%;">
                <span style="font-size: 14pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH KABUPATEN [KOTA_DINAS]</span><br>
                <span style="font-size: 18pt; font-weight: bold; text-transform: uppercase;">DINAS PENDIDIKAN</span><br>
                <span style="font-size: 9.5pt;">[ALAMAT_DINAS]</span>
            </td>
        </tr>
    </tbody>
</table>
<hr style="border: none; border-top: 3px solid black; border-bottom: 1px solid black; height: 3px; background: transparent; margin: 0 0 10px 0;">

<p style="text-align: center; margin: 0 0 10px 0;">
    <span style="font-size: 11.5pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN DAFTAR ULANG ( HER-REGISTRASI )</span>
</p>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
    <tbody>
        <tr>
            <td style="width: 50%; padding: 1px 0;">Nomor : [NOMOR_SURAT]</td>
            <td style="width: 50%; padding: 1px 0;">- Didik</td>
        </tr>
    </tbody>
</table>

<p style="margin: 0 0 6px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :</p>

<table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 8px;">
    <tbody>
        <tr><td style="width: 30%; padding: 1px 0;">Nama Lembaga</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 68%; font-weight: bold; padding: 1px 0;">[NAMA_LEMBAGA]</td></tr>
        <tr><td style="padding: 1px 0;">Alamat Lembaga</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[ALAMAT_LEMBAGA]</td></tr>
        <tr><td style="padding: 1px 0;">Kecamatan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:KECAMATAN]</td></tr>
        <tr><td style="padding: 1px 0;">Kabupaten</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[KOTA_DINAS]</td></tr>
    </tbody>
</table>

<p style="margin: 0 0 6px 0;">Telah mempunyai Izin Pendirian Kepala Dinas Pendidikan kabupaten [KOTA_DINAS] :</p>

<table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 10px;">
    <tbody>
        <tr><td style="width: 30%; padding: 1px 0;">Nomor Izin</td><td style="width: 2%; padding: 1px 0;">:</td><td style="width: 68%; padding: 1px 0;">[DATA:NOMOR_IZIN_PENDIRIAN]</td></tr>
        <tr><td style="padding: 1px 0;">Tanggal</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:TANGGAL_IZIN_PENDIRIAN]</td></tr>
        <tr><td style="padding: 1px 0;">NPSN</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[NPSN]</td></tr>
        <tr><td style="padding: 1px 0;">Nama Pimpinan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
        <tr><td style="padding: 1px 0;">Pemilik/ Penyelenggara</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
        <tr><td style="padding: 1px 0;">Akreditasi</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:AKREDITASI]</td></tr>
        <tr><td style="padding: 1px 0;">TMT</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">[DATA:TMT]</td></tr>
    </tbody>
</table>

<p style="text-align: justify; margin: 0 0 10px 0; line-height: 1.3;">
    PKBM tersebut telah melakukan daftar ulang (her-registrasi) pada Bidang PAUD dan Dikmas Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong> terhitung tanggal [TANGGAL_TERBIT] sampai dengan [DATA:TANGGAL_AKHIR_BERLAKU]
</p>

<table style="width: 100%; border-collapse: collapse; page-break-inside: avoid; margin-top: 5px;">
    <tbody>
        <tr>
            <td style="width: 60%; vertical-align: bottom; text-align: left; padding-left: 15px;">
                [QR_CODE]<br>
                <span style="font-size: 8px; color: #555; margin-top: 2px; margin-left: 3px;">Scan untuk<br>Verifikasi Asli</span>
            </td>
            <td style="width: 40%; text-align: center;">
                [KOTA_DINAS], [TANGGAL_TERBIT]<br>
                <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong>
                <div style="height: 50px;"></div>
                <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                [PIMPINAN_PANGKAT]<br>
                NIP. [PIMPINAN_NIP]
            </td>
        </tr>
    </tbody>
</table>
HTML;

        foreach([1, 2, 3, 4] as $id) {
            $perizinan = JenisPerizinan::find($id);
            if($perizinan) {
                $perizinan->template_html = $html;
                $perizinan->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
