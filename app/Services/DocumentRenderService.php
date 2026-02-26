<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null, $paperSize = null, $orientation = null): string
    {
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        // Use the user-designed template from the template editor
        $body = $p->replaceVariables();

        $pageCss = $this->buildPageCss($preset, $paperSize, $orientation);

        return '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
          ' . $pageCss . '
          body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 10.5pt; 
            line-height: 1.35; 
            color: #000; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact;
          }
          .certificate-wrapper {
            position: relative;
            width: 100%;
            height: 99.5%; /* Slight buffer to prevent rounding-error page breaks */
            overflow: hidden;
            display: block;
            box-sizing: border-box;
          }
          table { border-collapse: collapse; page-break-inside: avoid; }
          td { vertical-align: top; padding: 1px 0; }
          img { max-width: 100%; page-break-inside: avoid; }
          .signature-block { page-break-inside: avoid; margin-top: 10px; }
          
          /* Force single page rendering */
          * { box-sizing: border-box; }
        </style>
        </head><body><div class="certificate-wrapper">' . $body . '</div></body></html>';
    }

    public function generatePdf(Perizinan $p, $paperSize = null, $orientation = null)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        $html = $this->renderHtml($p, $preset, $paperSize, $orientation);

        $paperSize = $paperSize ?: ($preset->paper_size ?? 'a4');
        $orientation = $orientation ?: ($preset->orientation ?? 'portrait');

        // DomPDF doesn't natively support F4. We define it manually (210mm x 330mm in points).
        // 1mm = 2.83465 points
        if (strtoupper($paperSize) === 'F4') {
            $paperSize = [0, 0, 595.28, 935.43]; // 210mm x 330mm
        }

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSize, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
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

        // Sanitize: replace spaces with dash and remove special characters
        $name = str_replace(' ', '-', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);

        return $name;
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

    private function buildPageCss($preset, $paperSizeOverride = null, $orientationOverride = null): string
    {
        $size = 'A4 portrait';
        $margin = '10mm 10mm 10mm 10mm';

        $paperSize = strtoupper($paperSizeOverride ?: ($preset->paper_size ?? 'a4'));
        $orientation = strtolower($orientationOverride ?: ($preset->orientation ?? 'portrait'));

        if ($paperSize === 'F4') {
            $size = "210mm 330mm {$orientation}";
        } else {
            $size = "{$paperSize} {$orientation}";
        }

        if ($preset) {
            // Use Null Coalesce (??) to allow 0 constant value
            // Fallback to 1.0 cm if null
            $mt = $preset->margin_top ?? 1.0;
            $mr = $preset->margin_right ?? 1.0;
            $mb = $preset->margin_bottom ?? 1.0;
            $ml = $preset->margin_left ?? 1.0;

            $margin = "{$mt}cm {$mr}cm {$mb}cm {$ml}cm";
        }

        return "
        @page {
            size: {$size};
            margin: {$margin};
        }
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