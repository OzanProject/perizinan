<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null, $paperSize = null, $orientation = null): string
    {
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        $body = $p->replaceVariables();

        // Pembersih Spasi Gaib CKEditor
        $body = preg_replace('/(<p>(&nbsp;|\s|<br\s*\/?>)*<\/p>\s*)+$/i', '', $body);
        $body = preg_replace('/(<br\s*\/?>\s*)+$/i', '', $body);

        $pageCss = $this->buildPageCss($preset, $paperSize, $orientation);
        $padding = $this->getContentPadding($preset, $orientation);

        // SUNTIKAN PAKSA: Jika Landscape, kecilkan font jadi 9.5pt agar tabel muat 1 lembar di Hosting!
        $isLandscape = strtolower($orientation ?: ($preset->orientation ?? 'portrait')) === 'landscape';
        $fontSize = $isLandscape ? '9.5pt' : '10.5pt';

        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                ' . $pageCss . '
                
                body { 
                    font-family: "Times New Roman", Times, serif; 
                    font-size: ' . $fontSize . ' !important; 
                    line-height: 1.1; /* Line height super rapat */
                    color: #000; 
                    margin: 0; 
                    padding: ' . $padding . '; 
                }

                figure { margin: 0; padding: 0; }
                figure.image { display: block !important; width: 100% !important; text-align: center !important; margin-bottom: 5px !important; clear: both !important; }
                figure.image img { display: inline-block !important; margin: 0 auto !important; max-width: 100%; height: auto; }

                /* Paksaan Spasi Paragraf */
                p { clear: both; margin-top: 0; margin-bottom: 4px; }
                p:last-child { margin-bottom: 0 !important; }

                /* Paksaan Tabel */
                table { border-collapse: collapse; width: 100%; page-break-inside: avoid; margin-bottom: 5px; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                td { vertical-align: top; padding: 1px 3px; border: none; }
                
                .signature-block { page-break-inside: avoid; margin-top: 5px; }
            </style>
        </head>
        <body>
            ' . $body . '
        </body>
        </html>';
    }

    public function generatePdf(Perizinan $p, $paperSize = null, $orientation = null)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        $paperSize = $paperSize ?: ($preset->paper_size ?? 'A4');
        $orientation = $orientation ?: ($preset->orientation ?? 'portrait');

        $html = $this->renderHtml($p, $preset, $paperSize, $orientation);

        if (strtoupper($paperSize) === 'F4') {
            $paperSizeArray = [0, 0, 595.28, 935.43];
        } else {
            $paperSizeArray = strtolower($paperSize);
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSizeArray, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'Times New Roman',
                'dpi' => 96,
            ]);

        $filename = $this->generateStandardFilename($p) . '.pdf';

        return $pdf->stream($filename);
    }

    public function generateStandardFilename(Perizinan $p): string
    {
        $p->load(['lembaga', 'jenisPerizinan']);
        $lembaga = $p->lembaga->nama_lembaga ?? 'Lembaga';
        $jenis = $p->jenisPerizinan->nama ?? 'Perizinan';
        $tanggal = date('d-m-Y');

        $name = "{$lembaga}-{$jenis}-{$tanggal}";
        $name = str_replace(' ', '-', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);

        return $name;
    }

    public function storePermanentPdf(Perizinan $perizinan): string
    {
        $html = $this->renderHtml($perizinan);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
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

    private function buildPageCss($preset, $paperSizeOverride = null, $orientationOverride = null): string
    {
        $paperSize = strtoupper($paperSizeOverride ?: ($preset->paper_size ?? 'A4'));
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        if ($paperSize === 'F4') {
            $size = "210mm 330mm {$orientation}";
        } else {
            $size = "{$paperSize} {$orientation}";
        }

        return "@page { size: {$size}; margin: 0px; }";
    }

    private function getContentPadding($preset, $orientationOverride): string
    {
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        if ($preset) {
            $mt = $preset->margin_top ?? ($orientation === 'landscape' ? 1.0 : 2.5);
            $mr = $preset->margin_right ?? ($orientation === 'landscape' ? 2.5 : 3.0);
            $mb = $preset->margin_bottom ?? ($orientation === 'landscape' ? 0.5 : 1.5);
            $ml = $preset->margin_left ?? ($orientation === 'landscape' ? 2.5 : 3.0);

            // FORCE OVERRIDE: Jika di database setting margin atas/bawahnya terlalu besar untuk landscape, KITA PAKSA TIPIS!
            if ($orientation === 'landscape') {
                if ($mt > 1.2)
                    $mt = 1.0;
                if ($mb > 1.0)
                    $mb = 0.5; // Batas bawah maksimal hanya 0.5cm
            }

            return "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        if ($orientation === 'landscape')
            return "1.0cm 2.5cm 0.5cm 2.5cm";
        return "2.5cm 3cm 1.5cm 3cm";
    }
}