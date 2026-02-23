<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentRenderService
{
  public function renderHtml(Perizinan $p): string
  {
    $logo = $this->toBase64Public($p->dinas->logo ?? null);

    return "
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset='UTF-8'>
        " . $this->style() . "
        </head>
        <body>

        <table class='kop'>
            <tr>
                <td width='90'>" . ($logo ? "<img src='{$logo}' class='logo'>" : "") . "</td>
                <td class='kop-text'>
                    <div class='kop-1'>PEMERINTAH KABUPATEN GARUT</div>
                    <div class='kop-2'>DINAS PENDIDIKAN</div>
                    <div class='kop-3'>" . $p->dinas->alamat . "</div>
                </td>
            </tr>
        </table>

        <br>

        <div class='judul'>SURAT KETERANGAN IZIN MEMIMPIN</div>
        <div class='nomor'>Nomor : " . $p->nomor_surat . "</div>

        <br><br>

        <div class='isi'>

        <strong>Dasar :</strong>
        <ol>
            <li>" . $p->dasar_1 . "</li>
            <li>" . $p->dasar_2 . "</li>
            <li>" . $p->dasar_3 . "</li>
        </ol>

        <p>
        Berdasarkan hal tersebut di atas, maka dengan ini Kepala Dinas Pendidikan
        Kabupaten Garut menerangkan bahwa :
        </p>

        <table class='data'>
            <tr><td width='230'>Nama</td><td>: " . $p->nama . "</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: " . $p->ttl . "</td></tr>
            <tr><td>Pendidikan Terakhir</td><td>: " . $p->pendidikan . "</td></tr>
            <tr><td>Jabatan</td><td>: " . $p->jabatan . "</td></tr>
            <tr><td>Unit Kerja</td><td>: " . $p->unit_kerja . "</td></tr>
            <tr><td>NPSN</td><td>: " . $p->npsn . "</td></tr>
            <tr><td>Alamat</td><td>: " . $p->alamat . "</td></tr>
        </table>

        <br>

        <p>
        Adalah benar sebagai " . $p->jabatan . " di " . $p->unit_kerja . "
        yang diangkat oleh " . $p->yayasan . ".
        </p>

        <p>
        Keterangan ini berlaku selama 2 (dua) tahun dengan ketentuan
        tidak ada perubahan terhadap penugasan.
        </p>

        <p>
        Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        </div>

        <br><br><br>

        <table width='100%'>
        <tr>
            <td width='60%'></td>
            <td class='ttd'>
                Dikeluarkan : Garut<br>
                Pada Tanggal : " . $p->tanggal_surat . "<br><br><br>
                <strong>KEPALA DINAS</strong><br><br><br><br>
                " . $p->dinas->pimpinan_nama . "<br>
                " . $p->dinas->pimpinan_pangkat . "<br>
                NIP. " . $p->dinas->pimpinan_nip . "
            </td>
        </tr>
        </table>

        " . $this->qrBlock($p) . "

        </body>
        </html>
        ";
  }

  public function generatePdf(Perizinan $p)
  {
    $pdf = Pdf::loadHTML($this->renderHtml($p))
      ->setPaper('a4')
      ->setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => false,
        'defaultFont' => 'DejaVu Sans',
      ]);

    return $pdf->stream('surat.pdf');
  }

  private function qrBlock($p)
  {
    if (!$p->document_hash)
      return '';

    $url = route('perizinan.verify', $p->document_hash);

    $qr = base64_encode(
      QrCode::format('svg')->size(90)->margin(0)->generate($url)
    );

    return "
        <div class='qr'>
            <div>Verifikasi Dokumen:</div>
            <img src='data:image/svg+xml;base64,{$qr}' width='80'>
            <div class='qr-text'>" . substr($p->document_hash, 0, 16) . "...</div>
        </div>
        ";
  }

  private function style()
  {
    return "
        <style>

        @page {
            size: A4;
            margin: 25mm 25mm 25mm 25mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }

        .kop {
            width: 100%;
            border-bottom: 4px double #000;
        }

        .kop-text {
            text-align: center;
        }

        .kop-1 {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-2 {
            font-size: 13pt;
            font-weight: bold;
        }

        .kop-3 {
            font-size: 10pt;
        }

        .logo {
            width: 75px;
            height: 75px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
        }

        .nomor {
            text-align: center;
        }

        .isi {
            text-align: justify;
        }

        .data td {
            padding: 3px 0;
        }

        .ttd {
            text-align: left;
            font-size: 11pt;
        }

        .qr {
            margin-top: 30px;
            text-align: right;
            font-size: 8pt;
        }

        .qr-text {
            font-family: monospace;
            font-size: 7pt;
        }

        table, tr, td {
            page-break-inside: avoid;
        }

        </style>
        ";
  }

  private function toBase64Public(?string $path): ?string
  {
    if (!$path || !Storage::disk('public')->exists($path)) {
      return null;
    }

    $fullPath = Storage::disk('public')->path($path);
    $type = pathinfo($fullPath, PATHINFO_EXTENSION);
    $data = base64_encode(file_get_contents($fullPath));

    return "data:image/{$type};base64,{$data}";
  }
}