<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

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

<p style="text-align: center; margin: 0 0 15px 0;">
    <span style="font-size: 11.5pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">SURAT KETERANGAN DAFTAR ULANG (HER-REGISTRASI)</span>
    <br>
    <span style="font-size: 10.5pt; font-weight: bold;">Nomor: [NOMOR_SURAT]</span>
</p>

<p style="margin: 0 0 8px 0;">Kepala Dinas Pendidikan Kabupaten [KOTA_DINAS], dengan ini menerangkan :</p>

<table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 15px;">
    <tbody>
        <tr><td style="width: 25%; padding: 2px 0;">Nama Lembaga</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 73%; font-weight: bold; padding: 2px 0;">[NAMA_LEMBAGA]</td></tr>
        <tr><td style="padding: 2px 0;">Nama Pimpinan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PIMPINAN]</td></tr>
        <tr><td style="padding: 2px 0;">Nama Penyelenggara</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:NAMA_PENYELENGGARA]</td></tr>
        <tr><td style="padding: 2px 0;">NPSN</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NPSN]</td></tr>
        <tr><td style="padding: 2px 0;">Alamat Lembaga</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[ALAMAT_LEMBAGA]</td></tr>
    </tbody>
</table>

<p style="margin: 0 0 8px 0;">Telah memiliki Izin Pendirian Kepala Dinas Kabupaten [KOTA_DINAS]:</p>

<table style="width: 95%; border-collapse: collapse; margin-left: 20px; margin-bottom: 15px;">
    <tbody>
        <tr><td style="width: 25%; padding: 2px 0;">Nomor</td><td style="width: 2%; padding: 2px 0;">:</td><td style="width: 73%; padding: 2px 0;">[DATA:NOMOR_IZIN]</td></tr>
        <tr><td style="padding: 2px 0;">Tanggal</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[DATA:TANGGAL_IZIN]</td></tr>
    </tbody>
</table>

<p style="text-align: justify; margin: 0 0 15px 0; line-height: 1.4;">
    Berdasarkan kelengkapan proposal permohonan izin operasional PKBM, Lembaga tersebut telah melakukan daftar ulang (her-registrasi) pada Dinas Pendidikan Kabupaten [KOTA_DINAS]. Surat keterangan ini berlaku selama <strong>[MASA_BERLAKU]</strong>.
</p>

<table style="width: 100%; border-collapse: collapse; page-break-inside: avoid; margin-top: 25px;">
    <tbody>
        <tr>
            <td style="width: 60%; vertical-align: bottom; text-align: left; padding-left: 15px;">
                [QR_CODE]<br>
                <span style="font-size: 8px; color: #555; margin-top: 2px; margin-left: 3px;">Scan untuk<br>Verifikasi Asli</span>
            </td>
            <td style="width: 40%; text-align: left; padding-left: 25px;">
                [KOTA_DINAS], [TANGGAL_TERBIT]<br>
                <strong style="text-transform: uppercase;">[PIMPINAN_JABATAN]</strong>
                <div style="height: 65px;"></div>
                <strong><u>[PIMPINAN_NAMA]</u></strong><br>
                [PIMPINAN_PANGKAT]<br>
                NIP. [PIMPINAN_NIP]
            </td>
        </tr>
    </tbody>
</table>
HTML;

        foreach([1, 2, 3, 4] as $id) {
            $perizinan = App\Models\JenisPerizinan::find($id);
            if($perizinan) {
                $perizinan->template_html = $html;
                $perizinan->save();
            }
        }
        echo "Template updated!\n";
