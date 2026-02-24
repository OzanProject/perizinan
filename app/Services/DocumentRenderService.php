<?php

namespace App\Services;

use App\Models\Perizinan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentRenderService
{
    public function renderHtml(Perizinan $p, $preset = null): string
    {
        $p->load(['lembaga', 'jenisPerizinan', 'dinas']);

        // Use the user-designed template from the template editor
        $body = $p->replaceVariables();

        $pageCss = $this->buildPageCss($preset);

        return '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
          ' . $pageCss . '
          body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; line-height: 1.4; color: #000; margin: 0; padding: 0; }
          table { border-collapse: collapse; page-break-inside: avoid; }
          td { vertical-align: top; padding: 2px 0; }
          img { max-width: 100%; page-break-inside: avoid; }
          .signature-block { page-break-inside: avoid; breaks-inside: avoid; }
        </style>
        </head><body>' . $body . '</body></html>';
    }

    public function generatePdf(Perizinan $p)
    {
        $preset = \App\Models\CetakPreset::where('dinas_id', $p->dinas_id)
            ->where('is_active', true)
            ->first();

        $html = $this->renderHtml($p, $preset);

        $paperSize = $preset->paper_size ?? 'a4';
        $orientation = $preset->orientation ?? 'portrait';

        $pdf = Pdf::loadHTML($html)
            ->setPaper($paperSize, $orientation)
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
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

    private function buildPageCss($preset): string
    {
        $size = 'A4 portrait';
        $margin = '20mm 20mm 20mm 20mm';

        if ($preset) {
            $paperSize = $preset->paper_size ?: 'a4';
            $orientation = $preset->orientation ?: 'portrait';
            $size = "{$paperSize} {$orientation}";

            $mt = $preset->margin_top ?: 20;
            $mr = $preset->margin_right ?: 20;
            $mb = $preset->margin_bottom ?: 20;
            $ml = $preset->margin_left ?: 20;
            $margin = "{$mt}mm {$mr}mm {$mb}mm {$ml}mm";
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