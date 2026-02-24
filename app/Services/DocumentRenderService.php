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
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        // Use the user-designed template from the template editor
        $body = $p->replaceVariables();

        return '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
          body { font-family: DejaVu Sans, sans-serif; font-size: 12pt; line-height: 1.6; color: #000; }
          table { border-collapse: collapse; }
          td { vertical-align: top; padding: 2px 0; }
          img { max-width: 100%; }
        </style>
        </head><body>' . $body . $this->qrBlock($p) . '</body></html>';
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

    public function storePermanentPdf(Perizinan $perizinan): string
    {
        $html = $this->renderHtml($perizinan);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        $folder = 'issued_pdfs/' . date('Y/m');
        if (!Storage::disk('local')->exists($folder)) {
            Storage::disk('local')->makeDirectory($folder);
        }

        $filename = "{$perizinan->id}_" . time() . ".pdf";
        $path = "{$folder}/{$filename}";

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
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