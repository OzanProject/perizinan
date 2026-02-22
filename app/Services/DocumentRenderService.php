<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentRenderService
{
  public function renderHtml(Perizinan $perizinan, bool $isPdf = false): string
  {
    // Single Source of Truth
    $content = $perizinan->snapshot_html ?: $perizinan->replaceVariables();

    // Late-stage replacement for system-wide variables (Fixes legacy snapshots)
    $dinas = $perizinan->dinas;
    $systemVars = [
      '[ALAMAT_DINAS]' => $dinas->alamat ?: 'Jl. Jenderal Sudirman No. 1, ' . ($dinas->kabupaten ?? 'Garut'),
      '[KOTA_DINAS]' => $dinas->kabupaten ?? 'Garut',
      '[PROVINSI_DINAS]' => $dinas->provinsi ?? 'Jawa Barat',
      '[LOGO_DINAS]' => $this->toBase64Public($dinas->logo),
      '[WATERMARK_LOGO]' => $this->toBase64Public($dinas->watermark_img ?: $dinas->logo),
      '[PIMPINAN_NAMA]' => $perizinan->pimpinan_nama ?: ($dinas->pimpinan_nama ?: '............................'),
      '[PIMPINAN_JABATAN]' => $perizinan->pimpinan_jabatan ?: ($dinas->pimpinan_jabatan ?: 'KEPALA DINAS PENDIDIKAN'),
      '[PIMPINAN_NIP]' => $perizinan->pimpinan_nip ?: ($dinas->pimpinan_nip ?: '............................'),
    ];

    foreach ($systemVars as $tag => $val) {
      if ($val) {
        $content = str_replace($tag, $val, $content);
      }
    }

    if ($perizinan->document_hash) {
      $content .= $this->generateQrBlock($perizinan);
    }

    $watermarkHtml = '';
    if ($dinas && $dinas->watermark_enabled) {
      $base64 = $systemVars['[WATERMARK_LOGO]'];
      if ($base64) {
        $watermarkHtml = "<img src='{$base64}' class='watermark'>";
      }
    }

    $style = $this->getStyle($isPdf);

    return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            {$style}
        </head>
        <body>
            <div class='page'>
                {$watermarkHtml}
                <div class='document-content'>
                    {$content}
                </div>
            </div>
        </body>
        </html>
        ";
  }

  private function generateQrBlock(Perizinan $perizinan): string
  {
    $verifyUrl = route('perizinan.verify', $perizinan->document_hash);

    $qrSvg = base64_encode(
      QrCode::format('svg')
        ->size(90)
        ->margin(0)
        ->generate($verifyUrl)
    );

    return "
        <div style='margin-top:25px;text-align:right;font-size:8pt;'>
            <div>Verify Authenticity:</div>
            <img src='data:image/svg+xml;base64,{$qrSvg}' width='80'>
            <div style='font-family:monospace;font-size:7pt;'>
                " . substr($perizinan->document_hash, 0, 16) . "...
            </div>
        </div>
        ";
  }

  public function storePermanentPdf(Perizinan $perizinan): string
  {
    $html = $this->renderHtml($perizinan, true);

    $pdf = Pdf::loadHTML($html)
      ->setPaper('a4', 'portrait')
      ->setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true
      ]);

    $folder = 'issued_pdfs/' . date('Y/m');

    if (!Storage::disk('local')->exists($folder)) {
      Storage::disk('local')->makeDirectory($folder);
    }

    $filename = "{$perizinan->id}_" . time() . ".pdf";
    $path = "{$folder}/{$filename}";

    Storage::disk('local')->put($path, $pdf->output());

    return $path;
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

  private function getStyle(bool $isPdf): string
  {
    $commonStyle = "
            body { 
                font-family: 'Times New Roman', Times, serif; 
                font-size: 12pt; 
                line-height: 1.5; 
                margin: 0; 
                padding: 0; 
                color: #000;
            }
            .page { position: relative; width: 100%; box-sizing: border-box; }
            .document-content { position: relative; z-index: 10; width: 100%; }
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                width: 400px;
                margin-top: -200px; /* Half of width for stable centering */
                margin-left: -200px;
                opacity: 0.05;
                z-index: -1;
            }
            
            /* Professional Kop Surat Stabilization */
            .kop-table { width: 100%; border-collapse: collapse; border-bottom: 4px double #000; margin-bottom: 20px; }
            .kop-table td { vertical-align: middle; }
            .logo-kop { width: 80px; height: 80px; object-fit: contain; }
            .title-center { text-align: center; }
            
            table { width: 100%; border-collapse: collapse; }
            td { vertical-align: top; }
        ";

    if ($isPdf) {
      return "
            <style>
            @page { size: A4 portrait; margin: 3cm 2.5cm 2.5cm 2.5cm; }
            {$commonStyle}
            </style>
            ";
    }

    return "
        <style>
        body { background: #f1f5f9; padding: 40px 0; }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 3cm 2.5cm 2.5cm 2.5cm;
            background: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        {$commonStyle}
        /* Override for browser to ensure watermark stays relative to page */
        .watermark { position: absolute; z-index: 1; }
        </style>
        ";
  }
}